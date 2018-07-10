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

class SurStudioPluginTranslatorRevolutionLiteCommon {

	public static function getAdminCssUrl() {
		
		return SURSTUDIO_TR_CSS . '/admin.css';
		
	}

	public static function getCommonJSUrl() {

		return SURSTUDIO_TR_JS . '/common.class.js';
		
	}
	
	public static function getAdminWidgetsUrl() {
		
		return admin_url('widgets.php');
		
	}
	
	public static function getAjaxUrl() {
	
		return admin_url('admin-ajax.php');
		
	}

	public static function getAdminWelcomePluginUrl() {
		
		return admin_url('admin.php?page=' . SurStudioPluginTranslatorRevolutionLiteConfig::getWelcomeHandle());
		
	}

	public static function getAdminPluginUrl() {
		
		return admin_url('admin.php?page=' . SurStudioPluginTranslatorRevolutionLiteConfig::getAdminHandle());
		
	}
	
	public static function printHeaders() {

		header('Content-Type:application/json;charset=UTF-8');
		header('Content-Disposition:attachment');

	}
	
	public static function isOpenSSLInstalled() {

		return defined('OPENSSL_VERSION_TEXT');
		
	}
	
	public static function isFolderWritable($_folder) {
		
		return @is_writable($_folder) && is_array(@scandir($_folder));
		
	}

	public static function getLanguage($_code) {
		
		$languages = self::getLanguages();
		return array_key_exists($_code, $languages) ? $languages[$_code] : false;
		
	}

	public static function getNativeLanguages($_codes=null) {
		
		$languages = array(
			'id' => 'Bahasa Indonesia',
			'ca' => 'Català',
			'cs' => 'Čeština',
			'da' => 'Dansk',
			'de' => 'Deutsch',
			'es' => 'Español',
			'fr' => 'Français',
			'hr' => 'Hrvatski',
			'it' => 'Italiano',
			'lv' => 'Latviešu',
			'lt' => 'Lietuvių',
			'hu' => 'Magyar',
			'nl' => 'Nederlands',
			'no' => 'Norsk‎',
			'pl' => 'Polski',
			'pt' => 'Português',
			'ro' => 'Română',
			'sk' => 'Slovenčina',
			'sl' => 'slovenščina',
			'fi' => 'Suomi',
			'sv' => 'Svenska',
			'tr' => 'Türkçe',
			'vi' => 'Tiếng Việt',
			'el' => 'Ελληνικά',
			'ru' => 'Русский',
			'sr' => 'Српски',
			'uk' => 'Українська',
			'bg' => 'Български',
			'iw' => 'עברית',
			'ar' => 'العربية',
			'fa' => 'فارسی',
			'hi' => 'हिन्दी',
			'tl' => 'Tagalog',
			'th' => 'ภาษาไทย',
			'mt' => 'Malti',
			'sq' => 'Shqip',
			'eu' => 'Euskara',
			'bn' => 'বাংলা',
			'be' => 'беларуская мова',
			'et' => 'Eesti Keel',
			'gl' => 'Galego',
			'ka' => 'ქართული',
			'gu' => 'ગુજરાતી',
			'ht' => 'Kreyòl Ayisyen',
			'is' => 'Íslenska',
			'ga' => 'Gaeilge',
			'kn' => 'ಕನ್ನಡ',
			'mk' => 'македонски',
			'ms' => 'Bahasa Melayu',
			'sw' => 'Kiswahili',
			'yi' => 'ײִדיש',
			'ta' => 'தமிழ்',
			'te' => 'తెలుగు',
			'ur' => 'اردو',
			'cy' => 'Cymraeg',
			'zh-CN' => '中文（简体）‎',
			'zh-TW' => '中文（繁體）‎',
			'ja' => '日本語',
			'ko' => '한국어'
		);
		
		if (!is_array($_codes))
			return $languages;
			
		return array_intersect_key($languages, array_flip($_codes));
		
	}
	
	public static function getLanguages() {
	
		return array(
			'af' => 'Afrikaans',
			'sq' => 'Albanian',
			'ar' => 'Arabic',
			'hy' => 'Armenian',
			'az' => 'Azerbaijani',
			'eu' => 'Basque',
			'be' => 'Belarusian',
			'bn' => 'Bengali',
			'bs' => 'Bosnian',
			'bg' => 'Bulgarian',
			'ca' => 'Catalan',
			'ceb' => 'Cebuano',
			'ny' => 'Chichewa',
			'zh-CN' => 'Chinese Simplified',
			'zh-TW' => 'Chinese Traditional',
			'hr' => 'Croatian',
			'cs' => 'Czech',
			'da' => 'Danish',
			'nl' => 'Dutch',
			'en' => 'English',
			'eo' => 'Esperanto',
			'et' => 'Estonian',
			'tl' => 'Filipino',
			'fi' => 'Finnish',
			'fr' => 'French',
			'gl' => 'Galician',
			'ka' => 'Georgian',
			'de' => 'German',
			'el' => 'Greek',
			'gu' => 'Gujarati',	 //x in
			'ht' => 'Haitian Creole', 
			'ha' => 'Hausa',
			'iw' => 'Hebrew',
			'hi' => 'Hindi',
			'hmn' => 'Hmong',
			'hu' => 'Hungarian',
			'is' => 'Icelandic',
			'ig' => 'Igbo',
			'id' => 'Indonesian',
			'ga' => 'Irish',
			'it' => 'Italian',
			'ja' => 'Japanese',
			'jw' => 'Javanese',
			'kn' => 'Kannada',
			'kk' => 'Kazakh',
			'km' => 'Khmer',
			'ko' => 'Korean',
			'lo' => 'Lao',
			'la' => 'Latin',
			'lv' => 'Latvian',
			'lt' => 'Lithuanian',
			'mk' => 'Macedonian',
			'mg' => 'Malagasy',
			'ms' => 'Malay',
			'ml' => 'Malayalam',
			'mt' => 'Maltese',
			'mi' => 'Maori',
			'mr' => 'Marathi',
			'mn' => 'Mongolian',
			'my' => 'Burmese',
			'ne' => 'Nepali',
			'no' => 'Norwegian',
			'fa' => 'Persian',
			'pl' => 'Polish',
			'pt' => 'Portuguese',
			'pa' => 'Punjabi',
			'ro' => 'Romanian',
			'ru' => 'Russian',
			'sr' => 'Serbian',
			'st' => 'Sesotho',
			'si' => 'Sinhala',
			'sk' => 'Slovak',
			'sl' => 'Slovenian',
			'so' => 'Somali',
			'es' => 'Spanish',
			'su' => 'Sundanese',
			'sw' => 'Swahili',
			'sv' => 'Swedish',
			'tg' => 'Tajik',
			'ta' => 'Tamil',
			'te' => 'Telugu',
			'th' => 'Thai',
			'tr' => 'Turkish',
			'uk' => 'Ukrainian',
			'ur' => 'Urdu',
			'uz' => 'Uzbek',
			'vi' => 'Vietnamese',
			'cy' => 'Welsh',
			'yi' => 'Yiddish',
			'yo' => 'Yoruba',
			'zu' => 'Zulu'
		);
		
	}

	public static function areFolderFilesWritable($_folder) {
		
		$contents = @scandir($_folder);
		
		foreach ($contents as $name) {
			$file = $_folder . '/' . $name;
			if (self::endsWith($name, '.xml') && @is_file($file) && !@is_writable($file))
				return $name;
		}
		
		return true;
		
	}
	
	public static function getArrayItems($_items, $_array) {
		
		$result = array();
		
		if (!is_array($_items))
			$_items = array($_items);
		
		for ($i=0; $i<count($_items); $i++)
			if (array_key_exists($_items[$i], $_array))
				$result[$_items[$i]] = $_array[$_items[$i]];
		
		return $result;
		
	}
	
	public static function inArray($_value, $_array) { 
		
		if (!is_array($_array))
			$_array = array($_array);		
	
		if (!is_array($_value))
			return in_array($_value, $_array);
		else {
			foreach ($_value as $single)
				if (in_array($single, $_array))
					return true;
			return false;
		}
	
	}
	
	public static function countCharacters($_text) {
		
		if (!function_exists('mb_strlen') || !function_exists('mb_substr'))
			return false;
		
		if (!is_array($_text))
			$_text = array($_text);
		
		$result = 0;
		foreach ($_text as $txt)
			$result += array_sum(self::_mb_count_chars($txt));
		
		return $result;
		
	}
	
	protected static function _mb_count_chars($input) {
		$l = mb_strlen($input, 'UTF-8');
		$unique = array();
		for($i = 0; $i < $l; $i++) {
			$char = mb_substr($input, $i, 1, 'UTF-8');
			if(!array_key_exists($char, $unique))
				$unique[$char] = 0;
			$unique[$char]++;
		}
		return $unique;
	}
	
	public static function getUserAgent() {
		
		return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		
	}
	
	public static function getUrl($_server) {

		$result = isset($_server['HTTP_REFERER']) ? parse_url($_server['HTTP_REFERER'], PHP_URL_PATH) : false;
		
		if (!empty($result)) {
		
			$query = parse_url($_server['HTTP_REFERER'], PHP_URL_QUERY);
			if ($result !== false && !empty($query))
				$result .= '?' . str_replace('&', '&amp;', $query);

		}
		
		return $result;
		
	}
	
	public static function hashUrl($_url) {

		$salt = '>=/{Dhaay933O).IGzxmzsdf4sIt+7.aFgOiQI}9Ofsd46+63f -YI++c/<J}#]s';
		return hash('md5', hash('md5', $_url . $salt, false) . $salt, false);
		
	}
	
	public static function hashText($_text) {
		
		if (is_array($_text)) {
			array_walk($_text, create_function('&$v,$k', '$v = hash(\'sha256\', $v, false);'));
			return $_text;
		}
		else		
			return hash('sha256', $_text, false);
		
	}
	
	public static function arrayKeysExists($_array_1, $_array_2) { 

		if (!is_array($_array_1))
			$_array_1 = array($_array_1);

		foreach ($_array_1 as $key)
			if (array_key_exists($key, $_array_2)) 
				return true; 
		
		return false;

	} 
	
	public static function loadStyle($_file, $_version=null) {
		
		$file = $_file . '.css';
		if (!is_null($_version))
			$file.= '?ver=' . $_version;
		
		$location = SURSTUDIO_TR_CSS . '/' . $file;
		
		echo '<link rel="stylesheet" type="text/css" href="' . $location . '" />';
		
	}

	public static function renderCSS($_code) {
	
		if (!empty($_code))
			echo '<style type="text/css">' . $_code . '</style>';
		
	}

	public static function loadjQuery($_file) {
		
		echo '<script type="text/javascript">/*<![CDATA[*/!window.jQuery && document.write(\'<script src="' . $_file . '" type="text/javascript"><\/script>\')/*]]>*/</script>';
		
	}
	
	public static function httpBuildStr($_array) {
		
		$result = '';

		foreach($_array as $key => $value) 
			$result .= $key . '=' . $value . '&';
			
		return rtrim($result, '&');
				
	}

	public static function isMcryptInstalled() {

		return function_exists('mcrypt_decrypt');

	}
	
	public static function parseToken($_token, $_key, $_nd) {
	
		$result = self::_decrypt($_token, $_key, $_nd);
		
        return @unserialize($result);
		
	}
	
	public static function log($_string, $_key) {
		
		$salt = '7ZgS?x,:IK9G8fJdr43YK<LKr]yHT^v!>QPh*/>2BYE:TDS?<?<R1m06},)`?E?7';
		$key = hash('md5', hash('md5', $_key . $salt, false) . $salt, false);
		
		@error_log($_string . "\n" . str_repeat('-', 100) . "\n\n", 3, SURSTUDIO_TR_CACHE . '/error_' . $key . '.log');
		
	}
	
	protected static function _unchunk($_str) {

		$result = '';
		$parts = explode("\r\n", $_str);
		$chunkLen = 0;
		$thisChunk = '';

		while (!is_null($part = array_shift($parts))) {
			
			if ($chunkLen) {
				
				$thisChunk .= $part."\r\n";
				
				if (strlen($thisChunk) == $chunkLen) {
					$result .= $thisChunk;
					$chunkLen = 0;
					$thisChunk = '';
				} 
				else if (strlen($thisChunk) == $chunkLen + 2) {
					$result .= substr($thisChunk, 0, -2);
					$chunkLen = 0;
					$thisChunk = '';
				} 
				else if (strlen($thisChunk) > $chunkLen)
					return false;
								
			} 
			else {
				
				if ($part === '') 
					continue;
				
				if (!$chunkLen = hexdec($part)) 
					break;
			
			}
		}

		return $chunkLen ? false : $result;

	}

	public static function parseHttpResponse($string, $key) {

		$headers = array();
		$content = '';
		$str = strtok($string, "\n");
		$h = null;
		while ($str !== false) {
			if ($h and trim($str) === '') {                
				$h = false;
				continue;
			}
			if ($h !== false and false !== strpos($str, ':')) {
				$h = true;
				list($headername, $headervalue) = explode(':', trim($str), 2);
				$headername = strtolower($headername);
				$headervalue = ltrim($headervalue);
				if (isset($headers[$headername])) 
					$headers[$headername] .= ',' . $headervalue;
				else 
					$headers[$headername] = $headervalue;
			}
			if ($h === false) {
				$content .= $str."\n";
			}
			$str = strtok("\n");
		}

		if (array_key_exists('transfer-encoding', $headers) && $headers['transfer-encoding'] == 'chunked')
			$result = self::_unchunk($content);
		else
			$result = $content;

		$result = trim($result);
		
		if (array_key_exists('content-encoding', $headers)) {
			switch ($headers['content-encoding']) {
				case 'gzip':
					$result = gzdecode($result);
					break;
				case 'compress':
					$result = gzuncompress($result);
					break;
				case 'deflate':
					$result = gzinflate($result);
					break;
			}
		}

		return $result;

	}
	
	protected static function _decrypt($_string, $_key, $_nd) {
		
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($_key . $_nd . self::getUserAgent()), base64_decode($_string), MCRYPT_MODE_ECB, $iv);

	}

	public static function getScriptUrl($_file, $_version=null) {

		$file = $_file . '.js';
		if (!is_null($_version))
			$file.= '?ver=' . $_version;
		
		return SURSTUDIO_TR_JS . '/' . $file;
		
	}
	
	public static function startsWith($_string, $_start) {
		
		$length = strlen($_start);
		return substr($_string, 0, $length) === $_start;
		
	}

	public static function endsWith($_string, $_end) {
	
		return strcmp(substr($_string, strlen($_string) - strlen($_end)), $_end) === 0;
	
	}

	public static function genRepeatArray($_count, $_content) {
		
		return $_count > 0 ? array_fill(0, $_count, $_content) : array();
		
	}
	
	public static function redirect($_url) {

		header('Location: ' . $_url);

	}

	public static function htmlRedirect($url) {
		echo "<meta http-equiv=\"Refresh\" content=\"0;URL=" . $url . "\">";
		die();
	}

	public static function escapeHtmlBrackets($_string) {

		if (is_string($_string)) {
			$result = str_replace('&', '&amp;', $_string);
			$result = str_replace(array('\\', '<', '>', '"'), array('&#92;', '&lt;', '&gt;', '&quot;'), $result);
			return $result;
		}
		else if (is_array($_string)) {
			$result = array();
			foreach ($_string as $key => $string)
				$result[$key] = str_replace('&', '&amp;', $result[$key]);
				$result[$key] = str_replace(array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $string);
			return $result;
		}		
		else
			return $_string;

	}

	public static function cleanId($_string, $_separator='-') {
		
		$result = preg_replace('/[^a-zA-Z0-9]+|\s+/', $_separator, strtolower($_string));
		
		if (self::endsWith($result, '-'))
			$result = rtrim($result, '-');
		
		return $result;
		
	}
	
	public static function tabify($_string, $_degree) {
		
		return preg_replace("/\n\r|\r\n|\n|\r/", "\n" . str_repeat("\t", $_degree), $_string);
		
	}
	
	public static function jsonCompatible($_string) {

		if (is_string($_string)) {
			$result = self::stripBreakLines($_string);
			$result = addslashes($result);
			return $result;
		}
		else if (is_null($_string))
			return '';
		else
			return $_string;

	}

	public static function removeBreakLines($_string) {

		return preg_replace("/\n\r|\r\n|\n|\r/", '{{ break_line }}', $_string);

	}

	public static function removeEtx($_string) {
	
		$result = preg_replace('/\x03$/', '', $_string);
		$result = preg_replace('/\x03/', ' ', $result);
		
		$result = str_replace(array(chr(8), chr(29)), '', $result);
	
		return $result;
		
	}

	public static function htmlBreakLines($_string) {

		return preg_replace("/\n\r|\r\n|\n|\r/", '<br />', $_string);

	}

	public static function stripTabs($_string) {
		
		return str_replace("\t", '', $_string);
		
	}

	public static function stripBreakLinesAndTabs($_string) {
		
		$result = self::stripBreakLines($_string);
		
		return self::stripTabs($result);
		
	}
	
	public static function stripBreakLines($_string, $_flag=true) {

		if ($_flag)
			return preg_replace("/\n\r|\r\n|\n|\r/", '', $_string);
		else
			return preg_replace("/(\n\r|\r\n|\n|\r){2,}/", '', $_string);
			
	}

	private static $_disabled_magic_quotes_flag = false;

	private final static function _disable_magic_quotes() {

		if (self::$_disabled_magic_quotes_flag)
			return;

		if (get_magic_quotes_gpc()) {
			$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
			while (list($key, $val) = each($process)) {
				foreach ($val as $k => $v) {
					unset($process[$key][$k]);
					if (is_array($v)) {
						$process[$key][stripslashes($k)] = $v;
						$process[] = &$process[$key][stripslashes($k)];
					} else {
						$process[$key][stripslashes($k)] = stripslashes($v);
					}
				}
			}
			unset($process);
		}

		self::$_disabled_magic_quotes_flag = true;

	}

	public static function getAlternateVariable($_var_names, $_method='POST', $_escape_html=true) {

		foreach ($_var_names as $var_name) {
			$result = self::getVariable($var_name, $_method, $_escape_html);
			if ($result !== false)
				break;
		}

		return $result;

	}

	public static function getVariableSet($_var_base_name, $_variants=array(), $_discard_empty=true, $_method='POST', $_escape_html=true) {
		
		$result = array();
		
		for ($i=0; $i<count($_variants); $i++) {
			
			$partial = self::getVariable($_var_base_name . $_variants[$i], $_method, $_escape_html);
			
			if (!is_array($partial))
				$partial = array($partial);
			
			for ($j=0; $j<count($partial); $j++) {
				
				if (!SurStudioPluginTranslatorRevolutionLiteValidator::isEmpty($partial[$j]) || !$_discard_empty) {
					
					if (!is_array($result[$j]))
						$result[$j] = array();
					
					$result[$j][$i] = $partial[$j];
					
				}
			
			}
			
		}

		return $result;
		
	}
	
	public static function getVariable($_var_name, $_method='POST', $_escape_html=false, $_strip_quotes=false) {

		self::_disable_magic_quotes();

		if (strtolower($_method) == 'get')
			$result = isset($_GET[$_var_name]) ? $_GET[$_var_name] : false;
		else
			$result = isset($_POST[$_var_name]) ? $_POST[$_var_name] : false;

		if ($result !== false && $_strip_quotes)
			$result = preg_replace('/\"|\'|\\\\/', '', $result);
			
		if ($result !== false) {
			if (is_array($result)) {
				array_walk_recursive($result, create_function('&$val', '$val = stripslashes($val);'));
				return $result;
			}
			else
				$result = stripslashes($result);
		}

		return $_escape_html ? self::escapeHtmlBrackets($result) : $result;

	}

	public static function mergeImages($_message, $_filenames, $_base_url) {

		$images = array();
		for ($i = 0; $i < count($_filenames); $i++)
			$images[] = '<img src="' . $_base_url . $_filenames[$i] . '" alt="" />';
		return self::mergeText($_message, $images);

	}

	public static function mergeText($_message, $_new_values_array) {

		$match_array = array();
		for ($i = 0; $i < count($_new_values_array); $i++)
			$match_array[] = "[$i]";
		return str_replace($match_array, $_new_values_array, $_message);

	}

	public static function mergeArrays($_array_1, $_array_2) {
		
	  foreach ($_array_2 as $key => $value) {
		
		if (!is_array($_array_1)) {
			var_dump('Array 1 is not an array!');
			var_dump($_array_1);
			die();
		}		

		if (!is_array($_array_2)) {
			var_dump('Array 2 is not an array!');
			var_dump($_array_2);
			die();
		}

		if (array_key_exists($key, $_array_1) && is_array($value))
		  $_array_1[$key] = self::mergeArrays($_array_1[$key], $_array_2[$key]);
		else
		  $_array_1[$key] = $value;

	  }

	  return $_array_1;

	}

	public static function chain($primary_field, $parent_field, $sort_field, $rows, $root_id=0, $maxlevel=250000) {
		
		$chain = new SurStudioPluginTranslatorRevolutionLiteChain($primary_field, $parent_field, $sort_field, $rows, $root_id, $maxlevel);
		return $chain->get();
		
	}

	/**
	*
	* render object methods
	*
	*/

	protected static $_render_object_cache;
	
	protected static function _initialize_template_cache() {
		
		if (!is_array(self::$_render_object_cache))
			self::$_render_object_cache = array();
		
	}
	
	protected static function _set_template($_file, $_content) {
		
		self::$_render_object_cache[$_file] = $_content;
		
	}
	
	protected static function _get_template($_file) {
		
		return array_key_exists($_file, self::$_render_object_cache) ? self::$_render_object_cache[$_file] : false;
		
	}
	
	public static function renderObject($_object, $_options=null, $_htmlencode=false) {

		self::_initialize_template_cache();

		if ($_options['type'] == 'file')
			$template = SURSTUDIO_TR_TEMPLATES . $_options['content'];
		else if ($_options['type'] == 'html')
			$html = $_options['content'];
		else {
			var_dump('--------');
			print_r($_options);
			var_dump('--------');
			return 'template type error';
		}

		if (array_key_exists('meta_tag_rules', $_options))
			$meta_tag_rules = $_options['meta_tag_rules'];
		else
			$meta_tag_rules = null;

		if (!is_array($_object)) {

			if ($_options['type'] == 'file') {
				
				$result = self::_get_template($template);
				
				if ($result !== false)
					self::_set_template($template, $result);
				else {
					ob_start();
					if (is_file($template))
						include $template;
					else {
						echo "$template does not exist!<br />";
						#var_dump('Error: ');
						#print_r($_options);
					}
					$result = ob_get_clean();
				}
				
			}
			else 
				$result = $html;

			if ($_object != null)
				foreach ($_object as $property => $value) 
					$result = self::stampCustomValue("{{ $property }}", $value, $result, $_htmlencode);

			if (is_array($meta_tag_rules))
				$result = self::displayHideMetaTags($_object, $meta_tag_rules, $result);

		} 
		else {

			$result = '';

			foreach ($_object as $single_object) {

				$temp_object = is_array($single_object) ? (object) $single_object : $single_object;

				$result .= self::renderObject($temp_object, $_options, $_htmlencode);
				
			}

		}

		return $result;

	}

	protected static function displayHideMetaTags($_object, $_meta_tag_rules, $_html) {

		$result = $_html;

		foreach ($_meta_tag_rules as $meta_tag_rule) {

			if (array_key_exists('property', $meta_tag_rule))
				$_expression = ($_object->{$meta_tag_rule['property']} == $meta_tag_rule['value']);
			else if (array_key_exists('expression', $meta_tag_rule)) 
				$_expression = $meta_tag_rule['expression'];

			$result = self::displayHideMetaTag($_expression, $meta_tag_rule['tag'], $result);

		}

		return $result;

	}

	public static function displayHideMetaTag($_expression, $_tag, $_html)	{

		if ($_expression) {
			$_html = self::displayHideBlock("$_tag.true", $_html, true);
			$_html = self::displayHideBlock("$_tag.false", $_html, false);
		} 
		else {
			$_html = self::displayHideBlock("$_tag.true", $_html, false);
			$_html = self::displayHideBlock("$_tag.false", $_html, true);
		}

		return $_html;

	}

	protected static function displayHideBlock($_name, $_html, $_state) {

		if ($_state) {

			$_names = array (
				"{{ $_name:begin }}",
				"{{ $_name:end }}"
			);
			$results = str_replace($_names, '', $_html);

		} 
		else {

			$occurrence_ini = strpos($_html, "{{ $_name:begin }}");
			$occurrence_end = strpos($_html, "{{ $_name:end }}", $occurrence_ini);
			$last_occurrence_ini = 0;
			$positions = array ();
			$results = $_html;

			while ((!SurStudioPluginTranslatorRevolutionLiteValidator::isEmpty($occurrence_ini)) && (SurStudioPluginTranslatorRevolutionLiteValidator::isInteger($occurrence_ini)) && (!SurStudioPluginTranslatorRevolutionLiteValidator::isEmpty($occurrence_end)) && (SurStudioPluginTranslatorRevolutionLiteValidator::isInteger($occurrence_end))) {
				$positions[] = array (
					$occurrence_ini,
					$occurrence_end
				);
				$occurrence_ini = strpos($_html, "{{ $_name:begin }}", $occurrence_end);
				$occurrence_end = strpos($_html, "{{ $_name:end }}", $occurrence_ini);
			}

			$_name_length = strlen("{{ $_name:end }}");
			$results = $_html;

			rsort($positions);

			foreach ($positions as $position) {
				$results = substr_replace($results, '', $position[0], $position[1] - $position[0] + $_name_length);
			}

		}

		return $results;

	}

	public static function stampCustomValue($_tag, $_value, $_html, $_htmlencode=false) {

		if (is_string($_value) || is_int($_value) || is_float($_value) || is_null($_value))
			$result = str_replace($_tag, $_htmlencode ? utf8_decode($_value) : $_value, $_html);
		else
			$result = $_html;

		return $result;

	}

}

class SurStudioPluginTranslatorRevolutionLiteValidator {

	public static function isInteger($_number) {

		if (!self::isEmpty($_number))
			return ((string) $_number) === ((string) (int) $_number);
		else
			return true;

	}

	public static function isEmpty($_string) {
	
		return (empty($_string) && strlen($_string) == 0);

	}

	public static function isBool($_string) {
	
		return ($_string === 'true' || $_string === 'false' || $_string === true || $_string === false);

	}

}

class SurStudioPluginTranslatorRevolutionLiteChain {
	
    public $table;
    public $rows;
    public $chain_table;
    public $primary_field;
    public $parent_field;
    public $sort_field;
    
    public function __construct($primary_field, $parent_field, $sort_field, $rows, $root_id, $maxlevel) {
		
        $this->rows = $rows;
        $this->primary_field = $primary_field;
        $this->parent_field = $parent_field;
        $this->sort_field = $sort_field;
        $this->_build_chain($root_id,$maxlevel);
    
    }

    public function get() {
		
		return $this->chain_table;
		
	}
	
    protected function _build_chain($rootcatid, $maxlevel) {
		
        foreach($this->rows as $row)
            $this->table[$row[$this->parent_field]][ $row[$this->primary_field]] = $row;

        $this->_make_branch($rootcatid,0,$maxlevel);

    }
            
    protected function _make_branch($parent_id, $level, $maxlevel) {
        
        $rows = $this->table[$parent_id];

        if (empty($rows))
			return;
        
        foreach($rows as $key => $value)
            $rows[$key]['key'] = $this->sort_field;
        
        usort($rows, array('self', 'cmp'));
        
        foreach($rows as $item) {
        
            $item['indent'] = $level;
            $this->chain_table[] = $item;
            
            if (isset($this->table[$item[$this->primary_field]]) && ($maxlevel>$level+1) || ($maxlevel==0))
                $this->_make_branch($item[$this->primary_field], $level+1, $maxlevel);

        }
        
    }

	public static function cmp($a, $b) {
		
		if ($a[$a['key']] == $b[$b['key']])
			return 0;

		return $a[$a['key']] < $b[$b['key']] ? -1 : 1;

	}

}

class SurStudioPluginTranslatorRevolutionLiteFileHandler {

	protected static $_files = array();

	protected static function _get_file($_file, $_mode) {
		
		self::$_files[$_file] = @fopen($_file, $_mode);
		
		if (!self::$_files[$_file])
			return false;
		
		self::_lock($_file, $_mode);
		
		return self::$_files[$_file];
		
	}
	
	protected static function _lock($_file, $_mode) {

		if ($_mode == 'r')
			flock(self::$_files[$_file], LOCK_SH);

		if ($_mode == 'wb' || $_mode == 'x')
			flock(self::$_files[$_file], LOCK_EX);

	}
	
	protected static function _unlock($_file) {
		
		flock(self::$_files[$_file], LOCK_UN);
		fclose(self::$_files[$_file]);
		unset(self::$_files[$_file]);
		
	}
	
	public static function read($_file) {
		
		$file = self::_get_file($_file, 'r');
		
		if (!$file)
			return false;
		
		$contents = '';

		while (!feof($file))
		  $contents .= fread($file, 8192);

		self::_unlock($_file);

		return $contents;
		
	}
	
	public static function write($_file, $_contents) {
		
		$file = self::_get_file($_file, 'wb');
		
		if (!$file) {
			SurStudioPluginTranslatorRevolutionLiteConfig::setCacheFlag(false);
			return false;
		}
		
		$result = fwrite($file, $_contents);
		fflush($file);
		
		self::_unlock($_file);

		return $result;
		
	}
	
	public static function create($_file, $_contents) {
		
		$file = self::_get_file($_file, 'x');
		
		if (!$file)
			return false;
		
		$result = fwrite($file, $_contents);
		
		self::_unlock($_file);

		return $result;
		
	}
	
}

class SurStudioPluginTranslatorRevolutionLiteParseCSV {	

	public $heading = false;
	public $fields = array();
	public $sort_by = null;
	public $sort_reverse = false;
	public $sort_type = null;
	public $delimiter = ',';
	public $enclosure = '"';
	public $conditions = null;
	public $offset = null;
	public $limit = null;
	public $auto_depth = 15;
	public $auto_non_chars = "a-zA-Z0-9\n\r";	
	public $auto_preferred = ",;\t.:|";
	public $convert_encoding = false;
	public $input_encoding = 'ISO-8859-1';
	public $output_encoding = 'ISO-8859-1';
	public $linefeed = "\r\n";
	public $output_delimiter = ',';
	public $output_filename = 'data.csv';
	public $keep_file_data = false;
	
	public $file;
	public $file_data;
	public $error = 0;
	public $error_info = array();
	public $titles = array();
	public $data = array();
	
	public function __construct($input=null, $offset=null, $limit=null, $conditions=null) {
		
		if ($offset !== null)
			$this->offset = $offset;
		
		if ($limit !== null)
			$this->limit = $limit;
		
		if (count($conditions) > 0)
			$this->conditions = $conditions;
		
		if (!empty($input))
			$this->parse($input);

	}

	public function parse($input=null, $offset=null, $limit=null, $conditions=null) {
		
		if ($input === null)
		$input = $this->file;
		
		if (!empty($input)) {
		
			if ($offset !== null)
				$this->offset = $offset;
			
			if ($limit !== null)
				$this->limit = $limit;
			
			if (count($conditions) > 0)
				$this->conditions = $conditions;
				
			$this->file_data = &$input;
			$this->data = $this->parse_string();
			
			if ($this->data === false ) 
				return false;
				
		}
		
		return true;

	}
		
	protected function parse_string($data = null) {
		
		if (empty($data)) {

			if ($this->_check_data())
				$data = &$this->file_data;
			else
				return false;

		}
		
		$white_spaces = str_replace($this->delimiter, '', " \t\x0B\0");
		
		$rows = array();
		$row = array();
		$row_count = 0;
		$current = '';
		$head = !empty($this->fields)? $this->fields : array();
		$col = 0;
		$enclosed = false;
		$was_enclosed = false;
		$strlen = strlen($data);
		
		for ($i=0;$i<$strlen; $i++) {
		
			$ch = $data{$i};
			$nch = isset($data{$i+1}) ? $data{$i+1} : false;
			$pch = isset($data{$i-1}) ? $data{$i-1} : false;
			
			if ($ch == $this->enclosure) {
				if (!$enclosed) {
					if (ltrim($current, $white_spaces) == '') {
						$enclosed = true;
						$was_enclosed = true;
					} 
					else {
						$this->error = 2;
						$error_row = count($rows) + 1;
						$error_col = $col + 1;
						if (!isset($this->error_info[$error_row.'-'.$error_col])) {
							$this->error_info[$error_row.'-'.$error_col] = array(
								'type' => 2,
								'info' => 'Syntax error found on row ' . $error_row . '. Non-enclosed fields can not contain double-quotes.',
								'row' => $error_row,
								'field' => $error_col,
								'field_name' => (!empty($head[$col])) ? $head[$col] : null,
							);
						}
						$current .= $ch;
					}
				}
				elseif ($nch == $this->enclosure) {
					$current .= $ch;
					$i++;
				}
				elseif ($nch != $this->delimiter && $nch != "\r" && $nch != "\n") {
					for ($x=($i+1); isset($data{$x}) && ltrim($data{$x}, $white_spaces) == ''; $x++) {}
					if ($data{$x} == $this->delimiter) {
						$enclosed = false;
						$i = $x;
					}
					else {
						if ($this->error < 1)
							$this->error = 1;

						$error_row = count($rows) + 1;
						$error_col = $col + 1;

						if (!isset($this->error_info[$error_row.'-'.$error_col])) {
							$this->error_info[$error_row.'-'.$error_col] = array(
								'type' => 1,
								'info' =>
									'Syntax error found on row ' . (count($rows) + 1) . '. ' .
									'A single double-quote was found within an enclosed string. ' .
									'Enclosed double-quotes must be escaped with a second double-quote.',
								'row' => count($rows) + 1,
								'field' => $col + 1,
								'field_name' => (!empty($head[$col])) ? $head[$col] : null,
							);
						}

						$current .= $ch;
						$enclosed = false;
					}
				} 
				else {
					$enclosed = false;
				}
				
			} 
			elseif (($ch == $this->delimiter || $ch == "\n" || $ch == "\r") && !$enclosed) {
				$key = ( !empty($head[$col]) ) ? $head[$col] : $col ;
				$row[$key] = ( $was_enclosed ) ? $current : trim($current) ;
				$current = '';
				$was_enclosed = false;
				$col++;
				
				if ($ch == "\n" || $ch == "\r") {
					if ($this->_validate_offset($row_count) && $this->_validate_row_conditions($row, $this->conditions)) {
						if ($this->heading && empty($head)) {
							$head = $row;
						} elseif (empty($this->fields) || (!empty($this->fields) && (($this->heading && $row_count > 0) || !$this->heading))) {
							if (!empty($this->sort_by) && !empty($row[$this->sort_by])) {
								if (isset($rows[$row[$this->sort_by]])) {
									$rows[$row[$this->sort_by].'_0'] = &$rows[$row[$this->sort_by]];
									unset($rows[$row[$this->sort_by]]);
									for ( $sn=1; isset($rows[$row[$this->sort_by].'_'.$sn]); $sn++) {}
									$rows[$row[$this->sort_by].'_'.$sn] = $row;
								} else $rows[$row[$this->sort_by]] = $row;
							} else $rows[] = $row;
						}
					}
					$row = array();
					$col = 0;
					$row_count++;
					if ($this->sort_by === null && $this->limit !== null && count($rows) == $this->limit) {
						$i = $strlen;
					}
					if ($ch == "\r" && $nch == "\n")
						$i++;
				}
				
			} else {
				$current .= $ch;
			}
		}
		$this->titles = $head;
		if (!empty($this->sort_by)) {
			$sort_type = SORT_REGULAR;
			if ($this->sort_type == 'numeric') {
				$sort_type = SORT_NUMERIC;
			} elseif ($this->sort_type == 'string') {
				$sort_type = SORT_STRING;
			}
			( $this->sort_reverse ) ? krsort($rows, $sort_type) : ksort($rows, $sort_type) ;
			if ($this->offset !== null || $this->limit !== null) {
				$rows = array_slice($rows, ($this->offset === null ? 0 : $this->offset) , $this->limit, true);
			}
		}
		if (!$this->keep_file_data) {
			$this->file_data = null;
		}
		return $rows;
	}

	public function unparse($data = array(), $fields=array(), $append=false , $is_php=false, $delimiter=null) {
		
		if (!is_array($data) || empty($data))
			$data = &$this->data;

		if (!is_array($fields) || empty($fields))
			$fields = &$this->titles;
			
		if ($delimiter === null)
			$delimiter = $this->delimiter;
		
		$string = $is_php ? "<?php header('Status: 403'); die(' '); ?>" . $this->linefeed : '' ;
		$entry = array();
		
		foreach ($data as $key => $row) {
			
			foreach ($row as $field => $value)
				$entry[] = $this->_enclose_value($value);

			$string .= implode($delimiter, $entry) . $this->linefeed;
			$entry = array();
			
		}
		
		return $string;
		
	}

	protected function _wfile($file, $string='', $mode='wb', $lock=2) {
		if ($fp = fopen($file, $mode)) {
			flock($fp, $lock);
			$re = fwrite($fp, $string);
			$re2 = fclose($fp);
			if ($re != false && $re2 != false)
				return true;
		}
		return false;
	}

	protected function _validate_row_conditions($row=array(), $conditions=null) {
		if (!empty($row)) {
			if (!empty($conditions)) {
				$conditions = (strpos($conditions, ' OR ') !== false) ? explode(' OR ', $conditions) : array($conditions) ;
				$or = '';
				foreach( $conditions as $key => $value) {
					if (strpos($value, ' AND ') !== false) {
						$value = explode(' AND ', $value);
						$and = '';
						foreach( $value as $k => $v) {
							$and .= $this->_validate_row_condition($row, $v);
						}
						$or .= (strpos($and, '0') !== false) ? '0' : '1' ;
					} else {
						$or .= $this->_validate_row_condition($row, $value);
					}
				}
				return (strpos($or, '1') !== false) ? true : false ;
			}
			return true;
		}
		return false;
	}
	
	protected function _validate_row_condition($row, $condition) {
		$operators = array(
			'=', 'equals', 'is',
			'!=', 'is not',
			'<', 'is less than',
			'>', 'is greater than',
			'<=', 'is less than or equals',
			'>=', 'is greater than or equals',
			'contains',
			'does not contain',
		);
		$operators_regex = array();
		foreach( $operators as $value) {
			$operators_regex[] = preg_quote($value, '/');
		}
		$operators_regex = implode('|', $operators_regex);
		if (preg_match('/^(.+) ('.$operators_regex.') (.+)$/i', trim($condition), $capture)) {
			$field = $capture[1];
			$op = $capture[2];
			$value = $capture[3];
			if (preg_match('/^([\'\"]{1})(.*)([\'\"]{1})$/i', $value, $capture)) {
				if ($capture[1] == $capture[3]) {
					$value = $capture[2];
					$value = str_replace("\\n", "\n", $value);
					$value = str_replace("\\r", "\r", $value);
					$value = str_replace("\\t", "\t", $value);
					$value = stripslashes($value);
				}
			}
			if (array_key_exists($field, $row)) {
				if (($op == '=' || $op == 'equals' || $op == 'is') && $row[$field] == $value) {
					return '1';
				} elseif (($op == '!=' || $op == 'is not') && $row[$field] != $value) {
					return '1';
				} elseif (($op == '<' || $op == 'is less than' ) && $row[$field] < $value) {
					return '1';
				} elseif (($op == '>' || $op == 'is greater than') && $row[$field] > $value) {
					return '1';
				} elseif (($op == '<=' || $op == 'is less than or equals' ) && $row[$field] <= $value) {
					return '1';
				} elseif (($op == '>=' || $op == 'is greater than or equals') && $row[$field] >= $value) {
					return '1';
				} elseif ($op == 'contains' && preg_match('/'.preg_quote($value, '/').'/i', $row[$field])) {
					return '1';
				} elseif ($op == 'does not contain' && !preg_match('/'.preg_quote($value, '/').'/i', $row[$field])) {
					return '1';
				} else {
					return '0';
				}
			}
		}
		return '1';
	}
	
	protected function _validate_offset($current_row) {
		if ($this->sort_by === null && $this->offset !== null && $current_row < $this->offset ) return false;
		return true;
	}
	
	protected function _enclose_value($value=null) {
		if ($value !== null && $value != '') {
			$delimiter = preg_quote($this->delimiter, '/');
			$enclosure = preg_quote($this->enclosure, '/');
			if (preg_match("/".$delimiter."|".$enclosure."|\n|\r/i", $value) || ($value{0} == ' ' || substr($value, -1) == ' ')) {
				$value = str_replace($this->enclosure, $this->enclosure.$this->enclosure, $value);
				$value = $this->enclosure.$value.$this->enclosure;
			}
		}
		return $value;
	}
	
	protected function _check_data($file=null) {
		if (empty($this->file_data)) {
			if ($file === null)
		$file = $this->file;
			return $this->load_data($file);
		}
		return true;
	}
	
	protected function _check_count($char, $array, $depth, $preferred) {
		if ($depth == count($array)) {
			$first = null;
			$equal = null;
			$almost = false;
			foreach( $array as $key => $value) {
				if ($first == null) {
					$first = $value;
				} elseif ($value == $first && $equal !== false) {
					$equal = true;
				} elseif ($value == $first+1 && $equal !== false) {
					$equal = true;
					$almost = true;
				} else {
					$equal = false;
				}
			}
			if ($equal) {
				$match = ( $almost ) ? 2 : 1 ;
				$pref = strpos($preferred, $char);
				$pref = ( $pref !== false ) ? str_pad($pref, 3, '0', STR_PAD_LEFT) : '999' ;
				return $pref.$match.'.'.(99999 - str_pad($first, 5, '0', STR_PAD_LEFT));
			} else return false;
		}
	}
	
	protected function _rfile($file=null) {
		if (is_readable($file)) {
			if (!($fh = fopen($file, 'r')) ) return false;
			$data = fread($fh, filesize($file));
			fclose($fh);
			return $data;
		}
		return false;
	}
	
}

?>