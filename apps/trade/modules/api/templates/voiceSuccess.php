<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <?php include_http_metas() ?>
 <?php include_metas() ?>
 <link href="http://b3.hoopchina.com.cn/common/common.css" rel="stylesheet" type="text/css" />
 <script>var _common = _common || []; _common.project = 'nba';</script>
 <script type="text/javascript" src="http://b1.hoopchina.com.cn/common/jquery-1.6.js"></script>
 <?php include_title() ?>
</head>
<body style="background:rgb(254,255,240);">
<script type="text/javascript" src="http://www.shihuo.cn/js/trade/zbq_common.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="/css/trade/proIframe.css" />
<div class="shihuo-proIframe">
    <?php if($show_text) :?>
	<div class="hd">
		<h2>相关商品推荐</h2>
	</div>
	<div class="tag">
        <?php foreach($show_tags as $tag) : ?>
        <a data-track="voice-text-<?php echo $tag->getName(); ?>" target="_blank" href="<?php echo url_for("@tag_list?name=".urlencode($tag->getName())); ?>"><?php echo $tag->getName();?></a>
        <?php endforeach; ?>
	</div>
    <?php endif; ?>
	<div class="bd">	
	<div class="shihuo-proIframe-wrap">
		<ul id="shihuo-proIframe-ul" style="left: 0px;">
            <?php $i = 1; ?>
            <?php foreach($items as $item): ?>
            <?php 
                if($i == 1) {
                    $item_url = url_for("@tag_list?name=".urlencode($item->getTrdTags()->getName()) . "&track=from-voice-pic-" . urlencode($item->getTrdTags()->getName()));
                } else {
                    $item_url = $item->getTrdItemAll()->getLink() . "?track=from-voice-pic-" . urlencode($item->getTrdItemAll()->getName());
                }
                $i++;
            ?>
         <li> <div class="img"><p>
        <a data-track="voice-pic-<?php echo $item->getTrdItemAll()->getName(); ?>" href="<?php echo $item_url; ?>" target="_blank" title="<?php echo $item->getTrdItemAll()->getName(); ?>" >
            <img title="<?php echo $item->getTrdItemAll()->getName(); ?>" src="<?php echo $item->getTrdItemAll()->getImgBySize(300); ?>" ></a></p>
			</div>
            <a href="<?php echo $item->getTrdItemAll()->getLink(); ?>" target="_blank">
				 
					
				</a>
				<span class="bg"></span>		
                <span class="text"><span class="ms"><?php echo mb_substr($item->getTrdItemAll()->getName(), 0, 15, "utf8"); ?></span>
                <span class="xj">
                现价：￥<?php echo $item->getTrdItemAll()->getPrice(); ?></span>
			</li>
            <?php endforeach; ?>
		</ul>
	</div>
	<div class="shihuo-proIframe-num" id="shihuo-proIframe-num">
	
	</div>
	</div>
</div>

<script type="text/javascript">
var proMove = new ProMove({ul:"shihuo-proIframe-ul",num:"shihuo-proIframe-num",v:1,auto:true});
</script>
<script>var _common = _common || [];</script>
<script type='text/javascript' src='http://b3.hoopchina.com.cn/common/common.js'></script>
</body>
</html>
