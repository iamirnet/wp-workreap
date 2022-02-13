<?php
add_action('admin_menu', 'i_amir_net_workreap_manage_admin_menu');
function i_amir_net_workreap_manage_admin_menu(){
    add_menu_page('سفارش سازی ورک ریپ', 'سفارش سازی ورک ریپ', 'i_amir_net_workreap_manage', 'i_amir_net_workreap', '', 'dashicons-layout', 3);
    add_submenu_page('i_amir_net_workreap', 'درگاه پیامک', 'درگاه پیامک', 'i_amir_net_workreap_manage', 'i_amir_net_workreap', 'i_amir_net_workreap_raygansms');
}
