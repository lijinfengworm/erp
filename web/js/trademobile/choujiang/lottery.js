$(function(){
	var Game = {};
	Game.stage2D = new LXY.Stage2D('lottery');
	Game.images = [];
	Game.imageSrcs = [GV.round_img, GV.pointer_img];
	Game.canPlay = true; //动画结束才可以进行下一次
	Game.stage2D.stop = true;
	Game.init = function(){
		//先清除画布
		Game.stage2D.context.clearRect(0, 0, Game.stage2D.stageWidth, Game.stage2D.stageHeight);
		Game.addBg();
		Game.addArrow();
		Game.arrowEvent();
		Game.stage2D.init();
	}

	Game.addBg = function(){
		Game.bg = new LXY.MovieClip2D();
		Game.bg.fps = 100;
		Game.bg.x = Game.images[0].width / 2;
		Game.bg.y = Game.images[0].height / 2;
  		Game.bg.speed = 20;

		Game.bg.draw = function(){
			this.drawImage(Game.images[0], -Game.images[0].width / 2, -Game.images[0].height / 2, Game.images[0].width, Game.images[0].height);
		}

		Game.stage2D.addChild(Game.bg);
	}

	Game.random = function(ran){
		var myDate=new Date();
		switch(parseInt(ran)){
			case 1 :
				Game.theRotate =  Math.floor(Math.random() * 20);
			break;
			case 2 :
				Game.theRotate =  Math.floor(Math.random() * 40) + 160;
			break;
			case 3 :
				if(myDate.getSeconds()%2){
	                Game.theRotate =  Math.floor(Math.random() * 40) + 250;
				}else{
					Game.theRotate =  Math.floor(Math.random() * 40) + 70;
				}
			break;
			case 4 :
				if(myDate.getSeconds()%2){
					Game.theRotate =  Math.floor(Math.random() * 40) + 25;
				}else{
					Game.theRotate =  Math.floor(Math.random() * 40) + 205;
				}
			break;
			case 5 :
				if(myDate.getSeconds()%2){
					Game.theRotate =  Math.floor(Math.random() * 40) + 115;
				}else{
					Game.theRotate =  Math.floor(Math.random() * 40) + 295;
				}
			break;
			default:
				Game.theRotate = 0;
			break;
		}
	}


	Game.addArrow = function(){
		Game.arrow = new LXY.MovieClip2D();
		Game.arrow.fps = 33;
		Game.arrow.x = Game.stage2D.stageWidth / 2;
		Game.arrow.y = Game.stage2D.stageHeight / 2;

		Game.arrow.draw = function(){
			this.drawImage(Game.images[1], -Game.images[1].width / 2, -Game.images[1].height / 2, Game.images[1].width, Game.images[1].height);
		}

		Game.stage2D.addChild(Game.arrow);
	}

	Game.arrowEvent = function(){
		Game.stage2D.canvas.addEventListener("touchstart", function(e){
			Game.x = (e.targetTouches[0].pageX - Game.stage2D.canvas.getBoundingClientRect().left) * 2;
			Game.y = (e.targetTouches[0].pageY - Game.stage2D.canvas.getBoundingClientRect().top) * 2;
			Game.move = false;
		});

		Game.stage2D.canvas.addEventListener("touchmove", function(e){
			Game.endX = (e.targetTouches[0].pageX - Game.stage2D.canvas.getBoundingClientRect().left) * 2;
			Game.endY = (e.targetTouches[0].pageY - Game.stage2D.canvas.getBoundingClientRect().top) * 2;

			if(Math.abs(Game.endX - Game.x) > 10 || Math.abs(Game.endY - Game.y) > 10){
				Game.move = true;
			}
		});



		Game.stage2D.canvas.addEventListener("touchend", function(e){
            if(Game.move || !Game.canPlay){
                return false;
            } 
            if(!GV.user_phone){
                $("#phone").focus();
                $.vui.remind("请先验证手机号再抽奖");
                return false;
            }

            if(GV.lottery_num == 0){
                $.vui.remind("您的抽奖次数已经用光了！");
                return false;
            }
            __dace.sendEvent('shihuo_choujiang_go_'+GV.lottery_id);
			var radiusX = Game.stage2D.stageWidth / 2,
				radiusY = Game.stage2D.stageHeight / 2,
				radius = Game.images[1].width / 2;
    			if(Math.pow((Game.x - radiusX), 2) + Math.pow((Game.y - radiusY), 2) > Math.pow(radius, 2)){
    				return false;
    			}


            $.ajax({
                type: 'POST',
                url: "http://m.shihuo.cn/choujiang/c",
                data: {
                    id:GV.lottery_id,
                    source:GV.source
                },
                dataType: 'json',
                success: function(data) {
                	if(data.status == "1"){
                        Game.canPlay = false;
                        GV.lottery_num = data.data.lottery_num;
                		GV.level = data.level;
                		GV.is_virtual = data.data.is_virtual;//是否虚拟奖品
                		GV.prize_name = data.data.prize_name;
                		GV.history_id = data.data.id;
                		GV.card       = data.data.card;
                        if(data.data.fail_msg) {
                            GV.fail_msg = data.data.fail_msg;
                        }
                        $('.zhongjiao_info').html(GV.level+'等奖：'+data.data.prize_name);
                	    Game.random(data.level);

                        if(Game.canPlay && !(parseInt(GV.level)>0)){
                            return false;
                        }
                        Game.bg.rotation = 0;
                        Game.bg.speed =10;
                        Game.bg.isPlay = true;
                        Game.stage2D.stop = false;
                        Game.bg.updateData = function(){
                            Game.bg.rotation += Game.bg.speed;

                            if(Game.bg.rotation > 1000){
                                Game.bg.speed = 8
                            }
                            if(Game.bg.rotation > 1300){
                                Game.bg.speed = 5
                            }
                            if(Game.bg.rotation > 1600){
                                Game.bg.speed = 3
                            }
                            if(Game.bg.rotation > 1798){
                                Game.bg.speed = 1
                            }
                            if(Game.bg.rotation >= 1800 + Game.theRotate){
                                Game.bg.rotation = 1800 + Game.theRotate;
                                Game.bg.speed = 0;
                                Game.canPlay = true;
                                Game.bg.isPlay = false;
                                setTimeout(function(){
                                    Game.calcAward();
                                }, 500);
                                Game.stage2D.stop = true;
                            }
                        }
                        setTimeout(function(){
                            Game.stage2D.paint();
                        }, 0);


                	}else{

                        GV.level = 0;
                		$.vui.remind(data.info);
                        if (data.data.lottery_num == 0) {
                            GV.lottery_num = 0;
                        }

                        return false;
                	}
                    if(GV.lottery_num == 0 && GV.is_share == 0) {
                        $("#showShare").show();
                    }
                    $("#awardNum .num").html(parseInt(GV.lottery_num));
                },
                error: function() {
                    console.log("error");
                }
            });




		});
	}

	Game.calcAward = function(){
        $.vui.noscroll = 1;
		if(GV.is_virtual==1){
            $("body,html").addClass("noscroll");
			$('#boxAlert').addClass("show");
			setTimeout(function() {
			   $('#boxAlert .i').addClass("show");
			   $('#boxAlert .zhongjiang').addClass("show");
			   $("#card").html(GV.card);
			   $("#boxAlert .zhongjiang .tel").val(GV.user_phone);
			}, 10);
		}else if(GV.is_virtual == 2){
            $("body,html").addClass("noscroll");
			$('#boxAlert').addClass("show");
			setTimeout(function() {
			   $('#boxAlert .i').addClass("show");
			   $('#boxAlert .address').addClass("show");
			}, 10);
		} else if(GV.is_virtual == 3){
            if(GV.fail_msg == '' || typeof(GV.fail_msg) == "undefined") {
                var _msg = '再接再厉，说不定下次就中奖了！';
            } else {
                var _msg = GV.fail_msg;
            }
            $.vui.remind(_msg);
        } else {
            if(GV.level == 0) {
                if(GV.fail_msg == '' || typeof(GV.fail_msg) == "undefined") {
                    var _msg = '您没有中奖！';
                } else {
                    var _msg = GV.fail_msg;
                }
                $.vui.remind(_msg);
            }
        }
        //抽奖记录
        $.ajax({
            type: 'POST',
            url: "http://m.shihuo.cn/choujiang/getList",
            data: {
                id:GV.lottery_id
            },
            dataType: 'json',
            success: function(data) {
                var _html = "";
                if(data.status==1){
                    $.each(data.data, function(index, val) {
                        if(val.is_virtual == 1) {
                            _html += '<p>' + val.prize_name  + '  ' + val.card + '  ' + val.created_at + '</p>';
                        } else {
                            _html += '<p>' + val.prize_name + '  ' + val.created_at + '</p>';
                        }
                    });
                }
                $("#record").html(_html);
            },
            error: function() {
                console.log("error");
            }
        });
	}

	Game.stage2D.loadImg(Game.imageSrcs, Game.images, Game.init);
});
