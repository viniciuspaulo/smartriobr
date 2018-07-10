<?php
/**
 * Plugin Name: HTTP / HTTPS Remover
 * Plugin URI: https://de.wordpress.org/plugins/http-https-remover/
 * Description: This Plugin creates protocol relative urls by removing http + https from links.
 * Version: 2.0
 * Author: CONDACORE
 * Author URI: https://condacore.com/
 * License: GPLv3
 */

// Function for srcset URLs
function protcol_relative_url_srcset($sources)
{
    foreach ($sources as &$source) {
        $link = str_replace("http://", "//", $source['url']);
        $link = str_replace("https://", "//", $link);
        $source['url'] = $link;
    }

    return $sources;
}

// Function for all other URLs
 function callback_protcol_relative_url($buffer)
 {
     $re     = "/(<(script|link|base|img|form|a)([^>]*)(href|src|srcset|action)=[\"'])https?:\\/\\//i";
     $subst  = "$1//";
     $return = preg_replace($re, $subst, $buffer);

     // on regex error, skip overwriting buffer
     if ($return) {
         $buffer = $return;
     }
     return $buffer;
 }

 function http_https_remover_extra_links($links, $file)
 {
     if ($file == plugin_basename(dirname(__FILE__).'/http-https-remover.php')) {
         //$links[] = '<a href="https://condacore.com/portfolio/http-https-remover/#beta" target="_blank">' . esc_html__('Become a Beta Tester', 'http-https-remover') . '</a>';
         $links[] = '<a href="https://twitter.com/condacore" target="_blank">' . esc_html__('Twitter', 'http-https-remover') . '</a>';
         $links[] = '<a href="https://paypal.me/MariusBolik" target="_blank">' . esc_html__('Donate', 'http-https-remover') . '</a>';
     }
     return $links;
 }

 function buffer_start_protcol_relative_url()
 {
     ob_start('callback_protcol_relative_url');
 }
 function buffer_end_protcol_relative_url()
 {
     ob_end_flush();
 }

 // http://codex.wordpress.org/Plugin_API/Action_Reference
 add_filter('plugin_row_meta', 'http_https_remover_extra_links', 10, 2);
 add_filter('wp_calculate_image_srcset', 'protcol_relative_url_srcset');
 add_action('wp_loaded', 'buffer_start_protcol_relative_url' , 99, 1);
 //add_action('registered_taxonomy', 'buffer_start_protcol_relative_url');
 add_action('shutdown', 'buffer_end_protcol_relative_url');
