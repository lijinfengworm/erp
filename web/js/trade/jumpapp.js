$(document).ready(function() {
    $('body').on('click', 'a', function(e) {
        var ua = navigator.userAgent.toLowerCase();
        if ((ua.indexOf('android') > -1 || ua.indexOf('iphone') > -1)
            && ua.indexOf('shihuo/') != -1 && ua.indexOf('sc') != -1 ) {

            var purl = $(this).attr('href'),
                paramStr = purl.split('?url=');
            var paramUrl = paramStr[1] ? paramStr[1] : purl,
                relUrl = decodeURIComponent(paramUrl);
            // 优惠列表
            var youhuiListExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                youhuiListExp = /^http:\/\/www\.shihuo\.cn\/youhui(#*|#.*)$/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                youhuiListExp = /^http:\/\/m\.shihuo\.cn\/youhui(#*|#.*)$/i;
            }
            if (youhuiListExp) {
                var youhuiListRes = youhuiListExp.test(relUrl);
                if (youhuiListRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=youhuiList';
                    return false;
                }
            }
            // 优惠详情
            var youhuiExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                youhuiExp = /www\.shihuo\.cn\/youhui\/(\d+)\.html/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                youhuiExp = /m\.shihuo\.cn\/youhui\/(\d+)\.html/i;
            }
            if (youhuiExp) {
                var youhuiDetailRes = relUrl.match(youhuiExp);
                if (null != youhuiDetailRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=youhuiDetail&id=' + youhuiDetailRes[1];
                    return false;
                }
            }
            // 海淘首页
            var haitaoIndexExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                haitaoIndexExp = /^http:\/\/www\.shihuo\.cn\/haitao(#*|#.*)$/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                haitaoIndexExp = /^http:\/\/m\.shihuo\.cn\/haitao\/index(#*|#.*)$/i;
            }
            if (haitaoIndexExp) {
                var haitaoIndexRes = haitaoIndexExp.test(relUrl);
                if (haitaoIndexRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=haitaoIndex';
                    return false;
                }
            }
            // 海淘列表
            var haitaoListExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                haitaoListExp = /^http:\/\/www\.shihuo\.cn\/haitao\/youhui(#*|#.*)$/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                haitaoListExp = /^http:\/\/m\.shihuo\.cn\/haitao(#*|#.*)$/i;
            }
            if (haitaoListExp) {
                var haitaoListRes = haitaoListExp.test(relUrl);
                if (haitaoListRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=haitaoList';
                    return false;
                }
            }
            // 海淘详情
            var haitaoExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                haitaoExp = /www\.shihuo\.cn\/haitao\/youhui\/(\d+)\.html/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                haitaoExp = /m\.shihuo\.cn\/haitao\/youhui\/(\d+)\.html/i;
            }
            if (haitaoExp) {
                var haitaoDetailRes = relUrl.match(haitaoExp);
                if (null != haitaoDetailRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=haitaoDetail&id=' + haitaoDetailRes[1];
                    return false;
                }
            }
            // 代购列表
            var daigouListExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                daigouListExp = /^http:\/\/www\.shihuo\.cn\/haitao\/daigou(#*|#.*|\/p-.*)$/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                daigouListExp = /^http:\/\/m\.shihuo\.cn\/daigou(#*|#.*|\/p-.*)$/i;
            }
            if (daigouListExp) {
                var daigouListRes = daigouListExp.test(relUrl);
                if (daigouListRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=daigouList';
                    return false;
                }
            }
            // 代购详情
            var daigouExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                daigouExp = /www\.shihuo\.cn\/haitao\/buy\/([\d-]+)\.html/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                daigouExp = /m\.shihuo\.cn\/daigou\/([\d-]+)\.html/i;
            }
            if (daigouExp) {
                var daigouDetailRes = relUrl.match(daigouExp);
                if (null != daigouDetailRes) {
                    var idArr = daigouDetailRes[1],
                        idParam = idArr.split('-');
                    var pid = idParam[0];
                    var dgurl = 'shihuo://www.shihuo.cn?route=daigouDetail&pid=' + pid;
                    if (idParam.length > 1) {
                        dgurl += '&gid=' + idParam[1];
                    }
                    window.location.href = dgurl;
                    return false;
                }
            }
            // 团购列表
            var grouponListExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                grouponListExp = /^http:\/\/www\.shihuo\.cn\/tuangou(#*|#.*)$/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                grouponListExp = /^http:\/\/m\.shihuo\.cn\/tuangou(#*|#.*)$/i;
            }
            if (grouponListExp) {
                var daigouListRes = grouponListExp.test(relUrl);
                if (daigouListRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=grouponList';
                    return false;
                }
            }
            // 团购详情
            var grouponExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                grouponExp = /www\.shihuo\.cn\/tuangou\/(\d+)/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                grouponExp = /m\.shihuo\.cn\/tuangou\/(\d+)/i;
            }
            if (grouponExp) {
                var grouponDetailRes = relUrl.match(grouponExp);
                if (null != grouponDetailRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=grouponDetail&id=' + grouponDetailRes[1];
                    return false;
                }
            }
            // 运动鞋详情
            var shoeDExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                shoeDExp = /www\.shihuo\.cn\/detail\/(\d+)\.html/i;
            }
            if (shoeDExp) {
                var shoeDRes = relUrl.match(shoeDExp);
                if (null != shoeDRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=shoesDetail&id=' + shoeDRes[1];
                    return false;
                }
            }
            // 运动鞋列表
            var shoeListExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                shoeListExp = /^http:\/\/www\.shihuo\.cn\/shoe(#*|#.*)$/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                shoeListExp = /^http:\/\/m\.shihuo\.cn\/shoe(#*|#.*)$/i;
            }
            if (shoeListExp) {
                var shoeListRes = shoeListExp.test(relUrl);
                if (shoeListRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=shoesList';
                    return false;
                }
            }
            // 运动鞋详情
            var shoeExp;
            if (relUrl.indexOf('m.shihuo.cn') > -1) {
                shoeExp = /m\.shihuo\.cn\/shoe\/detail\/(\d+)\.html/i;
            }
            if (shoeExp) {
                var shoeDetailRes = relUrl.match(shoeExp);
                if (null != shoeDetailRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=shoesDetail&id=' + shoeDetailRes[1];
                    return false;
                }
            }
            // 晒物列表
            var shaiwuListExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                shaiwuListExp = /^http:\/\/www\.shihuo\.cn\/shaiwu(#*|#.*)$/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                shaiwuListExp = /^http:\/\/m\.shihuo\.cn\/shaiwu(#*|#.*)$/i;
            }
            if (shaiwuListExp) {
                var shaiwuListRes = shaiwuListExp.test(relUrl);
                if (shaiwuListRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=shaiwuList';
                    return false;
                }
            }
            // 晒物详情
            var shaiwuDetailExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                shaiwuDetailExp = /www\.shihuo\.cn\/shaiwu\/detail\/(\d+).html/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                shaiwuDetailExp = /m\.shihuo\.cn\/shaiwu\/detail\/(\d+)/i;
            }
            if (shaiwuDetailExp) {
                var shaiwuDetailRes = relUrl.match(shaiwuDetailExp);
                if (null != shaiwuDetailRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=shaiwuDetail&id=' + shaiwuDetailRes[1];
                    return false;
                }
            }
            // 优惠券列表
            var couponsListExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                couponsListExp = /^http:\/\/www\.shihuo\.cn\/coupons\/quan(#*|#.*)$/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                couponsListExp = /^http:\/\/m\.shihuo\.cn\/coupon(#*|#.*)$/i;
            }
            if (couponsListExp) {
                var couponsListRes = couponsListExp.test(relUrl);
                if (couponsListRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=youhuiquanList';
                    return false;
                }
            }
            // 优惠券详情
            var couponDetailExp;
            if (relUrl.indexOf('www.shihuo.cn') > -1) {
                couponDetailExp = /www\.shihuo\.cn\/duihuan\/(\d+).html/i;
            } else if (relUrl.indexOf('m.shihuo.cn') > -1) {
                couponDetailExp = /m\.shihuo\.cn\/coupon\/(\d+).html/i;
            }
            if (couponDetailExp) {
                var couponDetailRes = relUrl.match(couponDetailExp);
                if (null != couponDetailRes) {
                    window.location.href = 'shihuo://www.shihuo.cn?route=youhuiquanDetail&id=' + couponDetailRes[1];
                    return false;
                }
            }

            var versionExp = /shihuo\/([\d.]+)/i;
            var appVerRes = ua.match(versionExp);
            if (null != appVerRes) {
                var appVer = appVerRes[1];
                var ourl = $(this).data('original');
                if (typeof(ourl) != "undefined") {
                    if ((ourl.indexOf('item.taobao.com/item.htm') > -1) || (ourl.indexOf('detail.tmall.com/item.htm') > -1)
                        || (ourl.indexOf('item.tmall.com/item.htm') > -1) || (ourl.indexOf('detail.tmall.hk/hk/item.htm') > -1)
                        || (purl.indexOf('go.shihuo.cn/u?url') > -1)) {
                        if ((ua.indexOf('android') > -1 && ('3.0.0' <= appVer))
                            || (ua.indexOf('iphone') > -1 && ('3.3.0' <= appVer))) {
                            Jockey.send('buy', {url: "shihuo://www.shihuo.cn?route=go&url=" + encodeURIComponent(ourl)});
                            return false;
                        }
                    } else if ('3.3.0' <= appVer) {
                        if (ourl.indexOf('http://www.shihuo.cn') != -1 || (ourl.indexOf('http://m.shihuo.cn') != -1)) {
                            if ((ua.indexOf('iphone') > -1 && ('3.5.0' > appVer))) {
                                Jockey.send('action', {url: 'shihuo://www.shihuo.cn?webview=mobilesite&url=' + encodeURIComponent(ourl)});
                            } else {
                                Jockey.send('action', {url: ourl});
                            }
                            return false;
                        } else {
                            Jockey.send('action', {url: ourl});
                            return false;
                        }
                    }
                } else {
                    var isconvert = $(this).attr('isconvert');
                    if ('undefined' == isconvert || (null == isconvert)) {
                        var href = $(this).attr('href');
                        if ((href.indexOf('http://www.shihuo.cn') != -1) || (href.indexOf('http://m.shihuo.cn') != -1)) {
                            if ((ua.indexOf('iphone') > -1 && ('3.5.0' > appVer))) {
                                Jockey.send('action', {url: 'shihuo://www.shihuo.cn?webview=mobilesite&url=' + encodeURIComponent(href)});
                            } else {
                                Jockey.send('action', {url: href});
                            }
                            return false;
                        }
                    }
                }
            }
        }
    });
});
