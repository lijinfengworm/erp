define(function(){
    function getW() {
      var client_h, client_w, scrollTop;
      client_h = document.documentElement.clientHeight || document.body.clientHeight;
      client_w = document.documentElement.clientWidth || document.body.clientWidth;
      screen_h = document.documentElement.scrollHeight || document.body.scrollHeight;
      scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
      return o = {
        w: client_w,
        h: client_h,
        s: scrollTop,
        s_h: screen_h
      };
    }
    return getW
})