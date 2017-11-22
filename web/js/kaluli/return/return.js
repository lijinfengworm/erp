$('.refresh').click(function(){
    $.post('/return/aJaxRecommend?rd='+Math.random(),{},function(msg){
        if(msg.status){
           var str = '';
           for(var i in msg.data){
               str += '<dl>\
               <dt class="pro-img">\
               <a target="_blank" href="/product/'+msg.data[i].id+'.html">\
                   <img src="'+msg.data[i].pic+'?imageView2/2/w/128">\
                   </a>\
               </dt>\
               <dd class="pro-price"><span>¥</span>'+msg.data[i].price+'</dd>\
                   <dd class="pro-name">\
                       <a target="_blank" href="/product/'+msg.data[i].id+'.html">'+msg.data[i].title+'<br/><span>'+msg.data[i].sell_point+'</span></a>\
               </dd>\
               </dl>';
           }
           $('.r-l-grid').html(str);
        }
    },'json')

})
