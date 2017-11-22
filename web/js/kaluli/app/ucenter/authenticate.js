/**
 * Created by jiangyanghe on 17/4/14.
 */
requirejs.config({
    baseUrl: "//kaluli.hoopchina.com.cn/js/kaluli/",
    paths: {
        "validate": "modules/common/validate"
    },
    urlArgs: 'v=' + ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});
require(["validate"], function (validate) {
    var AjaxLoding = true;
    var uid = $('input[name="uid"]').val();
    //获取实名认证
    $.post('//www.kaluli.com/api/purchaserAuth', {
        _act: 'get',
        _uid: uid
    }, function (data) {
        var datas = typeof data == "string" ? $.parseJSON(data) : data;
        if (datas.status == 1) {
            if (datas.data.length == 0) {//0 是未实名认证 1已实名认证
                $('#unauthenticate').show();
            } else {
                $('#authenticated').show();
                $('#authentication_name').text(datas.data.purchaser);
                $('#authentication_card_num').text(datas.data.card_number.replace(/(\d{3})\d{11}(\w{4})/, "$1***********$2"));
                $('input[name="purchaser_name_update"]').val(datas.data.purchaser);
                $('input[name="id_card_update"]').val(datas.data.card_number);
            }
        } else {
            alert('查询实名认证失败');
        }
    });

    //实名认证
    $('#authentication_btn').click(function () {
        AjaxLoding = true;
        var id_card = $('input[name="id_card"]').val(),
            purchaser = $('input[name="purchaser_name"]').val();
        if (!validate.isChineseName(purchaser)) {
            AjaxLoding = false;
            $('.J-error-msg').text('请填写正确的姓名').show();
            return
        }
        if (!validate.isIdCard(id_card)) {
            AjaxLoding = false;
            $('.J-error-msg').text('请填写正确的身份证').show();
            return
        }
        if (AjaxLoding) {
            $.post('//www.kaluli.com/api/purchaserAuth', {
                _act: 'add',
                _uid: uid,
                _purchaser: purchaser,
                _card_number: id_card
            }, function (data) {
                var datas = typeof data == "string" ? $.parseJSON(data) : data;
                if (datas.status == 1) {
                    $('.J-content-text').text('实名认证成功');
                    $(".successPop").show();
                    setTimeout(function () {
                        $(".successPop").hide();
                    },1500);
                    $('#unauthenticate').hide();
                    $('#authenticated').show();
                    $('#authentication_name').text(datas.data.purchaser);
                    $('#authentication_card_num').text(datas.data.card_number.replace(/(\d{3})\d{11}(\w{4})/, "$1***********$2"));
                    $('input[name="purchaser_name_update"]').val(datas.data.purchaser);
                    $('input[name="id_card_update"]').val(datas.data.card_number);
                } else {
                    $('.J-error-msg').text('添加实名认证失败').show();
                }
            });
        }
    });

    //修改认证
    $('#authentication_update_btn').click(function () {
        AjaxLoding = true;
        var purchaser = $('input[name="purchaser_name_update"]').val(),
            id_card = $('input[name="id_card_update"]').val();
        if (!validate.isChineseName(purchaser)) {
            AjaxLoding = false;
            $('.J-error-msg').text('请填写正确的姓名').show();
        }
        if (!validate.isIdCard(id_card)) {
            AjaxLoding = false;
            $('.J-error-msg').text('请填写正确的身份证').show();
        }
        if (AjaxLoding) {
            $.post('//www.kaluli.com/api/purchaserAuth', {
                _act: 'edit',
                _uid: uid,
                _purchaser: purchaser,
                _card_number: id_card
            }, function (data) {
                var datas = typeof data == "string" ? $.parseJSON(data) : data;
                if (datas.status == 1) {
                    $('.J-content-text').text('实名认证成功');
                    $(".successPop").show();
                    setTimeout(function () {
                        $(".successPop").hide();
                    },1500);
                    $('#update_authenticate').hide();
                    $('#authenticated').show();
                    $('#authentication_name').text(datas.data.purchaser);
                    $('#authentication_card_num').text(datas.data.card_number.replace(/(\d{3})\d{11}(\w{4})/, "$1***********$2"));
                    $('input[name="purchaser_name_update"]').val(datas.data.purchaser);
                    $('input[name="id_card_update"]').val(datas.data.card_number);
                } else {
                    $('.J-error-msg').text('添加实名认证失败').show();
                }
            });
        }
    });

    //正则表达式替换
    // var card_num = '320821199110275447';
    // console.log(card_num.replace(/(\d{3})\d{11}(\d{4})/, "$1***********$2"));

    $('#cancel_update_btn').click(function () {//取消修改
        $('#update_authenticate').hide();
        $('#authenticated').show();
    });
    $('#edit_authenticate_btn').click(function () {//修改实名登记
        $('#update_authenticate').show();
        $('#authenticated').hide();

    });
});