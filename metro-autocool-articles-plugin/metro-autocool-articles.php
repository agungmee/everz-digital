<?php
/**
 * Plugin Name: Metro Autocool Articles
 * Plugin URI: https://acmobilsurabaya.com
 * Description: Plugin untuk menampilkan artikel Metro Autocool dengan shortcode yang bisa digunakan di Elementor
 * Version: 1.0.0
 * Author: Metro Autocool
 * Author URI: https://acmobilsurabaya.com
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: metro-autocool-articles
 * Domain Path: /languages
 */

// Exit jika file diakses langsung
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('METRO_AUTOCOOL_ARTICLES_VERSION', '1.0.0');
define('METRO_AUTOCOOL_ARTICLES_PATH', plugin_dir_path(__FILE__));
define('METRO_AUTOCOOL_ARTICLES_URL', plugin_dir_url(__FILE__));

// Include file utama plugin
require_once METRO_AUTOCOOL_ARTICLES_PATH . 'includes/class-metro-autocool-articles.php';

// Initialize plugin
function metro_autocool_articles_init() {
    $plugin = new Metro_Autocool_Articles();
    $plugin->run();
}

add_action('plugins_loaded', 'metro_autocool_articles_init');
