<?php
/*
 * PluginManager
 */

class DeclutterWP_PluginManager {

   public static function init() {
      $options = get_option('declutter_wp_options');

      if ($options !== FALSE) {
         new DeclutterWP_Declutter($options);
      }
   }

   public static function setDefaultOptions() {
      $defaults = array(
         'remove-header-junk' => 'on',
         'disable-json-rest-api' => 'on',
         'disable-oembed' => 'on',
         'disable-xml-rpc' => 'on',
         'remove-emoji-support' => 'on'
      );

      if (get_option('declutter_wp_options') === FALSE) {
         update_option('declutter_wp_options', $defaults);
      }

      return;
   }

}
