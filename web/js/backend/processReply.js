    function processDelete(id,request_url){ 
        if(!confirm("你确定要进行该操作吗?")){
            return false;
        }
        var updateOrderedUrl = $.trim(request_url) ;
        replyData = {"replyId" : id};
        $.ajax({
            url: updateOrderedUrl,
            data: replyData,
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data.isok == 1){
                  $("#"+id).html('删除'); 
                  $("#"+id).parent().attr('class','sf_admin_action_delete');
                }else if(data.isok == 2){
                  $("#"+id).html('还原');
                  $("#"+id).parent().attr('class','sf_admin_action_new');
                }
                alert(data.result);
            }
        });
    }
    
    function processBanned(userId,userName,identify,request_url){
        if(!confirm("你确定要进行该操作吗?")){
            return false;
        }
        var bannedDay = 1;       
        var tmpIdentify = $("."+userId).attr('data-message-id');
        
        if(!identify && tmpIdentify=='false'){
            bannedDay = prompt("请输入需要禁言的天数","1");
            if(bannedDay==null || bannedDay==""){
               alert('请输入具体的禁言天数');
               return fasle;
            }else if(isNaN(bannedDay)){
                alert('抱歉！你输入了非数字的内容！');
                return fasle;
            }
        }
        var updateOrderedUrl = $.trim(request_url) ;
        userData = {"userId" : userId ,"userName" : userName,"bannedDay" : parseInt(bannedDay)};
        $.ajax({
            url: updateOrderedUrl,
            data: userData,
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data.isok == 1){
                  $("."+userId).html('禁言'); 
                  $("."+userId).parent().attr('class','sf_admin_action_edit');
                  $("."+userId).attr('data-message-id','false');
                }else if(data.isok == 2){
                  $("."+userId).html('解禁');
                  $("."+userId).parent().attr('class','sf_admin_action_new');
                  $("."+userId).attr('data-message-id','true');
                }
                alert(data.result);
            }
        });
    }
    
    function processPush(id,request_url){
        if(!confirm("你确定要进行该操作吗?")){
            return false;
        }
       
        var updateOrderedUrl = $.trim(request_url) ;
        replyData = {"replyId" : id};
        $.ajax({
            url: updateOrderedUrl,
            data: replyData,
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data.isok == 1){
                  $("."+id).html('推送'); 
                }else if(data.isok == 2){
                    $("."+id).html('取消推送'); 
                }
                alert(data.result);
            }
        });
    }
