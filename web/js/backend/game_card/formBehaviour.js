$(document).ready(
  function ()
  {
    validate();
    addEventForGameSelection();
  }
);

function validate()
{
  $('#gameCardForm').submit(
    function ()
    {
      if (!$.trim($('#gameId').val()))
      {
        alert('请选择游戏');
        
        return false;
      }
      else
      if (!$.trim($('#serverId').val()))
      {
        alert('请选择服务器');
        
        return false;
      }
      else
      if (!$.trim($('#value').val()))
      {
        alert('请填写金额');
        
        return false;
      }
      else
      if (!$.trim($('#quantity').val()))
      {
        alert('请填写数量');
        
        return false;
      }
      else
      if (!$.trim($('#startTime').val()))
      {
        alert('请设定开始时间');
        
        return false;
      }
      else
      if (!$.trim($('#validPeriod').val()))
      {
        alert('oops, 你忘了设定有效期了*.*');
        
        return false;
      }
      
      return true;
    }
  );
}

function addEventForGameSelection()
{
  $('#gameId').bind('click blur change', 
    function ()
    {
      $('#serverId')
        .empty()
        .html(getServerOptions($(this).val()));  
    }
  );
}


function getServerOptions(gameId)
{
  var servers = $.parseJSON($.trim($('#servers').val()));
    
  var options = '';
  var gameServers = servers[gameId];
  
  for (var serverId in gameServers)
  {
    options += '<option value="' + serverId + '">' + gameServers[serverId] + '</option>';
  }
      
  return options;
}