<?php
namespace Cbd\Shop\Admin;

class Menu
{
    public function __construct( )
    {
        add_action( 'admin_menu',[$this, 'register_cbd_admin_menu']);
        add_action('admin_init', [$this, 'register_cbd_shop_setting_options']);
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
            [$this, 'show_setting_page_content'], 
            CBD_ASSETS_URL . '/images/cbd-shop.png', 11 );


        add_submenu_page( $parent_slug, 
        __('Setting','cbd-shop'), __('Settings','cbd-shop'), $capability,  $parent_slug, [$this, 'show_setting_page_content']);
            
        global $submenu;
        $url = "https://softxltd.com/cbd-shop/#faq";    

        $submenu['cbd-shop-options'][] = ['FAQ', $capability, $url];    

    }

    public function show_setting_page_content()
    {
        if( ! current_user_can('manage_options')){ 
            wp_die( __( 'You do not have permission to edit this page','cbd-shop'));
        }

        require('option-page-wrapper.php');
    }



    public function register_cbd_shop_setting_options()
    {
        $main_section = 'cbd_shop_option_main_section';
        $page = 'cbd-shop-options';

        $args = [
            'type'              => 'string',
            'sanitize_callback' => [$this, 'cbd_shop_options_inputs_validation'],
            'default'           => null,
        ];

        register_setting( 'cbd_shop_option_group', 'cbd_shop_options', $args );
       /*
        add_settings_section( 
            $id:string:HTML ID for section Tag, 
            $title:string: Section title that will show in h2 tag as section heading, 
            $callback: function to echo explanation of the section, 
            $page: admin page slug in where the section will be added dynamically )
        */
        add_settings_section( $main_section, 'CBD Shop Option Settings', [$this, 'option_main_section_content'], $page );

        /*
        add_settings_field( 
            $id:string:HTML id for the field, 
            $title:string:form field label, 
            $callback:funcation to echo form field, 
            $page: admin page slug (URL ?page= ....), 
            $section:string:section id, 
            $args:array:optional )    
        */
        add_settings_field( 'first_debit_api', 'First Debit API', [$this, 'first_api_field'], $page, $main_section);
       /*  add_settings_field( 'first_debit_username', 'First Debit Username', [$this, 'first_api_username'], $page, $main_section);
        add_settings_field( 'first_debit_password', 'First Debit Password', [$this, 'first_api_password'], $page, $main_section); */
    }

    public function option_main_section_content()
    {
        echo "<p>you need to Add First Debit API information</p>";
    }

    public function first_api_field()
    {
        $options = get_option('cbd_shop_options');
        $api = $options['api'];
        echo '<input type="text" name="cbd_shop_options[api]" value="' . $api . '"/>';
    }

    public function cbd_shop_options_inputs_validation($input){ 
            $valid['api'] = preg_replace('/[^a-zA-Z0-9\s]/','',$input['api']);

            if( $valid['api'] !== $input['api']){  
                add_settings_error( 'cbd_shop_text_string', 'cbd_shop_texterror', 'Incorrent value entered! Please only input letters and spaces and numbers', 'error' );
            }

            return $valid; 
    }


}
