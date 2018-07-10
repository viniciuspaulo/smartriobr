<?php
//register and enqueue scripts
add_action('admin_enqueue_scripts', 'wcf2w_scripts_admin');
function wcf2w_scripts_admin() {
	//enqueue the scripts in setting page of plugin
	if(isset($_GET['page']) && $_GET['page'] == 'wcf2w'){
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_style('wp-color-picker');
		wp_register_script('wcf2w-admin-script', plugins_url('assets/js/admin_script.js', __FILE__));
		wp_enqueue_script('wcf2w-admin-script');
	}	
}	

?>