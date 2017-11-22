<?php

/*
 * 生成缩略图的类
 * 可指定宽、高、文件名、路径、质量
 */

class mythumb {

    public $source_src;                     //图片原始地址
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

    public function __construct($source_src, $new_name, $new_path, $width, $height, $quality = 50) {
        $this->source_src = $source_src;
        $this->new_width = $width;
        $this->new_height = $height;
        $this->quality = $quality;
        
        $this->thumb_name = $new_name;
        $this->thumb_path = $new_path;
        if (!$this->isValidSrc()) {
            return;
        }
        // clean params before use
        $this->clean_source();
        // get mime type of src
        if (($this->mime_type = $this->mime_type()) === false) {
            return false;
        }
       
        
        if(!file_exists($this->source_src)){
            return ;
        }

       //写入一个空白文件，防止高并发时同时生成过多的文件
       $this->createNewThumb();

    }
   
    /*
     * 是否是一个合法的图片URL
     */

    function isValidSrc() {
        if ($this->source_src == '' || strlen($this->source_src) <= 3 || (!preg_match('/^http.*[jpg|jpeg|gif|png]/i', $this->source_src) && !preg_match('/(.*)\/([^\/]*)$/', $this->source_src) )) {
            return false;
        }
        return true;
    }

    /*
     * 是否是一个合法的图片URL
     */

    function createNewThumb() {
        $image = $this->open_image($this->mime_type, $this->source_src);
        if ($image === false) {
            return false;
        }
        // Get original width and height        
        $width = imagesx($image);
        $height = imagesy($image);
        $canvas = imagecreatetruecolor($this->new_width, $this->new_height);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $this->new_width, $this->new_height, $width, $height);
        if (!$this->generateThumb($this->mime_type, $canvas)) {
            chmod($src, 0777);
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
        $file_infos = getimagesize($this->source_src);
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
        $this->source_src = preg_replace($regex, '', $this->source_src);
        $this->source_src = strip_tags($this->source_src);
    }
}