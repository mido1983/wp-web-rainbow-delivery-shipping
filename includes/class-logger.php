<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Простой класс логгирования для отладки
 */
class WRDS_Logger {
    public static function log( $message ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[WRDS] ' . $message );
        }
    }
}
