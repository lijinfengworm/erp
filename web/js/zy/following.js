//爵爷退休个人页侧栏信息
$(function(){
	if($("#j_head").length>=1){
		var iuid = $("#j_head").attr("uid");
		//佛爵爷活动
		 $.getJSON("http://zy.hupu.com/event/is_join?uid="+iuid+"&callback=?",function(data){
		   	if((data.status+1)){
		   		var photo = "<a href='http://zy.hupu.com/memorywall/sir-retires?uid="+uid+"' target='_blank'><img src='http://i1.hoopchina.com.cn/u/1305/10/602/15669602/0ee1ded3big.jpeg' width='29' height='29' /></a>"
		   		var text  = "2013年5月12日晚23点，亲历弗格森爵士退休前最后一个主场比赛——<a href='http://zy.hupu.com/memorywall/sir-retires?uid="+iuid+"' class='blue' target='_blank'>感谢你，弗爵爷</a>！<br />(第<span class='c_a41f24'>"+data.number+"</span>名亲历者)"
		   		addFollowing(iuid,photo,text);
		   	};
		  });

		 //贝克汉姆活动
		 $.getJSON("http://zy.hupu.com/beckham/is_join?uid="+iuid+"&callback=?",function(data){
		   	if((data.status+1)){
		   		//2013年5月16日晚23点，见证贝克汉姆宣布退役——再见，贝克汉姆！(再见，贝克汉姆 加链接)
		   		var photo = "<a href='http://zy.hupu.com/memorywall/beckham-retire?uid="+uid+"' target='_blank'><img src='http://i1.hoopchina.com.cn/u/1305/17/602/15669602/7fbb5507big.png' width='29' height='29' /></a>"
		   		var text  = "2013年5月16日晚23点，见证贝克汉姆宣布退役——<a href='http://zy.hupu.com/memorywall/beckham-retire?uid="+iuid+"' class='blue' target='_blank'>再见，贝克汉姆！</a>！<br />(第<span class='c_a41f24'>"+data.number+"</span>名亲历者)"
		   		addFollowing(iuid,photo,text);
		   	};
		  });
	};

	//生成dom
	function addFollowing(uid,photo,text){

		if($(".followingActivity").length<=0){
			var styleText = "<style>.followingActivity{zoom:1;background:#fff4ea;overflow:hidden;color:#444;padding:10px;margin-top:15px;line-height:24px;border:1px solid #f8e5d1;}.followingActivity .photo{float:left;width:29;height:29;border:1px solid #bcc4cb;margin-top:5px;}.followingActivity .text{float:right;width:240px;}.followingActivity .c_a41f24{color:#a41f24;}.followingActivity .listBox{overflow:hidden;border-top:1px dotted #ccc;padding-top:5px;margin-top:5px;zoom:1;}.followingActivity .listBox_1{margin：0px;margin:0px;border:0 none;}</style>"
			$("head").append(styleText);
			var html = "<div class='followingActivity'><div class='listBox listBox_1'><div class='photo'>"+photo+"</div>";
				html += "<div class='text'>"+text+"</div></div></div>";
			$("#sidebar").children().first().before(html);	
		}else{
			var html = "<div class='listBox'><div class='photo'>"+photo+"</div>";
				html += "<div class='text'>"+text+"</div></div>";
			$(".followingActivity").append(html);	
		}
	}
});
