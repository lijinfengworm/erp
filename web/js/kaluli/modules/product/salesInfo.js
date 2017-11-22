/**
 * Created by PhpStorm.
 * User: songxiaoqiang
 * DATA: 2015/12/21
 */
define(function(){
   "use strict";
    var sales = {
        defaults:{
            pages:1
        },
        ajaxBefor:false,
        ajaxLoading:false,
        init:function(){
            this.ajaxHandle();
            this.bindFun();
        },
        template:function(data){
          return   '<tr>\
                        <td>'+data.username+'</td>\
                        <td>'+data.attr+'</td>\
                        <td>'+data.num+'</td>\
                        <td>'+data.created_time+'</td>\
                    </tr>';
        },
        appendPages:function(){
            var allPage = this.allPage,
                str = '<a class="pre" href="#sales">&lt; 上一页</a>';
            if(allPage == 1){
                $(".sales-pages").hide();
                return false;
            }else{
                $(".sales-table,.sales-pages").show();
            }

            if(allPage < 6 || this.defaults.pages<6){
                if(allPage < 6){
                    for(var i=0; i<allPage; i++){
                        str+='<a class="a1'+(i==(this.defaults.pages-1)?" on":"")+'" href="#sales">'+(i+1)+'</a>';
                    }
                }else{
                    for(var i=0; i<6; i++){
                        str+='<a class="a1'+(i==(this.defaults.pages-1)?" on":"")+'" href="#sales">'+(i+1)+'</a>';
                    }
                }

            }else{
                for(var i=0; i<2; i++){
                    str+='<a class="a1" href="#sales">'+(i+1)+'</a>';
                }
                str+='<span>...</span>';

                for(var i=0; i<4; i++){
                    if(this.defaults.pages-2+i > allPage){
                        break;
                    }
                    str+='<a class="a1'+(this.defaults.pages==(this.defaults.pages-2+i)?" on":"")+'" href="#sales">'+(this.defaults.pages-2+i)+'</a>';
                }
            }
            str+='<a class="next" href="#sales">下一页 &gt;</a>';
            $(".sales-pages").html(str);
            if(this.defaults.pages > 1){
                $(".sales-pages .pre").css({
                    cursor:"pointer"
                });
            }
            if(this.defaults.pages == allPage){
                $(".sales-pages .next").css({
                    cursor:"auto"
                });
            }
        },
        ajaxHandle:function(){
            var t = this;
            if(!t.ajaxLoading){
                $.post('//www.kaluli.com/item/ajaxSales',{page:this.defaults.pages,product_id:product_id},function(res){
                    var data = "string" !== typeof res ? res : $.parseJSON(res);
                    if(data.status){
                        t.allPage = data.msg.num;
                        var dom= [];
                        for(var i=0;i<data.msg.res.length;i++){
                            dom.push(t.template(data.msg.res[i]));
                        }
                        $("#sales_table tbody").html(dom.join(''));
                        t.appendPages();
                    }
                });
            }
        },
        bindFun:function(){
            var t = this,that=this;
            $(".sales-pages .a1").live("click",function(){
                var tr = $(this).html();
                that.defaults.pages = tr;
                that.ajaxHandle();
                $(window).scrollTop($(".switchbox[data-index=1]").offset().top);
            });

            $(".sales-pages .pre").live("click",function(){
                var tr = that.defaults.pages*1-1;
                if(tr > 0){
                    that.defaults.pages = tr;
                    that.ajaxHandle();
                    $(window).scrollTop($(".switchbox[data-index=1]").offset().top);
                }
            });

            $(".sales-pages .next").live("click",function(){
                var tr = that.defaults.pages*1+1;
                if(tr <= that.allPage){
                    that.defaults.pages = tr;
                    that.ajaxHandle();
                    $(window).scrollTop($(".switchbox[data-index=1]").offset().top);
                }
            });
        }
    };

    return sales
});