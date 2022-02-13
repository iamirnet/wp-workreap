<?php
include 'user.php';
function i_amir_net_workreap_mobile_exists($mobile,$limit = 1,$id = false) {
    global $wpdb;
    $table = $wpdb->prefix . '_usermeta';
    $query = $wpdb->get_results("SELECT * FROM `{$table}` WHERE `meta_key` LIKE 'mobile' AND `meta_value` LIKE '$mobile' ORDER BY `meta_value` DESC");
    if (count($query) <= $limit){
        if ($id) {
            return array("status" =>  true, 'user_id' => $query[0]->user_id);
        }else
            return true;
    }
    else
        return false;
}

function i_amir_net_workreap_check() {
    if (is_user_logged_in() && !is_user_admin()){
        $verified = get_user_meta(get_current_user_id(), 'mobile_verified', true);
        if (!$verified && strpos($_SERVER[REQUEST_URI], 'mobile') == false)
            i_amir_net_workreap_to(home_url().'/dashboard/mobile');
    }
}
add_action( 'init', 'i_amir_net_workreap_check' );

function i_amir_net_workreap_to($url){
    if (!headers_sent()){
        header('Location: '.$url); exit;
    }else{
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}