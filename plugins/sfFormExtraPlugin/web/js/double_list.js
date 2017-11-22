
//用户管理页面判断是否有wgreader权限决定是否显示游戏列表
$(document).ready(function(){
    var values = $("#backend_user_acl").get(0).options;
    for(var i = 0; i< values.length; i++){
        if(values[i].text == 'wgreader'){
            return true;
        }
    }
    $('.sf_admin_form_field_wp_game_list').addClass('hidden');
});

var sfDoubleList =
{
  init: function(id, className)
  {
    form = sfDoubleList.get_current_form(id);

    callback = function() { sfDoubleList.submit(form, className) };

    if (form.addEventListener)
    {
      form.addEventListener("submit", callback, false);
    }
    else if (form.attachEvent)
    {
      var r = form.attachEvent("onsubmit", callback);
    }
  },

  move: function(srcId, destId)
  {
    var src = document.getElementById(srcId);
    var dest = document.getElementById(destId);
    for (var i = 0; i < src.options.length; i++)
    {
      if (src.options[i].selected)
      {
        var option =  new Option(src.options[i].text, src.options[i].value);
        dest.options[dest.length] = option;
        src.options[i] = null;
        --i;
        if(option.text == 'wgreader'){
            var game_list = $('.sf_admin_form_field_wp_game_list');
            if("backend_user_acl" == srcId){
                game_list.removeClass('show');
                game_list.addClass('hidden');
            }else{
                game_list.removeClass('hidden');
                game_list.addClass('show');
            }
        }
      }
    }
  },

  submit: function(form, className)
  {
    var element;

    for (var i = 0; i < form.elements.length; i++)
    {
      element = form.elements[i];
      if (element.type == 'select-multiple')
      {
        if (element.className == className + '-selected')
        {
          for (var j = 0; j < element.options.length; j++)
          {
            element.options[j].selected = true;
          }
        }
      }
    }
  },

  get_current_form: function(el)
  {
    if ("form" != el.tagName.toLowerCase())
    {
      return sfDoubleList.get_current_form(el.parentNode);
    }

    return el;
  }
};

function gamelist(){
      var values = $("#backend_user_acl").get(0).options;
      for(var i = 0; i< values.length; i++){
          if(values[i].text == 'wgreader'){
              return true;
          }
      }
      $('.sf_admin_form_field_wp_game_list').addClass('hidden');
}
