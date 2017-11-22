$(document).ready(function() {
    $("#cancel_submit").click(function(e) {
        e.preventDefault();
        if(confirm("放弃发布，您填写的全部内容将丢失。仍然放弃发布？")) {
            window.location.href = homepage_url;
        }
    });

    $("#item_add_again").click(function(e) {
        e.preventDefault();
        var href = $(this).attr("href");
        window.location.href = href;
    });
    
    $("#item_update").submit(function(e) {
        e.preventDefault();
        var value = $("#item_update").serialize();
        var url = $(this).attr("data_url");
        
        $.post($("#item_update").attr('action'), value, function(data) {
            if(data.status == 0) {
                //alert(data.msg);
				$("#fbzg_box").html("提前成功！");
				//$("#tq_tip").show();
				submitRight(url);				
            } else {
				$("#fbzg_box").show();
				$("#fbzg_box").html("提前失败，请重试！");
				setTimeout(function(){
					$("#fbzg_box").fadeOut();
				},3000);
               // alert(data.msg);
			   
            }
        }, "json");
    });
});

//发布成功
function submitRight(url){
	var t = 2;
	var timer = setInterval(function(){
		t--;
		if(t===0){
			clearInterval(timer);
            window.location.href= url;
		};
	},1000);
	openBox.show('fbzg_box');	
  openBox.mask.style.display = "none";
};
