<?php

class voiceHushiRssTask extends sfBaseTask
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
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'voice'),
      // add your own options here
    ));

    $this->namespace        = 'voice';
    $this->name             = 'voiceHushiRss';
    $this->briefDescription = '生成虎视xml';
    $this->detailedDescription = <<<EOF
The [voiceHushiRss|INFO] task does things.
Call it with:

  [php symfony voiceHushiRss|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $voiceSiteUrl = 'http://voice.hupu.com';
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    sfContext::createInstance($this->configuration)->getConfiguration()->loadHelpers('Url');
    
    //获取star的route
    $this->frontendRouting = new sfPatternRouting(new sfEventDispatcher());
    $config = new sfRoutingConfigHandler();
    $routes = $config->evaluate(array(sfConfig::get('sf_apps_dir').'/star/config/routing.yml'));
    $this->frontendRouting->setRoutes($routes);

    
    $rss = new UniversalFeedCreator();
    $rss->setEnCoding('utf-8');
    $rss->useCached();
    $rss->title = "虎视Rss订阅-虎扑新声";
    $rss->description = "汇集国内外知名体育专栏作家、记者观点及精华文章。";
    $feedImg = new FeedImage();
    $feedImg->title = "虎扑体育";
    $feedImg->url = "http://b3.hoopchina.com.cn/images/logo2013/v1/hp_logo_voice.png";
    $feedImg->link = $voiceSiteUrl.$this->frontendRouting->generate('hushi');
    $rss->image = $feedImg;
    $messages = voiceColumnMessageTable::getInstance()->getLimit(300);
    foreach ($messages as $message)
    {
        
        $url = $voiceSiteUrl.$this->frontendRouting->generate('hushi_contents', array('category'=>dataProcessFunc::getHuShiChannelName($message->getCategory()),'id'=>$message->getId()));

        $title = $message->getTitle();
        $content = $message->getFullText();
        $content = preg_replace('/(<img)(.+\/?>)/isU', '$1 width="300px" $2', $content);
        $origin = $message->getVoiceColumnAuthor()?'来源 :' .  $message->getVoiceColumnAuthor() . '':'';
        $content = $content."<br/><br/> [".$origin."]";
        $img = $message->getImgPath() ? $message->getCDNThumbnailImgUrl() : 'http://img04.store.sogou.com/net/a/46/link?appid=46&url=' . urlencode($message->getImgLink());
        if ($img) $content = "<img src ='".$img."' ><br />".$content;
        $content .=" <br/><br/> @hupu.com | 更多虎视专栏请访问 <a href=\"http://voice.hupu.com\" target=\"_blank\" >虎扑新声</a> | <a href=\"$url\" target=\"_blank\" >内页链接</a> . ".($message->getReplyCount()? ""."<a href=\"$url#comments\" target=\"_blank\" >". $message->getReplyCount() . "条评论</a> ."."":""."<a href=\"$url#comments\" target=\"_blank\" >评论</a> ."."")." <a href=\"http://weibo.com/hoopchina\" target=\"_blank\">新浪微薄</a> . <a href=\"http://user.qzone.qq.com/1624355655\" target=\"_blank\">QQ空间</a> . <a href=\"http://page.renren.com/699131720\" target=\"_blank\">人人公共主页</a>";
        $item = new FeedItem();
        $item->title = $title;
        $item->link = $url;
        $item->description = $content;
        $item->date = strtotime($message->getPublishDate() + date('Z'));
        $item->soure = $origin;
        $rss->addItem($item);
    }
    $rss->saveFeed('RSS2.0',  realpath('.').'/web/generated/voice/hushi.xml');
  }
}
