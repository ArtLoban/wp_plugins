<?php

if( ! defined('WP_UNINSTALL_PLUGIN') )
    exit;

function al_delete_plugin_data() {

    // ...

    // Clear any cached data that has been removed.
    wp_cache_flush();
}

// Execute function
al_delete_plugin_data();

print_r('Plugin has been conditionally removed');
die();