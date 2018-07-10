<?php
/*
Plugin Name: Translator Revolution
Plugin URI: http://www.surstudio.net/translator-revolution/
Description: Translator Revolution WP Plugin is a user-friendly, highly customizable Wordpress translation plugin. It works making it simple to get started, but powerful enough to create highly customized translator setups. <a href="admin.php?page=translator-revolution-plugin-settings">[Settings]</a>
Version: 2.0.6
Author: SurStudio
Author URI: http://www.surstudio.net/
*/

$dirname = dirname(__FILE__);
$plugin_dir_url = plugin_dir_url(__FILE__);

define('SURSTUDIO_TR_TEMPLATES', "$dirname/templates");
define('SURSTUDIO_TR_PROCEDURES', "$dirname/procedures");
define('SURSTUDIO_TR_CACHE', "$dirname/cache");
define('SURSTUDIO_TR_IMAGES', $plugin_dir_url . 'images');
define('SURSTUDIO_TR_CSS', $plugin_dir_url . 'styles');
define('SURSTUDIO_TR_JS', $plugin_dir_url . 'javascript');
define('SURSTUDIO_TR_TEXTDOMAIN', 'surstudio_trd');
define('SURSTUDIO_TR_COMPATIBILITY', false);

$dirname = dirname(__FILE__);

require_once $dirname . '/classes/common.class.php';
require_once $dirname . '/classes/main.class.php';

?>
