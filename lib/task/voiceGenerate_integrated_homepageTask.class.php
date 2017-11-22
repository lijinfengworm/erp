<?php

class voiceGenerate_integrated_homepageTask extends sfBaseTask
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
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'voice'),
      // add your own options here
    ));

    $this->namespace        = 'voice';
    $this->name             = 'generate_integrated_homepage';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [voice:generate_integrated_homepage|INFO] task does things.
Call it with:

  [php symfony voice:generate_integrated_homepage|INFO]
EOF;
  }

   protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star', $options['env'], true));
        $this->runStatus = sfContext::getInstance()->getDatabaseConnection('liangleMemcache');

        if (!$this->getRunningStatus()) {
            $this->log('start to generate the integrated homepage!');
            $this->setRunningStatus();
            $page = @file_get_contents('http://voice.hupu.com/index/integratedHomepage');
            if (!$page || strpos($page, '服务器出错了') !== false) {
                $this->log('Occurs an error when try to get the page contents');
            } else {
                $this->log('Get page success');
                $homepage_dir = sfConfig::get('sf_web_dir') . '/generated/voice';
                if (!file_exists($homepage_dir)) {
                    mkdir($homepage_dir, 0777, true);
                }
                $file_name = 'voice_integrated_homepage.html';
                if (file_exists($homepage_dir . '/' . $file_name)) {
                    if (copy($homepage_dir . '/' . $file_name, $homepage_dir . '/voice_integrated_homepage.backup.html')) {
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
            
            $this->delRunningStatus();
      } else {
          $this->log('The task is still running!');
      }
  }
  
    private function getRunningStatus() {
        return $this->runStatus->get($this->name);
    }

    private function setRunningStatus() {
        $this->runStatus->set($this->name, TRUE, 600);
    }

    private function delRunningStatus() {
        $this->runStatus->delete($this->name);
    }
}
