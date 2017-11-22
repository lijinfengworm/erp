requirejs.config({
    // urlArgs: "bust=" +  (new Date()).getTime(),
    urlArgs: "v=2016010701",
    baseUrl: "http://kaluli.hoopchina.com.cn/js/trademobile/",
    paths: {
        "jquery": "lib/zepto",
        "underscore": "lib/underscore",
        "backbone": "lib/backbone",
        "fastclick": "lib/fastclick",
        "md5": "lib/md5",
        "fx": "lib/zepto.fx",
        "alertbox": "lib/zepto.alertbox",
        "tip": "lib/zepto.tip",
        "clamp": "lib/clamp",
        "cart": "cart/cart"
    },
    shim: {
        'underscore': {
            exports: '_'
        },
        'jquery': {
            exports: '$'
        },
        'fx': {
            deps: ['jquery']
        },
        'backbone': {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        }
    }
});

requirejs(['cart'], function(cart) {
    cart.init();
})