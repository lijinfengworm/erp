<?php

class updateObjectCountInfoTask extends sfBaseTask
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

        $this->namespace        = 'zy';
        $this->name             = 'update_object_count_info';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
更新阵营的统计信息
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);    
        $databaseManager = new sfDatabaseManager($this->configuration);

        $objectTable            = ZyObjectTable::getInstance();
        $messageMentionTable    = ZyMentionTable::getInstance();
        $voiceMentionTable      = ZyVoiceMentionTable::getInstance();
        $replyTable             = ZyReplyTable::getInstance();
        $objects                = $objectTable->getAll();

        foreach($objects as $object) {
            $new_count  = $messageMentionTable->getNewCountByObjectId($object->getId());
            $message_visit_count  = $messageMentionTable->getNewVisitCountByObjectId($object->getId());
            $voice_visit_count  = $voiceMentionTable->getNewVisitCountByObjectId($object->getId());
            $visit_count = $message_visit_count + $voice_visit_count;
            $voice_count  = $voiceMentionTable->getNewCountByObjectId($object->getId());
            //$join_count = $replyTable->getJoinCountByObjectId($object->getId());
            $object->setNewCount($new_count);
            //字段暂时借用一下,囧
            $object->setJoinCount($visit_count);
            $object->setVoiceCount($voice_count);
            $object->save();
        }

    }
}
