$(document).ready(
  function ()
  {
    addEventForGameChange(); 
    
    // Manually triger an event
    $('#gameId').trigger('click');
    
    $('#getRoleBtn').click(
      function (e)
      {
        e.preventDefault();
        e.stopPropagation();
        
        getRoles();
      }
    );
    
    // I must admit, jQuery is fucking weired.
    // addEventForRechargeBtn();
  }
);

function addEventForGameChange()
{
  $('#gameId').bind(
    'click focus blur change',
    
    function (event)
    {      
      getServerList();
      
      if ($('#roleBased').length)
      {
        var roleBased = $('#roleBased').val().split(',');
        var show = false;
        
        for (var i = 0; i < roleBased.length; i++)
        {
          if (this.value == roleBased[i])
          {
            show = true;
            
            break;
          }
        }
        
        if (show)
        {
          $('#roleSelectBox').show();
        }
        else
        {
          $('#roleSelectBox').hide();
        }
      }
    }
  );  
  
  $('#serverId').bind(
    'click focus blur change',
    
    function (event)
    {
      $('#role').empty();      
    }
  );
}

function getServerList()
{
  $('#gameId').bind('click change blur',
    function (event)
    {
      $('#serverId')
        .empty()
        .html(getServerOptions($(this).val()));
    }
  );
}

function getServerOptions(gameId)
{
  var servers = $.parseJSON($.trim($('#gameServers').val()));
    
  var options = '<option value="">--请选择--</option>';
  var gameServers = servers[gameId];
  
  for (var serverId in gameServers)
  {
    options += '<option value="' + serverId + '">' + gameServers[serverId] + '</option>';
  }
      
  return options;
}

function showAvailableOptions(hideElementSelector, showElementSelector)
{
   $(hideElementSelector).hide();
      
   $(showElementSelector).show();      
   $(showElementSelector).attr('selected', 'selected');       
}

function showSelectRoleOptions()
{
  
}

function getRoles()
{
  if (!$('#targetUserName').val() 
      || !$('#gameId').val()
      || !$('#serverId').val())
  {
    return alert('请先填写用户名，游戏和服务器');
  }
  
  $.ajax({
    url: $('#getRoleUrl').val(),
    type: 'post',
    dataType: 'json',
    data: {
      username: $('#targetUserName').val(), 
      gameId: $('#gameId').val(),
      serverId: $('#serverId').val(),
	  gameName: $("#gameId").find("option:selected").text(),
	  serverName: $("#serverId").find("option:selected").text()
    },
    
    success: function (response)
    {
      if (response.result.code == '000')
      {
        var options = '';
          
        for (var i = 0; i < response.roles.length; i++)
        {
          options += '<option value="' 
                     + response.roles[i].id 
                     + '|' 
                     + response.roles[i].name + '">' 
                     + response.roles[i].name 
                     + '</option>';
        }
        
        $('#role').html(options);
      }
      else
      {
        var error_info = response.result.code + '|' + response.result.message + "\r\n" + response.result.url;
        alert(error_info);
      }
    }
  });
}

function addEventForRechargeBtn()
{
  $('#rechargeForm').submit(
    function (event)
    {
      event.preventDefault();
      event.stopPropagation();
      
      if (!$('#targetUserName').val().length)
      {
        alert('请填入用户名');
        
        return false;
      }
      else
      if (!$('#amount').val().length)
      {
        alert('请填入充值金额');
        
        return false;
      }
      else
      if (!$('#gameId').val().length)
      {
        alert('请选择游戏');
        
        return false;
      }
      else
      if (!$('#serverId').val().length)
      {
        alert('请选择服务器');
        
        return false;
      }
      else
      {
        return true;
      }
    }
  );
}