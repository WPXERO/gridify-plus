<?php
/*
 * Plugin Name: Gridify Plus
 * Plugin URI: https://wordpress.org/plugins/gridify-plus/
 * Description: This plugin will create Custom WooCommerce Product Grid for Elementor.
 * Version: 1.0.0
 * Author: WPXERO
 * Author URI: https://wpxero.com/gridify-plus/
 * License: GPLv3
 * Text Domain: gridify-plus
 * Domain Path: /languages/
 */

namespace GridifyPlus;


use GridifyPlus\Widgets\Product_Grid;
use GridifyPlus\Widgets\Post_Grid;

define('GRIDIFYPLUS_VERSION', '1.0.0');

if (!defined('ABSPATH')) {
    exit(__('Direct script access denied.', 'gridify-plus')); // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped
}

final class GridifyPlus {
    const VERSION                   = GRIDIFYPLUS_VERSION;
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION       = '7.0';

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    public function __construct() {

        add_action('plugins_loaded', [$this, 'gridifyplus']);
        add_filter('unzip_file_use_ziparchive', '__return_false');
        $this->init_files();
    }

    public function init_files() {
        require_once __DIR__ . '/includes/helper.php';
    }

    public function i18n() {

        load_plugin_textdomain('gridify-plus');
    }

    public function gridifyplus() {

        if ($this->is_compatible()) {
            add_action('elementor/init', [$this, 'init']);
        }
    }

    public function is_compatible() {

        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return false;
        }

        // Check if WooCommerce installed and activated
        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            add_action('admin_notices', [$this, 'admin_notice_missing_woocommerce_plugin']);
            // return false;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return false;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return false;
        }


        return true;
    }


    public function init() {
        $this->i18n();
        // Add Plugin actions
        add_action('elementor/widgets/register', [$this, 'init_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'register_new_category']);
        add_action('elementor/frontend/before_register_scripts', array($this, 'widget_assets_enqueue'));
    }

    public function register_new_category($elements_manager) {
        $elements_manager->add_category(
            'gridify-plus',
            [
                'title' => __('Gridify Plus', 'gridify-plus'),
            ]
        );
    }

    public function widget_assets_enqueue() {
        wp_register_style('post-grid', plugin_dir_url(__FILE__) . 'assets/css/post-grid.css', null, self::VERSION, null);
        wp_register_style('product-grid', plugin_dir_url(__FILE__) . 'assets/css/product-grid.css', null, self::VERSION, null);
    }

    public function admin_notice_missing_woocommerce_plugin() {
        $message = sprintf('"%1$s" requires "%2$s" to be installed and activated.', '<strong>Gridify Plus</strong>', '<strong>Woocommerce</strong>');
        echo '<div class="notice notice-warning is-dismissible"><p>' . wp_kses_post($message) . '</p></div>';
    }
    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) unset($_GET['activate']); // phpcs:ignore WordPress.Security.NonceVerification
        $message = sprintf('"%1$s" requires "%2$s" to be installed and activated.', '<strong>Gridify Plus</strong>', '<strong>Elementor</strong>');
        echo '<div class="notice notice-warning is-dismissible"><p>' . wp_kses_post($message) . '</p></div>';
    }

    public function admin_notice_minimum_elementor_version() {

        if (isset($_GET['activate'])) unset($_GET['activate']); // phpcs:ignore WordPress.Security.NonceVerification

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'gridify-plus'),
            '<strong>' . esc_html__('Gridify Plus', 'gridify-plus') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'gridify-plus') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        echo '<div class="notice notice-warning is-dismissible"><p>' . wp_kses_post($message) . '</p></div>';
    }

    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']); // phpcs:ignore WordPress.Security.NonceVerification
        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'gridify-plus'),
            '<strong>' . esc_html__('Gridify Plus', 'gridify-plus') . '</strong>',
            '<strong>' . esc_html__('PHP', 'gridify-plus') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        echo '<div class="notice notice-warning is-dismissible"><p>' . wp_kses_post($message) . '</p></div>';
    }
    /**
     * ! Widgets Init
     */
    public function init_widgets($widgets_manager) {
        require_once __DIR__ . '/widgets/post-grid.php';
        $widgets_manager->register_widget_type(new Post_Grid());

        if (class_exists('WooCommerce')) {
            require_once __DIR__ . '/widgets/product-grid.php';
            $widgets_manager->register_widget_type(new Product_Grid());
        }
    }
}



GridifyPlus::instance();
