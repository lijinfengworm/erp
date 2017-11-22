<a href="http://www.shihuo.cn/haitao/cart">
<div id="cart-right-area">
<div class="img-png"><img src="http://www.shihuo.cn/images/trade/haitao/cart.png" /></div>
<div class="my-cart">我的购物车</div>
<div class="goods-num">{%$_User.cart_number|f_escape_xml%}</div></div>
</a>
<script type="text/javascript">
var isiPad = navigator.userAgent.match(/iPad/i) != null;
if(isiPad){
    $("#cart-right-area").css({
           right:10,
           marginRight:0
    });
}
</script>