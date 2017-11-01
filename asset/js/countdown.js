////////////////////////////////////////////
// 
//    Countdown
//    v5.2.1 - 2016-07-15 01:13
//
//    www.gieson.com
//    Copyright Mike Gieson
//
////////////////////////////////////////////
////////////////////////////////////////////
//
//    Configs
//
////////////////////////////////////////////


var CountdownImageFolder = "images/";
var CountdownImageBasename = "flipper";
var CountdownImageExt = "png";
var CountdownImagePhysicalWidth = 41;
var CountdownImagePhysicalHeight = 60;

var CountdownWidth = 400;
var CountdownHeight = 60;

var CountdownLabels = {
    ms: "MS",
    second: "SECONDS",
    minute: "MINUTES",
    hour: "HOURS",
    day: "DAYS",
    month: "MONTHS",
    year: "YEARS"
};

var CountdownInterval = 76;
var CountdownFadeInMS = 500; // (Only applies to image flipper)

////////////////////////////////////////////
//
//    End Configs
//
////////////////////////////////////////////


;Array.prototype.indexOf || (Array.prototype.indexOf = function (t) {
    if (null == this)throw new TypeError;
    var e = Object(this), r = e.length >>> 0;
    if (0 === r)return -1;
    var n = 0;
    if (arguments.length > 1 && (n = Number(arguments[1]), n != n ? n = 0 : 0 != n && n != 1 / 0 && n != -(1 / 0) && (n = (n > 0 || -1) * Math.floor(Math.abs(n)))), n >= r)return -1;
    for (var i = n >= 0 ? n : Math.max(r - Math.abs(n), 0); i < r; i++)if (i in e && e[i] === t)return i;
    return -1
}), Function.prototype.bind || (Function.prototype.bind = function (t) {
    if ("function" != typeof this)throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
    var e = Array.prototype.slice.call(arguments, 1), r = this, n = function () {
    }, i = function () {
        return r.apply(this instanceof n && t ? this : t, e.concat(Array.prototype.slice.call(arguments)))
    };
    return n.prototype = this.prototype, i.prototype = new n, i
}), this.jbeeb = this.jbeeb || {}, function () {
    "use strict";
    var t = function () {
    }, e = (Array.prototype.indexOf, Object.prototype.toString), r = String.prototype.trim;
    t.link = function (t, e, r) {
        r = r || {};
        var n = e || "_blank", i = [];
        for (var o in r)o = o.toLowerCase(), "width" == o || "height" == o || "left" == o ? i.push(o + "=" + r[o]) : "location" != o && "menubar" != o && "resizable" != o && "scrollbars" != o && "status" != o && "titlebar" != o && "toolbar" != o || i.push(o + "=1");
        var a = null;
        i.length > 0 && (a = i.join(",")), window.open(t, n, a)
    }, t.isArray = function (t) {
        return Array.isArray ? Array.isArray(t) : "[object Array]" === e.call(t)
    }, t.isEmpty = function (e) {
        var r = typeof e;
        if ("undefined" == r)return !0;
        if (null === e)return !0;
        if ("object" == r) {
            if (e == {} || e == [])return !0;
            var n = !0;
            for (var i in e)if (!t.isEmpty(e[i])) {
                n = !1;
                break
            }
            return n
        }
        return "string" == r && "" == e
    }, t.isNumber = function (t) {
        return "[object Number]" === e.call(t) && isFinite(t)
    }, t.isInteger = function (t) {
        return parseFloat(t) == parseInt(t) && !isNaN(t) && isFinite(t)
    }, t.isString = function (t) {
        return "[object String]" === e.call(t)
    }, t.isNull = function (t) {
        return "" === t || null === t || void 0 === t || "undefined" == typeof t || "undefined" == t || "null" == t
    }, t.clone = function (e) {
        if (null === e || "object" != typeof e)return e;
        if (e.init)return e;
        var r = e.constructor;
        if (r) {
            var n = new r;
            for (var i in e)n[i] = t.clone(e[i])
        }
        return n
    }, t.sortOn = function (t, e) {
        return e && t ? void t.sort(function (t, r) {
            return t[e] < r[e] ? -1 : t[e] > r[e] ? 1 : 0
        }) : t
    }, t.arrayShuffle = function (t) {
        if (t) {
            for (var e, r, n = t.length; n;)r = Math.floor(Math.random() * n--), e = t[n], t[n] = t[r], t[r] = e;
            return t
        }
        return []
    }, t.arrayMove = function (t, e, r) {
        t.splice(r, 0, t.splice(e, 1)[0])
    }, t.arrayInsertAt = function (t, e, r) {
        return Array.prototype.splice.apply(t, [e, 0].concat(r)), t
    }, t.rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, t.trim = r && !r.call("\ufeff ") ? function (t) {
        return null == t ? "" : r.call(t)
    } : function (e) {
        return null == e ? "" : (e + "").replace(t.rtrim, "")
    }, t.alphanumeric = function (t, e) {
        return e ? t.replace(/[^A-Za-z0-9]/g, "") : t.replace(/[^A-Za-z0-9_\-\.]/g, "")
    }, t.parseJSON = function (t) {
        if ("string" != typeof t)return null;
        try {
            return JSON.parse(t)
        } catch (e) {
            return t || null
        }
    }, t.hexToRgb = function (t) {
        if (!t)return "";
        var e = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(t);
        return e ? [parseInt(e[1], 16), parseInt(e[2], 16), parseInt(e[3], 16)] : [0, 0, 0]
    }, t.makeColor = function (e, r) {
        if (!e)return "";
        var n = t.hexToRgb(e);
        return t.isNumber(r) && jbeeb.Browser.rgba ? (r > 1 && (r /= 100), r = "," + r, "rgba(" + n.join(",") + r + ")") : e
    }, t.getXYWH = function (t) {
        var e = 0, r = 0, n = 0, i = 0;
        if (t) {
            n = t.offsetWidth, i = t.offsetHeight;
            for (var o = jbeeb.Browser.touch; t && !isNaN(t.offsetLeft) && !isNaN(t.offsetTop);)o ? (e += (t.offsetLeft || 0) - (t.scrollLeft || 0), r += (t.offsetTop || 0) - (t.scrollTop || 0)) : (e += t.offsetLeft || 0, r += t.offsetTop || 0), t = t.offsetParent;
            if (o) {
                var a = null != window.scrollX ? window.scrollX : window.pageXOffset,
                    u = null != window.scrollY ? window.scrollY : window.pageYOffset;
                e += a, r += u
            }
        }
        return {x: e, y: r, w: n, h: i, xMax: e + n, yMax: r + i}
    }, t.getWindowSize = function () {
        var t = window, e = document, r = e.documentElement, n = e.getElementsByTagName("body")[0];
        return {w: t.innerWidth || r.clientWidth || n.clientWidth, h: t.innerHeight || r.clientHeight || n.clientHeight}
    }, t.contains = function (t, e) {
        var r = {}, n = {x: t.x, y: t.y, w: t.width, h: t.height}, i = {x: e.x, y: e.y, w: e.width, h: e.height};
        n.xMax = n.x + n.w, n.yMax = n.y + n.h, i.xMax = i.x + i.w, i.yMax = i.y + i.h;
        for (var o in n)r[o] = n[o] >= i[o];
        var a = !(r.x || r.y || !r.xMax || !r.yMax);
        return a
    }, t.getTimestamp = function () {
        var t = new Date;
        return Date.UTC(t.getFullYear(), t.getMonth(), t.getDate(), t.getHours(), t.getMinutes(), t.getSeconds(), t.getMilliseconds()).valueOf()
    }, t.bindEvent = function (t, e, r) {
        t.attachEvent ? t.attachEvent("on" + e, r) : t.addEventListener && t.addEventListener(e, r, !1)
    }, t.unbindEvent = function (t, e, r) {
        t.attachEvent ? t.detachEvent("on" + e, r) : t.addEventListener && t.removeEventListener(e, r, !1)
    }, t.getAttributes = function (t) {
        var e = {}, r = t.attributes;
        if (r) {
            for (var n = r.length,
                     i = 0; i < n; i++)jbeeb.Browser.ie ? r[i].specified && (e[r[i].nodeName] = r[i].nodeValue.toString()) : r[i].value ? e[r[i].nodeName] = r[i].value.toString() : e[r[i].nodeName] = r[i].nodeValue.toString();
            return e
        }
        return {}
    }, jbeeb.Utils = t
}();
;this.jbeeb = this.jbeeb || {}, function () {
    "use strict";
    var e = function () {
        this.initialize()
    }, t = e.prototype;
    e.initialize = function (e) {
        e.addEventListener = t.addEventListener, e.removeEventListener = t.removeEventListener, e.removeAllEventListeners = t.removeAllEventListeners, e.hasEventListener = t.hasEventListener, e.dispatchEvent = t.dispatchEvent
    }, t.b = null, t.initialize = function () {
    }, t.addEventListener = function (e, t, n, i) {
        var s = this.b;
        s ? this.removeEventListener(e, t, n) : s = this.b = {};
        var r = s[e];
        return r || (r = s[e] = []), r.push({fn: t, arg: i, scope: n}), t
    }, t.removeEventListener = function (e, t, n) {
        var i = this.b;
        if (i) {
            var s = i[e];
            if (s)for (var r = s.length; r--;) {
                var v = s[r];
                v.scope == n && v.fn == t && s.splice(r, 1)
            }
        }
    }, t.removeAllEventListeners = function (e) {
        e ? this.b && delete this.b[e] : this.b = null
    }, t.dispatchEvent = function (e) {
        var t = "undefined", n = this.b;
        if (e && n) {
            var i = n[e];
            if (i) {
                var s = [].slice.call(arguments);
                s.splice(0, 1);
                for (var r = 0; r < i.length; r++) {
                    var v = i[r];
                    if (v.fn) {
                        var l = s, a = v.arg;
                        typeof a !== t && l.push(a), l.length ? v.scope ? v.fn.apply(v.scope, l) : v.fn.apply(null, l) : v.scope ? v.fn.call(v.scope) : v.fn()
                    }
                }
            }
        }
    }, t.hasEventListener = function (e) {
        var t = this.b;
        return !(!t || !t[e])
    }, t.toString = function () {
        return "[EventDispatcher]"
    }, jbeeb.EventDispatcher || (jbeeb.EventDispatcher = e)
}();
;this.jbeeb = this.jbeeb || {}, function () {
    var e = function () {
        var e, t, n, o = [], a = !1, c = document, r = c.documentElement, d = r.doScroll, i = "DOMContentLoaded",
            u = "addEventListener", s = "onreadystatechange", f = "readyState", l = d ? /^loaded|^c/ : /^loaded|c/,
            h = l.test(c[f]);
        return t = function (e) {
            try {
                e = c.getElementsByTagName("body")[0].appendChild(c.createElement("span")), e.parentNode.removeChild(e)
            } catch (e) {
                return setTimeout(function () {
                    t()
                }, 50)
            }
            for (h = 1; e = o.shift();)e()
        }, c[u] && (n = function () {
            c.removeEventListener(i, n, a), t()
        }, c[u](i, n, a), e = function (e) {
            h ? e() : o.push(e)
        }), d && (n = function () {
            /^c/.test(c[f]) && (c.detachEvent(s, n), t())
        }, c.attachEvent(s, n), e = function (t) {
            if (self != top) h ? t() : o.push(t); else {
                try {
                    r.doScroll("left")
                } catch (n) {
                    return setTimeout(function () {
                        e(t)
                    }, 50)
                }
                t()
            }
        }), e
    };
    jbeeb.ready || (jbeeb.ready = e())
}();
;this.jbeeb = this.jbeeb || {}, function () {
    function t() {
        return e && e.call(performance) || (new Date).getTime()
    }

    var i = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame,
        e = window.performance && (performance.now || performance.mozNow || performance.msNow || performance.oNow || performance.webkitNow),
        n = function (t) {
            return this.init(t), this
        }, s = n.prototype;
    s.addEventListener = null, s.removeEventListener = null, s.removeAllEventListeners = null, s.dispatchEvent = null, s.hasEventListener = null, jbeeb.EventDispatcher.initialize(s), s.l = 50, s.k = 0, s.d = null, s.q = null, s.h = null, s.c = !1, s.state = 0, s.init = function (t) {
        t.fps ? (this.c = t.animation && i || !1, this.l = 1e3 / (t.fps || 60)) : this.l = t.interval || 50, t.startNow && this.start()
    }, s.stop = function () {
        this.state = 0, this.g(this.o)
    }, s.getInterval = function () {
        return this.l
    }, s.setInterval = function (t) {
        this.l = t
    }, s.start = function () {
        this.state || (this.state = 1, this.d = [], this.d.push(this.k = t()), this.c ? this.g(this.p) : this.g(this.m), this.j())
    }, s.getFPS = function () {
        var t = this.d.length - 1;
        if (t < 2)return this.l;
        var i = (this.d[0] - this.d[t]) / t;
        return 1e3 / i
    }, s.p = function () {
        this.q = null, this.j(), t() - this.k >= .97 * (this.l - 1) && this.f()
    }, s.m = function () {
        this.q = null, this.j(), this.f()
    }, s.o = function () {
        this.q = null
    }, s.j = function () {
        if (null == this.q) {
            if (this.c)return i(this.h), void(this.q = !0);
            this.q && clearTimeout(this.q), this.q = setTimeout(this.h, this.l)
        }
    }, s.g = function (t) {
        this.h = t.bind(this)
    }, s.f = function () {
        var i = t(), e = i - this.k;
        for (this.k = i, this.dispatchEvent("tick", {
            delta: e,
            time: i
        }), this.d.unshift(i); this.d.length > 100;)this.d.pop()
    }, s.toString = function () {
        return "[Ticker]"
    }, jbeeb.Ticker || (jbeeb.Ticker = n)
}();
;this.jbeeb = this.jbeeb || {}, function () {
    "use strict";
    if (!jbeeb.Browser) {
        var e = {};
        e.ie = null, e.ios = null, e.mac = null, e.webkit = null, e.oldWebkit = !1, e.flash = 0, e.touch = !1;
        var o = function (e) {
            e = e.toLowerCase();
            var o = /(chrome)[ \/]([\w.]+)/.exec(e) || /(webkit)[ \/]([\w.]+)/.exec(e) || /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(e) || /(msie) ([\w.]+)/.exec(e) || e.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(e) || [];
            return {browser: o[1] || "", version: o[2] || "0"}
        }, r = o(navigator.userAgent);
        e.version = parseFloat(r.version);
        var i = r.browser;
        e.agent = i;
        var t = !1;
        "chrome" == i ? t = !0 : "webkit" == i && (t = !0), e.webkit = t, e.chrome = /chrome/.test(i) || /chromium/.test(i), e.moz = /mozilla/.test(i), e.opera = /opera/.test(i), e.safari = /webkit/.test(i), e.ie = /msie/.test(i) && !/opera/.test(i), e.android = /android/.test(i);
        var s = navigator, a = s.platform.toLowerCase();
        e.platform = a, e.ios = /iphone/.test(a) || /ipod/.test(a) || /ipad/.test(a), e.win = e.windows = a ? /win/.test(a) : /win/.test(i), e.mac = a ? /mac/.test(a) : /mac/.test(i), e.cssPrefix = "", e.chrome || e.safari ? (e.cssPrefix = "webkit", e.chrome && e.version < 10 ? e.oldWebkit = !0 : e.safari && e.version < 5.1 && (e.oldWebkit = !0)) : e.opera ? e.cssPrefix = "o" : e.moz ? e.cssPrefix = "moz" : e.ie && e.version > 8 && (e.cssPrefix = "ms"), (e.chrome || e.ios || e.android) && (e.flash = 0);
        var n = !1, m = "animation", c = "", d = "Webkit Moz O ms Khtml".split(" "), l = "",
            b = document.createElement("div");
        if (b.style.animationName && (n = !0), n === !1)for (var v = 0; v < d.length; v++)if (void 0 !== b.style[d[v] + "AnimationName"]) {
            l = d[v], m = l + "Animation", c = "-" + l.toLowerCase() + "-", n = !0;
            break
        }
        b = null, e.animation = n, e.modern = !1, e.moz && e.version > 3 && (e.modern = !0), e.opera && e.version > 9 && (e.modern = !0), e.ie && e.version > 9 && (e.modern = !0), (e.chrome || e.safari || e.ios || e.android) && (e.modern = !0), e.rgba = !0, e.quirks = "CSS1Compat" != document.compatMode, e.ie ? e.version < 9 ? e.rgba = !1 : e.quirks && (e.rgba = !1, e.version = 8, e.modern = !1) : e.moz && e.version < 3 ? e.rgba = !1 : e.safari && e.version < 3 ? e.rgba = !1 : e.opera && e.version < 10 && (e.rgba = !1), e.touch = "undefined" != typeof window.ontouchstart, jbeeb.Browser = e
    }
}();
;this.jbeeb = this.jbeeb || {}, function () {
    "use strict";
    var t = function (t, l) {
        function n(t, l) {
            var s, e, r, i, u, h, b, f, c, g, v, m, j, d;
            t = t || "";
            var x = t.replace(/\\/g, "/");
            if (!x.match(/:\//)) {
                var w = "";
                w = l ? a : o;
                var O = n(w, !1);
                if ("/" == x.substr(0, 1)) x = O.host + (p ? "" : "/") + x; else if ("../" == x.substr(0, 3)) {
                    var P = O.parenturl.split("/"), y = x.split("../"), C = y.pop();
                    P.splice(P.length - y.length, y.length, C), x = P.join("/")
                } else x = O.pathurl + (p ? "" : "/") + x
            }
            "/" == x.substr(-1) && (x = x.substr(0, x.length - 1));
            var L = x.split("://");
            if (e = L.shift(), f = (L.shift() || "").replace("//", "/"), f = f.split("/"), r = f.shift() || "", r.indexOf("@") > -1) {
                L = r.split("@");
                var S = L[0].split(":");
                m = S[0], j = S[1], r = L[1]
            }
            r.indexOf(":") > -1 && (L = r.split(":"), i = L[1], r = L[0]), f = f.join("/"), f.indexOf("#") != -1 && (L = f.split("#"), v = L[1], f = L[0]), f.indexOf("?") != -1 && (L = f.split("?"), g = L[1], f = L[0]), L = f.split("/"), b = L.pop(), f = L.join("/"), ".." == b && (b = "");
            var q = b.split(".");
            q.length > 1 && (h = q.pop().toLowerCase(), u = q.join(".")), d = e + "://" + r + (i ? ":" + i : ""), f = "/" + f + (f.length > 0 ? "/" : ""), c = d + f, s = d + f + b + (g ? "?" + g : "") + (v ? "#" + v : "");
            var A = f, B = c;
            return h ? (f += b, c += b) : (f += b + ("" != b ? "/" : ""), c += b + ("" != b ? "/" : ""), u = b, g || v || "/" == s.substr(-1) || (s += "/")), p === !1 && ("/" == A.substr(-1) && (A = A.substr(0, A.length - 1)), "/" == B.substr(-1) && (B = B.substr(0, B.length - 1)), h || ("/" == f.substr(-1) && (f = f.substr(0, f.length - 1)), "/" == c.substr(-1) && (c = c.substr(0, c.length - 1)), "/" == s.substr(-1) && (s = s.substr(0, s.length - 1)))), {
                source: t || null,
                url: s || null,
                protocol: e || null,
                domain: r || null,
                port: i || null,
                basename: u || null,
                ext: h || null,
                filename: b || null,
                path: f || null,
                pathurl: c || null,
                parent: A || null,
                parenturl: B || null,
                query: g || null,
                fragment: v || null,
                username: m || null,
                password: j || null,
                host: d || null
            }
        }

        function s(t) {
            return t = t || "", t.split("?")[0].split("/").pop()
        }

        function e(t) {
            var l = s(t), n = l.split(".");
            return n.pop(), n.join(".")
        }

        function r(t) {
            t = t || "";
            var l = t.split("?")[0].split("/").pop(), n = l.split(".");
            return n.pop().toLowerCase()
        }

        function i(t) {
            var l = t.split("/");
            return l.pop(), l.join("/").toString() + (l.length > 0 ? "/" : "")
        }

        function u(t) {
            var l, n = document.getElementsByTagName("script"), s = n[n.length - 1], e = s.getAttribute("src");
            return l = e ? t ? e.split("?")[0] : i(e.split("?")[0]) : ""
        }

        var p = !0, a = u(), o = i(window.location.href);
        return {parse: n, filename: s, basename: e, basepath: i, scriptPath: a, getScriptPath: u, pagePath: o, ext: r}
    };
    jbeeb.PathInfo = t()
}();
;this.jbeeb = this.jbeeb || {}, function () {
    "use strict";
    jbeeb.Base || (jbeeb.amReady = !1, jbeeb.ticker = null, jbeeb.tickerInterval = 80, jbeeb.scriptPath = null, jbeeb.pagePath = "", jbeeb.assetsBasePath = "", jbeeb.focus = null, jbeeb.binit = 0), jbeeb.unfocus = function () {
        if (jbeeb.focus) {
            var e = jbeeb.focus;
            e.element && e.element.blur(), jbeeb.focus = null
        }
    };
    var e = function () {
    };
    e.w = 0, e.r = [], e.v = [], e.scriptPath = null, e.x = function (b) {
        return "jbeeb_" + e.w++
    }, e.s = function (b) {
        e.v.push(b), jbeeb.amReady && e.u()
    }, e.u = function () {
        var b = e.v.length;
        if (b > 0)for (var t = b; t--;) {
            var a = e.v.splice(t, 1)[0];
            a && a.init && a.init.call(a)
        }
    }, e.init = function () {
        if (!jbeeb.amReady) {
            jbeeb.ticker = new jbeeb.Ticker({
                interval: jbeeb.tickerInterval,
                startNow: 1
            }), jbeeb.assetsBasePath || (jbeeb.assetsBasePath = "");
            var b = window.location.href;
            "http" != b.substr(0, 4) ? (jbeeb.pagePath || (jbeeb.pagePath = ""), jbeeb.scriptPath || (jbeeb.scriptPath = "")) : (jbeeb.pagePath || (jbeeb.pagePath = jbeeb.PathInfo.pagePath), jbeeb.scriptPath || (jbeeb.scriptPath = jbeeb.PathInfo.scriptPath)), jbeeb.FlashDetect && jbeeb.FlashDetect.run(), jbeeb.amReady = !0, e.u()
        }
    }, jbeeb.Base || (jbeeb.Base = e, jbeeb.register = e.s, jbeeb.getUID = e.x)
}(), jbeeb.binit || (jbeeb.binit = 1, jbeeb.ready(function () {
    jbeeb.Base.init()
}));
;this.jbeeb = this.jbeeb || {}, function () {
    "use strict";
    var t = function (t) {
        this.init(t)
    }, e = t.prototype;
    e.addEventListener = null, e.removeEventListener = null, e.removeAllEventListeners = null, e.dispatchEvent = null, e.hasEventListener = null, jbeeb.EventDispatcher.initialize(e), e.amStage = null, e.element = null, e.style = null, e.O = null, e.alpha = 1, e.id = null, e.name = null, e.parent = null, e.stage = null, e.rotation = 0, e.scale = 1, e.scaleX = 1, e.scaleY = 1, e.stretchX = 1, e.stretchY = 1, e.skewX = 0, e.skewY = 0, e.origin = null, e.originX = 0, e.originY = 0, e.originType = "px", e.shadow = null, e.bevel = null, e.outline = null, e.inset = null, e.visible = !0, e.overflow = "visible", e.autoCenter = null, e.x = 0, e.y = 0, e.width = 0, e.height = 0, e.flex = "wh", e.L = 1, e.M = 1, e.pin = null, e.D = null, e.C = null, e.z = 0, e.temp = null, e.rounded = null, e.fill = null, e.stroke = null, e.image = null, e.gradient = null, e.P = null, e.init = function (t) {
        this.temp = {}, this.style = null, this.alpha = 1, this.id = null, this.name = null, this.parent = null, this.rotation = 0, this.scale = 1, this.scaleX = 1, this.scaleY = 1, this.skewX = 0, this.skewY = 0, this.visible = !0, this.overflow = "visible", this.x = 0, this.y = 0, this.width = 0, this.height = 0, this.flex = "wh", this.L = 1, this.M = 1, this.pin = null, this.D = null, this.C = null, this.z = 0, this.autoCenter = null, this.stroke = {}, this.fill = {}, this.shadow = null, this.inset = null, this.gradient = {}, this.rounded = null, jbeeb.storeCSS ? this.O = {} : this.O = null;
        var t = t || {}, e = jbeeb.getUID();
        this.id = e, t.element ? this.element = t.element : (this.element = document.createElement("div"), this.element.id = e, this.element.style.position = "absolute", this.element.style.overflow = "visible", this.O && (this.O.position = "absolute", this.O.overflow = "visible")), t.standalone && (this.amStage = 1), t.inline ? this.P = "inline-block" : this.P = "block", t.name && (this.name = t.name), this.element.id = this.type + "_" + this.element.id;
        var i = this.style = this.element.style;
        if (i.padding = "0px", i.margin = "0px", i.border = "0px", i.fontSize = "100%", i.verticalAlign = "baseline", i.outline = "0px", i.background = "transparent", i.WebkitTextSizeAdjust = "100%", i.msTextSizeAdjust = "100%", i.WebkitBoxSizing = i.MozBoxSizing = i.boxSizing = "padding-box", i.backgroundClip = "padding-box", this.O) {
            var s = this.O;
            s.padding = "0px", s.margin = "0px", s.border = "0px", s.fontSize = "100%", s.verticalAlign = "baseline", s.outline = "0px", s.background = "transparent", s.WebkitTextSizeAdjust = "100%", s.msTextSizeAdjust = "100%", s.boxSizing = "padding-box", s.backgroundClip = "padding-box"
        }
        t.editable || this.setSelectable(!1), this.setCursor("inherit"), t && (this.autoCenter = t.center, "undefined" != typeof t.flex && this.setFlex(t.flex), "undefined" != typeof t.pin && this.setPin(t.pin), "undefined" != typeof t.overflow && this.setOverflow(t.pin)), this.setOrigin(0, 0, "px"), this.applySkin(t, !1)
    }, e.setSelectable = function (t) {
        var e = this.style, i = "none", s = "-moz-none";
        t && (i = "text", s = "text"), e.userSelect = e.WebkitUserSelect = e.MozUserSelect = e.OUserSelect = i, e.MozUserSelect = s, this.O && (this.O.userSelect = i, this.O.MozUserSelect = s)
    }, e.setBorderRender = function (t) {
        var e, i = this.style;
        e = "outside" == t ? "content-box" : "border-box", i.WebkitBoxSizing = i.MozBoxSizing = i.boxSizing = e, this.O && (this.O.boxSizing = e)
    }, e.applySkin = function (t, e) {
        if (this.stroke = {}, this.fill = {}, this.gradient = null, this.rounded = 0, this.image = null, this.shadow = null, this.bevel = null, this.outline = null, this.inset = null, e = 1 == e && e, !e) {
            var i = jbeeb.Utils.isNumber(t.x) ? t.x : 0, s = jbeeb.Utils.isNumber(t.y) ? t.y : 0;
            this.setXY(i, s), t.height && this.setHeight(t.height), t.width && this.setWidth(t.width), t.h && this.setHeight(t.h), t.w && this.setWidth(t.w)
        }
        this.setRounded(t.rounded);
        var h, n, o = t.fill;
        if (o) {
            var o = o;
            "string" == typeof o ? (h = o, n = 1) : (h = o.color, n = o.alpha)
        }
        this.setFill(h, n);
        var o = t.stroke;
        h = null, n = null;
        var r = null, l = null;
        o && ("string" == typeof o ? (h = o, n = 1, r = 1, l = "solid") : null != o.color && (h = o.color || "#000000", n = jbeeb.Utils.isNumber(o.alpha) ? o.alpha : 1, r = o.weight || 1, l = o.style || "solid")), this.setStroke(r, h, n, l), this.setStrokeStyle(l);
        var a, u, o = t.image;
        t.image && ("string" == typeof o ? (a = o, u = null) : (a = o.url, u = o.mode)), this.setImage(a, u), this.setShadow(t.shadow), this.setBevel(t.bevel), this.setOutline(t.outline), this.setInset(t.inset)
    }, e.U = function () {
        var t = this.style;
        if (t) {
            var e = "", i = "", s = "", h = "", n = "", o = 0, r = this.fill;
            if (r && (jbeeb.Utils.isArray(r.color) ? o = 1 : r.color && (i = jbeeb.Utils.makeColor(r.color, r.alpha))), this.image && this.image.url) {
                e = 'url("' + this.image.url + '")';
                var l = this.image.mode || "center";
                "pattern" == l || ("fit" == l ? s = "100% 100%" : "contain" != l && "cover" != l || (s = "contain"), h = "no-repeat", n = "center center"), o = 0
            }
            if (o) {
                var a = r.color;
                this.O && (this.O.gradient = 1);
                for (var u = r.alpha || "v", c = jbeeb.Browser, d = [], p = [], f = a.length, g = c.oldWebkit,
                         b = 0; b < f; b += 3) {
                    var W = jbeeb.Utils.makeColor(a[b], a[b + 1]), m = a[b + 2];
                    m > 100 ? m = 100 : m < 0 && (m = 0), g ? p.push("color-stop(" + m + "%, " + W + ")") : d.push(W + " " + m + "%")
                }
                if (c.modern) {
                    var v, S, x = c.cssPrefix;
                    if ("" == x) v = "linear-", S = ("v" == u ? "to bottom, " : "to right, ") + d.join(","); else if ("webkit" == x && g) {
                        var y = p.join(",");
                        v = "-webkit-", S = "v" == u ? "linear, left top, left bottom, " + y : "linear, left top, right top, " + y
                    } else v = "-" + x + "-linear-", S = ("v" == u ? "top, " : "left, ") + d.join(",");
                    e = v + "gradient(" + S + ")"
                } else if (c.ie && c.version < 9) {
                    var w = "v" == u ? "0" : "1",
                        k = "progid:DXImageTransform.Microsoft.gradient( gradientType=" + w + ", startColorstr='" + a[0] + "', endColorstr='" + a[a.length - 3] + "')";
                    if (this.style.filter = k, this.style.msFilter = '"' + k + '"', this.O) {
                        var X = this.O;
                        X.filter = k, X.msFilter = '"' + k + '"'
                    }
                } else {
                    for (var F = "", b = 0; b < f; b += 3) {
                        var W = jbeeb.Utils.makeColor(a[b], a[b + 1]);
                        F += '<stop offset="' + a[b + 2] + '%" stop-color="' + a[b] + '" stop-opacity="' + a[b + 1] + '"/>'
                    }
                    var Y = "0", B = "100";
                    "h" == u && (Y = "100", B = "0");
                    var j = "jbeeb-grad-" + this.id, z = "";
                    z += '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 1 1" preserveAspectRatio="none">', z += '  <linearGradient id="' + j + '" gradientUnits="userSpaceOnUse" x1="0%" y1="0%" x2="' + Y + '%" y2="' + B + '%">', z += F, z += "  </linearGradient>", z += '  <rect x="0" y="0" width="1" height="1" fill="url(#' + j + ')" />', z += "</svg>", e = 'url("data:image/svg+xml;base64,' + jbeeb.Base64.encode(z) + '")'
                }
            } else this.O && (this.O.gradient = 0);
            if (t.backgroundColor = i || "", t.backgroundImage = e || "none", t.backgroundSize = s || "", t.backgroundRepeat = h || "", t.backgroundPosition = n || "", this.O) {
                var X = this.O;
                X.backgroundColor = i || "", X.backgroundImage = e || "none", X.backgroundSize = s || "", X.backgroundRepeat = h || "", X.backgroundPosition = n || ""
            }
        }
    }, e.setFill = function (t, e) {
        this.fill || (this.fill = {}), this.fill.color = t, this.fill.alpha = e, this.U()
    }, e.setImage = function (t, e) {
        t ? (this.image || (this.image = {}), this.image.url = t, this.image.mode = e) : this.image = null, this.U()
    }, e.setImageSizing = function (t) {
        this.image && (this.image.mode = t, this.U())
    }, e.setStroke = function (t, e, i, s) {
        this.stroke || (this.stroke = {}), "string" == typeof t && (e = t, t = 1), t > 0 && (t = Math.round(t));
        var h = i || 1, n = s || "solid";
        null == e && (t = null, h = null, n = null);
        var o = this.stroke;
        o.weight = t, o.color = e, o.alpha = h, o.style = n;
        var n, r, l, a, u, c = this.style;
        if (t ? (n = n, r = t + "px", l = jbeeb.Utils.makeColor(e, h), a = -t + "px", u = -t + "px") : (n = "", r = "", l = "", a = "", u = ""), c.borderStyle = n, c.borderWidth = r, c.borderColor = l, c.marginLeft = a, c.marginTop = u, this.O) {
            var d = this.O;
            d.borderStyle = n, d.borderWidth = r, d.borderColor = l, d.marginLeft = a, d.marginTop = u
        }
        this.T()
    }, e.setStrokeStyle = function (t) {
        var e = t || "";
        this.style.borderStyle = e, this.O && (this.O.borderStyle = e)
    }, e.setCursor = function (t) {
        this.style.cursor = t, this.O && (this.O.cursor = t)
    }, e.setWidth = function (t) {
        var e = this.style;
        e && t > 0 && (this.width = t, e.width = t + "px", this.autoCenter && this.center(this.autoCenter), this.rounded && this.T(), this.O && (this.O.width = t + "px"))
    }, e.setHeight = function (t) {
        var e = this.style;
        e && t > 0 && (this.height = t, e.height = t + "px", this.autoCenter && this.center(this.autoCenter), this.rounded && this.T(), this.O && (this.O.height = t + "px"))
    }, e.measure = function () {
        var t = this.element, e = t.clientWidth, i = t.clientHeight;
        return this.width = e, this.height = i, [e, i]
    }, e.setSize = function (t, e) {
        var i = this.style;
        i && t > 0 && e > 0 && (this.width = t, this.height = e, i.width = t + "px", i.height = e + "px", this.autoCenter && this.center(this.autoCenter), this.rounded && this.T(), this.O && (this.O.width = t + "px", this.O.height = e + "px"))
    }, e.setXY = function (t, e) {
        this.x = t, this.y = e;
        var i = this.style;
        i.left = t + "px", i.top = e + "px", this.O && (this.O.left = t + "px", this.O.top = e + "px")
    }, e.setBaseXY = function (t, e) {
        this.setXY(t, e), this.R = t, this.Q = e
    }, e.setXYWH = function (t, e, i, s) {
        this.width = i, this.height = s, this.x = t, this.y = e;
        var h = this.style;
        if (h.width = (i || 0) + "px", h.height = (s || 0) + "px", h.left = (t || 0) + "px", h.top = (e || 0) + "px", this.O) {
            var n = this.O;
            n.width = (i || 0) + "px", n.height = (s || 0) + "px", n.left = (t || 0) + "px", n.top = (e || 0) + "px"
        }
    }, e.setX = function (t) {
        this.x = t, this.style.left = (t || 0) + "px", this.O && (this.O.left = (t || 0) + "px")
    }, e.setY = function (t) {
        this.y = t, this.style.top = (t || 0) + "px", this.O && (this.O.top = (t || 0) + "px")
    }, e.setTop = function (t) {
        this.y = t, this.style.top = t + "px", this.O && (this.O.top = (t || 0) + "px")
    }, e.setBottom = function (t) {
        this.y = t - this.height, this.style.bottom = t + "px", this.O && (this.O.bottom = (t || 0) + "px")
    }, e.setLeft = function (t) {
        this.x = t, this.style.left = (t || 0) + "px", this.O && (this.O.left = (t || 0) + "px")
    }, e.setRight = function (t) {
        var e = (t || 0) - this.width;
        this.x = e, this.style.right = e + "px", this.O && (this.O.right = e + "px")
    }, e.setZ = function (t) {
        t < 0 && (t = 0), this.z = t;
        var e = this.style;
        e || (this.style = e = this.element.style), e.zIndex = t, this.O && (this.O.zIndex = t)
    }, e.setScale = function (t) {
        this.scale = t, this.scaleX = t, this.scaleY = t;
        var e = "scale(" + t + "," + t + ")";
        this.N(e)
    }, e.setScaleX = function (t) {
        this.scaleX = t;
        var e = "scale(" + this.scaleX + "," + t + ")";
        this.N(e)
    }, e.setScaleY = function (t) {
        this.scaleY = t;
        var e = "scale(" + t + "," + this.scaleY + ")";
        this.N(e)
    }, e.stretch = function (t, e) {
        if (this.stretchX = t, this.stretchY = e, t > 0 && e > 0) {
            this.L && this.setWidth(this.width * t), this.M && this.setHeight(this.height * e);
            var i = this.x, s = this.y;
            if (this.D) {
                if ("r" == this.D && this.parent) {
                    null == this.E && (this.E = this.parent.width - this.x);
                    var h = this.parent.width - this.E;
                    this.setX(h)
                }
            } else if (this.originX) {
                var n = this.originX;
                this.setX(n + (i - n) * t)
            } else this.setX(i * t);
            if (this.C) {
                if ("b" == this.C && this.parent) {
                    null == this.G && (this.G = this.parent.height - this.y);
                    var h = this.parent.height - this.G;
                    this.setY(h)
                }
            } else if (this.originY) {
                var n = this.originY;
                this.setY(n + (s - n) * e)
            } else this.setY(s * e)
        }
        this.dispatchEvent("stretch", this.width, this.height)
    }, e.E = null, e.G = null, e.setPin = function (t) {
        this.pin = t, this.D = 0, this.C = 0, t && (t = t.toLowerCase(), t.match(/r/) && (this.D = "r"), t.match(/l/) && (this.D = "l"), t.match(/t/) && (this.C = "t"), t.match(/b/) && (this.C = "b"), t.match(/s/) && (this.D = "s", this.C = "s"))
    }, e.setFlex = function (t) {
        this.L = 0, this.M = 0, t && (t.toLowerCase(), t.match(/w/) ? this.L = 1 : this.L = 0, t.match(/h/) ? this.M = 1 : this.M = 0), this.flex = t
    }, e.setRotation = function (t) {
        this.rotation = t;
        var e = "rotate(" + t + "deg)";
        this.N(e)
    }, e.setSkew = function (t, e) {
        this.skewX = t, this.skewY = e;
        var i = "skew(" + t + "deg," + e + "deg)";
        this.N(i)
    }, e.setOrigin = function (t, e, i) {
        this.originX = t, this.originY = e, this.originType = i;
        var s = i ? i : "px", h = t + s + " " + e + s, n = this.style;
        n.transformOrigin = n.WebkitTransformOrigin = n.msTransformOrigin = n.MozTransformOrigin = n.OTransformOrigin = h, this.O && (this.O.transformOrigin = h)
    }, e.N = function (t) {
        var e = this.style;
        e.transform = e.transform = e.msTransform = e.WebkitTransform = e.MozTransform = t, this.O && (this.O.transform = t)
    }, e.center = function (t, e) {
        if ((this.parent || this.amStage) && this.width && this.height) {
            var i, s, h, n = this.x, o = this.y;
            this.amStage ? (i = jbeeb.Utils.getXYWH(this.element.parentNode), s = .5 * i.w, h = .5 * i.h) : (i = this.parent, i.width || i.measure(), s = .5 * i.width, h = .5 * i.height);
            var r = .5 * this.width, l = .5 * this.height;
            "v" == t ? o = h - l : "h" == t ? n = s - r : (n = s - r, o = h - l), this.setXY(n, o), i = null
        }
    }, e.setOverflow = function (t) {
        this.overflow = t;
        var e = "", i = "";
        if ("x" != t && "y" != t && t || ("x" == t ? (e = "auto", i = "hidden") : "y" == t && (e = "hidden", i = "auto", jbeeb.Browser.ie && this.setWidth(this.width + 20)), this.style.overflowX = e, this.style.overflowY = i), this.style.overflow = t, this.O) {
            var s = this.O;
            s.overflow = t, s.overflowX = e, s.overflowY = i
        }
    }, e.setVisible = function (t) {
        this.visible = t;
        var e, i = this.style;
        e = t ? this.P : "none", i.display = e, this.O && (this.O.display = e)
    }, e.show = function () {
        this.setVisible(!0)
    }, e.hide = function () {
        this.setVisible(!1)
    }, e.setAlpha = function (t) {
        this.alpha = t, null !== t && (this.style.opacity = "" + t), this.O && (this.O.opacity = "" + t)
    }, e.setRounded = function (t) {
        this.rounded = t, this.T()
    }, e.T = function () {
        var t = "", e = this.rounded;
        if (e) {
            var i = this.width, s = this.height, h = 0, n = this.stroke;
            if (n) {
                var o = n.weight;
                jbeeb.Utils.isNumber(o) && (h = 2 * o)
            }
            var r = .5 * ((i < s ? i : s) + h);
            jbeeb.Utils.isNumber(e) ? t = r * e + "px" : e && "object" == typeof e && (t += (r * e.tl || 0) + "px " + (r * e.tr || 0) + "px " + (r * e.br || 0) + "px " + (r * e.bl || 0) + "px")
        }
        var l = this.style;
        l.borderRadius = l.MozBorderRadius = l.WebkitBorderRadius = l.OBorderRadius = l.msBorderRadius = t, this.O && (this.O.borderRadius = t)
    }, e.onAdded = function () {
        this.autoCenter && this.center(this.autoCenter), this.dispatchEvent("added", this)
    }, e.toFront = function () {
        this.parent && this.parent.toFront(this)
    }, e.toBack = function () {
        this.parent && this.parent.toBack(this)
    }, e.A = function () {
        var t = this.style, e = this.H(), i = this.K(), s = this.I(), h = this.J(), n = "none";
        if (e == [] && i == [] && s == [] && h == []); else {
            for (var o = i.concat(s, h, e), r = o.length, l = [], a = [], u = 0,
                     c = 0; c < r; c++)0 == u ? 1 == o[c] && a.push("inset") : u < 4 ? a.push(o[c] + "px") : (a.push(jbeeb.Utils.makeColor(o[c], o[c + 1])), l.push(a.join(" ")), a = [], ++c, u = -1), u++;
            l.length > 0 && (n = l.join(","))
        }
        t.boxShadow = t.MozBoxShadow = t.WebkitBoxShadow = t.OBoxShadow = t.msBoxShadow = n, this.O && (this.O.boxShadow = n)
    },e.H = function () {
        var t = this.shadow;
        return t ? [0, t.x || 0, t.y || 0, t.s, t.c || "#000000", t.a || .4] : []
    },e.setShadow = function (t) {
        this.shadow = t, this.A()
    },e.J = function () {
        var t = this.inset;
        return t ? [1, t.x || 0, t.y || 0, t.s, t.c || "#000000", t.a || .4] : []
    },e.setInset = function (t) {
        this.inset = t, this.A()
    },e.K = function () {
        var t = this.bevel;
        return t ? [1, -t.x, -t.y, t.s1, t.c1 || "#FFFFFF", t.a1, 1, t.x, t.y, t.s2, t.c2 || "#000000", t.a2] : []
    },e.setBevel = function (t) {
        t && (jbeeb.Utils.isNumber(t) ? t = {
            x: -t,
            y: -t,
            s1: 0,
            s2: 0,
            c1: "#FFFFFF",
            c2: "#000000",
            a1: 1,
            a2: 1
        } : (t.c1 = t.c1 || "#FFFFFF", t.c2 = t.c2 || "#000000")), this.bevel = t, this.A()
    },e.I = function () {
        if (this.outline) {
            var t = this.outline;
            return [0, -t.weight, -t.weight, t.spread || 0, t.color || "#000000", t.alpha || 1, 0, t.weight, -t.weight, t.spread || 0, t.color || "#000000", t.alpha || 1, 0, -t.weight, t.weight, t.spread || 0, t.color || "#000000", t.alpha || 1, 0, t.weight, t.weight, t.spread || 0, t.color || "#000000", t.alpha || 1]
        }
        return []
    },e.setOutline = function (t) {
        this.outline = t, this.A()
    },e.setMouseEnabled = function (t) {
        var e = this.style, i = 0 === t || t === !1 ? "none" : "auto";
        e.pointerEvents = i, this.O && (this.O.pointerEvents = i)
    },e.V = null,e.MELbubble = !1,e.addMEL = function (t, e, i, s, h) {
        this.MELbubble = s, this.V || (this.V = new jbeeb.MouseEventListener(this)), "mouseOver" != t && "mouseOut" != t && "mouseMove" != t || this.V.enableMouseOver(1), this.addEventListener(t, e, i, h)
    },e.removeMEL = function (t, e) {
        this.removeEventListener(t, e), "mouseOver" == t && this.V.enableMouseOver(0)
    },e.setFloat = function (t) {
        this.style.position = "relative", this.style.left = "", this.style.top = "", this.style.cssFloat = t, this.style.display = "inline-block", this.O && (this.O.position = "relative", this.O.left = null, this.O.top = null, this.O.cssFloat = t, this.O.display = "inline-block")
    },e.destroy = function () {
        this.removeAllEventListeners(), this.V && (this.V.destroy(), this.V = null), this.element && this.element.parentNode && (this.element.parentNode.removeChild(this.element), this.element = null), this.parent && (this.parent.removeChild(this), this.parent = null), this.temp = null, this.stroke = null, this.fill = null, this.gradient = null, this.bevel = null, this.outline = null, this.shadow = null, this.inset = null, this.image = null, this.element = null, this.O = null
    },e.getCSS = function () {
        return this.O
    },e.toString = function () {
        return "[Box (name=" + this.name + ")]"
    },e.type = "Box",jbeeb.Box = t
}();
;this.jbeeb = this.jbeeb || {}, function () {
    "use strict";
    var t = function (t) {
        this.init(t)
    }, e = t.prototype = new jbeeb.Box(null);
    e.textFit = null, e.text = "", e.ab = "", e.textSize = null, e.textColor = null, e.shadowText = null, e.bevelText = null, e.outlineText = null, e.insetText = null, e.font = null, e.align = null, e.textScale = null, e.selectable = null, e.bold = null, e.padding = null, e.editable = null, e.ak = null, e.multiline = null, e.baselineShift = null, e.ar = null, e.ag = null, e.aa = !1, e.ap = e.init, e.init = function (t) {
        if (t) {
            if (t.editable) {
                var e;
                e = t.multiline ? document.createElement("textarea") : document.createElement("input"), this.ak = 1, e.id = jbeeb.getUID(), e.style.position = "absolute", e.style.overflow = "visible", this.aj && (this.aj.position = "absolute", this.aj.overflow = "visible"), t.multiline || (e.type = "text"), t.element = e
            }
            this.ap(t), t.element = null;
            var i = this.style;
            if (i.textDecoration = "none", i.zoom = 1, i.size = t.h, this.text = t.text || "", this.aj) {
                var s = this.aj;
                s.fontSmooth = "always", s.WebkitFontSmoothing = "antialiased", s.textDecoration = "none", s.zoom = 1, s.size = t.h
            }
            this.applySkin(t, !0)
        }
    }, e.aq = e.applySkin, e.applySkin = function (t, e) {
        if (this.aa = !0, t.editable) {
            var i = null;
            t.fill && (i = "object" == typeof t.fill ? t.fill.color : t.fill), t.stroke = t.stroke || i || {
                    weight: 1,
                    color: "#000000",
                    alpha: 1
                }
        }
        this.aq(t, e);
        this.style;
        if (this.textFit = t.textFit || null, this.font = t.font || "Arial, Helvetica, sans-serif", this.align = t.align || "left", this.textScale = t.textScale || 1, this.bold = t.bold || 0, this.selectable = t.selectable || 0, this.editable = t.editable || 0, this.multiline = t.multiline || 0, this.baselineShift = t.baselineShift || 0, e || (this.text = t.text || ""), this.ab = "", this.textColor = {}, t.textSize && (this.textSize = t.textSize), 1 == t.editable && this.setEditable(1), this.setMultiline(this.multiline, !0), this.setText(this.text), t.textColor) {
            var s = t.textColor, h = {};
            "string" == typeof s ? h = {
                color: s,
                alpha: 1
            } : (h = s, h.color || (h.color = null, h.alpha = null)), this.setTextColor(h.color || "#000000", h.alpha || 1)
        }
        t.shadowText && (this.shadowText = t.shadowText), t.insetText && (this.insetText = t.insetText), t.bevelText && (this.bevelText = t.bevelText), t.outlineText && (this.outlineText = t.outlineText), t.shadow && (this.shadow = t.shadow), t.inset && (this.insetText = t.inset), t.bevel && (this.bevel = t.bevel), t.outline && (this.outline = t.outline), t.padding && this.setPadding(t.padding), t.alphaNumeric && (this.alphaNumeric = 1), t.numeric && (this.numeric = 1), this.setBaselineShift(this.baselineShift), this.aa = !1, this.Z(), this.X()
    }, e.setMultiline = function (t, e) {
        this.multiline = t;
        var i, s = this.style;
        t ? (this.textSize || (this.textSize = 12), i = "normal") : i = "nowrap", s.whiteSpace = i, this.aj && (this.aj.whiteSpace = i), this.ai()
    }, e.ak = 0, e.setEditable = function (t) {
        1 === t ? (this.amSM || this.setCursor("text"), this.ag ? this.ag.removeAllEventListeners() : this.ag = new jbeeb.Keyboard(this.element), this.ag.addEventListener("keydown", this.keyHandler, this), this.ag.addEventListener("keyup", this.keyHandler, this), this.setOverflow("hidden"), jbeeb.Utils.bindEvent(this.element, "focus", this.setFocus.bind(this)), jbeeb.Utils.bindEvent(this.element, "blur", this.Y.bind(this)), this.addMEL("mouseUp", this.setFocus, this)) : (this.amSM || this.setCursor("default"), this.ag && this.ag.removeAllEventListeners(), jbeeb.Utils.unbindEvent(this.element, "focus", this.setFocus.bind(this))), this.editable = t
    }, e.numeric = null, e.alphaNumeric = null, e.keyHandler = function (t, e, i) {
        var s = !0;
        this.alphaNumeric ? s = this.ag.alphaNumeric(e) : this.numeric && (s = this.ag.numeric(e)), 0 == this.multiline && (108 != e && 13 != e || (s = !1, "keyup" == i && this.dispatchEvent("enter", this, this.text))), 9 == e && (s = !1, "keyup" == i && this.dispatchEvent("tab", this, this.text)), s ? (this.ak && !this.multiline ? this.text = this.element.value : this.text = this._.text, "keyup" == i && this.dispatchEvent("change", this, this.text)) : this.ag.block(t)
    }, e.Y = function () {
        this.dispatchEvent("change", this, this.text)
    }, e.setPadding = function (t) {
        this.padding = t;
        var e;
        e = this._ ? this._.style : this.style;
        var i = "", s = "", h = "", n = "";
        if (this.multiline ? (i = t + "px", s = t + "px", h = t + "px", n = t + "px") : "left" == this.align ? t && (i = t + "px") : "right" == this.align && t && (s = t + "px"), e.paddingLeft = i, e.paddingRight = s, e.paddingTop = h, e.paddingBottom = n, this.aj) {
            var l = this.aj;
            l.paddingLeft = i, l.paddingRight = s, l.paddingTop = h, l.paddingBottom = n
        }
    }, e.ah = function () {
        var t = this.font, e = this.textColor || {}, i = jbeeb.Utils.makeColor(e.color, e.alpha),
            s = this.bold ? "bold" : "normal", h = this.style;
        if (h.fontFamily = t, h.color = i, h.textAlign = this.align, h.fontWeight = s, this.aj) {
            var n = this.aj;
            n.fontFamily = t, n.color = i, n.textAlign = this.align, n.fontWeight = s
        }
    }, e.setFont = function (t) {
        this.font = t, this.style.fontFamily = t, this._ && (this._.style.fontFamily = this.font), this.aj && (this.aj.fontFamily = t), this.Z()
    }, e.setAlign = function (t) {
        this.align = t, this.style.textAlign = t, "center" == t && this.setPadding(0), this.aj && (this.aj.textAlign = t)
    }, e.setBold = function (t) {
        this.bold = t ? "bold" : "", this.style.fontWeight = this.bold, this.aj && (this.aj.fontWeight = this.bold), this.Z()
    }, e.setBaselineShift = function (t) {
        this.baselineShift = t, t ? t > 1 ? t = 1 : t < -1 && (t = -1) : t = 0, t *= -1, this.ar = 1 + t, this.Z()
    }, e.measureText = function (t) {
        if (this.text || t) {
            var e = document.createElement("div");
            document.body.appendChild(e);
            var i = e.style;
            i.fontSize = this.height * this.textScale + "px", i.fontFamily = this.font, i.fontWeight = this.bold ? "bold" : "normal", i.position = "absolute", i.left = -1e3, i.top = -1e3, e.innerHTML = t || this.text;
            var s = e.clientWidth, h = e.clientHeight, n = {w: s, h: h};
            return document.body.removeChild(e), e = null, n
        }
        return 0
    }, e.setTextColor = function (t, e) {
        this.textColor || (this.textColor = {}), this.textColor.color = t, this.textColor.alpha = e;
        var i = jbeeb.Utils.makeColor(t, e);
        this.style.color = i, this.aj && (this.aj.color = i)
    }, e.setText = function (t) {
        if (this.element) {
            if (t = "" != t && t ? String(t) : "", this.text = t, this.ak && !this.multiline) this.element.value = t; else {
                if (!this._) {
                    var e = document.createElement("span");
                    e.style.fontFamily = this.font, this.element.appendChild(e), this._ = e
                }
                this._.innerHTML = t
            }
            this.ab != t && this.Z(), this.ab = t
        }
    }, e.selectAll = function () {
        this.ak && (jbeeb.focus = this, this.element.focus(), this.element.select())
    }, e.al = e.setWidth, e.setWidth = function (t) {
        t != this.width && (this.al(t), this.ai())
    }, e.an = e.setHeight, e.setHeight = function (t) {
        t != this.height && (this.an(t), this.ai())
    }, e.am = e.setSize, e.setSize = function (t, e) {
        t == this.width && e == this.height || (this.am(t, e), this.ai())
    }, e.setTextScale = function (t) {
        this.textScale = t || 1, this.ai()
    }, e.setTextSize = function (t) {
        this.textSize = t, this.ai()
    }, e.setTextFit = function (t) {
        this.textFit = t, this.ai()
    }, e.ao = e.onAdded, e.onAdded = function () {
        this.ao(), this.Z()
    }, e.setFocus = function (t) {
        jbeeb.focus = this, this.element.focus()
    }, e.ai = function () {
        if ("" != this.text) {
            var t = null, e = null, i = null;
            if (this.textSize) t = this.textSize, e = "1em", i = t + "px"; else {
                var s = this.width, h = this.height;
                if (s > 0 && h > 0)if ("wh" == this.textFit) {
                    var n = s < h ? s : h;
                    t = this.textScale > 0 ? n * this.textScale : n
                } else if ("w" == this.textFit) {
                    var l = this.measureText(), o = this.width / l.w / 2;
                    jbeeb.Utils.isNumber(o) && o > 0 && (this.textScale = o, t = h * o)
                } else t = h * this.textScale; else t = 0
            }
            t && (e = this.height * this.ar / t + "em", i = t + "px");
            var a = this.style;
            a.lineHeight = e, a.fontSize = i, this.aj && (this.aj.lineHeight = e, this.aj.fontSize = i)
        }
    }, e.getTextSize = function () {
        return this.style.fontSize || null
    }, e.Z = function () {
        this.aa || (this.ai(), this.ah())
    }, e.X = function () {
        var t = this.ac(), e = this.af(), i = this.ad(), s = this.ae(), h = "none";
        if (t == [] && e == [] && i == [] && s == []); else {
            for (var n = e.concat(i, t, s), l = n.length, o = [], a = [], d = 0,
                     r = 0; r < l; r++)0 == d ? 1 == n[r] && a.push("inset") : d < 4 ? a.push(n[r] + "px") : (a.push(jbeeb.Utils.makeColor(n[r], n[r + 1])), o.push(a.join(" ")), a = [], ++r, d = -1), d++;
            o.length > 0 && (h = o.join(","))
        }
        var u = this.style;
        u.textShadow = u.MozTextShadow = u.WebkitTextShadow = u.OTextShadow = u.msTextShadow = h, this.aj && (this.aj.textShadow = h)
    }, e.ac = function () {
        var t = this.shadowText;
        return t ? [0, t.x, t.y, t.s, t.c, t.a] : []
    }, e.setShadowText = function (t) {
        this.shadowText = t, this.X()
    }, e.ae = function () {
        var t = this.insetText;
        return t ? [1, t.x, t.y, t.s, t.c, t.a] : []
    }, e.setInsetText = function (t) {
        this.insetText = t, this.X()
    }, e.af = function () {
        if (this.bevelText) {
            var t = this.bevelText, e = [];
            return t.c1 && t.a1 > 0 && (e = [0, -t.x, -t.y, t.s1, t.c1 || "#000000", t.a1]), t.c2 && t.a2 > 0 && (e = e.concat([0, t.x, t.y, t.s2, t.c2 || "#FFFFFF", t.a2])), e
        }
        return []
    }, e.setBevelText = function (t) {
        this.bevelText = t, this.X()
    }, e.ad = function () {
        if (this.outlineText) {
            var t = this.outlineText;
            return [0, -t.weight, -t.weight, t.spread || 0, t.color || "#000000", t.alpha, 0, t.weight, -t.weight, t.spread || 0, t.color || "#000000", t.alpha, 0, -t.weight, t.weight, t.spread || 0, t.color || "#000000", t.alpha, 0, t.weight, t.weight, t.spread || 0, t.color || "#000000", t.alpha]
        }
        return []
    }, e.setOutlineText = function (t) {
        this.outlineText = t, this.X()
    }, e.toString = function () {
        return "[TextBox (name=" + this.name + ")]"
    }, e.type = "TextBox", jbeeb.TextBox = t
}();
;this.jbeeb = this.jbeeb || {}, function () {
    "use strict";
    var t = function (t) {
        this.init(t)
    }, e = t.prototype = new jbeeb.Box(null);
    e.at = [], e.addChild = function (t) {
        if (null == t)return t;
        var e = arguments.length;
        if (e > 0)for (var i = 0; i < e; i++) {
            var n = arguments[i];
            n.parent && n.parent.removeChild(n), n.parent = this, n.stage = 1 == this.amStage ? this : this.stage, n.setZ(this.at.length), this.element.appendChild(n.element), n.onAdded && n.onAdded.call(n), this.at.push(n)
        }
    }, e.removeChild = function (t) {
        var e = arguments.length;
        if (e > 1) {
            for (var i = !0, n = e; n--;)i = i && this.removeChild(arguments[n]);
            return i
        }
        return this.removeChildAt(this.at.indexOf(t))
    }, e.removeChildAt = function (t) {
        var e = arguments.length;
        if (e > 1) {
            for (var i = [], n = 0; n < e; n++)i[n] = arguments[n];
            i.sort(function (t, e) {
                return e - t
            });
            for (var r = !0, n = 0; n < e; n++)r = r && this.removeChildAt(i[n]);
            return r
        }
        if (t < 0 || t > this.at.length - 1)return !1;
        var h = this.at[t];
        return h && (h.element && h.element.parentNode && h.element.parentNode.removeChild(h.element), h.parent = null), this.at.splice(t, 1), this.as(), !0
    }, e.removeAllChildren = function () {
        for (var t = this.at; t.length;)this.removeChildAt(0)
    }, e.as = function () {
        for (var t = this.at.length, e = 0; e < t; e++) {
            var i = this.at[e];
            i && i.setZ(e + 1)
        }
    }, e.toFront = function (t) {
        if (t) {
            for (var e = this.at.length, i = 0, n = e; n--;)if (this.at[n] == t) {
                i = n;
                break
            }
            jbeeb.Utils.arrayMove(this.at, i, e - 1), this.as()
        } else this.parent && this.parent.toFront(this)
    }, e.toBack = function (t) {
        if (t) {
            for (var e = this.at.length, i = 0, n = e; n--;)if (this.at[n] == t) {
                i = n;
                break
            }
            jbeeb.Utils.arrayMove(this.at, i, 0), this.as()
        } else this.parent && this.parent.toBack(this)
    }, e.aw = e.init, e.init = function (t) {
        this.aw(t), t && (this.stage = 1 == this.amStage ? this : this.stage, this.at = [])
    }, e.au = e.stretch, e.stretch = function (t, e) {
        var i = t, n = e, r = this.flex;
        r && (r.match(/w/) || (i = 1), r.match(/h/) || (n = 1));
        for (var h = this.at.length; h--;) {
            var l = this.at[h];
            l && l.stretch(i, n)
        }
        this.au(t, e)
    }, e.av = e.setFlex, e.setFlex = function (t) {
        for (var e = this.at.length; e--;)this.at[e].setFlex(t);
        this.av(t)
    }, e.ax = e.destroy, e.destroy = function () {
        if (this.at)for (var t = this.at.length; t--;)this.at[t] && (this.at[t].destroy(), this.removeChild(this.at[t]), this.at[t] = null);
        this.at = null, this.ax()
    }, e.destroyChildren = function () {
        if (this.at)for (var t = this.at.length; t--;)this.at[t] && (this.at[t].destroy(), this.removeChild(this.at[t]), this.at[t] = null);
        this.at.length = 0, this.at = null, this.at = []
    }, e.getChildren = function () {
        return this.at
    }, e.toString = function () {
        return "[Container (name=" + this.name + ")]"
    }, e.type = "Container", jbeeb.Container = t
}();
;this.jbeeb = this.jbeeb || {}, function () {
    "use strict";
    var t = function (t) {
        return this.aA(t), this
    }, e = t.prototype = new jbeeb.Container;
    e.amReady = null, e.ay = null, e.aA = function (t) {
        if (t)if (this.amReady = 0, t.onReady && (this.ay = [], this.ay.push(t.onReady)), this.id = jbeeb.getUID(), t.stage) this.amStage = 0, this.aB(t), jbeeb.register(this); else {
            this.amStage = 1, this.parent = this, this.stage = this;
            var e = t.target, i = null, s = 0;
            e && (i = "string" == typeof e ? document.getElementById(e) : e, i && 1 === i.nodeType ? (this.element = document.createElement("div"), this.element.id = this.id, i.appendChild(this.element)) : s = 1), e && !s || (document.write('<div id="' + this.id + '"></div>'), this.element = document.getElementById(this.id)), t.element = this.element, this.aB(t), this.style = this.element.style, this.style.position = "relative", this.style.display = t.inline === !0 || "true" == t.inline || 1 === t.inline ? "inline-block" : "block", this.style.verticalAlign = "top", this.style.clear = "both", this.style.zoom = 1;
            var h = this.width || t.w || 1, n = this.height || t.h || 1;
            this.setSize(h, n), this.setOverflow(t.overflow || "visible"), this.setCursor("default"), jbeeb.register(this)
        }
    }, e.aB = e.init, e.init = function () {
        var t = jbeeb.Utils.getXYWH(this.element);
        this.x = t.x, this.y = t.y, this.width = t.width, this.height = t.height;
        setTimeout(this.az.bind(this), 50)
    }, e.az = function () {
        if (this.amReady = 1, this.ay)for (var t = 0; t < this.ay.length; t++)this.ay.pop()()
    }, e.onReady = function (t) {
        this.amReady ? t() : (this.ay || (this.ay = []), this.ay.push(t))
    }, e.toString = function () {
        return "[Stage (name=" + this.name + ")]"
    }, e.type = "Stage", jbeeb.Stage = t
}();
;this.jbeeb = this.jbeeb || {}, function () {
    var e = function (t) {
        return t = t || {}, this.aI = t.onComplete, this.aC = t.timezone, this.aT = t.digits || 2, this.aD = t.truncate || 0, e.aL[t.rangeHi] ? this.aH = e.aL[t.rangeHi] : this.aH = e.aJ, e.aL[t.rangeLo] ? this.aG = e.aL[t.rangeLo] : this.aG = e.aK, t.end && this.aF(t.end), this
    };
    e.aW = 6e4, e.aX = 36e5, e.aY = 864e5, e.aM = 0, e.aK = 1, e.aO = 2, e.aP = 3, e.aQ = 4, e.aN = 5, e.aJ = 6, e.aL = {
        ms: e.aM,
        second: e.aK,
        minute: e.aO,
        hour: e.aP,
        day: e.aQ,
        month: e.aN,
        year: e.aJ
    };
    var t = e.prototype;
    t.aS = !1, t.aR = !1, t.aI = null, t.aE = null, t.aC = 0, t.aT = 0, t.aH = e.aJ, t.aG = e.aM, t.aD = 0, t.aF = function (t, n) {
        var a, i = new Date, r = 0;
        if (t instanceof Date) a = new Date(t.getTime()); else if ("object" == typeof t) {
            var aZ = t.year ? parseInt(t.year) : i.getFullYear(), s = t.month ? parseInt(t.month) - 1 : 0,
                o = t.day ? parseInt(t.day) : 0, g = t.hour ? parseInt(t.hour) : 0,
                h = t.minute ? parseInt(t.minute) : 0, u = t.second ? parseInt(t.second) : 0,
                d = (t.ampm ? t.ampm : "am").toLowerCase();
            g < 12 && /p/.test(d) && (g += 12), a = new Date(Date.UTC(aZ, s, o, g, h, u))
        } else r = 1, a = new Date(i.getTime() + 1e3 * (parseInt(t) + 1));
        if (!r && !n) {
            var c = 0, f = 0;
            if (c = -(new Date).getTimezoneOffset() * e.aW, "undefined" != typeof this.aC) {
                var k = this.aC, m = parseInt(k);
                f = k == m ? k * e.aX : c
            } else f = c;
            var T = Math.abs(f - c);
            c < f && (T *= -1);
            var M = a.getTime() + T - c;
            a = new Date(M)
        }
        this.aE = a, this.aS = !1, this.aR = !1
    }, t.update = function () {
        return this.aV(new Date)
    }, t.diff = function (e, t) {
        return t && this.aF(t, !0), this.aV(e)
    }, t.aV = function (t) {
        var n = 0, a = 0, i = 0, r = 0, aZ = 0, s = 0, o = 0, g = this.aE, h = g.getTime() - t.getTime(),
            u = Math.floor, d = !1;
        if (h > 0) {
            var c = 3600, f = e.aX, k = (e.aY, this.aG), m = this.aH;
            this.aD && (k = -1, m = 10);
            var T = e.aM, M = e.aK, l = e.aO, p = e.aP, v = e.aQ, y = e.aN, D = (e.aJ, h / 1e3), C = D / 60, H = C / 60,
                U = H / 24;
            k < v && (m >= T && (n = u(m == T ? h : h % 1e3)), m >= M && (a = u(m == M ? D : D % 60)), m >= l && (i = u(m == l ? C : C % 60)), m >= p && (r = u(m == p ? H : H % 24)));
            var S = t.getUTCFullYear(), I = t.getUTCMonth(), R = t.getUTCDate(), Y = g.getUTCFullYear(),
                L = g.getUTCMonth(), b = g.getUTCDate(), E = R, w = b, F = 0;
            if (m >= v)if (m == v) aZ = u(U); else {
                var j = t.getUTCHours(), O = t.getUTCMinutes(), Z = t.getUTCSeconds(), A = g.getUTCHours(),
                    z = g.getUTCMinutes(), N = g.getUTCSeconds(), P = L == I ? 0 : -1, q = L + P;
                q < 0 && (q += 12);
                var x = e.getMonthDays(q, Y);
                x = x < R ? e.getMonthDays(q - 1, Y) : x, x = x < b ? b : x;
                var B = 0;
                b > R ? B = b - R - 1 : b < R && (B = R - b - 1);
                var G = e.aY - 1e3 * (Z + 60 * O + j * c), J = 1e3 * (N + 60 * z + A * c);
                F = (G + J) / f, F < 24 && R++, R += B;
                var K = (x - R + b + B) % x;
                aZ = u(K)
            }
            if (m >= y) {
                var Q = 0, V = 12 * (Y - S);
                if (V < 0 || S == Y && I == L) V = 0; else {
                    I++, L++;
                    var B = 0;
                    L == I ? E <= w && B-- : L > I ? B = L - I - 1 : L < I && (B = 12 - I + L, Q--), F < 24 && E++, I >= L && E > w ? B-- : L >= I && E <= w && B++, V += B, V < 0 && (V = 0), V > 11 && (Q += u(V / 12), V %= 12), m == y && (V += 12 * Q, Q = 0)
                }
                s = V, o = Q
            }
        } else d = !0;
        var W = {ms: n, second: a, minute: i, hour: r, day: aZ, month: s, year: o};
        return e.pad(W, this.aT), d && !this.aR && this.aI && (this.aS = !0, this.aR = !0, this.aI(this.aE)), W
    }, e.aU = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31], e.getMonthDays = function (t, n) {
        return 1 == t ? n % 400 == 0 || n % 4 == 0 && n % 100 != 0 ? 29 : 28 : e.aU[t]
    }, e.pad = function (e, t) {
        if (t)for (var n in e) {
            for (var a = String(e[n]), i = "ms" == n ? 3 : t; a.length < i;)a = "0" + a;
            e[n] = a
        }
    }, Object.defineProperty(t, "rangeHi", {
        get: function () {
            return this.aH
        }, set: function (t) {
            e.aL[t] ? this.aH = e.aL[t] : this.aH = e.aJ
        }
    }), Object.defineProperty(t, "rangeLo", {
        get: function () {
            return this.aG
        }, set: function (t) {
            e.aL[t] ? this.aG = e.aL[t] : this.aG = e.aK
        }
    }), jbeeb.TimeDiff = e
}();
;!function () {
    var t, e = function (t) {
        this.imageFolder = CountdownImageFolder, this.imageBasename = CountdownImageBasename, this.imageExt = CountdownImageExt, this.imagePhysicalWidth = CountdownImagePhysicalWidth, this.imagePhysicalHeight = CountdownImagePhysicalHeight, this.totalFlipDigits = 2, this.bk = t || {};
        var e, i, n, s;
        if (t.bkgd) {
            var l = t.bkgd;
            l.color && (e = l.color), l.stroke && l.strokeColor && (i = {
                weight: l.stroke || 1,
                color: l.strokeColor,
                alpha: l.strokeAlpha
            }), l.shadow && (n = l.shadow), l.rounded && (s = l.rounded)
        }
        this.be = new jbeeb.Stage({
            target: t.target,
            inline: t.inline || !1,
            w: t.w || t.width || CountdownWidth,
            h: t.h || t.height || CountdownHeight,
            rounded: s || null,
            fill: e || null,
            stroke: i || null,
            shadow: n || null
        }), jbeeb.register(this)
    }, i = {}, n = e.prototype;
    n.bk = null, n.be = null, n.bB = !1, n.bm = null, n.id = null, n.bs = !1, n.bc = null, n.totalFlipDigits = null, n.imageFolder = null, n.imageBasename = "flipper", n.imageExt = "png", n.bG = null, n.bd = null, n.bn = "second", n.by = !1, n.bo = null, n.bx = !1, n.bE = 0, n.bD = 0, n.bH = 0, n.bA = 0, n.bl = [], n.bj = {}, n.bq = 0, n.bp = 0, n.ba = null, n.init = function () {
        this.id = jbeeb.getUID();
        var e = this.bk;
        this.bB = !1, this.bs = !1, this.bc = e.style || "boring", this.width = e.w || e.width || CountdownWidth, this.height = e.h || e.height || CountdownHeight, this.bm = e.onComplete, this.by = e.hideLabels, this.bx = e.hideLine, this.bf = e.fadeInMS || CountdownFadeInMS, this.bo = e.labelText || CountdownLabels, this.bq = e.interval || CountdownInterval, this.bp = 0, this.ba = {
            year: 0,
            month: 0,
            day: 0,
            hour: 0,
            minute: 0,
            second: 0,
            ms: 0
        };
        var n = "";
        if ("flip" == this.bc) {
            var s = "";
            "/" != this.imageFolder.substr(1) && "http" != this.imageFolder.substr(4) && (s = jbeeb.scriptPath, "" != s && "http" == s.substr(4) && "/" != s.substr(-1) && (s += "/")), "/" != this.imageFolder.substr(-1) && (this.imageFolder += "/"), n = s + this.imageFolder + this.imageBasename
        }
        this.bd = {
            ms: {use: !1, prev: [null, null], ani: [null, null], aniCount: [null, null]},
            second: {use: !1, prev: [null, null], ani: [null, null], aniCount: [null, null]},
            minute: {use: !1, prev: [null, null], ani: [null, null], aniCount: [null, null]},
            hour: {use: !1, prev: [null, null], ani: [null, null], aniCount: [null, null]},
            day: {use: !1, prev: [null, null], ani: [null, null], aniCount: [null, null]},
            month: {use: !1, prev: [null, null], ani: [null, null], aniCount: [null, null]},
            year: {use: !1, prev: [null, null], ani: [null, null], aniCount: [null, null]}
        };
        var l = ["ms", "second", "minute", "hour", "day", "month", "year"], a = e.rangeLo ? e.rangeLo : "second",
            o = e.rangeHi ? e.rangeHi : "year";
        a = "ms" != a && "s" == a.substr(-1) ? a.substr(0, a.length - 1) : a, o = "ms" != o && "s" == o.substr(-1) ? o.substr(0, o.length - 1) : o;
        for (var h = a, r = o, u = 0; u < l.length; u++) {
            var d = l[u];
            d == a && (a = u), d == o && (o = u)
        }
        for (var u = 0; u < l.length; u++)if (u >= a && u <= o) {
            var m = l[u];
            this.bd[m].use = !0, this.bn = m
        }
        var bI;
        bI = 0 === e.padding ? 0 : e.padding ? e.padding : "flip" == this.bc ? 0 : .8, "flip" == this.bc && (bI /= 2);
        var c = o - a + 1, g = this.height, p = this.width / c, b = this.by ? 0 : .25 * p, f = .1 * p, y = p - f,
            v = g - b, C = y * bI;
        "flip" == this.bc && (C = y * (bI / this.totalFlipDigits));
        var w = y - C, x = this.height - 2 * b;
        this.bD = y / this.totalFlipDigits, this.bH = f;
        var k = 0;
        "flip" == this.bc && (x = this.height - b, k = .03 * g), this.bE = y, this.bD = w * this.totalFlipDigits, this.bH = f, this.bA = C / 2 / this.totalFlipDigits / 2;
        var T = {
            numbers: {
                font: "Arial, _sans",
                color: "#FFFFFF",
                weight: "normal",
                bkgd: "flip" == this.bc ? null : {
                    color: ["#000000", 1, 0, "#686868", 1, 50, "#000000", 1, 50, "#535050", 1, 100],
                    alpha: "v"
                },
                rounded: "flip" == this.bc ? null : .18,
                shadow: null
            }, labels: {font: "Arial, _sans", color: "#303030", weight: "bold", textScale: 1, offset: 0}
        };
        if (e.numbers)for (var L in T.numbers)e.numbers[L] && (T.numbers[L] = e.numbers[L]);
        if (e.labels)for (var L in T.labels)e.labels[L] && (T.labels[L] = e.labels[L]);
        l.reverse(), this.bG = {}, this.bl = [];
        for (var S = 0, R = [], u = 0; u < l.length; u++) {
            var F = l[u];
            if (this.bd[F].use) {
                this.bG[F] = new jbeeb.Container({
                    x: S + f / 2,
                    y: 0,
                    w: y,
                    h: v,
                    rounded: T.numbers.rounded || null,
                    fill: jbeeb.Utils.clone(T.numbers.bkgd) || null,
                    shadow: T.numbers.shadow || null
                });
                var D = this.bG[F];
                if (D.store = {name: F}, this.bj[F] = y, "flip" == this.bc) {
                    var W = (w - 2 * k - 2 * C) / this.totalFlipDigits,
                        B = this.imagePhysicalWidth * (W / this.imagePhysicalWidth),
                        j = this.imagePhysicalHeight * (x / this.imagePhysicalHeight);
                    D.time = new jbeeb.Container({x: 0, y: 0, w: B * this.totalFlipDigits, h: j});
                    for (var A = [], P = 0; P < this.totalFlipDigits; P++) {
                        for (var I = new jbeeb.Container({x: B * P + k * P, y: 0, w: B, h: j}), O = [],
                                 z = 0; z < 10; z++) {
                            for (var H = new jbeeb.Container({x: 0, y: 0, w: B, h: j}), E = [], N = 0; N < 3; N++) {
                                var M = "" + z + N, U = n + M + "." + this.imageExt;
                                i[U] || (R.push(U), i[U] = 1);
                                var X = new jbeeb.Box({x: 0, y: 0, w: B, h: j, image: {url: U, mode: "fit"}});
                                E[N] = X, H.addChild(X)
                            }
                            H.img = E, O[z] = H, I.addChild(H)
                        }
                        I.num = O, A[P] = I, D.time.addChild(I)
                    }
                    D.time.slot = A, D.addChild(D.time)
                } else if (D.time = new jbeeb.TextBox({
                        x: 0,
                        y: 0,
                        w: y,
                        h: v,
                        text: "00",
                        textScale: bI,
                        font: T.numbers.font,
                        textColor: T.numbers.color,
                        align: "center"
                    }), D.addChild(D.time), !this.bx) {
                    var Y = .03 * g;
                    D.line = new jbeeb.Box({
                        x: 0,
                        y: 0,
                        w: y,
                        h: Y,
                        fill: "#000000"
                    }), D.addChild(D.line), D.line.center()
                }
                if (this.be.addChild(D), !this.by) {
                    var q = this.bo[F], G = g - .7 * b + T.labels.offset;
                    D.labels = new jbeeb.TextBox({
                        x: S,
                        y: G,
                        w: p,
                        h: .7 * b,
                        font: T.labels.font,
                        textScale: T.labels.textScale,
                        textColor: T.labels.color,
                        bold: 1,
                        align: "center",
                        text: q
                    }), this.be.addChild(D.labels)
                }
                this.bl.push(D), D.time.center(), e.numberMarginTop && D.time.setY(e.numberMarginTop), S += p
            }
        }
        var D = this.bG;
        "flip" == this.bc ? (D.year && this.bz("year", "00"), D.month && this.bz("month", "00"), D.day && this.bz("day", "00"), D.hour && this.bz("hour", "00"), D.minute && this.bz("minute", "00"), D.second && this.bz("second", "00"), D.ms && this.bz("ms", "000")) : (D.year && D.year.time.setText("00"), D.month && D.month.time.setText("00"), D.day && D.day.time.setText("00"), D.hour && D.hour.time.setText("00"), D.minute && D.minute.time.setText("00"), D.second && D.second.time.setText("00"), D.ms && D.ms.time.setText("000"), this.bi());
        var J;
        J = e.time ? e.time : {
            year: e.year || e.years,
            month: e.month || e.months,
            day: e.day || e.days,
            hour: e.hour || e.hours,
            minute: e.minute || e.minutes,
            second: e.second || e.seconds,
            ms: e.second || e.ms,
            ampm: e.ampm || ""
        }, this.a_ = new jbeeb.TimeDiff({
            end: J,
            rangeHi: r,
            rangeLo: h,
            timezone: e.timezone || 0,
            onComplete: this.bC.bind(this),
            truncate: e.truncate || 0
        });
        var K = R.length;
        if (this.bu = K, K > 0) {
            this.bw(!0);
            for (var u = 0; u < K; u++) {
                var E = new Image;
                if (E.onload = this.bt.bind(this), E.src = R[u], !t) {
                    t = document.createElement("div");
                    var Q = t.style;
                    Q.position = "fixed", Q.left = "0px", Q.bottom = "0px", Q.width = "1px", Q.height = "1px", Q.overflow = "hidden", document.body.appendChild(t)
                }
                Q = E.style, Q.position = "absolute", Q.left = "0px", Q.bottom = "0px", Q.width = "1px", Q.height = "1px", t.appendChild(E)
            }
            this.bb(), this.bb(), this.bb()
        } else this.br()
    }, n.bu = 0, n.bv = 0, n.bt = function (t) {
        this.bv++, t.target.onload = null, this.bv >= this.bu && (this.br(), this.bw(!1))
    }, n.br = function () {
        this.bs = !0, jbeeb.ticker.addEventListener("tick", this.tick, this)
    }, n.bw = function (t) {
        t ? (this.be.setAlpha(0), this.bg = 1, setTimeout(this.bh.bind(this), 10)) : (this.bg = 0, setTimeout(this.bh.bind(this), this.bf / 2))
    }, n.bf = 1e3, n.bg = 0, n.bh = function () {
        if (1 === this.bg) {
            var t = "opacity " + this.bf + "ms ease-out", e = this.be.element.style;
            e.opacity = "0", e.mozTransition = e.oTransition = e.msTransition = e.webkitTransition = e.transition = t
        } else this.be.setAlpha(1)
    }, n.tick = function () {
        this.bs === !0 && this.bb()
    }, n.bC = function (t) {
        this.bm && this.bm(t)
    }, n.bF = function (t) {
        return t.toString().length * this.bD
    }, n.bi = function () {
        for (var t = !1, e = 0; e < this.bl.length; e++) {
            var i = this.bl[e], n = i.store.name, s = i.time.text, l = this.bF(s);
            l >= this.bE && l != this.bj[n] && (i.setWidth(l + this.bA), this.bj[n] = l + this.bA, t = !0)
        }
        if (t)for (var a = 0, e = 0; e < this.bl.length; e++) {
            var i = this.bl[e], s = i.time.text, l = this.bF(s);
            i.setX(a), i.time.setWidth(i.width), i.time.center(), i.labels && (i.labels.setX(a), i.labels.setWidth(i.width)), i.line && (i.line.setWidth(i.width), i.line.center()), a += i.width + this.bH
        }
    }, n.bb = function () {
        this.bp += jbeeb.ticker.getInterval(), this.bp > this.bq && (this.ba = this.a_.update(), this.bp = 0);
        var t = this.bG, e = this.ba;
        "flip" == this.bc ? (t.year && this.bz("year", e.year), t.month && this.bz("month", e.month), t.day && this.bz("day", e.day), t.hour && this.bz("hour", e.hour), t.minute && this.bz("minute", e.minute), t.second && this.bz("second", e.second), t.ms && this.bz("ms", e.ms)) : (t.year && t.year.time.setText(e.year), t.month && t.month.time.setText(e.month), t.day && t.day.time.setText(e.day), t.hour && t.hour.time.setText(e.hour), t.minute && t.minute.time.setText(e.minute), t.second && t.second.time.setText(e.second), t.ms && t.ms.time.setText(e.ms), this.bi())
    }, n.bz = function (t, e) {
        for (var i = 0; i < this.totalFlipDigits; i++) {
            var n = this.bG[t].time.slot[i], s = this.bd[t], l = String(e).substr(i, 1), a = n.num[l];
            if (a) {
                if (s.prev[i] != l) {
                    for (var o = 0; o < 10; o++)n.num[o].hide();
                    a.show(), s.ani[i] = !0, s.aniCount[i] = 0
                }
                if (s.ani[i]) {
                    for (var o = 0; o < 3; o++)a.img[o].hide();
                    this.bB ? a.img[2].show() : (a.img[s.aniCount[i]].show(), s.aniCount[i]++, s.aniCount[i] > 2 && (s.ani[i] = !1))
                }
                s.prev[i] = l
            }
        }
    }, window.Countdown = e
}();
