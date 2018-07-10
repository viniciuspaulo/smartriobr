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

require_once dirname(__FILE__) . '/../classes/main.class.php';

SurStudioPluginTranslatorRevolutionLiteCommon::printHeaders();

class SurStudioPluginTranslatorRevolutionLiteTranslateManage {

	protected static $_token;
	protected static $_crc;
	protected static $_text;
	protected static $_from;
	protected static $_to;
	protected static $_ct;
	protected static $_nd;
	
	public static function main() {
		
		self::$_token = SurStudioPluginTranslatorRevolutionLiteCommon::getVariable('tk');
		self::$_crc = SurStudioPluginTranslatorRevolutionLiteCommon::getVariable('cr');
		self::$_from = SurStudioPluginTranslatorRevolutionLiteCommon::getVariable('f');
		self::$_to = SurStudioPluginTranslatorRevolutionLiteCommon::getVariable('t');
		self::$_ct = SurStudioPluginTranslatorRevolutionLiteCommon::getVariable('ct');
		self::$_nd = SurStudioPluginTranslatorRevolutionLiteCommon::getVariable('nd');
		self::$_text = SurStudioPluginTranslatorRevolutionLiteCommon::getVariable('tx');
		
		try {
			$validate = new SurStudioPluginTranslatorRevolutionLiteTranslateValidator(array(
				'token' => self::$_token,
				'crc' => self::$_crc,
				'text' => self::$_text,
				'from' => self::$_from,
				'to' => self::$_to,
				'ct' => self::$_ct,
				'nd' => self::$_nd
			));
		}
		catch(Exception $e) {
			return self::_fail($e);
		}

		try {
			$translate = new SurStudioPluginTranslatorRevolutionLiteTranslateTransport($validate);
			self::_gen_response($translate->generate());
		}
		catch(Exception $e) {
			return self::_fail($e);
		}

	}

	protected static function _fail($_e) {

		return self::_gen_response(array(
			'error' => $_e->getMessage()
		));

	}

	protected static function _gen_response($_array) {

		echo json_encode($_array);
		
	}

}

SurStudioPluginTranslatorRevolutionLiteTranslateManage::main();