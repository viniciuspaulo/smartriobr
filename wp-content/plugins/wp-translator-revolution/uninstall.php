<?php
/**
 * Translator Revolution WP Plugin
 * http://goo.gl/4E7Mx
 *
 * LICENSE
 *
 * You need to buy a license if you want use this script.
 * http://codecanyon.net/legal/market
 *
 * @package    Translator Revolution WP Plugin
 * @copyright  Copyright (c) 2016, SurStudio, www.surstudio.net
 * @license    http://codecanyon.net/licenses/standard
 * @version    2.0.6
 * @date       2016-11-04
 */

class SurStudioPluginTranslatorRevolutionLiteUninstall { 
 
 	public static function run() {
		
		require_once dirname(__FILE__) . '/classes/main.class.php';
		
		$name = SurStudioPluginTranslatorRevolutionLiteConfig::getDbSettingsName();
		$name_cache = SurStudioPluginTranslatorRevolutionLiteConfig::getDbCacheWritableName();
		$name_main = SurStudioPluginTranslatorRevolutionLiteConfig::getDbMainName();
		
		if (get_option($name))
			delete_option($name);

		if (get_option($name_main))
			delete_option($name_main);

		if (get_option($name_cache))
			delete_option($name_cache);

	}
	
}

SurStudioPluginTranslatorRevolutionLiteUninstall::run();