//dom加载完成后执行的js
$(function(){
	

	 //ajax get请求
    $('.ajax-get').click(function(){
        var target;
        var that = this;
        if ( $(this).hasClass('confirm') ) {
            if(!confirm('确认要执行该操作吗?')){
                return false;
            }
        }
        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {
            $.get(target,function(data){
                if (data.status==1) {
                    if (data.url) {
                        updateAlert(data.info + ' 页面即将自动跳转~','alert-success');
                    }else{
                        updateAlert(data.info,'alert-success');
                    }
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
                            $('#top-alert').find('button').click();
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    updateAlert(data.info);
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $('#top-alert').find('button').click();
                        }
                    },1500);
                }
            },'json');

        }
        return false;
    });

    //ajax post submit请求
    $('.ajax-post').click(function(){
        var target,query,form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm=false;
		
        if( ($(this).attr('type')=='submit') || (target = $(this).attr('href')) || (target = $(this).attr('url')) ){
            form = $('.'+target_form);

            if ($(this).attr('hide-data') === 'true'){//无数据时也可以使用的功能
            	form = $('.hide-data');
            	query = form.serialize();
            }else if (form.get(0)==undefined){
            	return false;
            }else if ( form.get(0).nodeName=='FORM' ){
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                if($(this).attr('url') !== undefined){
                	target = $(this).attr('url');
                }else{
                	target = form.attr('action');
                }
                query = form.serialize();
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {
                form.each(function(k,v){
                    if(v.type=='checkbox' && v.checked==true){
                        nead_confirm = true;
                    }
                })
                if ( nead_confirm && $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.serialize();
            }else{
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
				//压入数组
                query = form.find('input,select,textarea').serialize();
            }
			
			
			
            $(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
            $.post(target,query,function(data){
                if (data.status==1) {
                    if (data.url) {
                        updateAlert(data.info + ' 页面即将自动跳转~','alert-success');
                    }else{
                        updateAlert(data.info ,'alert-success');
                    }
                    setTimeout(function(){
                    	$(that).removeClass('disabled').prop('disabled',false);
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
                            $('#top-alert').find('button').click();
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    updateAlert(data.info);
                    setTimeout(function(){
                    	$(that).removeClass('disabled').prop('disabled',false);
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $('#top-alert').find('button').click();
                        }
                    },1500);
                }
            },'json');
        }
        return false;
    });
	
	
    //所有的删除操作，删除数据后刷新页面  通用 并不只是用于删除
    if ($('a.J_ajax_del').length) {
        Wind.use('artDialog', function () {
            $('.J_ajax_del').on('click', function (e) {
                e.preventDefault();
                var $_this = this,
                    $this = $($_this),
                    href = $this.prop('href'),
                    msg = $this.data('msg') ? $this.data('msg')  : '确定要删除吗';
                	dialog({
						title: false,
						content: msg,
						okValue: '确定',
						ok: function () {
							$.getJSON(href).done(function (data) {
								if (data.status === 1) {
									if (data.url) {
										location.href = data.url;
									} else {
										reloadPage(window);
									}
								} else if (data.state === 'fail') {
									dialog({title: '提示',content:data.info}).show();
			
								}
							});
						},
						cancelValue: '取消',
    					cancel: function () {}
					}).show($_this);
            });

        });
    }




    /*复选框全选(支持多个，纵横双控全选)。
     *实例：版块编辑-权限相关（双控），验证机制-验证策略（单控）
     *	"J_check"的"data-xid"对应其左侧"J_check_all"的"data-checklist"；
     *	"J_check"的"data-yid"对应其上方"J_check_all"的"data-checklist"；
     *	全选框的"data-direction"代表其控制的全选方向(x或y)；
     *	"J_check_wrap"同一块全选操作区域的父标签class，多个调用考虑
     */

    if ($('.J_check_wrap').length) {
        var total_check_all = $('input.J_check_all');

        //遍历所有全选框
        $.each(total_check_all, function () {
            var check_all = $(this),
                check_items;

            //分组各纵横项
            var check_all_direction = check_all.data('direction');
            check_items = $('input.J_check[data-' + check_all_direction + 'id="' + check_all.data('checklist') + '"]');

            //点击全选框
            check_all.change(function (e) {
                var check_wrap = check_all.parents('.J_check_wrap'); //当前操作区域所有复选框的父标签（重用考虑）
                if ($(this).prop('checked')) {
                    //全选状态
                    check_items.prop('checked', true);

                    //所有项都被选中
                    if (check_wrap.find('input.J_check').length === check_wrap.find('input.J_check:checked').length) {
                        check_wrap.find(total_check_all).prop('checked', true);
                    }

                } else {
                    //非全选状态
                    check_items.prop('checked',false);

                    //另一方向的全选框取消全选状态
                    var direction_invert = check_all_direction === 'x' ? 'y' : 'x';
                    check_wrap.find($('input.J_check_all[data-direction="' + direction_invert + '"]')).removeAttr('checked');
                }

            });

            //点击非全选时判断是否全部勾选
            check_items.change(function () {

                if ($(this).prop('checked')) {

                    if (check_items.filter(':checked').length === check_items.length) {
                        //已选择和未选择的复选框数相等
                        check_all.prop('checked', true);
                    }

                } else {
                    check_all.prop('checked',false);
                }

            });


        });

    }
	
	
	
	
	/**顶部警告栏*/
	var content = $('#main');
	var top_alert = $('#top-alert');
	top_alert.find('.close').on('click', function () {
		top_alert.removeClass('block').slideUp(200);
	});
	
	window.updateAlert = function (text,c) {
		text = text||'default';
		c = c||false;
		if ( text!='default' ) {
            top_alert.find('.alert-content').html(text);
			if (top_alert.hasClass('block')) {
			} else {
				top_alert.addClass('block').slideDown(200);
			}
		} else {
			if (top_alert.hasClass('block')) {
				top_alert.removeClass('block').slideUp(200);
			}
		}
		if ( c!=false ) {
            top_alert.removeClass('alert-error alert-warn alert-info alert-success').addClass(c);
		}
	};
	

    //后台通用日历空间
    //日期选择器
    var dateInput = $("input.J_date")
    if (dateInput.length) {
        Wind.use('My97DatePicker');
    }



    //强化select
    var radioChosen = $('.J_radioChosen');
    if(radioChosen.length) {
        Wind.css('chosen');
        Wind.use('chosen',  function () {
            radioChosen.chosen();
        });
    }


    //所有加了限制文字字数 text limit的text

    var J_text_limit = $('.J_text_limit');
    if(J_text_limit.length) {
        Wind.use('limitTxt', function () {
            J_text_limit.each(function(){
                limitTxt(this.id,$(this).attr('tip-id'),$(this).attr('text-limit'),false);
            });
        });
    }



    //treeTable
    /*
    var _treeTable = $('.J_tree_table');
    if(_treeTable.length) {
        Wind.css('treeTable');
        Wind.use('treeTable',  function () {
            _treeTable.treetable({ expandable: true });
        });
    }*/

    //iframe窗口打开
    var J_art_iframe = $('.J_art_iframe');
    if(J_art_iframe.length) {
        Wind.use('artDialogPlus', function () {
            $('.J_art_iframe').on('click', function (e) {
                var J_art_iframe_title = $(this).data('title');
                var J_art_iframe_url = $(this).data('url');
                var J_art_iframe_height = $(this).data('height');
                var J_art_iframe_width = $(this).data('width');
                var is_reload = $(this).data('reload');
                dialog({
                    title: J_art_iframe_title ? J_art_iframe_title : 'title',
                    url: J_art_iframe_url,
                    height:J_art_iframe_height ? J_art_iframe_height :500,
                    width:J_art_iframe_width ? J_art_iframe_width : 900,
                    onclose: function () {
                        if(is_reload) top.location.reload();
                    }
                }).showModal();
            });
        });
    }

    //tips
    var tips_dom = $('.J_tips');
    if(tips_dom.length) {
        Wind.css('tips');
        Wind.use('tips',function(){
            tips_dom.simpletooltip({
               position:"bottom"
            });
        });


    }



 //颜色选择器
    var color_pick = $('.J_color_pick');
    if (color_pick.length) {
        Wind.use('colorPicker', function () {
            color_pick.each(function () {
                var not_show_flag = $(this).attr('not-show-flag');
                $(this).colorPicker({
                    callback: function (color) {
                        input = $(this).next('.J_hidden_color');
                        var _color = color.length === 7 ? color : '';
                        var bg_color = _color;
                        if(not_show_flag == 1 && _color != '') {
                            _color = _color.substring(1);
                        }
                        input.val(_color).css('background', bg_color);
                    }
                });
            });
        });
    }

        $(document).on('click','.J_color_pick',function(){
            var _this = $(this);
            var not_show_flag = $(this).attr('not-show-flag');
            Wind.use('colorPicker', function () {
                _this.colorPicker({
                    callback: function (color) {
                        input = _this.next('.J_hidden_color');
                        var _color = color.length === 7 ? color : '';
                        var bg_color = _color;
                        if(not_show_flag == 1 && _color != '') {
                            _color = _color.substring(1);
                        }
                        input.val(_color).css('background',bg_color);
                    }
                });
            });
        });




});//jquery end




function reloadPage(win) {
    var location = win.location;
    location.href = location.pathname + location.search;
}

function changePage(url) {
    window.location = url;
}
