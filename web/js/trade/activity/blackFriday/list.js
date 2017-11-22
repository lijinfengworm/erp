var app = angular.module('app',[]);

app.config(function($httpProvider){
    // Use x-www-form-urlencoded Content-Type
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    $httpProvider.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
    /**
     * The workhorse; converts an object to x-www-form-urlencoded serialization.
     * @param {Object} obj
     * @return {String}
     */
    var param = function(obj) {
        var query = '', name, value, fullSubName, subName, subValue, innerObj, i;

        for(name in obj) {
            value = obj[name];

            if(value instanceof Array) {
                for(i=0; i<value.length; ++i) {
                    subValue = value[i];
                    fullSubName = name + '[' + i + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if(value instanceof Object) {
                for(subName in value) {
                    subValue = value[subName];
                    fullSubName = name + '[' + subName + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if(value !== undefined && value !== null)
                query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
        }

        return query.length ? query.substr(0, query.length - 1) : query;
    };

    // Override $http service's default transformRequest
    $httpProvider.defaults.transformRequest = [function(data) {
        return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
    }];
});

// I lazily load the images, when they come into view.
app.directive("bnLazySrc", function( $window, $document ) {
    // I manage all the images that are currently being
    // monitored on the page for lazy loading.
    var lazyLoader = (function() {
        // list of images that lazy-loading and have yet to be rendered
        var images = [];

        // render timer for lazy loading images so that DOM-querying (for offsets) is chunked in groups
        var renderTimer = null;
        var renderDelay = 100;

        // cache the window element as a jQuery reference
        var win = $( $window );

        // cache the document document height so that we can respond to changes in the height due to dynamic content
        var doc = $document;
        var documentHeight = doc.height();
        var documentTimer = null;
        var documentDelay = 2000;

        // determine if window dimension events (ie. resize, scroll) are currenlty being monitored for changes
        var isWatchingWindow = false;

        // ---
        // PUBLIC METHODS.
        // ---

        // I start monitoring the given image for visibility
        // and then render it when necessary.
        function addImage( image ) {
            images.push( image );
            if ( ! renderTimer ) startRenderTimer();
            if ( ! isWatchingWindow ) startWatchingWindow();
        }

        // I remove the given image from the render queue.
        function removeImage( image ) {
            // Remove the given image from the render queue.
            for ( var i = 0 ; i < images.length ; i++ ) {
                if ( images[ i ] === image ) {
                    images.splice( i, 1 );
                    break;
                }
            }

            // If removing the given image has cleared the render queue, we can stop monitoring window and image queue
            if ( ! images.length ) {
                clearRenderTimer();
                stopWatchingWindow();
            }
        }

        // ---
        // PRIVATE METHODS.
        // ---

        // I check the document height to see if it's changed.
        function checkDocumentHeight() {
            // If render time is currently active: don't bother getting the document height - it won't actually do anything
            if ( renderTimer ) return;

            var currentDocumentHeight = doc.height();

            // Cancel if height has not changed - no more images could have come into view
            if ( currentDocumentHeight === documentHeight ) return;

            // Cache the new document height.
            documentHeight = currentDocumentHeight;

            startRenderTimer();
        }

        // I check the lazy-load images that have yet to be rendered.
        function checkImages() {
            // Log here so we can see how often this gets called during page activity.
            //console.log( "Checking for visible images..." );

            var visible = [];
            var hidden = [];

            // Determine the window dimensions.
            var windowHeight = win.height();
            var scrollTop = win.scrollTop();

            // Calculate the viewport offsets.
            var topFoldOffset = scrollTop;
            var bottomFoldOffset = ( topFoldOffset + windowHeight );

            // Query the DOM for layout and seperate the
            // images into two different categories: those
            // that are now in the viewport and those that
            // still remain hidden.
            for ( var i = 0 ; i < images.length ; i++ ) {
                var image = images[ i ];
                if ( image.isVisible( topFoldOffset, bottomFoldOffset ) ) {
                    visible.push( image );
                } else {
                    hidden.push( image );
                }
            }

            // Update the DOM with new image source values.
            for ( var i = 0 ; i < visible.length ; i++ ) {
                visible[ i ].render();
            }

            // Keep the still-hidden images as the new image queue to be monitored.
            images = hidden;

            // Clear the render timer so that it can be set again in response to window changes.
            clearRenderTimer();

            // If we've rendered all the images, then stop monitoring the window for changes.
            if ( ! images.length ) {
                stopWatchingWindow();
            }
        }

        // I clear the render timer so that we can easily
        // check to see if the timer is running.
        function clearRenderTimer() {
            clearTimeout( renderTimer );
            renderTimer = null;
        }

        // I start the render time, allowing more images to
        // be added to the images queue before the render
        // action is executed.
        function startRenderTimer() {
            renderTimer = setTimeout( checkImages, renderDelay );
        }

        // I start watching the window for changes in dimension.
        function startWatchingWindow() {
            isWatchingWindow = true;

            // Listen for window changes.
            win.on( "resize.bnLazySrc", windowChanged );
            win.on( "scroll.bnLazySrc", windowChanged );

            // Set up a timer to watch for document-height changes.
            documentTimer = setInterval( checkDocumentHeight, documentDelay );
        }

        // I stop watching the window for changes in dimension.
        function stopWatchingWindow() {
            isWatchingWindow = false;

            // Stop watching for window changes.
            win.off( "resize.bnLazySrc" );
            win.off( "scroll.bnLazySrc" );

            // Stop watching for document changes.
            clearInterval( documentTimer );
        }

        // I start the render time if the window changes.
        function windowChanged() {
            if ( ! renderTimer ) startRenderTimer();
        }

        // Return the public API.
        return({
            addImage: addImage,
            removeImage: removeImage
        });

    })();

    // ------------------------------------------ //
    // ------------------------------------------ //

    // representation of a single lazy-load image
    function LazyImage( element ) {
        // I am the interpolated LAZY SRC attribute of the image as reported by AngularJS.
        var source = null;

        // I determine if the image has already been rendered (ie, that it has been exposed to the
        // viewport and the source had been loaded).
        var isRendered = false;

        // cached height of the element (we assume that the image doesn't change height over time)
        var height = null;

        // ---
        // PUBLIC METHODS.
        // ---

        // I determine if the element is above the given fold of the page.
        function isVisible( topFoldOffset, bottomFoldOffset ) {
            // If the element is not visible because it is hidden, don't bother testing it.
            if ( ! element.is( ":visible" ) ) return( false );

            // If the height has not yet been calculated, the cache it for the duration of the page.
            if ( height === null ) {
                height = element.height();
            }

            // Update the dimensions of the element.
            var top = element.offset().top;
            var bottom = ( top + height );

            // Return true if the element is:
            // 1. The top offset is in view.
            // 2. The bottom offset is in view.
            // 3. The element is overlapping the viewport.
            return(
            (
            ( top <= bottomFoldOffset ) &&
            ( top >= topFoldOffset )
            )
            ||
            (
            ( bottom <= bottomFoldOffset ) &&
            ( bottom >= topFoldOffset )
            )
            ||
            (
            ( top <= topFoldOffset ) &&
            ( bottom >= bottomFoldOffset )
            )
            );
        }

        // move cached source into the live source
        function render() {
            isRendered = true;
            renderSource();
        }

        // set the interpolated source value reported by the directive / AngularJS
        function setSource( newSource ) {
            source = newSource;
            if ( isRendered ) renderSource();
        }

        // ---
        // PRIVATE METHODS.
        // ---

        // load the lazy source value into the actual source value of the image element.
        function renderSource() {
            element[ 0 ].src = source;
        }

        // Return the public API
        return({
            isVisible: isVisible,
            render: render,
            setSource: setSource
        });

    }

    // ------------------------------------------ //
    // ------------------------------------------ //

    // bind the UI events to the scope.
    function link( $scope, element, attributes ) {
        var lazyImage = new LazyImage( element );

        // Start watching the image for changes in its visibility.
        lazyLoader.addImage( lazyImage );

        // Since the lazy-src will likely need some sort of string interpolation, we don't want to
        attributes.$observe(
            "bnLazySrc",
            function( newSource ) {
                lazyImage.setSource( newSource );
            }
        );

        // When the scope is destroyed, we need to remove the image from the render queue.
        $scope.$on(
            "$destroy",
            function() {
                lazyLoader.removeImage( lazyImage );
            }
        );
    }

    // Return the directive configuration.
    return({
        link: link,
        restrict: "A"
    });
});

app.directive('pvScrolled', function () {
    return function (scope, elm, attr) {
        var raw = elm[0];
        elm.bind('scroll', function () {
            if (raw.scrollTop + raw.offsetHeight >= raw.scrollHeight) {
                scope.$apply(attr.pvScrolled);
            }
        });
    };
});

app.controller('blackfridayList',function($scope,$filter,$http){

    var res = {
        method:"post",
        url:"http://www.shihuo.cn/activity/daigou",
        data:{page:1}
    }

    $scope.backtotop = function(){
        $("body,html").stop(true,true).animate({scrollTop:0},600,'swing');
    };
    $http(res).success(function(response){
        $scope.words = response.words;
        $scope.product = response.product;
        $scope.choosed = response.choosed;
    });

    $scope.search = function(keywords){
        var thiskey,root_name,brand;
        if(keywords){
            var filter = $("[data-title='"+keywords+"']").parents(".item-list").attr("id");
            switch(filter){
                case "brand" : brand = keywords;
                    break;
                case "root_name" : root_name = keywords;
                    break;
                case "keywords" : thiskey = keywords;
                    break;
                default:
                    thiskey = keywords;
            }
        }else{
            thiskey = $scope.keywords;
            root_name = $scope.choosed.root_name;
            brand = $scope.choosed.brand;
        }
        var res = {
            method:"post",
            url:"http://www.shihuo.cn/activity/daigou",
            data:{keywords:thiskey,page:1,root_name:root_name,brand:brand}
        };

        $scope.loading=true;
        $http(res).success(function(response){
            $scope.loading=false;
            if(response.product == ""){
                $scope.empty =  'true';
            }else{
                $scope.empty =  'false';
            }
            var arr =["brand","root_name","keywords"];
            for(var i in response.choosed){
                if(i == "brand" || i == "root_name"){
                    $("#"+i+" li").removeClass("active");
                    if(response.choosed[i]){
                        $("#"+i+" li[data-title='"+response.choosed[i]+"']").addClass("active");
                    }
                    $("#keywords li").removeClass("active");
                }else if(i == "keywords"){
                    $(".item-list li").removeClass("active");
                    $("#keywords li[data-title='"+response.choosed[i]+"']").addClass("active");
                }
                arr.push(i);
            }

            for(var s=0;s<arr.length;s++){
                typeof response.choosed[arr[s]] == "undefined" && $("li","#"+arr[s]).removeClass("active");
            }

            $scope.choosed = response.choosed;
            $scope.product = response.product;

        });
        $("body,html").stop(true,true).animate({scrollTop:$(".pro-list").offset().top},600,'swing');
    };

    function load(page){
        res.data={
            page:page,
            keywords:$scope.choosed.keywords,
            brand:$scope.choosed.brand,
            root_name:$scope.choosed.root_name
        };
        $http(res).success(function(response){
            $scope.loading=false;
            $scope.choosed.page = res.data.page;
            $scope.product =  $scope.product || [];

            $scope.product.push.apply($scope.product, response.product);
        });
    }

    $(window).scroll(function(){
        scrollHandle();
        if($(".pro-list-content li").length < 60 || $scope.loading){
            return false
        }
       if($(window).scrollTop() >= ($("body").height()-$(window).height())){
           var page = $scope.choosed.page + 1;
           $scope.loading=true;
           load(page);
       }
    });

    $(".grid li").live("click",function(){
        var filtername = $(this).hasClass("active") ? "" : $("a",this).text(),
            filter = $(this).parents('.item-list').attr("id"),
            index = $(this).index();

        switch(filter){
            case "brand" : $scope.choosed.brand = filtername;
            break;
            case "root_name" : $scope.choosed.root_name = filtername;
            break;
            case "keywords" : $scope.choosed.keywords = filtername;
            break;
        }
        filter == "keywords" ? $scope.search(filtername) : $scope.search();
        //$scope.choosed.keywords = keywords;
        $(this).hasClass("active") && $(this).removeClass("active");
    });

    $("#searchBtn").keydown(function(e){
        if(e.keyCode==13){
            $(".search-btn").trigger("click");
        }
    });

    function scrollHandle(){
        if($(window).scrollTop() > 400){
            $(".backto-top").css("visibility","visible");
        }else{
            $(".backto-top").css("visibility","hidden");
        }
    }
});

