<?php

class drivAlgolia {

    static $textdomain = 'driv_algolia';
    static $settingsKey = 'driv_algolia_settings';

    public function __construct() {
        add_action( 'plugins_loaded',                               'drivAlgolia::load_textdomain' );
    }

    public static function driv_algolia_settings_js_var() {
        echo 'var drivAlgoliaSettings = ' . json_encode((array) get_option( self::$settingsKey, array() )) . ';';
    }

    static function load_textdomain() {
      $locale = apply_filters( 'plugin_locale', get_locale(), self::$textdomain );

      // Load textdomain
      $languages_dir =  plugin_basename( dirname( __FILE__ ) ) . '/assets/languages/';
      load_textdomain( self::$textdomain, $languages_dir . self::$textdomain . '-' . $locale . '.mo' );
      load_plugin_textdomain( self::$textdomain, false, plugin_basename( dirname( __FILE__ ) ) . '/assets/languages' );
    }

    public static function get_current_settings($indices=null)
    {
        if (empty($indices)) {
            $indices = (array) get_option( 'algolia_synced_indices_ids', array() );
        }
        $settings = (array) get_option( self::$settingsKey, array() );
        if (count($settings)==0) {
            foreach ($indices as $index) {
                $settings[$index] = [];
                foreach (['view', 'empty_view'] as $setting) {
                    $settings[$index][$setting] = '';
                }
            }
        }
        return $settings;
    }

}

$drivAlgolia = new drivAlgolia;