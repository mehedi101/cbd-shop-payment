<?php
/**
 * Plugin Name: CBD Shop Payment
 * Plugin URI: https://softxltd.com 
 * Description: An wooCommerce Extension plugin that will check a customer is elegible for credit purchase or not.
 * Version: 1.0.0
 * Author: Mehedi Hasan
 * Author URI: https://mehedihasn.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cbd-shop
 * Domain Path: /i18n/languages/
 * Requires at least: 5.7
 * Requires PHP: 7.3
 */

defined('ABSPATH') || exit ;
require_once __DIR__ . '/vendor/autoload.php';

final class Cbd_Shop{ 

    
    public $version='1.0.0';
    
    private static $instance = null;

    private $min_php = '7.3';

    private $container=[];


    private function __construct()
    {
        $this->define_constant();
        register_activation_hook( __FILE__, [$this, 'activate'] );
        register_deactivation_hook( __FILE__, [$this, 'deactivate'] );
        add_action( 'plugins_loaded',[$this, 'init_plugin']);

        
    }


   public static function init()
   {
       if( null === self::$instance ){ 
           self::$instance = new self();
       }
       return self::$instance;

   }


    public function define_constant()
    {
        $this->define('CBD_VERSION', $this->version);
        $this->define('CBD_PLUGIN_DIR', plugin_dir_path( __FILE__ ));


        /**
         * create includes and assets folder
         * includes folder is the class file container
         * assest folder is the css, js and javascript files container 
         */
         
        $this->define('CBD_INC_DIR', CBD_PLUGIN_DIR.'/includes');
        $this->define('CBD_ASSETS_URL', plugins_url( '/assets', __FILE__ ) );

    }

    private function define( $name, $value)
    {
        if( ! defined($name)){ 
            define($name, $value); 
        }
    }

    public function activate()
    {
        
    }

    public function deactivate()
    {
        
    }
    /**
     * activate pluugin features
     * when launch the plugin
     *
     * @return void
     */
    public function init_plugin(){ 
        add_action('init',[$this, 'localization_setup']);

        if(is_admin()){ 
            new Cbd\Shop\Admin();

        }else{
            new Cbd\Shop\Frontend();
        }
        do_action('CBD_loaded');

    }

    /**
     * initialize plugin localization
     * create languages path folder        
     *
     * @uses load_plugin_textdomain
     */
    public function localization_setup()
    {
        load_plugin_textdomain( 'cbd-shop', false, CBD_PLUGIN_DIR.'i18n/languages' );
    }



}

function cbdShop(){ 

    return Cbd_Shop::init();
}

cbdShop();

