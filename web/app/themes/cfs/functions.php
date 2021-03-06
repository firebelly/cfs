<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php' // Theme customizer
];

$firebelly_includes = [
  'lib/disable-comments.php',          // Disables WP comments in admin and frontend
  'lib/fb-init.php',                   // FB theme setups
  'lib/fb-metatags.php',               // FB metatags / ogtags
  'lib/fb-media.php',                  // FB media
  'lib/fb-ajax.php',                   // FB AJAX functions
  'lib/fb-utils.php',                  // FB utility functions
  'lib/cmb2-custom-fields.php',        // Custom CMB2
  'lib/page-fields.php',               // Extra fields for pages
  'lib/post-fields.php',               // Extra fields for posts + CPTs
  'lib/program-post-type.php',         // Programs
  'lib/workshop-post-type.php',        // Workshops
  'lib/person-post-type.php',          // People
  'lib/partner-post-type.php',         // Partners
  'lib/job-post-type.php',             // Jobs
  // 'lib/applicant-post-type.php',    // Applicants to forms (custom trainings primarily) — decided to not use but leaving here in case is useful in future
  'lib/site-options.php',              // Custom site options page for admin
  'lib/constant-contact.php',          // Constant Contact API functions
];

$sage_includes = array_merge($sage_includes, $firebelly_includes);

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);
