<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Класс для фронтенда
 */
class WRDS_Frontend {

    public function init_hooks() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_assets' ) );

        // Пример: хук WooCommerce для пересчёта доставки
        add_filter( 'woocommerce_package_rates', array( $this, 'modify_shipping_rates' ), 10, 2 );

        // Пример: вывести дату доставки на странице товара
        add_action( 'woocommerce_single_product_summary', array( $this, 'display_product_delivery_date' ), 25 );
    }

    /**
     * Подключаем стили/скрипты для фронтенда
     */
    public function enqueue_public_assets() {
        wp_enqueue_style(
            'wrds-frontend-css',
            plugin_dir_url( __FILE__ ) . '../public/css/frontend.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'wrds-frontend-js',
            plugin_dir_url( __FILE__ ) . '../public/js/frontend.js',
            array( 'jquery' ),
            '1.0.0',
            true
        );
    }

    /**
     * Пример: модифицируем стоимость доставки в зависимости от города
     */
    public function modify_shipping_rates( $rates, $package ) {
        // Тут можно смотреть на $package['destination']['city'], применять логику
        // для изменения стоимости, если нужно.

        if ( ! empty( $package['destination']['city'] ) ) {
            $city = $package['destination']['city'];
            // Например, если город == "Moscow", накинем 100 рублей
            if ( strtolower( $city ) === 'moscow' ) {
                foreach ( $rates as $rate_key => $rate ) {
                    $new_cost = $rate->cost + 100;
                    $rates[ $rate_key ]->cost = $new_cost;
                }
            }
        }

        return $rates;
    }

    /**
     * Пример: выводим дату доставки на странице товара
     */
    public function display_product_delivery_date() {
        global $product;
        $delivery_date = get_post_meta( $product->get_id(), '_wrds_delivery_date', true );
        if ( ! empty( $delivery_date ) ) {
            echo '<p class="wrds-delivery-date">' .
                esc_html__( 'Estimated Delivery Date:', 'web-rainbow-delivery-shipping' ) .
                ' ' . esc_html( $delivery_date ) . '</p>';
        }
    }
}
