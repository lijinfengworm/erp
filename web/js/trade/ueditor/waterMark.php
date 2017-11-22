<?php

/**
 * Created by PhpStorm.
 * User: worm
 * Date: 5/19/16
 * Time: 11:59 PM
 */
class WaterMark
{
    public $text, $color, $size, $font, $angle, $px, $py, $im;
    //要添加的文字
    public function GetWpText($text)
    {
        $this->text = $text;
    }
    //添加文字的颜色
    public function GetFtColor($color)
    {
        $this->color = $color;
    }
    //添加文字的字体
    public function GetFtType($font)
    {
        $this->font = $font;
    }

    //添加文字的大小
    public function GetFtSize($size)
    {
        $this->size = $size;
    }
    //文字旋转的角度
    public function GetTtAngle($angle)
    {
        $this->angle = $angle;
    }
    //添加文字的位置
    public function GetTtPosit()
    {
    //echo 'here'.strlen($this->text);
        $this->px = imagesx($this->im) - strlen($this->text)*10;
        $this->py = imagesy($this->im) - 20;
    }
    static function getImageInfo($img) {
        $imageInfo = getimagesize($img);
        if ($imageInfo !== false) {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
            $imageSize = filesize($img);
            $info = array(
                "width" => $imageInfo[0],
                "height" => $imageInfo[1],
                "type" => $imageType,
                "size" => $imageSize,
                "mime" => $imageInfo['mime']
            );
            return $info;
        } else {
            return false;
        }
    }
    //添加文字水印
    public function AddWpText($pict)
    {
        //$ext = exif_imagetype($pict);
        $sInfo = self::getImageInfo($pict);
        switch ($sInfo['type']) {
            case 1:
                $picext = "gif";
                $this->im = imagecreatefromgif($pict);
                break;
            case "jpeg":
                $picext = "jpeg";
                $this->im = imagecreatefromjpeg($pict);
                break;
            case 3:
                $picext = "png";
                $this->im = imagecreatefrompng($pict);
                break;
            default:
                $this->Errmsg("不支持的文件格式！");
                break;
        }

        //$this->picext = $picext;
        $this->GetTtPosit();
        $im   = $this->im;
        $size = $this->size;
        $angle= $this->angle;
        $px   = $this->px;
        $py   = $this->py;
        $color= $this->color;
        $font = $this->font;
        $text = $this->text;
        $color= imagecolorallocate($im, 255, 0, 0);
        //echo $picext.$im.'==='.$pict;
        //echo $size.' '.$angle.' '.px.' '.$py.' '.$color.' '.$font.' '.$text.'end';
        imagettftext($im, $size, $angle, $px, $py, $color, $font, $text);
        switch ($picext) {
            case "gif":
                imagegif($im, $pict);
                break;
            case "jpeg":
                imagejpeg($im, $pict, 100);
                break;
            case "png":
                imagealphablending($im, false);
                imagesavealpha($im, true);
                imagepng($im, $pict);
                break;
        }
        imagedestroy($im);
    }
    //错误信息提示
    public function Errmsg($msg)
    {
        echo "<script language='javascript'>alert('".$msg."');</script>";
    }
    //类结束
}