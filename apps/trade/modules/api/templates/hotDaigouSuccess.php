<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=8">
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>热门代购</title>
    <style>
        *{ padding: 0; margin: 0; font-family: "Microsoft Yahei"; }
        body {margin: 10px 5px; width: 100%; overflow: hidden;}
        .hot{ width:150px; margin:0 auto;}
        .hot .title-sub{width:150px;padding-left: 6px; font-family: Arial; font-size: 12px; color: #666;}
        .hot ul{padding:5px;width:140px;}
        .hot ul li{ list-style:none;padding: 5px 10px 5px 1px;width:58px;float:left;}
        .hot ul li .imgs{border:1px solid #eee;padding:0;}
        .hot ul li img{width:100%; display:block;}
        .hot ul li .icons{width:100%; height:20px;color:#666; white-space:nowrap; font-size: 11px; text-align: center;}
        .hot ul li .icons .i1{padding-top: 3px;}
    </style>
</head>
<body>
<div class="hot">
    <div class="title-sub">热门推荐</div>
    <ul>
        <?php if (!empty($products)):
            foreach ($products as $item): ?>
                <li class="" data-id="<?php echo $item['id'];?>">
                    <div class="imgs">
                        <a target="_blank" href="<?php echo $item['url'];?>">
                            <img src="<?php echo $item['img_path'];?>" alt="<?php echo $item['title'];?>" />
                        </a>
                    </div>
                    <div class="icons clearfix">
                        <div class="i1">¥ <?php echo $item['price'];?></div>
                        <i class="bg"></i>
                    </div>
                </li>
            <?php endforeach;
        endif;?>
    </ul>
</div>
</body>
</html>