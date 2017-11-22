
<div>
    <?php $num = 0; foreach($donenum as $k=>$v):  ?>
    <span><?php echo $k; ?></span>   =============
    <span><?php   $num+=$v; echo $v; ?></span>
        </br>
        </br>
    <?php endforeach; ?>



    <br />总数： <?php  echo $num; ?>
</div>


<p class="welcome">黑五GO</p>
<script type="text/javascript" src="/js/tradeadmin/page/welcome.js"></script>