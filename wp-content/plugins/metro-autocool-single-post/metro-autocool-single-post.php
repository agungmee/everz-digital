<?php
/**
 * Plugin Name: Metro Autocool Single Post
 * Plugin URI: https://acmobilsurabaya.com
 * Description: Template single post blog untuk Metro Autocool dengan navbar dan footer yang sama seperti halaman utama
 * Version: 1.0.0
 * Author: Metro Autocool
 * Author URI: https://acmobilsurabaya.com
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: metro-autocool-single-post
 * Domain Path: /languages
 */

// Exit jika file diakses langsung
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('METRO_SINGLE_POST_VERSION', '1.0.0');
define('METRO_SINGLE_POST_PATH', plugin_dir_path(__FILE__));
define('METRO_SINGLE_POST_URL', plugin_dir_url(__FILE__));

// Include file utama plugin
require_once METRO_SINGLE_POST_PATH . 'includes/class-single-post-template.php';

// Initialize plugin
function metro_single_post_init() {
    $plugin = new Metro_Single_Post_Template();
    $plugin->run();
}

add_action('plugins_loaded', 'metro_single_post_init');
