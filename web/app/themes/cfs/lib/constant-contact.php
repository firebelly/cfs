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
  if(empty($_REQUEST['newsletter_form_nonce']) || !wp_verify_nonce($_REQUEST['newsletter_form_nonce'], 'newsletter_form')) {
    wp_send_json_error(['message' => 'Invalid nonce.']);
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
          print_r($e);
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
        wp_send_json_success(['message' => 'You were subscribed successfully.']);

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
