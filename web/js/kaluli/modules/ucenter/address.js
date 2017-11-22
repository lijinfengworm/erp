define(function () {
    var address = {
        Verification: {
            name: false,
            phone: false,
            tell: false,
            //address:false,
            detailed: false,
            postal: false
        },
        init: function (fn) {
            this.bindFun();
            this.cityAdd();
        },
        bindFun: function () {
            var phone = /^1[34578][0-9]{9}$/,
                phonesection = /^[0-9]{3,6}$/,
                phonecode = /^[0-9]{5,10}$/,
                phoneext = /^[0-9]{0,5}$/,
                that = this,
                iserror = false;

            function checkPhone() {
                var valphone1 = $("input[name='phonesection']").data("check"),
                    valphone2 = $("input[name='phonecode']").data("check"),
                    valphone3 = $("input[name='phoneext']").data("check");
                if (valphone1 && valphone2) {
                    if (valphone3 == false) {
                        that.Verification.tell = false;
                    } else {
                        that.Verification.tell = true;
                    }
                } else {
                    that.Verification.tell = false;
                }
            }

            $("input[name='name']").blur(function () {
                var val = $(this).val();
                if ($.trim(val) == "") {
                    $(this).tips("请填写姓名", {
                        left: $(this).offset().left + $(this).outerWidth() + 10,
                        top: $(this).offset().top
                    });
                    that.Verification["name"] = false;
                    iserror = true;
                } else {
                    that.Verification["name"] = true;
                    iserror = false;
                }
            });

            $("input[name='card']").blur(function () {
                var val = $(this).val();
                var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
                if (val.length > 1) {
                    if (reg.test(val) === false) {
                        $(this).tips("请填写正确的身份证", {
                            left: $(this).offset().left + $(this).outerWidth() + 10,
                            top: $(this).offset().top
                        });
                        iserror = true;
                        that.Verification["card"] = false;
                    } else {
                        that.Verification["card"] = true;
                        iserror = false;
                    }
                } else {
                    that.Verification["card"] = true;
                    iserror = false;
                }

            });

            $("input[name='phone']").blur(function () {
                var val = $(this).val();
                if (!phone.test(val)) {
                    $(this).tips("请填写正确的手机号码", {
                        left: $(this).offset().left + $(this).outerWidth() + 10,
                        top: $(this).offset().top
                    });
                    iserror = true;
                    that.Verification["phone"] = false;
                } else {
                    that.Verification["phone"] = true;
                    iserror = false;
                }
            });

            $("input[name='phonesection']").blur(function () {
                var val = $(this).val();
                if (!phonesection.test(val) && $.trim(val) != "") {
                    $(this).tips("区号必须为3到6位数字", {
                        left: $(this).offset().left + $(this).outerWidth() + 10,
                        top: $(this).offset().top
                    });
                    $(this).data("check", false);
                    iserror = true;
                } else {
                    $(this).data("check", true);
                    iserror = false;
                }
                checkPhone();
            });

            $("input[name='phonecode']").blur(function () {
                var val = $(this).val();
                if (!phonecode.test(val) && $.trim(val) != "") {
                    $(this).tips("电话必须为5到10位数字", {
                        left: $(this).offset().left + $(this).outerWidth() + 10,
                        top: $(this).offset().top
                    });
                    $(this).data("check", false);
                    iserror = true;
                } else {
                    $(this).data("check", true);
                    iserror = false;
                }
                checkPhone();
            });

            $("input[name='phoneext']").blur(function () {
                var val = $(this).val();
                if (!phoneext.test(val) && $.trim(val) != "") {
                    $(this).tips("电话分机必须少于6个数字", {
                        left: $(this).offset().left + $(this).outerWidth() + 10,
                        top: $(this).offset().top
                    });
                    $(this).data("check", false);
                    iserror = true;
                } else {
                    $(this).data("check", true);
                    iserror = false;
                }
                checkPhone();
            });

            $("input[name='detailed']").blur(function () {
                var val = $(this).val();
                if ($.trim(val) == "") {
                    $(this).tips("请填写详细地址", {
                        left: $(this).offset().left + $(this).outerWidth() + 10,
                        top: $(this).offset().top
                    });
                    iserror = true;
                    that.Verification["detailed"] = false;
                } else {
                    that.Verification["detailed"] = true;
                    iserror = false;
                }
            });

            $("input[name='address']").change(function (event) {
                priceValue.address_id = $(this).val() * 1;
                getPrice.getJson();
            });

            $(".address-editor").find(".save").click(function (event) {
                $(".address-editor").find("input[type=text]").trigger('blur');
                var $this = $(this),
                    id = $this.parent().attr("data-value"),
                    nameVal = $("input[name='name']").val(),
                    identity_number = $("input[name=card]").val(),
                    mobileVal = $("input[name='phone']").val(),
                    phonesectionVal = $("input[name='phonesection']").val(),
                    phonecodeVal = $("input[name='phonecode']").val(),
                    phoneextVal = $("input[name='phoneext']").val(),
                    provinceVal = $(".sel-1").val(),
                    cityVal = $(".sel-2").val(),
                    areaVal = $(".sel-3").val(),
                    streetVal = $("input[name='detailed']").val(),
                    postcodeVal = '000000';
                if ($("input[name='defaultflag']").attr("checked") == "checked") {
                    defaultflagVal = 1
                } else {
                    defaultflagVal = 0
                }

                if (!that.Verification["name"]) {
                    $("input[name='name']").blur();
                    return false;
                }

                if (!that.Verification["card"]) {
                    $("input[name='card']").blur();
                    return false;
                }

                if (!that.Verification["phone"]) {
                    $("input[name='phone']").blur();
                    return false;
                }

                if ($.trim($("input[name='phonesection']").val()) != "" && $.trim($("input[name='phonecode']").val()) != "" && $.trim($("input[name='phoneext']").val()) != "") {
                    if (!that.Verification["tell"]) {
                        $("input[name='phonecode']").tips("请正确填写电话号码", {
                            left: $("input[name='phonecode']").offset().left + $(this).outerWidth() + 10,
                            top: $("input[name='phonecode']").offset().top
                        });
                        return false;
                    }
                }

                if (!that.Verification["detailed"]) {
                    $("input[name='detailed']").blur();
                    return false;
                }

                var address = {
                    id: id,
                    identity_number: identity_number,
                    name: nameVal,
                    mobile: mobileVal,
                    phonesection: phonesectionVal,
                    phonecode: phonecodeVal,
                    phoneext: phoneextVal,
                    province: provinceVal,
                    city: cityVal,
                    area: areaVal,
                    street: streetVal,
                    postcode: postcodeVal,
                    defaultflag: defaultflagVal
                }
                if (provinceVal == "请选择" || cityVal == "请选择" || areaVal == "请选择") {
                    $(".sel-3").tips("请选择地区", {
                        left: $(".sel-3").offset().left + $(".sel-3").outerWidth() + 10,
                        top: $(".sel-3").offset().top
                    });
                    return false
                }
                $.post("//www.kaluli.com/api/editAddress", {address: address}, function (data) {
                    if ((-1) * data.code == 10) {
                        $.each(data.data.data, function (i, item) {
                            $this.tips(item);
                        });
                    } else {
                        var defaultflag,
                            nonetr = 1,
                            province = $(".sel-1").find("option:selected").text(),
                            city = $(".sel-2").find("option:selected").text(),
                            area = $(".sel-3").find("option:selected").text();
                        uw = address.identity_number.replace(/(\w)/g, function (a, b, c, d) {
                            return (c > 5 && c < 12) ? '*' : a
                        });
                        address.defaultflag == 1 ? ($(".user-detail tbody .td6").text("否"), defaultflag = "是") : defaultflag = "否";
                        if (id != "") {
                            nonetr = 1;
                        } else {
                            nonetr = 0;
                        }
                        var str, separator1, separator2;
                        address.phonesection != "" ? separator1 = "-" : separator1 = "";
                        address.phoneext != "" ? separator2 = "-" : separator2 = "";
                        if (nonetr != 1) str = '<tr data-value=' + data.data.data.id + '>';
                        str += '<td class="td1">' + address.name + '</td>';
                        str += '<td class="td2">' + province + '&nbsp;' + city + '&nbsp;' + area + '&nbsp;' + address.street + '</td>';
                        str += '<td class="td4">' + address.mobile + '<br />' + address.phonesection + '' + separator1 + '' + address.phonecode + '' + separator2 + '' + address.phoneext + '</td>';
                        str += '<td class="td5">' + uw + '</td>';
                        str += '<td class="td6">' + defaultflag + '</td>';
                        str += '<td class="td7"><span class="editor">修改</span>|<span class="delete">删除</span>|<span class="defaultflag">设为默认</span></td>';
                        if (nonetr != 1) str += '</tr>';
                        if (id != "") {
                            var index = $(".address-editor").attr("data-index");
                            $(".user-detail tbody tr:eq(" + index + ")").html(str);
                        } else {
                            $(".user-detail tbody").prepend(str);
                        }
                        that.clearAddress();
                        that.callback();
                    }
                    window.location.reload();
                }, "json");
            });

            $(".add_new_address").change(function () {
                $(".address-ul").show();
            });
        },
        callback: function () {

        },
        clearAddress: function () {
            $("input[name='name']").val("");
            $("input[name='card']").val("");
            $("input[name='phone']").val("");
            $("input[name='phonesection']").val("");
            $("input[name='phonecode']").val("");
            $("input[name='phoneext']").val("");
            $("input[name='detailed']").val("");
            $("input[name='postal']").val("");
            $("input[name='defaultflag']").removeAttr("checked");
            $(".sel-1").val("请选择");
            $(".sel-2").html('<option>请选择</option>');
            $(".sel-3").html('<option>请选择</option>');
            $(".address-editor").attr({"data-value": "", "data-index": ""});
        },
        cityAdd: function () {
            var obj = $(".select_city"),
                that = this;

            obj.find(".sel-1").change(function (event) {
                var val = $(this).val();
                $(".select_city").find(".sel-2").html('<option value="0">请选择</option>');
                $(".select_city").find(".sel-3").html('<option value="0">请选择</option>');
                if (val == "请选择") {
                    return false
                }
                $.get("//www.kaluli.com/api/getNextRegionById?id=" + val, function (data) {
                    that.cityAddstr(data, $(".select_city").find(".sel-2"));
                }, "json");
            });

            obj.find(".sel-2").change(function (event) {
                var val = $(this).val();
                if (val == "请选择") {
                    return false
                }
                $.get("//www.kaluli.com/api/getNextRegionById?id=" + val, function (data) {
                    that.cityAddstr(data, $(".select_city").find(".sel-3"));
                }, "json");
            });
        },
        cityAddstr: function (o, d) {
            var str = ['<option>请选择</option>'];
            for (var i = 0, len = o.data.list.length; i < len; i++) {
                str.push('<option value="' + o.data.list[i].region_id + '">' + o.data.list[i].region_name + '</option>');
                str.join('');
            }
            d.html(str);
        }
    }

    return address
})