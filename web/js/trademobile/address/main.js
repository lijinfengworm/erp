requirejs.config({
    // urlArgs: "bust=" +  (new Date()).getTime(),
    urlArgs: "bust=2015063005",
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
        "address": "address/address"
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

requirejs(['address'], function(address) {
    address.init();
})