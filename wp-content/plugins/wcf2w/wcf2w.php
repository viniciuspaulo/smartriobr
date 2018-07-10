<?php
/*
Plugin Name: WhatsApp Contact Button
Plugin URI: http://web-cude.com/wcf2w/
Description: WhatsApp Contact Button is a WordPress plugin which can create chat with you at WhatsApp.
Version: 1.6.3
Text Domain: wcf2w
Domain Path: /languages
Author: Alex Kuimov
Author URI: http://web-cude.com
*/

//default setting
register_activation_hook( __FILE__, 'wcf2w_plugin_activate');
function wcf2w_plugin_activate(){
	update_option('wcf2w_enable_button', sanitize_text_field('n'));     
    update_option('wcf2w_only_mobile', sanitize_text_field('n'));
    update_option('wcf2w_redirect', sanitize_text_field('web'));
    update_option('wcf2w_button_color', sanitize_text_field('#2ECC71')); 
    update_option('wcf2w_icon_color', sanitize_text_field('#ffffff'));
    update_option('wcf2w_animation_button', sanitize_text_field('Buzz'));
    update_option('wcf2w_position_button', sanitize_text_field('Right'));       	
    update_option('wcf2w_position_button_left', sanitize_text_field('20'));
    update_option('wcf2w_position_button_right', sanitize_text_field('20'));
    update_option('wcf2w_position_button_bottom', sanitize_text_field('20'));
    update_option('wcf2w_mobile_phone', sanitize_text_field(''));
    update_option('wcf2w_text_message', sanitize_text_field(''));
    update_option('wcf2w_tooltip_width', sanitize_text_field('100'));
    update_option('wcf2w_showing_tooltip', sanitize_text_field('disable'));
    update_option('wcf2w_tooltip_text_color', sanitize_text_field('#ffffff'));
    update_option('wcf2w_tooltip_background_color', sanitize_text_field('#2ECC71'));
    update_option('wcf2w_text_tooltip', sanitize_text_field('Contact Us'));
}	

//require scripts
require_once(plugin_dir_path(__FILE__).'wcf2w-scripts.php');
//require styles
require_once(plugin_dir_path(__FILE__).'wcf2w-styles.php');
//require functions
require_once(plugin_dir_path(__FILE__).'wcf2w-functions.php');
//require core
require_once(plugin_dir_path(__FILE__).'wcf2w-core.php');
//require admin
require_once(plugin_dir_path(__FILE__).'wcf2w-admin.php');
?>