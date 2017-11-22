/*
*@Description: 阵营球迷平台
*@Author: wangjun
*@Update: 2013-04-24 18:40
*/
(function(win){
	// 防止多次加载
	if(typeof window.zyFansPlatform != "undefined") return false;
	var _win = window,
		_doc = document;
	var zyFansPlatform = {
		init: function(){
			var _this = this
				reg = /(\.\w+)_/g;
			this.obj = hp.$("#J_zyFansPlatform");
			this.zyName = hp.attr(this.obj,"data-zy-name");
			this.url = 'http://zy.hupu.com/api/objects_follow_count?names=' + encodeURIComponent(this.zyName) + '&callback=?';
			this.subDomain = window.location.host.split('.')[0] || "nba";
			this.getData();
			this.zyItem = [];
			this.zyItem.push(this.zyName.split(/_/g)); 
		},
		getData: function(){
			var _this = this,
				len,total = 0,
				html = '';
			hp.getJSON(this.url,function(data){
				if(data.status == 0){
					data = data.data;
					len = _this.zyItem[0].length;
					for(var i = 0; i < len; i++){	
						total += parseInt(data[_this.zyItem[0][i]]);
					}
					if(total == 0) return false;
					hp.loadStyle('http://zy.hupu.com/css/zy/zyFansPlatform.css');
					if(_this.subDomain == "voice"){
						html = '<div class="voice-zyJoin-ad300-90"><div class="voice-hd-A"><h2>虎扑阵营</h2></div><ul class="voice-zyJoin-item">';					
					}else{
						html = '<div class="zy-join-ad250"><h4 class="zy-join-hd">虎扑阵营</h4><div class="zy-join-bd"><ul class="item">';					
					}
					for(var i = 0; i < len; i++){
						if(data[_this.zyItem[0][i]] != 0){
							html += '<li><span class="zy-num">'+data[_this.zyItem[0][i]]+'</span><span class="zy-red">'+_this.zyItem[0][i]+'</span>蜜</li>';
						}
					}
					if(_this.subDomain == "voice"){
						html += '</ul></div>';
					}else{
						html += '</ul><span class="text-info"></span><a href="http://zy.hupu.com/object/all" target="_blank" class="btn-joinZy" title="立即加入">立即加入</a></div></div>';
					}
					
					hp.append(_this.obj,html);
				}
			})
		}
	}
	hp.ready(function(){
		zyFansPlatform.init()
	})
	win.zyFansPlatform = zyFansPlatform;
})(window);
