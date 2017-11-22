<?php
class ueditorImageUploadAction extends sfAction{
    public function execute($request)
    {
        sfConfig::set('sf_web_debug', false);

        $tag1 = false;
        $tag2 = false;
        $tag  = "";
        if (!isset($_FILES["upfile"]) || !is_uploaded_file($_FILES["upfile"]["tmp_name"]) || $_FILES["upfile"]["error"] != 0) {
            $tar1 = true;
            $tag = "imgfile";
        }

        if (!isset($_FILES["imgFile"]) || !is_uploaded_file($_FILES["imgFile"]["tmp_name"]) || $_FILES["imgFile"]["error"] != 0) {
            $tar1 = true;
            $tag = "upfile";
        }

        if($tag1 && $tag2)
        {
            echo "ERROR:非法上传";
            exit(0);
        }
        if($tag=="upfile"){

            $size = filesize($_FILES["upfile"]["tmp_name"]);

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $ext = array_search(
                $finfo->file($_FILES['upfile']['tmp_name']),
                array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                    'csv' => 'text/plain'
                ),true);
            if(empty($ext))
            {
                $info = array();
                $info['state'] = '';
                return $this->renderText(json_encode($info));
            }
            $tmp_name = $_FILES["upfile"]["tmp_name"];
            $water = $request->getParameter('water');
            if($water && $ext != 'gif') {
                if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "ueditor/" . $_FILES["upfile"]["name"])) {
                    $tmp_name = 'ueditor/' . $_FILES["upfile"]["name"];
                }
            }
            //git第一次添加
            $new_file_name = 'ucditor/kulili/'.date('Ymd').'/'.md5_file($tmp_name).time().'.'.$ext;

            $qiniuObj = new tradeQiNiu();
            $qiuniu_path = $qiniuObj->uploadFile($new_file_name, $tmp_name);
            $info = array();
            $width = $request->getParameter('width');

            if(!empty($width) && $width == 'auto')
            {
                $info['url'] = $qiuniu_path;
            }
            else
            {
                $info['url'] = $qiuniu_path;
            }

            $info['size'] = $size;
            $info['state'] = 'SUCCESS';
            $info['error'] = 0;
            return $this->renderText(json_encode($info));
        }

        if($tag=="imgfile"){

            $size = filesize($_FILES["imgFile"]["tmp_name"]);
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $ext = array_search(
                $finfo->file($_FILES["imgFile"]["tmp_name"]),
                array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                    'csv' => '*.*',
                ),true);

            $rule =  unserialize($request->getParameter("rule"));
            $rule['required'] = true;
            if($request->getParameter("goods") == 'cut')
            {
                $rule['min_width'] = 400;
                $rule['min_height'] = 400;
            }
            $alltips = array();
            $alltips['required'] = "图片不能为空";
            $alltips['mime_types'] = "图片格式不正确";
            $alltips['min_width'] = "图片宽度不能小于";
            $alltips['min_height'] = "图片高度不能小于";
            $alltips['max_width'] = "图片宽度不能大于";
            $alltips['max_height'] = "图片高度不能大于";
            $alltips['max_size'] = "图片大小不能大于";
            $alltips['width'] = "图片宽度只能为";
            $alltips['height'] = "图片高度只能为";
            $alltips['ratio'] = "图片尺寸不符合";

            $tips = array();
            foreach($rule AS $key => $val)
            {
                if(isset($alltips[$key]))
                {
                    if($key == "min_width" ||$key == "min_height" || $key == "max_height" || $key == "max_height" || $key == "width" || $key == "height" )
                    {
                        $tips[$key] = $alltips[$key].$val."像素";
                    }else if($key == "max_size"){
                        $tips[$key] = $alltips[$key].($val/1000)."KB";
                    }else if($key == "ratio"){
                        $tips[$key] = $alltips[$key].$val."比例";
                    }else{
                        $tips[$key] = $alltips[$key].$val;
                    }
                }
            }

            $option = array();
            $option['validated_file_class'] = "traderQiniuValidated";
            foreach($rule AS $key => $val)
            {
                $option[$key] = $val;
            }

            $validator = new tradeValidatorFileImage($option,$tips );

            try{
                $qiniu = $validator->clean($request->getFiles('imgFile'));
                $imgurl = $qiniu->save();
            }catch(sfValidatorError $e){
                $errmsg = $e->getMessage();
            }

            if(isset($imgurl)){
                if($request->getParameter("goods") == 'cut')
                {
                    # 处理商品库图片裁剪
                    $qiuniu_path = $qiniu->getQiniuPath();
                    $imgurl = FunBase::cutGoodsPic($imgurl,$qiuniu_path);
                }
                if(empty($imgurl))
                {
                    $info['message'] = '另存图片失败';
                    $info['error'] = 1;
                }
                else
                {
                    $info['url'] = $imgurl;
                    $info['error'] = 0;
                }
            }else{
                $info['message'] = $errmsg;
                $info['error'] = 1;
            }

        }
        @unlink($tmp_name);
        return $this->renderText(json_encode($info));
    }
    //图片加水印
    public function watermark($source, $target = '', $w_pos = '', $w_img = '', $w_text = '99danji',$w_font = 8, $w_color = '#ff0000') {
        $this->w_img = 'images/kaluli/watermark.png';
        $this->w_pos = 9;
        $this->w_minwidth = 400;
        $this->w_minheight = 200;
        $this->w_quality = 80;
        $this->w_pct = 85;

        $w_pos = $w_pos ? $w_pos : $this->w_pos;
        $w_img = $w_img ? $w_img : $this->w_img;
        //if(!$this->watermark_enable || !$this->check($source)) return false;
        if(!$target) $target = $source;
        //$w_img = PHPCMS_PATH.$w_img;
        //define('WWW_PATH', dirname(dirname(dirname(__FILE__)));
        $source_info = getimagesize($source);
        $source_w  = $source_info[0];
        $source_h  = $source_info[1];
        //if($source_w < $this->w_minwidth || $source_h < $this->w_minheight) return false;
        switch($source_info[2]) {
            case 1 :
                $source_img = imagecreatefromgif($source);
                break;
            case 2 :
                $source_img = imagecreatefromjpeg($source);
                break;
            case 3 :
                $source_img = imagecreatefrompng($source);
                break;
            default :
                return false;
        }
        if(!empty($w_img) && file_exists($w_img)) {
            $ifwaterimage = 1;
            $water_info  = getimagesize($w_img);
            $width    = $water_info[0];
            $height    = $water_info[1];
            switch($water_info[2]) {
                case 1 :
                    $water_img = imagecreatefromgif($w_img);
                    break;
                case 2 :
                    $water_img = imagecreatefromjpeg($w_img);
                    break;
                case 3 :
                    $water_img = imagecreatefrompng($w_img);
                    break;
                default :
                    return;
            }
        } else {
            $ifwaterimage = 0;
            $temp = imagettfbbox(ceil($w_font*2.5), 0, 'libs/data/font/elephant.ttf', $w_text);
            $width = $temp[2] - $temp[6];
            $height = $temp[3] - $temp[7];
            unset($temp);
        }
        switch($w_pos) {
            case 1:
                $wx = 5;
                $wy = 5;
                break;
            case 2:
                $wx = ($source_w - $width) / 2;
                $wy = 0;
                break;
            case 3:
                $wx = $source_w - $width;
                $wy = 0;
                break;
            case 4:
                $wx = 0;
                $wy = ($source_h - $height) / 2;
                break;
            case 5:
                $wx = ($source_w - $width) / 2;
                $wy = ($source_h - $height) / 2;
                break;
            case 6:
                $wx = $source_w - $width;
                $wy = ($source_h - $height) / 2;
                break;
            case 7:
                $wx = 0;
                $wy = $source_h - $height;
                break;
            case 8:
                $wx = ($source_w - $width) / 2;
                $wy = $source_h - $height;
                break;
            case 9:
                $wx = $source_w - $width;
                $wy = $source_h - $height;

                break;
            case 10:
                $wx = rand(0,($source_w - $width));
                $wy = rand(0,($source_h - $height));
                break;
            default:
                $wx = rand(0,($source_w - $width));
                $wy = rand(0,($source_h - $height));
                break;
        }

        if($ifwaterimage) {
            if($water_info[2] == 3) {
                imagecopy($source_img, $water_img, $wx, $wy, 0, 0, $width, $height);
            } else {
                imagecopymerge($source_img, $water_img, $wx, $wy, 0, 0, $width, $height, $this->w_pct);
            }
        } else {
            if(!empty($w_color) && (strlen($w_color)==7)) {
                $r = hexdec(substr($w_color,1,2));
                $g = hexdec(substr($w_color,3,2));
                $b = hexdec(substr($w_color,5));
            } else {
                return;
            }
            imagestring($source_img,$w_font,$wx,$wy,$w_text,imagecolorallocate($source_img,$r,$g,$b));
        }

        switch($source_info[2]) {
            case 1 :
                imagegif($source_img, $target);
                break;
            case 2 :
                imagejpeg($source_img, $target, $this->w_quality);
                break;
            case 3 :
                imagepng($source_img, $target);
                break;
            default :
                return;
        }

        if(isset($water_info)) {
            unset($water_info);
        }
        if(isset($water_img)) {
            imagedestroy($water_img);
        }
        unset($source_info);
        imagedestroy($source_img);
        return true;
    }

    public function check($image) {
        return extension_loaded('gd') && preg_match("/\.(jpg|jpeg|gif|png|csv)/i", $image, $m) && file_exists($image) && function_exists('imagecreatefrom'.($m[1] == 'jpg' ? 'jpeg' : $m[1]));
    }

}
