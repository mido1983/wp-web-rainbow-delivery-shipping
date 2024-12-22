<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Класс для админской части плагина
 */
class WRDS_Admin {

    /**
     * Инициализируем хуки
     */
    public function init_hooks() {
        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

        // Пример: добавляем метабокс на страницу товара
        add_action( 'add_meta_boxes', array( $this, 'add_delivery_metabox' ) );
        add_action( 'save_post', array( $this, 'save_delivery_date' ) );
    }

    /**
     * Создаём страницу настроек плагина
     */
    public function add_plugin_admin_menu() {
        add_menu_page(
            __( 'Delivery & Shipping', 'web-rainbow-delivery-shipping' ),
            __( 'Delivery & Shipping', 'web-rainbow-delivery-shipping' ),
            'manage_options',
            'wrds-settings',
            array( $this, 'display_admin_page' ),
            'dashicons-admin-generic',
            55
        );
    }

    /**
     * Выводим HTML страницы настроек
     */
    public function display_admin_page() {
        // Пример: простая форма
        ?>
        <div class="wrap">
            <h1><?php _e( 'Delivery & Shipping Settings', 'web-rainbow-delivery-shipping' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                // Регистрируем/собираем настройки
                settings_fields( 'wrds_settings_group' );
                do_settings_sections( 'wrds-settings' );

                // Пример: любое поле
                $some_option = get_option( 'wrds_some_option', '' );
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">
                            <?php _e('Some Option', 'web-rainbow-delivery-shipping'); ?>
                        </th>
                        <td>
                            <input type="text" name="wrds_some_option" value="<?php echo esc_attr( $some_option ); ?>" />
                        </td>
                    </tr>
                </table>
                <?php
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Подключаем стили/скрипты для админки
     */
    public function enqueue_admin_assets() {
        // admin.css
        wp_enqueue_style(
            'wrds-admin-css',
            plugin_dir_url( __FILE__ ) . '../admin/css/admin.css',
            array(),
            '1.0.0'
        );

        // admin.js
        wp_enqueue_script(
            'wrds-admin-js',
            plugin_dir_url( __FILE__ ) . '../admin/js/admin.js',
            array( 'jquery' ),
            '1.0.0',
            true
        );
    }

    /**
     * Пример метабокса на странице товара (для выбора даты доставки)
     */
    public function add_delivery_metabox() {
        add_meta_box(
            'wrds_delivery_date',
            __( 'Delivery Date', 'web-rainbow-delivery-shipping' ),
            array( $this, 'render_delivery_metabox' ),
            'product',
            'side',
            'core'
        );
    }

    public function render_delivery_metabox( $post ) {
        $delivery_date = get_post_meta( $post->ID, '_wrds_delivery_date', true );
        wp_nonce_field( 'wrds_delivery_date_nonce_action', 'wrds_delivery_date_nonce' );
        ?>
        <label for="wrds_delivery_date_field"><?php _e( 'Select delivery date:', 'web-rainbow-delivery-shipping' ); ?></label><br/>
        <input type="date" name="wrds_delivery_date_field" id="wrds_delivery_date_field" value="<?php echo esc_attr( $delivery_date ); ?>" />
        <?php
    }

    public function save_delivery_date( $post_id ) {
        // Проверяем nonce
        if ( ! isset( $_POST['wrds_delivery_date_nonce'] ) ||
            ! wp_verify_nonce( $_POST['wrds_delivery_date_nonce'], 'wrds_delivery_date_nonce_action' ) ) {
            return;
        }
        // Проверяем автосохранение
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        // Проверяем тип поста
        if ( 'product' !== get_post_type( $post_id ) ) {
            return;
        }

        if ( isset( $_POST['wrds_delivery_date_field'] ) ) {
            update_post_meta( $post_id, '_wrds_delivery_date', sanitize_text_field( $_POST['wrds_delivery_date_field'] ) );
        }
    }
}
