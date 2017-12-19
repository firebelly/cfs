<?php
/**
 * Eventbrite Importer - CFS - Firebelly 2017
 */

use jamiehollern\eventbrite\Eventbrite; // see https://github.com/jamiehollern/eventbrite

class EventbriteImporter {
  private $log = ['error' => [], 'notice' => [], 'stats' => []];
  private $workshop_series;
  private $eventbrite_workshop_type;
  private $workshop_series_titles = [];
  private $prefix = '_cmb2_';
  private $num_skipped = 0;
  private $num_updated = 0;
  private $num_imported = 0;
  private $eventbrite;
  private $force_update_meta;

  function do_import($force_update_meta=false) {
    $page = 1;
    $time_start = microtime(true);
    $this->force_update_meta = $force_update_meta;

    // Pull in libraries for media_sideload_image()
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Cache categories, pull eventbrite-event taxonomy to assign to new posts
    $this->workshop_series = get_terms(['taxonomy' => 'workshop_series', 'hide_empty' => 0]);
    $this->eventbrite_workshop_type = get_term_by('slug', 'eventbrite-event', 'workshop_type');
    foreach($this->workshop_series as $workshop_series_cat)
      $this->workshop_series_titles[] = $workshop_series_cat->name;

    // Connect to Eventbrite API (using token from .env)
    $this->eventbrite = new Eventbrite(getenv('EVENTBRITE_OAUTH_TOKEN'));

    try {

      // Get all user events
      $events = $this->eventbrite->get('users/me/owned_events/');
      $this->log['notice'][] = 'Eventbrite API: '.count($events['body']['events']).' total events found';

      // Process events, adding new Workshops, and updating ticket totals and price range for still-active events
      $this->process_events($events['body']['events']);

      // Loop through the rest of the pages (if any)
      while ($events['body']['pagination']['has_more_items']) {
        try {
          $events = $this->eventbrite->get('users/me/owned_events/', ['continuation' => $events['body']['pagination']['continuation']]);
          $this->log['notice'][] = 'Eventbrite API: processing page '.(++$page).' of '.$events['body']['pagination']['page_count'];
          $this->process_events($events['body']['events']);
        } catch ( Exception $e ) {
          $this->log['error'][] = 'Error fetching paginated events: ' . $e->getMessage();
          wp_mail( 'developer@firebellydesign.com', 'CFS error', 'Error fetching paginated events: ' . $e->getMessage() );
        }
      }

    } catch ( Exception $e ) {
      $this->log['error'][] = 'Error fetching events: ' . $e->getMessage();
      wp_mail( 'developer@firebellydesign.com', 'CFS error', 'Error fetching events: ' . $e->getMessage() );
    }

    // Build summary notices
    if ($this->num_skipped)
      $this->log['notice'][] = sprintf("Skipped %s entries", $this->num_skipped);

    if ($this->num_updated)
      $this->log['notice'][] = sprintf("Updated %s entries", $this->num_updated);

    if ($this->num_imported)
      $this->log['notice'][] = sprintf("Imported %s entries", $this->num_imported);

    $exec_time = microtime(true) - $time_start;
    $this->log['stats']['exec_time'] = sprintf("%.2f", $exec_time);

    // Build HTML version of log for js and email
    $html_log = '';
    if (!empty($this->log['notice'])) {
      $html_log .= '<h3>Notices:</h3><ul><li>' . join('</li><li>', $this->log['notice']) . '</li></ul>';
    }
    if (!empty($this->log['error'])) {
      $html_log .= '<h3>Errors:</h3><ul><li>' . join('</li><li>', $this->log['error']) . '</li></ul>';
    }
    $html_log .= '<p><b>Import took ' . $this->log['stats']['exec_time'] . ' seconds.</b></p>';
    $this->log['html_log'] = $html_log;

    // Send email report?
    $eventbrite_notifications_email = \Firebelly\SiteOptions\get_option('eventbrite_notifications_email');
    if (!empty($eventbrite_notifications_email)) {
      add_filter('wp_mail_content_type', ['EventbriteImporter', 'set_html_email']);
      wp_mail($eventbrite_notifications_email, 'CFS Eventbrite Import '.date('Y-m-d'), $this->log['html_log']);
      remove_filter('wp_mail_content_type', ['EventbriteImporter', 'set_html_email']);
    }

    return $this->log;
  }

  /**
   * Temporarily set email type as html
   */
  public static function set_html_email() {
    return 'text/html';
  }

  /**
   * Process a batch of eventbrite API events
   */
  function process_events($events) {
    global $wpdb;
    foreach ($events as $event ) {
      $update_notices = [];

      // Abort if already imported 5 workshops as PHP times out importing & resizing images (hopefully this never happens after complete import)
      // if ($this->num_imported>5) continue;

      // If event status is not "started" or "live"
      if (!$this->force_update_meta && !in_array($event['status'], ['started','live'])) {
        $this->log['notice'][] = 'Event status is "'.$event['status'].'" so skipping: "'.$event['name']['text'].'"';
        $this->num_skipped++;
        continue;
      }

      $workshop_id = $event_workshop_series = null;
      $event_title = $event['name']['text'];

      // Pull workshop series title if colon in title
      if (strpos($event_title, ':')!==FALSE) {
        $event_workshop_series = substr($event_title, 0, strpos($event_title, ':'));
        // If matches a workshop series, strip from the title
        if (in_array($event_workshop_series, $this->workshop_series_titles)) {
          $event_title = trim(substr($event_title, strpos($event_title, ':')+1));
        }
      }

      $event_exists = $wpdb->get_var($wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s", $this->prefix.'eventbrite_id', $event['id'] ));
      if (!$event_exists) {
        // Get timestamp of date event created
        $publishedAt = strtotime($event['created']);

        // Strip out crappy inline styles, unneeded classes, empty span/divs left behind, and images
        $event_html = $event['description']['html'];
        $event_html = preg_replace('/ style="(.*?)"/i','',$event_html);
        $event_html = preg_replace('/ class="(.*?)"/i','',$event_html);
        $event_html = preg_replace('~<[\/]?(div|span)>~i','',$event_html);
        $event_html = preg_replace('~<p>\s+</p>~i','',$event_html);
        $event_html = preg_replace('~<p>&nbsp;</p>~i','',$event_html);
        $event_html = preg_replace('~<br( /)?>~i',"\n",$event_html);

        // Insert workshop post
        $new_post = [
          'post_status' => 'draft',
          'post_type' => 'workshop',
          'post_author' => 1,
          'post_date' => date('Y-m-d H:i:s', $publishedAt),
          'post_date_gmt' => date('Y-m-d H:i:s', $publishedAt),
          'post_title' => $event_title,
          'post_content' => $event_html,
        ];
        $workshop_id = wp_insert_post($new_post);

        if ($workshop_id) {
          // Download and attach image
          if (!empty($event['logo']['original']['url'])) {
            media_sideload_image($event['logo']['original']['url'], $workshop_id);
            // Get ID of new attachment to make it featured image (this is *almost* a feature of WP atm: https://core.trac.wordpress.org/ticket/19629, at some point we can change 'src' to 'id' above)
            $attachments = get_posts(['numberposts'=>'1', 'post_parent'=>$workshop_id, 'post_type'=>'attachment']); // Get attachment posts to find last inserted
            if (count($attachments) > 0) {
              // Set image as the post thumbnail
              set_post_thumbnail($workshop_id, $attachments[0]->ID);
            }
          }

          // Set workshop_type as Eventbrite Event
          wp_set_object_terms($workshop_id, $eventbrite_workshop_type->term_id, 'workshop_type');

          // Set workshop_series category if we were able to extract a series title (colon in the title)
          if (!empty($event_workshop_series)) {
            $cat_ids = [];
            foreach($this->workshop_series as $workshop_series_cat) {
              if ($workshop_series_cat->name == $event_workshop_series) {
                $cat_ids[] = (int)$workshop_series_cat->term_id;
              }
            }
            if (!empty($cat_ids)) {
              wp_set_object_terms($workshop_id, $cat_ids, 'workshop_series');
            }
          }

          $this->num_imported++;
          $this->log['notice'][] = '<h3>New workshop #'.$workshop_id.' created for <a target="_blank" href="' . get_edit_post_link($workshop_id) . '">'.$event_title.'</a></h3>';
        }
      } else {
        $workshop_id = $wpdb->get_var($wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s", $this->prefix.'eventbrite_id', $event['id'] ));
        $this->num_updated++;
        // $this->log['notice'][] = 'Existing workshop #'.$workshop_id.' found for "'.$event['name']['text'].'"';
      }

      if ($workshop_id) {
        // Set (or update if existing event) various custom fields
        update_post_meta($workshop_id, $this->prefix.'eventbrite_id', $event['id'] );
        update_post_meta($workshop_id, $this->prefix.'eventbrite_url', $event['url']);
        update_post_meta($workshop_id, $this->prefix.'date_start', strtotime($event['start']['local']));
        update_post_meta($workshop_id, $this->prefix.'date_end', strtotime($event['end']['local']));

        // Event has a venue?
        if (!empty($event['venue_id'])) {
          try {
            // Pull venue details from API
            $venue = $this->eventbrite->get('venues/'.$event['venue_id'].'/');

            // Set venue and address meta fields
            update_post_meta($workshop_id, $this->prefix.'venue', $venue['body']['name']);
            $address = [
              'address-1' => $venue['body']['address']['address_1'],
              'address-2' => $venue['body']['address']['address_2'],
              'city' => $venue['body']['address']['city'],
              'state' => $venue['body']['address']['region'],
              'zip' => $venue['body']['address']['postal_code'],
            ];
            update_post_meta($workshop_id, $this->prefix.'address', $address);

            // Set lat/long, even though unused at this time
            update_post_meta($workshop_id, $this->prefix.'latitude', $venue['body']['address']['latitude']);
            update_post_meta($workshop_id, $this->prefix.'longitude', $venue['body']['address']['longitude']);

          } catch ( Exception $e ) {
            $this->log['error'][] = 'Error fetching event venue: ' . $e->getMessage();
            wp_mail( 'developer@firebellydesign.com', 'CFS error', 'Error fetching event venue: ' . $e->getMessage() );
          }
        }

        // Build time string
        $time_txt = date('g:ia', strtotime($event['start']['local']));
        // If same day, pull end time
        if (date('m-d-y', strtotime($event['start']['local'])) == date('m-d-y', strtotime($event['end']['local']))) {
          $time_txt .= ' – ' . date('g:ia', strtotime($event['end']['local']));
        }
        update_post_meta($workshop_id, $this->prefix.'time', $time_txt);

        // If not free event, get ticket info
        if (empty($event['is_free'])) {

          try {
            // Pull ticket classes for event from API
            $tickets = $this->eventbrite->get('events/'.$event['id'].'/ticket_classes/');

            // Determine if any tickets left
            $tickets_available = 0;
            $ticket_price_arr = [];
            if (!empty($tickets['body']['ticket_classes'])) {
              foreach($tickets['body']['ticket_classes'] as $ticket_class) {
                if ($ticket_class['on_sale_status']=='AVAILABLE') {
                  $tickets_available += $ticket_class['quantity_total'] - $ticket_class['quantity_sold'];
                }
                if (!empty($ticket_class['cost']))
                  $ticket_price_arr[] = $ticket_class['cost']['major_value'];
              }
            }
            update_post_meta($workshop_id, $this->prefix.'tickets_available', $tickets_available);
            $update_notices[] = 'tickets available: '.$tickets_available;

            // Set price text to range of ticket prices (or single price)
            if (!empty($ticket_price_arr)) {
              $price_txt = '$' . min($ticket_price_arr);
              if (count($ticket_price_arr) > 1 && min($ticket_price_arr) != max($ticket_price_arr)) {
                $price_txt .= ' – $' . max($ticket_price_arr);
              }
              update_post_meta($workshop_id, $this->prefix.'cost', $price_txt);
              $update_notices[] = 'cost: '.$price_txt;
            }

          } catch ( Exception $e ) {
            $this->log['error'][] = 'Error fetching event tickets: ' . $e->getMessage();
            wp_mail( 'developer@firebellydesign.com', 'CFS error', 'Error fetching event tickets: ' . $e->getMessage() );
          }

        }
        if (!empty($update_notices)) {
          $this->log['notice'][] = 'Workshop #'.$workshop_id.' <a target="_blank" href="' . get_edit_post_link($workshop_id) . '">'.$event_title.'</a> updated with ' . implode(', ', $update_notices);
        }
      }
    }
  }
}
