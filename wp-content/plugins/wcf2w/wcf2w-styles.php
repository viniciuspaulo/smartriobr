<?php
//Register & enqueue styles
add_action('wp_enqueue_scripts', 'wcf2w_style');
function wcf2w_style() {
	wp_register_style('wcf2w-style', plugins_url('assets/css/style.css', __FILE__), false, false, 'all');
	wp_enqueue_style('wcf2w-style');

	wp_register_style('wcf2w-animate', plugins_url('assets/css/ak86_animate.css', __FILE__), false, false, 'all');
	wp_enqueue_style('wcf2w-animate');

	wp_register_style('wcf2w-font-awesome', plugins_url('assets/css/font-awesome.css', __FILE__), false, false, 'all');
	wp_enqueue_style('wcf2w-font-awesome');
}

add_action('admin_enqueue_scripts', 'wcf2w_style_admin');
function wcf2w_style_admin() {
	if(isset($_GET['page']) && $_GET['page'] == 'wcf2w'){
		wp_register_style('wcf2w-font-awesome-admin', plugins_url('assets/css/font-awesome.css', __FILE__), false, false, 'all');
		wp_enqueue_style('wcf2w-font-awesome-admin');
	}
}	
?>