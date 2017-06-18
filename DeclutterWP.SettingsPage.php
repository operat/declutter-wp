<?php
/*
 * SettingsPage
 */

class DeclutterWP_SettingsPage {

   public function __construct() {
      add_action('admin_menu', array($this, 'addPage'));
      add_action('admin_init', array( $this, 'initPage'));
   }

   public function addPage() {
      add_options_page(
         DECLUTTER_WP_NAME,
         DECLUTTER_WP_NAME,
         'manage_options',
         'declutter-wp',
         array($this, 'createPage')
      );
   }

   public function createPage() {
      $this->options = get_option('declutter_wp_options');

      ?>
         <div class="wrap">
            <h1><?php echo DECLUTTER_WP_NAME; ?></h1>
            <p>
               <b><?php echo DECLUTTER_WP_DESCRIPTION; ?></b><br>
               Find information, report issues and make contributions on <a href="<?php echo DECLUTTER_WP_URL; ?>" title="<?php echo DECLUTTER_WP_NAME; ?>" target="_blank">GitHub</a>.
            </p>
            <form method="post" action="options.php">
            <?php
               settings_fields('declutter_wp');
               do_settings_sections( 'declutter-wp' );
               submit_button();
            ?>
            </form>
         </div>
      <?php
   }

   public function initPage() {
      register_setting(
         'declutter_wp',
         'declutter_wp_options'
      );

      add_settings_section(
         'general-settings',
         'General Settings',
         array(
            $this,
            'printGeneralInfo'
         ),
         'declutter-wp'
      );

      add_settings_field(
         'remove-header-junk',
         'Remove header junk',
         array(
            $this,
            'printCheckbox'
         ),
         'declutter-wp',
         'general-settings',
         array(
            $this,
            'field' => 'remove-header-junk',
            'description' => 'Remove unnecessarily injected links from the header'
         )
      );

      add_settings_field(
         'disable-json-rest-api',
         'Disable JSON REST API',
         array(
            $this,
            'printCheckbox'
         ),
         'declutter-wp',
         'general-settings',
         array(
            $this,
            'field' => 'disable-json-rest-api',
            'description' => 'Disable the JSON REST API'
         )
      );

      add_settings_field(
         'disable-oembed',
         'Disable OEmbed',
         array(
            $this,
            'printCheckbox'
         ),
         'declutter-wp',
         'general-settings',
         array(
            $this,
            'field' => 'disable-oembed',
            'description' => 'Disable auto embed functionality'
         )
      );

      add_settings_field(
         'disable-xml-rpc',
         'Disable XML-RPC',
         array(
            $this,
            'printCheckbox'
         ),
         'declutter-wp',
         'general-settings',
         array(
            $this,
            'field' => 'disable-xml-rpc',
            'description' => 'Disable the XML-RPC API'
         )
      );

      add_settings_field(
         'remove-emoji-support',
         'Remove emoji support',
         array(
            $this,
            'printCheckbox'
         ),
         'declutter-wp',
         'general-settings',
         array(
            $this,
            'field' => 'remove-emoji-support',
            'description' => 'Disable support for Emojis'
         )
      );

   }

   public function printGeneralInfo() {
      // print '';
   }

   public function printCheckbox($args) {
      $field = $args['field'];
      $checked = isset($this->options[$field]) ? ' checked' : '';

      echo '<input type="checkbox" id="' . $field . '" name="declutter_wp_options[' . $field . ']"' . $checked . '><label for="' . $field . '">' . $args['description'] . '</label>';
   }

}
