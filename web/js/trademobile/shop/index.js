var page = 1;
var pageSize = 24;
var type = "";
$(function(){
    slide.init();
    shopListData.init();
    // tab切换
    $(".listShow .tit li").on("click",function(){
        $(".listShow .tit li").removeClass("on");
        $(this).addClass("on");
        type = $(this).attr("data-type");
        page = 1;
        shopListData.getData();
    });
    // 点击收藏  列表
    $("#shopList .sc").live("click",function(){ 
        var shopId = $(this).attr("data-shopid");
        if(shopId){
            shop_sC(shopId,this);
        }
    });
    // 点击收藏
    $(".oneSp .sc").live("click",function(){ 
        var shopId = $(this).attr("data-shopid");
        if(shopId){
            shop_sC(shopId,this);
        }
    });
    function shop_sC(shopId,id){
        var link = "http://www.shihuo.cn/user_colloection/add?id="+shopId+"&type=shop";
        $.ajax({
            url: link,
            type: 'GET',
            dataType: 'json',
            xhrFields:{
                withCredentials:true
            },
            crossDomain: true,
            success: function(data){
                if(data.status == 0){
                    $(id).addClass("on");
                    $.ajax({
                        url: "http://www.shihuo.cn/shop/addCollectCount?shop_id="+shopId,
                        type: 'GET',
                        dataType: 'json',
                        xhrFields:{
                            withCredentials:true
                        },
                        crossDomain: true,
                        success: function(){
                            
                        },
                        error: function(){
                            
                        }
                    });
                    return true;
                }else if(data.status == 1){
                    var url = window.location.href;
                    $.ui.tips("请先登录",function(){
                         location.href = $.ui.loginUrl();
                    });
                    return false;
                }else{
                    $.ui.tips(data.info);
                    return false;
                }
            },
            error: function(){
                $.ui.tips("网络异常,请稍候重试");
            }
        });
    }
});
var shopListData ={
    ajaxLink: "http://m.shihuo.cn/shop/getShops",
    init:function(){
       this._ajaxG = true;
       this.getData();
       this.ajaxScroll();
    },  
    ajaxScroll: function() {
        var that = this; //页面滚动
        $(window).scroll(function() {
            if ($(window).scrollTop() >= $(document).height() - $(window).height() && that._ajaxG ) {
                page +=1;
                $("#loadding").show();
                that.getData();
            }
        });
    },
    getData:function(){
        var that = this;
        if(type == 2 || type == 3){
            var link = this.ajaxLink+"?page="+page+"&pagesize="+pageSize+"&type="+type;
        }else{
            var link = this.ajaxLink+"?page="+page+"&pagesize="+pageSize;
        }
        that._ajaxG = false;
        $.get(link, function(data) {
             var _html = "";
             if(data.status == 0){
                $.each(data.data, function(index, val) {
                    var have ="",isconvert = "",flag='';
                    if(val.collect_flag == 1){
                         have ="on";
                    }
                    if(val.isTmall){
                        isconvert="isconvert='1' ";
                    }
                    if(val.flag==1){
                        flag='<div class="rz"></div>';
                    }

                    _html += '<li class="clearfix">\
                        <div class="imgs">\
                            <a href="'+val.link+'#qk=changgui&order='+(index+1)+'" '+isconvert+'><img src="'+val.logo+'" alt="" width="100%"></a>\
                        </div>\
                        <div class="fr">\
                            <a href="'+val.link+'#qk=changgui&order='+(index+1)+'" '+isconvert+'><p class="tit">'+val.name+'</p>\
                            <p class="desc">主营: '+val.business+'</p></a>\
                        </div><div class="sc '+have+'" data-shopid="'+val.id+'">\
                            <a href="javascript:void(0);"><span></span>收藏</a>\
                        </div>'+flag+'</li>';
                });
               if(page == 1){
                  $("#shopList").html(_html);
               }else{
                  $("#shopList").append(_html);
                  $("#loadding").hide();
               }
               if(data.data.length>0){
                  that._ajaxG = true;
               }
            }
         },"json");
    }
};
var slide =  {
    card: null,
    cacheSimilarData: [],
    showIndex: 1,
    helper: true,
    init: function() {
        this.renderList(1);
    },
    renderList: function(index) {
        var _self = this;
        var shopItems = _self.renderShopItem();
         _self.initShopCard(shopItems, index);
    },
    renderShopItem: function(data) {
       // var shopData = data.list;
        //create item element list
        var items = [];
        for (var i = 0, len = 5; i < len; i++) {
            var elem = document.createElement('li');
            elem.className = 'shop-wrapper';
            elem.setAttribute('data-id', i);
            elem.innerHTML = $("#oneSp"+i).html();
            items.push(elem);
        }
        return items;
    },
    initShopCard: function(cards, index, pageSize, callback) {
        var _self = this, 
        _index = +index - 1;
        var isAndroid = (/android/gi).test(navigator.appVersion)?"android":false;
        var card = new SwipeCard({
            container: '#shop-list',
            cards: cards,
            cardWidth: 280,
            cardHeight: 400,
            showIndex: _index,
            offsetY: 4,
            isAndroid: isAndroid
        });
        _self.updatePage(_index);
        _self.card = card;
        
        card.addEventListener("cardChange", function(e) {
            var cardShowIndex = card.showIndex;
            _self.showIndex = cardShowIndex;
            _self.updatePage(cardShowIndex);
        });
    },
    updatePage: function(n) {
        $('#page-current').html( n + 1);
    }
}
