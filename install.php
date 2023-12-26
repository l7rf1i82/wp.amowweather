<?php

global $amoweather_db_version;
       $amoweather_db_version = '1.0';

function amoweather_db_table_init()
{
    global $wpdb;
    global $amoweather_db_version;

    $table_name = $wpdb->prefix . 'amoweather';
    $charset_collate = $wpdb->get_charset_collate();

    // time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time tinytext NOT NULL,
		location tinytext NOT NULL,
		temperature tinytext NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );

    add_option( 'amoweather_db_version', $amoweather_db_version );
    add_option( 'amoweather_toggle_public_widget', 0, '', false );
}
