define(["tips"], function () {
    var cartUtil = {
        changeFlag: true,
        submitUp: false,
        goodCheck: [],
        goodList: [],
        goodGid: null,
        init: function () {
            this.bindFun();
            this.deleteFun();
        },
        allSelectedFun:function (_this) {
            var that = this,
             check = _this.attr("checked"),
             name = _this.attr("name"),
             sonName = name + '-son';

            if (check == "checked") {
                $('input[name="'+name+'"]').attr("checked", "checked");
                $('input[name="'+sonName+'"]').each(function () {
                    $(this).attr("checked", "checked");
                });
            } else {
                $('input[name="'+name+'"]').attr("checked", false);
                $('input[name="'+sonName+'"]').attr("checked", false);
            }
            that.getGoodsIdPost(name);
        },
        bindFun: function () {
            var that = this;
            //一般商品的全选
            $("input[name='check-all-general']").change(function () {
                var _this = $(this);
                that.allSelectedFun(_this);
            });
            //南沙仓仓库的的全选
            $("input[name='check-all-nansha']").change(function () {
                var _this = $(this);
                that.allSelectedFun(_this);
            });
            //宁波仓仓库的全选
            $("input[name='check-all-ningbo']").change(function () {
                var _this = $(this);
                that.allSelectedFun(_this);
            });
            
            //一般商品单个勾选
            $("input[name='check-all-general-son']").change(function () {
                var checkAll = true;
                $(this).parents("table").find("input[name='check-all-general-son']").each(function (i, d) {
                    if (!d.checked) {
                        checkAll = false;
                    }
                });
                if (checkAll) {
                    $("input[name='check-all-general']").attr("checked", "checked");
                } else {
                    $("input[name='check-all-general']").attr("checked", false);
                }
                that.getGoodsIdPost('check-all-general');
            });
            //南沙商品单个勾选
            $("input[name='check-all-nansha-son']").change(function () {
                var checkAll = true;
                $(this).parents("table").find("input[name='check-all-nansha-son']").each(function (i, d) {
                    if (!d.checked) {
                        checkAll = false;
                    }
                });
                if (checkAll) {
                    $("input[name='check-all-nansha']").attr("checked", "checked");
                } else {
                    $("input[name='check-all-nansha']").attr("checked", false);
                }
                that.getGoodsIdPost('check-all-nansha');
            });
            //宁波仓商品单个勾选
            $("input[name='check-all-ningbo-son']").change(function () {
                var checkAll = true;
                $(this).parents("table").find("input[name='check-all-ningbo-son']").each(function (i, d) {
                    if (!d.checked) {
                        checkAll = false;
                    }
                });
                if (checkAll) {
                    $("input[name='check-all-ningbo']").attr("checked", "checked");
                } else {
                    $("input[name='check-all-ningbo']").attr("checked", false);
                }
                that.getGoodsIdPost('check-all-ningbo');
            });

            //提交勾选商品
            $('.J-submit-all').click(function () {
                var _this = $(this),
                    attrName = _this.attr('data-name'),
                    checkedList = that.getCheckedListFun(''+attrName+'-son');
                if (checkedList.length > 0) {
                    that.fromSubmit(attrName,checkedList);
                } else {
                    alert("请勾选商品");
                }
            });
        },
        getCheckedListFun:function (name) {
            var that = this,
            deleteList = [];
            $('input[name="'+name+'"]').each(function () {
                if($(this).attr('checked') == 'checked'){
                    deleteList.push($(this).attr('gid'));
                }
            });
            return deleteList;
        },
        deleteFun:function () {
            var that = this;
            $(".J-item-delete").click(function () {//每行td6后面的删除按钮
                that.goodGid = $(this).attr("gid");
                that.deleteConfirm([that.goodGid]);
            });
            $(".write-card i,.write-card .btn2,.body-mask").live("click", function () {//删除浮窗的取消按钮
                $.Jui._closeMasks();
                $(".write-card").remove();
            });

            $(".write-card .btn1").live("click", function () {//删除浮窗的确定按钮
                $.Jui._closeMasks();
                that.deleteGoods($(".write-card").data("gid"));
                $(".write-card").remove();
            });

            //删除多选一般的货物
            $("#delete_id").click(function () {
                that.deleteConfirm(that.getCheckedListFun('check-all-general-son'));
            });
            //删除多选南沙仓的货物
            $("#delete_id_ns").click(function () {
                that.deleteConfirm(that.getCheckedListFun('check-all-nansha-son'));
            });
            //删除多选宁波仓的货物
            $("#delete_id_ningbo").click(function () {
                that.deleteConfirm(that.getCheckedListFun('check-all-ningbo-son'));
            });
        },
        getGoodsIdPost: function (name) {
            var that = this,
                son_name = name + '-son';
                that.goodCheck = [];
                that.goodList = [];
            $('input[name="'+son_name+'"]:enabled').each(function (i, d) {
                if (d.checked) {
                    that.goodCheck.push(d.getAttribute("gid"));
                    that.goodList.push(d.getAttribute("gilist"));
                }
            });
            $.post("//www.kaluli.com/order/getCartAllPrice", {data: that.goodCheck}, function (data) {
                if (data.status * 1 == 0) {
                    $('.J-'+name+'-1').html(data.data.total_count);//商品个数
                    $('.J-'+name+'-2').html("￥" + data.data.total_price);//实付款
                    $('.J-'+name+'-3').html(data.data.total_duty_fee);//进口税预计
                    if (name == 'check-all-nansha' && data.data.total_price >= 2000 || name == 'check-all-ningbo'&& data.data.total_price >= 2000 ) {
                        $('.J-warning-'+name+'').show();
                        $('.J-submit-all').each(function () {
                            if($(this).attr('data-name') == name){
                                $(this).attr('disabled','disabled').addClass('input-false');
                            }
                        });
                    } else {
                        $('.J-warning-'+name+'').hide();
                        $('.J-submit-all').each(function () {
                            if($(this).attr('data-name') == name){
                                $(this).removeAttr('disabled').removeClass('input-false');
                            }
                        });

                    }
                    if ("undefined" == typeof data.data.original_price) {
                        $('.J-'+name+'-4').html("￥0");
                    } else {
                        $('.J-'+name+'-4').html("￥" + data.data.original_price);
                    }

                    if ("undefined" == typeof data.data.activity) {
                        $('.J-discount-'+name+'').text(0);
                        $('.J-'+name+'-5').html("￥0");
                        $(".go-buy").addClass('noactivity');
                        $(".goto-prolist").show();
                        $('.td5 .J-one-'+name+'').show();
                        $('.td5 .J-two-'+name+'').hide();
                        $('.J-activity-info-'+name+' span').removeClass('on');
                    } else {
                        console.log(JSON.stringify(data.data.activity));
                        var activityArr = data.data.activity.goods_info;
                        $('.J-'+name+'-5').html("￥" + data.data.activity.activity_save);
                        $('.J-discount-'+name+'').html(data.data.activity.activity_save);
                        $(".goto-prolist").show();
                        for (var type in data.data.activity.activity) {
                            if (data.data.activity.activity[type].activity_save) {
                                $('.J-discount-'+name+'', '#discount-' + type).text(data.data.activity.activity[type].activity_save);
                            } else {
                                $('.J-discount-'+name+'', '#discount-' + type).text(0);
                            }
                        }
                        $(".J-activity-info-"+name+" span").attr({
                            "class": ""
                        });
                        for (var objname in activityArr) {
                            for (var i = 0; i < activityArr[objname].activity.length; i++) {
                                if (activityArr[objname].activity[i].flag) {
                                    $(".J-activity-info-"+name+" span:eq(" + i + ")", ".goods-id-" + objname).attr({
                                        "class": "on"
                                    });
                                    $(".goto-prolist", ".goods-id-" + objname).eq(i).hide();
                                    $('.goods-id-'+objname +' .td5').find('.J-one-'+name+'').hide();
                                    $('.goods-id-'+objname +' .td5').find('.J-two-'+name+'').show();
                                }else{
                                    $('.goods-id-'+objname +' .td5').find('.J-one-'+name+'').show();
                                    $('.goods-id-'+objname +' .td5').find('.J-two-'+name+'').hide();
                                }
                            }
                        }
                        // $(".border-left").addClass('show');
                        //$(".go-buy").removeClass('noactivity');
                    }

                } else {
                    alert(data.msg);
                }
            }, "json");
        },
        addGoods: function (datas, obj) {
            var that = this;
            $.post("//www.kaluli.com/order/addCartNumber", datas, function (data) {
                if (data.status * 1 == 0) {
                    var line = '';
                    // alert(data.data.activity_type);
                    if(data.data.activity_type == 3 ) {  //3是单件
                        if (data.data.discount_rate == 1) {
                             line = '<p>￥' + data.data.total_price.toFixed(2) + '</p>' +
                                '<p>进口税预计:<span class="dutyFee">' + data.data.dutyFee + '</span>元</p>';
                        } else {
                            if (data.data.activity_flag == 0) {
                                 line = '<p>￥' + data.data.total_price.toFixed(2) + '</p>' +
                                    '<p>进口税预计:<span class="dutyFee">' + data.data.dutyFee + '</span>元</p>';
                            } else {
                                var total_discount_price = data.data.total_price * data.data.discount_rate / 10;
                                 line = '<p style="color: #999;font-size: 12px;text-decoration: line-through">¥' + data.data.total_price.toFixed(2) + '</p>' +
                                    '<p>￥' + total_discount_price.toFixed(2) + '</p>' +
                                    '<p>进口税预计:<span class="dutyFee">' + data.data.dutyFee + '</span>元</p>';
                            }
                        }
                    }else if(data.data.activity_type == 2){ //2是多件
                        // if (data.data.discount_rate != 1) {
                        //     var total_discount_price = data.data.total_price * data.data.discount_rate / 10;
                        //     line = '<div class="one-activity">' +
                        //         '<p>￥' + data.data.total_price.toFixed(2) + '</p>' +
                        //         '<p>进口税预计:<span class="dutyFee">' + data.data.dutyFee + '</span>元</p>' +
                        //         '</div>' +
                        //         '<div class="two-activity" style="display: none">' +
                        //         '<p style="color: #999;font-size: 12px;text-decoration: line-through">¥' + data.data.total_price.toFixed(2) + '</p>' +
                        //         '<p>￥' + total_discount_price.toFixed(2) + '</p>' +
                        //         '<p>进口税预计:<span class="dutyFee">' + data.data.dutyFee + '</span>元</p>'
                        //         '</div>'
                        // }
                        if (data.data.discount_rate == 1) { // discount_rate 是否是折扣
                            line = '<p>￥' + data.data.total_price.toFixed(2) + '</p>' +
                                '<p>进口税预计:<span class="dutyFee">' + data.data.dutyFee + '</span>元</p>';
                        } else {
                            if (data.data.activity_flag == 0) {
                                line = '<p>￥' + data.data.total_price.toFixed(2) + '</p>' +
                                    '<p>进口税预计:<span class="dutyFee">' + data.data.dutyFee + '</span>元</p>';
                            } else {
                                var total_discount_price = data.data.total_price * data.data.discount_rate / 10;
                                line = '<p style="color: #999;font-size: 12px;text-decoration: line-through">¥' + data.data.total_price.toFixed(2) + '</p>' +
                                    '<p>￥' + total_discount_price.toFixed(2) + '</p>' +
                                    '<p>进口税预计:<span class="dutyFee">' + data.data.dutyFee + '</span>元</p>';
                            }
                        }
                    }else{   //0没有折扣
                        line = '<p>￥' + data.data.total_price.toFixed(2) + '</p>' +
                            '<p>进口税预计:<span class="dutyFee">' + data.data.dutyFee + '</span>元</p>';
                    }
                    obj.html(line);
                    that.getGoodsIdPost(datas.isNanSha);
                }else{
                    alert(data.msg);
                }
            }, "json");
        },
        deleteConfirm: function (gid) {
            var str = '<div class="write-card">\
                      <div class="title"><i></i>提示</div>\
                      <div class="inner">\
                          <div class="inner-html clearfix">\
                               <div class="left">\
                                  <img src="/images/trade/ucenter/gt.jpg" />\
                               </div>\
                               <div class="right">\
                                  <div class="h2">删除商品?</div>\
                                  <div class="btn-span">\
                                      <span class="btn1">确定</span><span class="btn2">取消</span>\
                                  </div>\
                               </div>\
                          </div>\
                      </div>\
                  </div>';
            $.Jui._showMasks(0.6);
            $(str).appendTo('body');
            $(".write-card").css({
                left: $.Jui._position($(".write-card"))[0],
                top: $.Jui._position($(".write-card"))[1] - $.Jui._getpageScroll()
            }).show();
            $(".write-card").data("gid", gid);
        },
        deleteGoods: function (da) {
            var that = this;
            $.post("//www.kaluli.com/order/deleteCart", {data: da}, function (data) {
                if (data.status * 1 == 0) {
                    $(".price-id-3").html(data.data);
                    for (var i = 0; i < da.length; i++) {
                        var setObj = $("#cartpro_list tbody");
                        $(".goods-id-" + da[i]).remove();
                        if ($("#cartpro_list tbody").find("tr").length < 1) {
                            location.reload();
                        }
                    }
                    that.getGoodsIdPost();
                    $(".cart-box .cartnum").html(data.data);
                    //if (data.data == 0) {
                        window.location.reload();
                    //}
                }
            }, "json");
        },
        fromSubmit: function (name,list) {
            var str = '';
            if (this.submitUp) {
                return false;
            }
            this.submitUp = true;
            $('.J-submit-'+name+'').addClass('input-false').val("提交中...");
            for (var i = 0; i < list.length; i++) {
                str += (i == 0 ? "" : ",") + list[i];
            }
            $('.J-'+name+'-form').html('<input type="hidden" value="' + str + '" name="data" />').submit();
        }
    };
    return cartUtil;
});