<?php

class voiceRssTask extends sfBaseTask {

    protected function configure() {
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

        $this->namespace = 'voice';
        $this->name = 'rss';
        $this->briefDescription = 'Read rss to save news for voice';
        $this->detailedDescription = <<<EOF
The [voice:rss|INFO] task does things.
Call it with:

  [php symfony voice:rss|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star', $options['env'], true));
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $this->read();
    }
    
    private function read(){
        $rsses = voiceRssTable::getRsses();
        foreach($rsses as $rss){
            $tag = voiceTagTable::getTagByNameAndCategory('rss', $rss->getCategory());
            $class = $this->spiderObject($rss, $tag->getId());
            $class->work();
        }
    }
    
    private function spiderObject($rss, $tag_id){
        $url = parse_url($rss->getUrl());
        $host = $url['host'];
        $classes = array('sina', 'qq', 'sohu', '163');
        foreach($classes as $class){
            if(strpos($host, $class) !==false){
                $class_name = 'voice'.ucfirst($class).'RssSpider';
                if(class_exists($class_name)){
                    return new $class_name($rss, $tag_id);
                }                
            }
        }
        return new voiceRssSpider($rss, $tag_id);
    }

}
