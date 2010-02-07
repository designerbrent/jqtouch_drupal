<?php
// $Id$

// Include the definition of zen_theme_get_default_settings().
include_once './' . drupal_get_path('theme', 'jqtouch') . '/template.theme-registry.inc';

/**
 * Implementation of THEMEHOOK_settings() function.
 *
 * @param $saved_settings
 *   An array of saved settings for this theme.
 * @param $subtheme_defaults
 *   Allow a subtheme to override the default values.
 * @return
 *   A form array.
 */
function jqtouch_settings($saved_settings, $subtheme_defaults = array()) {
  /*
   * The default values for the theme variables. Make sure $defaults exactly
   * matches the $defaults in the template.php file.
   */

  // Add CSS to adjust the layout on the settings page
  // drupal_add_css(drupal_get_path('theme', 'jqtouch') . '/css/theme-settings.css', 'theme');

  // Add Javascript to adjust the layout on the settings page
  // drupal_add_js(drupal_get_path('theme', 'jqtouch') . '/css/theme-settings.js', 'theme');

  // Get the default values from the .info file.
  $defaults = jqtouch_theme_get_default_settings('jqtouch');

  // Allow a subtheme to override the default values.
  $defaults = array_merge($defaults, $subtheme_defaults);

  // Merge the saved variables and their default values.
  $settings = array_merge($defaults, $saved_settings);

  $form['jqtouch_theme'] = array(
    '#type' => 'fieldset', 
    '#title' => t('Theme Styles'), 
    '#weight' => 0,
    '#description' => t('Select a theme style.'),
    '#collapsible' => FALSE, 
    '#collapsed' => FALSE,
  );
  $form['jqtouch_theme']['jqtouch_theme_style'] = array(
    '#type' => 'radios',
    '#title' => t('Style'),
    '#default_value' => $settings['jqtouch_theme_style'],
    '#options' => array('apple' => t('Apple'), 'jqt' => t('jQTouch')),
    
  );

  $form['jqtouch_disable_resize'] = array(
     '#type'          => 'checkbox',
     '#title'         => t('Disable Resizing'),
     '#default_value' => $settings['jqtouch_disable_resize'],
     '#description'   => t('Check this box disable resizing of the page.'),
    );

  $form['jqtouch_dev'] = array(
    '#type' => 'fieldset', 
    '#title' => t('Development Settings'), 
    '#weight' => 5, 
    '#collapsible' => TRUE, 
    '#collapsed' => FALSE,
  );

  // Setting for flush all caches
  $form['jqtouch_dev']['jqtouch_rebuild_registry'] = array(
     '#type'          => 'checkbox',
     '#title'         => t('Rebuild the theme registry on every page.'),
     '#default_value' => $settings['jqtouch_rebuild_registry'],
     '#description'   => t('During theme development, it can be very useful to continuously <a href="!link">rebuild the theme registry</a>. WARNING: this is a huge performance penalty and must be turned off on production websites.', array('!link' => 'http://drupal.org/node/173880#theme-registry')),
    );

  // Return the additional form widgets
  return $form;
}
