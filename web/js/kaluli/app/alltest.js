/**
 * Created by jiangyanghe on 16/4/25.
 */
$(function(){
    /**
     * 众测页面进行中，已完成的tab切换
     */
    $('#alltest_tab li').click(function(){
        $('#alltest_tab li').css('color','#bababa');
        if($(this).hasClass('processing')){
            $(this).css('color','#fd6732');
            $('#processing').show();
            $('#done').hide();
        }else if($(this).hasClass('done')){
            $(this).css('color','#34d4d4');
            $('#done').show();
            $('#processing').hide();
        }
    });
});