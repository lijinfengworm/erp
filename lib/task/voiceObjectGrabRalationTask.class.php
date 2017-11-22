<?php
/**
 * 每过一段时间运行一次。 更新这个阶段内产生变化的 头条 对应的
 */
class voiceObjectGrabRalationTask extends sfBaseTask
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
    $this->name             = 'voiceObjectGrabRalation';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [voiceObjectGrabRalation|INFO] task does things.
Call it with:

  [php symfony voiceObjectGrabRalation|INFO]
EOF;
  }

    public function execute($arguments = array(), $options = array())
    {
		// initialize the database connection
		$databaseManager = new sfDatabaseManager($this->configuration);
		$connection = $databaseManager->getDatabase($options['connection'])->getConnection();
		sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star', $options['env'], true));
		
		//add your code here
		$tasks = voiceObjectGrabRalationTable::getTheGrabTask();
		
		if(count($tasks))
		{
			foreach($tasks as $task)
			{
				
				if(!empty($task) && $task['type'] == voiceObjectGrabRalationTable::TypeBBS)
				{
					$data = $this->getdatafrombbs($task['attr']);
					if(!empty($data))
					{
						$this->generatebbs($task,$data);	
					}
				}
				elseif(!empty($task) && $task['type'] == voiceObjectGrabRalationTable::TypeVideo)
				{
					$data = $this->getdatafromvideo($task['attr']);
					if(!empty($data))
					{
						$this->generatevideo($task,$data);	
					}
				}
				//更新这个任务的时间
				voiceObjectGrabRalationTable::finishOneGrabTask($task['id']);
			}
		}
		
    }
	public function getdatafrombbs($attr)
	{
		// 接口名称
		$apiname = 'getboardthreads';
		
		// 验证参数
		$appid = '90';
		$time = time();
		$key = '62c7c5ccd161d52';
		$sign = md5(md5($appid) . $time . $key);
		
		// 接口参数
		$attr = json_decode($attr,true);

		$arrays = array(
			'fid' => $attr['bbs_fid'],          //版块id
			'min' => $attr['bbs_min'],          //帖子回复数必须大于5
			'num' => 150,          //返回记录数
			'a' => 'getTodayThreads' //调用方法名
		);
		
		// 接口请求地址拼接
		$apiurl = 'http://interface.hoopchina.com/' . $apiname . '?appid=' . $appid . '&time=' . $time . '&sign=' . $sign . '&' . http_build_query($arrays);
		
		// 返回接口输出结果
		$result = file_get_contents($apiurl);
		$data = json_decode($result,true); 
		// 打印结果,json格式
		if($result && is_array($data) && !empty($data))
		{
			foreach($data as $key => $val)
			{
				$arr[$val['tid']] = $val; 	
			}
			
			return $arr;
		}
		else
		{
			return array();	
		}
			
	}
	
	public function getdatafromvideo($attr)
	{
		//频道选择
		$videoCategory_array = voiceObjectGrabRalationTable::$videoCategory;
		
		$attr = json_decode($attr,true);
		$url = $videoCategory_array[$attr['video_category']];
		$video_tag = $attr['video_tag'];
		
		//最后一次获取的视频id,即最大的那个，去重
		$video_id = voiceFrontPageTable::getLastTypeIdByType('voice_video_'.$attr['video_category']);

		$url .= '/index.php?m=interface&a=getVideoByTagsId&tag='.$video_tag.'&id='.$video_id;
		
		$result = file_get_contents($url);
		$data = json_decode($result,true);

		if($result && is_array($data) && !empty($data))
		{
			foreach($data as $key => $val)
			{
				$arr[$val['vid']] = $val;	
			}
			return $arr;	
		}
		else
		{
			return array();
		}
	}
	
	public function generatebbs($task,$data)
	{
		$type_ids = array_keys($data);
		$existsFrontPageMsgIds = voiceFrontPageTable::isExistsFrontPageByType('voice_bbs',$type_ids);
		
		//frontpage原有的，改变关系表
		if(count($existsFrontPageMsgIds))
		{
			//头条存在这些msg，但该些msg相关tag有更改操作，所以进行对象头条的关系调整
			$frontPageObjectInfos = voiceFrontPageTable::getFrontPageInfoByType('voice_bbs',$existsFrontPageMsgIds); //获取frontpage存在的这些msgid,属于哪些obj
			
			foreach($existsFrontPageMsgIds as $key => $val)
			{
				//头条对象关系调整
				$frontPageId = isset($frontPageObjectInfos[$val]) ? $frontPageObjectInfos[$val]['f_id'] : 0;

				if ($frontPageId)
				{
					$updateObjects[] = $task['voice_object_id'];
					$originalObjects = isset($frontPageObjectInfos[$val]) ? $frontPageObjectInfos[$val]['f_objects'] : array();
					$addObjects = array_diff($updateObjects, $originalObjects);
					if (count($addObjects))
					{//头条新增对象
						foreach ($addObjects as $object)
						{
							$voiceObjectFrontpage = new voiceObjectFrontPage();
							$voiceObjectFrontpage->setVoiceObjectId($object);
							$voiceObjectFrontpage->setVoiceFrontPageId($frontPageId);
							$voiceObjectFrontpage->save();
							$this->log('add one object about frontpage objectid:' . $object . '-frontpageId:' . $frontPageId);
						}
					}
				}			
			}

		}
		//frontpage没有的，完整步骤
		$newFrontPageMsgIds = array_diff($type_ids, $existsFrontPageMsgIds);

		if (count($newFrontPageMsgIds))
		{
			foreach($newFrontPageMsgIds as $key => $val)
			{
				/* tid,author,authorid,subject,postdate,url */
				//同步到头条
				$voiceFrontPage = new voiceFrontPage();
				
				$voiceFrontPage->setTitle($data[$val]['subject']);
				$voiceFrontPage->setLink($data[$val]['url']);
				$voiceFrontPage->setType('voice_bbs');
				$voiceFrontPage->setPublisherUid($data[$val]['authorid']);
				$voiceFrontPage->setPublisherName($data[$val]['author']);
				$voiceFrontPage->setTypeId($data[$val]['tid']);
				$voiceFrontPage->setSupport(1);
				$voiceFrontPage->set_frontpage_attr('attr_sync_comment', 1);
				$voiceFrontPage->save();
				$this->log('add one msg from bbs to frontpage msgid:' . $data[$val]['tid']);

				//头条支持操作记录
				$voiceFrontPageSupportAgaist = new voiceFrontPageSupportAgaist();
				$voiceFrontPageSupportAgaist->setUid($data[$val]['authorid']);
				$voiceFrontPageSupportAgaist->setFId((int) $voiceFrontPage->getId());
				$voiceFrontPageSupportAgaist->setSupportAgaist(1);
				$voiceFrontPageSupportAgaist->save();

				//同步到对象头条关系表中
				$frontPageId = $voiceFrontPage->getId();
				
				$voiceObjectFrontpage = new voiceObjectFrontPage();
				$voiceObjectFrontpage->setVoiceObjectId($task['voice_object_id']);
				$voiceObjectFrontpage->setVoiceFrontPageId($frontPageId);
				$voiceObjectFrontpage->save();
				$this->log('add one object from bbs about frontpage objectid:' . $task['voice_object_id'] . '-frontpageId:' . $frontPageId);

			}
		}
			
	
	}
	
	public function generatevideo($task,$data)
	{
		$type_ids = array_keys($data);
		
		$attr = json_decode($task['attr'],true);
		$type = 'voice_video_' . $attr['video_category'];
		
		$existsFrontPageMsgIds = voiceFrontPageTable::isExistsFrontPageByType($type,$type_ids);
		
		//frontpage原有的，改变关系表
		if(count($existsFrontPageMsgIds))
		{
			//头条存在这些msg，但该些msg相关tag有更改操作，所以进行对象头条的关系调整
			$frontPageObjectInfos = voiceFrontPageTable::getFrontPageInfoByType($type,$existsFrontPageMsgIds); //获取frontpage存在的这些msgid,属于哪些obj
			
			foreach($existsFrontPageMsgIds as $key => $val)
			{
				//头条对象关系调整
				$frontPageId = isset($frontPageObjectInfos[$val]) ? $frontPageObjectInfos[$val]['f_id'] : 0;

				if ($frontPageId)
				{
					$updateObjects[] = $task['voice_object_id'];
					$originalObjects = isset($frontPageObjectInfos[$val]) ? $frontPageObjectInfos[$val]['f_objects'] : array();
					$addObjects = array_diff($updateObjects, $originalObjects);
					if (count($addObjects))
					{//头条新增对象
						foreach ($addObjects as $object)
						{
							$voiceObjectFrontpage = new voiceObjectFrontPage();
							$voiceObjectFrontpage->setVoiceObjectId($object);
							$voiceObjectFrontpage->setVoiceFrontPageId($frontPageId);
							$voiceObjectFrontpage->save();
							$this->log('add one object about frontpage objectid:' . $object . '-frontpageId:' . $frontPageId);
						}
					}
				}			
			}

		}
		//frontpage没有的，完整步骤
		$newFrontPageMsgIds = array_diff($type_ids, $existsFrontPageMsgIds);

		if (count($newFrontPageMsgIds))
		{
			foreach($newFrontPageMsgIds as $key => $val)
			{
				//同步到头条
				$voiceFrontPage = new voiceFrontPage();
				
				$voiceFrontPage->setTitle($data[$val]['title']);
				//$voiceFrontPage->setLink($data[$val]['fromurl']);
				
				$videoCategory_array = voiceObjectGrabRalationTable::$videoCategory;
				$attr = json_decode($task['attr'],true);
				$url = $videoCategory_array[$attr['video_category']];
				$voiceFrontPage->setLink($url.'/v'.$data[$val]['vid'].'.html');

				$voiceFrontPage->setType($type);
				$voiceFrontPage->setPublisherUid($data[$val]['uid']);
				$voiceFrontPage->setPublisherName($data[$val]['author']);
				$voiceFrontPage->setTypeId($data[$val]['vid']);
				$voiceFrontPage->setSupport(1);
				$voiceFrontPage->set_frontpage_attr('attr_sync_comment', 1);
				$voiceFrontPage->save();
				$this->log('add one msg from bbs to frontpage msgid:' . $data[$val]['vid']);

				//头条支持操作记录
				$voiceFrontPageSupportAgaist = new voiceFrontPageSupportAgaist();
				$voiceFrontPageSupportAgaist->setUid($data[$val]['uid']);
				$voiceFrontPageSupportAgaist->setFId((int) $voiceFrontPage->getId());
				$voiceFrontPageSupportAgaist->setSupportAgaist(1);
				$voiceFrontPageSupportAgaist->save();

				//同步到对象头条关系表中
				$frontPageId = $voiceFrontPage->getId();
				
				$voiceObjectFrontpage = new voiceObjectFrontPage();
				$voiceObjectFrontpage->setVoiceObjectId($task['voice_object_id']);
				$voiceObjectFrontpage->setVoiceFrontPageId($frontPageId);
				$voiceObjectFrontpage->save();
				$this->log('add one object from video about frontpage objectid:' . $task['voice_object_id'] . '-frontpageId:' . $frontPageId);
			}
		}
			
	
	}

	
}
