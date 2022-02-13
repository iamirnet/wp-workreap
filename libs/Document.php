<?php


class Document
{
    public $user = null;
    public $national = null;
    public $job = null;
    public $stay = null;
    public $base_url = null;

    public function __construct()
    {
        if (is_user_logged_in()){
            $this->user = wp_get_current_user();
            $this->national = get_user_meta($this->user->id, 'national_file', true);
            $this->job = get_user_meta($this->user->id, 'job_file', true);
            $this->stay = get_user_meta($this->user->id, 'stay_file', true);
        }
        $this->base_url = wp_upload_dir()['baseurl'];
    }

    public function upload()
    {
        if($_FILES['national']['name'] != ''){
            $national = $this->save($_FILES['national'], 'کارت ملی', 1, ['png', 'jpeg', 'jpg']);
            $msg[] = $national['msg'];
            if ($national['status']){
                $this->national = $national['url'];
                update_user_meta($this->user->id, 'national_file', $this->national);
            }
        }
        if($_FILES['job']['name'] != ''){
            $job = $this->save($_FILES['job'], 'مدارک شغلی', 3);
            $msg[] = $job['msg'];
            if ($job['status']){
                $this->job = $job['url'];
                update_user_meta($this->user->id, 'job_file', $this->job);
            }
        }
        if($_FILES['stay']['name'] != ''){
            $stay = $this->save($_FILES['stay'], 'مدارک تحصیلی', 3);
            $msg[] = $stay['msg'];
            if ($stay['status']){
                $this->stay = $stay['url'];
                update_user_meta($this->user->id, 'stay_file', $this->stay);
            }
        }
        return $msg;

    }

    public function save($file, $title, $size = null , $types = ['x-zip-compressed', 'png', 'jpeg', 'jpg']) {
        $verify = $this->verify($file, $title, $size, $types);
        if ($verify['status']){
            $upload = wp_upload_bits( $file['name'], null, file_get_contents( $file['tmp_name'] ) );
            $wp_filetype = wp_check_filetype( basename( $upload['file'] ), null );
            $attachment = array(
                'guid' => $this->base_url .'/'. _wp_relative_upload_path($upload['file']),
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename( $upload['file'] )),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment( $attachment, $upload['file'] );
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            return array_merge($verify, ['url' => _wp_relative_upload_path($upload['file'])]);
        }
        return $verify;
    }

    public function verify($file, $title, $size = null , $types = ['x-zip-compressed', 'png', 'jpeg', 'jpg']) {
        $size = $size ? : 5;
        if (!in_array(explode('/',$file['type'])[1], $types)){
            $error = "فایل های مورد قبول ".implode(',', $types)." برای {$title} می باشد.";
        }elseif ($file['size'] > (($size * 1024) * 1024)){
            $error = "اندازه فایل {$title} نباید بزرگ تر {$size}mb از باشد.";
        }
        return array('status' => !isset($error), 'msg' => !isset($error) ? "فایل {$title} با موفقیت آپلود شد." : $error);
    }
}