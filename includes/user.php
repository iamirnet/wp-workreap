<?php

function extra_user_profile_fields( $user ) {
    $terms = array("برنزی","نقره ای","طلایی");
    $term = get_user_meta($user->ID, 'bit_term', true)
    ?>
    <h3><?php _e("اطلاعات سفارشی", "blank"); ?></h3>

    <table class="form-table">
        <tr>
            <th><label><?php _e("دسته کاربری"); ?></label></th>
            <td>
                <select id="bit_term" name="bit_term" >
                    <?php
                    foreach ($terms as $key => $value){
                        if ($key == $term) {
                            echo "<option value=\"$key\" selected>$value</option>";
                        }else{
                            echo "<option value=\"$key\">$value</option>";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="mobile"><?php _e("موبایل"); ?></label></th>
            <td>
                <input type="text" id="mobile" name="mobile"  value="<?php echo esc_attr( get_the_author_meta( 'mobile', $user->ID ) ); ?>" class="regular-text" /><br />
                <?php
                    $verified = get_user_meta($user->ID, 'mobile_verified', true);
                    if ($verified){
                        echo "<span class=\"description\">این شماره در ".date('Y/m/d H:i:s', $verified)." تایید شده است.</span>";
                    }else
                        echo "<span class=\"description\">این شماره تایید نشده است.</span>";
                ?>
            </td>
        </tr>
        <tr>
            <th>مدارک</th>
            <td>
                <?php
                    $national = get_user_meta($user->ID, 'national_file', true);
                    $job = get_user_meta($user->ID, 'job_file', true);
                    $stay = get_user_meta($user->ID, 'stay_file', true);
                    $base_url = wp_upload_dir()['baseurl'];
                    if ($national) echo "<a href=\"{$base_url}/{$national}\" class=\"button button-secondary\" target=\"_blank\">کارت ملی</a>";
                    if ($job) echo "<a href=\"{$base_url}/{$job}\" class=\"button button-secondary mr-2\" target=\"_blank\">مدارک شغلی</a>";
                    if ($stay) echo "<a href=\"{$base_url}/{$stay}\" class=\"button button-secondary mr-2\" target=\"_blank\">مدارک تحصیلی</a>";
                ?>
            </td>
        </tr>
    </table>
<?php }
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );



function save_extra_user_profile_fields( $user_id ) {
    global $wpdb;
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
    update_user_meta( $user_id, 'bit_term', $_POST['bit_term'] );
    update_user_meta( $user_id, 'mobile', $_POST['mobile'] );

}
add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
