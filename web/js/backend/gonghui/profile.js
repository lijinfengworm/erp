$(document).ready(
  function ()
  {
    addEventForRefreshBtn();
    addEventForEditableFields();
    addEventForAddNewMemberBtn();
    addEventForDeleteMemberBtn();
    calculateMoney();
    addEventForGameChange();
  }
);

function addEventForRefreshBtn()
{
  $('#refreshBtn').click(
    function (event)
    {       
      redirect();            
    }
  )
}

function redirect()
{
  window.location.href = window.location.protocol 
                         + '//' 
                         + window.location.host 
                         + $('#gonghuiProfileUrl').val() + '?id=' + $.trim($('#gonghuiId').val())
                         + '&gameId=' + $.trim($('#game').val())
                         + '&serverId=' + $.trim($('#server').val())
                         + '&date=' + $.trim($('#date').val());  
}

function addEventForEditableFields()
{
  $('button[id^=member_]').click(
    function (event)
    {
      event.stopPropagation();
      event.preventDefault();
      
      var memberId = this.id.split('_')[1];
      
      if ($(this).text() == '编辑')
      {
        $(this).text('保存');   

        $('#cell_' + memberId + ' .editable').each(
          function (idx, element)
          {            
            var contents = $(element).text().split(',');
            
            contents = $.map(contents, 
              function (value, index)
              {
                return $.trim(value);
              }
            );
            
            $(element).html(('<input class="miniEditor" type="text" value="' + contents.join(',') + '" />'));
          }
        );
      }
      else
      {
        $(this).text('编辑');
        
        // Replace the input boxes with contents
        $('#cell_' + memberId + ' .editable').each(
          function (idx, element)
          {
            var val = $.trim($('.miniEditor', element).val());
            
            $(element).empty().text(val);         
          }
        );
        
        saveMemberInfo(memberId);
      }
    }
  );
}

function addEventForAddNewMemberBtn()
{
  $('#addNewMemberBtn').click(
    function (event)
    {
      event.stopPropagation();
      addNewMember();
    }
  );
}

function addEventForDeleteMemberBtn()
{
  $('button[id^=deleteMemberBtn_]').click(
    function (event)
    {
      event.stopPropagation();
      
      var confirmed = confirm('你确定要删除吗？');
      
      if (confirmed)
      {
        var memberId = this.id.split('_')[1];
        
        deleteOneMember(memberId);
      }      
    }
  );
}

function addNewMember()
{
  $.ajax({
    url: $('#addNewMemberUrl').val(),
    type: 'POST',
    data: {
      gonghuiId: $('#gonghuiId').val(),
      memberName: $.trim($('#memberName').val())
    },
    
    success: function (response)
    {
      if (response.hasErrors)
      {
        alert('保存失败');
      }
      else
      {
        redirect(); 
      }
    }
  })
}

function deleteOneMember(memberId)
{
  $.ajax({
    url: $('#deleteMemberUrl').val(),
    type: 'POST',
    data:{
      memberId: memberId
    },
    
    success: function (response)
    {
      if (response.hasErrors)
      {
        alert('删除失败');
      }
      else
      {
        redirect(); 
      }
    }
  }
  );
}

function saveMemberInfo(memberId)
{
  $.ajax({
   url: $('#saveMemberInfoUrl').val(),
   type: 'POST',
   
   data: {
    gonghuiId: $('#gonghuiId').val(),
    gameId: $('#game').val(),
    serverId: $('#server').val(),
    memberId: memberId,
    roleNames: $.trim($('.roleRow', $('#cell_' + memberId)).text()),
    cardNo: $.trim($('.cardNumRow', $('#cell_' + memberId)).text()),
    modified: ''
   },
   
   dataType: 'json',
   
   success: function (response)
   {
      if (response.hasErrors)
      {
        alert('保存失败');        
      }
      else
      {
        redirect(); 
      }
   }
  });
}

function calculateMoney()
{
  $('td[id^=share_]').each(
    function (index, element)
    {      
      var shareRate = $('#shareRate').val();
      var memberId = element.id.split('_')[1];
      
      var recharge = parseFloat($.trim($('#recharge_' + memberId).text()));
      
      if (window.isNaN(recharge))
      {
        recharge = 0;
      }
      
      $(element).empty().text((shareRate/100 * recharge).toFixed(1));
    }
  );

  var rechargeCells = $('td[id^=recharge_]');
  
  var value;
  var amount;
  
  var sum = 0;
  
  for (var i = 0, length = rechargeCells.length; i < length; i++)  
  {
    value = parseFloat($.trim($(rechargeCells[i]).text()));
    
    amount = isNaN(value) ? 0 : value;
    
    sum += amount;
    
    $('#rechargeSum').text(sum.toFixed(1));
  }
  
  var shareCells = $('td[id^=share_]');
  
  var shareSum = 0;
  
  for (var j = 0, len = shareCells.length; j < len; j++)
  {
    value = parseFloat($.trim($(shareCells[j]).text()));
    
    amount = isNaN(value) ? 0 : value;
    
    shareSum += amount;
    
    $('#shareSum').text(shareSum.toFixed(1));
  }
}

function addEventForGameChange()
{
  $('#game').bind('click change blur',
    function (event)
    {
      $('#server')
        .empty()
        .html(getServerOptions($(this).val()));
    }
  );
}

function getServerOptions(gameId)
{
  var servers = $.parseJSON($.trim($('#gameServers').val()));
  
  var options = '';
  var gameServers = servers[gameId];
  
  for (var serverId in gameServers)
  {
    options += '<option value="' + serverId + '">' + gameServers[serverId] + '</option>';
  }
  
  return options;
}