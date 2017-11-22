<?php

class voiceTopic_reply_countTask extends sfBaseTask
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
      new sfCommandOption('offset', null, sfCommandOption::PARAMETER_REQUIRED, 'The offset', 0),
      new sfCommandOption('limit', null, sfCommandOption::PARAMETER_REQUIRED, 'The limit', 0)
      // add your own options here
    ));

    $this->namespace        = 'voice';
    $this->name             = 'topic_reply_count';
    $this->briefDescription = '统计话题的回复数和亮回复数';
    $this->detailedDescription = <<<EOF
The [voice:topic_reply_count|INFO] task does things.
Call it with:

  [php symfony voice:topic_reply_count|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $query = twitterTopicTable::getInstance()->createQuery('t')
            ->select('t.id')            
            ->where('t.topic_type=?', twitterTopicTable::$NEWTOPICTYPE)
            ->offset($options['offset']);
    $query = $options['limit'] > 0 ? $query->limit($options['limit']) : $query;
    $topics = $query->execute();
    foreach($topics as $k => $topic){
        echo ($k+1) . '.更新话题: id='.$topic->getId() ."\n";
        $a = twitterReplyTable::getInstance()->createQuery('r')
                ->select('count(r.id) as reply_count')
                ->where('r.twitter_topic_id =?', $topic->getId())
                ->andWhere('r.is_delete = 0')
                ->fetchArray();
        $reply_count = (int) $a[0]['reply_count'];
        $b = twitterReplyTable::getInstance()->createQuery('r')
                ->select('count(r.id) as light_count')
                ->where('r.twitter_topic_id =?', $topic->getId())
                ->andWhere('r.is_delete = 0')
                ->andWhere('r.light_count >= 5')
                ->fetchArray();
        $light_count = (int) $b[0]['light_count'];
        $topic->setReplyCount($reply_count);
        $topic->setLightCount($light_count);
        $topic->save();
    }
    echo '完成';
    
    // add your code here
  }
}
