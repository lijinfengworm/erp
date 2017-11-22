<?php

class hupuGenerateErrorTask extends sfBaseTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'hupu'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      //new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'hupu';
    $this->name             = 'generate-error';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [hupuGenerateError|INFO] task does things.
Call it with:

  [php symfony hupuGenerateError|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
     $contextInstance = sfContext::createInstance($this->configuration);
     
     $request = sfContext::getInstance()->getRequest();
     $request->setRelativeUrlRoot('/');
     
     $page404 = $contextInstance->getController()->getPresentationFor('unavailable', '404');
     $mes404 = 'get 404 failed';
     if($page404)
     {
         $mes404 = 'get 404 success!';
         $put404 = file_put_contents(sfConfig::get('sf_web_dir') ."/error/404.html", $page404); 
     } 
     $p404 = 'generate 404 failed!';
     if(isset($put404) && $put404)
     {
         $p404 = 'generate 404 success!';
     }
     echo $mes404."\n";
     echo $p404."\n";
     
     
     
     $page500 = $contextInstance->getController()->getPresentationFor('unavailable', '500');
     $mes500 = 'get 500 failed';
     if($page500)
     {
         $mes500 = 'get 500 success';
         $put500 = file_put_contents(sfConfig::get('sf_web_dir') ."/error/500.html", $page500);
     }
     $p500 = 'generate 500 failed!';
     if(isset($put500) && $put500)
     {
         $p500 = 'generate 500 success!';
     }
     echo $mes500."\n";
     echo $p500."\n";
     
     
     $page502 = $contextInstance->getController()->getPresentationFor('unavailable', '502');
     $mes502 = 'get 502 failed';
     if($page502)
     {
         $mes502 = 'get 502 success';
         $put502 = file_put_contents(sfConfig::get('sf_web_dir') ."/error/502.html", $page502);
     }
     if(isset($put502) && $put502)
     {
         $p502 = 'generate 502 success!';
     }
     echo $mes502."\n";
     echo $p502."\n";
  }
}
