<?php
namespace Cbd\Shop;

class Assets{ 


    public function __construct() {
        add_action('init', [$this, 'registerAllScripts']);
       
    

        if( ! is_admin()){
            add_action('wp_enqueue_scripts', [$this, 'enqueue_front_scripts']);
        }
       
    }

    public function registerAllScripts()
    {
      //  $styles = $this->get_styles();
        $scripts = $this->get_scripts();
      //  $this->register_styles( $styles );
        $this->register_scripts( $scripts );

    }


    public function get_scripts()
    {
        return [
            'cbd-shop-frontend-js' => [
                'src' => CBD_ASSETS_URL. '/js/cbd-shop.js', 
                'version' =>  filemtime(CBD_PLUGIN_DIR. '/assets/js/cbd-shop.js'),
                'deps' => ['jquery'] 
            ]
            ];
        
    }


    public function get_styles()
    {
        return [
            'dokan-admin-css' => [
                'src' => CBD_ASSETS_URL. '/css/softx-dokan-admin.css', 
                'version' => filemtime(CBD_PLUGIN_DIR. '/assets/css/softx-dokan-admin.css')
            ],
            'softx-dokan-fronted' => [
                'src' => CBD_ASSETS_URL. '/css/softx-dokan-frontend.css', 
                'version' => filemtime(CBD_PLUGIN_DIR. '/assets/css/softx-dokan-frontend.css')
            ]
            ];
    }


    public function register_scripts($scripts)
    {

        foreach( $scripts as $handle => $value){ 
            $deps = isset($value['deps']) ? $value['deps'] : false;    
            wp_register_script($handle,  $value['src'],$deps, $value['version'] ,true);
        }

        $opt =  get_option('cbd_shop_options');

        if( !empty($opt) && array_key_exists('api_username', $opt) && array_key_exists('api_pass', $opt) && array_key_exists('api_connection', $opt)){
       
        wp_localize_script('cbd-shop-frontend-js','cbdShop',[
            'username' => $opt['api_username'],
            'pass' => $opt['api_pass'],
            'connect' => $opt['api_connection']
        ]);
    }

    }

    public function register_styles($styles)
    {
        
        
        foreach($styles as $handle => $value){
            $deps = isset($value['deps']) ? $value['deps'] : false;    
            wp_register_style($handle , $value['src'], $deps , $value['version']);
        }
    }

    public function enqueue_admin_scripts($hook)
    {
        wp_enqueue_script('dokan-admin-script'); 
        wp_enqueue_style('dokan-admin-css'); 
    }

    public function enqueue_front_scripts()
    {
       
        // load softx dokan extension on every page 
           // wp_enqueue_style('softx-dokan-fronted');
            if(is_checkout()){
            wp_enqueue_script( 'cbd-shop-frontend-js');  
            }
    }

    
}