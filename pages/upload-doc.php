<?php
include 'header.php';
$user = wp_get_current_user();
include plugin_dir_path(__DIR__).'libs/Document.php';
$document = new Document();
if (isset($_POST)){
    $upload = $document->upload();
}
?>
<div class="col-xs-12 col-sm-12 col-md-10 push-md-1 col-lg-8 push-lg-2">
    <div class="wt-registerformhold">
        <div class="tab-content wt-registertabcontent">
            <div class="tab-pane active step-one-contents" id="one">
                <div class="wt-registerformmain">
                    <div class="wt-registerhead">
                        <div class="wt-title">
                            <h3>آپلود مدارک</h3>
                        </div>
                    </div>
                    <div class="wt-joinforms">
                        <div class="text-right">
                            <?php
                                if (isset($upload))
                                    foreach ($upload as $msg)
                                        echo $msg."<br/>";
                            ?>
                        </div>
                        <form class="wt-formtheme wt-formregister" method="post" enctype="multipart/form-data">
                            <fieldset class="wt-registerformgroup">
                                <div class="form-group form-group-half position-relative mt-3">
                                    <label style="right: 9px;top: -11px;background-color: #fff;" class="position-absolute input-label "><span class="ml-2">کارت ملی</span><?php echo $document->national ? "<a href=\"{$document->base_url}/{$document->national}\" target=\"_blank\">(دانلود)</a>": null; ?></label>
                                    <input class="form-control" type="file" name="national" accept="image/png,image/jpeg">
                                </div>
                                <div class="form-group form-group-half position-relative mt-3">
                                    <label style="right: 9px;top: -11px;background-color: #fff;" class="position-absolute input-label "><span class="ml-2">مدارک شغلی</span><?php echo $document->job ? "<a href=\"{$document->base_url}/{$document->job}\" target=\"_blank\">(دانلود)</a>": null; ?></label>
                                    <input class="form-control" type="file" name="job" accept="application/zip,image/png,image/jpeg">
                                </div>
                                <div class="form-group form-group-half position-relative mt-3">
                                    <label style="right: 9px;top: -11px;background-color: #fff;" class="position-absolute input-label"><span class="ml-2">مدارک تحصیلی</span><?php echo $document->stay ? "<a href=\"{$document->base_url}/{$document->stay}\" target=\"_blank\">(دانلود)</a>": null; ?></label>
                                    <input class="form-control" type="file" name="study" accept="application/zip,image/png,image/jpeg">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="wt-btn">آپلود مدارک</button>
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