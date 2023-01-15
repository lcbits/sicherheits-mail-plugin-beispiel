<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
require_once( plugin_dir_path( __FILE__ ) . '/attack-plugin.php' );


if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    update_email_clicked_counter($user_id);
    wp_redirect( home_url() );
    exit;
}