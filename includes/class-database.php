<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Пример класса для работы с таблицами плагина
 */
class WRDS_Database {

    /**
     * Сохраняем дату доставки в кастомную таблицу
     */
    public static function insert_delivery_date( $product_id, $date ) {
        global $wpdb;
        $table = $wpdb->prefix . 'wrds_delivery_dates';

        $wpdb->insert(
            $table,
            array(
                'product_id'     => $product_id,
                'delivery_date'  => $date,
            ),
            array(
                '%d',
                '%s',
            )
        );
    }

    /**
     * Получаем даты доставки по ID товара
     */
    public static function get_delivery_dates_by_product( $product_id ) {
        global $wpdb;
        $table = $wpdb->prefix . 'wrds_delivery_dates';

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table WHERE product_id = %d",
                $product_id
            ),
            ARRAY_A
        );
        return $results;
    }

    /**
     * Пример записи города и стоимости в таблицу
     */
    public static function insert_city_shipping( $city_name, $shipping_cost ) {
        global $wpdb;
        $table = $wpdb->prefix . 'wrds_shipping_cities';

        $wpdb->insert(
            $table,
            array(
                'city_name'     => $city_name,
                'shipping_cost' => $shipping_cost,
            ),
            array(
                '%s',
                '%f',
            )
        );
    }

    /**
     * Пример получения всех записей по городам
     */
    public static function get_city_shipping_list() {
        global $wpdb;
        $table = $wpdb->prefix . 'wrds_shipping_cities';

        $results = $wpdb->get_results(
            "SELECT * FROM $table ORDER BY city_id ASC",
            ARRAY_A
        );
        return $results;
    }
}
