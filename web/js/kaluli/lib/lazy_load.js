/*
 * Lazy Load - jQuery plugin for lazy loading images
 *
 * Copyright (c) 2007-2009 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   //www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   //www.appelsiini.net/projects/lazyload
 *
 * Version:  1.5.0
 *
 */
(function($) {

    $.fn.lazyload = function(options) {
        var settings = {
            placeholder  : null,
            threshold    : 0,
            failurelimit : 0,
            event        : "scroll",
            effect       : "show",
            container    : window,
            callBack     : null
        };
                
        if(options) {
            $.extend(settings, options);
        }

        /* Fire one scroll event per scroll. Not one scroll event per image. */
        var elements = this;
        if ("scroll" == settings.event) {
            $(settings.container).bind("scroll", function(event) {
                
                var counter = 0;
                elements.each(function() {
                    if ($.abovethetop(this, settings) ||
                        $.leftofbegin(this, settings)) {
                            /* Nothing. */
                        
                           
                    } else if (!$.belowthefold(this, settings) &&
                        !$.rightoffold(this, settings)) {
                        if($.browser.msie){
                            $(this).attr("src",$(this).attr("lazy_src"));
                        }
                        
                        // alert($(this).attr("lazy_src"));                        
                        $(this).trigger("appear");
                        settings.callBack && $.isFunction(settings.callBack) && settings.callBack(this);
                    } else {
                        if (counter++ > settings.failurelimit) {
                            return false;
                        }
                    }
                   
                });
                /* Remove image from array so it is not looped next time. */
                var temp = $.grep(elements, function(element) {
                    return !element.loaded;
                });
                elements = $(temp);
            });
        }
        
        var dateType = window.dateType ? false : true;
        this.each(function() {            
            var self = this;            
            /* Save original only if it is not defined in HTML. */
            if (undefined == $(self).attr("original")) {
                $(self).attr("original", $(self).attr("lazy_src"));     
            }                                    
            if ("scroll" != settings.event || 
                    undefined == $(self).attr("src") || 
                    settings.placeholder == $(self).attr("src") || 
                    ($.abovethetop(self, settings) ||
                     $.leftofbegin(self, settings) || 
                     $.belowthefold(self, settings) || 
                     $.rightoffold(self, settings) )) {
                        
                if (settings.placeholder) {
                    $(self).attr("src", settings.placeholder);
                    
                } else {
                    $(self).removeAttr("src");
                }
                self.loaded = false;
            } else {
                self.loaded = true;
            }
            //console.log(settings.event)   
            /* When appear is triggered load original image. */
            $(self).one("appear", function() {      
                //console.log(this.loaded)         
                if (!this.loaded) {
                    $("<img />")
                        .bind("load", function() {
                            $(self)
                                .hide()
                                .attr("src", $(self).attr("lazy_src"))
                                [settings.effect](settings.effectspeed);
                            
                            //index tout load ok
                            if($(self).parents(".skulist_index").length>0){
                                $(self).css("margin","10px");
                            }
                            //list page load ok
                            if($(self).parent(".skul_spic").length>0){
                                $(self).css("margin-top","");
                            }
                            
                            self.loaded = true;
                           // $(self).removeAttr("lazy_src")
                            //$(self).removeAttr("original").removeAttr("lazy_src");
                        })
                        .attr("src", $(self).attr("original")); 
                  
                };
            });

            /* When wanted event is triggered load original image */
            /* by triggering appear.                              */
            if ("scroll" != settings.event) {
                $(self).bind(settings.event, function(event) {
                    if (!self.loaded) {
                        $(self).trigger("appear");
                    }
                });
            }
        });
        
        /* Force initial check if images should appear. */
        if(dateType){
            $(settings.container).trigger(settings.event);
            window.dateType = true;
        }
        return this;

    };

    /* Convenience methods in jQuery namespace.           */
    /* Use as  $.belowthefold(element, {threshold : 100, container : window}) */

    $.belowthefold = function(element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).height() + $(window).scrollTop();
        } else {
            var fold = $(settings.container).offset().top + $(settings.container).height();
        }
        return fold <= $(element).offset().top - settings.threshold;
    };
    
    $.rightoffold = function(element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).width() + $(window).scrollLeft();
        } else {
            var fold = $(settings.container).offset().left + $(settings.container).width();
        }
        return fold <= $(element).offset().left - settings.threshold;
    };
        
    $.abovethetop = function(element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).scrollTop();
        } else {
            var fold = $(settings.container).offset().top;
        }
        return fold >= $(element).offset().top + settings.threshold  + $(element).height();
    };
    
    $.leftofbegin = function(element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).scrollLeft();
        } else {
            var fold = $(settings.container).offset().left;
        }
        return fold >= $(element).offset().left + settings.threshold + $(element).width();
    };
    /* Custom selectors for your convenience.   */
    /* Use as $("img:below-the-fold").something() */

    $.extend($.expr[':'], {
        "below-the-fold" : "$.belowthefold(a, {threshold : 0, container: window})",
        "above-the-fold" : "!$.belowthefold(a, {threshold : 0, container: window})",
        "right-of-fold"  : "$.rightoffold(a, {threshold : 0, container: window})",
        "left-of-fold"   : "!$.rightoffold(a, {threshold : 0, container: window})"
    });
    
})(jQuery);
