/**
 * Created by jiangyanghe on 16/12/19.
 */
define(function() {
    "use strict";
    // console.log(JSON.stringify(sku));
    var SKUResult = {},//保存最后的组合结果信息
        defaultstock,
        defaultprice,//保存默认原价
        defaultsaleprice,//保存默认卡路里价格
        defaultDelivery=[],
        defaultDeliverynote = [],
        defaultDiscount;

    if (!Array.prototype.indexOf)
    {
        Array.prototype.indexOf = function(elt /*, from*/)
        {
            var len = this.length >>> 0;
            var from = Number(arguments[1]) || 0;
            from = (from < 0)
                ? Math.ceil(from)
                : Math.floor(from);
            if (from < 0)
                from += len;
            for (; from < len; from++)
            {
                if (from in this &&
                    this[from] === elt)
                    return from;
            }
            return -1;
        };
    }

    /**
     * 获取字节数
     * split();返回一个数组
     * charCodeAt();方法返回0到65535之间的整数，代表索引处字符的UTF-16编码单元
     */
    function len(s) {
        var l = 0;
        var a = s.split("");
        for (var i=0;i<a.length;i++) {
            if (a[i].charCodeAt(0)<299) {
                l++;
            } else {
                l+=2;
            }
        }
        return l;
    }

    /**
     * 从数组中生成指定长度的组合
     * 方法: 先生成[0,1...]形式的数组, 然后根据0,1从原数组取元素，得到组合数组
     */
    function combInArray(aData) {
        if (!aData || !aData.length) {
            return [];
        }

        var len = aData.length;
        var aResult = [];

        for (var n = 1; n < len; n++) {
            var aaFlags = getCombFlags(len, n);
            while (aaFlags.length) {
                var aFlag = aaFlags.shift();
                var aComb = [];
                for (var i = 0; i < len; i++) {
                    aFlag[i] && aComb.push(aData[i]);
                }
                aResult.push(aComb);
            }
        }

        return aResult;
    }


    /**
     * 得到从 m 元素中取 n 元素的所有组合
     * 结果为[0,1...]形式的数组, 1表示选中，0表示不选
     */
    function getCombFlags(m, n) {
        if (!n || n < 1) {
            return [];
        }

        var aResult = [];
        var aFlag = [];
        var bNext = true;
        var i, j, iCnt1;

        for (i = 0; i < m; i++) {
            aFlag[i] = i < n ? 1 : 0;
        }

        aResult.push(aFlag.concat());

        while (bNext) {
            iCnt1 = 0;
            for (i = 0; i < m - 1; i++) {
                if (aFlag[i] == 1 && aFlag[i + 1] == 0) {
                    for (j = 0; j < i; j++) {
                        aFlag[j] = j < iCnt1 ? 1 : 0;
                    }
                    aFlag[i] = 0;
                    aFlag[i + 1] = 1;
                    var aTmp = aFlag.concat();
                    aResult.push(aTmp);
                    if (aTmp.slice(-n).join("").indexOf('0') == -1) {
                        bNext = false;
                    }
                    break;
                }
                aFlag[i] == 1 && iCnt1++;
            }
        }
        return aResult;
    }

    function initsku(jsonobj, opt) {
        this.option = opt === void 0 ? "" : opt;
        this.sku = jsonobj;//获取PHP sku对象
        console.log(JSON.stringify(this.sku));
        this.s_detail = this.sku.detail;
        this.s_attr = this.sku.attr;
    }
    initsku.prototype = {
        defaults: {
            sort: ".sku-content",
            sortelem: ".sku",
            saleprice: "#saleprice",//特卖价格
            originalPrice: "#originalPrice",//卡路里原格
            stock: "#stock",//库存
            storehouse_name:"#delivery_area",//发货地
            discount:".kaluli-discount",//打折
            select: "cur",
            unbindselect:'unclick'
        },
        init: function() {
            var t = this;
            defaultsaleprice = $(t.defaults.saleprice).text();
            $(".numbox-text").attr("maxNum",$(t.defaults.stock).text());
            defaultstock=$(t.defaults.stock).text() == 0 ? 0 : ($(t.defaults.stock).text() > 5 ? "有货" : $(t.defaults.stock).text());//保存默认库存
            defaultprice = $(t.defaults.originalPrice).text();//卡路里原价
            defaultDiscount = $(t.defaults.discount).text();


            $.extend(t.defaults, t.option);
            t.doResult();
            t.creatsort();
            $(t.defaults.sortelem).each(function() {
                var self = $(this);
                var attr_id = self.attr('data-value');
                if (!SKUResult[attr_id]) {
                    self.addClass(t.defaults.unbindselect);
                }
            }).live("click",function(){
                var self = $(this),
                    userselectnum = parseInt($("#numbox-text").attr("value"));
                if(self.hasClass(t.defaults.unbindselect)){
                    return false
                }
                // $(".error-msg .busnum").removeClass("show");
                $(".error-msg-sku").removeClass("show");
                //选中自己，兄弟节点取消选中
                self.toggleClass(t.defaults.select).siblings().removeClass(t.defaults.select);

                //已经选择的节点
                var selectedObjs = $('.'+t.defaults.select);
                if (selectedObjs.length) {
                    //获得组合key价格
                    var selectedIds = [];
                    selectedObjs.each(function() {
                        selectedIds.push($(this).attr('data-value'));
                    });
                    selectedIds.sort(function(value1, value2) {
                        return parseInt(value1) - parseInt(value2);
                    });

                    var len = selectedIds.length;
                    var prices = SKUResult[selectedIds.join(';')].prices;
                    var maxPrice = Math.max.apply(Math, prices);
                    var minPrice = Math.min.apply(Math, prices);
                    var discountPrice = SKUResult[selectedIds.join(';')].discountPrices;
                    var discountmaxPrice = Math.max.apply(Math, discountPrice);
                    var discountminPrice = Math.min.apply(Math, discountPrice);
                    var stocks = SKUResult[selectedIds.join(';')].stock;
                    var discount = SKUResult[selectedIds.join(';')].discount;
                    var discountmax = Math.max.apply(Math, discount);
                    var discountmin = Math.min.apply(Math, discount);
                    var itemId = SKUResult[selectedIds.join(';')].itemId;
                    var skuId = SKUResult[selectedIds.join(';')].skuId;
                    var status = SKUResult[selectedIds.join(';')].status;
                    var storehouse_name = SKUResult[selectedIds.join(';')].storehouse_name;
                    var storehouse_note = SKUResult[selectedIds.join(';')].storehouse_note;
                    userselectnum > stocks && stocks != 0? $(".error-msg .busnum").addClass("show") : $(".error-msg .busnum").removeClass("show");



                    stocks == 0 ? stocks = 0 : (stocks > 5 ? stocks = "有货" : stocks = "紧张");
                    $(t.defaults.stock).text(stocks);
                    // $(t.defaults.originalPrice).text(maxPrice > minPrice ? minPrice + "-" + maxPrice : maxPrice);
                    // $(t.defaults.saleprice).text(discountmaxPrice > discountminPrice ? discountminPrice + "-" + discountmaxPrice : discountmaxPrice);
                    // $(t.defaults.discount).text((discountmax > discountmin ? discountmin + "-" + discountmax : discountmax)+"折");
                    $(t.defaults.sort).attr("data-itemid",itemId);
                    $(t.defaults.sort).attr("data-skuid",skuId);
                    $(t.defaults.sort).attr("data-status",status);

                    //用已选中的节点验证待测试节点 underTestObjs
                    $(t.defaults.sortelem).not(selectedObjs).not(self).each(function() {
                        var siblingsSelectedObj = $(this).siblings('.'+t.defaults.select);
                        var testAttrIds = []; //从选中节点中去掉选中的兄弟节点
                        if (siblingsSelectedObj.length) {
                            var siblingsSelectedObjId = siblingsSelectedObj.attr('data-value');
                            for (var i = 0; i < len; i++) {
                                (selectedIds[i] != siblingsSelectedObjId) && testAttrIds.push(selectedIds[i]);
                            }
                        } else {
                            testAttrIds = selectedIds.concat();
                        }
                        testAttrIds = testAttrIds.concat($(this).attr('data-value'));
                        testAttrIds.sort(function(value1, value2) {
                            return parseInt(value1) - parseInt(value2);
                        });
                        if (!SKUResult[testAttrIds.join(';')]) {
                            $(this).addClass(t.defaults.unbindselect).removeClass(t.defaults.select);
                        } else {
                            $(this).removeClass(t.defaults.unbindselect);
                        }
                    });

                } else {
                    //设置默认属性                
                    $(t.defaults.originalPrice).text(defaultprice);
                    $(t.defaults.saleprice).text(defaultsaleprice);
                    $(t.defaults.stock).text(defaultstock);
                    $(t.defaults.discount).text(defaultDiscount);
                    console.log(defaultDiscount+'+++++++++++++');
                    // $(t.defaults.storehouse_name).text("");
                    $(t.defaults.sort).attr("data-itemid","");
                    $(t.defaults.sort).attr("data-skuid","");
                    $(t.defaults.sort).attr("data-status","");
                    // $(t.defaults.storehouse_name).html(defaultDelivery.join("/"));
                    // alert(defaultDelivery);
                    defaultDeliverynote.length > 0 ?  $(".free-tax").text(defaultDeliverynote[0]).show() : $(".free-tax").hide();

                    //设置属性状态
                    $(t.defaults.sortelem).each(function() {
                        SKUResult[$(this).attr('data-value')] ? $(this).removeClass(t.defaults.unbindselect) : $(this).addClass(t.defaults.unbindselect).removeClass(t.defaults.select);
                    });
                }

                if( $(t.defaults.stock).text() == 0 ||  $(t.defaults.sort).attr("data-status") == 1){
                    $(".button").addClass('none-btn').text('已抢完');
                    // $(".none-btn").css("display","block");
                }else{
                    $(".button").removeClass('none-btn').text('立即抢购');
                    // $(".none-btn").hide();
                    // $(".buy-btn").css("display","block");
                }
                if($(t.defaults.sort).attr("data-status") == 1){
                    $("#stock").text(0)
                }
                $(".sku").each(function(){
                    var q = $(this).attr("data-status");
                    var w = $(this).attr("data-stock");
                    if(q == 1 || w == 0){
                        $(this).addClass("unclick")
                    }
                })
            })
        },
        creatsort: function() {
            var t = this,
                dom = "";

            $.each(t.s_attr, function(i, item) { //循环打印所有的规格
                var thisitem = t.s_attr[i],
                    itemhead = len(thisitem.name) > 9 ?  thisitem.name.substring(0,9) : thisitem.name,
                    l = 0;

                //console.log(i,item);                
                dom += '<div class="grid sort">';
                dom += '<div class="title">' + itemhead + '</div>';
                dom += '<ul class="clearfix">';
                // alert(thisitem.data.length);
                for (var key in thisitem.data) {
                    // console.log(thisitem.flag+'<br>~~~~~~~');
                    //判断缺货
                    for(var key1 in t.s_detail){
                        if(key1.indexOf(";")>0){
                            var unableclick = 1
                        }else{
                            var unableclick = 0
                        }
                    }
                    // console.log(thisitem.flag+'<br>~~~~~~~');
                    var data_status = "data-status="+ t.s_detail[key].status;
                    var data_stock = "data-stock="+ t.s_detail[key].stock;
                    var data_status_value = t.s_detail[key].status;
                    var data_status_stock = t.s_detail[key].stock;
                    if(thisitem.flag == 1){
                        var skuId,itemId;
                        $.each(t.s_detail,function(i,item){
                            itemId = t.s_detail[i].itemId;
                            skuId = t.s_detail[i].skuId;
                        });
                        if(unableclick == 0){
                            if(data_status_value == 1 || data_status_stock == 0){
                                dom += '<li class="sku unclick data-single" data-single-itemid="'+ itemId +'" data-single-skuId="'+ skuId +'"  data-value=' + thisitem.data[key].alias + '>' + thisitem.data[key].name + '</li>';
                                l++;
                            }
                        }else{
                            dom += '<li class="sku cur data-single" data-single-itemid="'+ itemId +'" data-single-skuId="'+ skuId +'"  data-value=' + thisitem.data[key].alias + '>' + thisitem.data[key].name + '</li>';
                            l++;
                        }
                        // dom += '<li class="sku cur data-single" data-single-itemid="'+ itemId +'" data-single-skuId="'+ skuId +'"  data-value=' + thisitem.data[key].alias + '>' + thisitem.data[key].name + '</li>';
                    }else{
                        if(unableclick == 0){
                            if(data_status_value == 1 || data_status_stock == 0){
                                dom += '<li class="sku unclick' + '" data-value="' + thisitem.data[key].alias + '" data-cc="'+thisitem.flag +'"'+ data_status+ ' '+ data_stock +'>' + thisitem.data[key].name +'</li>';
                                l++;
                            }else{
                                dom += '<li class="sku' + '" data-value="' + thisitem.data[key].alias + '" data-cc="'+thisitem.flag +'"'+ data_status +'>' + thisitem.data[key].name +'</li>';
                                l++;
                            }
                        }else{
                            dom += '<li class="sku" data-value=' +  thisitem.data[key].alias + '>' + thisitem.data[key].name + '</li>';
                            l++;
                        }
                        // dom += '<li class="sku" data-value=' +  thisitem.data[key].alias + '>' + thisitem.data[key].name + '</li>';
                    }
                    l++;
                }
                dom += '</ul>';
                dom += '</div>';
            });
            var count = 0,defaultstatus;
            $.each(t.s_detail,function(i,item){
                count++;
                defaultstatus = t.s_detail[i].status;//status =0 上架,1下架
                defaultDelivery.indexOf(item.storehouse_name) < 0 && defaultDelivery.push(item.storehouse_name);//????
                defaultDeliverynote.indexOf(item.storehouse_note) < 0 && item.storehouse_note !=""  && defaultDeliverynote.push(item.storehouse_note);
            });

            if(count == 1 && defaultstatus == 1){//有无库存
                $("#stock").text(0);
                $(".buy-btn,.cart-btn").hide();
                $(".none-btn").css("display","block");
            }else{
                //详情页右侧大图左上角显示的发货地;发货地
                // $("#delivery_area").html(defaultDelivery.join("/"));
                // if(defaultDelivery.join("/").indexOf("香港") >=0 ){
                //     $(t.defaults.storehouse_name).html(defaultDelivery.join("/")+' 16:00前付款，预计10个工作日送达');
                //     $('#policy').text('重要提示：根据海关新政，单笔订单超过1000元无法报关');
                //     $(".free-tax").text("香港直邮").show();
                // }
                // if(defaultDelivery.join("/").indexOf("郑州") >=0 ){
                //     $(t.defaults.storehouse_name).html(defaultDelivery.join("/")+' 16:00前付款，预计7个工作日送达');
                //     $(".free-tax").text("郑州保税").show();
                // }
                // if(defaultDelivery.join("/").indexOf("南沙") >=0 ){
                //     $(t.defaults.storehouse_name).html(defaultDelivery.join("/")+' 16:00前付款，预计7个工作日送达');
                //     $('#policy').text('重要提示：根据海关新政，单笔订单超过2000元无法报关');
                //     $(".free-tax").text("南沙保税").show();
                // }else {//上海
                //     $(t.defaults.storehouse_name).html(defaultDelivery.join("/")+' 16:00前付款，预计4个工作日送达');
                // }
            }

            $(t.defaults.sort).append(dom);
            $(t.defaults.stock).text(defaultstock).show();
        },
        doResult: function() { //初始化得到结果集
            var t = this;
            var i, j, skuKeys = t.getObjKeys(t.s_detail);

            for (i = 0; i < skuKeys.length; i++) {
                var skuKey = skuKeys[i]; //一条SKU信息key
                var sku = t.s_detail[skuKey]; //一条SKU信息value
                var skuKeyAttrs = skuKey.split(";"); //SKU信息key属性值数组

                skuKeyAttrs.sort(function(value1, value2) {
                    return parseInt(value1) - parseInt(value2);
                });

                //对每个SKU信息key属性值进行拆分组合
                var combArr = combInArray(skuKeyAttrs);

                for (j = 0; j < combArr.length; j++) {
                    t.add2SKUResult(combArr[j], sku);
                }

                //结果集接放入SKUResult
                SKUResult[skuKeyAttrs.join(";")] = {
                    stock: sku.stock,
                    prices: [sku.price],
                    discount:[sku.discount],
                    discountPrices: [sku.discountPrice],
                    status: sku.status,
                    itemId: sku.itemId,
                    skuId: sku.skuId,
                    storehouse_name:[sku.storehouse_name],
                    storehouse_note:[sku.storehouse_note]
                }
            }
        },
        getObjKeys: function(obj) { //获得对象的key
            if (obj !== Object(obj)) throw new TypeError('Invalid object');
            var keys = [];
            for (var key in obj)
                if (Object.prototype.hasOwnProperty.call(obj, key))
                    keys[keys.length] = key;
            return keys;
        },
        add2SKUResult: function(combArrItem, sku) { //把组合的key放入结果集SKUResult
            var key = combArrItem.join(";");
            if (SKUResult[key]) { //SKU信息key属性
                SKUResult[key].stock = parseInt(SKUResult[key].stock) + parseInt(sku.stock);
                SKUResult[key].prices.push(sku.price);
                SKUResult[key].discount.push(sku.discount);
                SKUResult[key].discountPrices.push(sku.discountPrice);
                SKUResult[key].storehouse_name.push(sku.storehouse_name);
                SKUResult[key].storehouse_note.push(sku.storehouse_note);
            } else {
                SKUResult[key] = {
                    stock: sku.stock,
                    prices: [sku.price],
                    discount:[sku.discount],
                    discountPrices: [sku.discountPrice],
                    storehouse_name:[sku.storehouse_name],
                    storehouse_note:[sku.storehouse_note],
                    status: sku.status,
                    itemId: sku.itemId,
                    skuId: sku.skuId
                };
            }
        }
    };

    return initsku
})