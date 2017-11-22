// JavaScript Document

function tojump(url,pageid){
    var page = $("#"+pageid).val();
    if(page<1){
        alert('请输入页数');
        return false;
    }else{
        if(url.indexOf("?")>-1){
            window.location.href=url+"&page="+page;
        }else{
            window.location.href=url+"?page="+page;
				
        }
    }
}


$(document).ready(function(){
    if($('.datetimepicker').length>0){
        $('.datetimepicker').datetimepicker({
            dateFormat: 'yy-mm-dd'
        }); 
    }
    
})


var jmz = {};
jmz.GetLength = function(str) {
    ///<summary>获得字符串实际长度，中文2，英文1</summary>
    ///<param name="str">要获得长度的字符串</param>
    var realLength = 0, len = str.length, charCode = -1;
    for (var i = 0; i < len; i++) {
        charCode = str.charCodeAt(i);
        if (charCode >= 0 && charCode <= 128) realLength += 1;
        else realLength += 2;
    }
    return realLength;
};

//检测是否为空
jmz.IsEmpty = function (str){
	var ispast = false;
	str = str.replace(/\s+/g,"");
	if(str == ''){
		ispast = true;
	}
	return ispast;
};
