<?php
namespace Firebelly\ConstantContact;

/**
 * AJAX add contact to Constant Contact list
 */
function newsletter_subscribe() {
  // Check for email
  if (empty($_REQUEST['cc_email']) || !is_email($_REQUEST['cc_email'])) {
    wp_send_json_error(['message' => 'Invalid email.']);
  }

  // Check nonce
  if (empty($_REQUEST['newsletter_form_nonce']) || !wp_verify_nonce($_REQUEST['newsletter_form_nonce'], 'newsletter_form')) {
    wp_send_json_error(['message' => 'Invalid nonce.']);
  }

  // Check with akismet for spam
  if (is_spam_akismet(['user_name' => $_REQUEST['cc_name'], 'user_email' => $_REQUEST['cc_email']])) {
    wp_send_json_error(['message' => 'Your email was flagged as spam.']);
  }

  // Split name into first/last for CC
  $name = !empty($_REQUEST['cc_name']) ? $_REQUEST['cc_name'] : '';
  if (strpos($name, ' ') !== FALSE) {
    list($first, $last) = preg_split('/ ([^ ]+)$/', $name, 0, PREG_SPLIT_DELIM_CAPTURE);
  } else {
    $first = $name;
    $last = '';
  }

  $client = new \GuzzleHttp\Client();
  $cc_list_id = !empty($_REQUEST['cc_list_id']) ? $_REQUEST['cc_list_id'] : \Firebelly\SiteOptions\get_option('default_cc_list_id');

  try {
    // Check if user is already a contact
    $res = $client->request('GET', 'https://api.constantcontact.com/v2/contacts?email=' . $_REQUEST['cc_email'] . '&api_key=' . getenv('CONSTANT_CONTACT_APIKEY'), [
      'headers' => [
        'Authorization' => 'Bearer ' . getenv('CONSTANT_CONTACT_ACCESS_TOKEN')
      ]
    ]);
    $data = json_decode($res->getBody());

    if (!empty($data->results)) {
      // Existing contact
      $contact = $data->results[0];
      $contact_lists = [];

      // Check if user is already on the requested list
      $already_subscribed = false;
      foreach($contact->lists as $list) {
        // Build array of existing lists for contact
        $contact_lists[] = [ 'id' => $list->id ];
        if ($list->id == $cc_list_id) {
          $already_subscribed = true;
        }
      }
      if ($already_subscribed) {
        wp_send_json_error(['message' => 'You are already subscribed.']);
      } else {
        // Add new list_id
        $contact_lists[] = [ 'id' => $cc_list_id ];

        // Add existing contact to requested list
        try {
          $res = $client->request('PUT', 'https://api.constantcontact.com/v2/contacts/' . $contact->id . '?api_key=' . getenv('CONSTANT_CONTACT_APIKEY') . '&action_by=ACTION_BY_VISITOR', [
            'json' => [
              'email_addresses' => [
                [ 'email_address' => $_REQUEST['cc_email'] ]
              ],
              'lists' => $contact_lists
            ],
            'headers' => [
              'Authorization' => 'Bearer ' . getenv('CONSTANT_CONTACT_ACCESS_TOKEN')
            ]
          ]);
        } catch (\Exception $e) {
          // print_r($e);
          wp_send_json_error(['message' => 'There was an error adding existing contact to list.']);
        }
        wp_send_json_success(['message' => 'Thank you for subscribing!']);

      }
    } else {

      // Add contact and attach to requested list
      try {
        $res = $client->request('POST', 'https://api.constantcontact.com/v2/contacts?api_key=' . getenv('CONSTANT_CONTACT_APIKEY') . '&action_by=ACTION_BY_VISITOR', [
          'json' => [
            'lists' => [
              [ 'id' => $cc_list_id ]
            ],
            'email_addresses' => [
              [ 'email_address' => $_REQUEST['cc_email'] ]
            ],
            'first_name' => $first,
            'last_name' => $last
          ],
          'headers' => [
            'Authorization' => 'Bearer ' . getenv('CONSTANT_CONTACT_ACCESS_TOKEN')
          ]
        ]);
        wp_send_json_success(['message' => 'Thank you for subscribing!']);

      } catch (\Exception $e) {
        wp_send_json_error(['message' => 'There was an error adding a new contact to list.']);
      }

    }
  } catch (\Exception $e) {
    wp_send_json_error(['message' => 'There was an error sending the email check request.']);
  }
}
add_action( 'wp_ajax_newsletter_subscribe', __NAMESPACE__ . '\\newsletter_subscribe' );
add_action( 'wp_ajax_nopriv_newsletter_subscribe', __NAMESPACE__ . '\\newsletter_subscribe' );

/**
 * Pull all Constant Contact lists for select list
 */
function get_cc_lists() {
  $client = new \GuzzleHttp\Client();
  $return = [];
  try {
    $res = $client->request('GET', 'https://api.constantcontact.com/v2/lists?api_key=' . getenv('CONSTANT_CONTACT_APIKEY'), [
      'headers' => [
        'Authorization' => 'Bearer ' . getenv('CONSTANT_CONTACT_ACCESS_TOKEN')
      ]
    ]);
    $data = json_decode($res->getBody());
    foreach ($data as $list) {
      $return[$list->id] = $list->name;
    }
    asort($return);
  } catch (\Exception $e) {
    $return[] = 'Error sending request';
  }
  return $return;
}

function is_spam_akismet($args) {
    global $akismet_api_host, $akismet_api_port;
    $spam = false;

    // No akismet?
    if (empty($akismet_api_host) || empty($akismet_api_port)) {
      return false;
    }

    $query['user_ip']       = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';;
    $query['user_agent']    = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $query['referrer']      = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    $query['blog']          = get_option('home');
    $query['blog_lang']     = get_locale(); // default 'en_US'
    $query['blog_charset']  = get_option('blog_charset');
    $query['comment_type']  = 'contact-form'; //For more info http://bit.ly/2bVOMay

    $query['comment_content'] = !empty($args['post_content']) ? $args['post_content'] : '';
    // $query['permalink']     = $args['referrer'];
    $query['comment_author'] = $args['user_name'];
    $query['comment_author_email'] = $args['user_email'];

    // $query['is_test']  = '1';  // uncomment this when testing spam detection
    // $query['comment_author']  = 'viagra-test-123';  // uncomment this to test spam detection

    $query_string = http_build_query($query);

    if ( is_callable( array( 'Akismet', 'http_post' ) ) ) { //Akismet v3.0+
      $response = \Akismet::http_post($query_string, 'comment-check');
    } else {
      $response = akismet_http_post($query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);
    }
    if ('true' == $response[1]) {
      $spam = true;
    }

    return $spam;
}
