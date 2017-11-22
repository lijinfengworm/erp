<?php

class tradeImgsTask extends sfBaseTask
{
    protected function configure()
    {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            // add your own options here
        ));

        $this->namespace        = 'trade';
        $this->name             = 'imgs';
        $this->briefDescription = '生成识货商品各种规格的缩略图';
        $this->detailedDescription = "";
    }

    protected function execute($arguments = array(), $options = array()) {
        sfContext::createInstance($this->configuration);    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $key = "unique_item_last_id";
        $lastId = $memcache->get($key);
        if(!$lastId) {
            $lastId = 0;
        }

        $itemAllTable = TrdItemAllTable::getInstance();
        $items = $itemAllTable->getItemsAll($lastId, 1000);

        foreach ($items as $item) {
            $file = str_replace(".jpg", "_300.jpg", sfConfig::get('app_img_dir_web') . $item->getImgUrl());

            if(!file_exists($file)) {
                continue;
            }

            $config_90 = array(
                "width" => 90,
                "height" => 90,
                "target" => str_replace("_300.jpg", "_90.jpg", $file)
            );

            $this->thumb_img_square($file, $config_90);

            $config_150 = array(
                "width" => 150,
                "height" => 150,
                "target" => str_replace("_300.jpg", "_150.jpg", $file)
            );

            $this->thumb_img_square($file, $config_150);

            $config_210 = array(
                "width" => 210,
                "height" => 210,
                "target" => str_replace("_300.jpg", "_210.jpg", $file)
            );

            $this->thumb_img_square($file, $config_210);

            $memcache->set($key, $item->getId(), 0, 3600);
            echo $item->getId() . "\n";
        }
    }

    public function thumb_img_square($file, $config) {
        $image = new Imagick($file);
        $sizes = $image->getImageGeometry();

        $w = $sizes["width"];
        $h = $sizes["height"];

        if ($w >= $h) {
            $limit = $h; 
            $left = ($w - $h) / 2;
            $image->cropImage($limit, $limit, $left, 0); 

        } else {
            $limit = $w; 
            $top = ($h - $w) / 2;
            $image->cropImage($limit, $limit, 0, $top);
        }   

        $image->thumbnailImage($config["width"], $config["height"]);
        $image->writeImages($config["target"], true);
    }
}
