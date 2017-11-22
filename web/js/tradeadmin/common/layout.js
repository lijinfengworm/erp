//layout js
+function(){
            var $window = $(window), $subnav = $("#subnav"), url;
            //设置高度
            $window.resize(function(){
                $("#main").css("min-height", $window.height() - 100);
            }).resize();


            //清除自动生成部件无用元素
            if($('.help').length) {
                $('.help').each(function(i){
                    $(this).find("br:first-child").remove();
                });
            }





            //设置field
            if($('#sf_admin_bar').length) {
                if($('#sf_admin_bar').find('#bar_not_position').length <= 0 ) {
                    $('#sf_admin_bar').addClass('sf_position_bar');
                    $('#sf_admin_bar').append("<span class='sf_admin_bar_hide'>搜</span>");
                    $('.sf_admin_bar_hide').prev().hide();
                    $('.sf_admin_bar_hide').on('mouseover', function () {
                        if ($(this).prev().is(":hidden")) {
                            $(this).prev().show(function () {
                                $(this).animate({width: 'auto'}, 500);
                            });
                            $(this).text('隐藏搜索器');
                        }
                    });
                    $('.sf_admin_bar_hide').on('click', function () {
                        if ($(this).prev().is(":hidden")) {
                            $(this).prev().show(function () {
                                $(this).animate({width: 'auto'}, 500);
                            });
                            $(this).text('隐藏搜索器');
                        } else {
                            $(this).prev().hide();
                            $(this).text('搜');
                        }
                    });


                    //判断搜索是否默认展开
                    if ($('#sf_admin_bar').is(":hidden")) {
                        var _is_search_show = $('#main').data('search-show');
                        if (_is_search_show == 1) {
                            $('.sf_admin_bar_hide').prev().show(function () {
                                $('.sf_admin_bar_hide').animate({width: 'auto'}, 500);
                            });
                            $('.sf_admin_bar_hide').text('隐藏搜索器');
                        }
                    }
                } else {
                    $('#sf_admin_bar').addClass('sf_show_bar');
                }


            }





            /* 左边菜单高亮  如果不是和左边一样的 那么要调用自定义函数 来实现高亮 */
            url = window.location.pathname + window.location.search;
            url = url.replace(/(\?.*)|(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
            var _current = $subnav.find("a[href='" + url + "']").parent();
            _current.addClass("current");
            $('.side-sub-menu').attr('current-id',_current.attr('data-id'));
            /* 左边菜单显示收起 */
            $("#subnav").on("click", "h3", function(){
                var $this = $(this);
                $this.find(".icon").toggleClass("icon-fold");
                $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
                      prev("h3").find("i").addClass("icon-fold").end().end().hide();
            });
            $("#subnav h3 a").click(function(e){e.stopPropagation()});
            /* 头部管理员菜单收起隐藏 */
            $(".user-bar").mouseenter(function(){
                var userMenu = $(this).children(".user-menu ");
                userMenu.removeClass("hidden");
                clearTimeout(userMenu.data("timeout"));
            }).mouseleave(function(){
                var userMenu = $(this).children(".user-menu");
                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
            });

            // 导航栏超出窗口高度后的模拟滚动条
            var boxHeight = parseInt($(window).height() - $('.header').height());
            var subHeight  = $(".subnav").height();
            var diff = subHeight - boxHeight;
            if(diff > 0){
                $('.sidebar').css('height',parseInt(boxHeight));
                $('.sidebar').niceScroll({ cursorborder : 0  });
            }




        //放大缩小按钮
        $('#J_fullScreen').click(function (event) {
            event.preventDefault();
            event.stopPropagation();
            var is_all = $(this).attr("is-all");
            if(is_all == 1) {
                $(document.body).css('padding', '50px 0 0 140px');
                $('.sidebar, .header').show();
                $(this).attr('is-all',0);
                $(this).addClass('full_screen').removeClass('all_screen');
            } else {
                $(document.body).css('padding', '0');
                $('.sidebar, .header').hide();
                $(this).attr('is-all',1);
                $("#main").css("min-height", $window.height());
                $(this).addClass('all_screen').removeClass('full_screen');
            }
        });

}();
        
