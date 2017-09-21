<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ){
   exit;
}

if ( get_option( 'ethereum_donation_options' ) != false ){
   delete_option( 'ethereum_donation_options' );
}

?>
