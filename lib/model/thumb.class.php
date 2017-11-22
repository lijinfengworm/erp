<?php

/*
 * 生成缩略图的类
 */

class thumb {

    public $src;                            //图片原始地址
    public $old_path;                       //图片原始在服务器中的路径
    public $thumb_src;                      //缩略图SRC
    public $mime_type;                      //图片MIME类型
    public $thumb_path;                     //缩略图保存路径
    public $new_height;                     //缩略图高度,px
    public $new_width;                      //缩略图宽度,px
    public $thumb_name;                     //缩略图命名规则
    public $quality;                        //缩略图质量

    /*
     * $src图片URL
     */

    public function __construct($src) {       
        $this->src = $src;
        if (!$this->isValidSrc()) {
            return;
        }
        // clean params before use
        $this->clean_source();
        // get mime type of src              
        $img = common::getPathAndThumbnameBySrc($src);
        if (!file_exists($img['old_path'])) {
            return;
        }
        
        $this->old_path = $img['old_path'];

        if (($this->mime_type = $this->mime_type()) === false) {
            return false;
        }
        $this->new_width = sfConfig::get('app_thumb_width');
        $this->quality = sfConfig::get('app_thumb_quality');
        $this->thumb_name = $img['thumb_name'];
        $this->thumb_path = $img['thumb_path'];
        $this->thumb_src = $img['src'];
        if (!file_exists($this->thumb_path . $this->thumb_name)) {
            fclose(fopen($this->thumb_path . $this->thumb_name, 'w'));  //写入一个空白文件，防止高并发时同时生成过多的文件
            $this->createNewThumb();
        }
    }

    /*
     * 是否是一个合法的图片URL
     */

    function isValidSrc() {
        if ($this->src == '' || strlen($this->src) <= 3 || !preg_match('/^http.*[jpg|jpeg|gif|png]/i', $this->src)) {
            return false;
        }
        return true;
    }

    /*
     * 是否是一个合法的图片URL
     */

    function createNewThumb() {
        $image = $this->open_image($this->mime_type, $this->old_path);
        if ($image === false) {
            return false;
        }
        // Get original width and height        
        $width = imagesx($image);
        $height = imagesy($image);
        if ($this->new_width > $width) {
            $this->new_width = $width;
            $this->new_height = $height;
        } else {
            $this->new_height = floor($height * ($this->new_width / $width));
        }
        $canvas = imagecreatetruecolor($this->new_width, $this->new_height);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $this->new_width, $this->new_height, $width, $height);
        if (!$this->generateThumb($this->mime_type, $canvas)) {
            unlink($this->thumb_path . $this->thumb_name);
        } else {
            chmod($this->thumb_path . $this->thumb_name, 0777);
        }
        imagedestroy($canvas);
    }

    /**
     * @param <type> $mime_type
     * @param <type> $src
     * @return <type>
     */
    function open_image($mime_type, $src) {
        if (strpos($mime_type, 'jpeg') !== false) {
            $image = imagecreatefromjpeg($src);
        } elseif (strpos($mime_type, 'png') !== false) {
            $image = imagecreatefrompng($src);
        } elseif (strpos($mime_type, 'gif') !== false) {
            $image = imagecreatefromgif($src);
        }
        return $image;
    }

    /**
     *
     */
    function generateThumb($mime_type, $img) {
        $filename = $this->thumb_path . $this->thumb_name;
        header('Content-type: ' . $mime_type);
        if (strpos($mime_type, 'jpeg') > 1) {
            return imagejpeg($img, $filename, $this->quality);
        } elseif (strpos($mime_type, 'gif') > 1) {
            return imagegif($img, $filename);
        } else {
            return imagepng($img, $filename, floor(($this->quality - 1 ) / 10));
        }
    }

    /**
     * determine the file mime type
     *
     * @return <type>
     */
    function mime_type() {
        $file_infos = getimagesize($this->old_path);
        $mime_type = $file_infos['mime'];
        // no mime type
        if (empty($mime_type) || !preg_match("/jpg|jpeg|gif|png/i", $mime_type)) {
            return false;
        }
        return strtolower($mime_type);
    }

    /**
     * tidy up the image source url
     *
     * @param <type> $src
     * @return string
     */
    function clean_source() {
        $host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
        $regex = "/^(http(s|):\/\/)(www\.|)" . $host . "\//i";
        $this->src = preg_replace($regex, '', $this->src);
        $this->src = strip_tags($this->src);
    }

}
