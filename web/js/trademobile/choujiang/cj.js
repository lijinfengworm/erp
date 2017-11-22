window.LXY = {};

//动画框架
window.requestAnimFrame = (function(callback) {
	return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame ||
	function(callback) {
		window.setTimeout(callback, 0);
	};
})();

//取消动画
window.cancelAnimFrame = (function(id) {
	return window.cancelAnimationFrame || window.webkitCancelAnimationFrame || window.mozCancelAnimationFrame || window.oCancelAnimationFrame || window.msCancelAnimationFrame ||
	function(id) {
		window.clearTimeout(id);
	};
})();



LXY.Stage2D = function(id){
	var _this = this;
	_this.canvas = document.getElementById(id);
	_this.context = _this.canvas.getContext('2d');
	_this.stageWidth = _this.canvas.width;
	_this.stageHeight = _this.canvas.height;
	_this.displayObjectList = [];
	_this.imgsNum = 0;
	//fps 用于帧数设置
	_this.currLoop = 0;
	_this.lastLoop = 0;
	//初始化
	_this.init = function(){
		setTimeout(_this.paint,0);
	}

	_this.addChild = function(child){
		if(_this.indexOf(child) == -1){
			_this.displayObjectList.push(child);
		}else{
			_this.displayObjectList.splice(child,1);
			_this.displayObjectList.push(child);
		}
	}

	_this.removeChild = function(child){
		if(_this.indexOf(child) != -1){
			_this.displayObjectList.splice(child,1);
		}
	}

	_this.indexOf = function(object){
		for(var i = 0; l = _this.displayObjectList.length, i < l; i++){
			if(_this.displayObjectList[i] == object){
				return i;
			}
		}

		return -1;
	}

	// _this.clear = function(){
	// 	if(_this.timeout){
	// 		window.cancelAnimFrame(_this.timeout);
	// 	}
	// }


	_this.paint = function(){
		//清空画布
		_this.context.clearRect(0, 0, _this.stageWidth, _this.stageHeight);
		_this.context.globalAlpha = 1;
		for(var i = 0; i < _this.displayObjectList.length; i++){
			var obj = _this.displayObjectList[i];

			if(obj.visible){

				obj.timer();
				//保存上次状态
				_this.context.save();
				if(obj.blend){
					//加入混色功能
                	_this.context.globalCompositeOperation = obj.blend;
				}

				_this.context.globalAlpha = obj.alpha;
				_this.context.translate(obj.x, obj.y);
				_this.context.rotate(obj.rotation * Math.PI / 180);
				_this.context.scale(obj.scaleX, obj.scaleY);
				if(obj.draw){
					obj.draw.apply(_this.context);
				}
				
				//释放上次状态
				_this.context.restore();
			}
		}

	   _this.timeout = window.requestAnimFrame(_this.paint);

	   if(_this.stop){
	   		if(_this.timeout){
				window.cancelAnimFrame(_this.timeout);
			}
	   }
	}
	//传入一个需要加载的图片数组
	_this.loadImg = function(arg, images, callback){
		var image = new Image();
		image.src = arg[_this.imgsNum];
		image.onload = function(e){
			if(image.complete == true){
				images.push(image);
				_this.imgsNum ++;
				if(_this.imgsNum < arg.length){
					_this.loadImg(arg, images, callback);
				}else{
					if(callback){
						callback();
					}
				}
			}
		}
	}
}

LXY.MovieClip2D = function(){
	var _this = this;
	_this.x = 0;
	_this.y = 0;
	_this.width = 0;
	_this.height = 0;
	_this.rotation = 0;
	_this.scaleX = 1;
	_this.scaleY = 1;
	_this.visible = true;
	_this.alpha   = 1;
	_this.isPlay  = true;
	_this.blend   = 'source-over';
	_this.currLoop = 0;
	_this.lastLoop = 0;
	_this.fps = 0;
	_this.repeatCount = -1;//无限次数
	_this.currentCount = 0;//当前循环的次数
	_this.paint = function(){};

	//每次的回调
	_this.updateData = function(){}

	_this.reset = function(){
		_this.currentCount = 0;
	}

	_this.stop = function(){
		_this.isPlay = false;
	}

	//计时器
	_this.timer = function(){
		if(_this.isPlay){
			_this.currLoop = new Date().getTime();
			if((_this.currLoop - _this.lastLoop) > 1000 / _this.fps){
				if(_this.updateData){
					_this.updateData();
				}

				//记录一下时间间隔
				_this.lastLoop = new Date().getTime();

				if(_this.repeatCount != -1){
					_this.currentCount++;
					if(_this.currentCount >= _this.repeatCount){
						_this.isPlay = false;
					}
				}
			}
		}
	}
}
