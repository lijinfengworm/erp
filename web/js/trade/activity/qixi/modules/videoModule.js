define(function(){
    window.Modernizr=function(a,b,c){function d(a){n.cssText=a}function e(a,b){return typeof a===b}var f,g,h,i="2.6.2",j={},k=b.documentElement,l="modernizr",m=b.createElement(l),n=m.style,o=({}.toString," -webkit- -moz- -o- -ms- ".split(" ")),p={},q=[],r=q.slice,s=function(a,c,d,e){var f,g,h,i,j=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))for(;d--;)h=b.createElement("div"),h.id=e?e[d]:l+(d+1),j.appendChild(h);return f=["&#173;",'<style id="s',l,'">',a,"</style>"].join(""),j.id=l,(m?j:n).innerHTML+=f,n.appendChild(j),m||(n.style.background="",n.style.overflow="hidden",i=k.style.overflow,k.style.overflow="hidden",k.appendChild(n)),g=c(j,a),m?j.parentNode.removeChild(j):(n.parentNode.removeChild(n),k.style.overflow=i),!!g},t={}.hasOwnProperty;h=e(t,"undefined")||e(t.call,"undefined")?function(a,b){return b in a&&e(a.constructor.prototype[b],"undefined")}:function(a,b){return t.call(a,b)},Function.prototype.bind||(Function.prototype.bind=function(a){var b=this;if("function"!=typeof b)throw new TypeError;var c=r.call(arguments,1),d=function(){if(this instanceof d){var e=function(){};e.prototype=b.prototype;var f=new e,g=b.apply(f,c.concat(r.call(arguments)));return Object(g)===g?g:f}return b.apply(a,c.concat(r.call(arguments)))};return d}),p.touch=function(){var c;return"ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch?c=!0:s(["@media (",o.join("touch-enabled),("),l,")","{#modernizr{top:9px;position:absolute}}"].join(""),function(a){c=9===a.offsetTop}),c},p.video=function(){var a=b.createElement("video"),c=!1;try{(c=!!a.canPlayType)&&(c=new Boolean(c),c.ogg=a.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),c.h264=a.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),c.webm=a.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,""))}catch(d){}return c},p.audio=function(){var a=b.createElement("audio"),c=!1;try{(c=!!a.canPlayType)&&(c=new Boolean(c),c.ogg=a.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/,""),c.mp3=a.canPlayType("audio/mpeg;").replace(/^no$/,""),c.wav=a.canPlayType('audio/wav; codecs="1"').replace(/^no$/,""),c.m4a=(a.canPlayType("audio/x-m4a;")||a.canPlayType("audio/aac;")).replace(/^no$/,""))}catch(d){}return c};for(var u in p)h(p,u)&&(g=u.toLowerCase(),j[g]=p[u](),q.push((j[g]?"":"no-")+g));return j.addTest=function(a,b){if("object"==typeof a)for(var d in a)h(a,d)&&j.addTest(d,a[d]);else{if(a=a.toLowerCase(),j[a]!==c)return j;b="function"==typeof b?b():b,"undefined"!=typeof enableClasses&&enableClasses&&(k.className+=" "+(b?"":"no-")+a),j[a]=b}return j},d(""),m=f=null,j._version=i,j._prefixes=o,j.testStyles=s,j}(this,this.document)
    var videoModule = function(a){
        function b() {
            j = function(a) {
                switch (a) {
                case "EMPTY":
                    //log("media buffering");
                    break;
                case "FULL":
                    //log("media buffer full")
                }
            },
            k = function(a) {
                //log("--> progress: " + a),
                100 === a && p.startHandler()
            },
            l = function() {
                //log("media starts")
            },
            m = function(a) {
                switch (a) {
                case "AWAKENING":
                    //log("media awakening");
                    break;
                case "STARTING":                
                    //log("media starting");
                    break;
                case "PLAYING":
                    //log("media playing");             
                    break;
                case "COMPLETED":
                    //log("media playlist item complete")               
                    break;
                }
               
            },
            n = function(a) {
                i.getDuration() > 0 && a > i.getDuration() - y && (A ? (i.setPause(), i.setPlayhead(.1), i.setPlay()) : p.completeHandler())
            }
        }
        function c() {
            var a = null;
            Modernizr.touch ? (a = s ? {
                0 : {
                    src: r + C + ".mp3",
                    type: "audio/mp3"
                }
            }: {
                0 : {
                    src: r + C + ".mp4",
                    type: "video/mp4"
                },
                config: {
                    disablePause: v
                }
            },
            i = projekktor("#" + E, {
                autoplay: 1,
                controls: u,
                height: x,
                loop: A,
                thereCanBeOnlyOne: I,
                volume: J,
                width: w,
                playlist: [a]         
            },
            function(a) {
                a.addListener("buffer", j),
                a.addListener("progress", k),
                a.addListener("start", l),
                a.addListener("state", m),
                a.addListener("time", n)
            })):(a = s ? {
                0 : {
                    src: r + B + ".mp3",
                    type: "audio/mp3"
                },
                1 : {
                    src: r + B + ".ogg",
                    type: "audio/ogg"
                },
                config: {
                    disablePause: v,
                    enableKeyboard: !1
                }
            }: {
                0 : {
                    src: r + B + ".webm",
                    type: "video/webm"
                },   
                1 : {
                    src: r + B + ".mp4",
                    type: "video/mp4"
                },                             
                2 : {
                    src: r + B + ".ogv",
                    type: "video/ogg"
                },
                config: {
                    disablePause: v,
                    enableKeyboard: !1
                }
            },
            i = projekktor("#" + E, {
                autoplay: !1,
                controls: u,
                height: x,
                loop: A,
                thereCanBeOnlyOne: I,
                volume: J,
                width: w,
                playerFlashMP4: F+"swf/StrobeMediaPlayback/StrobeMediaPlayback.swf",
                playerFlashMP3: F+"swf/StrobeMediaPlayback/StrobeMediaPlayback.swf",
                playlist: [a]                       
            },
            function(a) {
                a.addListener("buffer", j),
                a.addListener("progress", k),
                a.addListener("start", l),
                a.addListener("state", m),
                a.addListener("time", n),
                t && a.setPlay()
            })),
            s || (o.resizeMedia(), $(window).resize(o.resizeMedia))
        }
        function h() {
            0 === i.getVolume() ? (i.setVolume(100), J = 100, R.find("img").attr("src", R.find("img").attr("src").replace("off.png", "on.png")), $(o).trigger("MEDIA_UNMUTED")) : (i.setVolume(0), J = 0, R.find("img").attr("src", R.find("img").attr("src").replace("on.png", "off.png")), $(o).trigger("MEDIA_MUTED"))
        }
        var i, j, k, l, m, n, o = this,
        p = this.constructor.prototype,
        q = {
            akamaiBaseDirectory: "",
            audioOnly: !1,
            autoplay: !1,
            controls: !1,
            disablePause: !0,
            displayWidth: 1600,
            displayHeight: 800,
            fadeOutTime: 1,
            iPadPlayBtnID: null,
            loop: !1,
            mediaSrc: "",
            mediaSrciPad: "",
            mediaTargetID: "mediaTarget",
            playerName: "vplayer",
            projectDirectory: "",
            skipBtnID: null,
            soundBtnID: null,
            thereCanBeOnlyOne: !0,
            volume: .8
        },
        r = void 0 !== a.akamaiBaseDirectory ? a.akamaiBaseDirectory: q.akamaiBaseDirectory,
        s = void 0 !== a.audioOnly ? a.audioOnly: q.audioOnly,
        t = void 0 !== a.autoplay ? a.autoplay: q.autoplay,
        u = void 0 !== a.controls ? a.controls: q.controls,
        v = void 0 !== a.disablePause ? a.disablePause: q.disablePause,
        w = void 0 !== a.displayWidth ? a.displayWidth: q.displayWidth,
        x = void 0 !== a.displayHeight ? a.displayHeight: q.displayHeight,
        y = void 0 !== a.fadeOutTime ? a.fadeOutTime: q.fadeOutTime,
        z = void 0 !== a.iPadPlayBtnID ? a.iPadPlayBtnID: q.iPadPlayBtnID,
        A = void 0 !== a.loop ? a.loop: q.loop,
        B = void 0 !== a.mediaSrc ? a.mediaSrc: q.mediaSrc,
        C = void 0 !== a.mediaSrciPad ? a.mediaSrciPad: q.mediaSrciPad,
        D = void 0 !== a.mediaTargetID ? a.mediaTargetID: q.mediaTargetID,
        E = void 0 !== a.playerName ? a.playerName: q.playerName,
        F = void 0 !== a.projectDirectory ? a.projectDirectory: q.projectDirectory,
        G = void 0 !== a.skipBtnID ? a.skipBtnID: q.skipBtnID,
        H = void 0 !== a.soundBtnID ? a.soundBtnID: q.soundBtnID,
        I = void 0 !== a.thereCanBeOnlyOne ? a.thereCanBeOnlyOne: q.thereCanBeOnlyOne,
        J = void 0 !== a.volume ? a.volume: q.volume,
        K = x / w,
        M = !0,
        N = !1,
        O = $("#" + z),
        P = $("#" + D),
        Q = $("#" + G),
        R = $("#" + H);
        o.init = function() {
            P.html(s ? '<audio id="' + E + '"></audio>': '<video id="' + E + '"></video>'),
            //log("--> _projectDirectory: " + F),
            //log("--> $iPadPlayBtn: " + O.attr("id")),
            c(),
            b()            
        },
        o.resizeMedia = function() {
            var a = P.width();
            i.setSize({
                width: a + "px",
                height: a * K + "px"
            })
        },
        o.play = function() {
            //log("PLAY"),      
            i.setPlay()
        },
        o.pause = function() {
            i.setPause()
        },
        o.playHead = function(a) {
            i.setPlayhead(a)
        },
        o.setVolume = function(a) {
            //log("SET VOL"),
            i.setVolume(a)
        },
        o.stop = function() {       
            i.removeListener("buffer", j),
            i.removeListener("progress", k),
            i.removeListener("start", l),
            i.removeListener("state", m),
            i.setPause(),
            p.completeHandler()
        },
        p.startHandler = function() {
            $(o).trigger("MEDIA_START"),
            i && i.setVolume(J),
            M || i.addListener("time", n),
            Modernizr.touch || null === H || f(),
            null !== G && d()
        },
        p.completeHandler = function() {
            $(o).trigger(N ? "MEDIA_SKIPPED": "MEDIA_COMPLETE"),
            i.removeListener("time", n),
            M = !1,
            Modernizr.touch || null === H || g(),
            null !== G && e()
        },
        o.kill = function() {
            //log("player kill"),
            i.selfDestruct(),
            i = null,
            P.empty(),
            $(window).off("resize", o.resizeMedia)
        }
    };

    return videoModule;
})