<?php


/**
 * Plugin Name: Dynamic Shortcode (Ajax)
 * Description: Make any shortcode asynchronous - Eliminate render-blocking shortcodes, improve user experience and Google PageSpeed Insights.
 * Version: 1.0
 * Author: FanaticPythoner
 * Author URI: https://github.com/FanaticPythoner
 */

// Constants
define('AJAXDS_PATH', plugin_dir_url(__FILE__));

//Admin Menu Section
require_once('AdminDashboard.php');

// Core Section
require_once('CoreHtml.php');
require_once('Dynamic.php');
require_once('SetGlobals.php');
require_once('GetGlobals.php');
require_once('DB.php');
require_once('Utils.php');
require_once('Hooks.php');
require_once('DynamicReplace.php');




// Activation hook
function ajaxds_activate()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    //Settings table
    $sql = "CREATE TABLE IF NOT EXISTS wp_dynamic_shortcode_settings (
        shortcode_name varchar(255) NOT NULL,
        placeholder_name varchar(255) NOT NULL,
        is_javascript_variable tinyint(1) NOT NULL,
        get_parameters_ignore longtext NULL,
        post_parameters_ignore longtext NULL,
        validation_function_name varchar(255) NULL,
        attributes_ignore longtext NULL,
        use_dynamic_replace tinyint(1) NOT NULL,
        PRIMARY KEY  (shortcode_name)) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    //Session table
    $sql = "CREATE TABLE IF NOT EXISTS wp_dynamic_shortcode (
        id_session varchar(255) NOT NULL,
        data longtext NOT NULL,
        get_post_params longtext NULL,
        expiration_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        current_url longtext NOT NULL,
        PRIMARY KEY  (id_session)) $charset_collate;";

    dbDelta($sql);

    //Placeholders table
    $sql = "CREATE TABLE IF NOT EXISTS wp_dynamic_shortcode_placeholders (
        placeholder_name varchar(255) NOT NULL,
        data longtext NOT NULL,
        PRIMARY KEY  (placeholder_name)) $charset_collate;";

    dbDelta($sql);

    //Global Settings table
    $sql = "CREATE TABLE IF NOT EXISTS wp_dynamic_shortcode_globalsettings (
        shortcode_name varchar(255) NOT NULL,
        is_editable tinyint(1) NOT NULL,
        setting_type varchar(255) NOT NULL,
        PRIMARY KEY  (shortcode_name, setting_type)) $charset_collate;";

    dbDelta($sql);

    // Add default placeholder
    $exist = ajaxds_doSql("SELECT placeholder_name FROM wp_dynamic_shortcode_placeholders WHERE placeholder_name = 'default'", null, 'row');
    $exist = isset($exist->placeholder_name) && $exist->placeholder_name === 'default';
    if (!$exist) {
        $sql = "INSERT INTO wp_dynamic_shortcode_placeholders (placeholder_name, data)
        VALUES (%s, %s);";
        $res = ajaxds_doSql($sql, array('default', '<div id="wp_dynamic_shortcode_[_MAIN_SHORTCODE_]_Loader" class="lds-ellipsis" style="margin: auto;display: block;text-align:center;"><div></div><div></div><div></div><div></div></div>'), 'query');
    }


    // Insert (Set) all shortcodes NOT editable by default
    $shortcodes = ajaxds_getAllNonSpecifiedEditableShortcodesGlobalSettings();
    $sql = "INSERT INTO wp_dynamic_shortcode_globalsettings (shortcode_name, is_editable, setting_type)
        VALUES (%s, %s, %s);";
    foreach ($shortcodes as $code) {
        $res = ajaxds_doSql($sql, array($code, '0', 'editable_shortcode'), 'query');
    }
}
register_activation_hook(__FILE__, 'ajaxds_activate');



/** Add script on bottom of page */
function ajaxds_addStylingMenu()
{
    echo <<<EOD
    <style>
    #toplevel_page_dynamic-shortcode-shortcodes > ul:nth-child(2) > li:nth-child(6) > a:nth-child(1) {
        color: rgba(0, 255, 52, 0.5);
        font-weight: bold;
        font-size: 16px;
        padding-top: 4px;
    }
    </style>
EOD;
}
add_action('admin_footer', 'ajaxds_addStylingMenu');




// Debugging on plugin activate
// function save_output_buffer_to_file()
// {
//     file_put_contents(
//       AJAXDS_PATH . 'activation_output_buffer.html'
//     , ob_get_contents()
//     );
// }
// add_action('activated_plugin','save_output_buffer_to_file');