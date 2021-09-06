<?php
namespace Cbd\Shop\Admin;

class Menu
{
    public function __construct( )
    {
        add_action( 'admin_menu',[$this, 'register_cbd_admin_menu']);
    }


    public function register_cbd_admin_menu()
    {
        $capability = 'manage_options';
        $parent_slug = 'cbd-shop-options';
        add_menu_page( 
            __('CBD Shop Setting page','cbd-shop'), 
            __('CBD Shop','cbd-shop'),
            $capability, 
            $parent_slug, 
            [$this, 'show_default_page_content'], 
            CBD_ASSETS_URL . '/images/cbd-shop.png', 11 );


        add_submenu_page( $parent_slug, 
        __('Setting','cbd-shop'), __('Settings','cbd-shop'), $capability,  $parent_slug, [$this, 'setting_page_content']);
            
        global $submenu;
        $url = "https://softxltd.com/cbd-shop/#faq";    

        $submenu['cbd-shop-options'][] = ['FAQ', $capability, $url];    

    }

    public function show_default_page_content()
    {
        if( ! current_user_can('manage_options')){ 
            wp_die( __( 'You do not have permission to edit this page','cbd-shop'));
        }

        require('option-page-wrapper.php');
    }

    public function setting_page_content()
    {
        esc_html_e( "Test Setting page content", 'cbd-Shop' );
    }
}
