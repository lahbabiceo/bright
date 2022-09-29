<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * The public/admin-facing functionality of the plugin.
 *
 * @link       http://www.webtoffee.com
 * @since      1.3.5
 *
 * @package    Wt_Smart_Coupon
 * @subpackage Wt_Smart_Coupon/common
 */

if( ! class_exists ( 'Wt_Smart_Coupon_Common' ) ) {
    class Wt_Smart_Coupon_Common {

        /**
         * The ID of this plugin.
         *
         * @since    1.3.5
         * @access   private
         * @var      string    $plugin_name    The ID of this plugin.
         */
        private $plugin_name;

        /**
         * The version of this plugin.
         *
         * @since    1.3.5
         * @access   private
         * @var      string    $version    The current version of this plugin.
         */
        private $version;

        /*
         * module list, Module folder and main file must be same as that of module name
         * Please check the `register_modules` method for more details
         */
        public static $modules=array(
            'coupon_category',
            'coupon_shortcode',
            'giveaway_product',
            'coupon_restriction',
        );

        public static $existing_modules=array();

        private static $instance = null;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.3.5
         * @param      string    $plugin_name       The name of the plugin.
         * @param      string    $version    The version of this plugin.
         */
        public function __construct($plugin_name, $version) {

            $this->plugin_name = $plugin_name;
            $this->version = $version;
   
        }

        /**
         * Get Instance
         * @since 1.3.5
         */
        public static function get_instance($plugin_name, $version)
        {
            if(self::$instance==null)
            {
                self::$instance=new Wt_Smart_Coupon_Common($plugin_name, $version);
            }

            return self::$instance;
        }

        /**
         *  Registers modules    
         *  @since 1.3.5     
         */
        public function register_modules()
        {            
            Wt_Smart_Coupon::register_modules(self::$modules, 'wt_sc_common_modules', plugin_dir_path( __FILE__ ), self::$existing_modules);          
        }

        /**
         *  Check module enabled    
         *  @since 1.3.5     
         */
        public static function module_exists($module)
        {
            return in_array($module, self::$existing_modules);
        }


        /**
         *  Prepare WC_DateTime object from date
         *  @since  1.4.1
         *  @param  $date   WC_DateTime|String|Int  Date value 
         *  @return WC_DateTime|null 
         */
        public static function prepare_date_object($date)
        {
            try {
                if ( empty( $date ) ) {
                    return null;
                }

                if ( is_a( $date, 'WC_DateTime' ) ) {
                    $datetime = $date;
                } elseif ( is_numeric( $date ) ) {
                    // Timestamps are handled as UTC timestamps in all cases.
                    $datetime = new WC_DateTime( "@{$date}", new DateTimeZone( 'UTC' ) );
                } else {
                    // Strings are defined in local WP timezone. Convert to UTC.
                    if ( 1 === preg_match( '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(Z|((-|\+)\d{2}:\d{2}))$/', $date, $date_bits ) ) {
                        $offset    = ! empty( $date_bits[7] ) ? iso8601_timezone_to_offset( $date_bits[7] ) : wc_timezone_offset();
                        $timestamp = gmmktime( $date_bits[4], $date_bits[5], $date_bits[6], $date_bits[2], $date_bits[3], $date_bits[1] ) - $offset;
                    } else {
                        $timestamp = wc_string_to_timestamp( get_gmt_from_date( gmdate( 'Y-m-d H:i:s', wc_string_to_timestamp( $date ) ) ) );
                    }
                    $datetime = new WC_DateTime( "@{$timestamp}", new DateTimeZone( 'UTC' ) );
                }

                // Set local timezone or offset.
                if ( get_option( 'timezone_string' ) ) {
                    $datetime->setTimezone( new DateTimeZone( wc_timezone_string() ) );
                } else {
                    $datetime->set_utc_offset( wc_timezone_offset() );
                }

                return $datetime;

            } catch ( Exception $e ) {} // @codingStandardsIgnoreLine.

            return null;
            
        }

        /**
         *  Prepare timestamp from WC_DateTime object
         *  @since  1.4.1
         *  @param  $date   WC_DateTime  Date object 
         *  @param  $gmt   bool  return GMT timestamp (optional). Default:true
         *  @return int  timestamp 
         */
        public static function get_date_timestamp($date, $gmt=true)
        {
            $datetime=self::prepare_date_object($date);

            if($datetime && is_a($datetime, 'WC_DateTime'))
            {
                return ($gmt ? $datetime->getOffsetTimestamp() : $datetime->getTimestamp());
            }

            return 0;
        }

        /**
         *  @since 1.4.1
         *  Check the coupon exists
         */
        public static function is_coupon_exists($coupon)
        {
            global $wpdb;
            if(!is_null($wpdb->get_row($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type ='shop_coupon' AND post_status = 'publish' AND post_title = %s ", $coupon))))
            {
                return true;                
            }
            return false;
        }

    }
}