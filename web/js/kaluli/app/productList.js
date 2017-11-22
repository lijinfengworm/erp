requirejs.config({
    baseUrl: "/js/kaluli/"
});
require([""],function(){
    var multiple_select_list = [];//保存多选的数组
    $("input[name=filter_activity]").click(function(){
        if($(this).prop("checked")){
            window.location.href= $(this).parent().attr("data-actUrl");
        }else{
            window.location.href = $(this).parent().attr("data-originUrl");
        }
    });

    /**
     * 列表页 展开收齐功能
     */
    $(".J_more-sild").click(function(){
        if($(this).children('i').hasClass('icon-arrowup')){
            $(this).html('展开<i class="iconfont icon-zhankai"></i>');
            $(this).parents('.sl-wrap').find(".k-valueList").css("max-height",52);
        }else{
            $(this).html('收起<i class="iconfont icon-arrowup"></i>');
            $(this).parents('.sl-wrap').find(".k-valueList").css("max-height","none");
        }
    });

    /**
     * 列表页多选 展开
     */
    $('.multiple-choice-btn').click(function () {
        $('.sl-wrap').removeClass('multiple').find(".fixed-buttons").css('visibility', 'visible');
        $('.sl-wrap .k-valueList').css("max-height",52);
        $('.sl-wrap a').removeClass('selected');
        if(addSelectd().length > 0){
            $(this).parents('.sl-wrap').find('.J_btnsConfirm').removeClass('disabled');
        }else{
            $(this).parents('.sl-wrap').find('.J_btnsConfirm').addClass('disabled');
        }
        $(this).parents('.sl-wrap').addClass('multiple');
        $(this).parents('.sl-wrap').find(".k-valueList").css("max-height","none");
        $(this).parent().css('visibility', 'hidden');
    });

    /**
     * 列表页多选 关闭
     */
    $('.J_btnsCancel').click(function () {
        $(this).parents('.sl-wrap').removeClass('multiple');
        $(this).parents('.sl-wrap').find(".k-valueList").css("max-height",52);
        $(this).parents('.sl-wrap').find(".fixed-buttons").css('visibility', 'visible');
    })

    // ‍function URLencode(sStr) {
    //     return escape(sStr).replace(/\+/g, '%2B').replace(/\"/g,'%22').replace(/\'/g, '%27').replace(/\//g,'%2F');
    // }

    /**
     * 判断所有多选的数组
     * @returns {Array}
     */
    function addSelectd() {
        multiple_select_list.length = 0;
        $('.sl-value a').each(function () {
            if($(this).hasClass('selected')){
                multiple_select_list.push($(this).attr('title'));
            }
        });
        return multiple_select_list.join();
    }

    $('.sl-value').on('click','a',function (e) {
        var _this = $(this);
        if(_this.parents('.sl-wrap').hasClass('multiple')){
            e.preventDefault();
            if(_this.hasClass('selected')){
                _this.removeClass('selected');
            }else{
                _this.addClass('selected');
            }
            if(addSelectd().length > 0){
                _this.parents('.sl-wrap').find('.J_btnsConfirm').removeClass('disabled');
            }else{
                _this.parents('.sl-wrap').find('.J_btnsConfirm').addClass('disabled');
            }
        }
    });

    function urlencode(clearString) {
        var output = '';
        var x = 0;

        clearString = utf16to8(clearString.toString());
        var regex = /(^[a-zA-Z0-9-_.]*)/;

        while (x < clearString.length)
        {
            var match = regex.exec(clearString.substr(x));
            if (match != null && match.length > 1 && match[1] != '')
            {
                output += match[1];
                x += match[1].length;
            }
            else
            {
                if (clearString[x] == ' ')
                    output += '+';
                else
                {
                    var charCode = clearString.charCodeAt(x);
                    var hexVal = charCode.toString(16);
                    output += '%' + ( hexVal.length < 2 ? '0' : '' ) + hexVal.toUpperCase();
                }
                x++;
            }
        }

        function utf16to8(str)
        {
            var out, i, len, c;

            out = "";
            len = str.length;
            for(i = 0; i < len; i++)
            {
                c = str.charCodeAt(i);
                if ((c >= 0x0001) && (c <= 0x007F))
                {
                    out += str.charAt(i);
                }
                else if (c > 0x07FF)
                {
                    out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
                    out += String.fromCharCode(0x80 | ((c >> 6) & 0x3F));
                    out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
                }
                else
                {
                    out += String.fromCharCode(0xC0 | ((c >> 6) & 0x1F));
                    out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
                }
            }
            return out;
        }

        return output;
    }
    
    $('.J_btnsConfirm').click(function () {
        if($(this).hasClass('disabled')){
            return
        }
        var urlParam = addSelectd(),
            urlType = $(this).attr('data-ca'),
            preParam = '',
            oldParam = '';
        if(urlType === 'brand'){
            urlParam = urlencode(urlencode(addSelectd()));
        }
        preParam = '?'+urlType+'='+urlParam;
        if($('.search-area').length > 0){
            $('.search-area b').each(function () {
                if($(this).attr('data-search-name') == 'brand'){
                    oldParam+='&'+$(this).attr('data-search-name')+'='+urlencode(urlencode($(this).attr("title")))+'';
                }else{
                    oldParam+='&'+$(this).attr('data-search-name')+'='+$(this).attr("title")+'';
                }
            });
            window.location.href = '//www.kaluli.com/product'+preParam+oldParam+'';
        }else {
            window.location.href = '//www.kaluli.com/product'+preParam+'';
        }
    });

    /**
     * fun1 列表高度不超过52px 的时候隐藏展开按钮
     * fun2 分类选项小于2个的时候把多选隐藏
     */
    (function () {

        $('.sl-value').each(function () {
            if($(this).height() < 52){
                $(this).find('.J_more-sild').css('visibility', 'hidden');
            }
        });

        $('.J-valueList').each(function () {
            if($(this).children('li').length < 2){
                $(this).parents('.sl-value').find('.multiple-choice-btn').css('visibility', 'hidden');
            }
        });
    })()

});