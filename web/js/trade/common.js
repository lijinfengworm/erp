var getShopData = {
    init:function(arr){
         var that = this;
         this.arr = arr.type;
         this.obj = arr.obj;
         this.tid = arr.tid;
         $.post('http://www.shihuo.cn/api/getDetailByTid?tid='+this.tid,function(data){
                if(data.status*1 == 0){
                     that.obj.prepend(that.writeDom(data.data)).show();
                }    
         },"json");
    },
    writeDom:function(data){
         var base = [],clas = [],i,str
         for(i in data.evaluateInfo){
             if(data.evaluateInfo[i].highGap > 0.5){
                   base[i] = "↑";
                   clas[i] = "";
               }else if(data.evaluateInfo[i].highGap < -0.5){
                   base[i] = "↓";
                   clas[i] = ' class="gr"';
               }else{
                   base[i] = "一";
                   clas[i] = ' class="b"';
               }
         }
         switch(this.arr){
            case "shoe":
                   str = '<div class="name-b"><a onclick="taobaokeShopDaceStatistics()" target="_blank" data-sellerid="'+data.userNumId+'" isconvert="1">【'+data.type+'】'+data.shopTitle+'</a></div>\
                         <div class="tips-shop">\
                             <div class="fn"><s>描述</s><s>服务</s><s>物流</s></div>\
                             <div class="num">\
                                 <span class="t1"><s'+clas[0]+'>'+data.evaluateInfo[0].score+'</s><i'+clas[0]+'>'+base[0]+'</i></span><span class="t1"><s'+clas[1]+'>'+data.evaluateInfo[1].score+'</s><i'+clas[1]+'>'+base[1]+'</i></span><span class="t1"><s'+clas[2]+'>'+data.evaluateInfo[2].score+'</s><i'+clas[2]+'>'+base[2]+'</i></span>\
                             </div>\
                         </div>\
                         <a onclick="taobaokeShopDaceStatistics()" class="j-link" target="_blank" data-sellerid="'+data.userNumId+'" isconvert="1"><img src="/images/trade/baoliao/house.png" /> | 进入店铺 <b>></b></a>';
               break;
            case "tuangou":
                   str = '<div class="h2"><a onclick="taobaokeShopDaceStatistics()" target="_blank" data-sellerid="'+data.userNumId+'" isconvert="1">【'+data.type+'】'+data.shopTitle+'</a></div>\
                     <div class="tips-shop"><span class="t1">描述 <s'+clas[0]+'>'+data.evaluateInfo[0].score+'</s><i'+clas[0]+'>'+base[0]+'</i></span><span class="t1">服务 <s'+clas[1]+'>'+data.evaluateInfo[1].score+'</s><i'+clas[1]+'>'+base[1]+'</i></span><span class="t1">物流 <s'+clas[2]+'>'+data.evaluateInfo[2].score+'</s><i'+clas[2]+'>'+base[2]+'</i></span></div>\
                     <div class="jong-area">\
                         <a onclick="taobaokeShopDaceStatistics()" class="j-link" target="_blank" data-sellerid="'+data.userNumId+'" isconvert="1"><img src="/images/trade/baoliao/house.png" /> | 进入店铺 <b>></b></a>\
                     </div>';
                break;
            case "youhui":
                   str = '<span class="name-b"><a onclick="taobaokeShopDaceStatistics()" target="_blank" data-sellerid="'+data.userNumId+'" isconvert="1">【'+data.type+'】'+data.shopTitle+'</a></span><span class="tips-shop"><span class="t1">描述 <s'+clas[0]+'>'+data.evaluateInfo[0].score+'</s><i'+clas[0]+'>'+base[0]+'</i></span><span class="t1">服务 <s'+clas[1]+'>'+data.evaluateInfo[1].score+'</s><i'+clas[1]+'>'+base[1]+'</i></span><span class="t1">物流 <s'+clas[2]+'>'+data.evaluateInfo[2].score+'</s><i'+clas[2]+'>'+base[2]+'</i></span></span><a onclick="taobaokeShopDaceStatistics()" class="j-link" target="_blank" data-sellerid="'+data.userNumId+'" isconvert="1"><img src="/images/trade/baoliao/house.png" /> | 进入店铺 <b>></b></a>';     
            case "find":
                   str = '<span class="name-b"><a onclick="taobaokeShopDaceStatistics()" target="_blank" data-sellerid="'+data.userNumId+'" isconvert="1">【'+data.type+'】'+data.shopTitle+'</a></span><span class="tips-shop"><span class="t1">描述 <s'+clas[0]+'>'+data.evaluateInfo[0].score+'</s><i'+clas[0]+'>'+base[0]+'</i></span><span class="t1">服务 <s'+clas[1]+'>'+data.evaluateInfo[1].score+'</s><i'+clas[1]+'>'+base[1]+'</i></span><span class="t1">物流 <s'+clas[2]+'>'+data.evaluateInfo[2].score+'</s><i'+clas[2]+'>'+base[2]+'</i></span></span><a onclick="taobaokeShopDaceStatistics()" class="j-link" target="_blank" data-sellerid="'+data.userNumId+'" isconvert="1"><img src="/images/trade/baoliao/house.png" /> | 进入店铺 <b>></b></a>';
            default:
         }
         return str;
    }
}