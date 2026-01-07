<?php

namespace SolidApi\Database;

class Migration {
    
    public static function run() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'student_books';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_name varchar(255) NOT NULL,
            book_title varchar(255) NOT NULL,
            isbn varchar(50) DEFAULT NULL,
            borrowed_date datetime DEFAULT NULL,
            return_date datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function rollback() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'student_books';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
}
