    var frontImgs = $('.front img'),
        frontImgsLen = frontImgs.length,
        loadIndex = 0,
        backImgHeight = 0;

    frontImgs.on('load', function() {
        loadIndex++;
        if(loadIndex == frontImgsLen) {
            var rowHeight = frontImgs[0].offsetHeight;
            $('.col-4').height(rowHeight);
            $('.front').height(rowHeight);
            $('.flip-container').height(rowHeight);
            $('.flipper').height(rowHeight);
            backImgHeight = rowHeight * 0.513684;
        }
    });
    frontImgs.each(function(a,e){
        $(e).attr('src',$(e).attr('data-src'));
    })

    //翻牌
    //TODO: 用户登录后，获取用户所剩翻牌机会数量，判断
    var gameMain = $('.section-4'),
        cards = $('.flip-container'),
        limit = 3,
        chance = $('#chance').val() * 1, // 后端写入 机会数
        limitCounter = 1,
        chanceCounter = 0,
        playAgainBtn = $('#js-play-again'),
        confirmBtn = $('#js-confirm'),
        modalPrice = $("#js-price-sum"),
        modalChance = $('#js-chance-num'),
        overlay = $('#js-overlay'),
        modal = $('#js-modal'),
        cardBackTpl = Handlebars.compile($('#card-back-tpl').html()),
        myPrice = $('#js-my-price'),
        myRank = $('#js-my-rank'),
        myChance = $('#js-my-chance');

    function checkLogin() {
        if($.trim($('#uid').val()) != "") {
          return true;
        } else {
          return false;
        }
    }

    function shuffle(cb) {
         $.ajax({
          url: "http://www.shihuo.cn/api/1111/generateRandomGoodsInfo",
          type: 'get',
          dataType: 'json',
          data: {APPSource: 1}
         }).done(function(data) {
           cb && cb();
         });
       }

    shuffle();

    function getCard(reqData, cb) {
       $.ajax({
           url: "http://www.shihuo.cn/api/1111/postMyChoice",
           type: 'post',
           dataType: 'json',
           data: reqData
       }).done(function(data) {
           cb && cb(data);
       })
   }

   function getRestCard(cb) {
      $.ajax({
         url: "http://www.shihuo.cn/api/1111/restRandomGoodsInfo",
           type: 'post',
           dataType: 'json',
           data: {APPSource: 1}
      }).done(function(data) {
        cb && cb(data);
      })
   }

   function getRestCardCallback(data) {

      var flipperLeft = $('.flip-container').not('.hover'),
          leftIndex = 5;                   

      flipperLeft.on('click', function() {

         $(this).off('click');
         

         var cardBack = $(this).find('.back');

         cardBack.html(cardBackTpl(data.result[leftIndex--]));
         //$('.back img').css('height', backImgHeight);

         $(this).addClass('hover');
      })
   }

   function clickCardCallback(flipper, backCard, target) {
       var reqData = {
           'choiceStep': limitCounter,
           'choiceId': target.data('id'),
           'APPSource': 1
       }
       //请求卡片回调
       getCard(reqData, function(data) {

           if(data.result == "用户未登录") {
               return false;
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
               
               //$('.back img').css('height', backImgHeight);

               // 翻完3张牌，更新价格总 和 剩余机会
               if(limitCounter == limit) {
                   setTimeout(function() {

                       var lowPrice = $.trim(myPrice.html()),
                           gameScore = data.result.myGameScore,
                           gameChance = data.result.myChance;

                       //弹出层 价格总和，排名
                       modalPrice.html(gameScore);
                       modalChance.html(gameChance);
                       myChance.html(gameChance);
                       //0次机会
                       if(gameChance == 0) {
                           playAgainBtn.hide();
                       }
                        
                        overlay.show();
                        modal.show();

                       //更新用户最低价格总和
                       if(lowPrice == "/" || lowPrice*1 >  gameScore*1) {
                         myPrice.html(gameScore);
                       }
                       

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

    
   function frontTouchCB() {
        if(!checkLogin()) {
            return false;
        }
        if(chanceCounter == chance) {
            modalChance.text(0);
            modalPrice.text(0);
            playAgainBtn.hide();
            overlay.show();
            modal.show();
            return;
        }

        if(chanceCounter < chance) {

            if(limitCounter <= limit) {
                var flipper = $(this).parents('.flip-container'),
                    cardBack = flipper.find('.back');

                clickCardCallback(flipper, cardBack, $(this).find('img'));
                $(this).off('click');

            }
        }
   }

    $('.front').on('click', frontTouchCB);
    //重新开始
    playAgainBtn.on('click', function() {
        shuffle(function() {
            cards.removeClass('hover');
            limitCounter = 1;
            $('.front').off('click').on('click', frontTouchCB);
            modal.hide();
            overlay.hide();
        })
    });

    confirmBtn.on('click', function() {
        modal.hide();
        overlay.hide();
    });


//slide
var nextBtn = $('#js-slider-next'),
    sliderOffset = $('.slide-item')[0].offsetWidth,
    index = 0,
    slide = $('#js-slide'),
    slideLen = slide.children().length;

function changeSlide() {
    slide.animate({'left': (-index * sliderOffset) + 'px'});
}

nextBtn.on('click', function() {
    if(index >= slideLen -1 ) {
        index = 0;
        changeSlide();
    } else {
        index++;
        changeSlide();
    }
});

//modal
var modal = $('#js-modal'),
    overlay = $('#js-overlay'),
    playAgainBtn = $('#js-play-again'),
    confirmBtn = $('#js-confirm');

playAgainBtn.on('click', function() {
    modal.hide();
    overlay.hide();
});

confirmBtn.on('click', function() {
    modal.hide();
    overlay.hide();
});

//rank
var rankTable = $('#js-rank'),
    rankTpl = Handlebars.compile($('#rank-tpl').html());

//更新用户排名
function updateUserRank(cb) {
     $.ajax({
       url: 'http://www.shihuo.cn/api/1111/userRankInfo',
       type: 'get',
       dataType: 'json',
       data: {APPSource: 1}
     }).done(function(data){
       cb && cb(data);
     })
}

function updateUserRankCallback(data) {
    var totalRank = data.result.totalRank;
        data.result.top3 = {};

    //更新个人排名
    myRank.html(data.result.myRank);

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

 
