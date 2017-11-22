/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function vancl_sustain(type,e){
    if(!user_id)
    {
        commonLogin(); return false;
    }
    $.post($(e).attr('href'), {match_id:match_id,type:type}, function(data){
            if(data.code < 0)
            {
                alert(data.msg);
            }else{
                alert(data.msg);
            }
   }, "json");
}
function submit_answer(question_id){
    if(!user_id)
    {
        commonLogin(); return false;
    }
    var key = $("input[name='answer_radio_"+question_id+"']:checked").val();
    $.post(answer_url, {question_id:question_id,key:key}, function(data){
            if(data.code < 0)
            {
                alert(data.msg);
            }else{
                alert(data.msg);
            }
   }, "json");    
}
$("#choujia").click(function(){
    $.post(award_url,{match_id:match_id},function(data){
        if(data.code < 0)
            {
                alert(data.msg);
            }else{
                alert(data.msg);
            }
    },"json");
})
