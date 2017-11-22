(function(){function n(n){function t(t,r,e,u,i,o){for(;i>=0&&o>i;i+=n){var a=u?u[i]:i;e=r(e,t[a],a,t)}return e}return function(r,e,u,i){e=b(e,i,4);var o=!k(r)&&m.keys(r),a=(o||r).length,c=n>0?0:a-1;return arguments.length<3&&(u=r[o?o[c]:c],c+=n),t(r,e,u,o,c,a)}}function t(n){return function(t,r,e){r=x(r,e);for(var u=O(t),i=n>0?0:u-1;i>=0&&u>i;i+=n)if(r(t[i],i,t))return i;return-1}}function r(n,t,r){return function(e,u,i){var o=0,a=O(e);if("number"==typeof i)n>0?o=i>=0?i:Math.max(i+a,o):a=i>=0?Math.min(i+1,a):i+a+1;else if(r&&i&&a)return i=r(e,u),e[i]===u?i:-1;if(u!==u)return i=t(l.call(e,o,a),m.isNaN),i>=0?i+o:-1;for(i=n>0?o:a-1;i>=0&&a>i;i+=n)if(e[i]===u)return i;return-1}}function e(n,t){var r=I.length,e=n.constructor,u=m.isFunction(e)&&e.prototype||a,i="constructor";for(m.has(n,i)&&!m.contains(t,i)&&t.push(i);r--;)i=I[r],i in n&&n[i]!==u[i]&&!m.contains(t,i)&&t.push(i)}var u=this,i=u._,o=Array.prototype,a=Object.prototype,c=Function.prototype,f=o.push,l=o.slice,s=a.toString,p=a.hasOwnProperty,h=Array.isArray,v=Object.keys,g=c.bind,y=Object.create,d=function(){},m=function(n){return n instanceof m?n:this instanceof m?void(this._wrapped=n):new m(n)};"undefined"!=typeof exports?("undefined"!=typeof module&&module.exports&&(exports=module.exports=m),exports._=m):u._=m,m.VERSION="1.8.3";var b=function(n,t,r){if(t===void 0)return n;switch(null==r?3:r){case 1:return function(r){return n.call(t,r)};case 2:return function(r,e){return n.call(t,r,e)};case 3:return function(r,e,u){return n.call(t,r,e,u)};case 4:return function(r,e,u,i){return n.call(t,r,e,u,i)}}return function(){return n.apply(t,arguments)}},x=function(n,t,r){return null==n?m.identity:m.isFunction(n)?b(n,t,r):m.isObject(n)?m.matcher(n):m.property(n)};m.iteratee=function(n,t){return x(n,t,1/0)};var _=function(n,t){return function(r){var e=arguments.length;if(2>e||null==r)return r;for(var u=1;e>u;u++)for(var i=arguments[u],o=n(i),a=o.length,c=0;a>c;c++){var f=o[c];t&&r[f]!==void 0||(r[f]=i[f])}return r}},j=function(n){if(!m.isObject(n))return{};if(y)return y(n);d.prototype=n;var t=new d;return d.prototype=null,t},w=function(n){return function(t){return null==t?void 0:t[n]}},A=Math.pow(2,53)-1,O=w("length"),k=function(n){var t=O(n);return"number"==typeof t&&t>=0&&A>=t};m.each=m.forEach=function(n,t,r){t=b(t,r);var e,u;if(k(n))for(e=0,u=n.length;u>e;e++)t(n[e],e,n);else{var i=m.keys(n);for(e=0,u=i.length;u>e;e++)t(n[i[e]],i[e],n)}return n},m.map=m.collect=function(n,t,r){t=x(t,r);for(var e=!k(n)&&m.keys(n),u=(e||n).length,i=Array(u),o=0;u>o;o++){var a=e?e[o]:o;i[o]=t(n[a],a,n)}return i},m.reduce=m.foldl=m.inject=n(1),m.reduceRight=m.foldr=n(-1),m.find=m.detect=function(n,t,r){var e;return e=k(n)?m.findIndex(n,t,r):m.findKey(n,t,r),e!==void 0&&e!==-1?n[e]:void 0},m.filter=m.select=function(n,t,r){var e=[];return t=x(t,r),m.each(n,function(n,r,u){t(n,r,u)&&e.push(n)}),e},m.reject=function(n,t,r){return m.filter(n,m.negate(x(t)),r)},m.every=m.all=function(n,t,r){t=x(t,r);for(var e=!k(n)&&m.keys(n),u=(e||n).length,i=0;u>i;i++){var o=e?e[i]:i;if(!t(n[o],o,n))return!1}return!0},m.some=m.any=function(n,t,r){t=x(t,r);for(var e=!k(n)&&m.keys(n),u=(e||n).length,i=0;u>i;i++){var o=e?e[i]:i;if(t(n[o],o,n))return!0}return!1},m.contains=m.includes=m.include=function(n,t,r,e){return k(n)||(n=m.values(n)),("number"!=typeof r||e)&&(r=0),m.indexOf(n,t,r)>=0},m.invoke=function(n,t){var r=l.call(arguments,2),e=m.isFunction(t);return m.map(n,function(n){var u=e?t:n[t];return null==u?u:u.apply(n,r)})},m.pluck=function(n,t){return m.map(n,m.property(t))},m.where=function(n,t){return m.filter(n,m.matcher(t))},m.findWhere=function(n,t){return m.find(n,m.matcher(t))},m.max=function(n,t,r){var e,u,i=-1/0,o=-1/0;if(null==t&&null!=n){n=k(n)?n:m.values(n);for(var a=0,c=n.length;c>a;a++)e=n[a],e>i&&(i=e)}else t=x(t,r),m.each(n,function(n,r,e){u=t(n,r,e),(u>o||u===-1/0&&i===-1/0)&&(i=n,o=u)});return i},m.min=function(n,t,r){var e,u,i=1/0,o=1/0;if(null==t&&null!=n){n=k(n)?n:m.values(n);for(var a=0,c=n.length;c>a;a++)e=n[a],i>e&&(i=e)}else t=x(t,r),m.each(n,function(n,r,e){u=t(n,r,e),(o>u||1/0===u&&1/0===i)&&(i=n,o=u)});return i},m.shuffle=function(n){for(var t,r=k(n)?n:m.values(n),e=r.length,u=Array(e),i=0;e>i;i++)t=m.random(0,i),t!==i&&(u[i]=u[t]),u[t]=r[i];return u},m.sample=function(n,t,r){return null==t||r?(k(n)||(n=m.values(n)),n[m.random(n.length-1)]):m.shuffle(n).slice(0,Math.max(0,t))},m.sortBy=function(n,t,r){return t=x(t,r),m.pluck(m.map(n,function(n,r,e){return{value:n,index:r,criteria:t(n,r,e)}}).sort(function(n,t){var r=n.criteria,e=t.criteria;if(r!==e){if(r>e||r===void 0)return 1;if(e>r||e===void 0)return-1}return n.index-t.index}),"value")};var F=function(n){return function(t,r,e){var u={};return r=x(r,e),m.each(t,function(e,i){var o=r(e,i,t);n(u,e,o)}),u}};m.groupBy=F(function(n,t,r){m.has(n,r)?n[r].push(t):n[r]=[t]}),m.indexBy=F(function(n,t,r){n[r]=t}),m.countBy=F(function(n,t,r){m.has(n,r)?n[r]++:n[r]=1}),m.toArray=function(n){return n?m.isArray(n)?l.call(n):k(n)?m.map(n,m.identity):m.values(n):[]},m.size=function(n){return null==n?0:k(n)?n.length:m.keys(n).length},m.partition=function(n,t,r){t=x(t,r);var e=[],u=[];return m.each(n,function(n,r,i){(t(n,r,i)?e:u).push(n)}),[e,u]},m.first=m.head=m.take=function(n,t,r){return null==n?void 0:null==t||r?n[0]:m.initial(n,n.length-t)},m.initial=function(n,t,r){return l.call(n,0,Math.max(0,n.length-(null==t||r?1:t)))},m.last=function(n,t,r){return null==n?void 0:null==t||r?n[n.length-1]:m.rest(n,Math.max(0,n.length-t))},m.rest=m.tail=m.drop=function(n,t,r){return l.call(n,null==t||r?1:t)},m.compact=function(n){return m.filter(n,m.identity)};var S=function(n,t,r,e){for(var u=[],i=0,o=e||0,a=O(n);a>o;o++){var c=n[o];if(k(c)&&(m.isArray(c)||m.isArguments(c))){t||(c=S(c,t,r));var f=0,l=c.length;for(u.length+=l;l>f;)u[i++]=c[f++]}else r||(u[i++]=c)}return u};m.flatten=function(n,t){return S(n,t,!1)},m.without=function(n){return m.difference(n,l.call(arguments,1))},m.uniq=m.unique=function(n,t,r,e){m.isBoolean(t)||(e=r,r=t,t=!1),null!=r&&(r=x(r,e));for(var u=[],i=[],o=0,a=O(n);a>o;o++){var c=n[o],f=r?r(c,o,n):c;t?(o&&i===f||u.push(c),i=f):r?m.contains(i,f)||(i.push(f),u.push(c)):m.contains(u,c)||u.push(c)}return u},m.union=function(){return m.uniq(S(arguments,!0,!0))},m.intersection=function(n){for(var t=[],r=arguments.length,e=0,u=O(n);u>e;e++){var i=n[e];if(!m.contains(t,i)){for(var o=1;r>o&&m.contains(arguments[o],i);o++);o===r&&t.push(i)}}return t},m.difference=function(n){var t=S(arguments,!0,!0,1);return m.filter(n,function(n){return!m.contains(t,n)})},m.zip=function(){return m.unzip(arguments)},m.unzip=function(n){for(var t=n&&m.max(n,O).length||0,r=Array(t),e=0;t>e;e++)r[e]=m.pluck(n,e);return r},m.object=function(n,t){for(var r={},e=0,u=O(n);u>e;e++)t?r[n[e]]=t[e]:r[n[e][0]]=n[e][1];return r},m.findIndex=t(1),m.findLastIndex=t(-1),m.sortedIndex=function(n,t,r,e){r=x(r,e,1);for(var u=r(t),i=0,o=O(n);o>i;){var a=Math.floor((i+o)/2);r(n[a])<u?i=a+1:o=a}return i},m.indexOf=r(1,m.findIndex,m.sortedIndex),m.lastIndexOf=r(-1,m.findLastIndex),m.range=function(n,t,r){null==t&&(t=n||0,n=0),r=r||1;for(var e=Math.max(Math.ceil((t-n)/r),0),u=Array(e),i=0;e>i;i++,n+=r)u[i]=n;return u};var E=function(n,t,r,e,u){if(!(e instanceof t))return n.apply(r,u);var i=j(n.prototype),o=n.apply(i,u);return m.isObject(o)?o:i};m.bind=function(n,t){if(g&&n.bind===g)return g.apply(n,l.call(arguments,1));if(!m.isFunction(n))throw new TypeError("Bind must be called on a function");var r=l.call(arguments,2),e=function(){return E(n,e,t,this,r.concat(l.call(arguments)))};return e},m.partial=function(n){var t=l.call(arguments,1),r=function(){for(var e=0,u=t.length,i=Array(u),o=0;u>o;o++)i[o]=t[o]===m?arguments[e++]:t[o];for(;e<arguments.length;)i.push(arguments[e++]);return E(n,r,this,this,i)};return r},m.bindAll=function(n){var t,r,e=arguments.length;if(1>=e)throw new Error("bindAll must be passed function names");for(t=1;e>t;t++)r=arguments[t],n[r]=m.bind(n[r],n);return n},m.memoize=function(n,t){var r=function(e){var u=r.cache,i=""+(t?t.apply(this,arguments):e);return m.has(u,i)||(u[i]=n.apply(this,arguments)),u[i]};return r.cache={},r},m.delay=function(n,t){var r=l.call(arguments,2);return setTimeout(function(){return n.apply(null,r)},t)},m.defer=m.partial(m.delay,m,1),m.throttle=function(n,t,r){var e,u,i,o=null,a=0;r||(r={});var c=function(){a=r.leading===!1?0:m.now(),o=null,i=n.apply(e,u),o||(e=u=null)};return function(){var f=m.now();a||r.leading!==!1||(a=f);var l=t-(f-a);return e=this,u=arguments,0>=l||l>t?(o&&(clearTimeout(o),o=null),a=f,i=n.apply(e,u),o||(e=u=null)):o||r.trailing===!1||(o=setTimeout(c,l)),i}},m.debounce=function(n,t,r){var e,u,i,o,a,c=function(){var f=m.now()-o;t>f&&f>=0?e=setTimeout(c,t-f):(e=null,r||(a=n.apply(i,u),e||(i=u=null)))};return function(){i=this,u=arguments,o=m.now();var f=r&&!e;return e||(e=setTimeout(c,t)),f&&(a=n.apply(i,u),i=u=null),a}},m.wrap=function(n,t){return m.partial(t,n)},m.negate=function(n){return function(){return!n.apply(this,arguments)}},m.compose=function(){var n=arguments,t=n.length-1;return function(){for(var r=t,e=n[t].apply(this,arguments);r--;)e=n[r].call(this,e);return e}},m.after=function(n,t){return function(){return--n<1?t.apply(this,arguments):void 0}},m.before=function(n,t){var r;return function(){return--n>0&&(r=t.apply(this,arguments)),1>=n&&(t=null),r}},m.once=m.partial(m.before,2);var M=!{toString:null}.propertyIsEnumerable("toString"),I=["valueOf","isPrototypeOf","toString","propertyIsEnumerable","hasOwnProperty","toLocaleString"];m.keys=function(n){if(!m.isObject(n))return[];if(v)return v(n);var t=[];for(var r in n)m.has(n,r)&&t.push(r);return M&&e(n,t),t},m.allKeys=function(n){if(!m.isObject(n))return[];var t=[];for(var r in n)t.push(r);return M&&e(n,t),t},m.values=function(n){for(var t=m.keys(n),r=t.length,e=Array(r),u=0;r>u;u++)e[u]=n[t[u]];return e},m.mapObject=function(n,t,r){t=x(t,r);for(var e,u=m.keys(n),i=u.length,o={},a=0;i>a;a++)e=u[a],o[e]=t(n[e],e,n);return o},m.pairs=function(n){for(var t=m.keys(n),r=t.length,e=Array(r),u=0;r>u;u++)e[u]=[t[u],n[t[u]]];return e},m.invert=function(n){for(var t={},r=m.keys(n),e=0,u=r.length;u>e;e++)t[n[r[e]]]=r[e];return t},m.functions=m.methods=function(n){var t=[];for(var r in n)m.isFunction(n[r])&&t.push(r);return t.sort()},m.extend=_(m.allKeys),m.extendOwn=m.assign=_(m.keys),m.findKey=function(n,t,r){t=x(t,r);for(var e,u=m.keys(n),i=0,o=u.length;o>i;i++)if(e=u[i],t(n[e],e,n))return e},m.pick=function(n,t,r){var e,u,i={},o=n;if(null==o)return i;m.isFunction(t)?(u=m.allKeys(o),e=b(t,r)):(u=S(arguments,!1,!1,1),e=function(n,t,r){return t in r},o=Object(o));for(var a=0,c=u.length;c>a;a++){var f=u[a],l=o[f];e(l,f,o)&&(i[f]=l)}return i},m.omit=function(n,t,r){if(m.isFunction(t))t=m.negate(t);else{var e=m.map(S(arguments,!1,!1,1),String);t=function(n,t){return!m.contains(e,t)}}return m.pick(n,t,r)},m.defaults=_(m.allKeys,!0),m.create=function(n,t){var r=j(n);return t&&m.extendOwn(r,t),r},m.clone=function(n){return m.isObject(n)?m.isArray(n)?n.slice():m.extend({},n):n},m.tap=function(n,t){return t(n),n},m.isMatch=function(n,t){var r=m.keys(t),e=r.length;if(null==n)return!e;for(var u=Object(n),i=0;e>i;i++){var o=r[i];if(t[o]!==u[o]||!(o in u))return!1}return!0};var N=function(n,t,r,e){if(n===t)return 0!==n||1/n===1/t;if(null==n||null==t)return n===t;n instanceof m&&(n=n._wrapped),t instanceof m&&(t=t._wrapped);var u=s.call(n);if(u!==s.call(t))return!1;switch(u){case"[object RegExp]":case"[object String]":return""+n==""+t;case"[object Number]":return+n!==+n?+t!==+t:0===+n?1/+n===1/t:+n===+t;case"[object Date]":case"[object Boolean]":return+n===+t}var i="[object Array]"===u;if(!i){if("object"!=typeof n||"object"!=typeof t)return!1;var o=n.constructor,a=t.constructor;if(o!==a&&!(m.isFunction(o)&&o instanceof o&&m.isFunction(a)&&a instanceof a)&&"constructor"in n&&"constructor"in t)return!1}r=r||[],e=e||[];for(var c=r.length;c--;)if(r[c]===n)return e[c]===t;if(r.push(n),e.push(t),i){if(c=n.length,c!==t.length)return!1;for(;c--;)if(!N(n[c],t[c],r,e))return!1}else{var f,l=m.keys(n);if(c=l.length,m.keys(t).length!==c)return!1;for(;c--;)if(f=l[c],!m.has(t,f)||!N(n[f],t[f],r,e))return!1}return r.pop(),e.pop(),!0};m.isEqual=function(n,t){return N(n,t)},m.isEmpty=function(n){return null==n?!0:k(n)&&(m.isArray(n)||m.isString(n)||m.isArguments(n))?0===n.length:0===m.keys(n).length},m.isElement=function(n){return!(!n||1!==n.nodeType)},m.isArray=h||function(n){return"[object Array]"===s.call(n)},m.isObject=function(n){var t=typeof n;return"function"===t||"object"===t&&!!n},m.each(["Arguments","Function","String","Number","Date","RegExp","Error"],function(n){m["is"+n]=function(t){return s.call(t)==="[object "+n+"]"}}),m.isArguments(arguments)||(m.isArguments=function(n){return m.has(n,"callee")}),"function"!=typeof/./&&"object"!=typeof Int8Array&&(m.isFunction=function(n){return"function"==typeof n||!1}),m.isFinite=function(n){return isFinite(n)&&!isNaN(parseFloat(n))},m.isNaN=function(n){return m.isNumber(n)&&n!==+n},m.isBoolean=function(n){return n===!0||n===!1||"[object Boolean]"===s.call(n)},m.isNull=function(n){return null===n},m.isUndefined=function(n){return n===void 0},m.has=function(n,t){return null!=n&&p.call(n,t)},m.noConflict=function(){return u._=i,this},m.identity=function(n){return n},m.constant=function(n){return function(){return n}},m.noop=function(){},m.property=w,m.propertyOf=function(n){return null==n?function(){}:function(t){return n[t]}},m.matcher=m.matches=function(n){return n=m.extendOwn({},n),function(t){return m.isMatch(t,n)}},m.times=function(n,t,r){var e=Array(Math.max(0,n));t=b(t,r,1);for(var u=0;n>u;u++)e[u]=t(u);return e},m.random=function(n,t){return null==t&&(t=n,n=0),n+Math.floor(Math.random()*(t-n+1))},m.now=Date.now||function(){return(new Date).getTime()};var B={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;"},T=m.invert(B),R=function(n){var t=function(t){return n[t]},r="(?:"+m.keys(n).join("|")+")",e=RegExp(r),u=RegExp(r,"g");return function(n){return n=null==n?"":""+n,e.test(n)?n.replace(u,t):n}};m.escape=R(B),m.unescape=R(T),m.result=function(n,t,r){var e=null==n?void 0:n[t];return e===void 0&&(e=r),m.isFunction(e)?e.call(n):e};var q=0;m.uniqueId=function(n){var t=++q+"";return n?n+t:t},m.templateSettings={evaluate:/<%([\s\S]+?)%>/g,interpolate:/<%=([\s\S]+?)%>/g,escape:/<%-([\s\S]+?)%>/g};var K=/(.)^/,z={"'":"'","\\":"\\","\r":"r","\n":"n","\u2028":"u2028","\u2029":"u2029"},D=/\\|'|\r|\n|\u2028|\u2029/g,L=function(n){return"\\"+z[n]};m.template=function(n,t,r){!t&&r&&(t=r),t=m.defaults({},t,m.templateSettings);var e=RegExp([(t.escape||K).source,(t.interpolate||K).source,(t.evaluate||K).source].join("|")+"|$","g"),u=0,i="__p+='";n.replace(e,function(t,r,e,o,a){return i+=n.slice(u,a).replace(D,L),u=a+t.length,r?i+="'+\n((__t=("+r+"))==null?'':_.escape(__t))+\n'":e?i+="'+\n((__t=("+e+"))==null?'':__t)+\n'":o&&(i+="';\n"+o+"\n__p+='"),t}),i+="';\n",t.variable||(i="with(obj||{}){\n"+i+"}\n"),i="var __t,__p='',__j=Array.prototype.join,"+"print=function(){__p+=__j.call(arguments,'');};\n"+i+"return __p;\n";try{var o=new Function(t.variable||"obj","_",i)}catch(a){throw a.source=i,a}var c=function(n){return o.call(this,n,m)},f=t.variable||"obj";return c.source="function("+f+"){\n"+i+"}",c},m.chain=function(n){var t=m(n);return t._chain=!0,t};var P=function(n,t){return n._chain?m(t).chain():t};m.mixin=function(n){m.each(m.functions(n),function(t){var r=m[t]=n[t];m.prototype[t]=function(){var n=[this._wrapped];return f.apply(n,arguments),P(this,r.apply(m,n))}})},m.mixin(m),m.each(["pop","push","reverse","shift","sort","splice","unshift"],function(n){var t=o[n];m.prototype[n]=function(){var r=this._wrapped;return t.apply(r,arguments),"shift"!==n&&"splice"!==n||0!==r.length||delete r[0],P(this,r)}}),m.each(["concat","join","slice"],function(n){var t=o[n];m.prototype[n]=function(){return P(this,t.apply(this._wrapped,arguments))}}),m.prototype.value=function(){return this._wrapped},m.prototype.valueOf=m.prototype.toJSON=m.prototype.value,m.prototype.toString=function(){return""+this._wrapped},"function"==typeof define&&define.amd&&define("underscore",[],function(){return m})}).call(this);
;!function(a){"use strict";function b(a,b){var c=(65535&a)+(65535&b),d=(a>>16)+(b>>16)+(c>>16);return d<<16|65535&c}function c(a,b){return a<<b|a>>>32-b}function d(a,d,e,f,g,h){return b(c(b(b(d,a),b(f,h)),g),e)}function e(a,b,c,e,f,g,h){return d(b&c|~b&e,a,b,f,g,h)}function f(a,b,c,e,f,g,h){return d(b&e|c&~e,a,b,f,g,h)}function g(a,b,c,e,f,g,h){return d(b^c^e,a,b,f,g,h)}function h(a,b,c,e,f,g,h){return d(c^(b|~e),a,b,f,g,h)}function i(a,c){a[c>>5]|=128<<c%32,a[(c+64>>>9<<4)+14]=c;var d,i,j,k,l,m=1732584193,n=-271733879,o=-1732584194,p=271733878;for(d=0;d<a.length;d+=16)i=m,j=n,k=o,l=p,m=e(m,n,o,p,a[d],7,-680876936),p=e(p,m,n,o,a[d+1],12,-389564586),o=e(o,p,m,n,a[d+2],17,606105819),n=e(n,o,p,m,a[d+3],22,-1044525330),m=e(m,n,o,p,a[d+4],7,-176418897),p=e(p,m,n,o,a[d+5],12,1200080426),o=e(o,p,m,n,a[d+6],17,-1473231341),n=e(n,o,p,m,a[d+7],22,-45705983),m=e(m,n,o,p,a[d+8],7,1770035416),p=e(p,m,n,o,a[d+9],12,-1958414417),o=e(o,p,m,n,a[d+10],17,-42063),n=e(n,o,p,m,a[d+11],22,-1990404162),m=e(m,n,o,p,a[d+12],7,1804603682),p=e(p,m,n,o,a[d+13],12,-40341101),o=e(o,p,m,n,a[d+14],17,-1502002290),n=e(n,o,p,m,a[d+15],22,1236535329),m=f(m,n,o,p,a[d+1],5,-165796510),p=f(p,m,n,o,a[d+6],9,-1069501632),o=f(o,p,m,n,a[d+11],14,643717713),n=f(n,o,p,m,a[d],20,-373897302),m=f(m,n,o,p,a[d+5],5,-701558691),p=f(p,m,n,o,a[d+10],9,38016083),o=f(o,p,m,n,a[d+15],14,-660478335),n=f(n,o,p,m,a[d+4],20,-405537848),m=f(m,n,o,p,a[d+9],5,568446438),p=f(p,m,n,o,a[d+14],9,-1019803690),o=f(o,p,m,n,a[d+3],14,-187363961),n=f(n,o,p,m,a[d+8],20,1163531501),m=f(m,n,o,p,a[d+13],5,-1444681467),p=f(p,m,n,o,a[d+2],9,-51403784),o=f(o,p,m,n,a[d+7],14,1735328473),n=f(n,o,p,m,a[d+12],20,-1926607734),m=g(m,n,o,p,a[d+5],4,-378558),p=g(p,m,n,o,a[d+8],11,-2022574463),o=g(o,p,m,n,a[d+11],16,1839030562),n=g(n,o,p,m,a[d+14],23,-35309556),m=g(m,n,o,p,a[d+1],4,-1530992060),p=g(p,m,n,o,a[d+4],11,1272893353),o=g(o,p,m,n,a[d+7],16,-155497632),n=g(n,o,p,m,a[d+10],23,-1094730640),m=g(m,n,o,p,a[d+13],4,681279174),p=g(p,m,n,o,a[d],11,-358537222),o=g(o,p,m,n,a[d+3],16,-722521979),n=g(n,o,p,m,a[d+6],23,76029189),m=g(m,n,o,p,a[d+9],4,-640364487),p=g(p,m,n,o,a[d+12],11,-421815835),o=g(o,p,m,n,a[d+15],16,530742520),n=g(n,o,p,m,a[d+2],23,-995338651),m=h(m,n,o,p,a[d],6,-198630844),p=h(p,m,n,o,a[d+7],10,1126891415),o=h(o,p,m,n,a[d+14],15,-1416354905),n=h(n,o,p,m,a[d+5],21,-57434055),m=h(m,n,o,p,a[d+12],6,1700485571),p=h(p,m,n,o,a[d+3],10,-1894986606),o=h(o,p,m,n,a[d+10],15,-1051523),n=h(n,o,p,m,a[d+1],21,-2054922799),m=h(m,n,o,p,a[d+8],6,1873313359),p=h(p,m,n,o,a[d+15],10,-30611744),o=h(o,p,m,n,a[d+6],15,-1560198380),n=h(n,o,p,m,a[d+13],21,1309151649),m=h(m,n,o,p,a[d+4],6,-145523070),p=h(p,m,n,o,a[d+11],10,-1120210379),o=h(o,p,m,n,a[d+2],15,718787259),n=h(n,o,p,m,a[d+9],21,-343485551),m=b(m,i),n=b(n,j),o=b(o,k),p=b(p,l);return[m,n,o,p]}function j(a){var b,c="";for(b=0;b<32*a.length;b+=8)c+=String.fromCharCode(a[b>>5]>>>b%32&255);return c}function k(a){var b,c=[];for(c[(a.length>>2)-1]=void 0,b=0;b<c.length;b+=1)c[b]=0;for(b=0;b<8*a.length;b+=8)c[b>>5]|=(255&a.charCodeAt(b/8))<<b%32;return c}function l(a){return j(i(k(a),8*a.length))}function m(a,b){var c,d,e=k(a),f=[],g=[];for(f[15]=g[15]=void 0,e.length>16&&(e=i(e,8*a.length)),c=0;16>c;c+=1)f[c]=909522486^e[c],g[c]=1549556828^e[c];return d=i(f.concat(k(b)),512+8*b.length),j(i(g.concat(d),640))}function n(a){var b,c,d="0123456789abcdef",e="";for(c=0;c<a.length;c+=1)b=a.charCodeAt(c),e+=d.charAt(b>>>4&15)+d.charAt(15&b);return e}function o(a){return unescape(encodeURIComponent(a))}function p(a){return l(o(a))}function q(a){return n(p(a))}function r(a,b){return m(o(a),o(b))}function s(a,b){return n(r(a,b))}function t(a,b,c){return b?c?r(b,a):s(b,a):c?p(a):q(a)}"function"==typeof define&&define.amd?define(function(){return t}):a.md5=t}(this);
!function(a,b,c){function f(a,c){this.wrapper="string"==typeof a?b.querySelector(a):a,this.scroller=this.wrapper.children[0],this.scrollerStyle=this.scroller.style,this.options={startX:0,startY:0,scrollY:!0,directionLockThreshold:5,momentum:!0,bounce:!0,bounceTime:600,bounceEasing:"",preventDefault:!0,preventDefaultException:{tagName:/^(INPUT|TEXTAREA|BUTTON|SELECT)$/},HWCompositing:!0,useTransition:!0,useTransform:!0};for(var d in c)this.options[d]=c[d];this.translateZ=this.options.HWCompositing&&e.hasPerspective?" translateZ(0)":"",this.options.useTransition=e.hasTransition&&this.options.useTransition,this.options.useTransform=e.hasTransform&&this.options.useTransform,this.options.eventPassthrough=this.options.eventPassthrough===!0?"vertical":this.options.eventPassthrough,this.options.preventDefault=!this.options.eventPassthrough&&this.options.preventDefault,this.options.scrollY="vertical"==this.options.eventPassthrough?!1:this.options.scrollY,this.options.scrollX="horizontal"==this.options.eventPassthrough?!1:this.options.scrollX,this.options.freeScroll=this.options.freeScroll&&!this.options.eventPassthrough,this.options.directionLockThreshold=this.options.eventPassthrough?0:this.options.directionLockThreshold,this.options.bounceEasing="string"==typeof this.options.bounceEasing?e.ease[this.options.bounceEasing]||e.ease.circular:this.options.bounceEasing,this.options.resizePolling=void 0===this.options.resizePolling?60:this.options.resizePolling,this.options.tap===!0&&(this.options.tap="tap"),this.x=0,this.y=0,this.directionX=0,this.directionY=0,this._events={},this._init(),this.refresh(),this.scrollTo(this.options.startX,this.options.startY),this.enable()}var d=a.requestAnimationFrame||a.webkitRequestAnimationFrame||a.mozRequestAnimationFrame||a.oRequestAnimationFrame||a.msRequestAnimationFrame||function(b){a.setTimeout(b,1e3/60)},e=function(){function g(a){return f===!1?!1:""===f?a:f+a.charAt(0).toUpperCase()+a.substr(1)}var h,d={},e=b.createElement("div").style,f=function(){for(var b,a=["t","webkitT","MozT","msT","OT"],c=0,d=a.length;d>c;c++)if(b=a[c]+"ransform",b in e)return a[c].substr(0,a[c].length-1);return!1}();return d.getTime=Date.now||function(){return(new Date).getTime()},d.extend=function(a,b){for(var c in b)a[c]=b[c]},d.addEvent=function(a,b,c,d){a.addEventListener(b,c,!!d)},d.removeEvent=function(a,b,c,d){a.removeEventListener(b,c,!!d)},d.prefixPointerEvent=function(b){return a.MSPointerEvent?"MSPointer"+b.charAt(9).toUpperCase()+b.substr(10):b},d.momentum=function(a,b,d,e,f,g){var j,k,h=a-b,i=c.abs(h)/d;return g=void 0===g?6e-4:g,j=a+i*i/(2*g)*(0>h?-1:1),k=i/g,e>j?(j=f?e-f/2.5*(i/8):e,h=c.abs(j-a),k=h/i):j>0&&(j=f?f/2.5*(i/8):0,h=c.abs(a)+j,k=h/i),{destination:c.round(j),duration:k}},h=g("transform"),d.extend(d,{hasTransform:h!==!1,hasPerspective:g("perspective")in e,hasTouch:"ontouchstart"in a,hasPointer:a.PointerEvent||a.MSPointerEvent,hasTransition:g("transition")in e}),d.isBadAndroid=/Android /.test(a.navigator.appVersion)&&!/Chrome\/\d/.test(a.navigator.appVersion),d.extend(d.style={},{transform:h,transitionTimingFunction:g("transitionTimingFunction"),transitionDuration:g("transitionDuration"),transitionDelay:g("transitionDelay"),transformOrigin:g("transformOrigin")}),d.hasClass=function(a,b){var c=new RegExp("(^|\\s)"+b+"(\\s|$)");return c.test(a.className)},d.addClass=function(a,b){if(!d.hasClass(a,b)){var c=a.className.split(" ");c.push(b),a.className=c.join(" ")}},d.removeClass=function(a,b){if(d.hasClass(a,b)){var c=new RegExp("(^|\\s)"+b+"(\\s|$)","g");a.className=a.className.replace(c," ")}},d.offset=function(a){for(var b=-a.offsetLeft,c=-a.offsetTop;a=a.offsetParent;)b-=a.offsetLeft,c-=a.offsetTop;return{left:b,top:c}},d.preventDefaultException=function(a,b){for(var c in b)if(b[c].test(a[c]))return!0;return!1},d.extend(d.eventType={},{touchstart:1,touchmove:1,touchend:1,mousedown:2,mousemove:2,mouseup:2,pointerdown:3,pointermove:3,pointerup:3,MSPointerDown:3,MSPointerMove:3,MSPointerUp:3}),d.extend(d.ease={},{quadratic:{style:"cubic-bezier(0.25, 0.46, 0.45, 0.94)",fn:function(a){return a*(2-a)}},circular:{style:"cubic-bezier(0.1, 0.57, 0.1, 1)",fn:function(a){return c.sqrt(1- --a*a)}},back:{style:"cubic-bezier(0.175, 0.885, 0.32, 1.275)",fn:function(a){var b=4;return(a-=1)*a*((b+1)*a+b)+1}},bounce:{style:"",fn:function(a){return(a/=1)<1/2.75?7.5625*a*a:2/2.75>a?7.5625*(a-=1.5/2.75)*a+.75:2.5/2.75>a?7.5625*(a-=2.25/2.75)*a+.9375:7.5625*(a-=2.625/2.75)*a+.984375}},elastic:{style:"",fn:function(a){var b=.22,d=.4;return 0===a?0:1==a?1:d*c.pow(2,-10*a)*c.sin((a-b/4)*2*c.PI/b)+1}}}),d.tap=function(a,c){var d=b.createEvent("Event");d.initEvent(c,!0,!0),d.pageX=a.pageX,d.pageY=a.pageY,a.target.dispatchEvent(d)},d.click=function(a){var d,c=a.target;/(SELECT|INPUT|TEXTAREA)/i.test(c.tagName)||(d=b.createEvent("MouseEvents"),d.initMouseEvent("click",!0,!0,a.view,1,c.screenX,c.screenY,c.clientX,c.clientY,a.ctrlKey,a.altKey,a.shiftKey,a.metaKey,0,null),d._constructed=!0,c.dispatchEvent(d))},d}();f.prototype={version:"5.1.3",_init:function(){this._initEvents()},destroy:function(){this._initEvents(!0),this._execEvent("destroy")},_transitionEnd:function(a){a.target==this.scroller&&this.isInTransition&&(this._transitionTime(),this.resetPosition(this.options.bounceTime)||(this.isInTransition=!1,this._execEvent("scrollEnd")))},_start:function(a){if(!(1!=e.eventType[a.type]&&0!==a.button||!this.enabled||this.initiated&&e.eventType[a.type]!==this.initiated)){!this.options.preventDefault||e.isBadAndroid||e.preventDefaultException(a.target,this.options.preventDefaultException)||a.preventDefault();var d,b=a.touches?a.touches[0]:a;this.initiated=e.eventType[a.type],this.moved=!1,this.distX=0,this.distY=0,this.directionX=0,this.directionY=0,this.directionLocked=0,this._transitionTime(),this.startTime=e.getTime(),this.options.useTransition&&this.isInTransition?(this.isInTransition=!1,d=this.getComputedPosition(),this._translate(c.round(d.x),c.round(d.y)),this._execEvent("scrollEnd")):!this.options.useTransition&&this.isAnimating&&(this.isAnimating=!1,this._execEvent("scrollEnd")),this.startX=this.x,this.startY=this.y,this.absStartX=this.x,this.absStartY=this.y,this.pointX=b.pageX,this.pointY=b.pageY,this._execEvent("beforeScrollStart")}},_move:function(a){if(this.enabled&&e.eventType[a.type]===this.initiated){this.options.preventDefault&&a.preventDefault();var h,i,j,k,b=a.touches?a.touches[0]:a,d=b.pageX-this.pointX,f=b.pageY-this.pointY,g=e.getTime();if(this.pointX=b.pageX,this.pointY=b.pageY,this.distX+=d,this.distY+=f,j=c.abs(this.distX),k=c.abs(this.distY),!(g-this.endTime>300&&10>j&&10>k)){if(this.directionLocked||this.options.freeScroll||(this.directionLocked=j>k+this.options.directionLockThreshold?"h":k>=j+this.options.directionLockThreshold?"v":"n"),"h"==this.directionLocked){if("vertical"==this.options.eventPassthrough)a.preventDefault();else if("horizontal"==this.options.eventPassthrough)return this.initiated=!1,void 0;f=0}else if("v"==this.directionLocked){if("horizontal"==this.options.eventPassthrough)a.preventDefault();else if("vertical"==this.options.eventPassthrough)return this.initiated=!1,void 0;d=0}d=this.hasHorizontalScroll?d:0,f=this.hasVerticalScroll?f:0,h=this.x+d,i=this.y+f,(h>0||h<this.maxScrollX)&&(h=this.options.bounce?this.x+d/3:h>0?0:this.maxScrollX),(i>0||i<this.maxScrollY)&&(i=this.options.bounce?this.y+f/3:i>0?0:this.maxScrollY),this.directionX=d>0?-1:0>d?1:0,this.directionY=f>0?-1:0>f?1:0,this.moved||this._execEvent("scrollStart"),this.moved=!0,this._translate(h,i),g-this.startTime>300&&(this.startTime=g,this.startX=this.x,this.startY=this.y)}}},_end:function(a){if(this.enabled&&e.eventType[a.type]===this.initiated){this.options.preventDefault&&!e.preventDefaultException(a.target,this.options.preventDefaultException)&&a.preventDefault();var d,f,g=(a.changedTouches?a.changedTouches[0]:a,e.getTime()-this.startTime),h=c.round(this.x),i=c.round(this.y),j=c.abs(h-this.startX),k=c.abs(i-this.startY),l=0,m="";if(this.isInTransition=0,this.initiated=0,this.endTime=e.getTime(),!this.resetPosition(this.options.bounceTime))return this.scrollTo(h,i),this.moved?this._events.flick&&200>g&&100>j&&100>k?(this._execEvent("flick"),void 0):(this.options.momentum&&300>g&&(d=this.hasHorizontalScroll?e.momentum(this.x,this.startX,g,this.maxScrollX,this.options.bounce?this.wrapperWidth:0,this.options.deceleration):{destination:h,duration:0},f=this.hasVerticalScroll?e.momentum(this.y,this.startY,g,this.maxScrollY,this.options.bounce?this.wrapperHeight:0,this.options.deceleration):{destination:i,duration:0},h=d.destination,i=f.destination,l=c.max(d.duration,f.duration),this.isInTransition=1),h!=this.x||i!=this.y?((h>0||h<this.maxScrollX||i>0||i<this.maxScrollY)&&(m=e.ease.quadratic),this.scrollTo(h,i,l,m),void 0):(this._execEvent("scrollEnd"),void 0)):(this.options.tap&&e.tap(a,this.options.tap),this.options.click&&e.click(a),this._execEvent("scrollCancel"),void 0)}},_resize:function(){var a=this;clearTimeout(this.resizeTimeout),this.resizeTimeout=setTimeout(function(){a.refresh()},this.options.resizePolling)},resetPosition:function(a){var b=this.x,c=this.y;return a=a||0,!this.hasHorizontalScroll||this.x>0?b=0:this.x<this.maxScrollX&&(b=this.maxScrollX),!this.hasVerticalScroll||this.y>0?c=0:this.y<this.maxScrollY&&(c=this.maxScrollY),b==this.x&&c==this.y?!1:(this.scrollTo(b,c,a,this.options.bounceEasing),!0)},disable:function(){this.enabled=!1},enable:function(){this.enabled=!0},refresh:function(){this.wrapper.offsetHeight,this.wrapperWidth=this.wrapper.clientWidth,this.wrapperHeight=this.wrapper.clientHeight,this.scrollerWidth=this.scroller.offsetWidth,this.scrollerHeight=this.scroller.offsetHeight,this.maxScrollX=this.wrapperWidth-this.scrollerWidth,this.maxScrollY=this.wrapperHeight-this.scrollerHeight,this.hasHorizontalScroll=this.options.scrollX&&this.maxScrollX<0,this.hasVerticalScroll=this.options.scrollY&&this.maxScrollY<0,this.hasHorizontalScroll||(this.maxScrollX=0,this.scrollerWidth=this.wrapperWidth),this.hasVerticalScroll||(this.maxScrollY=0,this.scrollerHeight=this.wrapperHeight),this.endTime=0,this.directionX=0,this.directionY=0,this.wrapperOffset=e.offset(this.wrapper),this._execEvent("refresh"),this.resetPosition()},on:function(a,b){this._events[a]||(this._events[a]=[]),this._events[a].push(b)},off:function(a,b){if(this._events[a]){var c=this._events[a].indexOf(b);c>-1&&this._events[a].splice(c,1)}},_execEvent:function(a){if(this._events[a]){var b=0,c=this._events[a].length;if(c)for(;c>b;b++)this._events[a][b].apply(this,[].slice.call(arguments,1))}},scrollBy:function(a,b,c,d){a=this.x+a,b=this.y+b,c=c||0,this.scrollTo(a,b,c,d)},scrollTo:function(a,b,c,d){d=d||e.ease.circular,this.isInTransition=this.options.useTransition&&c>0,!c||this.options.useTransition&&d.style?(this._transitionTimingFunction(d.style),this._transitionTime(c),this._translate(a,b)):this._animate(a,b,c,d.fn)},scrollToElement:function(a,b,d,f,g){if(a=a.nodeType?a:this.scroller.querySelector(a)){var h=e.offset(a);h.left-=this.wrapperOffset.left,h.top-=this.wrapperOffset.top,d===!0&&(d=c.round(a.offsetWidth/2-this.wrapper.offsetWidth/2)),f===!0&&(f=c.round(a.offsetHeight/2-this.wrapper.offsetHeight/2)),h.left-=d||0,h.top-=f||0,h.left=h.left>0?0:h.left<this.maxScrollX?this.maxScrollX:h.left,h.top=h.top>0?0:h.top<this.maxScrollY?this.maxScrollY:h.top,b=void 0===b||null===b||"auto"===b?c.max(c.abs(this.x-h.left),c.abs(this.y-h.top)):b,this.scrollTo(h.left,h.top,b,g)}},_transitionTime:function(a){a=a||0,this.scrollerStyle[e.style.transitionDuration]=a+"ms",!a&&e.isBadAndroid&&(this.scrollerStyle[e.style.transitionDuration]="0.001s")},_transitionTimingFunction:function(a){this.scrollerStyle[e.style.transitionTimingFunction]=a},_translate:function(a,b){this.options.useTransform?this.scrollerStyle[e.style.transform]="translate("+a+"px,"+b+"px)"+this.translateZ:(a=c.round(a),b=c.round(b),this.scrollerStyle.left=a+"px",this.scrollerStyle.top=b+"px"),this.x=a,this.y=b},_initEvents:function(b){var c=b?e.removeEvent:e.addEvent,d=this.options.bindToWrapper?this.wrapper:a;c(a,"orientationchange",this),c(a,"resize",this),this.options.click&&c(this.wrapper,"click",this,!0),this.options.disableMouse||(c(this.wrapper,"mousedown",this),c(d,"mousemove",this),c(d,"mousecancel",this),c(d,"mouseup",this)),e.hasPointer&&!this.options.disablePointer&&(c(this.wrapper,e.prefixPointerEvent("pointerdown"),this),c(d,e.prefixPointerEvent("pointermove"),this),c(d,e.prefixPointerEvent("pointercancel"),this),c(d,e.prefixPointerEvent("pointerup"),this)),e.hasTouch&&!this.options.disableTouch&&(c(this.wrapper,"touchstart",this),c(d,"touchmove",this),c(d,"touchcancel",this),c(d,"touchend",this)),c(this.scroller,"transitionend",this),c(this.scroller,"webkitTransitionEnd",this),c(this.scroller,"oTransitionEnd",this),c(this.scroller,"MSTransitionEnd",this)},getComputedPosition:function(){var c,d,b=a.getComputedStyle(this.scroller,null);return this.options.useTransform?(b=b[e.style.transform].split(")")[0].split(", "),c=+(b[12]||b[4]),d=+(b[13]||b[5])):(c=+b.left.replace(/[^-\d.]/g,""),d=+b.top.replace(/[^-\d.]/g,"")),{x:c,y:d}},_animate:function(a,b,c,f){function l(){var n,o,p,m=e.getTime();return m>=k?(g.isAnimating=!1,g._translate(a,b),g.resetPosition(g.options.bounceTime)||g._execEvent("scrollEnd"),void 0):(m=(m-j)/c,p=f(m),n=(a-h)*p+h,o=(b-i)*p+i,g._translate(n,o),g.isAnimating&&d(l),void 0)}var g=this,h=this.x,i=this.y,j=e.getTime(),k=j+c;this.isAnimating=!0,l()},handleEvent:function(a){switch(a.type){case"touchstart":case"pointerdown":case"MSPointerDown":case"mousedown":this._start(a);break;case"touchmove":case"pointermove":case"MSPointerMove":case"mousemove":this._move(a);break;case"touchend":case"pointerup":case"MSPointerUp":case"mouseup":case"touchcancel":case"pointercancel":case"MSPointerCancel":case"mousecancel":this._end(a);break;case"orientationchange":case"resize":this._resize();break;case"transitionend":case"webkitTransitionEnd":case"oTransitionEnd":case"MSTransitionEnd":this._transitionEnd(a);break;case"wheel":case"DOMMouseScroll":case"mousewheel":this._wheel(a);break;case"keydown":this._key(a);break;case"click":a._constructed||(a.preventDefault(),a.stopPropagation())}}},f.utils=e,"undefined"!=typeof module&&module.exports?module.exports=f:a.IScroll=f}(window,document,Math);
(function(){window.$clamp=function(c,d){function s(a,b){n.getComputedStyle||(n.getComputedStyle=function(a,b){this.el=a;this.getPropertyValue=function(b){var c=/(\-([a-z]){1})/g;"float"==b&&(b="styleFloat");c.test(b)&&(b=b.replace(c,function(a,b,c){return c.toUpperCase()}));return a.currentStyle&&a.currentStyle[b]?a.currentStyle[b]:null};return this});return n.getComputedStyle(a,null).getPropertyValue(b)}function t(a){a=a||c.clientHeight;var b=u(c);return Math.max(Math.floor(a/b),0)}function x(a){return u(c)*
    a}function u(a){var b=s(a,"line-height");"normal"==b&&(b=1.2*parseInt(s(a,"font-size")));return parseInt(b)}function l(a){if(a.lastChild.children&&0<a.lastChild.children.length)return l(Array.prototype.slice.call(a.children).pop());if(a.lastChild&&a.lastChild.nodeValue&&""!=a.lastChild.nodeValue&&a.lastChild.nodeValue!=b.truncationChar)return a.lastChild;a.lastChild.parentNode.removeChild(a.lastChild);return l(c)}function p(a,d){if(d){var e=a.nodeValue.replace(b.truncationChar,"");f||(h=0<k.length?
    k.shift():"",f=e.split(h));1<f.length?(q=f.pop(),r(a,f.join(h))):f=null;m&&(a.nodeValue=a.nodeValue.replace(b.truncationChar,""),c.innerHTML=a.nodeValue+" "+m.innerHTML+b.truncationChar);if(f){if(c.clientHeight<=d)if(0<=k.length&&""!=h)r(a,f.join(h)+h+q),f=null;else return c.innerHTML}else""==h&&(r(a,""),a=l(c),k=b.splitOnChars.slice(0),h=k[0],q=f=null);if(b.animate)setTimeout(function(){p(a,d)},!0===b.animate?10:b.animate);else return p(a,d)}}function r(a,c){a.nodeValue=c+b.truncationChar}d=d||{};
    var n=window,b={clamp:d.clamp||2,useNativeClamp:"undefined"!=typeof d.useNativeClamp?d.useNativeClamp:!0,splitOnChars:d.splitOnChars||[".","-","\u2013","\u2014"," "],animate:d.animate||!1,truncationChar:d.truncationChar||"\u2026",truncationHTML:d.truncationHTML},e=c.style,y=c.innerHTML,z="undefined"!=typeof c.style.webkitLineClamp,g=b.clamp,v=g.indexOf&&(-1<g.indexOf("px")||-1<g.indexOf("em")),m;b.truncationHTML&&(m=document.createElement("span"),m.innerHTML=b.truncationHTML);var k=b.splitOnChars.slice(0),
        h=k[0],f,q;"auto"==g?g=t():v&&(g=t(parseInt(g)));var w;z&&b.useNativeClamp?(e.overflow="hidden",e.textOverflow="ellipsis",e.webkitBoxOrient="vertical",e.display="-webkit-box",e.webkitLineClamp=g,v&&(e.height=b.clamp+"px")):(e=x(g),e<=c.clientHeight&&(w=p(l(c),e)));return{original:y,clamped:w}}})();
;(function(){function e(o,n){var p;n=n||{};this.trackingClick=false;this.trackingClickStart=0;this.targetElement=null;this.touchStartX=0;this.touchStartY=0;this.lastTouchIdentifier=0;this.touchBoundary=n.touchBoundary||10;this.layer=o;this.tapDelay=n.tapDelay||200;this.tapTimeout=n.tapTimeout||700;if(e.notNeeded(o)){return}function q(l,i){return function(){return l.apply(i,arguments)}}var j=["onMouse","onClick","onTouchStart","onTouchMove","onTouchEnd","onTouchCancel"];var m=this;for(var k=0,h=j.length;k<h;k++){m[j[k]]=q(m[j[k]],m)}if(d){o.addEventListener("mouseover",this.onMouse,true);o.addEventListener("mousedown",this.onMouse,true);o.addEventListener("mouseup",this.onMouse,true)}o.addEventListener("click",this.onClick,true);o.addEventListener("touchstart",this.onTouchStart,false);o.addEventListener("touchmove",this.onTouchMove,false);o.addEventListener("touchend",this.onTouchEnd,false);o.addEventListener("touchcancel",this.onTouchCancel,false);if(!Event.prototype.stopImmediatePropagation){o.removeEventListener=function(l,s,i){var r=Node.prototype.removeEventListener;if(l==="click"){r.call(o,l,s.hijacked||s,i)}else{r.call(o,l,s,i)}};o.addEventListener=function(r,s,l){var i=Node.prototype.addEventListener;if(r==="click"){i.call(o,r,s.hijacked||(s.hijacked=function(t){if(!t.propagationStopped){s(t)}}),l)}else{i.call(o,r,s,l)}}}if(typeof o.onclick==="function"){p=o.onclick;o.addEventListener("click",function(i){p(i)},false);o.onclick=null}}var b=navigator.userAgent.indexOf("Windows Phone")>=0;var d=navigator.userAgent.indexOf("Android")>0&&!b;var f=/iP(ad|hone|od)/.test(navigator.userAgent)&&!b;var a=f&&(/OS 4_\d(_\d)?/).test(navigator.userAgent);var g=f&&(/OS [6-7]_\d/).test(navigator.userAgent);var c=navigator.userAgent.indexOf("BB10")>0;e.prototype.needsClick=function(h){switch(h.nodeName.toLowerCase()){case"button":case"select":case"textarea":if(h.disabled){return true}break;case"input":if((f&&h.type==="file")||h.disabled){return true}break;case"label":case"iframe":case"video":return true}return(/\bneedsclick\b/).test(h.className)};e.prototype.needsFocus=function(h){switch(h.nodeName.toLowerCase()){case"textarea":return true;case"select":return !d;case"input":switch(h.type){case"button":case"checkbox":case"file":case"image":case"radio":case"submit":return false}return !h.disabled&&!h.readOnly;default:return(/\bneedsfocus\b/).test(h.className)}};e.prototype.sendClick=function(h,i){var j,k;if(document.activeElement&&document.activeElement!==h){document.activeElement.blur()}k=i.changedTouches[0];j=document.createEvent("MouseEvents");j.initMouseEvent(this.determineEventType(h),true,true,window,1,k.screenX,k.screenY,k.clientX,k.clientY,false,false,false,false,0,null);j.forwardedTouchEvent=true;h.dispatchEvent(j)};e.prototype.determineEventType=function(h){if(d&&h.tagName.toLowerCase()==="select"){return"mousedown"}return"click"};e.prototype.focus=function(i){var h;if(f&&i.setSelectionRange&&i.type.indexOf("date")!==0&&i.type!=="time"&&i.type!=="month"){h=i.value.length;i.setSelectionRange(h,h)}else{i.focus()}};e.prototype.updateScrollParent=function(h){var j,i;j=h.fastClickScrollParent;if(!j||!j.contains(h)){i=h;do{if(i.scrollHeight>i.offsetHeight){j=i;h.fastClickScrollParent=i;break}i=i.parentElement}while(i)}if(j){j.fastClickLastScrollTop=j.scrollTop}};e.prototype.getTargetElementFromEventTarget=function(h){if(h.nodeType===Node.TEXT_NODE){return h.parentNode}return h};e.prototype.onTouchStart=function(j){var i,k,h;if(j.targetTouches.length>1){return true}i=this.getTargetElementFromEventTarget(j.target);k=j.targetTouches[0];if(f){h=window.getSelection();if(h.rangeCount&&!h.isCollapsed){return true}if(!a){if(k.identifier&&k.identifier===this.lastTouchIdentifier){j.preventDefault();return false}this.lastTouchIdentifier=k.identifier;this.updateScrollParent(i)}}this.trackingClick=true;this.trackingClickStart=j.timeStamp;this.targetElement=i;this.touchStartX=k.pageX;this.touchStartY=k.pageY;if((j.timeStamp-this.lastClickTime)<this.tapDelay){j.preventDefault()}return true};e.prototype.touchHasMoved=function(h){var j=h.changedTouches[0],i=this.touchBoundary;if(Math.abs(j.pageX-this.touchStartX)>i||Math.abs(j.pageY-this.touchStartY)>i){return true}return false};e.prototype.onTouchMove=function(h){if(!this.trackingClick){return true}if(this.targetElement!==this.getTargetElementFromEventTarget(h.target)||this.touchHasMoved(h)){this.trackingClick=false;this.targetElement=null}return true};e.prototype.findControl=function(h){if(h.control!==undefined){return h.control}if(h.htmlFor){return document.getElementById(h.htmlFor)}return h.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")};e.prototype.onTouchEnd=function(j){var m,l,i,k,n,h=this.targetElement;if(!this.trackingClick){return true}if((j.timeStamp-this.lastClickTime)<this.tapDelay){this.cancelNextClick=true;return true}if((j.timeStamp-this.trackingClickStart)>this.tapTimeout){return true}this.cancelNextClick=false;this.lastClickTime=j.timeStamp;l=this.trackingClickStart;this.trackingClick=false;this.trackingClickStart=0;if(g){n=j.changedTouches[0];h=document.elementFromPoint(n.pageX-window.pageXOffset,n.pageY-window.pageYOffset)||h;h.fastClickScrollParent=this.targetElement.fastClickScrollParent}i=h.tagName.toLowerCase();if(i==="label"){m=this.findControl(h);if(m){this.focus(h);if(d){return false}h=m}}else{if(this.needsFocus(h)){if((j.timeStamp-l)>100||(f&&window.top!==window&&i==="input")){this.targetElement=null;return false}this.focus(h);this.sendClick(h,j);if(!f||i!=="select"){this.targetElement=null;j.preventDefault()}return false}}if(f&&!a){k=h.fastClickScrollParent;if(k&&k.fastClickLastScrollTop!==k.scrollTop){return true}}if(!this.needsClick(h)){j.preventDefault();this.sendClick(h,j)}return false};e.prototype.onTouchCancel=function(){this.trackingClick=false;this.targetElement=null};e.prototype.onMouse=function(h){if(!this.targetElement){return true}if(h.forwardedTouchEvent){return true}if(!h.cancelable){return true}if(!this.needsClick(this.targetElement)||this.cancelNextClick){if(h.stopImmediatePropagation){h.stopImmediatePropagation()}else{h.propagationStopped=true}h.stopPropagation();h.preventDefault();return false}return true};e.prototype.onClick=function(h){var i;if(this.trackingClick){this.targetElement=null;this.trackingClick=false;return true}if(h.target.type==="submit"&&h.detail===0){return true}i=this.onMouse(h);if(!i){this.targetElement=null}return i};e.prototype.destroy=function(){var h=this.layer;if(d){h.removeEventListener("mouseover",this.onMouse,true);h.removeEventListener("mousedown",this.onMouse,true);h.removeEventListener("mouseup",this.onMouse,true)}h.removeEventListener("click",this.onClick,true);h.removeEventListener("touchstart",this.onTouchStart,false);h.removeEventListener("touchmove",this.onTouchMove,false);h.removeEventListener("touchend",this.onTouchEnd,false);h.removeEventListener("touchcancel",this.onTouchCancel,false)};e.notNeeded=function(j){var h;var l;var k;var i;if(typeof window.ontouchstart==="undefined"){return true}l=+(/Chrome\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1];if(l){if(d){h=document.querySelector("meta[name=viewport]");if(h){if(h.content.indexOf("user-scalable=no")!==-1){return true}if(l>31&&document.documentElement.scrollWidth<=window.outerWidth){return true}}}else{return true}}if(c){k=navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/);if(k[1]>=10&&k[2]>=3){h=document.querySelector("meta[name=viewport]");if(h){if(h.content.indexOf("user-scalable=no")!==-1){return true}if(document.documentElement.scrollWidth<=window.outerWidth){return true}}}}if(j.style.msTouchAction==="none"||j.style.touchAction==="manipulation"){return true}i=+(/Firefox\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1];if(i>=27){h=document.querySelector("meta[name=viewport]");if(h&&(h.content.indexOf("user-scalable=no")!==-1||document.documentElement.scrollWidth<=window.outerWidth)){return true}}if(j.style.touchAction==="none"||j.style.touchAction==="manipulation"){return true}return false};e.attach=function(i,h){return new e(i,h)};if(typeof define==="function"&&typeof define.amd==="object"&&define.amd){define(function(){return e})}else{if(typeof module!=="undefined"&&module.exports){module.exports=e.attach;module.exports.FastClick=e}else{window.FastClick=e}}}());
var Util = {
    getCookie: function getCookie(name){
        if (document.cookie.length>0){
            var start = document.cookie.indexOf(name + "=");
            if (start != -1){
                var end = document.cookie.indexOf(";", start);
                if (end == -1) end = document.cookie.length;

                return unescape(document.cookie.substring(start, end));
            }
        }
        return "";
    },
    loginUrl:function loginUrl(){
        var urlTo="";
        var isAndroid = (/android/gi).test(navigator.appVersion);
        var isIOS     = (/iphone|ipad/gi).test(navigator.appVersion);
        var kanqiu_version = this.getCookie("kanqiu_version");
        if(isAndroid){
            if(kanqiu_version >= "7.0.0"){
                urlTo = "kanqiu://account/account";
            }else{
                urlTo = "http://passport.shihuo.cn/m/2?from=m&project=shihuo&appid=10017&jumpurl="+ encodeURI(window.location.href);
            }
        }else if(isIOS){
            if(kanqiu_version >= "7.0.0"){
                urlTo = "prokanqiu://account/login";
            }else{
                urlTo = "http://passport.shihuo.cn/m/2?from=m&project=shihuo&appid=10017&jumpurl="+ encodeURI(window.location.href);
            }
        }
        return urlTo;
    },
    checkLogin: function checkLogin() {
        var ua = Util.getCookie('ua');
        if (ua) {
            return true;
        } else {
            return false;
        }
    },

    touchMoveHandle: function touchMoveHandle(e) {
        e.preventDefault();
        return false;
    },

    showSkuBox: function() {
        document.addEventListener('touchmove', Util.touchMoveHandle, false);
        var mask = $('#js-mask'),
            skuBox = $('#js-sku-box');

        mask.css('display', 'block');
        mask.removeClass('maskFadeOut').addClass('maskFadeIn');
        skuBox.show();
        skuBox.removeClass('fadeOutDown').addClass('fadeInUp');
    },

    closeSkuBox: function closeSkuBox() {
        document.removeEventListener('touchmove', Util.touchMoveHandle);
        var mask = $('#js-mask'),
            skuBox = $('#js-sku-box');

        var skuTitle = $('#js-sku-title'),
            skuDesc = $('#js-sku-desc');

        mask.removeClass('maskFadeIn').addClass('maskFadeOut');
        // mask.removeClass('maskFadeIn');
        skuBox.removeClass('fadeInUp').addClass('fadeOutDown');

        if ($('.sku-color-btn').length) {
            if ($('.sku-size-btn.selected').length == 0 || $('.sku-color-btn.selected').length == 0) {
                skuTitle.html('请选择');
                skuDesc.addClass('not-select');
                skuDesc.html("尺寸，颜色等属性");
            } else {
                skuTitle.html('已选择');
                skuDesc.removeClass('not-select');
                skuDesc.html('"' + $.trim($('.sku-size-btn.selected').html()) + ', ' + $.trim($('.sku-color-btn.selected').html()) + '"');
            }
        } else {
            if ($('.sku-size-btn.selected').length == 0) {
                skuTitle.html('请选择');
                skuDesc.addClass('not-select');
                skuDesc.html("尺寸，颜色等属性");
            } else {
                skuTitle.html('已选择');
                skuDesc.removeClass('not-select');
                skuDesc.html('"' + $.trim($('.sku-size-btn.selected').html())  + '"');
            }
        }

    }

};

var templates = {
    sku: function sku() {
        return {
            container: $('#js-sku-box-inner'),
            template: _.template($('#tpl-sku-box').html())
        }
    }
};

var skuTpl = templates.sku();
var secret = md5(goods_id + '' + product_id + '123456');

//初始化操作

//当前已选择的sku
window.currentSku = {};

renderSkuBox(skuBoxInit);


function renderSkuBox(cb) {

    var color = '',
        size = '';

    if (updateFlag) {

        var updatePriceSpan = $('#js-price-update'),
            updateMask = $('#js-update-mask');
        //更新价格
        updatePriceSpan.show();

        //显示sku选择框加载
        if (updateMask.length) {
            updateMask.show();
        } else {
            $('#js-sku-box').append('<div class="sku-mask" id="js-update-mask"><div class="sku-mask-inner">由于价格变动，数据正在更新中...</div></div>');
            updateMask = $('#js-update-mask');
            updateMask.show();
        }

        $.ajax({
            url: 'http://www.shihuo.cn/app2/updateDaigouPrice',
            type: 'post',
            dataType: 'json',
            data: {pid: product_id, gid: goods_id, token: md5(goods_id + "" + product_id + "123456")},
            success: function(data) {

                updatePriceSpan.hide();

                updateMask.hide();

                if (data.status == 0) {
                    $('#js-price').html('￥' + data.data.price);
                    $('#js-dollar').html('$' + data.data.dollar);

                    getDaigouSkuInfo(cb);
                }

            }
        })

    } else {
        getDaigouSkuInfo(cb);
    }


}

function updateCartBadge() {
    var cartBadge = $('#js-cart-badge');
    cartBadge.show();

    $.ajax({
        url: 'http://m.shihuo.cn/haitao/cartCount',
        type: 'get',
        dataType: 'json',
        success: function(response) {
            if (response.status == 0) {
                if (response.data.count != 0) {
                    cartBadge.html(response.data.count).show();
                }
            }
        }
    })
}

function getDaigouSkuInfo(cb) {
    $.ajax({
        url: 'http://www.shihuo.cn/app2/getDaigouSkuInfo?id=' + product_id + '&goods_id=' + goods_id + '&token=' + secret,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            cb && cb(data);
        }
    });
}

//展示每个sku对应的营销活动信息
function displaySkuMarketInfo(currentSku) {
    // alert(JSON.stringify(currentSku.market_info));
    var marketItems = "";
    for(var i = 0, len = currentSku.market_info.length; i < len; i++) {
        marketItems += '<span class="sku-market-info">' + currentSku.market_info[i] + '</span>';
    }
    $('#js-market').html(marketItems);
}

function skuBoxInit(data) {
    var data = data.data,
        limit = data.limit;

    data.title = $('#js-title').html();

    //所有属性, attr: {attr:"" content:[] } attr 可能为空
    var contentKeys = Object.keys(data.attr.content[0]);
    var attrs = _.intersection(Object.keys(data.attr), contentKeys);

    attrs && attrs.length && attrs.forEach(function(item, index) {
        if (/Size/.test(item)) {
            if (index != 0) {
                var temp = attrs[0];
                attrs[0] = attrs[index];
                attrs[1] = temp;
            }
        }
    });
    data.attrs = attrs;


    if (data.attr.attr == "" || data.attr.attr.length == 0) {
        $('#js-sku').hide();
        currentSku = data.attr.content[0];
        return;
    } else {
    }

    skuTpl.container.html(skuTpl.template(data));

    $clamp($('#js-sku-item-title').get(0), {clamp: 2});
    var skuScroll = new IScroll('#sku-wrapper');

    $('#js-sku-minus, #js-sku-add').on('touchstart', function() {
        $(this).addClass('touch');
    }).on('touchend', function() {
        $(this).removeClass('touch');
    });

    $('#js-sku-add').on('click', addNum);
    $('#js-sku-minus').on('click', minusNum);
    $('#js-sku-num').on('blur', numInputBlur);

    var addCartConfirmBtn = $('#js-add-cart-confirm');

	addCartConfirmBtn.on('click', function(e) {
		__dace.sendEvent('shihuo_m_daigou_detail_cart');
		$.ajax({
			url: 'http://m.shihuo.cn/haitao/cartAdd',
			type: 'post',
			dataType: 'json',
			data: {pid: currentSku.pid, gid: currentSku.gid, num: $('#js-sku-num').val()},
			success: function(data) {
				if (data.status == 0) {
					new Tip({msg:'成功加入购物车'}).show();
					//更新购物车数量 data.data.count
					updateCartBadge();
					setTimeout(function(){
						Util.closeSkuBox();//关闭弹框
					},1500);
				} else {
					new Tip({msg: data.msg}).show();
				}
			},
			fail: function(err) {
				new Tip({msg: '服务异常'}).show();
			}
		});
	});

    $('#js-sku-buy').on('click', function() {
        if(!loginflag){
            $("#login-mask").addClass('maskFadeIn').show();
            $("#loginAlert").show();

            return false;
        } else {
            var data = {
                product_id: product_id,
                goods_id: currentSku.gid,
                mobile: $("#loginAlert .logintel").val(),
                num: $('#js-sku-num').val()
            };

            window.location.href = "http://m.shihuo.cn/daigou/order?product_id=" + data.product_id + "&goods_id=" + data.goods_id + "&num=" + data.num+"&mobile="+data.mobile;
        }
    });

    //尺寸，颜色选择
    var sizeBtns = $('.sku-size-btn');
    colorBtns = $('.sku-color-btn');


    var sizes = data.attr[attrs[0]],
        colors = data.attr[attrs[1]],
        content = data.attr.content;

    var colorsForSize = {},
        sizesForColor = {};


    sizes && sizes.forEach(function(size) {
        colorsForSize[size] = content.filter(function(item) {
            if (item[attrs[0]] == size) {
                return true;
            }
            return false;
        })
    });

    colors && colors.forEach(function(color) {
        sizesForColor[color] = content.filter(function(item) {
            if (item[attrs[1]] == color) {
                return true;
            }
            return false;
        })
    });

    //更新选择的尺寸，颜色
    var skuTitle = $('#js-sku-title'),
        skuDesc = $('#js-sku-desc'),
        skuColorSizeTitle = $('#js-color-size');

    function updateSizeColorForShow(size, color, initFlag) {
        if (!initFlag) {
            skuTitle.html('已选择');
            skuDesc.removeClass('not-select');
            skuDesc.html('"' + (size ? size : "") + (color ? ', ': '') + (color ? color : "") + '"');
        }
        skuColorSizeTitle.html('"' + (size ? size : "") + (color ? ', ': '') + (color ? color : "") + '"');
    }

    function disableColorsrNotForSize(colorsForSize, allColors) {
        colorBtns.removeClass('disabled');
        allColors && allColors.forEach(function(color) {
            if (!_.contains(colorsForSize, color)) {
                colorBtns.each(function(index, item) {
                    if ($.trim($(item).html()) == color) {
                        $(item).addClass('disabled');
                    }
                });
            }
        });
    }

    function disableSizesNotForColor(sizesForColor, allSizes) {
        sizeBtns.removeClass('disabled');
        allSizes && allSizes.forEach(function(size) {
            if (!_.contains(sizesForColor, size)) {
                sizeBtns.each(function(index, item) {
                    if ($.trim($(item).html()) == size) {
                        $(item).addClass('disabled');
                    }
                });
            }
        });
    }

    //初始化尺寸和颜色

    var initSize = $.trim($('.sku-size-btn').first().html()),
        initColor = colorsForSize && colorsForSize[initSize] && colorsForSize[initSize][0][attrs[1]];
    // debugger;
    sizeBtns.first().addClass('selected');

    colorBtns.each(function(index, item) {
        if ($.trim($(item).html()) == initColor) {
            $(item).addClass('selected');
        }
    });

    updateSizeColorForShow(initSize, initColor, true);
    var initColors = _.pluck(colorsForSize[initSize], attrs[1]);
    disableColorsrNotForSize(initColors, colors);

    //符合当前条件的sku商品
    var skuImg = $('#js-sku-img'),
        priceNum = $('#js-price-num'),
        originNum = $('#js-origin-num');

    //初始化
    var a1 = attrs[0],
        a2 = attrs[1];

    if (a1) {
        if (/Size/.test(a1)) {
            $("#js-sku-size-title").html("尺寸选择");
        } else {
            if (/Color/.test(a1)) {
                $("#js-sku-size-title").html("颜色选择");
            } else {
                $("#js-sku-size-title").html(a1);
            }
        }
    } else {
        $('#sku-wrapper li.sku-size').css('display', 'none');
    }

    if (a2) {
        if (/Color/.test(a2)) {
            $("#js-sku-color-title").html("颜色选择");
        } else {
            $("#js-sku-color-title").html(a2);
        }
    } else {
        $('#sku-wrapper li.sku-color').css('display', 'none');
    }

    // currentSku = _.where(data.attr.content, {a1: initSize, a2: initColor});
    if (attrs.length == 0) {
        currentSku = data.attr.content[0];
    } else if (attrs.length == 1) {
        data.attr.content.forEach(function(item) {
            if (item[a1] == initSize) {
                currentSku = item;
                return false;
            }
        });
    } else if (attrs.length == 2) {
        data.attr.content.forEach(function(item) {
            if ((item[a1] == initSize) && (item[a2] == initColor)) {
                currentSku = item;
                return false;
            }
        });
    }

    skuImg.attr('src', currentSku.img);
    priceNum.html(currentSku.Price);
    originNum.html('$' + currentSku.price);
    displaySkuMarketInfo(currentSku);

    $('#js-sku').on('click', Util.showSkuBox);
    $('#js-mask').on('click', Util.closeSkuBox);
    $('#js-sku-close').on('click', Util.closeSkuBox);

    sizeBtns.on('tap', function() {
        var colorBtns = $('.sku-color-btn');

        if ($(this).hasClass('disabled')) {
            return;
        }

        if ($(this).hasClass('selected')) {
            sizeBtns.removeClass('selected').removeClass('disabled');
            //激活colors
            colorBtns.removeClass('disabled');
            return;
        } else {
            sizeBtns.removeClass('selected');
            $(this).addClass('selected');
        }


        //选择颜色，如果当前颜色符合尺寸，
        //不变化，否则选择可用的颜色第一个
        var size = $.trim($(this).html()),
            sizeColors = _.pluck(colorsForSize[size], attrs[1]);

        if (attrs.length == 0) {

        } else if (attrs.length == 1) {
            data.attr.content.forEach(function(item) {
                if (item[a1] == size) {
                    currentSku = item;
                    return false;
                }
            });

            skuImg.attr('src', currentSku.img);
            priceNum.html(currentSku.Price);
            originNum.html('$' + currentSku.price);
            updateSizeColorForShow(size, sizeColors[0]);
            displaySkuMarketInfo(currentSku);

        } else if (attrs.length == 2) {
            var currentColor = $.trim($('.sku-color-btn.selected').html());

            disableColorsrNotForSize(sizeColors, colors);

            ////如果当前的color 在 sizeColors中，就直接使用；否则就遍历取第一个可用的color
            if (_.indexOf(sizeColors, currentColor) == -1) {

                colorBtns.each(function(index, colorItem) {

                    //匹配Size colors 的第一个color
                    if ($.trim($(colorItem).html()) == sizeColors[0]) {
                        colorBtns.removeClass('selected');
                        $(colorItem).addClass('selected');

                        //获取符合条件sku
                        // currentSku = _.where(data.attr.content, {Size: size, Color: sizeColors[0]});
                        //所有的组合，匹配，选出符合条件的一条数据
                        data.attr.content.forEach(function(item) {
                            if ((item[a1] == size) && (item[a2] == sizeColors[0])) {
                                currentSku = item;
                                return false;
                            }
                        });

                    }
                });

                skuImg.attr('src', currentSku.img);
                priceNum.html(currentSku.Price);
                originNum.html('$' + currentSku.price);
                updateSizeColorForShow(size, sizeColors[0]);
                displaySkuMarketInfo(currentSku);

                return false;

            } else {
                //获取符合条件sku
                // currentSku = _.where(data.attr.content, {Size: size, Color: currentColor});
                data.attr.content.forEach(function(item) {
                    if ((item[a1] == size) && (item[a2] == currentColor)) {
                        currentSku = item;
                        return false;
                    }
                });


                skuImg.attr('src', currentSku.img);
                priceNum.html(currentSku.Price);
                originNum.html('$' + currentSku.price);

                updateSizeColorForShow(size, currentColor);
                displaySkuMarketInfo(currentSku);
            }
        }


    });

    colorBtns.on('tap', function() {
        if ($(this).hasClass('disabled')) {
            return;
        }

        if ($(this).hasClass('selected')) {
            colorBtns.removeClass('selected').removeClass('disabled');
            //激活sizes
            $('.sku-size-btn').removeClass('disabled');
            return;
        } else {
            colorBtns.removeClass('selected');
            $(this).addClass('selected');
            $('.sku-size-btn').removeClass('disabled');
        }

        var color = $.trim($(this).html()),
            colorSizes = _.pluck(sizesForColor[color], attrs[0]),
            sizeBtns = $('.sku-size-btn');

        var currentSize = $.trim($('.sku-size-btn.selected').html());

        disableSizesNotForColor(colorSizes, sizes);


        //如果当前的size 在 colorSizes中，就直接使用；否则就遍历取第一个可用的size
        if (_.indexOf(colorSizes, currentSize) == -1) {
            sizeBtns.each(function(index, sizeItem) {

                if ($.trim($(sizeItem).html()) == colorSizes[0]) {

                    sizeBtns.removeClass('selected');
                    $(sizeItem).addClass('selected');

                    // currentSku = _.where(data.attr.content, {Size: sizes[0], Color: color});
                    data.attr.content.forEach(function(item, index) {
                        if ((item[a1] == colorSizes[0]) && (item[a2] == color)) {
                            currentSku = item;
                            return false;
                        }
                    });
                }

            });

            skuImg.attr('src', currentSku.img);
            priceNum.html(currentSku.Price);
            originNum.html('$' + currentSku.price);
            updateSizeColorForShow(currentSku[a1], color);
            displaySkuMarketInfo(currentSku);

            return false;
        } else {
            //获取符合条件sku
            // currentSku = _.where(data.attr.content, {Size: currentSize, Color: color});
            data.attr.content.forEach(function(item) {
                if ((item[a1] == currentSize) && (item[a2] == color)) {
                    currentSku = item;
                    return false;
                }
            });

            skuImg.attr('src', currentSku.img);
            priceNum.html(currentSku.Price);
            originNum.html('$' + currentSku.price);
            updateSizeColorForShow(currentSize, color);
            displaySkuMarketInfo(currentSku);
        }
    });

}

var addNum = function addNum() {
    var skuNumInput = $('#js-sku-num'),
        limit = window.currentSku.item_limit * 1;

    if (parseInt(skuNumInput.val(),10) + 1 > limit) {
        skuNumInput.val(limit);
    } else {
        skuNumInput.val(parseInt(skuNumInput.val(),10) + 1);
    }

}

var minusNum = function minusNum() {
    var skuNumInput = $('#js-sku-num');
    if (skuNumInput.val() == 1) {
        return false;
    } else {
        skuNumInput.val(parseInt(skuNumInput.val(),10) - 1);
    }
}

var numInputBlur = function numInputBlur() {
    var numInput = $('#js-sku-num');

    if ($.trim(numInput.val()).length == 0) {
        numInput.val(1);
    } else {
        if (parseInt(numInput.val(), 10) > limit) {
            numInput.val(limit);
        }
    }

}


//加入购物车
var addCartShowBtn = $('#js-add-cart-show');

addCartShowBtn.on('click', function(e) {
    if (Util.checkLogin()) {
        //如果没有sku,那么就直接加入购物车
        if ($('#js-sku').css('display') == 'none') {
            $.ajax({
                url: 'http://m.shihuo.cn/haitao/cartAdd',
                type: 'post',
                dataType: 'json',
                data: {pid: product_id, gid: goods_id, num: 1},
                success: function(data) {
                    if (data.status == 0) {
                        new Tip({msg:'成功加入购物车'}).show();
                        //更新购物车数量 data.data.count
                        $.ajax({
                            url:'http://m.shihuo.cn/haitao/cartCount',
                            type: 'get',
                            dataType: 'json',
                            success: function(data) {
                                if (data.status == 0) {
                                    $('#js-cart-badge').html(data.data.count);
                                }
                            }
                        })
                    } else {
                        new Tip({msg:data.msg}).show();
                    }
                },
                fail: function(err) {
                    new Tip({msg: '服务异常'}).show();
                }
            })
        } else {
            Util.showSkuBox();
        }

    } else {
        location.href = Util.loginUrl();
    }

});




