<?php
/*
 * Declutter
 */

class DeclutterWP_Declutter {

   /*
    * Constructor
    */

   public function __construct($options) {
      if (isset($options['remove-header-junk'])) { $this->removeHeaderJunk(); }
      if (isset($options['disable-json-rest-api'])) { $this->disableJsonRestApi(); }
      if (isset($options['disable-oembed'])) { $this->disableOEmbed(); }
      if (isset($options['disable-xml-rpc'])) { $this->disableXmlRpc(); }
      if (isset($options['remove-emoji-support'])) { $this->removeEmojiSupport(); }
   }

   /*
    * Methods
    */

   private function removeHeaderJunk() {
      // Remove canonical URL and shortlink
      remove_action('wp_head', 'rel_canonical');
      remove_action('wp_head', 'wp_shortlink_wp_head');

      // Remove links to index, parent, prev, next and random post
      remove_action('wp_head', 'index_rel_link');
      remove_action('wp_head', 'parent_post_rel_link', 10, 0);
      remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
      remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
      remove_action('wp_head', 'start_post_rel_link', 10, 0);

      // Remove feed links
      remove_action('wp_head', 'feed_links', 2);
      remove_action('wp_head', 'feed_links_extra', 3);

      // Remove generator from html and feed
      remove_action('wp_head', 'wp_generator');
      add_filter('the_generator', '__return_false');
   }

   private function disableJsonRestApi() {
      // Filters for WP-API version 1.x
      add_filter('json_enabled', '__return_false');
      add_filter('json_jsonp_enabled', '__return_false');

      // Filters for WP-API version 2.x
      add_filter('rest_enabled', '__return_false');
      add_filter('rest_jsonp_enabled', '__return_false');

      // Remove REST API info from head and headers
      remove_action('wp_head', 'rest_output_link_wp_head', 10);
      remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
      remove_action('template_redirect', 'rest_output_link_header', 11);
   }

   private function disableOEmbed() {
      // Remove discovery link from header
      remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

      // Remove scripts from footer
      add_action('wp_enqueue_scripts', function () { wp_deregister_script('wp-embed'); });

      // Disable embeds in visual editor
      add_filter('tiny_mce_plugins', function ($plugins) {
         return array_diff( $plugins, array('wpview') );
      });

      // Disable embeds in content output
      remove_filter('the_content', array( $GLOBALS['wp_embed'], 'autoembed'), 8);
   }

   private function disableXmlRpc() {
      // Disable XML-RPC
      add_filter('xmlrpc_enabled', '__return_false');
      remove_action('wp_head', 'rsd_link');
      remove_action('wp_head', 'wlwmanifest_link');

      // Remove XMLRPC header entry
      add_filter('wp_header', function ($headers) {
         unset($headers['X-Pingback']);
         return $headers;
      });

   }

   private function removeEmojiSupport() {
      // Remove emojis from frontend
      add_filter('emoji_svg_url', '__return_false');
      remove_action('wp_print_styles', 'print_emoji_styles');
      remove_action('wp_head', 'print_emoji_detection_script', 7);

      // Remove emojis from admin
      add_filter('tiny_mce_plugins', array('DeclutterWP_Declutter', 'disableEmojiInEditor'));
      remove_action('admin_print_styles', 'print_emoji_styles');
      remove_action('admin_print_scripts', 'print_emoji_detection_script');

      // More
      remove_filter('the_content_feed', 'wp_staticize_emoji');
      remove_filter('comment_text_rss', 'wp_staticize_emoji');
      remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
   }

   // Helper methods below

   public static function disableEmojiInEditor($plugins) {
      if (is_array($plugins)) {
         return array_diff($plugins, array('wpemoji'));
      } else {
         return array();
      }
   }

}
