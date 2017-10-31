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
    'program_type' => __('Type'),
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
    'desc'        => 'e.g. 5.00',
    'type'        => 'text_small',
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

function get_workshop_date($workshop_post) {
  if (empty($workshop_post->meta)) $workshop_post->meta = get_post_meta($workshop_post->ID);
  $output = '<div class="date">';
  if (!empty($workshop_post->meta['_cmb2_date_start'])) {
    $output .= '<time datetime="' . date('Y-m-d', $workshop_post->meta['_cmb2_date_start'][0]) . '">' . date('m/j/y', $workshop_post->meta['_cmb2_date_start'][0]) . '</time>';
  }
  if (!empty($workshop_post->meta['_cmb2_date_end'])) {
    $output .= '– <time datetime="' . date('Y-m-d', $workshop_post->meta['_cmb2_date_end'][0]) . '">' . date('m/j/y', $workshop_post->meta['_cmb2_date_end'][0]) . '</time>';
  }
  if (!empty($workshop_post->meta['_cmb2_time'])) {
    $output .= ' <span class="timespan">' . $workshop_post->meta['_cmb2_time'][0] . '</span>';
  }
  $output .= '</div>';
  return $output;
}

function get_series($post) {
  $series = \Firebelly\Utils\get_first_term($post, 'workshop_series');
  return (empty($series)) ? '' : $series->name;
}

// daily cronjob to import new videos
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
  $workshop_series = get_terms('workshop_series', ['hide_empty' => 0]);
  $workshop_types = get_terms('workshop_types', ['hide_empty' => 0]);

  // Connect to Eventbrite API (uses .env token)
  $eventbrite = new Eventbrite(getenv('EVENTBRITE_OAUTH_TOKEN'));

  // $last_run = get_option( 'fb_eventbrite_import_last_run' );
  try {

    // Get all user events
    $events = $eventbrite->get('users/me/owned_events/', ['expand' => 'ticket_classes']);

    foreach ( $events['body']['events'] as $event ) {
      $workshop_id = null;
      $event_exists = $wpdb->get_var($wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s", '_cmb2_eventbrite_id', $event['id'] ));
      if (!$event_exists) {
        // Get timestamp of date event created
        $publishedAt = strtotime($event['created']);

        // Strip out crappy inline styles and empty span/divs left behind
        $event_html = $event['description']['html'];
        $event_html = preg_replace('/ style=("|\')(.*?)("|\')/i','',$event_html);
        $event_html = preg_replace('~<[\/]?(div|span)>~i','',$event_html);

        // Insert workshop post
        $video_post = [
          'post_status' => 'publish',
          'post_type' => 'workshop',
          'post_author' => 1,
          'post_date' => date('Y-m-d H:i:s', $publishedAt),
          'post_date_gmt' => date('Y-m-d H:i:s', $publishedAt),
          'post_title' => $event['name']['text'],
          'post_content' => $event_html,
        ];
        $new_workshop_id = wp_insert_post($video_post, $wp_error);

        if ($new_workshop_id) {
          // Download and attach image
          if (!empty($event['logo']['original']['url'])) {
            $attachment_src = media_sideload_image($event['logo']['original']['url'], $new_workshop_id, null, 'src');
            // Get ID of new attachment (this is *almost* a feature of WP atm: https://core.trac.wordpress.org/ticket/19629, at some point we can change 'src' to 'id' above)
            $attachment_id = \Firebelly\Media\get_attachment_id_from_src($attachment_src);
            // Make this the featured image of the new post
            set_post_thumbnail($new_workshop_id, $attachment_id);
          }

          // Set workshop_series if colon in title
          if (strpos($event['name']['text'], ':')!==FALSE) {
            $event_workshop_series = substr($event['name']['text'], 0, strpos($event['name']['text'], ':'));
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
        // update_post_meta($workshop_id, '_cmb2_application_deadline', $event['']);
        update_post_meta($workshop_id, '_cmb2_time', date('g:ia', strtotime($event['start']['local'])));
        update_post_meta($workshop_id, '_cmb2_date_end', strtotime($event['end']['local']));
        update_post_meta($workshop_id, '_cmb2_date_start', strtotime($event['start']['local']));
        update_post_meta($workshop_id, '_cmb2_eventbrite_url', $event['url']);
        if (empty($event['is_free'])) {
          // Determine if any tickets left
          $tickets_available = 0;
          foreach($event['ticket_classes'] as $ticket_class) {
            if ($ticket_class['on_sale_status']=='AVAILABLE') {
              $tickets_available += $ticket_class['quantity_total'] - $ticket_class['quantity_sold'];
            }
          }
          update_post_meta($workshop_id, '_cmb2_tickets_available', $tickets_available);
          // If single ticket class, and isn't donation, set cost for workshop
          if (count($event['ticket_classes'])==1 && empty($event['ticket_classes'][0]['donation'])) {
            update_post_meta($workshop_id, '_cmb2_cost', $event['ticket_classes'][0]['cost']['major_value']);
          }
        }
      }
    }

  } catch ( Exception $e ) {
    echo 'Error fetching events: ' . $e->getMessage();
    wp_mail( 'developer@firebellydesign.com', 'CFS error', 'Error fetching events: ' . $e->getMessage() );
  }
  // set date run so next run avoids unnecessary overhead
  // UPDATE: can't use because getPlaylistItemsByPlaylistId doesn't allow publishedAfter param!
  // update_option ( 'fb_eventbrite_import_last_run', date('Y-m-d\TH:i:sP') );
}