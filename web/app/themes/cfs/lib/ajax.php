<?php
namespace Firebelly\Ajax;

/**
 * Add wp_ajax_url variable to global js scope
 */
function wp_ajax_url() {
  wp_localize_script('sage/js', 'wp_ajax_url', admin_url( 'admin-ajax.php'));
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\wp_ajax_url', 100);

/**
 * Silly ajax helper, returns true if xmlhttprequest
 */
function is_ajax() {
  return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

/**
 * AJAX load more posts (news or events)
 */
// function load_more_posts() {
//   // news or projects?
//   $post_type = (!empty($_REQUEST['post_type']) && $_REQUEST['post_type']=='project') ? 'project' : 'news';
//   // get page offsets
//   $page = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
//   $per_page = !empty($_REQUEST['per_page']) ? $_REQUEST['per_page'] : get_option('posts_per_page');
//   $offset = ($page-1) * $per_page;
//   $args = [
//     'offset' => $offset,
//     'posts_per_page' => $per_page,
//   ];
//   if ($post_type == 'project') {
//     $args['post_type'] = 'project';
//   }
//   // Filter by Category?
//   if (!empty($_REQUEST['project_category'])) {
//     if (strpos($_REQUEST['project_category'], ',') !== false) {
//       $cats = explode(',', $_REQUEST['project_category']);
//       $args['tax_query'] = array();
//       foreach($cats as $cat) {
//         array_push($args['tax_query'], array(
//           'taxonomy' => 'project_category',
//           'field'    => 'slug',
//           'terms'    => sanitize_title($cat),
//         ));
//       }
//     } else {
//       $args['tax_query'] = array(
//         array(
//           'taxonomy' => 'project_category',
//           'field'    => 'slug',
//           'terms'    => sanitize_title($_REQUEST['project_category']),
//         )
//       );
//     }
//   }

//   $posts = get_posts($args);

//   if ($posts):
//     foreach ($posts as $post) {
//       // set local var for post type — avoiding using $post in global namespace
//       if ($post_type == 'project')
//         $project_post = $post;
//       else
//         $news_post = $post;
//       include(locate_template('templates/article-'.$post_type.'.php'));
//     }
//   endif;

//   // we use this call outside AJAX calls; WP likes die() after an AJAX call
//   if (is_ajax()) die();
// }
// add_action( 'wp_ajax_load_more_posts', __NAMESPACE__ . '\\load_more_posts' );
// add_action( 'wp_ajax_nopriv_load_more_posts', __NAMESPACE__ . '\\load_more_posts' );

/**
 * AJAX add contact to Constant Barf .. er, I mean Contact
 */
function add_cc_contact() {
  // Check for email
  if (empty($_REQUEST['EMAIL']) || !is_email($_REQUEST['EMAIL'])) {
    wp_send_json_error(['message' => 'Invalid email.']);
  }
  // Get name
  $name = !empty($_REQUEST['NAME']) ? $_REQUEST['NAME'] : '';
  if (strpos($name, ' ') !== FALSE) {
    list($first, $last) = preg_split('/ ([^ ]+)$/', $name, 0, PREG_SPLIT_DELIM_CAPTURE);
  } else {
    $first = '';
    $last = $name;
  }

  $client = new \GuzzleHttp\Client();
  $res = $client->request('GET', 'https://api.constantcontact.com/v2/contacts?email=' . $_REQUEST['EMAIL'] . '&api_key=' . getenv('CONSTANT_CONTACT_APIKEY'), [
    'headers' => [
      'Authorization' => 'Bearer ' . getenv('CONSTANT_CONTACT_ACCESS_TOKEN')
    ]
  ]);
  if ($res->getStatusCode() == '200') {
    $data = json_decode($res->getBody());
    if (count($data->results) > 0) {
      wp_send_json_error(['message' => 'You are already subscribed.']);
    } else {
      $res = $client->request('POST', 'https://api.constantcontact.com/v2/contacts?api_key=' . getenv('CONSTANT_CONTACT_APIKEY'), [
        'json' => [
          'lists' => [
            [ 'id' => '1795823311' ]
          ],
          'email_addresses' => [
            [ 'email_address' => $_REQUEST['EMAIL'] ]
          ],
          'first_name' => $first,
          'last_name' => $last
        ],
        'headers' => [
          'Authorization' => 'Bearer ' . getenv('CONSTANT_CONTACT_ACCESS_TOKEN')
        ]
      ]);
      if ($res->getStatusCode() == '200') {
        wp_send_json_success(['message' => 'You were subscribed successfully.']);
      }
    }
  } else {
    wp_send_json_error(['message' => 'There was an error sending the request.']);
  }
}
add_action( 'wp_ajax_add_cc_contact', __NAMESPACE__ . '\\add_cc_contact' );
add_action( 'wp_ajax_nopriv_add_cc_contact', __NAMESPACE__ . '\\add_cc_contact' );
