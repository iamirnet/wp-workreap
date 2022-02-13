<?php
function i_amir_net_workreap_raygansms()
{
    if (!current_user_can('i_amir_net_workreap_manage')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    if ($_POST){
        $data = [];
        if (isset($_POST['username']))
            $data['username'] = $_POST['username'];
        if (isset($_POST['password']))
            $data['password'] = $_POST['password'];
        if (isset($_POST['number']))
            $data['number'] = $_POST['number'];
        if (isset($_POST['number']))
            $data['footer'] = $_POST['footer'];
        update_option('i_amir_net_workreap_raygansms', json_encode($data));
    }
    $sms = get_option('i_amir_net_workreap_workreap_raygansms');
    if ($sms)
        $sms = json_decode($sms);
    $bit_username = isset($sms->username) ? $sms->username : null;
    $bit_password = isset($sms->password) ?  $sms->password : null;
    $bit_number = isset($sms->number) ?  $sms->number : null;
    $bit_footer = isset($sms->footer) ?  $sms->footer : null;
    ?>
    <div class="wrap">

        <h1><?php echo esc_html(get_admin_page_title()); ?> | سامانه پیامک</h1>

        <form method="post" action="<?php echo esc_html(admin_url('admin.php?page=i_amir_net_workreap_workreap')); ?>">
            <table class="form-table" role="presentation">

                <tbody>
                <tr>
                    <th scope="row">
                        <label for="title">نام کاربری</label>
                    </th>
                    <td>
                        <input name="username" type="text" id="bit_username"  placeholder="نام کاربری را وارد کنید" class="regular-text" value="<?php echo $bit_username ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="title">رمز عبور</label>
                    </th>
                    <td>
                        <input name="password" type="text" id="bit_password" value="<?php echo $bit_password ?>" placeholder="رمز عبور را وارد کنید" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="title">شماره ارسال</label>
                    </th>
                    <td>
                        <input name="number" type="text" id="bit_number" value="<?php echo $bit_number ?>" placeholder="شماره ارسال را وارد کنید" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="title">متن پاورقی</label>
                    </th>
                    <td>
                        <input name="footer" type="text" id="bit_footer" value="<?php echo $bit_footer ?>" class="regular-text">
                    </td>
                </tr>
                </tbody>
            </table>

            <?php
            wp_nonce_field('acme-settings-save', 'acme-custom-message');
            submit_button();
            ?>
        </form>

    </div>
    <?php
}
