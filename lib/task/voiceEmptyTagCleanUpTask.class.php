<?php
/**
 * 每过一段时间运行一次。 更新这个阶段内产生变化的 头条 对应的
 */
class voiceEmptyTagCleanUpTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','star'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name','voice'),
      // add your own options here
    ));

    $this->namespace        = 'voice';
    $this->name             = 'voiceEmptyTagCleanUp';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [voiceEmptyTagCleanUp|INFO] task does things.
Call it with:

  [php symfony voiceEmptyTagCleanUp|INFO]
EOF;
  }

    public function execute($arguments = array(), $options = array())
    {
		// initialize the database connection
		$databaseManager = new sfDatabaseManager($this->configuration);
		$connection = $databaseManager->getDatabase($options['connection'])->getConnection();
		sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star', $options['env'], true));
		
		//add your code here
		
		//get all tagid from voiceTags
		$Alltags = voiceTagTable::getAllTagIds();
		
		//get all voice_tag_id from voiceTagTwitterMessages
		$Messagetags = voiceTagTwitterMessageTable::getAllTagIdsWithMsg();
		
		
		if(empty($Alltags) || empty($Messagetags))
		{return;}
		//tagid with out msg
		$tagidWithOutMsg = array_diff($Alltags,$Messagetags);
		
		
		if(empty($tagidWithOutMsg))
		{return;}
		//排除这个表里相关联的外键关系
		$tagidWithOutTwitterUser = twitterUserTable::getObjByVoiceTagId($tagidWithOutMsg);


		if(empty($tagidWithOutTwitterUser))
		{return;}

		//排除外键关联
		$tagidWithOutTopicGroup = twitterTopicGroupTable::getObjByVoiceTagId($tagidWithOutTwitterUser);
		
		if(empty($tagidWithOutTopicGroup))
		{return;}
		//
		voiceTagTable::deleteTagByIds($tagidWithOutTopicGroup);
    }

}
