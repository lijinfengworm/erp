<?php

class voiceVoiceTagDeleteTask extends sfBaseTask
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
    $this->name             = 'voiceTagDelete';
    $this->briefDescription = '删除twitter数为零的tag';
    $this->detailedDescription = <<<EOF
The [voice:voiceTagDelete|INFO] task does things.
Call it with:

  [php symfony voice:voiceTagDelete|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $query = 'select vc.id from 
        (select v.id, count(t.id) as num from voiceTags v left join voiceTagTwitterMessages vt on v.id = vt.voice_tag_id left join twitterMessages t on t.id = vt.twitter_message_id group by v.id) vc 
        WHERE vc.num=0';

    $rows = $connection->query($query, PDO::FETCH_ASSOC);

    foreach ( $rows as $row) {
       $user = twitterUserTable::getInstance()->findBy('voice_tag_id', $row['id']);

       if (count($user))
           continue;

       $query = 'delete from voiceTags where id=' . $row['id'];
       
       try {
           $connection->query($query);
       } catch (Exception $e){
           continue;
       }
    }
    
    $this->log('task over!');
    // add your code here
  }
}
