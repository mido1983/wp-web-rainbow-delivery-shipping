<?php
/**
 * Plugin Name: WebRainbow Delivery & Shipping
 * Plugin URI:  https://example.com
 * Description: Объединённый плагин (Custom Delivery Date + Custom City Shipping). Демонстрационная версия.
 * Version:     1.0.0
 * Author:      Your Name
 * Author URI:  https://example.com
 * Text Domain: web-rainbow-delivery-shipping
 * Domain Path: /languages
 *
 * WC requires at least: 4.0
 * WC tested up to: 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Защита от прямого доступа к файлу
}

// 1. Подключаем нужные файлы
require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-frontend.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-logger.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-database.php';

// 2. Регистрируем хуки активации/деактивации
register_activation_hook( __FILE__, array( 'WRDS_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WRDS_Deactivator', 'deactivate' ) );

/**
 * Инициализируем плагин.
 */
function wrds_init_plugin() {
    // Загружаем текстовый домен для переводов
    load_plugin_textdomain(
        'web-rainbow-delivery-shipping',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );

    // Проверяем, что WooCommerce активен
    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', function() {
            ?>
            <div class="notice notice-error">
                <p><?php _e( 'WebRainbow Delivery & Shipping требует установленный и активированный WooCommerce!', 'web-rainbow-delivery-shipping' ); ?></p>
            </div>
            <?php
        } );
        return;
    }

    // Инициализация админской части
    $admin = new WRDS_Admin();
    $admin->init_hooks();

    // Инициализация фронтенда
    $frontend = new WRDS_Frontend();
    $frontend->init_hooks();
}
add_action( 'plugins_loaded', 'wrds_init_plugin' );
