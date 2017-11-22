$(document).ready(
  function ()
  {
    setSelectedGames();
    addEventForSelectGame();
    addValidateFormEvent();
  }
)

function addEventForSelectGame()
{
  $('div[id^=game_]').click(
    function (event)
    {
      $(this).toggleClass('normal selected');           
      
      $('#selectedGameIds').val((getSelectedGames()).join(','));
    }
  );
}

function setSelectedGames()
{
  var selectedGameIds = $('#selectedGameIds').val().split(',');
  
  $(selectedGameIds).each(
    function (index, id)
    {
      $('#game_' + id).addClass('selected');
    }
  );  
}

function getSelectedGames()
{
  var selectedGameIds = [];
  
  $('div[id^=game_]').each(
    function (index, element)
    {
      if ($(this).hasClass('selected'))
      {
        var id = this.id.split('_');        
        
        selectedGameIds.push(id[1]);
      }
    }
  );
  
  return selectedGameIds;
}

function addValidateFormEvent()
{  
  $('#gonghuiForm').submit(
    function (event)
    {            
      if (!$('#name').val().length)
      {
        alert('公会名不能为空');
        
        return false; 
      }
      else if (!$('#ownerName').val().length)
      {
        alert('会长账号不能为空');
        
        return false; 
      }
      else if (!$('#shareRate').val().length 
               || parseInt($('#shareRate').val()) > 100 || parseInt($('#shareRate').val()) < 0)
      {
        alert('分成比例必须介于0-100之间');
        
        return false; 
      }
      else if (!$('#selectedGameIds').val().length)
      {
        alert('请选择游戏');
        
        return false;        
      }
      else
      {
        return true;
      }            
    }
  );  
}