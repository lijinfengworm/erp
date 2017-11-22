$(document).ready(
  function ()
  {
      addEventForBanFormBtn();
      addEventForUnbanFormBtn();
      addEventForItemUpdateBtn();
      addEventForNowBtn();
      addEventForBanBtn();     
      addEventForBanTimeInput();
      addEventForPermanentBanBtn();
      addConfirmEvent();
      $('.banTime').trigger('blur');
      addEventForBanItemBtn();
      addEventForBanShopBtn();
      addEventForCollectionSave();
      addEventForTagSave();
      addEventForTagDel();
  }
);

function addEventForBanBtn()
{
    $('.ban-btn').click(
      function (e)
      {
        e.preventDefault();
        
        var uid = $(this).attr("user_id");
        var text = $('#ban-btn-text_' + uid).text();
        
        if (text == '封禁')
        {
          openBox.show('fjyh_' + uid);         
        }
        else
        {
          openBox.show('jjyh_' + uid);
        }
      }
  );
}

function addEventForBanFormBtn()
{    
    $('.form-ban-btn').click(
      function (e)
      {
        e.preventDefault();
        e.stopPropagation();
        
        var uid = $(this).attr("user_id");

        $.ajax(
        {
            url: $('.form-ban-user').attr('action'),
            dataType: 'json',
            data: {
                  username: $('#username_' + uid).val(),
                  banTime: $('#banTime_' + uid).val(),
                  banPermanent: $('#banPermanent_' + uid).is(':checked') ? 1 : 0,
                  hideAllItems: $('#hideAllItems_' + uid).is(':checked') ? 1 : 0,
                  sendPM: $('#sendPM_' + uid).is(':checked') ? 1 : 0,
                  pm: $('#pm_' + uid).val()
                },
                
            success: function (response)
            {
              alert(response.status.message);
              
              if (response.status.code == '200')
              {
                $('#fjyh_' + response.user_id).hide();
                openBox.hide('fjyh_' + response.user_id);                                
                
                $('#ban-btn-text_' + response.user_id).text('解禁');
                
                if (response.data.ban_permanent)
                {
                  $('[data-id=ban-status]_'+ response.user_id +']').text('永久封禁');  
                }
                else
                {
                  $('[data-id=ban-status_'+ response.user_id +']').text('封禁剩余' + response.data.time_remains + '天');  
                }
              }              
            }
        }
        );
      }
    );
}

function addEventForUnbanFormBtn()
{
    $('.form-unban-btn').click(
      function (e)
      {
        e.preventDefault();
        e.stopPropagation();

        var uid = $(this).attr("user_id");
        
        $.ajax(
        {
            url: $('#form-unban-user_' + uid).attr('action'),
            dataType: 'json',
            data: {
                  username: $('#unban-username_' + uid).val(),
                  showAllItems: $('#unban-showAllItems_' + uid).is(':checked') ? 1 : 0,
                  sendPM: $('#unban-sendPM_' + uid).is(':checked') ? 1 : 0,
                  pm: $('#unban-pm_' + uid).val()
                },
                
            success: function (response)
            {
              alert(response.status.message);
              
              if (response.status.code == '200')
              {
                $('#jjyh_' + response.user_id).hide();
                openBox.hide('jjyh_' + response.user_id);          
                
                $('#ban-btn-text').text('封禁');
                $('[data-id=ban-status]').text('正常');
              }
            }
        }
        );
      }
    );
}

function addEventForNowBtn()
{  
  $('#btn-now').click(
    function (e)
    {       
      e.preventDefault();
      
      $('#publishTime').datetimepicker('setDate', (new Date()));
    }
  );
}

function addEventForItemUpdateBtn()
{
    $('#item-update-btn').click(
      function (e)
      {
        e.preventDefault();
        e.stopPropagation();
        
        $.ajax(
        {
            url: $('#form-update-item').attr('action'),
            dataType: 'json',
            data: {
                  'itemId'     : $('#itemId').val(),
                  'publishTime': $('#publishTime').val(),
                  'isVerified' : $('#isVerified').is(':checked') ? 1 : 0,
                  'isHide'     : $('#isHide').is(':checked') ? 1 : 0,
                  'isSoldOut'  : $('#isSoldOut').is(':checked') ? 1 : 0
                },                
                
            success: function (response)
            {
              alert(response.status.message);                          
            }
        }
        );
      }
    );
}

function addEventForBanTimeInput()
{
  $('.banTime').bind('change blur', 
  function (e)
  {    
    var uid = $(this).attr("user_id");
    var username = $(this).attr("user_name");
    var $banMsg = $('#pm_' + uid);    
    var msg = $banMsg.data('message');
    
    msg = msg.replace('{{days}}', '封禁' + $.trim($(this).val()) + '天');
    msg = msg.replace('{{hupuUsername}}', username);
    $banMsg.val(msg);
  });
  
  $('.banTime').keydown(
      function(event) {
        // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }        
    }
  );
}

function addEventForPermanentBanBtn()
{
    $('.banPermanent').click(
      function (e)
      {
        if ($(this).is(':checked'))
        {
          var uid = $(this).attr("user_id");
          var $banMsg = $('#pm_' + uid);    
          var msg = $banMsg.data('message');
          
          msg = msg.replace('{{days}}', '永久封禁');
          $banMsg.val(msg);
          
          $('#banTime_' + uid).prop('disabled', true)
        }
        else
        {
          var uid = $(this).attr("user_id");
          $('#banTime_' + uid).prop('disabled', false);
          $('#banTime_' + uid + uid).trigger('blur');            
        }
      }
    );
}

function addConfirmEvent()
{
    $('[data-confirm]').click(
      function (e)
      {
        if ($(this).is(':checked'))
        {
          $result = confirm($(this).data('message-check'));
        }
        else
        {
          $result = confirm($(this).data('message-uncheck'));
        }       
        
        if (!$result)
        {
          e.preventDefault();  
        }
      }
    );
}

function addEventForBanItemBtn()
{
    $('#btn-ban-item').click(
      function (e)
      {
        e.preventDefault();
        e.stopPropagation();
        
        $that = $(this);
        
        if (!confirm('确定要 ' + $($that.data('target')).text()))
        {
          return;  
        }
        
        $.ajax(
         $that.data('action'),
         {
            data: { itemId: $('#itemId').val(), status: $('#item-status').val() == 0 ? 1 : 0 },
            dataType: 'json',
            success: function (response)
            {
              alert(response.status.message);
              
              if (response.status.code == '200')
              {
                if ($('#item-status').val() == 1)
                {                  
                  $('#item-status').val(0);
                  $($that.data('target')).text($that.data('text-0'));
                }
                else
                { 
                  $('#item-status').val(1);
                  $($that.data('target')).text($that.data('text-1'));
                }
              }
            }
         }
        );  
      }
    );
}

function addEventForBanShopBtn()
{
    $('#btn-ban-shop').click(
      function (e)
      {
        e.preventDefault();
        e.stopPropagation();
        $that = $(this);
        
        if (!confirm('确定要 ' + $($that.data('target')).text()))
        {
          return;  
        }

        $.ajax(
         $that.data('action'),
         {
            data: { shopId: $('#shop-id').val(), status: $('#shop-status').val() == 0 ? 1 : 0 },
            dataType: 'json',
            success: function (response)
            {
              alert(response.status.message);
              
              if (response.status.code == '200')
              {
                if ($('#shop-status').val() == 1)
                {                  
                  $('#shop-status').val(0);
                  $($that.data('target')).text($that.data('text-0'));
                }
                else
                { 
                  $('#shop-status').val(1);
                  $($that.data('target')).text($that.data('text-1'));
                }
              }
            }
         }
        );  
      }
    );
}

function addEventForCollectionSave() {
    $("#add_to_collection").submit(function(e) {
        e.preventDefault();
        var url = $(this).attr("action");
        var value = $(this).serialize();
        $.post(url, value, function(data) {
            if(data.status == 0) {
                alert("操作成功");
            }
        }, "json");
    });
}

function addEventForTagSave() {
    $("#add_to_tag").submit(function(e) {
        e.preventDefault();
        var url = $(this).attr("action");
        var value = $(this).serialize();
        $.post(url, value, function(data) {
            if(data.status == 0) {
                alert("操作成功");
                window.location.reload();
            }
        }, "json");
    });
}

function addEventForTagDel() {
    $(".tag_del").click(function(e) {
        var el = $(this);
        e.preventDefault();
        var url = $(this).attr("href");
        $.get(url, {}, function(data) {
            if(data.status == 0) {
                alert("操作成功");
                el.parent().hide();
            }
        }, "json");
    });
}
