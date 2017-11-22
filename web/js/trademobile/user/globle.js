var myCouponAjax = {
        ajaxLink:'http://m.shihuo.cn/user/myCouponAjax',
        type:"now",
        nowPage:1,
        overPage:1,
        init:function(){
           this.getDom({
               page:this.nowPage
           });
           this.bindFun();
        },
        getDom:function(o){
            var that = this;
            $.post(that.ajaxLink,{type:that.type,page:o.page},function(data){
                if(data.status*1 == 0){
                    that.writeDom(data.data);
                    if(data.flag){
                        $(".loding-more").show();
                    }else{
                        $(".loding-more").hide();
                    }
                }else{
                    if((that.nowPage == 1 && that.type == "now") || (that.overPage == 1 && that.type == "over")){
                        $(".coupon-list,.loding-more").hide();
                        $("#hasCouponLabel").show();
                    }
                    $(".loding-more").hide();
                }
            },"json");
        },
        writeDom:function(data){
            var str = [],classD;
            for(var i=0; i<data.length; i++){
                var href = '';
                var imagePath = '';
                var go_href = '';
                //链接
                if(data[i].activity_id && data[i].root_type == 0) {
                    href = 'http://www.shihuo.cn/duihuan/' + data[i].activity_id + '.html';
                }

                if(data[i].root_type == 2) {
                    href = data[i].activity_id;
                } else {
                    href = 'http://www.shihuo.cn/haitao/daigou';
                }

                if(data[i].root_type == 1) {
                    go_href = href;
                }else {
                    go_href = 'javascript:void();';
                }

                //图片
                if(data[i].activity_id) {
                    imagePath = data[i].img_path;
                }else {
                    imagePath = '/images/trade/duihuan/card.jpg';
                }
                console.log(data[i].etime);
                str.push('<li>\
                        <div class="imgs">\
                            <img src="'+ imagePath +'"/>\
                        </div>\
                        <div class="txt">\
                            <div class="h2">'+data[i].new_title+'</div>\
                            <div class="date">有效日期：'+data[i].etime+'</div>\
                            <div class="use">\
                                <a href="' + go_href + '" class="btn-use' + (this.type == 'over'?' over-btn':'')+'" account="'+data[i].account+'" mart="'+data[i].mart+'" receive_url="'+data[i].receive_url+'?utm_source=ZMC_DtSzcmxsc">立即使用</a>\
                            </div>\
                        </div>\
                  </li>');
            }

            $(".coupon-list").append(str.join("")).show();
        },
        bindFun:function(){
            var that = this;
            $(".loding-more").click(function(){
                if(that.type == "now"){
                   that.nowPage++;
                   that.getDom({
                       page:that.nowPage
                   });
               }else{
                   that.overPage++;
                   that.getDom({
                       page:that.overPage
                   });
               }
            });

            $(".now-tab").click(function(){
                 $(".coupon-list").html("");
                 $("#hasCouponLabel").hide();
                 that.nowPage = 1;
                 $(this).addClass('on');
                 $(".over-tab").removeClass('on');
                 that.type = "now";
                 that.getDom({
                   page:that.nowPage
                 });
            });
            
            $(".over-tab").click(function(){
                $(".coupon-list").html("");
                $("#hasCouponLabel").hide();
                that.overPage = 1;
                $(this).addClass('on');
                 $(".now-tab").removeClass('on');
                 that.type = "over";
                 that.getDom({
                       page:that.overPage
                  });
            });

            $(".coupon-list .btn-use").live("click",function(){
                if($(this).attr('href') != 'javascript:void();') {
                     return;
                }
                 that._showMasks(0.6);
                 $(".copy-layer").find(".youhuiquan").html("券码:"+$(this).attr("account"));
                 $(".copy-layer").find(".shopname").html("去"+$(this).attr("mart")+"看看");
                 $(".copy-layer").find(".shopname").attr("href",$(this).attr("receive_url"));
                 $(".copy-layer").show()
                 $(".copy-layer").css({
                    left:that._position($(".copy-layer"))[0],
                    top:that._position($(".copy-layer"))[1]
                 });
            });

            $(".copy-layer .title-bg s").click(function(){
               $(".copy-layer").hide(); 
               that._closeMasks();
            });
        },
        _showMasks: function(a) {
            var str = "<div class='body-mask' style='position:absolute; top:0; left:0; width:100%; height:" + $(document).height() + "px; background-color:#000;  z-index:91;'></div>";
            $("body").append(str);
            $(".body-mask").css("opacity", 0);
            $(".body-mask").css({
                "opacity": a ? a : "0.8"
            });
        },
        _closeMasks: function() {
            var close = $(".body-mask");
            close.remove();
        },
        _getpageSize: function() {
            /*
             height:parseInt($(document).height()),
             width:parseInt($(document).width())
             */
            var de = document.documentElement, arrayPageSize,
                    w = window.innerWidth || self.innerWidth || (de && de.clientWidth) || document.body.clientWidth,
                    h = window.innerHeight || self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
            arrayPageSize = [w, h]
            return arrayPageSize;
        },
        _position: function(obj) {//计算对象放置在屏幕中间的值   obj:需要计算的对象
            var left = ((this._getpageSize()[0] - parseInt(obj.width())) / 2);
            var top = ((this._getpageSize()[1] - parseInt(obj.height())) / 2);
            return [left, top];
        },
        _getpageScroll: function() {
            var yScrolltop;
            if (self.pageYOffset) {
                yScrolltop = self.pageYOffset;
            } else if (document.documentElement && document.documentElement.scrollTop) {
                yScrolltop = document.documentElement.scrollTop;
            } else if (document.body) {
                yScrolltop = document.body.scrollTop;
            }
            return yScrolltop;
        }
}

    

var myCollect = {
    ajaxLink:'http://m.shihuo.cn/user/getUserColloectionAjax',
    type:"good",
    nowPage:1,
    overPage:1,
    init:function(){
       this.getDom({
           page:this.nowPage
       });
       this.bindFun();
    },
    getDom:function(o){
        var that = this;
        $.post(that.ajaxLink,{type:that.type,page:o.page},function(data){
            if(data.status*1 == 0){
                if(that.type == "good"){
                    that.writeDom(data.data.goods);
                    if(data.flag){
                        $(".loding-more").show();
                    }else{
                        $(".loding-more").hide();
                    }
                }else{
                    that.writeDom(data.data.shops);
                    if(data.flag){
                        $(".loding-more").show();
                    }else{
                        $(".loding-more").hide();
                    }
                }
            }else{
                if((that.nowPage == 1 && that.type == "good") || (that.overPage == 1 && that.type == "shop")){
                    $(".user-coupon-content ul").hide();
                    if(that.type == "good"){
                       $(".hasCouponLabel").html("您还没有收藏任何商品，快去收藏吧！").show();
                    }else{
                        $(".hasCouponLabel").html("您还没有收藏任何店铺，快去收藏吧！").show();
                    }
                       
                }
                $(".loding-more").hide();
            }
        },"json");
    },
    writeDom:function(data){
        if(this.type == "good"){
            var str = [];
            for(var i=0; i<data.length; i++){
                str.push('<li>\
                    <div class="display-flex">\
                        <div class="imgs"><a href="'+data[i].url+'"><img src="'+data[i].image+'" /></a></div>\
                        <div class="user-txt">\
                            <div class="h2"><a href="'+data[i].url+'">'+data[i].title+'</a></div>\
                            <div class="price-cancel clearfix">\
                                <div class="price">￥'+data[i].price+'</div>\
                                <div class="cancel" cid="'+data[i].cid+'">\
                                    <a href="javascript:void();" class="delete"><img src="/images/trademobile/user/icon1.png" width="18" /> 取消收藏</a>\
                                </div>\
                             </div>\
                        </div>\
                    </div> \
                </li>');
            }
            $(".user-coupon-content ul").append(str.join("")).show();
        }else{
            var str = [];
            for(var i=0; i<data.length; i++){
                str.push('<li>\
                <div class="display-flex">\
                    <div class="shop-name">\
                        <div class="name">店铺：'+data[i].name+'</div>\
                        <p>主要项目：'+data[i].business+'</p>\
                        <p>淘宝ID：'+data[i].owner_name+'</p>\
                    </div>\
                    <div class="shop-btn">\
                        <a href="'+data[i].url+'">去店铺</a>\
                    </div>\
                </div>\
                <div class="shop-cancel clearfix">\
                    <a class="delete" href="javascript:void(0);" cid="'+data[i].cid+'"><img src="/images/trademobile/user/icon1.png" width="18" /> 取消收藏</a>\
                </div>\
            </li>');
            }
            $(".user-coupon-content ul").append(str.join("")).show();
        }
    },
    bindFun:function(){
        var that = this;
        $(".user-coupon-tag .area").click(function(){
            $(".hasCouponLabel").hide();
            $(".user-coupon-content ul").html("");
            $(".user-coupon-tag .area a").removeClass('on');
            $(this).find("a").addClass('on');
            if($(this).index()*1 == 0){
                that.type = "good";
                that.nowPage = 1;
                that.getDom({
                   page:that.nowPage
                });
            }else{
                that.type = "shop";
                that.overPage = 1;
                that.getDom({
                   page:that.overPage
                });
            }
        });

        $(".loding-more").click(function(){
            if(that.type == "good"){
               that.nowPage++;
               that.getDom({
                   page:that.nowPage
               });
           }else{
               that.overPage++;
               that.getDom({
                   page:that.overPage
               });
           }
        });

        $(".cancel").live("click",function(){
           var $this = $(this);
           $.ui.confirm("确定取消收藏？",function(){
                  myCollect.goodsRemove($this.attr("cid"),$this.parents("li"));
            }); 
        });

        $(".shop-cancel .delete").live("click",function(){
             var $this = $(this);
           $.ui.confirm("确定取消收藏？",function(){
                  myCollect.shopsRemove($this.attr("cid"),$this.parents("li"));
            });
        });
    },
    goodsRemove:function(o,obj){
       $.post("http://m.shihuo.cn/user/deleteMyCollect",{id:o},function(data){
         if(data.status*1 == 0){
              obj.remove();
         }
       },"json");
    },
    shopsRemove:function(o,obj){
       $.post("http://m.shihuo.cn/user/deleteMyCollect",{id:o},function(data){
         if(data.status*1 == 0){
              if(data.status*1 == 0){
                  obj.remove();
             }
         }
       },"json");
    }
}