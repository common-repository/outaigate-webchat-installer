<?php
/*
Plugin Name: OutaiGate WebChat Installer
Version: 1.0.3
Description: エックスサーバー株式会社が提供する各レンタルサーバーサービスでWebフォントを利用できるプラグインです。
Author: THEWC JAPAN
Author URI: https://www.thewc.co.jp/
Plugin URI: 
Text Domain: outaigate
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'OutaiGate_ADMIN_VERSION', '1.0.3' );

define( 'OutaiGate_ADMIN_DIR', 'outaigate-webchat-installer' );

/**
 * Helpers
 */
require plugin_dir_path( __FILE__ ) . 'includes/helpers.php';


/**
 * The core plugin class
 */
require plugin_dir_path( __FILE__ ) . 'includes/outaigate-admin-form.php';


function run_outaigate_admin_form() {

    $plugin = new OutaiGate_Admin_Form();
    $plugin->init();

}
run_outaigate_admin_form();

