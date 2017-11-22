$(document).ready(
  function ()
  {
    getCardUsers();
    // addEventForToolbar();
    addEventForCreateGonghuiBtn();
    addEventForGroupUserBtn();
    addEventForCheckAllBtn();
    addEventForListGonghuiBtn();
    addEventForGonghuiWrapper();
  }
);

function getCardUsers()
{
  $('td[id^=cardStatus-]').each(
    function (index, element)
    {
      var cardId = element.id.split('-')[1];
      
      if ($(element).text() == '√' && !$.trim($('#cardUserId-' + cardId).text()))
      {
        getOrder(cardId);  
      }
    }
  );
}

function getOrder(cardId)
{
  $.ajax({
    url: $.trim($('#url-OrderByCard').val()),
    type: 'get',
    dataType: 'json',
    data: { cardId: cardId },
    success: function (response)
    {      
      if (!$.isEmptyObject(response))
      {
      
        $('#cardUserId-' + cardId).text(response.id);
        $('#cardUsername-' + cardId).text(response.username);
      }
    }
  });
}

function addEventForToolbar()
{
  $(document).scroll(
    function ()
    {
      if (!window.box)
      {
        console.log('Get box');
        window.box = $('#toolbar-wrapper');        
      }
    
      var scrollBarPosition = document.documentElement.scrollTop;
      var boxPosition = box.offset();                             
      
      if (scrollBarPosition > boxPosition.top)
      {
        console.log(scrollBarPosition + ' => ' + boxPosition.top);
        
        $('#toolbar').removeClass('static');
        $('#toolbar').addClass('fixed');
      }
      else if (scrollBarPosition < boxPosition.top)
      {
        $('#toolbar').removeClass('fixed');
        $('#toolbar').addClass('static');
      }       
    }
  );  
}

function addEventForCreateGonghuiBtn()
{
  $('#createNewGonghuiBtn').click(
    function (event)
    {
      event.preventDefault();
      
      var gonghuiName = $.trim($('#newGonghuiName').val());      
      
      if (gonghuiName)
      {
        createGonghui(gonghuiName);
      }
    }
  );
}

function createGonghui(gonghuiName)
{
  $.ajax({
    url: $('#url-createGonghui').val(),
    type: 'post',
    dataType: 'json',
    data: { name: $.trim(gonghuiName) },
    success: function (response)
    {
      if (response.hasErrors)
      {
        alert(response.message);
        
        return;
      }
      
      var selectedCards = getCheckedElementValue('input[class=cardId]');

      if (!selectedCards.length)
      {
        alert('成功创建公会');
        
        window.location.reload();
        
        return;  
      }
      
      addUserToGonghuiByCards(response.data.id, selectedCards);
    }
  });
}

function addEventForGroupUserBtn()
{
  $('#groupUserBtn').click(
    function (e)
    {
      e.preventDefault();
      
      var selectedCards = getCheckedElementValue('input[class=cardId]');
      var checkedValues = getCheckedElementValue('input[id^=gonghui_]');
      
      if (!checkedValues.length || !selectedCards.length)
      {
        return;  
      }
      
      // Currently, we don't support to add one user to multiple gonghuis
      // So we only get the first checked one
      var gonghui = checkedValues[0];
            
      addUserToGonghuiByCards(gonghui, selectedCards);
    }
  );
}

function getCheckedElementValue(selector)
{
  var checkedElements = $(selector + ':checked');
  
  var checkedValues = [];
  
  checkedElements.each(
    function (index, element)
    {
      checkedValues.push($(element).val());
    }
  );  
  
  return checkedValues;
}

function addUserToGonghuiByCards(gonghui, cards)
{
  $.ajax({
    url: $.trim($('#url-addUserToGonghui').val()),
    type: 'post',
    dataType: 'json',
    
    data: { cards: cards.join(','), 
            gonghuiId: gonghui },
            
    success: function (response)
    {
      alert(response.message);
      
      // Reload the page
      window.location.reload();
    }    
  });
}

function addEventForCheckAllBtn()
{
  $('#checkAll').click(
    function (e)
    {      
      var checkboxes = $('input[class=cardId]');
      
      // Manually check the checkboxes visually
      if (!$(this).is(':checked'))
      {
        $(this).attr('checked','checked');  
      }
      else
      {
        $(this).removeAttr('checked');  
      }
      
      if ($(this).is(':checked'))
      {
        $(this).removeAttr('checked');
        
        checkboxes.each(
          function (index, element)
          {
            $(element).removeAttr('checked');   
          }
        );
      }
      else
      {
        $(this).attr('checked','checked');
        
        checkboxes.each(
          function (index, element)
          {
            $(element).attr('checked','checked');           
          }
        );          
      }
    }
  );
}

function addEventForListGonghuiBtn()
{
  $('#listGonghuiBtn').click(
    function (e)
    {
      e.preventDefault();
      
      $('#gonghui').toggle();                
    }    
  ); 
}

function addEventForGonghuiWrapper()
{
  $('#gonghuiWrapper').bind('clickoutside', 
    function (e)
    {
      $('#gonghui').hide();
      
      // Clear any checked checkboxes
      $('input[id^=gonghui_]').each(
        function (index, element)
        {
          $(element).removeAttr('checked');
        }
      );
    }
  );
}