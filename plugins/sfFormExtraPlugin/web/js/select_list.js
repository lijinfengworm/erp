// JavaScript Document
function ajaxSelectOptions(url,selected,selectId){
	var data = $("#"+selected).val();
	$.ajax({
            type: "post",
		    url: url,
		    data: "data="+data+"&t="+new Date().getTime(),
		    success: function(msg){
			msg = $.trim(msg);
				$("#"+selectId).html(msg);
			}	   
		});		
	
	
}

function cacleSelectNext(divid){
	$("#"+divid).html('<option value="">请选择</option>');
}