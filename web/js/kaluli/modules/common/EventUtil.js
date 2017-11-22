define(function(){
    var EventUtil = {
        //event的兼容写法
        getEvent: function (event) {
            return event ? event : window.event;
        },
        //target的兼容写法
        getTarget: function (event) {
            return event.target || event.srcElement;
        },
        //绑定函数到事件上，兼容写法
        addHandler: function (element, type, handler) {
            if (element.addEventListener) {
                element.addEventListener(type, handler, false);
            } else if (element.attachEvent) {
                element.attachEvent("on" + type, handler);
            } else {
                element["on" + type] = handler;
            }
        },
        //relatedTarget的兼容写法，获取所到的DIV参数
        getRelatedTarget: function (event) {
            if (event.relatedTarget) {
                return event.relatedTarget;
            } else if (event.toElement) {
                return event.toElement;
            } else if (event.fromElement) {
                return event.fromElement;
            } else {
                return null;
            }
        }
    };
    return EventUtil
})