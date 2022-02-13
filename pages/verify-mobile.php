<?php
include 'header.php';
$user = wp_get_current_user();
$mobile = get_user_meta($user->id, 'mobile', true);
if (isset($_POST)){
    include plugin_dir_path(__DIR__).'libs/Raygansms.php';
    $raygansms = new Raygansms();
}
$btn = 'ارسال کد تایید';
if (isset($_POST['mobile'])){
    $v_mobile = $raygansms->send();
    if ($v_mobile['status'])
        $btn = 'بررسی کد';
}elseif (isset($_POST['code'])){
    $v_mobile = $raygansms->check();
}
?>
    <div class="col-xs-12 col-sm-12 col-md-10 push-md-1 col-lg-8 push-lg-2">
        <div class="wt-registerformhold">
            <div class="tab-content wt-registertabcontent">
                <div class="tab-pane active step-one-contents" id="one">
                    <div class="wt-registerformmain">
                        <div class="wt-registerhead">
                            <div class="wt-title">
                                <h3>اعتبار سنجی / تغییر شماره موبایل</h3>
                            </div>
                        </div>
                        <div class="wt-joinforms">
                            <div class="text-right"><?php if (isset($v_mobile['msg'])) echo $v_mobile['msg']; ?></div>
                            <form class="wt-formtheme wt-formregister" method="post">
                                <fieldset class="wt-registerformgroup">
                                    <?php if (!isset($_POST['mobile']) || (isset($v_mobile['status']) && !$v_mobile['status'])){ ?>
                                    <div class="form-group">
                                        <input class="form-control" name="mobile" type="text" value="<?php echo $mobile ?>" placeholder="شماره موبایل" >
                                    </div>
                                    <?php } else { ?>
                                        <div class="form-group">
                                            <input class="form-control" name="code" type="text"  placeholder="کد تایید" >
                                        </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <button type="submit" class="wt-btn"><?php echo $btn ?></button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include 'footer.php'; ?>