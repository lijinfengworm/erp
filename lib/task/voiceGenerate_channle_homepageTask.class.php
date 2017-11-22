<?php

class voiceGenerate_channle_homepageTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'voice'),
            new sfCommandOption('channel', null, sfCommandOption::PARAMETER_REQUIRED, 'The channel name', 'all'),
                // add your own options here
        ));

        $this->namespace = 'voice';
        $this->name = 'generate_channle_homepage';
        
        $this->briefDescription = 'Generate channnel index page';
        $this->detailedDescription = <<<EOF
The [voice:generate_channle_homepage|INFO] task does things.
Call it with:

  [php symfony voice:generate_channle_homepage|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        
        $this->name = $this->name.'_'.$options['channel'];
        $this->log($this->name);
        sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star', $options['env'], true));
        $this->handle = sfContext::getInstance()->getDatabaseConnection('voiceRedis');

        if ($this->getTaskStatus()) {
            $this->log('The task is still running !');
            return;
        }
        $this->log('Start to generate pages');
        $this->setTaskStatus(1);
        
        if ($options['channel'] == 'all') {
            foreach (voiceSportCategory::getSportsWithChannelHomepage() as $slug) {
                $this->generateChannelHomepage($slug);
                sleep(1);
            }
        } else {
            $this->generateChannelHomepage($options['channel']);
        }
        
        $this->log('Task over');
        $this->setTaskStatus(0);
    }
    
    private function generateChannelHomepage($slug) {
        $this->log('Start to generate page for: ' . $slug);
        $page = @file_get_contents('http://voice.hupu.com/index/channel_homepage?c=' . $slug);
        if (!$page || strpos($page, '服务器出错了') !== false) {
            $this->log('The page you want has an error');
        } else {
            $this->log('Get page success');
            $homepage_dir = sfConfig::get('sf_web_dir') . '/generated/voice';
            if (!file_exists($homepage_dir)) {
                mkdir($homepage_dir, 0777, true);
            }
            $file_name = 'channel_index_' . $slug . '.html';
            if (file_exists($homepage_dir . '/' . $file_name)) {
                if (copy($homepage_dir . '/' . $file_name, $homepage_dir . '/channel_index_' . $slug . '.backup.html')) {
                    $this->log('Backup file success !');
                } else {
                    $this->log('Backup file failed !');
                }
            }
            if (file_put_contents($homepage_dir . '/' . $file_name, $page)) {
                $this->log('Generate page Success!');
            } else {
                $this->log('Generate page fail!');
            }
        }
    }
    
    private function getTaskStatus(){
        return $this->handle->get($this->name);
    }
    
    private function setTaskStatus($status){
        return $this->handle->setex($this->name, 120,$status);
    }

}
