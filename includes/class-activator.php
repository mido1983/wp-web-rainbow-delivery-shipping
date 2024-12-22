<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Класс для обработки активации плагина (создание таблиц и т.п.)
 */
class WRDS_Activator {
    public static function activate() {
        global $wpdb;

        // Пример создания таблицы для дат доставки
        $table_delivery = $wpdb->prefix . 'wrds_delivery_dates';
        $charset_collate = $wpdb->get_charset_collate();

        $sql_delivery = "CREATE TABLE IF NOT EXISTS $table_delivery (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            product_id bigint(20) NOT NULL,
            delivery_date datetime NOT NULL,
            PRIMARY KEY (id),
            KEY product_id (product_id)
        ) $charset_collate;";

        // Пример создания таблицы для городов и стоимости доставки
        $table_cities = $wpdb->prefix . 'wrds_shipping_cities';
        $sql_cities = "CREATE TABLE IF NOT EXISTS $table_cities (
            city_id mediumint(9) NOT NULL AUTO_INCREMENT,
            city_name varchar(255) NOT NULL,
            shipping_cost float NOT NULL,
            PRIMARY KEY (city_id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_delivery );
        dbDelta( $sql_cities );

        // Можно добавить другие действия, если нужно
        // Например, запись опций по умолчанию
        add_option( 'wrds_plugin_version', '1.0.0' );
    }
}
