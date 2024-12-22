<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Класс для обработки деактивации плагина
 */
class WRDS_Deactivator {
    public static function deactivate() {
        // Здесь можно отключать кроны, очищать кеш, останавливать расписания и т.д.
        // Таблицы и данные при деактивации обычно не удаляют (чтобы при повторном включении они остались).

        // Пример:
       wp_clear_scheduled_hook( 'wrds_some_cron_event' );
    }
}
