
    //放弃发布
    $("#cancel_add").click(function(e) {
        e.preventDefault();
        if(confirm("放弃发布，您填写的全部内容将丢失。仍然放弃发布？")) {
            window.location.href = homepage_url;
        }
    });

    //重新发布
    $("#re_add").click(function(e) {
        e.preventDefault();
        if(confirm("重新发布，您填写的全部内容将丢失。仍然重新发布？")) {
            window.location.href = submit_url;
        }
    });
    
    $(".size_checkall").click(function() {
        var name = $(this).attr("data");
        var container_name = name + "_sizes";

        if(this.checked){
            $("." + container_name + " input").attr("checked",true);
        }else{
            $("." + container_name + " input").removeAttr("checked");
        }

    });

    //图片上传
    var img_options = { 
        dataType :   "json",
        success  :   function(data) { 
            if (data.status == "0") {
                $("#img_preview").html("<img src='"+ data.show_url + ".jpg' />");
                $("#img_preview_300").html("<img src='"+ data.show_url + "_300.jpg' />");
                $("#data_img_url").val(data.img_url);
            } else {
                alert(data.msg);
            }
        } 
    }; 

    $(".img_upload").ajaxForm(img_options);

    var memo_default = "请输入140字以内的商品简介";
    if($("#data_memo").val() == "") {
        $("#memo_textarea").val(memo_default);
    } else {
        $("#memo_textarea").val($("#data_memo").val());
    }
	
	memoVal();
	
    //发布产品
    var item_options = { 
        dataType :   "json",
        beforeSubmit : function() {
            $("#item_submit").attr("disabled", "disabled");
        },
        success  :   function(data) { 
            if(data.status == 0) {
                //发布成功
                submitRight();
            } else {
                if(data.redirect) {
                    window.location.href=data.redirect;
                }
                for(var i in data.err) {
                    //错误提示
                    $("#item_" + i + "_err").html(data.err[i]);
                }

                $("#item_submit").removeAttr('disabled');
            }
        } 
    }; 

    $("input[name='data[style_ids][]']").click(function(e) {
        if($("input[name='data[style_ids][]']:checked").length > 5) {
            e.preventDefault();
            $(this).removeAttr("checked");
            alert("最多选择5个风格");
        }
    });
    $("#trade_item_form").ajaxForm(item_options);


function memoVal(){
	var l = 140;
	var text = $("#memo_textarea").val();
	
	var memoNum = $("#memoNum");
	var data_memo = $("#data_memo");
	if(text==""||text=="请输入140字以内的商品简介"){
		$("#memo_textarea").addClass("c_ccc");			
	} else {
        memoNum.html(140 - $("#memo_textarea").val().length);			
    };
	$("#memo_textarea").bind("keyup",function(){				
		text = $(this).val();		
		var l = 140-text.length;
			if(l<=0){
				l = 0;
				$(this).val(text.slice(0,140));
			};
		memoNum.html(l);			
		if(text=="请输入140字以内的商品简介"){
			text = '';
		}
		data_memo.val(text);
	});
	
}

//发布成功
function submitRight(){
	var t = 2;
	var timer = setInterval(function(){
		t--;
		if(t===0){
			clearInterval(timer);
            if ($("#source").val() == 'find'){
                window.location.href= "/find";
            } else {
                window.location.href= "/shoe";
            }
		};
	},1000);
	openBox.show('fbzg_box');	
  openBox.mask.style.display = "none";
};
