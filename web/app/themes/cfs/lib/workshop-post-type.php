<?php
/**
 * Workshop post type
 */

namespace Firebelly\PostTypes\Workshop;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use jamiehollern\eventbrite\Eventbrite; // see https://github.com/jamiehollern/eventbrite

$cpt = new PostType('workshop', [
  'taxonomies' => ['workshop_type', 'workshop_series'],
  'supports'   => ['title', 'editor', 'thumbnail'],
  'has_archive' => true,
  'rewrite'    => ['with_front' => false, 'slug' => 'workshops'],
]);
$cpt->taxonomy('workshop_type');
$cpt->taxonomy([
  'name'     => 'workshop_series',
  'plural'   => 'Workshop Series',
]);

$cpt->columns()->set([
    'cb' => '<input type="checkbox" />',
    'title' => __('Title'),
    'workshop_series' => __('Series'),
    // 'workshop_type' => __('Type'),
    'date_start' => __('Date Start'),
    'date_end' => __('Date End'),
    'time' => __('Time'),
    'featured' => __('Featured'),
    // 'date' => __('Date')
]);
$cpt->columns()->sortable([
    'date_start' => ['_cmb2_date_start', true],
    'date_end' => ['_cmb2_date_end', true]
]);
$cpt->columns()->populate('date_start', function($column, $post_id) {
  if ($val = get_post_meta($post_id, '_cmb2_date_start', true)) {
    echo date('Y-m-d', $val);
  } else {
    echo 'n/a';
  }
});
$cpt->columns()->populate('date_end', function($column, $post_id) {
  if ($val = get_post_meta($post_id, '_cmb2_date_end', true)) {
    echo date('Y-m-d', $val);
  } else {
    echo 'n/a';
  }
});
$cpt->columns()->populate('time', function($column, $post_id) {
  echo get_post_meta($post_id, '_cmb2_time', true);
});
$cpt->columns()->populate('featured', function($column, $post_id) {
  echo (get_post_meta($post_id, '_cmb2_featured', true)) ? '&check;' : '';
});

/**
 * CMB2 custom fields
 */
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );
function metaboxes() {
  $prefix = '_cmb2_';

  $workshop_info = new_cmb2_box([
    'id'            => 'workshop_info',
    'title'         => __( 'Workshop Info', 'cmb2' ),
    'object_types'  => ['workshop'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $workshop_info->add_field([
    'name'      => 'Details',
    'id'        => $prefix . 'workshop_details',
    'type'      => 'wysiwyg',
    'options' => [
      'textarea_rows' => 10,
    ]
  ]);
  $workshop_info->add_field([
    'name'        => 'Cost',
    'id'          => $prefix . 'cost',
    'desc'        => 'e.g. $5.00',
    'type'        => 'text_medium',
  ]);
  $workshop_info->add_field([
    'name'        => 'Eventbrite URL',
    'id'          => $prefix . 'eventbrite_url',
    'type'        => 'text',
    'description' => 'e.g. https://www.eventbrite.com/e/xxxx',
  ]);
  $workshop_info->add_field([
    'name'        => 'Tickets Available',
    'id'          => $prefix . 'tickets_available',
    'type'        => 'text_small',
    'attributes'  => [
      'type'      => 'number',
      'pattern'   => '\d*',
    ],
  ]);

  $workshop_when = new_cmb2_box([
    'id'            => 'workshop_when',
    'title'         => __( 'Workshop Date & Time', 'cmb2' ),
    'object_types'  => ['workshop'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $workshop_when->add_field([
    'name'      => 'Start Date',
    'id'        => $prefix . 'date_start',
    'type'      => 'text_date_timestamp',
  ]);
  $workshop_when->add_field([
    'name'      => 'End Date',
    'id'        => $prefix . 'date_end',
    'type'      => 'text_date_timestamp',
  ]);
  $workshop_when->add_field([
    'name'      => 'Time',
    'id'        => $prefix . 'time',
    'desc'      => 'e.g. 5:00pm to 8:00pm',
    'type'      => 'text_medium',
  ]);
  $workshop_when->add_field([
    'name'      => 'Application Deadline',
    'id'        => $prefix . 'application_deadline',
    'type'      => 'text_datetime_timestamp'
  ]);
}

/**
 * Get Workshops
 */
function get_workshops($options=[]) {
  if (empty($options['num_posts'])) $options['num_posts'] = get_option('posts_per_page');
  if (!empty($_REQUEST['past_workshops'])) $options['past_workshops'] = 1; // support for AJAX requests
  $args = [
    'numberposts' => $options['num_posts'],
    'post_type'   => 'workshop',
    'meta_key'    => '_cmb2_date_start',
    'orderby'     => 'meta_value_num',
  ];
  if (!empty($options['tax_query'])) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'workshop_type',
        'field'    => 'id',
        'terms'    => $options['workshop_type']
      ]
    ];
  }
  // Make sure we're only pulling upcoming or past workshops
  $args['order'] = !empty($options['past_workshops']) ? 'DESC' : 'ASC';
  $args['meta_query'] = [
    [
      'key'     => '_cmb2_date_end',
      'value'   => current_time('timestamp'),
      'compare' => (!empty($options['past_workshops']) ? '<=' : '>')
    ],
    [
      'key' => '_cmb2_workshop_type',
    ]
  ];

  // Display all matching workshops using article-workshop.php
  $workshop_posts = get_posts($args);
  if (!$workshop_posts) return false;
  $output = '';
  foreach ($workshop_posts as $workshop_post):
    ob_start();
    include(locate_template('templates/article-workshop.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}

function get_workshop_by_slug($slug){
  $posts = get_posts([
    'name' => $slug,
    'posts_per_page' => 1,
    'post_type' => 'workshop',
    'post_status' => 'publish'
  ]);
  if(!$posts) {
    return false;
  }
  return $posts[0];
}

/**
 * Get featured workshops
 * @param  array  $args Extra args for get_posts()
 */
function get_featured_workshops($args=[]) {
  array_merge($featured_args = [
    'post_type'  => 'workshop',
    'order'      => 'ASC',
    'orderby'    => 'meta_value_num',
    'meta_key'   => '_cmb2_date_start',
    'meta_query' => [
      [
        'key'    => '_cmb2_featured',
        'value'  => 'on',
      ],
    ]
  ], $args);
  return get_posts($featured_args);
}

function get_registration_button($workshop_post) {
  $output = '';
  if ($workshop_post->sold_out) {
    $output = '<a class="button black disabled" href="#">Sold Out</a>';
  } elseif (!empty($workshop_post->meta['_cmb2_eventbrite_url'][0])
                && $workshop_post->meta['_cmb2_date_end'][0] > time()) {
    $output = '<a class="button black" href="' . $workshop_post->meta['_cmb2_eventbrite_url'][0] .'">Register</a>';
  }
  return $output;
}

function get_series($post) {
  $series = \Firebelly\Utils\get_first_term($post, 'workshop_series');
  return (empty($series)) ? '' : $series;
}

// daily cronjob to import new eventbrite events
// add_action('wp', __NAMESPACE__ . '\\activate_eventbrite_import');
function activate_eventbrite_import() {
  if (!wp_next_scheduled('eventbrite_import')) {
    wp_schedule_event(current_time('timestamp'), 'twicedaily', 'eventbrite_import');
  }
}
// add_action( 'eventbrite_import', __NAMESPACE__ . '\\eventbrite_import' );
function fb_eventbrite_import() {
  global $wpdb;

  // Pull in libraries for media_sideload_image()
  require_once(ABSPATH . 'wp-admin/includes/media.php');
  require_once(ABSPATH . 'wp-admin/includes/file.php');
  require_once(ABSPATH . 'wp-admin/includes/image.php');

  // Cache categories
  $workshop_series = get_terms(['taxonomy' => 'workshop_series', 'hide_empty' => 0]);
  // $workshop_types = get_terms(['taxonomy' => 'workshop_types', 'hide_empty' => 0]);

  $workshop_series_titles = []; // Array to check event title to know if we should strip out series title before inserting post
  foreach($workshop_series as $workshop_series_cat)
    $workshop_series_titles[] = $workshop_series_cat->name;

  // Connect to Eventbrite API (uses .env token)
  $eventbrite = new Eventbrite(getenv('EVENTBRITE_OAUTH_TOKEN'));

  try {

    // Get all user events
    $events = $eventbrite->get('users/me/owned_events/', ['expand' => 'ticket_classes']);
    $imported = 0;

    foreach ( $events['body']['events'] as $event ) {
      $imported++;
      if ($imported>15) continue;
      $workshop_id = $event_workshop_series = null;
      $event_exists = $wpdb->get_var($wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s", '_cmb2_eventbrite_id', $event['id'] ));
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

        $event_title = $event['name']['text'];
        // Pull workshop series title if colon in title
        if (strpos($event_title, ':')!==FALSE) {
          $event_workshop_series = substr($event_title, 0, strpos($event_title, ':'));
          // If matches a workshop series, strip from the title
          if (in_array($event_workshop_series, $workshop_series_titles)) {
            $event_title = trim(substr($event_title, strpos($event_title, ':')+1));
          }
        }

        // Insert workshop post
        $new_post = [
          'post_status' => 'publish',
          'post_type' => 'workshop',
          'post_author' => 1,
          'post_date' => date('Y-m-d H:i:s', $publishedAt),
          'post_date_gmt' => date('Y-m-d H:i:s', $publishedAt),
          'post_title' => sanitize_title($event_title),
          'post_content' => $event_html,
        ];
        $new_workshop_id = wp_insert_post($new_post);

        if ($new_workshop_id) {
          // Download and attach image
          if (!empty($event['logo']['original']['url'])) {
            media_sideload_image($event['logo']['original']['url'], $new_workshop_id);
            // Get ID of new attachment to make it featured image (this is *almost* a feature of WP atm: https://core.trac.wordpress.org/ticket/19629, at some point we can change 'src' to 'id' above)
            $attachments = get_posts(['numberposts'=>'1', 'post_parent'=>$new_workshop_id, 'post_type'=>'attachment']); // Get attachment posts to find last inserted
            if (count($attachments) > 0) {
              // Set image as the post thumbnail
              set_post_thumbnail($new_workshop_id, $attachments[0]->ID);
            }
          }

          // Set workshop_series category if we were able to extract a series title (colon in the title)
          if (!empty($event_workshop_series)) {
            $cat_ids = [];
            foreach($workshop_series as $workshop_series_cat) {
              if ($workshop_series_cat->name == $event_workshop_series) {
                $cat_ids[] = (int)$workshop_series_cat->term_id;
              }
            }
            if (!empty($cat_ids)) {
              wp_set_object_terms($new_workshop_id, $cat_ids, 'workshop_series');
            }
          }

          $workshop_id = $new_workshop_id;
        }
      } else {
        $workshop_id = $wpdb->get_var($wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s", '_cmb2_eventbrite_id', $event['id'] ));
      }

      if ($workshop_id) {
        // Set (or update if existing event) various custom fields
        update_post_meta($workshop_id, '_cmb2_eventbrite_id', $event['id'] );
        update_post_meta($workshop_id, '_cmb2_eventbrite_url', $event['url']);
        // update_post_meta($workshop_id, '_cmb2_application_deadline', $event['']);
        update_post_meta($workshop_id, '_cmb2_date_start', strtotime($event['start']['local']));
        update_post_meta($workshop_id, '_cmb2_date_end', strtotime($event['end']['local']));

        // Build time string
        $time_txt = date('g:ia', strtotime($event['start']['local']));
        // If same day, pull end time
        if (date('m-d-y', strtotime($event['start']['local'])) == date('m-d-y', strtotime($event['end']['local']))) {
          $time_txt .= ' – ' . date('g:ia', strtotime($event['end']['local']));
        }
        update_post_meta($workshop_id, '_cmb2_time', $time_txt);

        // If not free event, get ticket info
        if (empty($event['is_free'])) {
          // Determine if any tickets left
          $tickets_available = 0;
          $ticket_price_arr = [];
          foreach($event['ticket_classes'] as $ticket_class) {
            if ($ticket_class['on_sale_status']=='AVAILABLE') {
              $tickets_available += $ticket_class['quantity_total'] - $ticket_class['quantity_sold'];
            }
            if (!empty($ticket_class['cost']))
              $ticket_price_arr[] = $ticket_class['cost']['major_value'];
          }
          update_post_meta($workshop_id, '_cmb2_tickets_available', $tickets_available);

          // Set price text to range of ticket prices (or single price)
          if (!empty($ticket_price_arr)) {
            $price_txt = '$' . min($ticket_price_arr);
            if (count($ticket_price_arr) > 1 && min($ticket_price_arr) != max($ticket_price_arr)) {
              $price_txt .= ' – $' . max($ticket_price_arr);
            }
            update_post_meta($workshop_id, '_cmb2_cost', $price_txt);
          }
        }
      }
    }

  } catch ( Exception $e ) {
    echo 'Error fetching events: ' . $e->getMessage();
    wp_mail( 'developer@firebellydesign.com', 'CFS error', 'Error fetching events: ' . $e->getMessage() );
  }
}