<?php
$theme_routes = new CustomRoutes();
$theme_routes->addRoute(
    "^dashboard/upload",
    'upload_callback'
);
function upload_callback() {
    if (is_user_logged_in())
        include plugin_dir_path(__DIR__).'pages/upload-doc.php';
    else
        wp_redirect(home_url());
}

$theme_routes->addRoute(
    "^dashboard/mobile",
    'mobile_callback'
);
function mobile_callback() {
    if (is_user_logged_in())
        include plugin_dir_path(__DIR__).'pages/verify-mobile.php';
    else
        wp_redirect(home_url());
}