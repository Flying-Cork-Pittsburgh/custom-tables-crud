<?php
namespace VendorName\PluginName;
use WordPress_ToolKit\ObjectCache;
use WordPress_ToolKit\ConfigRegistry;
use WordPress_ToolKit\Helpers\ArrayHelper;

class Plugin extends \WordPress_ToolKit\ToolKit {

  private static $instance;
  public static $textdomain;
  public static $config;

  public static function instance() {

    if ( !isset( self::$instance ) && !( self::$instance instanceof Plugin ) ) {

      self::$instance = new Plugin;

      // Load plugin configuration
      self::$config = self::$instance->init( dirname( __DIR__ ), trailingslashit( dirname( __DIR__ ) ) . 'plugin.json' );
      self::$config->merge( new ConfigRegistry( [ 'plugin' => self::$instance->get_current_plugin_meta( ARRAY_A ) ] ) );

      // Set Text Domain
      self::$textdomain = self::$config->get( 'plugin/meta/TextDomain' ) ?: self::$config->get( 'plugin/slug' );

      // Define plugin version
      if ( !defined( __NAMESPACE__ . '\VERSION' ) ) define( __NAMESPACE__ . '\VERSION', self::$config->get( 'plugin/meta/Version' ) );

      // Load dependecies and load plugin logic
      register_activation_hook( self::$config->get( 'plugin/identifier' ), array( self::$instance, 'activate' ) );
      add_action( 'plugins_loaded', array( self::$instance, 'load_dependencies' ) );

    }

    return self::$instance;

  }

  /**
    * Load plugin classes - Modify as needed, remove features that you don't need.
    *
    * @since 0.2.0
    */
  public function load_plugin() {

    if( !$this->verify_dependencies() ) {
      deactivate_plugins( self::$config->get( 'plugin/identifier' ) );
      return;
    }

    // Add Customizer panels and options
    // new Settings\Customizer_Options();

    // Enqueue scripts and stylesheets
    new EnqueueScripts();

    // Load shortcodes
    new Shortcodes\Shortcode_Loader();

    // Perform core plugin logic
    new Core();

  }

  /**
    * Check plugin dependencies on activation.
    *
    * @since 0.2.0
    */
  public function activate() {

    $this->verify_dependencies( true, true );

  }

  /**
    * Initialize Carbon Fields and load plugin logic
    *
    * @since 0.2.0
    */
  public function load_dependencies() {

    // if( class_exists( 'Carbon_Fields\\Carbon_Fields' ) ) {
    //   add_action( 'after_setup_theme', array( 'Carbon_Fields\\Carbon_Fields', 'boot' ) );
    // }
    // add_action( 'carbon_fields_fields_registered', array( $this, 'load_plugin' ));
  }

  /**
    * Function to verify dependencies, such as if an outdated version of Carbon
    *    Fields is detected.
    *
    * @param bool $die If true, plugin execution is halted with die(), useful for
    *    outputting error(s) in during activate()
    * @return bool
    * @since 0.2.0
    */
  private function verify_dependencies( $die = false, $activate = false ) {

    // Check if underDEV_Requirements class is loaded
    if( !class_exists( 'underDEV_Requirements' ) ) {
      if( $die ) {
        die( sprintf( __( '<strong>%s</strong>: One or more dependencies failed to load', self::$textdomain ), __( self::$config->get( 'plugin/meta/Name' ) ) ) );
      } else {
        return false;
      }
    }

    $requirements = new \underDEV_Requirements( __( self::$config->get( 'plugin/meta/Name' ), self::$textdomain ), self::$config->get( 'dependencies' ) );

    // Check for WordPress Toolkit
    $requirements->add_check( 'wordpress-toolkit', function( $val, $res ) {
      $wordpress_toolkit_version = defined( '\WordPress_ToolKit\VERSION' ) ? \WordPress_ToolKit\VERSION : null;
      if( !$wordpress_toolkit_version ) {
        $res->add_error( __( 'WordPress ToolKit not loaded.', self::$textdomain ) );
      } else if( version_compare( $wordpress_toolkit_version, self::$config->get( 'dependencies/wordpress-toolkit' ), '<' ) ) {
        $res->add_error( sprintf( __( 'An outdated version of WordPress ToolKit has been detected: %s (&gt;= %s required).', self::$textdomain ), $wordpress_toolkit_version, self::$config->get( 'dependencies/wordpress-toolkit' ) ) );
      }
    });

    // Display errors if requirements not met
    if( !$requirements->satisfied() ) {
      if( $die ) {
        die( $requirements->notice() );
      } else {
        add_action( 'admin_notices', array( $requirements, 'notice' ) );
        return false;
      }
    }

    return true;

  }

  /**
    * Append a field prefix as defined in $config
    *
    * @param string $field_name The string/field to prefix
    * @param string $before String to add before the prefix
    * @param string $after String to add after the prefix
    * @return string Prefixed string/field value
    * @since 0.1.0
    */
  public static function prefix( $field_name = null, $before = '', $after = '_' ) {

    $prefix = $before . self::$config->get( 'prefix' ) . $after;
    return $field_name !== null ? $prefix . $field_name : $prefix;

  }
}
