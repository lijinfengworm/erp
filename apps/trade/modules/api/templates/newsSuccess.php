<?php $page = $sf_data->getRaw('page');?>
<div style="width: 1380px; margin: 0 auto;">
    <div style="width: 460px;float: left;">
      <h2>优惠信息</h2>
        <?php  foreach($youhui as $k=>$v):?>
            <div>
            <a href="http://www.shihuo.cn/youhui/<?php echo $v['id']?>.html" target="_blank" title="<?php echo $v['title']?>"><?php echo $v['title']?></a>
            <span><?php echo $v['publish_date']?></span><br/>
            </div>
        <?php  endforeach;?>
    </div>
    <div style="width: 460px;float: left;">
        <h2>代购商品</h2>
        <?php  foreach($daigou as $k=>$v):?>
            <div>
                <a href="http://www.shihuo.cn/haitao/buy/<?php echo $v['id']?>.html" target="_blank" title="<?php echo $v['title']?>"><?php echo $v['title']?></a>
                <span><?php echo $v['publish_date']?></span><br/>
            </div>
        <?php  endforeach;?>
    </div>
    <div style="width: 460px;float: left;">
        <h2>发现好货</h2>
        <?php  foreach($find as $k=>$v):?>
            <div>
                <a href="http://www.shihuo.cn/detail/<?php echo $v['id']?>.html" target="_blank" title="<?php echo $v['title']?>"><?php echo $v['title']?></a>
                <span><?php echo date('Y-m-d',$v['publish_date'])?></span><br/>
            </div>
        <?php  endforeach;?>
    </div>
</div>
<div id="page" class="page" style="clear: both">
    <?php echo $page;?>
</div>
<style>

    #page {
        height: 200px;
        margin: 20px auto;
        text-align: center;

    }
    #page {
        font: 14px/16px arial;
    }
    #page span {
        float: left;
        margin: 0 3px;
    }
    #page a {
        border-radius: 5px;
        color: #222;
        margin: 0 3px;
        padding: 3px 7px;
        text-decoration: none;
    }
    #page a.now_page, #page a:hover {
        background: none repeat scroll 0 0 #91a8c9;
        color: #fff;
    }
</style>