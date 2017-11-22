$(function(){
    submitSearch.init();
});


var submitSearch = {
    ulist:-1,
    init:function(){
        this.bindFun();
    },
    bindFun:function(){
        var searchSleepSecond = 1000,
            searchStatus = true,
            searchData = new Object(),
            that = this;

        $('#submit_nav').keydown(function(e){
            if(e.keyCode == 38){
                if(that.ulist > 0 && !$(".shihuo-nav-shsug").is(":hidden")){
                    that.ulist--;
                    that.writeFun();
                }
                return false;
            }
            if(e.keyCode == 40 && !$(".shihuo-nav-shsug").is(":hidden")){
                if(that.ulist < $("#shihuo-nav-shsug-ul li").length-1){
                    that.ulist++;
                    that.writeFun();
                }
                return false;
            }
        })

        $('#submit_nav').keyup(function(e){
            if(e.keyCode == 38 || e.keyCode == 40 ){
                return false;
            }

            if(searchStatus && e.keyCode != 13){
                searchStatus = false;
                setTimeout(function(){
                    searchStatus = true;
                } , this.searchSleepSecond);

                var searchContent = $(this).val();
                var searchUrl = 'http://www.shihuo.cn/api/s';

                if(!searchContent){
                    $(".shihuo-nav-shsug").hide();
                    return false;
                }

                if(typeof searchData[searchContent] == 'undefined'){
                    searchContent = searchContent.replace(/\'/ig,"*");
                    $.post(searchUrl,{keywords :searchContent},function(msg){
                        if(msg.status){
                            searchData[searchContent] = msg.data;
                            var str='';
                            for(var i= 0; i < msg.data.length; i++){
                                str += '<li class="shsug-overflow" data-key="'+msg.data[i]+'">'+msg.data[i]+'</li>';
                            }

                            $('.shihuo-nav-shsug ul').html(str);
                            $(".shihuo-nav-shsug").show();
                        }
                    },'json')
                }else{
                    var str='';
                    for(var i= 0; i < searchData[searchContent].length; i++){
                        str += '<li class="shsug-overflow" data-key="'+searchData[searchContent][i]+'">'+searchData[searchContent][i]+'</li>';
                    }

                    if(str == ''){
                        $(".shihuo-nav-shsug").hide();
                    }else{
                        $(".shihuo-nav-shsug").show();
                        $('.shihuo-nav-shsug ul').html(str);
                    }
                }

               // console.log(searchData);
            }
        });

        $("#shihuo-nav-shsug-ul li").live('mousemove',function(data){
             $(this).addClass('on');
        });

        $("#shihuo-nav-shsug-ul li").live('mouseout',function(data){
             $(this).removeClass('on');
        });
    },
    writeFun:function(){
        $("#shihuo-nav-shsug-ul li").removeClass('on');
        $("#shihuo-nav-shsug-ul li").eq(this.ulist).addClass('on');
        $("#submit_nav").val($("#shihuo-nav-shsug-ul li").eq(this.ulist).html());
    }
}


var second = 0,loadingA,isCancel = false;
function loadingAnimate(){  
    second++;                       
    $(".loadingbar i").animate({width:Math.round(second*20)+"px"},300);                          
}
!(function($){
   var url = 'http://www.shihuo.cn/search'; 
   var JPlaceHolder = {
      _check : function(){
          return 'placeholder' in document.createElement('input');
      },
      init : function(){
          if(!this._check()){
              this.fix();
          }                          
      },
      fix : function(){
          jQuery('.logos-box input[placeholder]').each(function(index, element) {
              var self = $(this), txt = self.attr('placeholder');
              self.wrap($('<div></div>').css({position:'relative', zoom:'1', border:'none', background:'none', padding:'none', margin:'none'}));                              
              var pos = self.position(), h = self.outerHeight(true)-1, paddingleft = self.css('padding-left');
              var holder = $('<span></span>').text(txt).css({position:'absolute', left:pos.left, top:pos.top, height:h, "line-height":h+"px", paddingLeft:paddingleft, color:'#aaa',display:"none"}).appendTo(self.parent());                              
              $(this).css({"height":h,"line-height":h+"px"});
              self.focusin(function(e) {
                  holder.hide();
              }).focusout(function(e) {
                  if(!self.val()){
                      holder.show();
                  }
              });
              if(!self.val()){
                  holder.show();
              }
              holder.click(function(e) {
                  holder.hide();
                  self.focus();
              });
          });
      }
   };


   var seach_layer = {
     lodingJson:false,
     init:function(){
         this.bindFun();
         this.cancelSearch();
     },
     bindFun:function(){
        var that = this,
        shsug_overflow = $('.shsug-overflow'),
        submit_nav = $("#submit_nav");
        submit = $("#seach_sub")
        submit_nav.keyup(function(event){
            var value = submit_nav.val();                              
            var seachKeywords = value.replace(/\'/ig,"*");
            if (event.keyCode == 13 ) {  //判断是否单击的enter按键(回车键)                                  
                  
                if(that.isUrl(value)){
                    that.doyijianGou(value);
                    return false
                }else if(seachKeywords){
                   url= url+'?keywords='+seachKeywords;
                }                                  
                location.href = url;
                url = 'http://www.shihuo.cn/search';
                return false;
            }
        });
        submit.click(function(){
            var value = submit_nav.val();                              
            if(that.isUrl(value)){
               that.doyijianGou(value);
               return false
            }
            var seachKeywords = value.replace(/\'/ig,"*");
            if(value){
                url= url+'?keywords='+seachKeywords;
            }

            location.href = url;
            url = 'http://www.shihuo.cn/search';
        });

         shsug_overflow.live('click',function(){
             var value = $(this).html();
             if(that.isUrl(value)){
                 that.doyijianGou(value);
                 return false
             }
             var seachKeywords = value.replace(/\'/ig,"*");
             if(value){
                 url= url+'?keywords='+seachKeywords;
             }

             location.href = url;
             url = 'http://www.shihuo.cn/search';
         })
     },
     isUrl:function(val){
        var httpReg = new RegExp(/^https?:\/\/(([a-zA-Z0-9_-])+(\.)?)*(:\d+)?(\/((\.)?(\?)?=?&?[a-zA-Z0-9_-](\?)?)*)*$/i);
        var test = httpReg.test(val) || val.indexOf("http") >= 0  || val.indexOf("www.amazon.com") >= 0 || val.indexOf("www.6pm.com") >= 0 ? true : false;
        return test;
     },
     doyijianGou:function(val){
        var that = this;
        if($.trim(val) != "" && $.trim(val) != "输入海外商品链接，点击搜索即可直接通过识货购买"){
              if(that.lodingJson){
                  return false;
               }
               that.lodingJson = true;
              $(".loading,#cancelBtn").fadeIn();                                
              loadingA = setInterval("loadingAnimate()",500);
              $.ajax({
                 type: "POST",
                 url: "http://www.shihuo.cn/haitao/purchase",
                 data: "url="+encodeURIComponent(val),
                 dataType:"json",
                 success: function(data){
                   clearInterval(loadingA);
                   if(!isCancel){
                      $(".loadingbar i").css("max-width","auto").animate({width:"100%"},300,function(){
                        if(data.status*1 == 0){     
                          location.href = data.data.buy_url;
                          that.lodingJson = false;
                        }else{
                          $(".fade-bg1,.tips-bg1").show();
                          setTimeout(function(){
                             $(".fade-bg1,.tips-bg1").hide();
                             that.lodingJson = false;
                          },3000);
                          second = 0;
                          $(".loadingbar i").css({"max-width":"318px","width":"0px"});
                        }
                        $(".loading,#cancelBtn").hide();
                      });                                              
                    }
                    isCancel = false;                                                                                                                                                                                     
                 }
              });
         }  
     },
     cancelSearch:function(){
        var that = this;
        $("#cancelBtn").click(function(){
           isCancel = true;
           clearInterval(loadingA);     
           that.lodingJson = false;
           second = 0;
           $(".loading,#cancelBtn,.fade-bg1,.tips-bg1").hide();                             
           $(".loadingbar i").css({"max-width":"318px","width":"0px"});
        })
     }                       
   };
   seach_layer.init();
   JPlaceHolder.init();
})(jQuery);