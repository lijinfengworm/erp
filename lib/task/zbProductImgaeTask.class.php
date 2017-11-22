<?php

class zbProductImageTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'zhuangbei'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'zhuangbei'),
      // add your own options here
    ));

    $this->namespace        = 'zhuangbei';
    $this->name             = 'zbProductImage';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [zbProductPoint|INFO] task does things.
Call it with:

  [php symfony zbProductPoint|INFO]
EOF;
  }
 
  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    
    $pids = ZbProductsTable::getDisplayProduct(Doctrine::getTable('ZbProducts')->createQuery('p')->select('p.id'));
    foreach ($pids as $val)
    {  
    
        $img = $val->getImgSrc();
        
        $this->log('old:'.$img);
        $img = strtolower(sfConfig::get('sf_upload_dir') .'/zhuangbei/products/' . $img);
        
        $yuan = substr_replace($img, "_yuan.jpg", strripos($img, "."));
        
        if(!is_file($yuan))
        {
            copy($img, $yuan);
            $this->log('copy big:');
        }
        
        
        $smallPath  = substr_replace($img, "_160.jpg", strripos($img, "."));
        try {
        //填充背景
        $color=new ImagickPixel();
        $color->setColor("rgb(255,255,255)");
        
        //处理大图
        $i = new Imagick($yuan);
        $yuan_width = $i->getImageWidth();
        $yuan_height = $i->getImageHeight();
        
        $i->borderImage($color,0,0);
        if($yuan_width / $yuan_height > 370/340)
        {
            $i->resizeImage  (370, 0, imagick::FILTER_LANCZOS,true);
        }else{
            $i->resizeImage  (0, 340, imagick::FILTER_LANCZOS,true);
        }
        
        $i->setinterlacescheme(Imagick::INTERLACE_PLANE);
        $i->setimageformat('jpeg');
        
        $new_img = substr_replace($img, ".jpg", strripos($img, "."));
        
        
        $i->writeImages($new_img, true);   
        if($new_img != $img)
        {
            
            $new_img = str_replace(  sfConfig::get('sf_upload_dir') .'/zhuangbei/products/' , '', $new_img );
            $new_img = implode('/', array_filter(explode('/', $new_img)));
            $this->log('new:'.$new_img);
            $val->setImgSrc($new_img);
            $val->save();
        }
        //处理小图
        $i = new Imagick($yuan);
        $i->borderImage($color,0,0);
        if($yuan_width / $yuan_height > 160/147)
        {
            $i->resizeImage  (160, 0, imagick::FILTER_LANCZOS,true);
        }else{
            $i->resizeImage  (0, 147, imagick::FILTER_LANCZOS,true);
        }
        
        $i->setinterlacescheme(Imagick::INTERLACE_PLANE);
        $i->setimageformat('jpeg');
        $i->writeImages($smallPath, true); 
        
        
        
        } catch (Exception $e){
            $this->log('fail: '.$val->getID().' '.$yuan);
        }

        $this->log('success: '.$val->getID().' '.$yuan);        
    }
  }
}
