/*
 txt:要限制的文本框
 show:要展示剩余可输入的数量的文本框
 limit:限制数量
 isbyte:true：按字节限制；false：按字符限制
 */
 function limitTxt(txt,show,limit,isbyte){
 	$("#"+txt).keydown(function(){
 		lim(txt,show,limit,isbyte);
 	});
 	
 	$("#"+txt).keyup(function(){
 		lim(txt,show,limit,isbyte);
 	});
 }

 function lim(txt,show,limit,isbyte){
 	var t=$("#"+txt);
 	var count=getLen(t,isbyte);
 	if(count>limit){
 		var index=isbyte?t.val().length+Math.floor((limit-count)/2):limit;
 		t.val(t.val().substring(0,index));
 		$("#"+show).text(limit-getLen(t,isbyte));
 	}else{
 	$("#"+show).text(limit-count);}
 }

 function getLen(txt,isbyte){
 	var c=(isbyte)?$(txt).val().replace(/[^\u0000-\u00ff]/g,"aa").length:$(txt).val().length;
 return c;
 }