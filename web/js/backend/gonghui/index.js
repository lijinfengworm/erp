$(document).ready(
  function ()
  {
    addEventForCreateNewGonghuiBtn();
  }
);

function addEventForCreateNewGonghuiBtn()
{
  $('#createNewGonghuiBtn').click(
    function (event)
    {
      $('#editGonghuiFormContainer').toggle();
    }
  );
}