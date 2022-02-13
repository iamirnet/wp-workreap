<?php


class Raygansms
{
    public $username = null;
    public $password = null;
    public $from_number = null;
    public $footer = null;
    public $user = null;
    public $verify = null;
    public $verified = null;
    public $code = null;
    public $v_mobile = null;
    public $v_num = null;

    public function __construct()
    {
        $sms = get_option('i_amir_net_workreap_raygansms');
        if ($sms)
            $sms = json_decode($sms);
        $this->username = isset($sms->username) ? $sms->username : null;
        $this->password = isset($sms->password) ?  $sms->password : null;
        $this->from_number = isset($sms->number) ?  $sms->number : null;
        $this->footer = isset($sms->footer) ?  $sms->footer : null;
        if (is_user_logged_in()){
            $this->user = wp_get_current_user();
            $this->verify = get_user_meta($this->user->id, 'mobile_verify', true);
            $this->verified = get_user_meta($this->user->id, 'mobile_verified', true);
            $this->code = get_user_meta($this->user->id, 'mobile_verify_code',true);
            $this->v_mobile = get_user_meta($this->user->id, 'mobile_verified_mobile', true);
            $this->v_num = get_user_meta($this->user->id, 'mobile_verify_num', true);
        }
    }

    public function send()
    {
        $mobile = isset($_POST['mobile']) ? $_POST['mobile']: null;
        if ($mobile) {
            if ($mobile == $this->v_mobile)
                $error = 'شماره موبایل متفاوت باید وارد کنید.';
            else if (!i_amir_net_workreap_mobile_exists($mobile))
                $error = 'شماره قبلا برای کاربر دیگر ثبت شده است.';
            elseif ($this->verified && (int) $this->verified + 3600 > time()) {
                $error = 'بین هر تغییر شماره موبایل و تایید آن حداقل یک ساعت صبر کنید و شکیبا باشید.';
            } elseif ($this->v_num && $this->v_num < 3 && ((int) $this->verify + 60 > time())) {
                $error = 'بین هر ارسال حداقل یک دقیقه صبر کنید و شکیبا باشید.';
            } elseif ($this->v_num && $this->v_num < 3 && ((int) $this->verify + 600 > time())) {
                $error = 'شما سه بار کد را فرستادید و تا ده دقیقه دیگر باید صبر کنید.';
            } else {
                $code = rand(123456, 655357);
                $send = $this->sendCode($mobile, $code);
                if ($send) {
                    $this->verify = time();
                    $this->v_num = $this->v_num >= 3 ? 0 : $this->v_num + 1;
                    $this->code = $code;
                    update_user_meta( $this->user->id, 'mobile', $mobile );
                    update_user_meta( $this->user->id, 'mobile_verify', $this->verify );
                    update_user_meta( $this->user->id, 'mobile_verified', null );
                    update_user_meta( $this->user->id, 'mobile_verify_num', $this->v_num );
                    update_user_meta( $this->user->id, 'mobile_verify_code', $this->code );
                    $success = 'کد با موفقیت ارسال شد.';
                } else
                    $error = 'کد ارسال نشد.';
            }
        }else
            $error = 'شماره موبایل وارد نشده است.';
        return array('status' => isset($success), 'msg' => isset($success) ? $success : $error);
    }

    public function check()
    {
        $mobile = get_user_meta($this->user->id, 'mobile', true);
        $code = isset($_POST['code']) ? $_POST['code'] : null;
        if ($mobile && $code && !$this->verified) {
            if ($this->verify + 600 < time()) {
                update_user_meta( $this->user->id, 'mobile_verify', 0 );
                update_user_meta( $this->user->id, 'mobile_verify_num', 0 );
                update_user_meta( $this->user->id, 'mobile_verify_code', null );
                $error = 'متاسفانه زمان کد منقضی شده، لطفا مجدد کد را به همراه خود ارسال کنید.';
            } else {
                update_user_meta( $this->user->id, 'mobile_verify', null );
                update_user_meta( $this->user->id, 'mobile_verified', time() );
                update_user_meta( $this->user->id, 'mobile_verify_num', null );
                update_user_meta( $this->user->id, 'mobile_verify_code', null );
                update_user_meta( $this->user->id, 'mobile_verified_mobile', $mobile );
                $success = 'شماره موبایل شما تایید شد.';
            }
        } else
            $error = 'کد تایید وارد شده اشتباه است.';
        return array('status' => isset($success) ? true : false, 'msg' => isset($success) ? $success : $error);
    }

    public function sendCode($mobile, $code) {
        return $this->Send_Message('کد تایید شما: '.$code."\n".$this->footer,[$mobile]);
    }

    public function Send_Message($message, $mobiles_array)
    {
        $url = "http://smspanel.trez.ir/api/smsAPI/SendMessage";
        $post_data = json_encode(array(
            'PhoneNumber' => $this->from_number,
            'Message' => $message,
            'Mobiles' => $mobiles_array,
            'UserGroupID' => uniqid(),
            'SendDateInTimeStamp' => time(),
        ));
        $process = curl_init();
        curl_setopt($process, CURLOPT_URL, $url);
        curl_setopt($process, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($process, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($process, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'));
        $return = curl_exec($process);
        $httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);
        if ($httpcode == 401) {
            throw new exception("نام کاربری یا کلمه عبور صحیح نمی باشد");
        }
        curl_close($process);
        $decoded = json_decode($return);
        return true;
    }
}