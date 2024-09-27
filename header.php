<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <script>
    if (navigator.userAgent.match(/MSIE|Internet Explorer/i) || navigator.userAgent.match(/Trident\/7\..*?rv:11/i)) {
        var href = document.location.href;
        if (!href.match(/[?&]nowprocket/)) {
            if (href.indexOf("?") == -1) {
                if (href.indexOf("#") == -1) {
                    document.location.href = href + "?nowprocket=1"
                } else {
                    document.location.href = href.replace("#", "?nowprocket=1#")
                }
            } else {
                if (href.indexOf("#") == -1) {
                    document.location.href = href + "&nowprocket=1"
                } else {
                    document.location.href = href.replace("#", "&nowprocket=1#")
                }
            }
        }
    }
    </script>
    <script>
    class RocketLazyLoadScripts {
        constructor() {
            this.triggerEvents = ["keydown", "mousedown", "mousemove", "touchmove", "touchstart", "touchend",
                    "wheel"
                ], this.userEventHandler = this._triggerListener.bind(this), this.touchStartHandler = this
                ._onTouchStart.bind(this), this.touchMoveHandler = this._onTouchMove.bind(this), this
                .touchEndHandler = this._onTouchEnd.bind(this), this.clickHandler = this._onClick.bind(this), this
                .interceptedClicks = [], window.addEventListener("pageshow", (e => {
                    this.persisted = e.persisted
                })), window.addEventListener("DOMContentLoaded", (() => {
                    this._preconnect3rdParties()
                })), this.delayedScripts = {
                    normal: [],
                    async: [],
                    defer: []
                }, this.allJQueries = []
        }
        _addUserInteractionListener(e) {
            document.hidden ? e._triggerListener() : (this.triggerEvents.forEach((t => window.addEventListener(t, e
                .userEventHandler, {
                    passive: !0
                }))), window.addEventListener("touchstart", e.touchStartHandler, {
                passive: !0
            }), window.addEventListener("mousedown", e.touchStartHandler), document.addEventListener(
                "visibilitychange", e.userEventHandler))
        }
        _removeUserInteractionListener() {
            this.triggerEvents.forEach((e => window.removeEventListener(e, this.userEventHandler, {
                passive: !0
            }))), document.removeEventListener("visibilitychange", this.userEventHandler)
        }
        _onTouchStart(e) {
            "HTML" !== e.target.tagName && (window.addEventListener("touchend", this.touchEndHandler), window
                .addEventListener("mouseup", this.touchEndHandler), window.addEventListener("touchmove", this
                    .touchMoveHandler, {
                        passive: !0
                    }), window.addEventListener("mousemove", this.touchMoveHandler), e.target.addEventListener(
                    "click", this.clickHandler), this._renameDOMAttribute(e.target, "onclick", "rocket-onclick")
            )
        }
        _onTouchMove(e) {
            window.removeEventListener("touchend", this.touchEndHandler), window.removeEventListener("mouseup", this
                .touchEndHandler), window.removeEventListener("touchmove", this.touchMoveHandler, {
                passive: !0
            }), window.removeEventListener("mousemove", this.touchMoveHandler), e.target.removeEventListener(
                "click", this.clickHandler), this._renameDOMAttribute(e.target, "rocket-onclick", "onclick")
        }
        _onTouchEnd(e) {
            window.removeEventListener("touchend", this.touchEndHandler), window.removeEventListener("mouseup", this
                .touchEndHandler), window.removeEventListener("touchmove", this.touchMoveHandler, {
                passive: !0
            }), window.removeEventListener("mousemove", this.touchMoveHandler)
        }
        _onClick(e) {
            e.target.removeEventListener("click", this.clickHandler), this._renameDOMAttribute(e.target,
                    "rocket-onclick", "onclick"), this.interceptedClicks.push(e), e.preventDefault(), e
                .stopPropagation(), e.stopImmediatePropagation()
        }
        _replayClicks() {
            window.removeEventListener("touchstart", this.touchStartHandler, {
                passive: !0
            }), window.removeEventListener("mousedown", this.touchStartHandler), this.interceptedClicks.forEach(
                (e => {
                    e.target.dispatchEvent(new MouseEvent("click", {
                        view: e.view,
                        bubbles: !0,
                        cancelable: !0
                    }))
                }))
        }
        _renameDOMAttribute(e, t, n) {
            e.hasAttribute && e.hasAttribute(t) && (event.target.setAttribute(n, event.target.getAttribute(t)),
                event.target.removeAttribute(t))
        }
        _triggerListener() {
            this._removeUserInteractionListener(this), "loading" === document.readyState ? document
                .addEventListener("DOMContentLoaded", this._loadEverythingNow.bind(this)) : this
                ._loadEverythingNow()
        }
        _preconnect3rdParties() {
            let e = [];
            document.querySelectorAll("script[type=rocketlazyloadscript]").forEach((t => {
                    if (t.hasAttribute("src")) {
                        const n = new URL(t.src).origin;
                        n !== location.origin && e.push({
                            src: n,
                            crossOrigin: t.crossOrigin || "module" === t.getAttribute(
                                "data-rocket-type")
                        })
                    }
                })), e = [...new Map(e.map((e => [JSON.stringify(e), e]))).values()], this
                ._batchInjectResourceHints(e, "preconnect")
        }
        async _loadEverythingNow() {
            this.lastBreath = Date.now(), this._delayEventListeners(), this._delayJQueryReady(this), this
                ._handleDocumentWrite(), this._registerAllDelayedScripts(), this._preloadAllScripts(), await this
                ._loadScriptsFromList(this.delayedScripts.normal), await this._loadScriptsFromList(this
                    .delayedScripts.defer), await this._loadScriptsFromList(this.delayedScripts.async);
            try {
                await this._triggerDOMContentLoaded(), await this._triggerWindowLoad()
            } catch (e) {}
            window.dispatchEvent(new Event("rocket-allScriptsLoaded")), this._replayClicks()
        }
        _registerAllDelayedScripts() {
            document.querySelectorAll("script[type=rocketlazyloadscript]").forEach((e => {
                e.hasAttribute("src") ? e.hasAttribute("async") && !1 !== e.async ? this.delayedScripts
                    .async.push(e) : e.hasAttribute("defer") && !1 !== e.defer || "module" === e
                    .getAttribute("data-rocket-type") ? this.delayedScripts.defer.push(e) : this
                    .delayedScripts.normal.push(e) : this.delayedScripts.normal.push(e)
            }))
        }
        async _transformScript(e) {
            return await this._littleBreath(), new Promise((t => {
                const n = document.createElement("script");
                [...e.attributes].forEach((e => {
                    let t = e.nodeName;
                    "type" !== t && ("data-rocket-type" === t && (t = "type"), n
                        .setAttribute(t, e.nodeValue))
                })), e.hasAttribute("src") ? (n.addEventListener("load", t), n.addEventListener(
                    "error", t)) : (n.text = e.text, t());
                try {
                    e.parentNode.replaceChild(n, e)
                } catch (e) {
                    t()
                }
            }))
        }
        async _loadScriptsFromList(e) {
            const t = e.shift();
            return t ? (await this._transformScript(t), this._loadScriptsFromList(e)) : Promise.resolve()
        }
        _preloadAllScripts() {
            this._batchInjectResourceHints([...this.delayedScripts.normal, ...this.delayedScripts.defer, ...this
                .delayedScripts.async
            ], "preload")
        }
        _batchInjectResourceHints(e, t) {
            var n = document.createDocumentFragment();
            e.forEach((e => {
                if (e.src) {
                    const i = document.createElement("link");
                    i.href = e.src, i.rel = t, "preconnect" !== t && (i.as = "script"), e
                        .getAttribute && "module" === e.getAttribute("data-rocket-type") && (i
                            .crossOrigin = !0), e.crossOrigin && (i.crossOrigin = e.crossOrigin), n
                        .appendChild(i)
                }
            })), document.head.appendChild(n)
        }
        _delayEventListeners() {
            let e = {};

            function t(t, n) {
                ! function(t) {
                    function n(n) {
                        return e[t].eventsToRewrite.indexOf(n) >= 0 ? "rocket-" + n : n
                    }
                    e[t] || (e[t] = {
                        originalFunctions: {
                            add: t.addEventListener,
                            remove: t.removeEventListener
                        },
                        eventsToRewrite: []
                    }, t.addEventListener = function() {
                        arguments[0] = n(arguments[0]), e[t].originalFunctions.add.apply(t, arguments)
                    }, t.removeEventListener = function() {
                        arguments[0] = n(arguments[0]), e[t].originalFunctions.remove.apply(t, arguments)
                    })
                }(t), e[t].eventsToRewrite.push(n)
            }

            function n(e, t) {
                let n = e[t];
                Object.defineProperty(e, t, {
                    get: () => n || function() {},
                    set(i) {
                        e["rocket" + t] = n = i
                    }
                })
            }
            t(document, "DOMContentLoaded"), t(window, "DOMContentLoaded"), t(window, "load"), t(window,
                "pageshow"), t(document, "readystatechange"), n(document, "onreadystatechange"), n(window,
                "onload"), n(window, "onpageshow")
        }
        _delayJQueryReady(e) {
            let t = window.jQuery;
            Object.defineProperty(window, "jQuery", {
                get: () => t,
                set(n) {
                    if (n && n.fn && !e.allJQueries.includes(n)) {
                        n.fn.ready = n.fn.init.prototype.ready = function(t) {
                            e.domReadyFired ? t.bind(document)(n) : document.addEventListener(
                                "rocket-DOMContentLoaded", (() => t.bind(document)(n)))
                        };
                        const t = n.fn.on;
                        n.fn.on = n.fn.init.prototype.on = function() {
                            if (this[0] === window) {
                                function e(e) {
                                    return e.split(" ").map((e => "load" === e || 0 === e.indexOf(
                                        "load.") ? "rocket-jquery-load" : e)).join(" ")
                                }
                                "string" == typeof arguments[0] || arguments[0] instanceof String ?
                                    arguments[0] = e(arguments[0]) : "object" == typeof arguments[
                                        0] && Object.keys(arguments[0]).forEach((t => {
                                        delete Object.assign(arguments[0], {
                                            [e(t)]: arguments[0][t]
                                        })[t]
                                    }))
                            }
                            return t.apply(this, arguments), this
                        }, e.allJQueries.push(n)
                    }
                    t = n
                }
            })
        }
        async _triggerDOMContentLoaded() {
            this.domReadyFired = !0, await this._littleBreath(), document.dispatchEvent(new Event(
                    "rocket-DOMContentLoaded")), await this._littleBreath(), window.dispatchEvent(new Event(
                    "rocket-DOMContentLoaded")), await this._littleBreath(), document.dispatchEvent(new Event(
                    "rocket-readystatechange")), await this._littleBreath(), document.rocketonreadystatechange &&
                document.rocketonreadystatechange()
        }
        async _triggerWindowLoad() {
            await this._littleBreath(), window.dispatchEvent(new Event("rocket-load")), await this._littleBreath(),
                window.rocketonload && window.rocketonload(), await this._littleBreath(), this.allJQueries.forEach((
                    e => e(window).trigger("rocket-jquery-load"))), await this._littleBreath();
            const e = new Event("rocket-pageshow");
            e.persisted = this.persisted, window.dispatchEvent(e), await this._littleBreath(), window
                .rocketonpageshow && window.rocketonpageshow({
                    persisted: this.persisted
                })
        }
        _handleDocumentWrite() {
            const e = new Map;
            document.write = document.writeln = function(t) {
                const n = document.currentScript,
                    i = document.createRange(),
                    r = n.parentElement;
                let o = e.get(n);
                void 0 === o && (o = n.nextSibling, e.set(n, o));
                const s = document.createDocumentFragment();
                i.setStart(s, 0), s.appendChild(i.createContextualFragment(t)), r.insertBefore(s, o)
            }
        }
        async _littleBreath() {
            Date.now() - this.lastBreath > 45 && (await this._requestAnimFrame(), this.lastBreath = Date.now())
        }
        async _requestAnimFrame() {
            return document.hidden ? new Promise((e => setTimeout(e))) : new Promise((e => requestAnimationFrame(
                e)))
        }
        static run() {
            const e = new RocketLazyLoadScripts;
            e._addUserInteractionListener(e)
        }
    }
    RocketLazyLoadScripts.run();
    </script>
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=.5, user-scalable=yes">
    <meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />
    
    <link rel='stylesheet' id='mindk-tablet-css-css' href='css14/tablet.min.css' type='text/css'
    media='screen and (min-width: 768px) and (max-width: 1279.9px)' />
    <link rel='stylesheet' id='neliofp_style_css-css' href='css14/style.min.css' type='text/css' media='all' />
    <link data-minify="1" rel='stylesheet' id='wpa-css-css' href='css14/wpa.css' type='text/css' media='all' />
    <link data-minify="1" rel='stylesheet' id='cf7-custom-css-css' href='css14/cf7-custom.css' type='text/css'
        media='all' />
    <link rel='stylesheet' id='mindk-style-css' href='css14/style.css' type='text/css' media='all' />
    
    <link rel='stylesheet' id='mindk-desktop-css-css' href='css14/desktop.min.css' type='text/css'
        media='screen and (min-width: 1280px)' />
    <script type='text/javascript' src='js/jquery.min.js' id='jquery-migrate-js'></script>
    <script type='text/javascript' src='js/jquery-migrate.min.js' id='jquery-core-js'></script>
    
    
    <noscript>
        <style id="rocket-lazyload-nojs-css">
        .rll-youtube-player,
        [data-lazy-src] {
            display: none !important
        }
        </style>
    </noscript>

    
    <script type="rocketlazyloadscript" data-rocket-type="text/javascript"
        src="https://onsite.optimonk.com/script.js?account=204641" async></script>