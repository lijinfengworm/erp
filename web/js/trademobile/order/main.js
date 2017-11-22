requirejs.config({
    // urlArgs: "bust=" +  (new Date()).getTime(),
    urlArgs: "bust=2015072301",
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
        "confirm": "order/confirm"
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