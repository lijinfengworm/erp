/*
 * @start[] ��ʼλ�ã�����0Ϊleft��ֵ������1Ϊtopֵ
 * @end[] ��ֹλ�ã�����0Ϊleft��ֵ������1Ϊtopֵ
 * @step ���� ÿ��x�᷽���ƶ��ľ���
 * @movecb �ƶ��еĻص�����
 * @moveendcb �ƶ������Ļص�����
 */
!(function($) {
    var old = $.fn.paracurve;

    $.fn.paracurve = function(option) {
        //Ĭ�ϵ����Ϊ����ĵ�ǰλ�ã��յ�Ϊҳ������½�
        var opt = {
                start: [this.position().left, this.position().top],
                end: [$(window).width()-this.width(), $(window).height()-this.height()],
                step:1,
                movecb:$.noop,
                moveendcb:$.noop
            },
            that = this;

        $.extend(opt, option);

        //������������Ҫ���㣬��ʼ����ֹλ��+δ֪
        //δ֪λ�ã�ȡ��ʼ����ֹλ�õ�x���м�λ��x=start.x+(end.x-start.x)/2
        //y�᷽��ȡ��ʼ����ֹ�����ҳ�涥����С��ֵ-200��y=Math.max(start.y,end.y)-200,���y<0����=0
        //δ֪λ�õ�x��yȷ������Ѹõ���Ϊԭ�㣬����ҳ������ԭ����ԭ�������Ͻ�ת�Ƶ��õ㣬������ת��
        //���¼�����ʼ�������ԭ�������,��ʼ�㣺[x-start.x,y-start.y],��ֹ�㣺[x-end.x,y-end.y],ԭ�㣺[0,0]
        //���������߹�ʽy=a*x*x+b*x+c,������������빫ʽ���õ�a,b,c=0��ֵ

        //����ʵ������ֵ
        var x1 = opt.start[0],
            y1 = opt.start[1],
            x2 = opt.end[0],
            y2 = opt.end[1],
            x = x1 + (x2 - x1) / 2,
            y = Math.min(y1, y2) - 100;

        //��ֹ�Ƴ�ҳ��,x,y��Ϊԭ��
        x = x > 0 ? Math.ceil(x) : Math.floor(x);
        y = y < 0 ? 0 : Math.ceil(y);

        //�����������ֵ
        var X1 = x - x1,
            Y1 = y - y1,
            X2 = x - x2,
            Y2 = y - y2,
            X = 0,
            Y = 0;

        //�����������������㹫ʽ�е�a,b,c=0���ü���
        var a = (Y2 - Y1 * X2 / X1) / (X2 * X2 - X1 * X2),
            b = (Y1 - a * X1 * X1) / X1;

        return that.each(function(index, ele) {
            //���������ʼλ��
            var startPos = $(ele).data('startPos');
            startPos=!!startPos?startPos:$(ele).position();

            //��鵱ǰ�����Ƿ������˶��в��ҵ�ǰλ���Ƿ������յ�
            if ($(ele).data('running') || startPos.left == x2) {
                end();

                //��λ
                $(ele).css({
                    left: startPos.left + 'px',
                    top: startPos.top + 'px'
                });
            } else {
                //����������ʼλ��
                $(ele).data('startPos',$(ele).position());

                var timer = setInterval(function() {
                    var pos = $(ele).position();

                    //��������ѵ����յ�
                    if (pos.left >= x2) {
                        end();
                    } else {
                        //left,topʵ��λ�ã�Left,Top���λ��
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


            //�������
            function end(){
                //��ʶ�Ƿ������˶���
                $(ele).data('running', false).css({
                    left:x2+'px',
                    top:y2+'px'
                });

                clearInterval(timer);

                //ִ����ִ�лص�����
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