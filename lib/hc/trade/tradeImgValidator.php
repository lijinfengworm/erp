<?php
class tradeImgValidator extends sfValidatorRegex {

    protected function doClean($value) {
        if(strstr($value, "/") !== false) {
            return $value;
        }
        $pwd_dir = sfConfig::get('app_img_dir_real_pwd') . date("Ymd") . "/";
        $show_dir = sfConfig::get('app_img_dir_real_show') . date("Ymd") . "/";
        if(!is_dir($pwd_dir)) {
            mkdir($pwd_dir);
        }

        //移动临时目录的图片到upload目录
        copy(sfConfig::get('app_img_dir_tmp_pwd') . $value . ".jpg", $pwd_dir . $value . ".jpg");
        copy(sfConfig::get('app_img_dir_tmp_pwd') . $value . "_300.jpg", $pwd_dir . $value . "_300.jpg");
        copy(sfConfig::get('app_img_dir_tmp_pwd') . $value . "_210.jpg", $pwd_dir . $value . "_210.jpg");
        copy(sfConfig::get('app_img_dir_tmp_pwd') . $value . "_150.jpg", $pwd_dir . $value . "_150.jpg");
        copy(sfConfig::get('app_img_dir_tmp_pwd') . $value . "_90.jpg", $pwd_dir . $value . "_90.jpg");

        return $show_dir . $value . ".jpg";
    }

}

