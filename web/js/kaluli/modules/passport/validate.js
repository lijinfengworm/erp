/**
 * Created by jiangyanghe on 16/6/30.
 */
define(function(){
    var validateUtil = {
        //验证手机号
        testMobile: function (value) {
            if(/^(0|86|17951)?(13[0-9]|15[012356789]|17[03678]|18[0-9]|14[57])[0-9]{8}$/.test(value)){
                return true;
            }else{
                return false;
            }
        },
        //relatedTarget的兼容写法，获取所到的DIV参数
        testPassword: function(e){
            if(e.length < 6){
                return false;
            }
        },
        //checkbox是否勾选
        //id id对象
        testChecked:function(id){
            if(id.attr('checked') !== 'checked'){



                return false;
            }else{
                return true;
            }
        },
        //获取密码强度
        checkStrong:function(sValue) {
            var modes = 0;
            //正则表达式验证符合要求的
            if (sValue.length < 1) return modes;
            if (/\d/.test(sValue)) modes++; //数字
            if (/[a-z]/.test(sValue)) modes++; //小写
            if (/[A-Z]/.test(sValue)) modes++; //大写
            if (/\W/.test(sValue)) modes++; //特殊字符
            //逻辑处理
            switch (modes) {
                case 1:
                    return 1;
                    break;
                case 2:
                    return 2;
                case 3:
                    return 3;
                case 4:
                    //return sValue.length < 12 ? 3 : 4;
                    return 3;
                    break;
            }
        }
    };
    return validateUtil
})