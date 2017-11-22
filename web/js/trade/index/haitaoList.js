(function($,win,doc){
    var shareBlog = {
        init: function(){
            var _this = this;
            this.$elem = $(".J_shihuo_share");
            if(!this.$elem.length) return false;

            this.$item = this.$elem.find("a");

            this.$item.bind("click",function(){
                _this.getCon($(this));
            })
        },
        getShare:function(opt){
            var scrollW = 600,
                scrollH = 450,
                popNum = 0,
                iTop = (window.screen.availHeight-30-scrollH)/2,
                iLeft = (window.screen.availWidth-10-scrollW)/2,
                weiboAppkey = "3033141272",
                qqtAppKey = "801094981",
                ralateUid = "2754272121",
                qzoneSite = "虎扑识货",
                websiteLink = "",
                opt = $.extend({
                    element: "",
                    title:   "",
                    link:    "",
                    pic:     ""
                },opt),
                element = opt.element,
                title = opt.title,
                link = opt.link,
                pic = opt.pic ? opt.pic : "";

            switch(element){
                case "weibo":
                    websiteLink = 'http://service.weibo.com/share/share.php?url='+encodeURIComponent(link)+'&title='+encodeURIComponent(title)+'&pic='+encodeURIComponent(pic)+'&appkey='+encodeURIComponent(weiboAppkey)+'&ralateUid='+encodeURIComponent(ralateUid);
                    break;
                case "qq" :
                    websiteLink = 'http://v.t.qq.com/share/share.php?url='+encodeURIComponent(link)+'&title='+encodeURIComponent(title + ' （分享自 @hupuvoice)')+'&pic='+encodeURI(pic)+'&appkey='+encodeURIComponent(qqtAppKey);
                    break;
                case "qzone":
                    websiteLink = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+encodeURIComponent(link)+'&title='+encodeURIComponent(title)+'&pics='+encodeURI(pic)+'&site='+encodeURIComponent(qzoneSite);
                    break;
                case "renren":
                    websiteLink = 'http://widget.renren.com/dialog/share?link='+encodeURIComponent(link)+'&title='+encodeURIComponent(title)+'&pic='+encodeURIComponent(pic);
                    break;
                default:
                    break;
            }
            if(popNum == 0){
                popNum = 1;
                window.open(''+websiteLink+'','_blank','scrollbars=no,width='+scrollW+',height='+scrollH+',left='+iLeft+',top='+iTop+',status=no,resizable=yes');
            }

        },
        getCon: function(elem){
            var parent = elem.parents(".shihuo-index-item"),
                shareName = elem.attr("data-shareName"),
                title = elem.parent().attr('data-name'),
                link = elem.parent().attr('data-url'),
                pic;

            if(parent.length){
                var itemHd = parent.find(".item-hd h2 a");
                title = itemHd.text();
                link = itemHd.attr("href");
                pic = parent.find(".item-bd-all img").attr("src");
            }else{
                //title = $(".detail-title h1").text();
                pic = $(".detail-content-main img").attr("src");
            }

            this.getShare({
                element:  shareName,                title:   title,
                link:    link,
                pic:     pic
            })
        }
    }
    shareBlog.init();
})(jQuery,window,document);


/*点赞 取消赞*/
function praise(id,ts){
    $.get('http://www.shihuo.cn/message_support_agaist?id='+id+'&type=1&source=1',{},function(msg){
        if(msg.status == 200){
            if(msg.msg == '支持成功'){
                $(ts).find('i').addClass('on');
            }else if(msg.msg == '取消支持成功'){
                $(ts).find('i').removeClass('on');
            }

            $(ts).find('s').html(msg.data.snum);
        }else{

        }
    },'json');
}