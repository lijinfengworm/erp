define(function(){

var gallery_slider = function(option){
    "use strict";
    function onThumbnailPrev()
    {        
        if( ! _isReady) return false;
        _isReady = false;

        var len = $(_imgs).length - 1;

        var mx = $(_imgs).eq(len).width() + _cellmargin;
        $(_tc).css("left", -mx + "px");

        var stack = $(_tc).children().eq(len).detach();
        $(_tc).prepend(stack);

        $(_tc).animate({left: "0px"}, 300, function()
        {

            _isReady = true;
        });

        return false;
    }

    function onThumbnailNext()
    {                        
        if( ! _isReady) return false;
        _isReady = false;

        var mx = $(_imgs).width() + _cellmargin;
         
        $(_tc).animate({left: -mx + "px"}, 300, function()
        {
            $(_tc).css("left", "0px");

            var stack = $(_tc).children().eq(0).detach();
            $(_tc).append(stack);

            _isReady = true;
        });

        return false;
    }
        
    var t = this,
    _thumb_width,
    _isReady = true,
    setting = {
        tc: "#tc_container",
        img : "#tc_container img",
        prev: "#tc_l",
        next : "#tc_r",
        cellmargin : 20
    },
    _tc = void 0 !== option.tc ? option.tc : setting.tc,
    _imgs = void 0 !==  option.img ? option.img : setting.img,      
    _prev = void 0 !==  option.prev ? option.prev : setting.prev,   
    _next = void 0 !==  option.next ? option.next : setting.next,
    _cellmargin = void 0 !== option.cellmargin ? option.cellmargin : setting.cellmargin;

    this.init=function(option){       
        var w = 0;
        for (var i = 0; i < $(_imgs).length; i++) {                        
            w += $(_imgs).parent().eq(i).width()+setting.cellmargin;
        }
        if($(_imgs).length>3){
            //$(_tc).parent().css({"width": "480px","float":"left","display":"block","margin":"0 auto"});
            $(_prev).fadeIn();
            $(_next).fadeIn();            
        }        
        $(_prev).live("click", onThumbnailPrev);
        $(_next).live("click", onThumbnailNext);                          
        
        _thumb_width = w + (_cellmargin * $(_imgs).length);     
        $(_tc).width(_thumb_width);             
    }   
}

    return gallery_slider
})