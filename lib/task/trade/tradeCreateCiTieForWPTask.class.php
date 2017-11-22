<?php

class tradeCreateCiTieForWPTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            //new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', '1'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'CreateCiTieForWP';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:CreateCiTieForWP|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);   
    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        $info = TrdNewsTable::getInstance()->createQuery()->where('is_delete = ?',0)->orderBy('publish_date desc')->limit(1)->fetchOne();
        if ($info){
            $upload_dir = sfConfig::get('sf_upload_dir').'/trade/news';
            $img_path = $info->getImgPath();
            $img_path_sui = explode('trade/news',$img_path);
            if (isset($img_path_sui[1])){
                $img_path_sui480 = str_replace('thumbnail','thumbnail480',$img_path_sui[1]);
                if (is_file($upload_dir.$img_path_sui480)){//480的图片存在才进行操作
                    $resize_path256 = $this->imageResize256($img_path);
                    $bot_png = $this->gd_pic($info->getSubtitle());
                    $target  = dirname(__FILE__).'/../../../web/images/trade/white_336x336.png'; //背景图片
                    $name = dirname(__FILE__).'/../../../web/uploads/trade/news/shihuocitieforwp336*336.jpg';

                    $target_img = imagecreatefrompng ($target);
                    $resourse1 = imagecreatefrompng($resize_path256);
                    $size1 = getimagesize($resize_path256);
                    $dst_x = intval((336 - $size1[0])/2);
                    imagecopymerge($target_img, $resourse1, $dst_x, 0, 0, 0, $size1[0], $size1[1],100);
                    $resourse2 = imagecreatefrompng($bot_png);
                    $size2 = getimagesize($bot_png);
                    imagecopymerge($target_img, $resourse2, 0, 256, 0, 0, $size2[0], $size2[1],100);
                    Imagejpeg($target_img, $name);
                    
                    //生成691*336的大图片
                    $resize_path336 = $this->imageResize336($img_path);
                    $bot_png_336 = $this->gd_pic_big($info->getTitle(),$info->getSubtitle());
                    $target_336  = dirname(__FILE__).'/../../../web/images/trade/white_691x336.png'; //背景图片
                    $name_336 = dirname(__FILE__).'/../../../web/uploads/trade/news/shihuocitieforwp691*336.jpg';

                    $target_img_336 = imagecreatefrompng ($target_336);
                    $resourse1_336 = imagecreatefrompng($resize_path336);
                    imagecopymerge($target_img_336, $resourse1_336, 0, 0, 0, 0, 299, 336,100);
                    $resourse2_336 = imagecreatefrompng($bot_png_336);
                    $size2_336 = getimagesize($bot_png_336);
                    imagecopymerge($target_img_336, $resourse2_336, 299, 0, 0, 0, $size2_336[0], $size2_336[1],100);
                    Imagejpeg($target_img_336, $name_336);
                }
            }
        }
        exit;
  }
  
  //压缩图片
  private function imageResize256($target){
      if (!$target) return false;
      $target = str_replace('thumbnail','thumbnail480',$target);
      $name = dirname(__FILE__).'/../../../web/uploads/trade/news/shihuocitieforwp336*256.jpg';
      $target_img = imagecreatefromjpeg ($target);
      list( $width ,  $height ) =  getimagesize ( $target );
      $newwidth = intval(round(256*$width/$height));
      $thumb  =  imagecreatetruecolor ( $newwidth ,  256 );
      // Resize
      imagecopyresized ( $thumb ,  $target_img ,  0 ,  0 ,  0 ,  0 ,  $newwidth ,  256 ,  $width ,  $height );
      // Output
      imagepng($thumb, $name);
      return $name;
  }
  
  //压缩图片
  private function imageResize336($target){
      if (!$target) return false;
      $target = str_replace('thumbnail','thumbnail480',$target);
      $name = dirname(__FILE__).'/../../../web/uploads/trade/news/shihuocitieforwp299*336.jpg';
      $target_img = imagecreatefromjpeg ($target);
      list( $width ,  $height ) =  getimagesize ( $target );
      $thumb  =  imagecreatetruecolor ( 299 ,  336 );
      // Resize
      imagecopyresized ( $thumb ,  $target_img ,  0 ,  0 ,  0 ,  0 ,  299 ,  336 ,  $width ,  $height );
      // Output
      imagepng($thumb, $name);
      return $name;
  }
  
  //生成图片的功能 stxihei.ttf
   private function gd_pic($text){
        $bg = imagecreate (336, 80); // 创建画布
        $background_color  =  imagecolorallocate($bg, 255, 255, 255);

        $white = imagecolorallocate($bg, 255, 0, 0); // 创建白色
        $mid_o =  mb_substr($text,0,14,'utf-8');
        $text = mb_convert_encoding($mid_o, "html-entities","utf-8" );
        imagettftext($bg, 22, 0, 10, 45, $white, dirname(__FILE__).'/../../../web/fonts/hkhbw5.ttf', $text);
        $fname= dirname(__FILE__).'/../../../web/uploads/trade/news/shihuocitieforwp336*80.png';
        imagepng($bg,$fname);
        imagedestroy($bg);
        return $fname;
    }
    
    //生成图片的功能 stxihei.ttf
private function gd_pic_big($title,$subtitle){
	
	$bg = imagecreate (392, 336); // 创建画布
	$background_color  =  imagecolorallocate($bg, 168, 0, 6);

	$white = imagecolorallocate($bg, 255, 255, 255); // 创建白色
	$mid_o =  mb_substr($title,0,11,'utf-8');
	$mid_c =  mb_substr($title,11,11,'utf-8');
	$mid_o = mb_convert_encoding($mid_o, "html-entities","utf-8" );
        $mid_c = mb_convert_encoding($mid_c, "html-entities","utf-8" );
	imagettftext($bg, 26, 0, 15, 80, $white, dirname(__FILE__).'/../../../web/fonts/hkhbw5.ttf', $mid_o);
	if ($mid_c){
		imagettftext($bg, 26, 0, 15, 120, $white, dirname(__FILE__).'/../../../web/fonts/hkhbw5.ttf', $mid_c);
	}

	$submid_o =  mb_substr($subtitle,0,12,'utf-8');
	$submid_c =  mb_substr($subtitle,12,12,'utf-8');
	$submid_o = mb_convert_encoding($submid_o, "html-entities","utf-8" );
        $submid_c = mb_convert_encoding($submid_c, "html-entities","utf-8" );
	imagettftext($bg, 24, 0, 15, 200, $white, dirname(__FILE__).'/../../../web/fonts/hkhbw5.ttf', $submid_o);
	if ($mid_c){
		imagettftext($bg, 24, 0, 15, 240, $white, dirname(__FILE__).'/../../../web/fonts/hkhbw5.ttf', $submid_c);
	}
	
	$fname= dirname(__FILE__).'/../../../web/uploads/trade/news/shihuocitieforwp392*336.png';
	imagepng($bg,$fname);
	imagedestroy($bg);
	return $fname;
}


}
