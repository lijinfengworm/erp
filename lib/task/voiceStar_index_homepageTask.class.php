<?php

class voiceStar_index_homepageTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'star'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
                //new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = 'voice';
        $this->name = 'star_index_homepage';
        $this->briefDescription = 'generate homepage static file task';
        $this->detailedDescription = <<<EOF
The [voice:star_index_homepage|INFO] task does things.
Call it with:

  [php symfony voice:star_index_homepage|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
//    $databaseManager = new sfDatabaseManager($this->configuration);
//    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();


        sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star',  $options['env'], true));
        $this->memCache = sfContext::getInstance()->getDatabaseConnection('liangleMemcache');
        $request = sfContext::getInstance()->getRequest();
        $request->setRelativeUrlRoot('/');

        if ($this->getCacheInfo($this->name)) {
            $this->log('the homepage is still running !');
            return;
        }
        $this->setCacheInfo($this->name);
        $homepage = @file_get_contents('http://voice.hupu.com/index/homepage');

        if ($homepage) {
            echo 'get homepage information success !'. "\n";
            $homepageDir = sfConfig::get('sf_web_dir') . '/generated/voice';
            if(!file_exists($homepageDir)){
                mkdir($homepageDir, 0777, true);
            }
            if (file_exists($homepageDir . '/voice_homepage.html')) {
                $bstatus = copy($homepageDir . '/voice_homepage.html', $homepageDir . '/voice_homepage.backup.html');
                if ($bstatus) {
                    echo 'backup file success !' . "\n";
                } else {
                    echo 'backup file failed !' . "\n";
                }
            }
            file_put_contents($homepageDir . '/voice_homepage.html', $homepage);
        } else {
            echo 'get homepage information failed !'. "\n";
        }
        $this->setCacheInfo2($this->name);
    }

    private function getCacheInfo($key) {
        return $this->memCache->get($key);
    }

    private function setCacheInfo($key, $lifetime = 1800) {
        $this->memCache->set($key, 1, 0, $lifetime);
    }
    private function setCacheInfo2($key, $lifetime = 1800) {
        $this->memCache->set($key, 0, 0, $lifetime);
    }

}
