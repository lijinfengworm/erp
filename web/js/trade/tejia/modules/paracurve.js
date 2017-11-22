/*
 * @start[] 起始位置，索引0为left的值，索引1为top值
 * @end[] 终止位置，索引0为left的值，索引1为top值
 * @step 步长 每次x轴方向移动的距离
 * @movecb 移动中的回调函数
 * @moveendcb 移动结束的回调函数
 */
!(function($) {
    var old = $.fn.paracurve;

    $.fn.paracurve = function(option) {
        //默认的起点为物体的当前位置，终点为页面的右下角
        var opt = {
                start: [this.position().left, this.position().top],
                end: [$(window).width()-this.width(), $(window).height()-this.height()],
                step:1,
                movecb:$.noop,
                moveendcb:$.noop
            },
            that = this;

        $.extend(opt, option);

        //计算抛物线需要三点，起始和终止位置+未知
        //未知位置：取起始和终止位置的x轴中间位置x=start.x+(end.x-start.x)/2
        //y轴方向：取起始和终止点距离页面顶部最小的值-200，y=Math.max(start.y,end.y)-200,如果y<0，则=0
        //未知位置的x，y确定后，则把该点视为原点，即网页的坐标原点由原来的左上角转移到该点，意念上转移
        //重新计算起始点相对新原点的坐标,起始点：[x-start.x,y-start.y],终止点：[x-end.x,y-end.y],原点：[0,0]
        //根据抛物线公式y=a*x*x+b*x+c,把三点坐标代入公式，得到a,b,c=0的值

        //三点实际坐标值
        var x1 = opt.start[0],
            y1 = opt.start[1],
            x2 = opt.end[0],
            y2 = opt.end[1],
            x = x1 + (x2 - x1) / 2,
            y = Math.min(y1, y2) - 100;

        //防止移出页面,x,y作为原点
        x = x > 0 ? Math.ceil(x) : Math.floor(x);
        y = y < 0 ? 0 : Math.ceil(y);

        //三点相对坐标值
        var X1 = x - x1,
            Y1 = y - y1,
            X2 = x - x2,
            Y2 = y - y2,
            X = 0,
            Y = 0;

        //根据三点相对坐标计算公式中的a,b,c=0不用计算
        var a = (Y2 - Y1 * X2 / X1) / (X2 * X2 - X1 * X2),
            b = (Y1 - a * X1 * X1) / X1;

        return that.each(function(index, ele) {
            //获得物体起始位置
            var startPos = $(ele).data('startPos');
            startPos=!!startPos?startPos:$(ele).position();

            //检查当前物体是否正在运动中并且当前位置是否已在终点
            if ($(ele).data('running') || startPos.left == x2) {
                end();

                //复位
                $(ele).css({
                    left: startPos.left + 'px',
                    top: startPos.top + 'px'
                });
            } else {
                //记忆物体起始位置
                $(ele).data('startPos',$(ele).position());

                var timer = setInterval(function() {
                    var pos = $(ele).position();

                    //如果物体已到达终点
                    if (pos.left >= x2) {
                        end();
                    } else {
                        //left,top实际位置，Left,Top相对位置
                        var left = pos.left + opt.step,
                            Left = x - left,
                            Top = a * Left * Left + b * Left,
                            top = y - Top;

                        that.css({
                            left: left + 'px',
                            top: top + 'px'
                        });

                        $(ele).data('running', true);

                        if(opt.movecb&&$.isFunction(opt.movecb)){
                            opt.movecb.call(ele);
                        }
                    }
                }, 30);

                $(ele).data('timer', timer);
            }


            //动画完成
            function end(){
                //标识是否正在运动中
                $(ele).data('running', false).css({
                    left:x2+'px',
                    top:y2+'px'
                });

                clearInterval(timer);

                //执行完执行回调函数
                if(opt.moveendcb&&$.isFunction(opt.moveendcb)){
                    opt.moveendcb.call(ele);
                }
            }
        });
    };

    $.fn.paracurve.noConflict = function() {
        $.fn.paracurve = old;
        return this;
    };
})(jQuery);