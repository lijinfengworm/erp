
function itemUp(type,id){
    var shoeid ='';
    var allid ='';
    if(type == 'shoe'){
        shoeid = id;
    }else{
        allid = id;
    }
    
    postItemUp(allid,shoeid)
    return false;
}
function postItemUp(allid,shoeid){
    $.post(itemUpUrl,{"allid":allid,"shoeid":shoeid},function(data){
        tipCommon(data.msg);
    },"json");
}
function itemAllup(){
    postItemUp(allId,shoeId);
}