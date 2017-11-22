/**
 * Created by jiangyanghe on 17/4/12.
 */
define(function(){
    "use strict";

    var validate = {
        isIdCard: function (ID) {
            var reg = /^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/;
            return reg.test(ID);
        },
        isChineseName: function (name) {
            var reg =  /^([\u4e00-\u9fa5\·]{2,10})$/;
            return reg.test(name);
        }
    };
    return validate;
});