
$(function() {

    var API = {
        "platform": "http://www.shihuo.cn/api/1111/myPlatformStatus",
        "rankInfo": "http://www.shihuo.cn/api/1111/userRankInfo",
        "shareReport": "http://www.shihuo.cn/api/1111/postShareingStatus",
        "shuffle": "http://www.shihuo.cn/api/1111/generateRandomGoodsInfo",
        "getCard": "http://www.shihuo.cn/api/1111/postMyChoice",
        "getRestCard": "http://www.shihuo.cn/api/1111/restRandomGoodsInfo",
        "restSix": "http://www.shihuo.cn/api/1111/restRandomGoodsInfo",
        "getCategoryGoods": "http://www.shihuo.cn/api/1111/classifiedRandomGoodsInfo",
        "queryCategoryGoods": "http://www.shihuo.cn/shihuo/checkcvs?keyword=",
        "shareReport": "http://www.shihuo.cn/api/1111/postShareingStatus"
    }


    /**
     * 用户信息和操作
     * @constructor
     */
    var UserController = function UserController() {

        //负责事件监听注册
        function init() {
            $('#js-11-login').on('click', function() {
                if(!checkLogin()) {
                    commonLogin();
                }
            });
        }
        //TODO: 检查用户是否登录
        function checkLogin() {
            if($.trim($('#uid').val()) != "") {
              return true;
            } else {
              return false;
            }
        }
        //检测是否是对应模版
        function checkTemp(temp)
        {
            if($.trim($('#temp').val()) == temp) {
                return true;
            } else {
                return false;
            }
        }
        //获取用户使用平台
        function getPlatform(getPlatformCallback) {
            $.ajax({
                url: API.platform,
                type: 'get',
                dataType: 'json',
            }).done(getPlatformCallback)
        }

        init();

        return {
            checkLogin: checkLogin,
            getPlatform: getPlatform,
            checkTemp: checkTemp
        }
    };


    var UIController = function UIController() {
        function init() {
            navMenu();
            timeSlider();
        }

        //时间预告
        function timeSlider() {
            //slide
            var prevBtn = $('#js-slider-prev'),
                nextBtn = $('#js-slider-next'),
                WIDTH = 984,
                index = $('#slide-index').val(),
                slide = $('#js-slide'),
                slideLen = slide.children().length;

            function changeSlide() {
                slide.animate({'left': (-index * WIDTH) + 'px'});
            }

            prevBtn.on('click', function() {
                if(index == 0) {
                    return;
                } else {
                    index--;
                    changeSlide();
                }
            });

            nextBtn.on('click', function() {
                if(index >= slideLen -1 ) {
                   return;
                } else {
                    index++;
                    changeSlide();
                }
            });
        }

        //导航按钮
        function navMenu() {
            var rowHeight = $('.front img')[0].offsetHeight;
            $('.col-4').height(rowHeight);

            var menus = $('.menus img');
            menus.each(function(index, item) {
                $(item).hover(function() {
                    item.src = '../images/trade/1111/btn-' + $(item).data('id') + '-active.png?s=618';
                }, function() {
                    item.src = '../images/trade/1111/btn-' + $(item).data('id') + '.png?s=618';
                });
            });
        }

        init();

    }


    //翻牌
   var GameController = function GameController(userController) {
       var gameMain = $('.main'),
           cards = $('.flip-container'),
           cardsFront = $('.front img'),
           limit = 3,
           chanceLeftSpan = $('#js-chance-left'),
           chance = chanceLeftSpan.html() * 1, // 后端写入 机会数
           limitCounter = 1,
           chanceCounter = 0,
           gamePrice = $('#js-game-price'),
           modalPrice = $('#js-modal-price'),
           lowestPrice = $('#js-lowest-price'),
           modalRank = $('#js-modal-rank'),
           playResetBtn = $('#js-play-reset'),
           overlay = $('#js-double11-overlay'),
           modal = $('#js-double11-modal'),
           modalClose = $('#js-double11-modal-close'),
           cardBackTpl = Handlebars.compile($('#card-back-tpl').html()),
           rankTable = $('#js-rank'),
           rankTpl = Handlebars.compile($('#rank-tpl').html());

       modalClose.on('click', function() {
           overlay.hide();
           modal.hide();
           chanceLeftSpan.html(chanceLeftSpan.html()*1 - 1);
           if(chanceLeftSpan.html()*1 == 0) {
             playResetBtn.find('img').attr('src', '../images/trade/1111/btn-playonemore-disabled.png?s=618');
           }
       })

       function init() {
          shuffle();
          cards.off('click');
       }

       function shuffle(cb) {
         $.ajax({
          url: API.shuffle,
          type: 'get',
          dataType: 'json',
          data: {}
         }).done(function(data) {
           cb && cb();
         });
       }

       //更新用户排名
       function updateUserRank(cb) {
         $.ajax({
           url: API.rankInfo,
           type: 'get',
           dataType: 'json',
           data: {}
         }).done(function(data){
           cb && cb(data);
         })
       }

       function updateUserRankCallback(data) {
        var totalRank = data.result.totalRank;
            data.result.top3 = {};

        for(var i = 0, len = totalRank.length; i < len; i++) {
          totalRank[i].index = i + 1;

          if(i % 2 == 1) {
            totalRank[i].cls = 'tr-strip';
          } else {
            totalRank[i].cls = "";
          }
        }

        data.result.top3 = totalRank.splice(0, 3);
        rankTable.html(rankTpl(data.result));
       }

       //检测是否登录，
       //检测是否还有机会
       cardsFront.on('click', function(){

           if(!userController.checkLogin()) {
               if(userController.checkTemp('bbs'))
               {
                   //未登录
                   $("#js-double11-modal-message-alert-title").html('登录帐号后才能玩，正在跳转到登录...');
                   $("#js-double11-modal-message-alert").show();
                   setTimeout(function(){
                       top.window.location.href="http://passport.hupu.com/login?jumpurl="+encodeURIComponent(document.referrer);
                   },1500);

               }else{
                   commonLogin();
               }
           } else {
               if (chanceCounter < chance && limitCounter <= limit) {
                   var flipper = $(this).parents('.flip-container'),
                       cardBack = flipper.find('.back');
                   clickCardCallback(flipper, cardBack, $(this));
               }
               if(chance == 0)
               {
                   if(userController.checkTemp('bbs'))
                   {
                       $("#js-double11-modal-message-alert-title").html('您的机会已经用完,<a href="http://www.shihuo.cn/1111/game#game" target="_blank">点击查看其他机会</a>');
                   }else{
                       $("#js-double11-modal-message-alert-title").html('您的机会已经用完，通过点击分享或者下载app增加机会吧');
                   }
                   $("#js-double11-modal-message-alert").show();
               }
           }

       })

       function getCard(reqData, cb) {
           $.ajax({
               url: API.getCard,
               type: 'post',
               dataType: 'json',
               data: reqData
           }).done(function(data) {
               cb && cb(data);
           })
       }

       function getRestCard(cb) {
          $.ajax({
             url: API.getRestCard,
               type: 'post',
               dataType: 'json',
               data: {}
          }).done(function(data) {
            cb && cb(data);
          })
       }

       function getRestCardCallback(data) {

          var flipperLeft = $('.flip-container').not('.hover'),
              leftIndex = 5;                   

          flipperLeft.off('click').on('click', function() {

             $(this).off('click');
             $(this).addClass('hover');

             var cardBack = $(this).find('.back');

             cardBack.html(cardBackTpl(data.result[leftIndex--]));

          })
       }


       function clickCardCallback(flipper, backCard, target) {
           var reqData = {
               'choiceStep': limitCounter,
               'choiceId': target.data('id')
           }

           //请求卡片回调
           getCard(reqData, function(data) {
               if(data.result == "用户未登录") {
                   commonLogin();
               } else if(data.result == "商品重复" || data.result == "游戏次数为0") {
                   alert(data.result);
                   return;
               } else if(data.status == "success") {
                   flipper.addClass('hover');

                   if(reqData.choiceStep == 3) {
                     backCard.html(cardBackTpl(data.result.goodsInfo));
                   } else {
                     backCard.html(cardBackTpl(data.result));
                   }
                   
                   // 翻完3张牌，更新价格总 和 剩余机会
                   if(limitCounter == limit) {
                       setTimeout(function() {

                           if(userController.checkTemp('bbs'))
                           {
                               $("#js-double11-modal-message-alert-title").html('您的最低价格:'+data.result.myGameScore+'&nbsp;'+'排名:'+data.result.myRank+'<br/> <a style="color: dodgerblue" href="http://www.shihuo.cn/1111/game#game" target="_blank">再来一局</a>  <a style="color: darkred" href="http://www.shihuo.cn/1111/game#game" target="_blank">查看奖品</a>' );
                               $("#js-double11-modal-message-alert").show();
                               return;
                           }


                           overlay.show();
                           modal.show();

                           //弹出层 价格总和，排名
                           modalPrice.html(data.result.myGameScore);
                           modalRank.html(data.result.myRank);


                           var lowPrice = $.trim(lowestPrice.html()),
                               gameScore = data.result.myGameScore; 

                           //更新用户最低价格总和
                           if(lowPrice == "/" || lowPrice*1 >  gameScore*1) {
                             lowestPrice.html(gameScore);
                           }
                           

                           //更新最右边价格总和
                           gamePrice.html(gameScore);

                           //更新用户排名
                           updateUserRank(updateUserRankCallback);

                           //请求剩余六张
                           getRestCard(getRestCardCallback);

                       }, 200);
                   } 
                       
                  limitCounter++;
               }else{
                   alert(data.result);
               }

           });

       }
       function setCookie(name,value,days) {
           if (days) {
               var date = new Date();
               date.setTime(date.getTime()+(days*24*60*60*1000));
               var expires = "; expires="+date.toGMTString();
           }
           else var expires = "";
           document.cookie = name+"="+value+expires+"; path=/";
       }
       function getCookie(cname) {
           var name = cname + "=";
           var ca = document.cookie.split(';');
           for(var i=0; i<ca.length; i++) {
               var c = ca[i];
               while (c.charAt(0)==' ') c = c.substring(1);
               if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
           }
           return "";
       }

       function playResetBtnCallback() {

           if(!getCookie("s_target"))
           {
               setTimeout(function(){
                   setCookie('s_target',1,0.2)
                   window.location.href="http://s.click.taobao.com/t?e=m%3D2%26s%3DsRZgE%2BSpTVkcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMSo6N0hYKmzF5x%2BIUlGKNpVGtvS2t8YXqo2WrrQZ4Yz3wI9sZMY6IBVaNReY2WHpxqUuZxIcp9pf42w0P75TBmwBg%2BEL7%2BBoTwZn%2By0keumoIQRIr6Q%2B5%2FB%2BPGXoAieI2tfEJZuu2RKIjk3u7s1M%2BenEXzm7ZfO5Mg%3D%3D";
               },3000);
               return true;
           }

           if($('.flip-container.hover').length >= 3) {
             chanceCounter++;

             if(chanceCounter == chance) {
                 playResetBtn.find('img').attr('src', '../images/trade/1111/btn-playonemore-disabled.png?s=618');
                 playResetBtn.off('click');
                 return;
             } 

             $('#js-game-price').html("0.00");

             shuffle(function() {
                cards.removeClass('hover');
                limitCounter = 1;
             });
          }
           return false;
       }
       playResetBtn.on('click', playResetBtnCallback);

       //分享提示
       var shareAddChance = $('#js-share-add-chance'),
           appAddChance = $('#js-app-add-chance'),
           shareAddChanceTip = $('#js-chance-share'),
           appAddChanceTip = $('#js-qr-app-chance');

       //5
       shareAddChance.on('click', function(){
          shareAddChanceTip.show();
       });
       $('#js-pc-share').on('click', function() {
          if($(this).hasClass('cursor')) {
              shareAddChanceTip.show();
           }
       });
       $('#js-share').on('click', function() {
          if($(this).hasClass('cursor')) {
              shareAddChanceTip.show();
           }
       });

       //8
       appAddChance.on('click', function() {
          appAddChanceTip.show();
       });
       $('#js-app-share').on('click', function() {
          if($(this).hasClass('cursor')) {
              appAddChanceTip.show();
           }
       });

       $('#js-rules-app-share').on('click', function() {
          appAddChanceTip.show();
          $('html, body').animate({scrollTop: $('#game').offset().top});
       });

       $(document).on('mouseup', function(e) {
           if(!shareAddChanceTip.is(e.target) && shareAddChanceTip.has(e.target).length === 0){
               shareAddChanceTip.hide();
           }

           if(!appAddChanceTip.is(e.target) && appAddChanceTip.has(e.target).length === 0){
               appAddChanceTip.hide();
           }
       });

       //分享功能
       function shareReport(cb) {
         $.ajax({
            url: API.shareReport,
            type: 'post',
            dataType: 'json',
            data: {}
         }).done(function(data){
            cb && cb(data);
         })
       }
       function shareReportCallback(data) {
          var chanceLeft = $('#js-chance-left');
          if(data.status == "success" && data.result.isDoubleShare === false) {
            //更新机会
            chanceLeftSpan.html(data.result.chanceLeft);
            chance = data.result.chanceLeft * 1;
            //再玩一次，会先++, 如果是0，就会多用一次机会，导致剩余1次机会，但是按钮灰掉了
            chanceCounter = -1;

            $('#js-play-reset img').attr('src', '../images/trade/1111/btn-playonemore.png?s=618');
            playResetBtn.off('click').on('click', playResetBtnCallback);

            //更换图片
            if(data.result.onAPP == true) {
              $('#js-app-share')
              //更换图片
              .attr('src', '../images/trade/1111/app-8-chance-active.png?s=618');
            }
            if(data.result.onPC == true) {
              $('#js-pc-share')
              .attr('src', '../images/trade/1111/pc-5-chance-active.png?s=618');
            }
            if(data.result.onSHARE == true) {
              $('#js-share')
              .attr('src', '../images/trade/1111/share-5-chance-active.png?s=618');
            }
          }
       }
       $('.chance-share img').on('click', function(){
          var type = $(this).data('type'),
              share_title = "#识货618#海淘优惠券发不停！拼人品的时候到了，翻翻牌就赢钱！ @识货 我已经赚钱了，你呢？！",
              share_img = "http://c1.hoopchina.com.cn/images/trade/1111/title2.png?s=618";

          $.shareAPI(type, {
            title: share_title,
            pic: share_img,
            url: 'http://www.shihuo.cn/api/gamePage',
            source: {}
          });

          shareReport(shareReportCallback);
       })
       

       init();
   }


    //商品分类展示
    var GalleryController = function GalleryController() {
      var tabs = $('.gallery-menus li'),
          galleryContent = $('#js-gallery-content'),
          galleryGoodsTpl = Handlebars.compile($('#category-goods-tpl').html());

          //组织数据
          function assembleGoodData(data) {

            var goodsData = {
              data: []
            },
                goodDataLength = data.length,
                rowIndex = -1;


            for(var i = 0; i < goodDataLength; i++) {
              if(i % 5 == 0) {
                rowIndex++;
                goodsData.data.push({
                  goods: [
                    data[i]
                  ]
                });
              } else {
                goodsData.data[rowIndex].goods.push(data[i]);
              }
            }

            return goodsData;
          }

          function getCategoryGoods(reqData, cb) {
            $.ajax({
              url: API.getCategoryGoods,
              type: 'post',
              dataType: 'json',
              data: reqData
            }).done(function(data) {
              cb && cb(data);
            });
          }

          function getCategoryGoodsCallBack(data) {
            //渲染数据
            galleryContent.html(galleryGoodsTpl(assembleGoodData(data)));
          }

          //单击tab请求商品信息
          tabs.on('click', function() {

              if($(this).hasClass('on')) {
                  return;
              } else {
                  var id = $(this).attr('data-id');
                  $(this).addClass('on');
                  $(this).siblings().removeClass('on')

                  getCategoryGoods(
                    {
                      cid: id
                    }, 
                    getCategoryGoodsCallBack
                  );
              }
          });

          function queryCategoryGoods(reqData, cb) {
            $.ajax({
              url: API.queryCategoryGoods,
              type: 'get',
              dataType: 'json',
              data: reqData
            }).done(function(data) {
              cb && cb(data);
            })
          }

          function queryCategoryGoodsCallback(data) {
            //渲染数据
            galleryContent.html(galleryGoodsTpl(assembleGoodData(data)));
          }

          var queryTrigger = $('#js-search-box-trigger');
          $('#js-search-box-input').on('input', function() {
            queryTrigger.attr('href', API.queryCategoryGoods + $(this).val());
          })
    }
    

    //初始化操作
    new UIController();

    var userController = new UserController();

    var gameController = new GameController(userController);

    GalleryController();
});

// 倒计时
var hour = $('#js-hour-left'),
    minute = $('#js-minute-left'),
    second = $('#js-second-left'),
    isOver = $('#isOver').val() * 1;

if(isOver == 0) {
    $("#js-timeleft-content").countdown($('#leftTime').val()*1000, function(event) {
        hour.text(
            event.offset.hours
        );
        minute.text(
            event.offset.minutes
        );
        second.text(
            event.offset.seconds
        )
    });
}





