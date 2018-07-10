<?php
/*
  Plugin Name: Ninja Popups
  Plugin URI: http://codecanyon.net/item/ninja-popups-for-wordpress/3476479?ref=arscode
  Description: Awesome Popups for Your WordPress!
  Version: 4.5.3
  Author: ArsCode
  Author URI: http://www.arscode.pro/
 */
if (!defined('ABSPATH')) {
    die('-1');
}

define('SNP_OPTIONS', 'snp');
define('SNP_DB_VER', '1.3');
define('SNP_LIBRARY_DIR', 'ninja-popups/');
define('SNP_DEMO_LIBRARY_URL', 'http://demo.arscode.pro/ninja-popups/wp-content/uploads/sites/2/ninja-popups/');
define('SNP_DEMO_LIBRARY_URL_2', '/ninja-popups/wp-content/uploads/sites/2/ninja-popups/');
define('SNP_DEMO_LIBRARY_URL_3', '/wp-content/uploads/sites/2/ninja-popups/');
define('SNP_URL', plugins_url('/', __FILE__));
define('SNP_DIR_PATH', plugin_dir_path(__FILE__));
define('SNP_PROMO_LINK', 'http://codecanyon.net/item/ninja-popups-for-wordpress/3476479?ref=');
DEFINE('SNP_API_URL', 'http://updates.arscode.pro/');

$snp_options = array();
$snp_popups = array();

if (is_admin()) {
    require_once(plugin_dir_path(__FILE__) . '/admin/options.php');
    require_once(plugin_dir_path(__FILE__) . '/admin/init.php');
    require_once(plugin_dir_path(__FILE__) . '/admin/updates.php');
    require_once(plugin_dir_path(__FILE__) . '/include/lists.inc.php');
}

require_once(plugin_dir_path(__FILE__) . '/include/Mobile_Detect.php');
require_once(plugin_dir_path(__FILE__) . '/include/fonts.inc.php');
require_once(plugin_dir_path(__FILE__) . '/include/functions.inc.php');
require_once(plugin_dir_path(__FILE__) . '/include/snp_links.inc.php');

$detect = new Snp_Mobile_Detect;

function snp_get_option($opt_name, $default = null)
{
    global $snp_options;
    
    if (!$snp_options) {
        $snp_options = get_option(SNP_OPTIONS);
    }

    return (!empty($snp_options[$opt_name])) ? $snp_options[$opt_name] : $default;
}

global $snp_ignore_cookies;

$SNP_THEMES = array();
$SNP_THEMES_DIR_2 = apply_filters('snp_themes_dir_2', '');
$SNP_THEMES_DIR = apply_filters('snp_themes_dir', array(plugin_dir_path(__FILE__) . '/themes/', $SNP_THEMES_DIR_2));

function snp_popup_submit()
{
    global $wpdb;

    $result = array();
    $errors = array();

    $post_id = intval($_POST['popup_ID']);
    if ($post_id) {
        $POPUP_META = get_post_meta($post_id);
    }

    $cf_data = array();
    
    $POPUP_META['snp_theme'] = unserialize($POPUP_META['snp_theme'][0]);
    if (!isset($POPUP_META['snp_theme']['mode'])) {
        $POPUP_META['snp_theme']['mode'] = 0;
    }

    if ($POPUP_META['snp_theme']['mode'] == 0) {
        if (isset($_POST['email'])) {
            $_POST['email'] = snp_trim($_POST['email']);
        }

        if (isset($_POST['name'])) {
            $_POST['name'] = trim($_POST['name']);
        }

        if (!snp_is_valid_email($_POST['email'])) {
            $errors['email'] = 1;
        }

        if (isset($_POST['email']) && !$_POST['email']) {
            $errors['email'] = 1;
        }
    }

    require_once(plugin_dir_path(__FILE__) . '/include/recaptcha/src/autoload.php');

    if (snp_get_option('recaptcha_secret_key')) {
        $recaptcha = new \ReCaptcha\ReCaptcha(snp_get_option('recaptcha_secret_key'));
    }
	
    if (
        isset($POPUP_META['snp_bld_cf']) &&
        $POPUP_META['snp_theme']['mode'] == 1 &&
        $post_id
    ) {
        
        $POPUP_META['snp_bld_cf'] = unserialize($POPUP_META['snp_bld_cf'][0]);

        foreach ((array) $POPUP_META['snp_bld_cf'] as $f) {
            if ($f['type'] == 'captcha' && snp_get_option('recaptcha_secret_key')) {
                $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if (!$resp->isSuccess()) {
                    if ($f['validation_message']) {
                        $errors['captcha'] = $f['validation_message'];
                    } else {
                        $errors['captcha'] = 1;
                    }
                }
            } else if ($f['type'] == 'file') {
                if (!empty($_FILES)) {
                    if (
                        isset($_FILES[$f['name']]) && 
                        ($result = snp_upload_file($_FILES[$f['name']]))
                    ) {
                        $cf_data[$f['name']] = $result;
                    }
                }

                if (
                    isset($f['required']) && 
                    $f['required'] == 1 && 
                    !$cf_data[$f['name']]
                ) {
                    if ($f['validation_message']) {
                        $errors[$f['name']] = $f['validation_message'];
                    } else {
                        $errors[$f['name']] = 1;
                    }
                }
            } else {
                if ($f['name-type'] != '') {
                    $f['name'] = $f['name-type'];
                }

                if (isset($f['mailchimp_group']) && !empty($f['mailchimp_group'])) {
                    if (!empty($_POST['mcgroups'][$f['name']])) {
                        $cf_data['mcgroups'][$f['name']] = $_POST['mcgroups'][$f['name']];
                    }

                    if (
                        isset($f['required']) && 
                        $f['required'] == 1 && 
                        (
                            !isset($cf_data['mcgroups'][$f['name']]) || 
                            !$cf_data['mcgroups'][$f['name']]
                        )
                    ) {
                        if ($f['validation_message']) {
                            $errors[$f['name']] = $f['validation_message'];
                        } else {
                            $errors[$f['name']] = 1;
                        }
                    }
                } else {
                    if (strpos($f['name'], '[')) {
                        $f['name'] = substr($f['name'], 0, strpos($f['name'], '['));
                    }

                    if (!empty($_POST[$f['name']])) {
                        $cf_data[$f['name']] = snp_trim($_POST[$f['name']]);
                    }

                    if (
                        isset($f['required']) && 
                        $f['required'] == 1 && 
                        !$cf_data[$f['name']]
                    ) {
                        if ($f['validation_message']) {
                            $errors[$f['name']] = $f['validation_message'];
                        } else {
                            $errors[$f['name']] = 1;
                        }
                    }

                    if (
                        isset($f['required']) && 
                        $f['required'] == 1 && 
                        $f['name'] == 'email'
                    ) {
                        if (!snp_is_valid_email($_POST[$f['name']])) {
                            if ($f['validation_message']) {
                                $errors[$f['name']] = $f['validation_message'];
                            } else {
                                $errors[$f['name']] = 1;
                            }
                        }
                    }
                }

                if (in_array($f['name'],array('email','name'))) {
                    unset($cf_data[$f['name']]);
                }
            }
        }
    }

    if (
        isset($POPUP_META['snp_cf']) && 
        $POPUP_META['snp_theme']['mode'] == 0 && 
        $post_id
    ) {
        $cf = unserialize($POPUP_META['snp_cf'][0]);

        if (isset($cf) && is_array($cf)) {
            foreach ($cf as $f) {
                if (mb_strtolower($f['type']) == 'file') {
                    if (!empty($_FILES)) {
                        if (
                            isset($_FILES[$f['name']]) && 
                            ($result = snp_upload_file($_FILES[$f['name']]))
                        ) {
                            $cf_data[$f['name']] = $result;
                        }
                    }

                    if (
                        isset($f['required']) && 
                        $f['required'] == 1 && 
                        !$cf_data[$f['name']]
                    ) {
                        $errors[$f['name']] = 1;
                    }
                } else {
                    if (isset($f['name'])) {
                        if (strpos($f['name'], '[')) {
                            $f['name'] = substr($f['name'], 0, strpos($f['name'], '['));
                        }
						
                        if (!empty($_POST[$f['name']])) {
                            $cf_data[$f['name']] = snp_trim($_POST[$f['name']]);
                        }
                    }

                    if (isset($f['required']) && $f['required'] == 'Yes' && !$cf_data[$f['name']]) {
                        $errors[$f['name']] = 1;
                    }
                }
            }
        }
    }

    if (count($errors) > 0) {
        $result['Errors'] = $errors;
        $result['Ok'] = false;
    } else {
        $Done = 0;
        if (!empty($_POST['name'])) {
            $names = snp_detect_names($_POST['name']);
        } else {
            $names = array('first' => '', 'last' => '');
        }
        
        $api_error_msg = '';
        
        $ml_manager = snp_get_option('ml_manager');
        if(isset($POPUP_META['snp_ml_send_by_email'][0]) && $POPUP_META['snp_ml_send_by_email'][0] == 1) {
			$ml_manager = "email";	
        }

        if ($ml_manager == 'directmail') {
            require_once SNP_DIR_PATH . '/include/directmail/class.directmail.php';
            
            $form_id = snp_get_option('ml_dm_form_id');

            if ($form_id) {
                $api = new DMSubscribe();
                $retval = $api->submitSubscribeForm($form_id, snp_trim($_POST['email']), $error_message);

                if ($retval) {
                    $Done = 1;
                } else {
                    $api_error_msg = $error_message;
                }
            }
        } else if ($ml_manager == 'sendy') {
            $list_id = $POPUP_META['snp_ml_sendy_list'][0];
            if (!$list_id) {
                $list_id = snp_get_option('ml_sendy_list');
            }

            if ($list_id) {
                $log_list_id = $list_id;
                $options = array(
                    'list' => $list_id,
                    'boolean' => 'true'
                );

                $args['email'] = snp_trim($_POST['email']);
                if (!empty($_POST['name'])) {
                    $args['name'] = $_POST['name'];
                }

                if (count($cf_data) > 0) {
                    $args = array_merge($args, (array) $cf_data);
                }

                $content = array_merge($args, $options);
                $postdata = http_build_query($content);
                $sendy_url = str_replace('/subscribe', '', snp_get_option('ml_sendy_url')) . '/subscribe';
                $ch = curl_init($sendy_url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                $api_result = curl_exec($ch);
                curl_close($ch);

                if (strval($api_result) == 'true' || strval($api_result) == '1' || strval($api_result) == 'Already subscribed.') {
                    $Done = 1;
                } else {
                    $api_error_msg = $api_result;
                }
            }
        } else if ($ml_manager == 'salesautopilot') {
        	require_once SNP_DIR_PATH . '/include/salesautopilot/MailMaster.php';

            $list_id = $POPUP_META['snp_ml_salesautopilot_list'][0];
            if (!$list_id) {
                $list_id = snp_get_option('ml_salesautopilot_list');
            }

            $form_id = $POPUP_META['snp_ml_salesautopilot_form'][0];
            if (!$form_id) {
                $form_id = snp_get_option('ml_salesautopilot_form');
            }
            
            $log_list_id = $list_id;

            if (snp_get_option('ml_salesautopilot_apikey') && snp_get_option('ml_salesautopilot_login')) {
                $rest = new MailMaster($list_id, $form_id, snp_get_option('ml_salesautopilot_login'), snp_get_option('ml_salesautopilot_apikey'));
                
                $args =  new stdClass();
                $args->email = snp_trim($_POST['email']);
                if (!empty($_POST['name'])) {
                    $args -> mssys_firstname = $names['first'];
                    $args -> mssys_lastname = $names['last'];
                }
                
                if (count($cf_data) > 0) {
                    foreach ($cf_data as $k => $v) {
						$args -> $k = $v;
					}
                }

                try {
                    $response = $rest -> subscribe($args);
                    $response = json_decode($response);
                    if (isset($response) && ($response > 0 || $response == -1)) {
                        $Done = 1;
                    } else {
                        $api_error_msg = $response . ' ' . (isset($response) ? var_export($response, true) : '');
                    }
                } catch (Exception $e) {
                    $api_error_msg = var_export($response, true);
                }
            }
        } else if ($ml_manager == 'mailchimp') {
            require_once SNP_DIR_PATH . '/include/mailchimp/MC_Lists.php';

            $ml_mc_list = '';
            if (isset($POPUP_META['snp_ml_mc_list'][0])) {
                $ml_mc_list = $POPUP_META['snp_ml_mc_list'][0];
            }

            if (!$ml_mc_list) {
                $ml_mc_list = snp_get_option('ml_mc_list');
            }

            $log_list_id = $ml_mc_list;
            if (snp_get_option('ml_mc_apikey') && $ml_mc_list) {
                $rest = new MC_Lists(snp_get_option('ml_mc_apikey'));

                $args = array();
                $args['email_address'] = snp_trim($_POST['email']);
                $merge_fields = array();
                if (!empty($_POST['name'])) {
                    $merge_fields = array(
                        'FNAME' => $names['first'],
                        'LNAME' => $names['last']
                    );
                }

                if (count($cf_data) > 0) {
                    $merge_fields = array_merge($merge_fields, (array) $cf_data);
                }

                
                if (is_array($merge_fields) && count($merge_fields)>0) {
                    if (in_array('mcgroups', array_keys($merge_fields))) {
                        $args['interests'] = array();

                        foreach (snp_array_values_recursive($merge_fields['mcgroups']) as $v) {
                            $args['interests'][$v] = true;
                        }

                        unset($merge_fields['mcgroups']);
                    }

                    $args['merge_fields'] = $merge_fields;
                }

                try {
                    $double_optin = snp_get_option('ml_mc_double_optin');
                    
                    if ($double_optin == 1) {
                        $args['status'] = 'pending';
                        $args['status_if_new'] = 'pending';
                    } else {
                        $args['status'] = 'subscribed';
                        $args['status_if_new'] = 'subscribed';
                    }
                   
                    $retval = $rest->addMember($ml_mc_list, $args);
                    
                    $retval = json_decode($retval);

                    if (isset($retval->id) || (isset($retval->title) && $retval->title == 'Member Exists')) {
                        $Done = 1;
                    } else {
                        $api_error_msg = $retval->detail . ' ' . (isset($retval->errors) ? var_export($retval->errors, true) : '');
                    }
                } catch (Exception $e) {
                    $api_error_msg = var_export($response->errors, true);
                }
            }
        } elseif ($ml_manager == 'sendgrid') {
            require_once SNP_DIR_PATH . '/include/sendgrid/sendgrid_api.php';

            $ml_sendgrid_list = $POPUP_META['snp_ml_sendgrid_list'][0];
            if (!$ml_sendgrid_list){
                $ml_sendgrid_list = snp_get_option('ml_sendgrid_list');
            }

            $log_list_id = $ml_sendgrid_list;

            if (
                snp_get_option('ml_sendgrid_username') &&
                snp_get_option('ml_sendgrid_password') &&
                $ml_sendgrid_list
            ) {
                $rest = new snp_sendgrid_class(snp_get_option('ml_sendgrid_username'), snp_get_option('ml_sendgrid_password'));
                
                $args = array();
                $args['email'] = snp_trim($_POST['email']);
                if (!empty($_POST['name'])) {
                    $args['first_name'] = $names['first'];
                    $args['last_name'] = $names['last'];
                }

                if (count($cf_data) > 0) {
                    foreach($cf_data as $k => $v) {
						$args[$k] = $v;
					}
                }

                try {
                	$result = $rest -> addSubscriber($ml_sendgrid_list, $args);

                    if (isset($result) && $result) {
                        $Done = 1;
                    } else {
                        $api_error_msg = $e->getMessage();
                    }
                } catch (Exception $e) {
                    $api_error_msg = var_export($response->errors, true);
                }
            }
        } else if ($ml_manager == 'sendinblue') {
            require_once SNP_DIR_PATH . '/include/sendinblue/Mailin.php';

            $ml_sendinblue_list = $POPUP_META['snp_ml_sendinblue_list'][0];
            if (!$ml_sendinblue_list) {
                $ml_sendinblue_list = snp_get_option('ml_sendinblue_list');
            }

            $log_list_id = $ml_sendinblue_list;

            if (snp_get_option('ml_sendinblue_apikey') && $ml_sendinblue_list) {
                $api = new Mailin("https://api.sendinblue.com/v2.0", snp_get_option('ml_sendinblue_apikey'));
                if (!empty($_POST['name'])) {
                    $atributes = array(
                        'NAME' => $names['first'],
                        'SURNAME' => $names['last']
                    );
                }

                if (count($cf_data) > 0) {
                    foreach ($cf_data as $field => $value) {
                        $atributes[$field] = $value;
                    }
                }

                $args = array(
                    "email"      => snp_trim($_POST['email']),
                    "attributes" => $atributes,
                    "listid"     => array($ml_sendinblue_list),
                );

                $response = $api->create_update_user($args);
                if (isset($response['code']) && $response['code'] == 'success') {
                    $Done = 1;
                } else {
                    $api_error_msg = 'Api connection problem';
                }
            }
        } else if ($ml_manager == 'freshmail') {
            require_once SNP_DIR_PATH . '/include/freshmail/class.rest.php';

            $ml_freshmail_list = $POPUP_META['snp_ml_freshmail_list'][0];
            if (!$ml_freshmail_list) {
                $ml_freshmail_list = snp_get_option('ml_freshmail_list');
            }

            $log_list_id = $ml_freshmail_list;

            if (
                snp_get_option('ml_freshmail_apisecret') &&
                snp_get_option('ml_freshmail_apikey') &&
                $ml_freshmail_list
            ) {
                $rest = new FmRestAPI();
                $rest->setApiKey(snp_get_option('ml_freshmail_apikey'));
                $rest->setApiSecret(snp_get_option('ml_freshmail_apisecret'));

                $args = array();
                $args['email'] = snp_trim($_POST['email']);
                $args['list'] = $ml_freshmail_list;
                if (count($cf_data) > 0) {
                    $args['custom_fields'] = (array) $cf_data;
                }

                $double_optin = snp_get_option('ml_freshmail_double_optin');
                if ($double_optin == 1) {
                    $args['state'] = 2;
                    $args['confirm'] = 1;
                } else {
                    $args['state'] = 1;
                }

                try {
                    $response = $rest->doRequest('subscriber/add', $args);

                    $Done = 1;
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'sendlane') {
            require_once SNP_DIR_PATH . '/include/sendlane/snp_sendlane.php';

            $ml_sendlane_list = $POPUP_META['snp_ml_sendlane_list'][0];
            if (!$ml_sendlane_list) {
                $ml_sendlane_list = snp_get_option('ml_sendlane_list');
            }

            $log_list_id = $ml_sendlane_list;

            if (
                snp_get_option('ml_sendlane_apikey') &&
                snp_get_option('ml_sendlane_hash') &&
                snp_get_option('ml_sendlane_subdomain') &&
                $ml_sendlane_list
            ) {
                $rest = new snp_sendlane(snp_get_option('ml_sendlane_apikey'), snp_get_option('ml_sendlane_hash'), snp_get_option('ml_sendlane_subdomain'));

                $args = array();
                $args['list_id'] = $ml_sendlane_list;
                
                $name = '';
                if (!empty($_POST['name'])) {
                    $name = $names['first'].' '.$names['last'];
                }

                $args['email'] = $name.'<'.snp_trim($_POST['email']).'>';
                try {
                    $response = $rest->subscribe($args);

                    $Done = 1;
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'customerio') {
            require_once SNP_DIR_PATH . '/include/customerio/snp_customerio.php';
            
            $log_list_id = 'customer.io - no lists';
            
            if (
                snp_get_option('ml_customerio_sitekey') &&
                snp_get_option('ml_customerio_apikey')
            ) {
                $rest = new snp_customerio(snp_get_option('ml_customerio_apikey'), snp_get_option('ml_customerio_sitekey'));
                
                $args = array();
                $args['email'] = snp_trim($_POST['email']);
                if (!empty($_POST['name'])) {
                    $args['name'] = $_POST['name'];
                }

                if (count($cf_data) > 0) {
                    foreach($cf_data as $k => $v) {
                        $args[$k] = $v;
                    }
                }

                $response = $rest->subscribe($args);
                if (strlen($response) < 5 ){
                    $Done = 1;
                }
            }
        } else if ($ml_manager == 'mailrelay') {
            require_once SNP_DIR_PATH . '/include/mailrelay/snp_mailrelay.php';

            $ml_mailrelay_list = $POPUP_META['snp_ml_mailrelay_list'][0];
            if (!$ml_mailrelay_list) {
                $ml_mailrelay_list = snp_get_option('ml_mailrelay_list');
            }

            $log_list_id = $ml_mailrelay_list;

            if (
                snp_get_option('ml_mailrelay_apikey') &&
                snp_get_option('ml_mailrelay_address') &&
                $ml_mailrelay_list
            ) {
                $rest = new snp_mailrelay(snp_get_option('ml_mailrelay_apikey'), snp_get_option('ml_mailrelay_address'));

                $args = array();
                $args['email'] = snp_trim($_POST['email']);
                $args['groups'] = array($ml_mailrelay_list);
                $args['name'] = $_POST['name'];

                if (count($cf_data) > 0) {
                    $args['customFields'] = array();
                    foreach($cf_data as $k => $v) {
                        $args['customFields'][$k] = $v;
                    }
                }

                try
                {
                    $response = $rest->subscribe($args);
                    if (isset($response) && ($response->status == 1 || $response->error == 'El email ya existe')) {
                        $Done = 1;
                    }
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'mailup') {
            require_once SNP_DIR_PATH . '/include/mailup/snp_mailup.php';

            $ml_mailup_list = $POPUP_META['snp_ml_mailup_list'][0];
            if (!$ml_mailup_list) {
                $ml_mailup_list = snp_get_option('ml_mailup_list');
            }

            $log_list_id = $ml_mailup_list;

            if (
                snp_get_option('ml_mailup_clientid') && 
                snp_get_option('ml_mailup_clientsecret') &&
                snp_get_option('ml_mailup_login') &&
                snp_get_option('ml_mailup_password') &&
                $ml_mailup_list
            ) {           
                $rest = new snp_mailup(snp_get_option('ml_mailup_clientid'), snp_get_option('ml_mailup_clientsecret'), snp_get_option('ml_mailup_login'), snp_get_option('ml_mailup_password'));

                $args = array();
                $args['Email'] = snp_trim($_POST['email']);
                $args['Name'] = $_POST['name'];
                $args['Fields'] = array();
                if (!empty($_POST['name'])) {
                    $args['Fields'][] = array('Id' => 1, "Value" => $names['first']);
                    $args['Fields'][] = array('Id' => 2, "Value" => $names['last']);
                }
                
                if (count($cf_data) > 0) {
                    foreach($cf_data as $k => $v) {
                        $args['Fields'][] = array('Id' => $k, "Value" => $v);
                    }
                }

                $double_optin = snp_get_option('ml_mailup_double_optin');
                if ($double_optin == 1) {
                    $confirm = true;
                } else {
                    $confirm = false;
                }

                try {
                    $response = $rest->subscribe($ml_mailup_list, $args, $confirm);

                    if (isset($response) && is_int($response)) {
                        $Done = 1;
                    }
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'ontraport') {
            require_once SNP_DIR_PATH . '/include/ontraport/snp_ontraport.php';
            
            $ml_ontraport_list = $POPUP_META['snp_ml_ontraport_list'][0];
            if (!$ml_ontraport_list) {
                $ml_ontraport_list = snp_get_option('ml_ontraport_list');
            }

            $log_list_id = $ml_ontraport_list;
            
            if (
                snp_get_option('ml_ontraport_apiid') &&
                snp_get_option('ml_ontraport_apikey') &&
                $ml_ontraport_list
            ) {                
                $rest = new snp_ontraport(snp_get_option('ml_ontraport_apiid'), snp_get_option('ml_ontraport_apikey'));               
                $args = '<contact>
						    <Group_Tag name="Contact Information">
						    <field name="Email">'. snp_trim($_POST['email']) .'</field>';
				
                if (!empty($_POST['name'])) {
                    $args .=  '<field name="First Name">'. $names['first'] .'</field>
                    		   <field name="Last Name">'. $names['last'] .'</field>';
                }	          
                
                if (count($cf_data) > 0) {
                    foreach($cf_data as $k => $v) {
						$args .= '<field name="'. $k .'">'. $v .'</field>';
					}
                }

                $args .= '</Group_Tag>';
                $args .= '<Group_Tag name="Sequences and Tags">
                			<field name="Contact Tags">'. $ml_ontraport_list .'</field>
                		</Group_Tag>
						</contact>';   
                try {
                    $response = $rest->subscribe($args);

                    if(isset($response) && $response == 'Success') {
						$Done = 1;
					}
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'sendreach') {
            require_once SNP_DIR_PATH . '/include/sendreach/sendreach_api.php';

            $ml_sendreach_list = $POPUP_META['snp_ml_sendreach_list'][0];
            if (!$ml_sendreach_list) {
                $ml_sendreach_list = snp_get_option('ml_sendreach_list');
            }

            $log_list_id = $ml_sendreach_list;
            
            if (
                snp_get_option('ml_sendreach_pubkey') &&
                snp_get_option('ml_sendreach_privkey') &&
                $ml_sendreach_list
            ) {
                $rest = new snp_sendreach(snp_get_option('ml_sendreach_pubkey'), snp_get_option('ml_sendreach_privkey'));

                $args = array();
                $args['EMAIL'] = snp_trim($_POST['email']);
                if (!empty($_POST['name'])) {
                    $args = array_merge($args, array(
                        'FNAME' => $names['first'],
                        'LNAME' => $names['last']
                    ));
                }

                if (count($cf_data) > 0) {
                    $args = array_merge($args, $cf_data);
                }

                try {
                    $response = $rest->subscribe($args, $ml_sendreach_list);
                    $response = json_decode($response, true);

                    if(isset($response) && ($response['status'] == 'success' || $response['error'] == 'The subscriber already exists in this list.')) {
						 $Done = 1;	
					}
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'sendpulse') {
            require_once SNP_DIR_PATH . '/include/sendpulse/sendpulse.php';

            $ml_sendpulse_list = $POPUP_META['snp_ml_sendpulse_list'][0];
            if (!$ml_sendpulse_list) {
                $ml_sendpulse_list = snp_get_option('ml_sendpulse_list');
            }

            $log_list_id = $ml_sendpulse_list;
            
            if (
                snp_get_option('ml_sendpulse_id') &&
                snp_get_option('ml_sendpulse_apisecret') && 
                $ml_sendpulse_list
            ) {
            	$rest = new snp_sendpulse(snp_get_option('ml_sendpulse_id'), snp_get_option('ml_sendpulse_apisecret'));

                $args = array();
                $args['email'] = snp_trim($_POST['email']);
                $args['variables'] = array();
                if (count($cf_data) > 0) {
                    $args['variables'] = (array) $cf_data;
                }

                if (!empty($_POST['name'])) {
                    $args['variables']['name'] = $_POST['name'];
                }

                try {
                    $response = $rest->subscribe($args, $ml_sendpulse_list);

                    if(isset($response) && $response->result === true) {
						$Done = 1;	
					}
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'mailjet') {
            require_once SNP_DIR_PATH . '/include/mailjet/mailjet_class.php';
            
            $ml_mailjet_list = $POPUP_META['snp_ml_mailjet_list'][0];
            if (!$ml_mailjet_list) {
                $ml_mailjet_list = snp_get_option('ml_mailjet_list');
            }

            $log_list_id = $ml_mailjet_list;
            
            if (
                snp_get_option('ml_mailjet_apisecret') &&
                snp_get_option('ml_mailjet_apikey') && 
                $ml_mailjet_list
            ) {
                $rest = new snp_mailjet(snp_get_option('ml_mailjet_apikey'), snp_get_option('ml_mailjet_apisecret'));

                $param = array();
                if (count($cf_data) > 0) {
                	foreach($cf_data as $k => $v) {
						$param[] = array('Name' => $k, 'Value' => $v);
					}
                }

                try {
                    $response = $rest->subscribe($ml_mailjet_list, snp_trim($_POST['email']), $param);
                    
                    if(isset($response) && $response->Count == 1) {
						$Done = 1;	
					}
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'agilecrm') {
            require_once SNP_DIR_PATH . '/include/agilecrm/agilecrm_api.php';

            $ml_agilecrm_tag = $POPUP_META['snp_ml_agilecrm_tag'][0];
            if (!$ml_agilecrm_tag) {
                $ml_agilecrm_tag = snp_get_option('ml_agilecrm_tag');
            }

            $ml_agilecrm_useremail = $POPUP_META['snp_ml_agilecrm_useremail'][0];
            if (!$ml_agilecrm_useremail) {
                $ml_agilecrm_useremail = snp_get_option('ml_agilecrm_useremail');
            }
            
            $log_list_id = $ml_agilecrm_tag;

            if (
                snp_get_option('ml_agilecrm_apikey') &&
                snp_get_option('ml_agilecrm_userdomain') &&
                $ml_agilecrm_useremail
            ) {
                $rest = new snp_agilecrm_class(snp_get_option('ml_agilecrm_apikey'), snp_get_option('ml_agilecrm_userdomain'), $ml_agilecrm_useremail);

                $args = array();
                $args['properties'] = array();
                if (isset($ml_agilecrm_tag)) {
                	$tags = explode(',', $ml_agilecrm_tag);
                	$n = count($tags);
                	for($i=0; $i<$n; $i++) {
						$tags[$i] = trim($tags[$i]);
					}
					$args['tags'] = $tags;
				}

				$args['properties'][] = array(
					"name"=>"email",
				    "value"=> snp_trim($_POST['email']),
				    "type"=>"SYSTEM"
			    );

                if (!empty($_POST['name'])) {
                    $args['properties'][] = array(
	                    "name"=>"first_name",
					    "value"=> $names['first'],
					    "type"=>"SYSTEM"
                    );

                    $args['properties'][] = array(
	                    "name"=>"last_name",
					    "value"=> $names['last'],
					    "type"=>"SYSTEM"
                    );
            	}

                if (count($cf_data) > 0) {
                	foreach($cf_data as $k => $v) {
                		if (
                            $k == 'phone' ||
                            $k == 'address' ||
                            $k == 'website' ||
                            $k == 'title' ||
                            $k == 'company'
                        ) {
							$args['properties'][] = array(
			                    "name"=>$k,
							    "value"=> $v,
							    "type"=>"SYSTEM"
	                    	);
						} else {
							$args['properties'][] = array(
			                    "name"=>$k,
							    "value"=> $v,
							    "type"=>"CUSTOM"
	                    	);	
						}
						
					}   
                }

                try {
                	$args = json_encode($args);
                    $response = $rest->curl_wrap("contacts", $args, "POST");
                    $decoded = json_decode($response);

                    if(!empty($response) && ($response == 'Sorry, duplicate contact found with the same email address.' || $decoded -> type == 'PERSON' )) {
						 $Done = 1;
					}
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'salesmanago') {
            require_once SNP_DIR_PATH . '/include/salesmanago/salesmanago.php';

            $ml_salesmanago_tag = $POPUP_META['snp_ml_salesmanago_tag'][0];
            if (!$ml_salesmanago_tag){
                $ml_salesmanago_tag = snp_get_option('ml_salesmanago_tag');
            }

            $ml_salesmanago_useremail = $POPUP_META['snp_ml_salesmanago_useremail'][0];
            if (!$ml_salesmanago_useremail) {
                $ml_salesmanago_useremail = snp_get_option('ml_salesmanago_useremail');
            }
            
            $log_list_id = $ml_salesmanago_tag;

            if (
                snp_get_option('ml_salesmanago_apikey') &&
                snp_get_option('ml_salesmanago_apisecret') &&
                snp_get_option('ml_salesmanago_endpoint') &&
                snp_get_option('ml_salesmanago_clientid') && 
                $ml_salesmanago_useremail
            ) {
                $rest = new snp_salesmanago(
                    snp_get_option('ml_salesmanago_endpoint'),
                    snp_get_option('ml_salesmanago_clientid'),
                    snp_get_option('ml_salesmanago_apisecret'),
                    snp_get_option('ml_salesmanago_apikey'),
                    $ml_salesmanago_useremail
                );

                if (isset($ml_salesmanago_tag)) {
                	$tags = explode(',', $ml_salesmanago_tag);
                	$n = count($tags);
                	for($i=0; $i<$n; $i++)
                	{
						$tags[$i] = trim($tags[$i]);
					}
				}

				$args = array();
				$args['email'] = snp_trim($_POST['email']);
                if (!empty($_POST['name'])) {
                    $args['name'] = $_POST['name'];
            	}

                if (count($cf_data) > 0) {
                	foreach ($cf_data as $k => $v) {
						$args[$k] = $v;	
					}
                }

                try {
                    $response = json_decode($rest->subscribe($args, $tags), true);

                    if(!empty($response) && is_array($response) && $response['success'] === true) {
						 $Done = 1;
					}
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'elasticemail') {
            require_once SNP_DIR_PATH . '/include/elasticemail/ElasticEmail.php';

            $ml_elasticemail_list = $POPUP_META['snp_ml_elasticemail_list'][0];
            if (!$ml_elasticemail_list) {
                $ml_elasticemail_list = snp_get_option('ml_elasticemail_list');
            }

            $log_list_id = $ml_elasticemail_list;
            
            if (snp_get_option('ml_elasticemail_apikey') && $ml_elasticemail_list) {
                $rest = new ElasticEmail(snp_get_option('ml_elasticemail_apikey'));

                $args = array();
                $args['email'] = snp_trim($_POST['email']);
                $args['listname'] = $ml_elasticemail_list;
                if (!empty($_POST['name'])) {
                    $args['firstname'] = $names['first'];
                    $args['lastname'] =  $names['last'];
                }

                if (count($cf_data) > 0) {
                	foreach($cf_data as $k => $v) {
						$args[$k] = $v;
					}
                }

                try {
                    $response = $rest->subscribe($args);
                    if(isset($response) && ($response == 'Contact created.' || strpos($response, 'Error: Contact is already on the list') !== false))
                    {
                    	$Done = 1;
                    }
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'benchmarkemail') {
            require_once SNP_DIR_PATH . '/include/benchmarkemail/snp_benchmark_class.php';

            $ml_benchmarkemail_list = $POPUP_META['snp_ml_benchmarkemail_list'][0];
            if (!$ml_benchmarkemail_list) {
                $ml_benchmarkemail_list = snp_get_option('ml_benchmarkemail_list');
            }

            $log_list_id = $ml_benchmarkemail_list;

            if (snp_get_option('ml_benchmarkemail_apikey') && $ml_benchmarkemail_list) {
                $rest = new snp_benchmark_class(snp_get_option('ml_benchmarkemail_apikey'));

                $args = array();
                $args['contacts']['email'] = snp_trim($_POST['email']);
                if (!empty($_POST['name'])) {
                	$args['contacts']['firstname'] = $names['first'];
                	$args['contacts']['lastname'] = $names['last'];
            	}

                $args['listID'] = $ml_benchmarkemail_list;
                
                if (count($cf_data) > 0) {
                	foreach($cf_data as $k => $v) {
						$args['contacts'][$k] = $v;
					}
                }

                $double_optin = snp_get_option('ml_benchmarkemail_double_optin');
                if ($double_optin == 1) {
                	$args['optin'] = 1;
                } else {
                    $args['optin'] = 0;
                }

                try {
                    $response = $rest->subscribe($args);

                    if (isset($response) && $response == 1) {
					    $Done = 1;
					}
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'myemma') {
            require_once SNP_DIR_PATH . '/include/myemma/Emma.php';

            $ml_myemma_list = $POPUP_META['snp_ml_myemma_list'][0];
            if (!$ml_myemma_list) {
                $ml_myemma_list = snp_get_option('ml_myemma_list');
            }

            $log_list_id = $ml_myemma_list;
            
            if (
                snp_get_option('ml_myemma_account_id') &&
                snp_get_option('ml_myemma_pubkey') &&
                snp_get_option('ml_myemma_privkey') &&
                $ml_myemma_list
            ) {
                $rest = new Emma(snp_get_option('ml_myemma_account_id'), snp_get_option('ml_myemma_pubkey'), snp_get_option('ml_myemma_privkey'));

                $args = array();
                $args['email'] = snp_trim($_POST['email']);
                $args['group_ids'] = array($ml_myemma_list);
                if (count($cf_data) > 0) {
                    $args['fields'] = (array) $cf_data;
                }

                try {
                    $response = $rest->membersAddSingle($args);

                    $Done = 1;
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'mailerlite') {
            if (!class_exists('ML_Subscribers')) {
                require_once SNP_DIR_PATH . '/include/mailerlite/ML_Subscribers.php';
            }

            $ml_mailerlite_list = $POPUP_META['snp_ml_mailerlite_list'][0];
            if (!$ml_mailerlite_list) {
                $ml_mailerlite_list = snp_get_option('ml_mailerlite_list');
            }

            $log_list_id = $ml_mailerlite_list;

            if (snp_get_option('ml_mailerlite_apikey') && $ml_mailerlite_list) {
                $rest = new ML_Subscribers(snp_get_option('ml_mailerlite_apikey'));

                $args = array();
                $args['email'] = snp_trim($_POST['email']);
                $args['name'] = $_POST['name'];

                if (count($cf_data) > 0) {
                    $args['fields'] = array();
                    foreach ($cf_data as $field => $value) {
                        array_push($args['fields'], array(
                            'name' => $field,
                            'value' => $value
                        ));
                    }
                }

                try {
                    $response = $rest->setId($ml_mailerlite_list)->add( $args );
                    
                    $Done = 1;
                } catch (Exception $e) {
                   $api_error_msg = $e->getMessage();
                }
            }
        }
        else if ($ml_manager == 'rocketresponder') {
            require_once SNP_DIR_PATH . '/include/rocketresponder/RocketResponder.class.php';

            $ml_rocketresponder_list=$POPUP_META['snp_ml_rocketresponder_list'][0];
            if(!$ml_rocketresponder_list) {
                $ml_rocketresponder_list=snp_get_option('ml_rocketresponder_list');
            }
            
            $log_list_id = $ml_rocketresponder_list;
            
            if (
                snp_get_option('ml_rocketresponder_apipublic') && 
                snp_get_option('ml_rocketresponder_apiprivate') && 
                $ml_rocketresponder_list
            ) {
                $api = new RocketResponder(snp_get_option('ml_rocketresponder_apipublic'), snp_get_option('ml_rocketresponder_apiprivate'), 1);
                
                $email = snp_trim($_POST['email']);
                $LID = $ml_rocketresponder_list;
                
                if (isset($_POST['name'])) {
                    $XTRA['name'] = $_POST['name'];
                } else {
                    $XTRA = NULL;
                }

                try {
                    $response = $api->subscribe($email, $LID, $XTRA);

                    $Done = 1;
                } catch (Exception $e) {
                    $api_error_msg = $e->getMessage();
                }
            }
        } else if ($ml_manager == 'activecampaign') {
        	if (!class_exists('ActiveCampaign')) {
            	require_once SNP_DIR_PATH . '/include/activecampaign/ActiveCampaign.class.php';
			}
			
            $ml_activecampaign_list = $POPUP_META['snp_ml_activecampaign_list'][0];
            if (!$ml_activecampaign_list) {
                $ml_activecampaign_list = snp_get_option('ml_activecampaign_list');
            }

            $log_list_id = $ml_activecampaign_list;
            
            if (
                snp_get_option('ml_activecampaign_apiurl') &&
                snp_get_option('ml_activecampaign_apikey') &&
                $ml_activecampaign_list
            ) {
                $ac = new ActiveCampaign(snp_get_option('ml_activecampaign_apiurl'), snp_get_option('ml_activecampaign_apikey'));
                
                $args = array(
                    "email" => snp_trim($_POST['email']),
                    "p[{$ml_activecampaign_list}]" => $ml_activecampaign_list,
                    "status[{$ml_activecampaign_list}]" => 1, // "Active" status
                );
                
                if (!empty($_POST['name'])) {
                    $args['first_name'] = $names['first'];
                    $args['last_name'] = $names['last'];
                }

                if (count($cf_data) > 0) {
                    foreach ($cf_data as $field => $value) {
                        $args['field[%' . $field . '%,0]'] = $value;
                    }
                }

                $double_optin = snp_get_option('ml_activecampaign_double_optin');
                if ($double_optin == 1) {
                     $args["instantresponders[{$ml_activecampaign_list}]"] = 1;
                }

                $form = snp_get_option('ml_activecampaign_form');
                if (isset($POPUP_META['snp_ml_activecampaign_form'][0]) && $POPUP_META['snp_ml_activecampaign_form'][0]) {
                    $form = $POPUP_META['snp_ml_activecampaign_form'][0];
                }
                
                if ($form) {
                     $args["form"] = $form;
                }

                $response = $ac->api("contact/sync", $args);
                if (!(int) $response->success) {
                    $api_error_msg = $response->error;
                } else {
                    $Done = 1;
                }
            }
        } else if ($ml_manager == 'egoi') {
            require_once SNP_DIR_PATH . '/include/egoi/snp_egoi.php';

            $ml_egoi_apikey = snp_get_option('ml_egoi_apikey');

            $rest = new snp_egoi($ml_egoi_apikey);

            try {
                $ml_egoi_list = $POPUP_META['snp_ml_egoi_list'][0];
                if (!$ml_egoi_list) {
                    $ml_egoi_list = snp_get_option('ml_egoi_list');
                }

                $log_list_id = $ml_egoi_list;
                
                $args = array(
                    'listID' => $ml_egoi_list,
                    'email' => snp_trim($_POST['email']),
                );
                
                $double_optin = snp_get_option('ml_egoi_double_optin');
                if ($double_optin == 1) {
                    $args['status'] = 0;
                } else {
                    $args['status'] = 1;
                }

                if (!empty($_POST['name'])) {
                    $args['first_name'] = $names['first'];
                    $args['last_name'] = $names['last'];
                }

                if (count($cf_data) > 0) {
                    foreach ($cf_data as $k => $v) {
                        $args[$k] = $v;
                    }
                }

                $res = $rest->subscribe($args);

                if ($res === true) {
                    $Done = 1;
                }
            } catch (Exception $e) {
                $api_error_msg = $e->getMessage();
            }
        } else if ($ml_manager == 'getresponse') {
            require_once SNP_DIR_PATH . '/include/getresponse/jsonRPCClient.php';

            $ml_gr_apikey = snp_get_option('ml_gr_apikey');

            $api = new jsonRPCClient('http://api2.getresponse.com');
            try {
                $ml_gr_list = $POPUP_META['snp_ml_gr_list'][0];
                if (!$ml_gr_list) {
                    $ml_gr_list = snp_get_option('ml_gr_list');
                }
                
                $log_list_id = $ml_gr_list;
                
                $args = array(
                    'campaign' => $ml_gr_list,
                    'email' => snp_trim($_POST['email']),
                    'cycle_day' => '0',
                );

                if (!empty($_POST['name'])) {
                    $args['name'] = $_POST['name'];
                }

                if (count($cf_data) > 0) {
                    $CustomFields = array();
                    foreach ($cf_data as $k => $v) {
                        $CustomFields[] = array(
                            'name' => $k,
                            'content' => $v
                        );
                    }

                    $args['customs'] = $CustomFields;
                }

                $res = $api->add_contact($ml_gr_apikey, $args);

                $Done = 1;
            } catch (Exception $e) {
                $api_error_msg = $e->getMessage();
            }
        } else if ($ml_manager == 'campaignmonitor') {
            require_once SNP_DIR_PATH . '/include/campaignmonitor/csrest_subscribers.php';
            
            $ml_cm_list = $POPUP_META['snp_ml_cm_list'][0];
            if (!$ml_cm_list) {
                $ml_cm_list = snp_get_option('ml_cm_list');
            }
            
            $log_list_id = $ml_cm_list;
            
            $wrap = new CS_REST_Subscribers($ml_cm_list, snp_get_option('ml_cm_apikey'));
            
            $args = array(
                'EmailAddress' => snp_trim($_POST['email']),
                'Resubscribe' => true
            );

            if (!empty($_POST['name'])) {
                $args['Name'] = $_POST['name'];
            }

            if (count($cf_data) > 0) {
                $CustomFields = array();
                foreach ($cf_data as $k => $v) {
                    $CustomFields[] = array(
                        'Key' => $k,
                        'Value' => $v
                    );
                }

                $args['CustomFields'] = $CustomFields;
            }
            
            $res = $wrap->add($args);

            if ($res->was_successful()) {
                $Done = 1;
            } else {
                $api_error_msg = 'Failed with code ' . $res->http_status_code;
            }
        } else if ($ml_manager == 'icontact') {
            require_once SNP_DIR_PATH . '/include/icontact/iContactApi.php';

            iContactApi::getInstance()->setConfig(array(
                'appId' => snp_get_option('ml_ic_addid'),
                'apiPassword' => snp_get_option('ml_ic_apppass'),
                'apiUsername' => snp_get_option('ml_ic_username')
            ));

            if (snp_get_option('ml_ic_double_optin') == 1) {
                $double_optin = 'pending';
            } else {
                $double_optin = 'normal';
            }
            
            $oiContact = iContactApi::getInstance();
            $res1 = $oiContact->addContact(
                snp_trim($_POST['email']),
                $double_optin,
                null,
                (isset($names['first']) ? $names['first'] : ''),
                (isset($names['last']) ? $names['last'] : ''),
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null
            );

            if ($res1->contactId) {
                $ml_ic_list = $POPUP_META['snp_ml_ic_list'][0];
                if (!$ml_ic_list) {
                    $ml_ic_list = snp_get_option('ml_ic_list');
                }

                $log_list_id = $ml_ic_list;
                
                if ($oiContact->subscribeContactToList($res1->contactId, $ml_ic_list, $double_optin)) {
                    $Done = 1;
                }
            } else {
                $api_error_msg = 'iContact Problem!';
            }
        } else if ($ml_manager == 'constantcontact') {
            require_once SNP_DIR_PATH . '/include/constantcontact/class.cc.php';
            
            $cc = new constantcontact(snp_get_option('ml_cc_username'), snp_get_option('ml_cc_pass'));
            $send_welcome = snp_get_option('ml_cc_send_welcome');
            if ($send_welcome == 1) {
                $cc->set_action_type('contact');
            }
            $email = snp_trim($_POST['email']);

            $contact_list = $POPUP_META['snp_ml_cc_list'][0];
            if (!$contact_list) {
                $contact_list = snp_get_option('ml_cc_list');
            }
            
            $log_list_id = $contact_list;

            $extra_fields = array();
            if (!empty($names['first'])) {
                $extra_fields['FirstName'] = $names['first'];
            }

            if (!empty($names['last'])) {
                $extra_fields['LastName'] = $names['last'];
            }

            if (count($cf_data) > 0) {
                $extra_fields = array_merge($extra_fields, (array) $cf_data);
            }

            $contact = $cc->query_contacts($email);
            if ($contact) {
                $status = $cc->update_contact($contact['id'], $email, $contact_list, $extra_fields);
                
                if ($status) {
                    $Done = 1;
                } else {
                    $api_error_msg = "Contact Operation failed: " . $cc->http_get_response_code_error($cc->http_response_code);
                }
            } else {
                $new_id = $cc->create_contact($email, $contact_list, $extra_fields);
                
                if ($new_id) {
                    $Done = 1;
                } else {
                    $api_error_msg = "Contact Operation failed: " . $cc->http_get_response_code_error($cc->http_response_code);
                }
            }
        } else if ($ml_manager == 'madmimi') {
            require_once SNP_DIR_PATH . '/include/madmimi/MadMimi.class.php';
            
            if (snp_get_option('ml_madm_username') && snp_get_option('ml_madm_apikey')) {
                $mailer = new MadMimi(snp_get_option('ml_madm_username'), snp_get_option('ml_madm_apikey'));
                $user = array('email' => snp_trim($_POST['email']));
                if (!empty($names['first'])) {
                    $user['FirstName'] = $names['first'];
                }

                if (!empty($names['last'])) {
                    $user['LastName'] = $names['last'];
                }

                if (count($cf_data) > 0) {
                    $user = array_merge($user, (array) $cf_data);
                }

                $ml_madm_list = $POPUP_META['snp_ml_madm_list'][0];
                if (!$ml_madm_list) {
                    $ml_madm_list = snp_get_option('ml_madm_list');
                }

                $log_list_id = $ml_madm_list;
                
                $user['add_list'] = $ml_madm_list;
                
                $res = $mailer->AddUser($user);
                
                $Done = 1;
            }
        } else if ($ml_manager == 'infusionsoft') {
            require_once SNP_DIR_PATH . '/include/infusionsoft/infusionsoft.php';
            
            if (snp_get_option('ml_inf_subdomain') && snp_get_option('ml_inf_apikey')) {
                $infusionsoft = new Infusionsoft(snp_get_option('ml_inf_subdomain'), snp_get_option('ml_inf_apikey'));
                $user = array('Email' => snp_trim($_POST['email']));
                if (!empty($names['first'])) {
                    $user['FirstName'] = $names['first'];
                }

                if (!empty($names['last'])) {
                    $user['LastName'] = $names['last'];
                }

                if (count($cf_data) > 0) {
                    $user = array_merge($user, (array) $cf_data);
                }

                $ml_inf_list = $POPUP_META['snp_ml_inf_list'][0];
                if (!$ml_inf_list) {
                    $ml_inf_list = snp_get_option('ml_inf_list');
                }

                $log_list_id = $ml_inf_list;
                
                $data = $infusionsoft->contact('findByEmail', snp_trim($_POST['email']), array('Id'));
                if (!$data) {
                    $contact_id = $infusionsoft->contact('add', $user);
                } else { 
                    $contact_id = $data[0]['Id'];
                }

                $r = $infusionsoft->APIEmail('optIn', snp_trim($_POST['email']), "Ninja Popups on " . get_bloginfo());
                if ($contact_id && $ml_inf_list)  {
                    $infusionsoft->contact('addToGroup', $contact_id, $ml_inf_list);
                }

                if ($contact_id) {
                    $Done = 1;
                }
            }
        } else if ($ml_manager == 'aweber') {
            require_once SNP_DIR_PATH . '/include/aweber/aweber_api.php';

            if (get_option('snp_ml_aw_auth_info')) {
                $aw = get_option('snp_ml_aw_auth_info');
                try {
                    $aweber = new AWeberAPI($aw['consumer_key'], $aw['consumer_secret']);
                    $account = $aweber->getAccount($aw['access_key'], $aw['access_secret']);
                    $aw_list = $POPUP_META['snp_ml_aw_lists'][0];
                    if (!$aw_list) {
                        $aw_list = snp_get_option('ml_aw_lists');
                    }

                    $log_list_id = $aw_list;
                    $list = $account->loadFromUrl('/accounts/' . $account->id . '/lists/' . $aw_list);
                    $subscriber = array(
                        'email' => snp_trim($_POST['email']),
                        'ip_address' => $_SERVER['REMOTE_ADDR']
                    );
                    if (!empty($_POST['name'])) {
                        $subscriber['name'] = $_POST['name'];
                    }

                    if (count($cf_data) > 0) {
                        $subscriber['custom_fields'] = $cf_data;
                    }

                    $r = $list->subscribers->create($subscriber);

                    $Done = 1;
                } catch (AWeberException $e) {
                    $api_error_msg = 'AWEBER Error: ' . $e->getMessage();
                }
            }
        } else if ($ml_manager == 'wysija' && class_exists('WYSIJA')) {
            $ml_wy_list = $POPUP_META['snp_ml_wy_list'][0];
            if (!$ml_wy_list){
                $ml_wy_list = snp_get_option('ml_wy_list');
            }
            
            $log_list_id = $ml_wy_list;

            $userData = array(
                'email' => snp_trim($_POST['email']),
                'firstname' => $names['first'],
                'lastname' => $names['last']
            );
            
            $data = array(
                'user' => $userData,
                'user_list' => array('list_ids' => array(
                    $ml_wy_list
                ))
            );
            
            $userHelper = &WYSIJA::get('user', 'helper');

            if ($userHelper->addSubscriber($data)) {
                $Done = 1;
            } else {
                $api_error_msg = 'MailPoet Problem!';
            }
        } else if ($ml_manager == 'newsletter' && class_exists('Newsletter')) {
            require_once SNP_DIR_PATH . '/include/newsletter/newsletter.php';

            $api = new RelioNewsletterApi();

            $ml_newsletter_listid = $POPUP_META['snp_ml_newsletter_listid'][0];
            if (!$ml_newsletter_listid) {
                $ml_newsletter_listid = snp_get_option('ml_newsletter_listid');
            }

            $log_list_id = $ml_newsletter_listid;

            $ml_newsletter_listid = explode(',', $ml_newsletter_listid);

            $res = $api->subscribe(snp_trim($_POST['email']), $names['first'], $names['last'], $ml_newsletter_listid, $cf_data);
            
            if (is_object($res)) {
                $Done = 1;
            } else {
                $api_error_msg = 'Newsletter Problem!';
            }
        } else if ($ml_manager == 'subscribe2' && class_exists('s2class')) {
            global $wpdb;

            $log_list_id = 'Subscribe2 - no lists';
            
            $s2_email = snp_trim($_POST['email']);
            $s2_confirm = (snp_get_option('ml_subscribe2_double_optin') == 1)
                ? false 
                : true
            ;
            
            $s2 = new s2class();
            $s2->public = $wpdb->prefix.'subscribe2';
            $s2->add($s2_email, $s2_confirm);

            $Done = 1;
        } else if ($ml_manager == 'sendpress') {
            $ml_sp_list = $POPUP_META['snp_ml_sp_list'][0];
            if (!$ml_sp_list) {
                $ml_sp_list = snp_get_option('ml_sp_list');
            }

            $log_list_id = $ml_sp_list;
            
            try {
                SendPress_Data::subscribe_user($ml_sp_list, snp_trim($_POST['email']), $names['first'], $names['last'], 2);
                
                $Done = 1;
            } catch (Exception $e) {
                $api_error_msg = 'SendPress Problem!';
            }
        } else if ($ml_manager == 'mymail') {
            $userdata = array(
                'firstname' => $names['first'],
                'lastname' => $names['last']
            );

            $ml_mm_list = $POPUP_META['snp_ml_mm_list'][0];
            if (!$ml_mm_list) {
                $ml_mm_list = snp_get_option('ml_mm_list');
            }

            $log_list_id = $ml_mm_list;
            
            $lists = array($ml_mm_list);
            if (function_exists('mymail')) {
                $entry = $userdata;
                $entry['email'] = snp_trim($_POST['email']);
                
                $double_optin = snp_get_option('ml_mm_double_optin');
                
                if ($double_optin == 1) {
                    $entry['status'] = 0;
                } else {
                    $entry['status'] = 1;
                }

                if (count($cf_data) > 0) {
                    foreach ($cf_data as $k => $v) {
                        $entry[$k] = $v;
                    }
                }

                $subscriber_id = mymail('subscribers')->add($entry, true);
                if (!is_wp_error($subscriber_id)) {
                    $success = mymail('subscribers')->assign_lists($subscriber_id, $lists, false);
                }

                if ($success) {
                    $Done = 1;
                } else {
                    $api_error_msg = 'MyMail Problem!';
                }
            } else {
                $return = mymail_subscribe(snp_trim($_POST['email']), $userdata, $lists);
                if (!is_wp_error($return)) {
                    $Done = 1;
                } else {
                    $api_error_msg = 'MyMail Problem!';
                }
            }
        } else if ($ml_manager == 'mailster') {
            $userdata = array(
                'firstname' => $names['first'],
                'lastname' => $names['last']
            );

            $ml_mailster_list = $POPUP_META['snp_ml_mm_list'][0];
            if (!$ml_mailster_list) {
                $ml_mailster_list = snp_get_option('ml_mailster_list');
            }

            $log_list_id = $ml_mailster_list;
            
            $lists = array($ml_mailster_list);

            if (function_exists('mailster')) {
                $entry = $userdata;
                $entry['email'] = snp_trim($_POST['email']);
                $double_optin = snp_get_option('ml_mailster_double_optin');
                if ($double_optin == 1) {
                    $entry['status'] = 0;
                } else {
                    $entry['status'] = 1;
                }

                if (count($cf_data) > 0) {
                    foreach ($cf_data as $k => $v) {
                        $entry[$k] = $v;
                    }
                }

                if ($data = mailster('subscribers')->get_by_mail($entry['email'])) {
                    $subscriber_id = $data->ID;
                } else {
                    $subscriber_id = mailster('subscribers')->add($entry, true);
                }

                if (!is_wp_error($subscriber_id)) {
                    $success = mailster('subscribers')->assign_lists($subscriber_id, $lists, false);
                }

                if ($success) {
                    $Done = 1;
                } else {
                    $api_error_msg = 'Mailster Problem!';
                }
            } else {
                $return = mailster_subscribe(snp_trim($_POST['email']), $userdata, $lists);
                if (!is_wp_error($return)) {
                    $Done = 1;
                } else {
                    $api_error_msg = 'Mailster Problem!';
                }
            }
        } else if ($ml_manager == 'hubspot') {
            if (!class_exists('HubSpot_Forms')) {
                require_once SNP_DIR_PATH . '/include/hubspot/class.forms.php';
            }

            $HAPIKey = snp_get_option('ml_hubspot_apikey');

            $portalId = snp_get_option('ml_hubspot_portal');

            $formId = $POPUP_META['snp_ml_hubspot_list'][0];
            if (!$formId) {
                $formId = snp_get_option('ml_hubspot_list');
            }

            $log_list_id = $formId;

            try {
                $forms = new HubSpot_Forms($HAPIKey);

                $params =  array(
                    'email' => snp_trim($_POST['email']),
                    'firstname' => $names['first'],
                    'lastname' => $names['last']
                );

                if (count($cf_data) > 0) {
                    foreach ($cf_data as $k => $v) {
                        $params[$k] = $v;
                    }
                }

                $result = $forms->submit_form($portalId, $formId, $params, array(
                    'ipAddress' => $_SERVER['REMOTE_ADDR']
                ));

                $Done = 1;
            } catch (Exception $e) {
                $api_error_msg = 'HubSpot Problem: ' . $e->getMessage();
            }

        } else if ($ml_manager == 'convertkit') {
            if (!class_exists('Convertkit')) {
                require_once SNP_DIR_PATH . '/include/convertkit/convertkit.php';
            }
            
            $ml_convertkit_apikey = snp_get_option('ml_convertkit_apikey');

            $formId = $POPUP_META['snp_ml_convertkit_list'][0];
            if (!$formId) {
                $formId = snp_get_option('ml_convertkit_list');
            }

            $log_list_id = $formId;

            $api = new Convertkit($ml_convertkit_apikey);

            $params =  array(    
                'email' => snp_trim($_POST['email']),
                'first_name' => $names['first'],
                'fields' => array(
                    'last_name' => $names['last']
                )
            );

            if (count($cf_data) > 0) {
                foreach ($cf_data as $k => $v) {
                    $params['fields'][$k] = $v;
                }
            }

            try {
                $response = $api->addToForm($formId, $params);

                if (isset($response->subscription->id)) {
                    $Done = 1;
                } else {
                    $api_error_msg = 'ConvertKit Problem: ' . var_export($result);
                }
            } catch (Exception $e) {
                $api_error_msg = 'ConvertKit Problem: ' . $e->getMessage();
            }
        } else if ($ml_manager == 'jetpack') {
            $log_list_id = 'jetpack: no lists';

            $subscribe = Jetpack_Subscriptions::subscribe(snp_trim($_POST['email']), 0, false);

            if (is_wp_error($subscribe)) {
                $api_error_msg = 'JetPack Problem: ' . $subscribe->get_error_code();
            } else {
                $error = false;
                foreach ( $subscribe as $response ) {
                    if ( is_wp_error( $response ) ) {
                        $error = $response->get_error_code();
                        break;
                    }
                }
            }

            switch ($error) {
                case false:
                    $Done = 1;
                    break;
                case 'invalid_email':
                    $api_error_msg = 'JetPack Problem: ' . $error;
                    break;
                case 'active':
                case 'blocked_email':
                    $api_error_msg = 'JetPack Problem: opted_out';
                    break;
                case 'pending':
                    $api_error_msg = 'JetPack Problem: pending';
                    break;
                default:
                    $api_error_msg = 'JetPack Problem: error';
                    break;
            }
        } else if ($ml_manager == 'enewsletter') {
            if (class_exists('Email_Newsletter')) {
                $groupId = $POPUP_META['snp_ml_enewsletter_list'][0];
                if (!$groupId) {
                    $groupId = snp_get_option('snp_ml_enewsletter_list');
                }

                $log_list_id = $groupId;

                $newsletter = new Email_Newsletter();

                $settings = $newsletter->get_settings();

                //Sets up groups to subscribe to on the beginning
                $subscribe_groups = (isset($settings['subscribe_groups'])) ? explode(',', $settings['subscribe_groups']) : array();
                $subscribe_groups = array_merge(array($groupId), $subscribe_groups);
                $subscribe_groups = array_unique($subscribe_groups);

                //set up if double opt in is on
                $double_opt_in = (isset($newsletter->settings['double_opt_in']) && $newsletter->settings['double_opt_in']) ? 1 : 0;

                $member_data['email']       =  snp_trim($_POST['email']);
                $member_data['fname']       =  $names['first'];
                $member_data['lname']       =  $names['last'];
                $member_data['groups_id']   =  $subscribe_groups;
                
                //first lets check if email exists somewhere
                $currentMemberId = '';
                if (email_exists($member_data['email']) !== false) {
                    $wp_user_id = email_exists($member_data['email']);
                    $member_id = $newsletter->get_members_by_wp_user_id($wp_user_id);

                    if (0 < $member_id) {
                        $currentMemberId = $member_id;
                    }
                } else {
                    $member =  $newsletter->get_member_by_email($member_data['email']);

                    if (isset($member['member_id'])) {
                        $currentMemberId = $member['member_id'];
                    }
                }

                if ($currentMemberId) {
                    $newsletter->add_members_to_groups($currentMemberId, $subscribe_groups);

                    $Done = 1;
                } else {
                    $result = $newsletter->add_member($member_data, $double_opt_in);

                    if (!$result['error']) {
                        $Done = 1;
                    } else {
                        $api_error_msg = 'WPMU DEV E-newsletter error message: ' . $result['message'];
                    }
                }
            } else {
                $api_error_msg = 'WPMU DEV E-newsletter plugin not found';
            }
        } else if ($ml_manager == 'campaigner') {
            if (!class_exists('Campaigner')) {
                require_once SNP_DIR_PATH . '/include/campaigner/campaigner.php';
            }

            $listId = $POPUP_META['snp_ml_campaigner_list'][0];
            if (!$listId) {
                $listId = snp_get_option('ml_campaigner_list');
            }

            $log_list_id = $listId;

            $api = new Campaigner();
            $api->setUsername(snp_get_option('ml_campaigner_username'));
            $api->setPassword(snp_get_option('ml_campaigner_password'));

            try {
                $params = array();
                if (count($cf_data) > 0) {
                    foreach ($cf_data as $k => $v) {
                        $params[] = array(
                            'IsNull' => false,
                            'Id'     => $k,
                            'Value'  => $v
                        );
                    }
                }

                $api->addContact($listId, snp_trim($_POST['email']), $names['first'], $names['last'], $params);

                $Done = 1;
            } catch (Exception $e) {
                $api_error_msg = 'Campaigner API error: ' . $e->getMessage();
            }
        } else if ($ml_manager == 'campaignercsv') {
            if (!class_exists('CampaignerCsv')) {
                require_once SNP_DIR_PATH . '/include/campaignercsv/campaignercsv.php';
            }

            $listId = $POPUP_META['snp_ml_campaigner_list'][0];
            if (!$listId) {
                $listId = snp_get_option('ml_campaigner_list');
            }

            $log_list_id = $listId;

            $api = new CampaignerCsv();
            $api->setUsername(snp_get_option('ml_campaignercsv_username'));
            $api->setPassword(snp_get_option('ml_campaignercsv_password'));
            $api->login();
            $resultApi = $api->importContact(array(
                'email'     => snp_trim($_POST['email']),
                'firstname' => $names['first'],
                'lastname'  => $names['last'],
                'phone'     => '',
                'fax'       => '',
            ), $listId);

            if ($resultApi) {
                $Done = 1;
            } else {
                $api_error_msg = 'Campaigner CSV error!';
            }
        } else if ($ml_manager == 'sgautorepondeur') {
            if (!class_exists('API_SG')) {
                require_once SNP_DIR_PATH . '/include/sgautorepondeur/api.php';
            }

            $listId = $POPUP_META['snp_ml_sgautorepondeur_list'][0];
            if (!$listId) {
                $listId = snp_get_option('ml_sgautorepondeur_list');
            }

            $log_list_id = $listId;

            $sgApi = new API_SG(snp_get_option('ml_sgautorepondeur_id'), snp_get_option('ml_sgautorepondeur_code'));

            try {
                $sgApi->set('listeid', $listId)
                    ->set('email', snp_trim($_POST['email']))
                    ->set('name', $names['first'])
                    ->set('first_name', $names['last']);

                if (count($cf_data) > 0) {
                    foreach ($cf_data as $k => $v) {
                        if (in_array($k, array(
                            'civility',
                            'address',
                            'Zipcode',
                            'City',
                            'country',
                            'website',
                            'telephone',
                            'mobile',
                            'skype',
                            'UserName',
                            'Birthday',
                            'sponsor',
                            'field_1',
                            'field_2',
                            'field_3',
                            'field_4',
                            'field_5',
                            'field_6',
                            'field_7',
                            'field_8',
                            'field_9',
                            'field_10',
                            'field_11',
                            'field_12',
                            'field_13',
                            'field_14',
                            'field_15',
                            'field_16'
                        ))) {
                            $sgApi->set($k, $v);
                        }
                    }
                }

                $call = $sgApi->call('set_subscriber');
                $response = json_decode($call);

                if (isset($response->reponse)) {
                    if (!is_array($response->reponse) && $response->reponse == 'inscriptionok') {
                        $Done = 1;
                    } else {
                        $error = '';
                        foreach ($response->reponse as $errorMessage) {
                            $error .= $errorMessage."\n";
                        }
                        
                        $api_error_msg = 'SG Autorepondeur error message: ' . $error;
                    }
                }
            } catch (Exception $e) {
                $api_error_msg = 'SG Autorepondeur error message: ' . $e->getMessage();
            }
        } else if ($ml_manager == 'kirim') {
            if (!class_exists('Kirim')) {
                require_once SNP_DIR_PATH . '/include/kirim/kirim.php';
            }

            $listId = $POPUP_META['snp_ml_kirim_list'][0];
            if (!$listId) {
                $listId = snp_get_option('ml_kirim_list');
            }

            $log_list_id = $listId;

            try {
                $params = array();
                if (count($cf_data) > 0) {
                    foreach ($cf_data as $k => $v) {
                        $params[$k] = $v;
                    }
                }

                $api = new Kirim();
                $api->setUsername(snp_get_option('ml_kirim_username'));
                $api->setToken(snp_get_option('ml_kirim_token'));
                if ($api->addSubscriber($listId, snp_trim($_POST['email']), $names['first'], $names['last'], $params)) {
                    $Done = 1;
                }
            } catch (Exception $e) {
                $api_error_msg = 'Kirim.email error message: ' . $e->getMessage();
            }
        } else if ($ml_manager == 'mautic') {
            require_once SNP_DIR_PATH . '/include/mautic/QueryBuilder/QueryBuilder.php';
            
            require_once SNP_DIR_PATH . '/include/mautic/Auth/AuthInterface.php';    
            require_once SNP_DIR_PATH . '/include/mautic/Auth/ApiAuth.php';
            require_once SNP_DIR_PATH . '/include/mautic/Auth/AbstractAuth.php';
            require_once SNP_DIR_PATH . '/include/mautic/Auth/OAuth.php';

            require_once SNP_DIR_PATH . '/include/mautic/Api/Api.php';
            require_once SNP_DIR_PATH . '/include/mautic/Api/Contacts.php';
            require_once SNP_DIR_PATH . '/include/mautic/Api/Stages.php';
            require_once SNP_DIR_PATH . '/include/mautic/Api/Segments.php';
            require_once SNP_DIR_PATH . '/include/mautic/MauticApi.php';

            $uri = snp_get_option('ml_mautic_url');
            $public = snp_get_option('ml_mautic_key');
            $secret = snp_get_option('ml_mautic_secret');
            

            $ownerId = $POPUP_META['snp_ml_mautic_owner'][0];
            if (!$ownerId) {
                $ownerId = snp_get_option('ml_mautic_owner');
            }

            $stageId = $POPUP_META['snp_ml_mautic_stage'][0];
            if (!$stageId) {
                $stageId = snp_get_option('ml_mautic_stage');
            }

            $segmentId = $POPUP_META['snp_ml_mautic_segment'][0];
            if (!$segmentId) {
                $segmentId = snp_get_option('ml_mautic_segment');
            }

            $settings = array(
                'baseUrl'           => $uri,
                'clientKey'         => $public,
                'clientSecret'      => $secret,
                'callback'          => admin_url('edit.php?post_type=snp_popups&page=snp_opt'),
                'version'           => 'OAuth1a'
            );
            
            if (($info = get_option('snp_ml_mautic_auth_info'))) {
                $settings['accessToken']        = $info['accessToken'] ;
                $settings['accessTokenSecret']  = $info['accessTokenSecret'];
                $settings['accessTokenExpires'] = $info['accessTokenExpires'];
            }

            try {
                $auth = \Mautic\Auth\ApiAuth::initiate($settings);

                $contactApi = \Mautic\MauticApi::getContext(
                    'contacts',
                    $auth,
                    $uri . '/api/'
                );

                $data = array(
                    'firstname' => $names['first'],
                    'lastname'  => $names['last'],
                    'email'     => snp_trim($_POST['email']),
                    'ipAddress' => $_SERVER['REMOTE_ADDR'],
                    'owner'     => $ownerId
                );

                if (count($cf_data) > 0) {
                    foreach ($cf_data as $k => $v) {
                        $data[$k] = $v;
                    }
                }

                $contact = $contactApi->create($data);

                if (isset($contact['contact'])) {
                    if ($stageId) {
                        $stageApi = \Mautic\MauticApi::getContext(
                            'stages',
                            $auth,
                            $uri . '/api/'
                        );

                        $response = $stageApi->addContact($contact['contact']['id'], $stageId);
                    }

                    if ($segmentId) {
                        $segmentApi = \Mautic\MauticApi::getContext(
                            'segments',
                            $auth,
                            $uri . '/api/'
                        );

                        $response = $segmentApi->addContact($segmentId, $contact['contact']['id']);
                    }

                    $Done = 1;
                } else {
                    $api_error_msg = 'Mautic error message: ' . var_export($contact);
                }
            } catch (Exception $e) {
                $api_error_msg = 'Mautic error message: ' . $e->getMessage();
            }
        } else if ($ml_manager == 'mailpoet') {
            try {
                $listId = snp_get_option('ml_mailpoet_list');
                
                $lists = array($listId);

                $log_list_id = $listId;

                $subscriber_data = array(
                    'email' => snp_trim($_POST['email']),
                    'first_name' => $names['first'],
                    'last_name' => $names['last'],
                );

                $subscriber = \MailPoet\API\API::MP('v1')->addSubscriber($subscriber_data, $lists, array(
                    'send_confirmation_email' => true,
                    'schedule_welcome_email' => true
                ));

                $Done = 1;
            } catch (Exception $e) {
                $api_error_msg = 'MailPoet error message: ' . $e->getMessage();
            }
        } else if ($ml_manager == 'drip') {
            if (!class_exists('Drip')) {
                require_once SNP_DIR_PATH . '/include/drip/drip.php';
            }

            $account = $POPUP_META['snp_ml_drip_account'][0];
            if (!$account) {
                $account = snp_get_option('ml_drip_account');
            }

            if (!$token) {
                $token = snp_get_option('ml_drip_token');
            }

            $campaign = $POPUP_META['snp_ml_drip_campaigns'][0];
            if (!$campaign) {
                $campaign = snp_get_option('ml_drip_campaigns');
            }

            $doubleOptin = $POPUP_META['snp_ml_drip_double_optin'][0];
            if (!$doubleOptin) {
                $doubleOptin = snp_get_option('ml_drip_double_optin');
            }

            $log_list_id = $campaign;

            try {
                $data = array(
                    'account_id'   => $account,
                    'campaign_id'  => $campaign,
                    'double_optin' => (bool)$doubleOptin,
                    'first_name'   => $names['first'],
                    'last_name'    => $names['last'],
                    'email'        => snp_trim($_POST['email']),
                );

                if (count($cf_data) > 0) {
                    foreach ($cf_data as $k => $v) {
                        $data[$k] = $v;
                    }
                }

                $dripResult = array();
                foreach ($data as $key => $value) {
                    if (
                        !in_array($key, array(
                            'account_id',
                            'campaign_id',
                            'double_optin'
                        ))
                    ) {
                        $dripResult[$key] = $value;
                    }
                }

                $result['drip'] = $dripResult;

                $api = new Drip($token);
                $response = $api->subscribe_subscriber($data);

                $Done = 1;
            } catch (Exception $e) {
                $api_error_msg = 'Drip error message: ' . $e->getMessage();
            }
        } else if ($ml_manager == 'apsis') {
            require_once SNP_DIR_PATH . '/include/httpful.phar';

            $key = snp_get_option('ml_apsis_key');

            if (snp_get_option('ml_manager') != 'apsis' || !$key) {
                return;
            }

            $listId = $POPUP_META['snp_ml_apsis_list'][0];
            if (!$listId) {
                $listId = snp_get_option('ml_apsis_list');
            }

            $log_list_id = $listId;

            $body = array(
                'Email' => snp_trim($_POST['email']),
                'Name' => $names['first'] . ' ' . $names['last'],
                'DemDataFields' => array()
            );

            if (count($cf_data) > 0) {
                foreach ($body as $k => $v) {
                    $body['DemDataFields'][] = array(
                        'Key' => $k,
                        'Value' => $v
                    );
                }
            }

            try {
                $response = \Httpful\Request::post('http://se.api.anpdm.com/v1/subscribers/mailinglist/' . $listId . '/create?updateIfExists=false')
                    ->expectsJson()
                    ->sendsJson()
                    ->authenticateWith($key, '')
                    ->body(json_encode($body))
                    ->send();

                if ($response->body->Message == 'Subscriber successfully created and/or added to list') {
                    $Done = 1;
                } else {
                    $api_error_msg = 'APSIS error message: ' . print_r($response, true);
                }
            } catch (Exception $e) {
                $api_error_msg = 'APSIS error message: ' . $e->getMessage();
            }
        } else if ($ml_manager == 'csv' && snp_get_option('ml_csv_file') && is_writable(SNP_DIR_PATH . 'csv/')) {
            if (!isset($_POST['name'])) {
                $_POST['name'] = '';
            }

            if (count($cf_data) > 0) {
                $CustomFields = '';
                foreach ($cf_data as $k => $v) {
                    $CustomFields.= $k . ' = ' . $v . ';';
                }
            }

            $data = snp_trim($_POST['email']) . ";" . $_POST['name'] . ";" . $CustomFields . get_the_title($_POST['popup_ID']) . " (" . $_POST['popup_ID'] . ");" . date('Y-m-d H:i') . ";" . $_SERVER['REMOTE_ADDR'] . ";\n";
            
            if (file_put_contents(SNP_DIR_PATH . 'csv/' . snp_get_option('ml_csv_file'), $data, FILE_APPEND | LOCK_EX) !== FALSE) {
                $Done = 1;
            } else {
                $api_error_msg = 'CSV Problem!';
            }
        }

        if ($ml_manager == 'email' || !$Done) {
            $Email = snp_get_option('ml_email');

            if (isset($POPUP_META['snp_ml_email']) && !empty($POPUP_META['snp_ml_email'])) {
                $Email = $POPUP_META['snp_ml_email'];
            }

            if (!$Email) {
                $Email = get_bloginfo('admin_email');
            }

            if (!isset($_POST['name'])) {
                $_POST['name'] = '--';
            }

            $error_mgs = '';
            if ($api_error_msg != '') {
                $error_mgs.="IMPORTANT! You have received this message because connection to your e-mail marketing software failed. Please check connection setting in the plugin configuration.\n";
                $error_mgs.=$api_error_msg . "\n";
            }

            $cf_msg = '';
            if (count($cf_data) > 0) {
                foreach ($cf_data as $k => $v) {
                    $cf_msg .= $k . ": " . $v . "\r\n";
                }
            }

            $msg = "New subscription on " . get_bloginfo() . "\r\n" .
                $error_mgs .
                "\r\n" .
                "E-mail: " . snp_trim($_POST['email']) . "\r\n" .
                "\r\n" .
                "Name: " . $_POST['name'] . "\r\n" .
                "\r\n" .
                $cf_msg .
                "\r\n" .
                "Form: " . get_the_title($_POST['popup_ID']) . " (" . $_POST['popup_ID'] . ")\r\n" .
                "\r\n" .
                "\r\n" .
                "Referer: " . $_SERVER['HTTP_REFERER'] . "\r\n" .
                "\r\n" .
                "Date: " . date('Y-m-d H:i') . "\r\n" .
                "\r\n" .
                "IP: " . $_SERVER['REMOTE_ADDR'] . "";
            
            $headers[]   = 'Reply-To: ' . $_POST['name'] . ' <'.$_POST['email'].'>';

            wp_mail($Email, "New subscription on " . get_bloginfo(), $msg, $headers);
        }

        if ((snp_get_option('enable_log_gathering') == 'yes') && (snp_get_option('enable_log_g_subscribe') == 'yes')) {
            if ($Done) {
                snp_update_log_subscription($cf_data, $log_list_id);
            } else {
                snp_update_log_subscription($cf_data, $log_list_id, $api_error_msg);
            }
        }

        $result['Ok'] = true;
    }

    echo json_encode($result);
    die('');
}

function snp_popup_stats()
{
    global $wpdb;

    $table_name = $wpdb->prefix . "snp_stats";
    $ab_id = intval($_POST['ab_ID']);
    $post_id = intval($_POST['popup_ID']);

    if (current_user_can('manage_options')) {
        die('');
    }
    
    if ($post_id > 0) {
        if ($_POST['type'] == 'view') {
            $count = get_post_meta($post_id, 'snp_views');
            if (!$count || !$count[0])
                $count[0] = 0;
            update_post_meta($post_id, 'snp_views', $count[0] + 1);
            if ($ab_id)
            {
                $count = get_post_meta($ab_id, 'snp_views');
                if (!$count || !$count[0])
                    $count[0] = 0;
                update_post_meta($ab_id, 'snp_views', $count[0] + 1);
            }
            if((snp_get_option('enable_log_gathering') == 'yes') && (snp_get_option('enable_log_g_view') == 'yes'))
            {
                snp_update_log_popup($post_id);
            }
            $wpdb->query("insert into $table_name (`date`,`ID`,`AB_ID`,imps) values (CURDATE(),$post_id,$ab_id,1) on duplicate key update imps = imps + 1;");
            echo 'ok: view';
        } else {
            $count = get_post_meta($post_id, 'snp_conversions');
            if (!$count || !$count[0])
                $count[0] = 0;
            update_post_meta($post_id, 'snp_conversions', $count[0] + 1);
            if ($ab_id)
            {
                $count = get_post_meta($ab_id, 'snp_conversions');
                if (!$count || !$count[0])
                    $count[0] = 0;
                update_post_meta($ab_id, 'snp_conversions', $count[0] + 1);
            }
            $wpdb->query("insert into $table_name (`date`,`ID`,`AB_ID`,convs) values (CURDATE(),$post_id,$ab_id,1) on duplicate key update convs = convs + 1;");
            echo 'ok: conversion';
        }
    }

    die('');
}

function snp_get_theme($theme)
{
    global $SNP_THEMES, $SNP_THEMES_DIR;

    if (!$theme) {
        return false;
    }

    foreach ($SNP_THEMES_DIR as $DIR) {
        if (is_dir($DIR . '/' . $theme . '') && is_file($DIR . '/' . $theme . '/theme.php')) {
            require_once( $DIR . '/' . $theme . '/theme.php' );
            $SNP_THEMES[$theme]['DIR'] = $DIR . '/' . $theme . '/';
            return $SNP_THEMES[$theme];
        }
    }

    return false;
}

function snp_get_themes_list()
{
    global $SNP_THEMES, $SNP_THEMES_DIR;

    if (count($SNP_THEMES) == 0) {
        $files = array();
        foreach ($SNP_THEMES_DIR as $DIR) {
            if (is_dir($DIR)) {
                if ($dh = opendir($DIR)) {
                    while (($file = readdir($dh)) !== false) {
                        if (is_dir($DIR . '/' . $file) && $file != '.' && $file != '..') {
                            $files[] = $file;
                        }
                    }
                    closedir($dh);
                }
            }
        }

        sort($files);

        foreach ($files as $file) {
            snp_get_theme($file);
        }
    }

    return $SNP_THEMES;
}

function snp_popup_fields_list($popup)
{
    global $SNP_THEMES;
    
    $popup = trim($popup);

    if (is_array($SNP_THEMES) && is_array($SNP_THEMES[$popup])) {
        return $SNP_THEMES[$popup]['FIELDS'];
    } else {
        return array();
    }
}

function snp_popup_fields()
{
    global $SNP_THEMES, $SNP_NHP_Options, $post;

    if (!$post) {
        $post = (object) array();
    }

    $post->ID = intval($_POST['snp_post_ID']);
    snp_get_themes_list();
    
    if ($SNP_THEMES[$_POST['popup']]) {
        $SNP_NHP_Options->_custom_fields_html('snp_popup_fields', $_POST['popup']);
    } else {
        echo 'Error...';
    }

    die();
}

function snp_ml_list()
{
    require_once( plugin_dir_path(__FILE__) . '/include/lists.inc.php' );

    if ($_POST['ml_manager'] == 'mailchimp') {
        echo json_encode(snp_ml_get_mc_lists($_POST['ml_mc_apikey']));
    } elseif ($_POST['ml_manager'] == 'sendgrid') {
        echo json_encode(snp_ml_get_sendgrid_lists($_POST['ml_sendgrid_username'], $_POST['ml_sendgrid_password']));
    } elseif ($_POST['ml_manager'] == 'sendinblue') {
        echo json_encode(snp_ml_get_sendinblue_lists($_POST['ml_sendinblue_apikey']));
    } elseif ($_POST['ml_manager'] == 'getresponse') {
        echo json_encode(snp_ml_get_gr_lists($_POST['ml_gr_apikey']));
    } elseif ($_POST['ml_manager'] == 'freshmail') {
        echo json_encode(snp_ml_get_freshmail_lists($_POST['ml_freshmail_apikey'], $_POST['ml_freshmail_apisecret']));
    } elseif ($_POST['ml_manager'] == 'sendlane') {
        echo json_encode(snp_ml_get_sendlane_lists($_POST['ml_sendlane_apikey'], $_POST['ml_sendlane_hash'], $_POST['ml_sendlane_subdomain']));
    } elseif ($_POST['ml_manager'] == 'mailrelay') {
        echo json_encode(snp_ml_get_mailrelay_lists($_POST['ml_mailrelay_apikey'], $_POST['ml_mailrelay_address']));
    } elseif ($_POST['ml_manager'] == 'mailup') {
        echo json_encode(snp_ml_get_mailup_lists($_POST['ml_mailup_clientid'], $_POST['ml_mailup_clientsecret'], $_POST['ml_mailup_login'], $_POST['ml_mailup_password']));
    } elseif ($_POST['ml_manager'] == 'ontraport') {
        echo json_encode(snp_ml_get_ontraport_lists($_POST['ml_ontraport_apiid'], $_POST['ml_ontraport_apikey']));
    } elseif ($_POST['ml_manager'] == 'sendreach') {
        echo json_encode(snp_ml_get_sendreach_lists($_POST['ml_sendreach_pubkey'], $_POST['ml_sendreach_privkey']));
    } elseif ($_POST['ml_manager'] == 'sendpulse') {
        echo json_encode(snp_ml_get_sendpulse_lists($_POST['ml_sendpulse_id'], $_POST['ml_sendpulse_apisecret']));
    } elseif ($_POST['ml_manager'] == 'mailjet') {
        echo json_encode(snp_ml_get_mailjet_lists($_POST['ml_mailjet_apikey'], $_POST['ml_mailjet_apisecret']));
    } elseif ($_POST['ml_manager'] == 'elasticemail') {
        echo json_encode(snp_ml_get_elasticemail_lists($_POST['ml_elasticemail_apikey']));
    } elseif ($_POST['ml_manager'] == 'benchmarkemail') {
        echo json_encode(snp_ml_get_benchmarkemail_lists($_POST['ml_benchmarkemail_apikey']));
    } elseif ($_POST['ml_manager'] == 'myemma') {
        echo json_encode(snp_ml_get_myemma_lists($_POST['ml_myemma_account_id'], $_POST['ml_myemma_pubkey'], $_POST['ml_myemma_privkey']));
    } elseif ($_POST['ml_manager'] == 'mailerlite') {
        echo json_encode(snp_ml_get_mailerlite_lists($_POST['ml_mailerlite_apikey']));
    } elseif ($_POST['ml_manager'] == 'rocketresponder') {
		echo json_encode(snp_ml_get_rocketresponder_lists($_POST['ml_rocketresponder_apipublic'], $_POST['ml_rocketresponder_apiprivate']));
	} elseif ($_POST['ml_manager'] == 'activecampaign') {
        echo json_encode(snp_ml_get_activecampaign_lists($_POST['ml_activecampaign_apiurl'], $_POST['ml_activecampaign_apikey']));
    } elseif ($_POST['ml_manager'] == 'campaignmonitor') {
        echo json_encode(snp_ml_get_cm_lists($_POST['ml_cm_clientid'], $_POST['ml_cm_apikey']));
    } elseif ($_POST['ml_manager'] == 'icontact') {
        echo json_encode(snp_ml_get_ic_lists($_POST['ml_ic_username'], $_POST['ml_ic_addid'], $_POST['ml_ic_apppass']));
    } elseif ($_POST['ml_manager'] == 'constantcontact') {
        echo json_encode(snp_ml_get_cc_lists($_POST['ml_cc_username'], $_POST['ml_cc_pass']));
    } elseif ($_POST['ml_manager'] == 'aweber_auth') {
        echo json_encode(snp_ml_get_aw_auth($_POST['ml_aw_auth_code']));
    } elseif ($_POST['ml_manager'] == 'aweber_remove_auth') {
        echo json_encode(snp_ml_get_aw_remove_auth());
    } elseif ($_POST['ml_manager'] == 'aweber') {
        echo json_encode(snp_ml_get_aw_lists());
    } elseif ($_POST['ml_manager'] == 'wysija') {
        echo json_encode(snp_ml_get_wy_lists());
    } elseif ($_POST['ml_manager'] == 'madmimi') {
        echo json_encode(snp_ml_get_madm_lists($_POST['ml_madm_username'], $_POST['ml_madm_apikey']));
    } elseif ($_POST['ml_manager'] == 'infusionsoft') {
        echo json_encode(snp_ml_get_infusionsoft_lists($_POST['ml_inf_subdomain'], $_POST['ml_inf_apikey']));
    } elseif ($_POST['ml_manager'] == 'mymail') {
        echo json_encode(snp_ml_get_mm_lists());
    } elseif ($_POST['ml_manager'] == 'mailster') {
        echo json_encode(snp_ml_get_mailster_lists());
    } elseif ($_POST['ml_manager'] == 'sendpress') {
        echo json_encode(snp_ml_get_sp_lists());
    } elseif ($_POST['ml_manager'] == 'egoi') {
        echo json_encode(snp_ml_get_egoi_lists($_POST['ml_egoi_apikey']));
    } elseif ($_POST['ml_manager'] == 'hubspot') {
        echo json_encode(snp_ml_get_hubspot_lists($_POST['ml_hubspot_apikey']));
    } elseif ($_POST['ml_manager'] == 'convertkit') {
        echo json_encode(snp_ml_get_convertkit_lists($_POST['ml_convertkit_apikey']));
    } elseif ($_POST['ml_manager'] == 'enewsletter') {
        echo json_encode(snp_ml_get_enewsletter_lists());
    } elseif ($_POST['ml_manager'] == 'campaigner') {
        echo json_encode(snp_ml_get_campaigner_lists($_POST['ml_campaigner_username'], $_POST['ml_campaigner_password']));
    } elseif ($_POST['ml_manager'] == 'sgautorepondeur') {
        echo json_encode(snp_ml_get_sgautorepondeur_lists($_POST['ml_sgautorepondeur_id'], $_POST['ml_sgautorepondeur_code']));
    } else if ($_POST['ml_manager'] == 'kirim') {
        echo json_decode(snp_ml_get_kirim_lists($_POST['ml_kirim_username'], $_POST['ml_kirim_token']));
    } else if ($_POST['ml_manager'] == 'mautic_auth') {
        echo json_encode(snp_ml_get_mautic_auth($_POST['ml_mautic_url'], $_POST['ml_mautic_key'], $_POST['ml_mautic_secret']));
    } else if ($_POST['ml_manager'] == 'mautic_remove_auth') {
        echo json_encode(snp_ml_get_mautic_remove_auth());
    } else if ($_POST['ml_manager'] == 'mailpoet') {
        echo json_encode(snp_ml_get_mailpoet_lists());
    } else if ($_POST['ml_manager'] == 'drip') {
        echo json_encode(snp_ml_get_drip_campaigns($_POST['ml_drip_account'], $_POST['ml_drip_token']));
    } else if ($_POST['ml_manager'] == 'apsis') {
        echo json_encode(snp_ml_apsis_lists($_POST['ml_apsis_key']));
    } else {
        echo json_encode(array());
    }

    die();
}

function snp_popup_colors()
{
    global $SNP_THEMES, $SNP_NHP_Options, $post;

    snp_get_themes_list();
    echo json_encode($SNP_THEMES[$_POST['popup']]['COLORS']);
    die();
}

function snp_popup_types()
{
    global $SNP_THEMES, $SNP_NHP_Options, $post;

    snp_get_themes_list();
    echo json_encode($SNP_THEMES[$_POST['popup']]['TYPES']);
    die();
}

function snp_init()
{
    if (
        !snp_get_option('js_disable_jq_cookie') || 
        is_admin()
    ) {
        // jQuery Cookie
        wp_enqueue_script(
            'jquery-np-cookie', plugins_url('/js/jquery.ck.min.js', __FILE__), array('jquery'), false, true
        );
    }

    if (snp_get_option('enable_jquery_accordion')) {
        wp_enqueue_script('jquery-ui-accordion');
    }

    wp_enqueue_script(
        'js-dialog_trigger', plugins_url('/js/dialog_trigger.js', __FILE__), array('jquery'), false, true
    );
    
    if (snp_get_option('js_use_nonminify')) {
        wp_enqueue_script(
            'js-ninjapopups', plugins_url('/js/ninjapopups.js', __FILE__), array('jquery'), false, true
        );
    } else {
        wp_enqueue_script(
            'js-ninjapopups', plugins_url('/js/ninjapopups.min.js', __FILE__), array('jquery'), false, true
        );
    }
}

function snp_init_fancybox()
{
    if (
        !snp_get_option('js_disable_fancybox') ||
        is_admin()
    ) {
        // Fancybox 2
        wp_register_style('fancybox2', plugins_url('/fancybox2/jquery.fancybox.min.css', __FILE__));
        wp_enqueue_style('fancybox2');
        wp_enqueue_script(
            'fancybox2', plugins_url('/fancybox2/jquery.fancybox.min.js', __FILE__), array('jquery'), false, true
        );
    }
}

function snp_init_mbYTPlayer()
{
    if (
        !snp_get_option('js_disable_mbYTPlayer') || 
        is_admin()
    ) {
        wp_enqueue_script('mbYTPlayer', SNP_URL . 'js/jquery.mb.YTPlayer.min.js', array('jquery'), false, true);
    }
}

function snp_init_fontawesome()
{
    if (
        !snp_get_option('js_disable_fontawesome') || 
        is_admin()
    ) {
        wp_register_style('font-awesome', plugins_url('/font-awesome/css/font-awesome.min.css', __FILE__));
        wp_enqueue_style('font-awesome');
    }
}

function snp_run_popup($ID, $type, $is_preview = false)
{
    global $snp_popups, $PREVIEW_POPUP_META, $detect;
    
    if (!$ID && $ID != -1) {
        return;
    }
    
    snp_init();
    
    if ($ID == -1) {
        $POPUP_META = $PREVIEW_POPUP_META;
        foreach ($POPUP_META as $k => $v) {
            if (is_array($v)) {
                $v = serialize($v);
            } else {
                $v = stripslashes($v);
            }

            $POPUP_META[$k] = $v;
            $PREVIEW_POPUP_META[$k] = $v;
        }
    } else {
        if (strpos($ID, 'ab_') === 0) {
            $AB_ID = str_replace('ab_', '', $ID);
            $AB_META = get_post_meta($AB_ID);
            
            if (!isset($AB_META['snp_forms'])) {
                return;
            }

            $AB_META['snp_forms'] = array_keys(unserialize($AB_META['snp_forms'][0]));
            
            if (!is_array($AB_META['snp_forms']) || count($AB_META['snp_forms']) == 0) {
                return;
            }

            $ID = $AB_META['snp_forms'][array_rand($AB_META['snp_forms'])];
        }

        if (get_post_status($ID) != 'publish') {
            return;
        }

        $POPUP_META = get_post_meta($ID);
        foreach ((array) $POPUP_META as $k => $v) {
            $POPUP_META[$k] = $v[0];
        }
    }

    if ($is_preview==true) {
        $PREVIEW_POPUP_META['is_preview'] = true;   
    }

    if (!isset($POPUP_META['snp_theme'])) {
        $POPUP_META['snp_theme'] = '';
    }

    if (isset($POPUP_META['snp_theme']) && !is_array($POPUP_META['snp_theme'])) {
        $POPUP_META['snp_theme'] = unserialize($POPUP_META['snp_theme']);
    }

    $POPUP_START_DATE = strtotime(isset($POPUP_META['snp_start_date']) ? $POPUP_META['snp_start_date'] : '');
    $POPUP_END_DATE = strtotime(isset($POPUP_META['snp_end_date']) ? $POPUP_META['snp_end_date'] : '');

    $POPUP_START_HOUR = $POPUP_META['snp_start_hour'] ? $POPUP_META['snp_start_hour'] : '';
    $POPUP_END_HOUR = $POPUP_META['snp_end_hour'] ? $POPUP_META['snp_end_hour'] : '';


    if ($detect->isMobile() && !(snp_get_option('enable_mobile') == 'enabled' && !is_admin())) {
        return;
    }

    if ($POPUP_START_DATE) {
        if ($POPUP_START_DATE <= time()) {

        } else {
            return;
        }
    }

    if ($POPUP_END_DATE) {
        if ($POPUP_END_DATE >= time()) {
        } else {
            return;
        }
    }

    if ($POPUP_START_HOUR) {
        if (date('H:i', strtotime('2001-01-01 ' . $POPUP_START_HOUR) <= date('H:i', time()))) {

        } else {
            return;
        }
    }

    if ($POPUP_END_HOUR) {
        if (date('H:i', strtotime('2001-01-01 ' . $POPUP_END_HOUR) >= date('H:i', time()))) {

        } else {
            return;
        }
    }

    //Option to show pop-up based on referer
    if (isset($POPUP_META['show_by_referer']) && $POPUP_META['show_by_referer'] == 'yes') {
        if (!in_array($_SERVER['HTTP_REFERER'], snp_get_option('show_by_referer_urls'))) {
            return;
        }
    }

    //Option to hide pop-up based on referer
    if (isset($POPUP_META['hide_by_referer']) && $POPUP_META['hide_by_referer'] == 'yes') {
        if (in_array($_SERVER['HTTP_REFERER'], snp_get_option('hide_by_referer_urls'))) {
            return;
        }
    }
	
    if ($type == 'exit') {
        $use_in = snp_get_option('use_in');
        if ($use_in['the_content'] == 1) {
            add_filter('the_content', array('snp_links', 'search'), 100);
        }

        if ($use_in['the_excerpt'] == 1) {
            add_filter('the_excerpt', array('snp_links', 'search'), 100);
        }

        if ($use_in['widget_text'] == 1) {
            add_filter('widget_text', array('snp_links', 'search'), 100);
        }

        if ($use_in['comment_text'] == 1) {
            add_filter('comment_text', array('snp_links', 'search'), 100);
        }
    }

    if (snp_get_option('run_hook_footer') == 'snp_run_footer') {
        add_action('snp_run_footer', 'snp_footer');
    } else  {
        add_action('wp_footer', 'snp_footer');
    }
    wp_register_style('snp_styles_reset', plugins_url('/themes/reset.min.css', __FILE__));
    wp_enqueue_style('snp_styles_reset');

    if (isset($POPUP_META['snp_theme']['mode']) && $POPUP_META['snp_theme']['mode'] == '1') {
        $POPUP_META['snp_theme']['theme'] = 'builder';
    }

    if ($POPUP_META['snp_theme']['theme'] != 'builder') {
        snp_init_fancybox();
    }

    if (isset($POPUP_META['snp_theme']['theme']) && $POPUP_META['snp_theme']['theme']) {
        $THEME_INFO = snp_get_theme($POPUP_META['snp_theme']['theme']);
    }

    if (isset($THEME_INFO['STYLES']) && $THEME_INFO['STYLES']) {
        wp_register_style('snp_styles_' . $POPUP_META['snp_theme']['theme'], plugins_url($POPUP_META['snp_theme']['theme'] . '/' . $THEME_INFO['STYLES'], realpath($THEME_INFO['DIR'])));
        wp_enqueue_style('snp_styles_' . $POPUP_META['snp_theme']['theme']);
    }

    if (isset($POPUP_META['snp_theme']['theme']) && function_exists('snp_enqueue_' . $POPUP_META['snp_theme']['theme'])) {
        call_user_func('snp_enqueue_' . $POPUP_META['snp_theme']['theme'], $POPUP_META);
    }

    if ($type == 'inline') {
        
    } elseif ($type == 'content') {
        $snp_popups[$type][] = array(
            'ID' => $ID,
            'AB_ID' => isset($AB_ID) ? $AB_ID : false
        );
    } else {
        $snp_popups[$type] = array(
            'ID' => $ID,
            'AB_ID' => isset($AB_ID) ? $AB_ID : false
        );
    }
}

function snp_create_popup($ID, $AB_ID, $type, $args = array())
{
    global $PREVIEW_POPUP_META;
    
    $return = '';
    if ($ID == -1) {
        $POPUP_META = $PREVIEW_POPUP_META;
    } else {
        $POPUP = get_post($ID);
        $POPUP_META = get_post_meta($ID);
        foreach ($POPUP_META as $k => $v) {
            $POPUP_META[$k] = $v[0];
        }
    }

    if (isset($PREVIEW_POPUP_META['is_preview'])) {
        $POPUP_META['snp_open']='load';
        $POPUP_META['snp_open_after']='';
    }

    if (!is_array($POPUP_META['snp_theme'])) {
        $POPUP_META['snp_theme'] = unserialize($POPUP_META['snp_theme']);
    }

    if (!isset($POPUP_META['snp_theme']['mode']) || $POPUP_META['snp_theme']['mode'] == '0') {
        if (!$POPUP_META['snp_theme']['theme']) {
            return;
        }

        if ($POPUP_META['snp_theme']['type'] == 'social' || $POPUP_META['snp_theme']['type'] == 'likebox') {
            snp_enqueue_social_script();
        }
    } else {
        $POPUP_META['snp_theme']['theme'] = 'builder';
    }

    $CURRENT_URL = snp_get_current_url();
    $return .='	<div id="snppopup-' . $type . ($type == 'content' || $type == 'inline' || $type == 'widget' ? '-' . $ID : '') . '" class="snp-pop-' . $ID . ' snppopup' . ($type == 'inline' ? ' snp-pop-inline' : '') . ($type == 'widget' ? ' snp-pop-widget' : '') . '">';
    
    if (isset($args['lock_id']) && $args['lock_id']) {
        $return .= '<input type="hidden" class="snp_lock_id" value="' . $args['lock_id'] . '" />';
    }

    if (isset($POPUP_META['snp_cb_close_after']) && $POPUP_META['snp_cb_close_after']) {
        $return .= '<input type="hidden" class="snp_autoclose" value="' . $POPUP_META['snp_cb_close_after'] . '" />';
    }

    if (isset($POPUP_META['snp_open']) && $POPUP_META['snp_open']) {
        $return .= '<input type="hidden" class="snp_open" value="' . $POPUP_META['snp_open'] . '" />';
    } else {
        $return .= '<input type="hidden" class="snp_open" value="load" />';
    }

    if (isset($POPUP_META['snp_close']) && $POPUP_META['snp_close']) {
        $return .= '<input type="hidden" class="snp_close" value="' . $POPUP_META['snp_close'] . '" />';
    } else {
        $return .= '<input type="hidden" class="snp_close" value="close_manual" />';
    }

    $return .= '<input type="hidden" class="snp_show_on_exit" value="' . (isset($POPUP_META['snp_show_on_exit']) ? $POPUP_META['snp_show_on_exit'] : '1') . '" />';
    $return .= '<input type="hidden" class="snp_exit_js_alert_text" value="' . (isset($POPUP_META['snp_exit_js_alert_text'])?str_replace("\r\n", '\n', htmlspecialchars((string) $POPUP_META['snp_exit_js_alert_text'])):'') . '" />';
    $return .= '<input type="hidden" class="snp_exit_scroll_down" value="' . (isset($POPUP_META['snp_exit_scroll_down']) ? $POPUP_META['snp_exit_scroll_down'] : '') . '" />';
    $return .= '<input type="hidden" class="snp_exit_scroll_up" value="' . (isset($POPUP_META['snp_exit_scroll_up']) ? $POPUP_META['snp_exit_scroll_up'] : '') . '" />';
    
    if (isset($POPUP_META['snp_open_after']) && $POPUP_META['snp_open_after']) {
        $return .= '<input type="hidden" class="snp_open_after" value="' . $POPUP_META['snp_open_after'] . '" />';
    }

    if (isset($POPUP_META['snp_open_inactivity']) && $POPUP_META['snp_open_inactivity']) {
        $return .= '<input type="hidden" class="snp_open_inactivity" value="' . $POPUP_META['snp_open_inactivity'] . '" />';
    }

    if (isset($POPUP_META['snp_open_scroll']) && $POPUP_META['snp_open_scroll']) {
        $return .= '<input type="hidden" class="snp_open_scroll" value="' . $POPUP_META['snp_open_scroll'] . '" />';
    }
	
	if (isset($POPUP_META['snp_open_spend_time']) && $POPUP_META['snp_open_spend_time']) {
		$return .= '<input type="hidden" class="snp_open_spend_time" value="' . $POPUP_META['snp_open_spend_time'] . '" />';
	}
	
    if (isset($POPUP_META['snp_close_scroll']) && $POPUP_META['snp_close_scroll']) {
        $return .= '<input type="hidden" class="snp_close_scroll" value="' . $POPUP_META['snp_close_scroll'] . '" />';
    }

    if (isset($POPUP_META['snp_optin_redirect']) && $POPUP_META['snp_optin_redirect'] == 'yes' && !empty($POPUP_META['snp_optin_redirect_url'])) {
        $return .= '<input type="hidden" class="snp_optin_redirect_url" value="' . $POPUP_META['snp_optin_redirect_url'] . '" />';
    } else {
        $return .= '<input type="hidden" class="snp_optin_redirect_url" value="" />';
    }

    if (!isset($POPUP_META['snp_popup_overlay'])) {
        $POPUP_META['snp_popup_overlay'] = '';
    }

    $return .= '<input type="hidden" class="snp_show_cb_button" value="' . $POPUP_META['snp_show_cb_button'] . '" />';
    $return .= '<input type="hidden" class="snp_popup_id" value="' . $ID . '" />';
    if ($AB_ID != false) {
        $return .= '<input type="hidden" class="snp_popup_ab_id" value="' . $AB_ID . '" />';
    }
    $return .= '<input type="hidden" class="snp_popup_theme" value="' . $POPUP_META['snp_theme']['theme'] . '" />';
    $return .= '<input type="hidden" class="snp_overlay" value="' . $POPUP_META['snp_popup_overlay'] . '" />';
    $return .= '<input type="hidden" class="snp_cookie_conversion" value="' . (!empty($POPUP_META['snp_cookie_conversion']) ? $POPUP_META['snp_cookie_conversion'] : '30') . '" />';
    $return .= '<input type="hidden" class="snp_cookie_close" value="' . (!empty($POPUP_META['snp_cookie_close']) && $POPUP_META['snp_cookie_close'] ? $POPUP_META['snp_cookie_close'] : '-1') . '" />';
    
    if (isset($POPUP_META['snp_ajax_before_optin']) && $POPUP_META['snp_ajax_before_optin']) {
        $return .= '<input type="hidden" class="snp_ajax_before_optin" value="' . $POPUP_META['snp_ajax_before_optin'] . '" />';
    } else if (snp_get_option('ajax_before_optin')) {
        $return .= '<input type="hidden" class="snp_ajax_before_optin" value="' . snp_get_option('ajax_before_optin') . '" />';
    }

    if (isset($POPUP_META['snp_ajax_after_optin']) && $POPUP_META['snp_ajax_after_optin']) {
        $return .= '<input type="hidden" class="snp_ajax_after_optin" value="' . $POPUP_META['snp_ajax_after_optin'] . '" />';
    } else if (snp_get_option('ajax_after_optin')) {
        $return .= '<input type="hidden" class="snp_ajax_after_optin" value="' . snp_get_option('ajax_after_optin') . '" />';
    }

    if (isset($POPUP_META['snp_ajax_request_handler']) && $POPUP_META['snp_ajax_request_handler']) {
        $return .= '<input type="hidden" class="snp_ajax_url" value="' . $POPUP_META['snp_ajax_request_handler'] . '" />';
    } else if (snp_get_option('ajax_request_handler')) {
        $return .= '<input type="hidden" class="snp_ajax_url" value="' . snp_get_option('ajax_request_handler') . '" />';
    }

    $THEME_INFO = snp_get_theme($POPUP_META['snp_theme']['theme']);
    ob_start();
    include($THEME_INFO['DIR'] . '/template.php');
    $return .= ob_get_clean();
    if (!isset($POPUP_META['snp_cb_img'])) {
        $POPUP_META['snp_cb_img'] = '';
    }

    if (!isset($POPUP_META['snp_custom_css'])) {
        $POPUP_META['snp_custom_css'] = '';
    }

    if (!isset($POPUP_META['snp_custom_js'])) {
        $POPUP_META['snp_custom_js'] = '';
    }

    if (!isset($POPUP_META['snp_theme']['mode']) || $POPUP_META['snp_theme']['mode'] == '0') {
        if ($POPUP_META['snp_popup_overlay'] == 'image' && $POPUP_META['snp_overlay_image']) {
            $return .= '<style>.snp-pop-' . $ID . '-overlay { background: url(\'' . $POPUP_META['snp_overlay_image'] . '\');}</style>';
        }

        if ($POPUP_META['snp_cb_img'] != 'close_default' && $POPUP_META['snp_cb_img'] != '') {
            $return .= '<style>';
            switch ($POPUP_META['snp_cb_img']) {
                case 'close_1':
                    $return .= '.snp-pop-' . $ID . '-wrap .fancybox-close { width: 31px; height: 31px; top: -15px; right: -15px; background: url(\'' . SNP_URL . 'img/' . $POPUP_META['snp_cb_img'] . '.png\');}';
                    break;
                case 'close_2':
                    $return .= '.snp-pop-' . $ID . '-wrap .fancybox-close { width: 19px; height: 19px; top: -8px; right: -8px; background: url(\'' . SNP_URL . 'img/' . $POPUP_META['snp_cb_img'] . '.png\');}';
                    break;
                case 'close_3':
                    $return .= '.snp-pop-' . $ID . '-wrap .fancybox-close { width: 33px; height: 33px; top: -16px; right: -16px; background: url(\'' . SNP_URL . 'img/' . $POPUP_META['snp_cb_img'] . '.png\');}';
                    break;
                case 'close_4':
                case 'close_5':
                    $return .= '.snp-pop-' . $ID . '-wrap .fancybox-close { width: 20px; height: 20px; top: -10px; right: -10px; background: url(\'' . SNP_URL . 'img/' . $POPUP_META['snp_cb_img'] . '.png\');}';
                    break;
                case 'close_6':
                    $return .= '.snp-pop-' . $ID . '-wrap .fancybox-close { width: 24px; height: 24px; top: -12px; right: -12px; background: url(\'' . SNP_URL . 'img/' . $POPUP_META['snp_cb_img'] . '.png\');}';
                    break;
            }
            $return .= '</style>';
        }
    }

    if ($POPUP_META['snp_custom_css'] != '') {
        $return .= '<style>';
        $return .= $POPUP_META['snp_custom_css'];
        $return .= '</style>';
    }

    if ($POPUP_META['snp_custom_js'] != '') {
        $return .= '<script>';
        $return .= $POPUP_META['snp_custom_js'];
        $return .= '</script>';
    }

    //Hooks
    $return .= '<script>'."\n";
    $return .= 'jQuery(document).ready(function() {'."\n";
    
    if (isset($POPUP_META['snp_js_np_submit']) && !empty($POPUP_META['snp_js_np_submit'])) {
        $return .= 'jQuery(document).on("ninja_popups_submit", function(event, data) {'."\n";
        $return .= 'if (event.popup_id == '.$ID.') {'."\n";
        $return .= $POPUP_META['snp_js_np_submit']."\n";
        $return .= '}'."\n";
        $return .= "});"."\n";
    }

    if (isset($POPUP_META['snp_js_np_submit_success']) && !empty($POPUP_META['snp_js_np_submit_success'])) {
        $return .= 'jQuery(document).on("ninja_popups_submit_success", function(event, data) {'."\n";
        $return .= 'if (event.popup_id == '.$ID.') {'."\n";
        $return .= $POPUP_META['snp_js_np_submit_success']."\n";
        $return .= '}'."\n";
        $return .= "});"."\n";
    }


    if (isset($POPUP_META['snp_js_np_submit_error']) && !empty($POPUP_META['snp_js_np_submit_error'])) {
        $return .= 'jQuery(document).on("ninja_popups_submit_error", function(event, data) {'."\n";
        $return .= 'if (event.popup_id == '.$ID.') {'."\n";
        $return .= $POPUP_META['snp_js_np_submit_error']."\n";
        $return .= '}'."\n";
        $return .= "});"."\n";
    }

    if (isset($POPUP_META['snp_js_np_submit_after']) && !empty($POPUP_META['snp_js_np_submit_after'])) {
        $return .= 'jQuery(document).on("ninja_popups_submit_after", function(event, data) {'."\n";
        $return .= 'if (event.popup_id == '.$ID.') {'."\n";
        $return .= $POPUP_META['snp_js_np_submit_after']."\n";
        $return .= '}'."\n";
        $return .= "});"."\n";
    }


    if (isset($POPUP_META['snp_js_np_convert']) && !empty($POPUP_META['snp_js_np_convert'])) {
        $return .= 'jQuery(document).on("ninja_popups_convert", function(event, data) {'."\n";
        $return .= 'if (event.popup_id == '.$ID.') {'."\n";
        $return .= $POPUP_META['snp_js_np_convert']."\n";
        $return .= '}'."\n";
        $return .= "});"."\n";
    }

    if (isset($POPUP_META['snp_js_np_open']) && !empty($POPUP_META['snp_js_np_open'])) {
        $return .= 'jQuery(document).on("ninja_popups_open", function(event, data) {'."\n";
        $return .= 'if (event.popup_id == '.$ID.') {'."\n";
        $return .= $POPUP_META['snp_js_np_open']."\n";
        $return .= '}'."\n";
        $return .= "});"."\n";
    }

    if (isset($POPUP_META['snp_js_np_gotostep']) && !empty($POPUP_META['snp_js_np_gotostep'])) {
        $return .= 'jQuery(document).on("ninja_popups_gotostep", function(event, data) {'."\n";
        $return .= 'if (event.popup_id == '.$ID.') {'."\n";
        $return .= $POPUP_META['snp_js_np_gotostep']."\n";
        $return .= '}'."\n";
        $return .= "});"."\n";
    }

    if (isset($POPUP_META['snp_js_np_open_link']) && !empty($POPUP_META['snp_js_np_open_link'])) {
        $return .= 'jQuery(document).on("ninja_popups_open_link", function(event, data) {'."\n";
        $return .= 'if (event.popup_id == '.$ID.') {'."\n";
        $return .= $POPUP_META['snp_js_np_open_link']."\n";
        $return .= '}'."\n";
        $return .= "});"."\n";
    }

    if (isset($POPUP_META['snp_sound_on_open']) && !empty($POPUP_META['snp_sound_on_open'])) {
        $return .= 'jQuery(document).on("ninja_popups_open", function(event, data) {'."\n";
        $return .= 'if (event.popup_id == '.$ID.') {'."\n";
        $return .= 'var audio = new Audio("' . $POPUP_META['snp_sound_on_open'] . '");'."\n";
        $return .= 'audio.play();'."\n";
        $return .= '}'."\n";
        $return .= "});"."\n";
    }

    $return .= '});'."\n";
    $return .= '</script>'."\n";

    if ((isset($THEME_INFO['OPEN_FUNCTION']) || isset($THEME_INFO['CLOSE_FUNCION'])) && $type != 'inline') {
        $return .= '<script>';
        $return .= 'snp_f[\'snppopup-' . $type . ($type == 'content' || $type == 'inline' ? '-' . $ID : '') . '-open\']=' . $THEME_INFO['OPEN_FUNCTION'] . ($POPUP_META['snp_theme']['theme'] == 'builder' ? abs($ID) : '') . ';';
        $return .= 'snp_f[\'snppopup-' . $type . ($type == 'content' || $type == 'inline' ? '-' . $ID : '') . '-close\']=' . $THEME_INFO['CLOSE_FUNCION'] . ($POPUP_META['snp_theme']['theme'] == 'builder' ? abs($ID) : '') .';';
        $return .= '</script>';
    }
    $return .= '</div>';
    return $return;
}

function snp_footer()
{
    global $snp_popups, $snp_ignore_cookies, $post;
    ?>
    <script>
        var snp_f = [];
        var snp_hostname = new RegExp(location.host);
        var snp_http = new RegExp("^(http|https)://", "i");
        var snp_cookie_prefix = '<?php echo (string)snp_get_option('cookie_prefix') ?>';
        var snp_separate_cookies = <?php echo (snp_get_option('separate_popup_cookies') == 'yes') ? 'true' : 'false' ?>;
        var snp_ajax_url = '<?php echo admin_url('admin-ajax.php') ?>';
		var snp_ajax_nonce = '<?php echo wp_create_nonce('snp_popup_submit'); ?>';
        var snp_ignore_cookies = <?php if (!$snp_ignore_cookies) echo 'false'; else echo 'true'; ?>;
        var snp_enable_analytics_events = <?php if (snp_get_option('enable_analytics_events') == 'yes' && !is_admin()) echo 'true'; else echo 'false'; ?>;
        var snp_enable_mobile = <?php if (snp_get_option('enable_mobile') == 'enabled' && !is_admin()) echo 'true'; else echo 'false'; ?>;
        var snp_use_in_all = <?php $use_in = snp_get_option('use_in'); if ($use_in['all'] == 1) echo 'true'; else echo 'false'; ?>;
        var snp_excluded_urls = [];
        <?php
        $exit_excluded_urls = snp_get_option('exit_excluded_urls');
        if (is_array($exit_excluded_urls)) {
            foreach ($exit_excluded_urls as $url) {
                echo "snp_excluded_urls.push('" . $url . "');";
            }
        }
        ?>
    </script>
    <div class="snp-root">
        <input type="hidden" id="snp_popup" value="" />
        <input type="hidden" id="snp_popup_id" value="" />
        <input type="hidden" id="snp_popup_theme" value="" />
        <input type="hidden" id="snp_exithref" value="" />
        <input type="hidden" id="snp_exittarget" value="" />
        <?php
        $addedPopupContent = array();

        // welcome popup
        if (!empty($snp_popups['welcome']['ID']) && intval($snp_popups['welcome']['ID'])) {
            if (!in_array($snp_popups['welcome']['ID'], $addedPopupContent)) {
                echo snp_create_popup($snp_popups['welcome']['ID'], $snp_popups['welcome']['AB_ID'], 'welcome');

                $addedPopupContent[] = $snp_popups['welcome']['ID'];
            }
        }

        // exit popup
        if (!empty($snp_popups['exit']['ID']) && intval($snp_popups['exit']['ID'])) {
            if (!in_array($snp_popups['exit']['ID'], $addedPopupContent)) {
                echo snp_create_popup($snp_popups['exit']['ID'], $snp_popups['exit']['AB_ID'], 'exit');

                $addedPopupContent[] = $snp_popups['exit']['ID'];
            }
        }

        // popups from content
        if (isset($snp_popups['content']) && is_array($snp_popups['content'])) {
            foreach ($snp_popups['content'] as $popup_id) {
                if (!in_array($popup_id['ID'], $addedPopupContent)) {
                    echo snp_create_popup($popup_id['ID'], $popup_id['AB_ID'], 'content');

                    $addedPopupContent[] = $popup_id['ID'];
                }
            }
        }
        ?>
        <?php if (snp_get_option('recaptcha_api_key')) { ?>
            <script type="text/javascript">
                var CaptchaCallback = function() {
                    jQuery('.g-recaptcha').each(function(index, el) {
                        grecaptcha.render(el, {
                            'sitekey' : '<?php echo snp_get_option('recaptcha_api_key'); ?>'
                        });
                    });
                };
            </script>
        <?php } ?>
    </div>
    <?php
}

function snp_enqueue_social_script()
{
    if (!snp_get_option('js_disable_fb') || is_admin()) {
        // Facebook
        wp_enqueue_script('fbsdk', 'https://connect.facebook.net/' . snp_get_option('fb_locale', 'en_GB') . '/all.js#xfbml=1', array());
        wp_localize_script('fbsdk', 'fbsdku', array(
            'xfbml' => 1,
        ));
    }

    if (!snp_get_option('js_disable_gp') || is_admin()) {
        // Google Plus
        wp_enqueue_script('plusone', 'https://apis.google.com/js/plusone.js', array());
    }

    if (!snp_get_option('js_disable_tw') || is_admin()) {
        // Twitter
        wp_enqueue_script('twitter', 'https://platform.twitter.com/widgets.js', array());
    }

    if (!snp_get_option('js_disable_li') || is_admin()) {
        // Linkedin
        wp_enqueue_script('linkedin', 'https://platform.linkedin.com/in.js', array());
    }
}

function snp_ninja_popup_shortcode($attr, $content = null)
{
    extract(shortcode_atts(array('id' => '', 'autoopen' => false), $attr));
    snp_run_popup($id, 'content');
    if (isset($autoopen) && $autoopen == true) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var snp_open_after = jQuery('#snppopup-content-<?php echo $id; ?> .snp_open_after').val();
                if (snp_open_after) {
                    snp_timer_o = setTimeout("snp_open_popup('','','snppopup-content-<?php echo $id; ?>','content');", snp_open_after * 1000);
                } else {
                    snp_open_popup('', '', 'snppopup-content-<?php echo $id; ?>', 'content');
                }
            });
        </script>
        <?php
    }

    if ($content) {
        return '<a href="#ninja-popup-' . $id . '" class="snppopup-content" rel="' . $id . '">' . $content . ' </a>';
    }

    return '';
}
add_shortcode('ninja-popup', 'snp_ninja_popup_shortcode');
function snp_run()
{
    global $post;

    if (is_404()) {
        return;
    }
    
    if (snp_get_option('enable') == 'disabled') {
        return;
    }

    if ((isset($_REQUEST['nphide']) && $_REQUEST['nphide'] == 1) || isset($_COOKIE['nphide']) && $_COOKIE['nphide'] == 1) {
        setcookie('nphide', 1, 0, COOKIEPATH, COOKIE_DOMAIN, false);
        return;
    }

    $WELCOME_ID = 'global';
    $EXIT_ID = 'global';
    if (isset($post->ID) && (is_page() || is_single())) {
        $WELCOME_ID = get_post_meta($post->ID, 'snp_p_welcome_popup', true);
        $WELCOME_ID = ($WELCOME_ID ? $WELCOME_ID : 'global');
        $EXIT_ID = get_post_meta($post->ID, 'snp_p_exit_popup', true);
        $EXIT_ID = ($EXIT_ID ? $EXIT_ID : 'global');

        if ($WELCOME_ID == 'global' || $EXIT_ID == 'global') {
            if ($post->post_type == 'post') {
                $POST_CATS = wp_get_post_categories($post->ID);
            }

            $enable_taxs = snp_get_option('enable_taxs');
            if (is_array($enable_taxs)) {
                foreach ((array) $enable_taxs as $k => $v) {
                    $POST_CATS = array_merge((array) $POST_CATS, (array) wp_get_object_terms($post->ID, $k, array('fields' => 'ids')));
                }
            }

            if (isset($POST_CATS) && is_array($POST_CATS)) {
                foreach ($POST_CATS as $term_id) {
                    $term_meta = get_option("snp_taxonomy_" . $term_id);
                    if (isset($term_meta['welcome']) && $WELCOME_ID == 'global') {
                        $WELCOME_ID = $term_meta['welcome'];
                    }

                    if (isset($term_meta['exit']) && $EXIT_ID == 'global') {
                        $EXIT_ID = $term_meta['exit'];
                    }
                }
            }
        }
    } elseif (is_category() || is_tax() || is_tag() || is_archive()) {
        $category = get_queried_object();
        $term_meta = get_option("snp_taxonomy_" . $category->term_id);
        if (isset($term_meta['welcome'])) {
            $WELCOME_ID = $term_meta['welcome'];
        } else {
            $WELCOME_ID = 'global';
        }

        if (isset($term_meta['exit'])) {
            $EXIT_ID = $term_meta['exit'];
        } else {
            $EXIT_ID = 'global';
        }
    }

    if (defined('ICL_LANGUAGE_CODE')) {
        $snp_var_sufix = '_' . ICL_LANGUAGE_CODE;
    } else {
        $snp_var_sufix = '';
    }

    // WELCOME
    if (snp_get_option('welcome_disable_for_logged') == 1 && is_user_logged_in()) {
        //Nothing to do here
    } else if (snp_get_option('welcome_enabled_for_logged') == 1 && !is_user_logged_in()) {
        //Nothing to do here
    } else {
        $WELCOME_ID = apply_filters('ninjapopups_welcome_id', $WELCOME_ID);
        if ($WELCOME_ID !== 'disabled' && $WELCOME_ID !== 'global') {
            snp_run_popup($WELCOME_ID, 'welcome');
        } elseif ($WELCOME_ID === 'global') {
            $WELCOME_ID = snp_get_option('welcome_popup' . $snp_var_sufix);
            if ($WELCOME_ID === 'global' && defined('ICL_LANGUAGE_CODE')) {
                $WELCOME_ID = snp_get_option('welcome_popup');
            }
            
            if ($WELCOME_ID !== 'disabled') {
                $welcome_display_in = snp_get_option('welcome_display_in');
                if (
                    is_front_page() &&
                    isset($welcome_display_in['home']) &&
                    $welcome_display_in['home'] == 1
                ) {
                    //home
                    snp_run_popup($WELCOME_ID, 'welcome');
                }
                elseif (
                    is_page() &&
                    isset($welcome_display_in['pages']) &&
                    $welcome_display_in['pages'] == 1
                ) {
                    //page
                    snp_run_popup($WELCOME_ID, 'welcome');
                } elseif (
                    is_single() && 
                    isset($welcome_display_in['posts']) && 
                    $welcome_display_in['posts'] == 1
                ) {
                    //post
                    snp_run_popup($WELCOME_ID, 'welcome');
                } elseif (
                    isset($welcome_display_in['others']) && 
                    $welcome_display_in['others'] == 1 && 
                    !is_front_page() && 
                    !is_page() &&
                    !is_single()
                ) {
                    // other
                    snp_run_popup($WELCOME_ID, 'welcome');
                }
            }
        }
    }

    // EXIT
    if (snp_get_option('exit_disable_for_logged') == 1 && is_user_logged_in()) {
        //Nothing to do here
    } else if (snp_get_option('exit_enabled_for_logged') == 1 && !is_user_logged_in()) {
        //Nothing to do here    
    } else {
        $EXIT_ID = apply_filters('ninjapopups_exit_id', $EXIT_ID);
        if ($EXIT_ID != 'disabled' && $EXIT_ID != 'global') {
            snp_run_popup($EXIT_ID, 'exit');
        } elseif ($EXIT_ID === 'global') {
            $EXIT_ID = snp_get_option('exit_popup' . $snp_var_sufix);
            if ($EXIT_ID === 'global' && defined('ICL_LANGUAGE_CODE')) {
                $EXIT_ID = snp_get_option('exit_popup');
            }

            if ($EXIT_ID != 'disabled') {
                $exit_display_in = snp_get_option('exit_display_in');
                if (
                    is_front_page() && 
                    isset($exit_display_in['home']) && 
                    $exit_display_in['home'] == 1
                ) {
                    //home
                    snp_run_popup($EXIT_ID, 'exit');
                } elseif (
                    is_page() && 
                    isset($exit_display_in['pages']) && 
                    $exit_display_in['pages'] == 1
                ) {
                    //page
                    snp_run_popup($EXIT_ID, 'exit');
                } elseif (
                    is_single() && 
                    isset($exit_display_in['posts']) && 
                    $exit_display_in['posts'] == 1
                ) {
                    //post
                    snp_run_popup($EXIT_ID, 'exit');
                } elseif (
                    isset($exit_display_in['others']) && 
                    $exit_display_in['others'] == 1 && 
                    !is_front_page() && 
                    !is_page() && 
                    !is_single()
                ) {
                    // other
                    snp_run_popup($EXIT_ID, 'exit');
                }
            }
        }
    }

    add_filter('wp_nav_menu_objects', 'snp_wp_nav_menu_objects');
}

function snp_wp_nav_menu_objects($items)
{
    $parents = array();
    foreach ($items as $item) {
        if (strpos($item->url, '#ninja-popup-') !== FALSE) {
            $ID = str_replace('#ninja-popup-', '', $item->url);
            if (intval($ID)) {
                snp_run_popup(intval($ID), 'content');
            }
        }
    }

    return $items;
}

function snp_setup()
{
    register_post_type('snp_popups', array(
        'label' => 'Ninja Popups',
        'labels' => array(
            'name' => 'Ninja Popups',
            'menu_name' => 'Ninja Popups',
            'singular_name' => 'Popup',
            'add_new' => 'Add New Popup',
            'all_items' => 'Popups',
            'add_new_item' => 'Add New Popup',
            'edit_item' => 'Edit Popup',
            'new_item' => 'New Popup',
            'view_item' => 'View Popup',
            'search_items' => 'Search Popups',
            'not_found' => 'No popups found',
            'not_found_in_trash' => 'No popups found in Trash'
        ),
        'hierarchical' => false,
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_in_nav_menus' => false,
        'show_in_admin_bar' => false,
        'show_in_menu' => true,
        'capability_type' => 'page',
        'supports' => array('title', 'editor'),
        'menu_position' => 207,
        'menu_icon' => ''
    ));

    register_post_type('snp_ab', array(
        'label' => 'A/B Testing',
        'labels' => array(
            'name' => 'A/B Testing',
            'menu_name' => 'A/B Testing',
            'singular_name' => 'A/B Testing',
            'add_new' => 'Add New',
            'all_items' => 'A/B Testing',
            'add_new_item' => 'Add New',
            'edit_item' => 'Edit',
            'new_item' => 'New',
            'view_item' => 'View',
            'search_items' => 'Search',
            'not_found' => 'Not found',
            'not_found_in_trash' => 'Not found in Trash'
        ),
        'hierarchical' => false,
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_in_nav_menus' => false,
        'show_in_admin_bar' => false,
        'show_in_menu' => 'edit.php?post_type=snp_popups',
        'capability_type' => 'page',
        'supports' => array('title')
    ));

    add_action('wp_ajax_nopriv_snp_popup_stats', 'snp_popup_stats');
    add_action('wp_ajax_snp_popup_stats', 'snp_popup_stats');
    add_action('wp_ajax_nopriv_snp_popup_submit', 'snp_popup_submit');
    add_action('wp_ajax_snp_popup_submit', 'snp_popup_submit');
	add_action('wp_ajax_snp_popup_spend_time', 'snp_popup_spend_time');
    wp_enqueue_script('jquery');
}

add_action('init', 'snp_session_start', 1);

add_action('init', 'snp_setup', 15);

if (is_admin()) {
    add_action('init', 'snp_setup_admin', 15);
}

if (!is_admin()) {
    if (snp_get_option('run_hook') == 'wp') {
        add_action('wp', 'snp_run');
    } else {
        add_action('get_header', 'snp_run');
    }
}

function snp_session_start() {
    if (!session_id() || session_id() == '') {
        session_start();
    }
}

function snp_popup_spend_time()
{
	if (!isset($_SESSION['snp_time_on_page'])) {
		$_SESSION['snp_time_on_page'] = time();

        $minus = $_SESSION['snp_time_on_page'];
	} else {
        $minus = $_SESSION['snp_time_on_page'];
    }
    
	$time = time() - $minus;
	
	$_SESSION['snp_time_on_page'] = time();

    echo $time;
    exit;
}

function snp_update_log_subscription($cf_data, $log_list_id, $errors = null)
{
    global $wpdb;

    if (isset($_POST['name'])) {
        $cf_data['name'] = $_POST['name'];
    }

    $data = array(
        'action'        => 'subscribtion',
        'email'         => snp_trim($_POST['email']),
        'ip'            => $_SERVER['REMOTE_ADDR'],
        'browser'       => $_SERVER['HTTP_USER_AGENT'],       
        'list'          => $log_list_id,
        'popup_id'      => $_POST['popup_ID'],
        'custom_fields' => json_encode($cf_data),
        'referer'       => $_SERVER['HTTP_REFERER'],
        'errors'        => $errors,
    );

    $wpdb->insert($wpdb->prefix.'snp_log', $data, array('%s','%s','%s','%s','%s','%s','%s','%s','%s'));
}

function snp_update_log_popup($popup_id, $errors = null)
{
    global $wpdb;

    $data = array(
        'action'   => 'popup_view',
        'ip'       => $_SERVER['REMOTE_ADDR'],
        'browser'  => $_SERVER['HTTP_USER_AGENT'],
        'popup_id' => $popup_id,
        'referer'  => $_SERVER['HTTP_REFERER'],
        'errors'   => $errors,
    );

    $wpdb->insert($wpdb->prefix.'snp_log', $data, array('%s','%s','%s','%s','%s','%s'));
}

function snp_get_mc_fields($apikey, $list_id)
{
    require_once SNP_DIR_PATH . '/include/mailchimp/MC_Lists.php';
    
    $rest = new MC_Lists($apikey);
    $fields = json_decode($rest->mergeFields($list_id));

    $result = array();
    foreach($fields->merge_fields as $v) {
        $result[$v->merge_id]['field'] = $v->tag;
        $result[$v->merge_id]['name'] = $v->name;
        $result[$v->merge_id]['required'] = $v->required;
    }

    return json_encode($result, true);
}

function snp_get_mc_groups($apikey, $list_id)
{
    require_once SNP_DIR_PATH . '/include/mailchimp/MC_Lists.php';

    $rest = new MC_Lists($apikey);
    $groups = json_decode($rest->getGroups($list_id));

    $result = array();
    foreach ($groups->categories as $v) {
        $result[$v->id]['field'] = $v->id;
        $result[$v->id]['name'] = $v->title;
    }

    return json_encode($result, true);
}