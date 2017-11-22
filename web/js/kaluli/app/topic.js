requirejs.config({
    baseUrl:"//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        "getQueryString":"modules/common/getQueryString"
    },
    urlArgs: '20160309'
});

require(['getQueryString'],function(getQueryString){
    function createDom(name,id,index,version){
        var url,cursor,target,index = index < 10 ? "0"+index : index;
        var reg = new RegExp(/^\d+$/);
        if(id == "none"){
            url="javascript:void(0)";
            cursor="default";
        }else{
            cursor="pointer";
            target="_blank"
            id.match(reg) ? url='//www.kaluli.com/product/'+id+'.html' : url='//www.kaluli.com/product/'+escape(id)+''
        }

        return '<a target='+target+' href='+url+' style="cursor:'+cursor+'">\
                    <img width="100%" src="//kaluli.hoopchina.com.cn/images/kaluli/activity/specialtopic/'+name+'_'+index+'.jpg?v='+version+'" />\
              </a>';
    }
    if(getQueryString("name") !=null && getQueryString("name").toString().length>1)
    {
        var ids = getQueryString("id").split(","),
            name = getQueryString("name"),
            version = getQueryString("v");

        for(var i=0;i<ids.length;i++){
            $(".content").append(createDom(name,ids[i],Math.round(i+1),version));
        }

    }else{
        window.location.href="//www.kaluli.com";
    }


});