$(document).ready(
  function ()
  {
    addEventForFilterElement();
  }
);

function addEventForFilterElement()
{
  $('a[id^=reward_]').click(
    function (event)
    {
      event.preventDefault();
      
      filterOrders(this.id.split('_')[1]);
    }
  );
}

function filterOrders(date)
{
  var dateParts = date.split('-');
  
  $('#filterSourceType').val('11'); 
  
  $('#filterFromYear').val(dateParts[0]);
  $('#filterFromMonth').val(dateParts[1]);
  $('#filterFromDay').val(dateParts[2]);     

  $('#filterToYear').val(dateParts[0]);
  $('#filterToMonth').val(dateParts[1]);
  $('#filterToDay').val(dateParts[2]);     
  
  $('#form-filter').submit();

}