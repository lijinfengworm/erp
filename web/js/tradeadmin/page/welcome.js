
var _tips = {
    morning:[
        "全新的一天！深呼吸来个大大的微笑 ^_^",
        "今天你那天气怎么样，还不错吧！",
        "外面太阳大吗？记得多喝水哦！",
        "又是忙碌的一天！",
    ],
    afternoon:[
        "用心每一天，不忘初心，方能走远",
        "依心而行，无憾今生",
        "拥抱阳光，转身心晴",
        "看轻看淡多少，痛苦就离开你多少",
        "此时的坚持，是为了下班时的喜悦",
        "惜时光，演绎精彩生活",
        "缘深多聚聚，缘浅随它去",
        "即使没有翅膀，心也要飞翔",
        "累了，让眼皮来个深情的拥抱吧！",
        "偷偷挤进一缕斜阳，送来满满幸福",
    ]
};

function  remind() {
   var day = new Date();
   var hr = day.getHours();
   var  minu = day.getMinutes();
    var str = '';
    if (hr ==1) str = "一点多啦！别忘了休息哦！";
    if (hr ==2) str = "你真是工作狂啊，该休息了，身体是革命的本钱！";
    if (hr ==3) str = "午夜三点！你还不准备睡觉吗？";
    if (hr ==4) str = "凌晨四点多了，很敬重您这种忘我的工作精神！";
    if (hr ==5) str = "您是刚起床还是还没睡啊？";
    if (hr ==6) str = "早上好！新一天又开始啦！有什么打算呢？";
    if (hr ==7) str = "吃过早饭了吗？早饭尽量吃哦！";
    if ((hr ==8) || (hr == 9) || (hr ==10)) str = "早上好，"+_tips.morning[GetRandomNum(0,_tips.morning.length - 1)];;
    if (hr ==11) str = "快中午啦，准备吃饭了呀！";
    if (hr ==12) str = "中午好，大好时光，怎能浪费在瞌睡上，看个片吧！？";
    if ((hr==13) || (hr==14)) str = "下午好，"+_tips.afternoon[GetRandomNum(0,_tips.afternoon.length - 1)];
    if ((hr==15) || (hr==16) || (hr==17)) str = "下午好，"+_tips.afternoon[GetRandomNum(0,_tips.afternoon.length - 1)];
    if ((hr==18) || (hr==19)) str = "新闻联播你看了吗？吃晚饭了没？";
    if ((hr==20) || (hr==21) || (hr==22)) str = "晚上了，找个电影看看睡觉吧？";
    if (hr==23) str = "不早了，快休息吧？";
    if (hr==0) str = "午夜时分，你可要注意身体呢！";
    return str;
}

$(function(){
    $("#welcome_tips").text(remind());
});