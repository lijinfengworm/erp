requirejs.config({
    // urlArgs: "bust=" +  (new Date()).getTime(),
    baseUrl: "http://kaluli.hoopchina.com.cn/js/trademobile/order/",
    urlArgs: "v=2015072301",
    paths: {
        "jquery": "../lib/zepto",
        "underscore": "../lib/underscore",
        "backbone": "../lib/backbone",
        "fastclick": "../lib/fastclick",
        "md5": "../lib/md5",
        "fx": "../lib/zepto.fx",
        "alertbox": "../lib/zepto.alertbox",
        "tip": "../lib/zepto.tip",
        "confirm": "buy/confirm"
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

requirejs(['confirm'], function(confirm) {
    confirm.init();
})