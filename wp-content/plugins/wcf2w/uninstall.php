<?php 
//delete option
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

delete_option('wcf2w_enable_button');
delete_option('wcf2w_only_mobile');
delete_option('wcf2w_button_color');
delete_option('wcf2w_icon_color');
delete_option('wcf2w_animation_button');
delete_option('wcf2w_position_button');
delete_option('wcf2w_position_button_left');
delete_option('wcf2w_position_button_right');
delete_option('wcf2w_position_button_bottom');
delete_option('wcf2w_mobile_phone');
delete_option('wcf2w_text_message');
delete_option('wcf2w_tooltip_width');
delete_option('wcf2w_showing_tooltip');
delete_option('wcf2w_tooltip_text_color');
delete_option('wcf2w_tooltip_background_color');
delete_option('wcf2w_text_tooltip');