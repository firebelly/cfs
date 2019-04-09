<?php
/**
 * Applicant post type
 */

namespace Firebelly\PostTypes\Applicant;

use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$cpt = new PostType(['name' => 'applicant'], [
  'supports'   => ['title', 'editor', 'thumbnail'],
]);
$cpt->register();

// Custom CMB2 fields for post type
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $meta_boxes['applicant_details'] = array(
    'id'            => 'applicant_details',
    'title'         => __( 'Details', 'cmb2' ),
    'object_types'  => array( 'applicant', ),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    'fields'        => array(
      array(
        'name'   => 'Type',
        'id'   => $prefix . 'application_type',
        'type' => 'text',
      ),
      array(
        'name' => 'Application Details',
        'id'   => $prefix . 'details',
        'type' => 'textarea',
      ),
      array(
        'name' => 'Dates',
        'id'   => $prefix . 'dates',
        'type' => 'text',
      ),
      array(
        'name' => 'Organization Name',
        'id'   => $prefix . 'organization',
        'type' => 'text',
      ),
      array(
        'name' => 'Organization Details',
        'id'   => $prefix . 'organization_details',
        'type' => 'textarea',
      ),
      array(
        'name' => 'Contact Name',
        'id'   => $prefix . 'name',
        'type' => 'text',
      ),
      array(
        'name' => 'Email',
        'id'   => $prefix . 'email',
        'type' => 'text',
      ),
      array(
        'name' => 'Phone',
        'id'   => $prefix . 'phone',
        'type' => 'text',
      ),
      array(
        'name' => 'City',
        'id'   => $prefix . 'city',
        'type' => 'text',
      ),
      array(
        'name' => 'State',
        'id'   => $prefix . 'state',
        'type' => 'text',
      ),
      array(
        'name' => 'Staff Notes',
        'desc' => 'Not shown on front end of site',
        'id'   => $prefix . 'notes',
        'type' => 'wysiwyg',
        'options' => array(
          'textarea_rows' => 6,
        ),
      ),
    ),
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );

function new_applicant() {
  $errors = [];
  $attachments = $attachments_size = [];
  $notifications_email = false;

  $applicant_post = array(
    'post_title'    => 'Application from ' . $_POST['application_name'],
    'post_type'     => 'applicant',
    'post_author'   => 1,
    'post_status'   => 'draft',
  );
  $post_id = wp_insert_post($applicant_post);
  if ($post_id) {

    update_post_meta($post_id, '_cmb2_application_type', $_POST['application_type']);
    update_post_meta($post_id, '_cmb2_organization', $_POST['organization']);
    update_post_meta($post_id, '_cmb2_name', $_POST['name']);
    update_post_meta($post_id, '_cmb2_email', $_POST['email']);
    update_post_meta($post_id, '_cmb2_phone', $_POST['phone']);
    update_post_meta($post_id, '_cmb2_city', $_POST['city']);
    update_post_meta($post_id, '_cmb2_state', $_POST['state']);

    $notifications_email = \Firebelly\SiteOptions\get_option('notifications_email');

    // Send email if notifications_email was set for position or in site_options for internships/portfolio
    if ($notifications_email) {
      $headers = ['From: ' . get_bloginfo('name') . '<www-data@' . preg_replace('-http(s)?://-','',getenv('WP_HOME')) . '>'];
      $message .= $_POST['name'] . "\n";
      $message .= 'Email: ' . $_POST['email'] . "\n";
      $message .= 'Phone: ' . $_POST['phone'] . "\n\n";
      $message .= "Edit in WordPress:\n" . admin_url('post.php?post='.$post_id.'&action=edit') . "\n";
      wp_mail($notifications_email, $subject, $message, $headers);
    }

    // Send quick receipt email to applicant
    $applicant_message = "Thank you for your interest. We have received your application and will be getting in touch.\n\n";
    wp_mail($_POST['email'], 'Thank you for your interest in ' . get_bloginfo('name'), $applicant_message, ['From: ' . get_bloginfo('name') . ' <www-data@' . preg_replace('-http(s)?://-','',getenv('WP_HOME')) . '>']);

  } else {
    $errors[] = 'Error inserting post';
  }

  if (empty($errors)) {
    return true;
  } else {
    return $errors;
  }
}


/**
 * AJAX Application submissions
 */
function application_submission() {
  if($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['application_form_nonce'])) {
    if (wp_verify_nonce($_POST['application_form_nonce'], 'application_form')) {

      // Server side validation of required fields
      $required_fields = ['name',
                          'email',
                          'phone'];
      foreach($required_fields as $required) {
        if (empty($_POST[$required])) {
          $required_txt = ucwords(str_replace('_', ' ', str_replace('application_','',$required)));
          wp_send_json_error(['message' => 'Please enter a value for '.$required_txt]);
        }
      }

      // Check for valid Email
      if (!is_email($_POST['email'])) {
        wp_send_json_error(['message' => 'Invalid email']);
      } else {

        // Try to save new Applicant post
        $return = new_applicant();
        if (is_array($return)) {
          wp_send_json_error(['message' => 'There was an error: '.implode("\n", $return)]);
        } else {
          wp_send_json_success(['message' => 'Application was saved OK']);
        }

      }
    } else {
      // Bad nonce, man!
      wp_send_json_error(['message' => 'Invalid form submission (bad nonce)']);
    }
  }
  wp_send_json_error(['message' => 'Invalid post']);
}
add_action('wp_ajax_application_submission', __NAMESPACE__ . '\\application_submission');
add_action('wp_ajax_nopriv_application_submission', __NAMESPACE__ . '\\application_submission');
