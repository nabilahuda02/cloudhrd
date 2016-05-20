(function() {
    $('#profile_contacts').duplicator({
        row: ".row",
        remotes: {
            post: '/profile_contacts',
            put: '/profile_contacts',
            delete: '/profile_contacts',
            get: '/profile_contacts'
        }
    });
    $('#profile_educations').duplicator({
        row: ".row",
        remotes: {
            post: '/profile_educations',
            put: '/profile_educations',
            delete: '/profile_educations',
            get: '/profile_educations'
        }
    });
    $('#profile_emergency').duplicator({
        row: ".row",
        remotes: {
            post: '/profile_emergencies',
            put: '/profile_emergencies',
            delete: '/profile_emergencies',
            get: '/profile_emergencies'
        }
    });
    $('#profile_employment').duplicator({
        row: ".row",
        remotes: {
            post: '/profile_employment_history',
            put: '/profile_employment_history',
            delete: '/profile_employment_history',
            get: '/profile_employment_history'
        }
    });
    $('#profile_family').duplicator({
        row: ".row",
        remotes: {
            post: '/profile_family',
            put: '/profile_family',
            delete: '/profile_family',
            get: '/profile_family'
        }
    });

}).call(this);

;
(function(window, $) {

    /*
     * Pixastic - JavaScript Image Processing Library
     * Copyright (c) 2008 Jacob Seidelin, jseidelin@nihilogic.dk, http://blog.nihilogic.dk/
     * MIT License [http://www.pixastic.com/lib/license.txt]
     */

    var Pixastic = (function() {
        function addEvent(el, event, handler) {
            if (el.addEventListener)
                el.addEventListener(event, handler, false);
            else if (el.attachEvent)
                el.attachEvent("on" + event, handler);
        }

        function onready(handler) {
            var handlerDone = false;
            var execHandler = function() {
                if (!handlerDone) {
                    handlerDone = true;
                    handler();
                }
            }
            document.write("<" + "script defer src=\"//:\" id=\"__onload_ie_pixastic__\"></" + "script>");
            var script = document.getElementById("__onload_ie_pixastic__");
            script.onreadystatechange = function() {
                if (script.readyState == "complete") {
                    script.parentNode.removeChild(script);
                    execHandler();
                }
            }
            if (document.addEventListener)
                document.addEventListener("DOMContentLoaded", execHandler, false);
            addEvent(window, "load", execHandler);
        }

        function init() {
            var imgEls = getElementsByClass("pixastic", null, "img");
            var canvasEls = getElementsByClass("pixastic", null, "canvas");
            var elements = imgEls.concat(canvasEls);
            for (var i = 0; i < elements.length; i++) {
                (function() {
                    var el = elements[i];
                    var actions = [];
                    var classes = el.className.split(" ");
                    for (var c = 0; c < classes.length; c++) {
                        var cls = classes[c];
                        if (cls.substring(0, 9) == "pixastic-") {
                            var actionName = cls.substring(9);
                            if (actionName != "")
                                actions.push(actionName);
                        }
                    }
                    if (actions.length) {
                        if (el.tagName.toLowerCase() == "img") {
                            var dataImg = new Image();
                            dataImg.src = el.src;
                            if (dataImg.complete) {
                                for (var a = 0; a < actions.length; a++) {
                                    var res = Pixastic.applyAction(el, el, actions[a], null);
                                    if (res)
                                        el = res;
                                }
                            } else {
                                dataImg.onload = function() {
                                    for (var a = 0; a < actions.length; a++) {
                                        var res = Pixastic.applyAction(el, el, actions[a], null)
                                        if (res)
                                            el = res;
                                    }
                                }
                            }
                        } else {
                            setTimeout(function() {
                                for (var a = 0; a < actions.length; a++) {
                                    var res = Pixastic.applyAction(el, el, actions[a], null);
                                    if (res)
                                        el = res;
                                }
                            }, 1);
                        }
                    }
                })();
            }
        }
        if (typeof pixastic_parseonload != "undefined" && pixastic_parseonload)
            onready(init);

        function getElementsByClass(searchClass, node, tag) {
            var classElements = new Array();
            if (node == null)
                node = document;
            if (tag == null)
                tag = '*';
            var els = node.getElementsByTagName(tag);
            var elsLen = els.length;
            var pattern = new RegExp("(^|\\s)" + searchClass + "(\\s|$)");
            for (i = 0, j = 0; i < elsLen; i++) {
                if (pattern.test(els[i].className)) {
                    classElements[j] = els[i];
                    j++;
                }
            }
            return classElements;
        }
        var debugElement;

        function writeDebug(text, level) {
            if (!Pixastic.debug) return;
            try {
                switch (level) {
                    case "warn":
                        console.warn("Pixastic:", text);
                        break;
                    case "error":
                        console.error("Pixastic:", text);
                        break;
                    default:
                        console.log("Pixastic:", text);
                }
            } catch (e) {}
            if (!debugElement) {}
        }
        var hasCanvas = (function() {
            var c = document.createElement("canvas");
            var val = false;
            try {
                val = !!((typeof c.getContext == "function") && c.getContext("2d"));
            } catch (e) {}
            return function() {
                return val;
            }
        })();
        var hasCanvasImageData = (function() {
            var c = document.createElement("canvas");
            var val = false;
            var ctx;
            try {
                if (typeof c.getContext == "function" && (ctx = c.getContext("2d"))) {
                    val = (typeof ctx.getImageData == "function");
                }
            } catch (e) {}
            return function() {
                return val;
            }
        })();
        var hasGlobalAlpha = (function() {
            var hasAlpha = false;
            var red = document.createElement("canvas");
            if (hasCanvas() && hasCanvasImageData()) {
                red.width = red.height = 1;
                var redctx = red.getContext("2d");
                redctx.fillStyle = "rgb(255,0,0)";
                redctx.fillRect(0, 0, 1, 1);
                var blue = document.createElement("canvas");
                blue.width = blue.height = 1;
                var bluectx = blue.getContext("2d");
                bluectx.fillStyle = "rgb(0,0,255)";
                bluectx.fillRect(0, 0, 1, 1);
                redctx.globalAlpha = 0.5;
                redctx.drawImage(blue, 0, 0);
                var reddata = redctx.getImageData(0, 0, 1, 1).data;
                hasAlpha = (reddata[2] != 255);
            }
            return function() {
                return hasAlpha;
            }
        })();
        return {
            parseOnLoad: false,
            debug: false,
            applyAction: function(img, dataImg, actionName, options) {
                options = options || {};
                var imageIsCanvas = (img.tagName.toLowerCase() == "canvas");
                if (imageIsCanvas && Pixastic.Client.isIE()) {
                    if (Pixastic.debug) writeDebug("Tried to process a canvas element but browser is IE.");
                    return false;
                }
                var canvas, ctx;
                var hasOutputCanvas = false;
                if (Pixastic.Client.hasCanvas()) {
                    hasOutputCanvas = !!options.resultCanvas;
                    canvas = options.resultCanvas || document.createElement("canvas");
                    ctx = canvas.getContext("2d");
                }
                var w = img.offsetWidth;
                var h = img.offsetHeight;
                if (imageIsCanvas) {
                    w = img.width;
                    h = img.height;
                }
                if (w == 0 || h == 0) {
                    if (img.parentNode == null) {
                        var oldpos = img.style.position;
                        var oldleft = img.style.left;
                        img.style.position = "absolute";
                        img.style.left = "-9999px";
                        document.body.appendChild(img);
                        w = img.offsetWidth;
                        h = img.offsetHeight;
                        document.body.removeChild(img);
                        img.style.position = oldpos;
                        img.style.left = oldleft;
                    } else {
                        if (Pixastic.debug) writeDebug("Image has 0 width and/or height.");
                        return;
                    }
                }
                if (actionName.indexOf("(") > -1) {
                    var tmp = actionName;
                    actionName = tmp.substr(0, tmp.indexOf("("));
                    var arg = tmp.match(/\((.*?)\)/);
                    if (arg[1]) {
                        arg = arg[1].split(";");
                        for (var a = 0; a < arg.length; a++) {
                            thisArg = arg[a].split("=");
                            if (thisArg.length == 2) {
                                if (thisArg[0] == "rect") {
                                    var rectVal = thisArg[1].split(",");
                                    options[thisArg[0]] = {
                                        left: parseInt(rectVal[0], 10) || 0,
                                        top: parseInt(rectVal[1], 10) || 0,
                                        width: parseInt(rectVal[2], 10) || 0,
                                        height: parseInt(rectVal[3], 10) || 0
                                    }
                                } else {
                                    options[thisArg[0]] = thisArg[1];
                                }
                            }
                        }
                    }
                }
                if (!options.rect) {
                    options.rect = {
                        left: 0,
                        top: 0,
                        width: w,
                        height: h
                    };
                } else {
                    options.rect.left = Math.round(options.rect.left);
                    options.rect.top = Math.round(options.rect.top);
                    options.rect.width = Math.round(options.rect.width);
                    options.rect.height = Math.round(options.rect.height);
                }
                var validAction = false;
                if (Pixastic.Actions[actionName] && typeof Pixastic.Actions[actionName].process == "function") {
                    validAction = true;
                }
                if (!validAction) {
                    if (Pixastic.debug) writeDebug("Invalid action \"" + actionName + "\". Maybe file not included?");
                    return false;
                }
                if (!Pixastic.Actions[actionName].checkSupport()) {
                    if (Pixastic.debug) writeDebug("Action \"" + actionName + "\" not supported by this browser.");
                    return false;
                }
                if (Pixastic.Client.hasCanvas()) {
                    if (canvas !== img) {
                        canvas.width = w;
                        canvas.height = h;
                    }
                    if (!hasOutputCanvas) {
                        canvas.style.width = w + "px";
                        canvas.style.height = h + "px";
                    }
                    ctx.drawImage(dataImg, 0, 0, w, h);
                    if (!img.__pixastic_org_image) {
                        canvas.__pixastic_org_image = img;
                        canvas.__pixastic_org_width = w;
                        canvas.__pixastic_org_height = h;
                    } else {
                        canvas.__pixastic_org_image = img.__pixastic_org_image;
                        canvas.__pixastic_org_width = img.__pixastic_org_width;
                        canvas.__pixastic_org_height = img.__pixastic_org_height;
                    }
                } else if (Pixastic.Client.isIE() && typeof img.__pixastic_org_style == "undefined") {
                    img.__pixastic_org_style = img.style.cssText;
                }
                var params = {
                    image: img,
                    canvas: canvas,
                    width: w,
                    height: h,
                    useData: true,
                    options: options
                }
                var res = Pixastic.Actions[actionName].process(params);
                if (!res) {
                    return false;
                }
                if (Pixastic.Client.hasCanvas()) {
                    if (params.useData) {
                        if (Pixastic.Client.hasCanvasImageData()) {
                            canvas.getContext("2d").putImageData(params.canvasData, options.rect.left, options.rect.top);
                            canvas.getContext("2d").fillRect(0, 0, 0, 0);
                        }
                    }
                    if (!options.leaveDOM) {
                        canvas.title = img.title;
                        canvas.imgsrc = img.imgsrc;
                        if (!imageIsCanvas) canvas.alt = img.alt;
                        if (!imageIsCanvas) canvas.imgsrc = img.src;
                        canvas.className = img.className;
                        canvas.style.cssText = img.style.cssText;
                        canvas.name = img.name;
                        canvas.tabIndex = img.tabIndex;
                        canvas.id = img.id;
                        if (img.parentNode && img.parentNode.replaceChild) {
                            img.parentNode.replaceChild(canvas, img);
                        }
                    }
                    options.resultCanvas = canvas;
                    return canvas;
                }
                return img;
            },
            prepareData: function(params, getCopy) {
                var ctx = params.canvas.getContext("2d");
                var rect = params.options.rect;
                var dataDesc = ctx.getImageData(rect.left, rect.top, rect.width, rect.height);
                var data = dataDesc.data;
                if (!getCopy) params.canvasData = dataDesc;
                return data;
            },
            process: function(img, actionName, options, callback) {
                if (img.tagName.toLowerCase() == "img") {
                    var dataImg = new Image();
                    dataImg.src = img.src;
                    if (dataImg.complete) {
                        var res = Pixastic.applyAction(img, dataImg, actionName, options);
                        if (callback) callback(res);
                        return res;
                    } else {
                        dataImg.onload = function() {
                            var res = Pixastic.applyAction(img, dataImg, actionName, options)
                            if (callback) callback(res);
                        }
                    }
                }
                if (img.tagName.toLowerCase() == "canvas") {
                    var res = Pixastic.applyAction(img, img, actionName, options);
                    if (callback) callback(res);
                    return res;
                }
            },
            revert: function(img) {
                if (Pixastic.Client.hasCanvas()) {
                    if (img.tagName.toLowerCase() == "canvas" && img.__pixastic_org_image) {
                        img.width = img.__pixastic_org_width;
                        img.height = img.__pixastic_org_height;
                        img.getContext("2d").drawImage(img.__pixastic_org_image, 0, 0);
                        if (img.parentNode && img.parentNode.replaceChild) {
                            img.parentNode.replaceChild(img.__pixastic_org_image, img);
                        }
                        return img;
                    }
                } else if (Pixastic.Client.isIE()) {
                    if (typeof img.__pixastic_org_style != "undefined")
                        img.style.cssText = img.__pixastic_org_style;
                }
            },
            Client: {
                hasCanvas: hasCanvas,
                hasCanvasImageData: hasCanvasImageData,
                hasGlobalAlpha: hasGlobalAlpha,
                isIE: function() {
                    return !!document.all && !!window.attachEvent && !window.opera;
                }
            },
            Actions: {}
        }
    })();
    if (typeof jQuery != "undefined" && jQuery && jQuery.fn) {
        jQuery.fn.pixastic = function(action, options) {
            var newElements = [];
            this.each(function() {
                if (this.tagName.toLowerCase() == "img" && !this.complete) {
                    return;
                }
                var res = Pixastic.process(this, action, options);
                if (res) {
                    newElements.push(res);
                }
            });
            if (newElements.length > 0)
                return jQuery(newElements);
            else
                return this;
        };
    };
    Pixastic.Actions.crop = {
        process: function(params) {
            if (Pixastic.Client.hasCanvas()) {
                var rect = params.options.rect;
                var width = rect.width;
                var height = rect.height;
                var top = rect.top;
                var left = rect.left;
                if (typeof params.options.left != "undefined")
                    left = parseInt(params.options.left, 10);
                if (typeof params.options.top != "undefined")
                    top = parseInt(params.options.top, 10);
                if (typeof params.options.height != "undefined")
                    width = parseInt(params.options.width, 10);
                if (typeof params.options.height != "undefined")
                    height = parseInt(params.options.height, 10);
                if (left < 0) left = 0;
                if (left > params.width - 1) left = params.width - 1;
                if (top < 0) top = 0;
                if (top > params.height - 1) top = params.height - 1;
                if (width < 1) width = 1;
                if (left + width > params.width)
                    width = params.width - left;
                if (height < 1) height = 1;
                if (top + height > params.height)
                    height = params.height - top;
                var copy = document.createElement("canvas");
                copy.width = params.width;
                copy.height = params.height;
                copy.getContext("2d").drawImage(params.canvas, 0, 0);
                params.canvas.width = width;
                params.canvas.height = height;
                params.canvas.getContext("2d").clearRect(0, 0, width, height);
                params.canvas.getContext("2d").drawImage(copy, left, top, width, height, 0, 0, width, height);
                params.useData = false;
                return true;
            }
        },
        checkSupport: function() {
            return Pixastic.Client.hasCanvas();
        }
    }
    Pixastic.Actions.rotate = {
        process: function(params) {
            if (Pixastic.Client.hasCanvas()) {
                var canvas = params.canvas;
                var width = params.width;
                var height = params.height;
                var copy = document.createElement("canvas");
                copy.width = width;
                copy.height = height;
                copy.getContext("2d").drawImage(canvas, 0, 0, width, height);
                var angle = -parseFloat(params.options.angle) * Math.PI / 180;
                var dimAngle = angle;
                if (dimAngle > Math.PI * 0.5)
                    dimAngle = Math.PI - dimAngle;
                if (dimAngle < -Math.PI * 0.5)
                    dimAngle = -Math.PI - dimAngle;
                var diag = Math.sqrt(width * width + height * height);
                var diagAngle1 = Math.abs(dimAngle) - Math.abs(Math.atan2(height, width));
                var diagAngle2 = Math.abs(dimAngle) + Math.abs(Math.atan2(height, width));
                var newWidth = Math.abs(Math.cos(diagAngle1) * diag);
                var newHeight = Math.abs(Math.sin(diagAngle2) * diag);
                canvas.width = newWidth;
                canvas.height = newHeight;
                var ctx = canvas.getContext("2d");
                ctx.translate(newWidth / 2, newHeight / 2);
                ctx.rotate(angle);
                ctx.drawImage(copy, -width / 2, -height / 2);
                params.useData = false;
                return true;
            }
        },
        checkSupport: function() {
            return Pixastic.Client.hasCanvas();
        }
    }

    /*! Copyright (c) 2013 Brandon Aaron (http://brandon.aaron.sh)
     * Licensed under the MIT License (LICENSE.txt).
     *
     * Version: 3.1.11
     *
     * Requires: jQuery 1.2.2+
     */

    ;
    (function(factory) {
        if (typeof define === 'function' && define.amd) {
            // AMD. Register as an anonymous module.
            define(['jquery'], factory);
        } else if (typeof exports === 'object') {
            // Node/CommonJS style for Browserify
            module.exports = factory;
        } else {
            // Browser globals
            factory(jQuery);
        }
    }(function($) {

        var toFix = ['wheel', 'mousewheel', 'DOMMouseScroll', 'MozMousePixelScroll'],
            toBind = ('onwheel' in document || document.documentMode >= 9) ?
            ['wheel'] : ['mousewheel', 'DomMouseScroll', 'MozMousePixelScroll'],
            slice = Array.prototype.slice,
            nullLowestDeltaTimeout, lowestDelta;

        if ($.event.fixHooks) {
            for (var i = toFix.length; i;) {
                $.event.fixHooks[toFix[--i]] = $.event.mouseHooks;
            }
        }

        var special = $.event.special.mousewheel = {
            version: '3.1.11',

            setup: function() {
                if (this.addEventListener) {
                    for (var i = toBind.length; i;) {
                        this.addEventListener(toBind[--i], handler, false);
                    }
                } else {
                    this.onmousewheel = handler;
                }
                // Store the line height and page height for this particular element
                $.data(this, 'mousewheel-line-height', special.getLineHeight(this));
                $.data(this, 'mousewheel-page-height', special.getPageHeight(this));
            },

            teardown: function() {
                if (this.removeEventListener) {
                    for (var i = toBind.length; i;) {
                        this.removeEventListener(toBind[--i], handler, false);
                    }
                } else {
                    this.onmousewheel = null;
                }
                // Clean up the data we added to the element
                $.removeData(this, 'mousewheel-line-height');
                $.removeData(this, 'mousewheel-page-height');
            },

            getLineHeight: function(elem) {
                var $parent = $(elem)['offsetParent' in $.fn ? 'offsetParent' : 'parent']();
                if (!$parent.length) {
                    $parent = $('body');
                }
                return parseInt($parent.css('fontSize'), 10);
            },

            getPageHeight: function(elem) {
                return $(elem).height();
            },

            settings: {
                adjustOldDeltas: true, // see shouldAdjustOldDeltas() below
                normalizeOffset: true // calls getBoundingClientRect for each event
            }
        };

        $.fn.extend({
            mousewheel: function(fn) {
                return fn ? this.bind('mousewheel', fn) : this.trigger('mousewheel');
            },

            unmousewheel: function(fn) {
                return this.unbind('mousewheel', fn);
            }
        });


        function handler(event) {
            var orgEvent = event || window.event,
                args = slice.call(arguments, 1),
                delta = 0,
                deltaX = 0,
                deltaY = 0,
                absDelta = 0,
                offsetX = 0,
                offsetY = 0;
            event = $.event.fix(orgEvent);
            event.type = 'mousewheel';

            // Old school scrollwheel delta
            if ('detail' in orgEvent) {
                deltaY = orgEvent.detail * -1;
            }
            if ('wheelDelta' in orgEvent) {
                deltaY = orgEvent.wheelDelta;
            }
            if ('wheelDeltaY' in orgEvent) {
                deltaY = orgEvent.wheelDeltaY;
            }
            if ('wheelDeltaX' in orgEvent) {
                deltaX = orgEvent.wheelDeltaX * -1;
            }

            // Firefox < 17 horizontal scrolling related to DOMMouseScroll event
            if ('axis' in orgEvent && orgEvent.axis === orgEvent.HORIZONTAL_AXIS) {
                deltaX = deltaY * -1;
                deltaY = 0;
            }

            // Set delta to be deltaY or deltaX if deltaY is 0 for backwards compatabilitiy
            delta = deltaY === 0 ? deltaX : deltaY;

            // New school wheel delta (wheel event)
            if ('deltaY' in orgEvent) {
                deltaY = orgEvent.deltaY * -1;
                delta = deltaY;
            }
            if ('deltaX' in orgEvent) {
                deltaX = orgEvent.deltaX;
                if (deltaY === 0) {
                    delta = deltaX * -1;
                }
            }

            // No change actually happened, no reason to go any further
            if (deltaY === 0 && deltaX === 0) {
                return;
            }

            // Need to convert lines and pages to pixels if we aren't already in pixels
            // There are three delta modes:
            //   * deltaMode 0 is by pixels, nothing to do
            //   * deltaMode 1 is by lines
            //   * deltaMode 2 is by pages
            if (orgEvent.deltaMode === 1) {
                var lineHeight = $.data(this, 'mousewheel-line-height');
                delta *= lineHeight;
                deltaY *= lineHeight;
                deltaX *= lineHeight;
            } else if (orgEvent.deltaMode === 2) {
                var pageHeight = $.data(this, 'mousewheel-page-height');
                delta *= pageHeight;
                deltaY *= pageHeight;
                deltaX *= pageHeight;
            }

            // Store lowest absolute delta to normalize the delta values
            absDelta = Math.max(Math.abs(deltaY), Math.abs(deltaX));

            if (!lowestDelta || absDelta < lowestDelta) {
                lowestDelta = absDelta;

                // Adjust older deltas if necessary
                if (shouldAdjustOldDeltas(orgEvent, absDelta)) {
                    lowestDelta /= 40;
                }
            }

            // Adjust older deltas if necessary
            if (shouldAdjustOldDeltas(orgEvent, absDelta)) {
                // Divide all the things by 40!
                delta /= 40;
                deltaX /= 40;
                deltaY /= 40;
            }

            // Get a whole, normalized value for the deltas
            delta = Math[delta >= 1 ? 'floor' : 'ceil'](delta / lowestDelta);
            deltaX = Math[deltaX >= 1 ? 'floor' : 'ceil'](deltaX / lowestDelta);
            deltaY = Math[deltaY >= 1 ? 'floor' : 'ceil'](deltaY / lowestDelta);

            // Normalise offsetX and offsetY properties
            if (special.settings.normalizeOffset && this.getBoundingClientRect) {
                var boundingRect = this.getBoundingClientRect();
                offsetX = event.clientX - boundingRect.left;
                offsetY = event.clientY - boundingRect.top;
            }

            // Add information to the event object
            event.deltaX = deltaX;
            event.deltaY = deltaY;
            event.deltaFactor = lowestDelta;
            event.offsetX = offsetX;
            event.offsetY = offsetY;
            // Go ahead and set deltaMode to 0 since we converted to pixels
            // Although this is a little odd since we overwrite the deltaX/Y
            // properties with normalized deltas.
            event.deltaMode = 0;

            // Add event and delta to the front of the arguments
            args.unshift(event, delta, deltaX, deltaY);

            // Clearout lowestDelta after sometime to better
            // handle multiple device types that give different
            // a different lowestDelta
            // Ex: trackpad = 3 and mouse wheel = 120
            if (nullLowestDeltaTimeout) {
                clearTimeout(nullLowestDeltaTimeout);
            }
            nullLowestDeltaTimeout = setTimeout(nullLowestDelta, 200);

            return ($.event.dispatch || $.event.handle).apply(this, args);
        }

        function nullLowestDelta() {
            lowestDelta = null;
        }

        function shouldAdjustOldDeltas(orgEvent, absDelta) {
            // If this is an older event and the delta is divisable by 120,
            // then we are assuming that the browser is treating this as an
            // older mouse wheel event and that we should divide the deltas
            // by 40 to try and get a more usable deltaFactor.
            // Side note, this actually impacts the reported scroll distance
            // in older browsers and can cause scrolling to be slower than native.
            // Turn this off by setting $.event.special.mousewheel.settings.adjustOldDeltas to false.
            return special.settings.adjustOldDeltas && orgEvent.type === 'mousewheel' && absDelta % 120 === 0;
        }

    }));

    $.fn.drags = function(opt) {

        opt = $.extend({
            handle: "",
            cursor: "move"
        }, opt);

        if (opt.handle === "") {
            var $el = this;
        } else {
            var $el = this.find(opt.handle);
        }

        return $el.css('cursor', opt.cursor).on("mousedown", function(e) {
            if (opt.handle === "") {
                var $drag = $(this).addClass('draggable');
            } else {
                var $drag = $(this).addClass('active-handle').parent().addClass('draggable');
            }
            var z_idx = $drag.css('z-index'),
                drg_h = $drag.outerHeight(),
                drg_w = $drag.outerWidth(),
                pos_y = $drag.offset().top + drg_h - e.pageY,
                pos_x = $drag.offset().left + drg_w - e.pageX;
            $drag.css('z-index', 1000).parents().on("mousemove", function(e) {
                $('.draggable').offset({
                    top: e.pageY + pos_y - drg_h,
                    left: e.pageX + pos_x - drg_w
                }).on("mouseup", function() {
                    $(this).removeClass('draggable').css('z-index', z_idx);
                });
            });
            e.preventDefault(); // disable selection
        }).on("mouseup", function() {
            if (opt.handle === "") {
                $(this).removeClass('draggable');
            } else {
                $(this).removeClass('active-handle').parent().removeClass('draggable');
            }
        });

    }


    $.fn.avatar = function(url) {

        var image = $(this);
        image.on('ready', function() {
            var newimg;
            var originalWidth;
            var originalHeight;
            var imageHeight = image.height();
            var imageWidth = image.width();
            var canvas;
            var finput = $('<input type="file" accept="image/*">');
            var wrapper = $("<div></div>")
                .css({
                    position: 'relative',
                    overflow: 'hidden',
                    height: imageHeight,
                    width: imageWidth,
                });
            var buttons = $('<div></div>')
                .css({
                    position: 'absolute',
                    bottom: '0px',
                    right: '0px',
                    cursor: 'pointer',
                    'z-index': 1001
                })
                .hide();
            var dataURItoBlob = function(dataURI) {
                // convert base64 to raw binary data held in a string
                // doesn't handle URLEncoded DataURIs - see SO answer #6850276 for code that does this
                var byteString = atob(dataURI.split(',')[1]);

                // separate out the mime component
                var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]

                // write the bytes of the string to an ArrayBuffer
                var ab = new ArrayBuffer(byteString.length);
                var ia = new Uint8Array(ab);
                for (var i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }

                // write the ArrayBuffer to a blob, and you're done
                var bb = (window.MozBlobBuilder || window.WebKitBlobBuilder || window.BlobBuilder);
                if (!bb) {
                    return new Blob([ab], {
                        type: mimeString
                    });
                } else {
                    bb = new bb();
                }
                bb.append(ab);
                return bb.getBlob(mimeString);
            }
            var saveBtn = $('<button>&#10003;</button>')
                .click(function() {
                    var cropSettings = {
                        top: Math.abs(parseInt(newimg.css('top'), 10)) || 1,
                        left: Math.abs(parseInt(newimg.css('left'), 10)) || 1,
                        width: imageWidth,
                        height: imageHeight
                    };
                    canvas = newimg
                        .pixastic("crop", {
                            rect: cropSettings
                        });
                    canvas.css({
                        top: '0px',
                        left: '0px',
                        width: imageWidth,
                        height: imageHeight,
                    });

                    var fd = new FormData();
                    var uri = canvas[0].toDataURL();
                    $(canvas).remove();
                    newimg.remove();
                    image.attr('src', uri).fadeIn();

                    fd.append("file", dataURItoBlob(uri));
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            buttons.fadeOut();
                            try {
                                finput[0].value = '';
                                if (finput[0].value) {
                                    finput[0].type = "text";
                                    finput[0].type = "file";
                                }
                            } catch (e) {}
                            setTimeout(function() {
                                hoverWrapper.fadeIn(100);
                                $.notify('Profile image updated', 'success');
                            }, 2000);
                        },
                        error: function(jqXHR, textStatus, errorMessage) {
                            console.log(errorMessage); // Optional
                        }
                    });
                });
            var rotateBtn = $('<button>↻</button>')
                .click(function() {
                    if (!buttons.angle)
                        buttons.angle = 0;
                    buttons.angle = buttons.angle + 90;
                    console.log(buttons.angle)
                    newimg.pixastic("rotate", {
                        angle: buttons.angle
                    });

                });
            var cancelBtn = $('<button>&times;</button>')
                .click(function() {
                    $(canvas).remove();
                    newimg.remove();
                    image.fadeIn();
                    buttons.fadeOut();
                    hoverWrapper.fadeIn(100);
                    try {
                        finput[0].value = '';
                        if (finput[0].value) {
                            finput[0].type = "text";
                            finput[0].type = "file";
                        }
                    } catch (e) {}
                });
            buttons.append(saveBtn);
            // buttons.append(rotateBtn);
            buttons.append(cancelBtn);
            var hoverWrapper = $("<div>Click to change</div>")
                .css({
                    'background-color': 'rgba(255,255,255,0.8)',
                    height: imageHeight,
                    width: imageWidth,
                    'padding-top': '45%',
                    position: 'absolute',
                    top: '0px',
                    left: '0px',
                    cursor: 'pointer'
                })
                // .hide()
                ;
            var helperWrapper = $("<div>Use your mousewheel to zoom and drag to position your avatar. Click &#10003; once your are done.</div>")
                .css({
                    'background-color': 'rgba(255,255,255,0.8)',
                    height: imageHeight,
                    width: imageWidth,
                    'padding-top': '18%',
                    position: 'absolute',
                    top: '0px',
                    left: '0px',
                    cursor: 'pointer'
                })
                .hide()
                .click(function(){
                    helperWrapper.fadeOut();
                })
                ;

            image.wrap(wrapper);
            image.after(hoverWrapper);
            image.after(helperWrapper);
            image.after(buttons);
            hoverWrapper.click(function() {
                finput.click();
            });
            finput.on('change', function(e) {
                if (this.files && this.files[0]) {
                    hoverWrapper.hide();
                    image.fadeOut();
                    window.URL = window.URL || window.webkitURL;
                    newimg = $(new Image())
                        .hide()
                        .mousewheel(function(e, delta) {
                            var delta_px = delta > 0 ? "+=50" : "-=50";
                            $(this).css('width', delta_px);
                            e.preventDefault();
                            e.stopImmediatePropagation();
                        }).on('load', function() {
                            newimg
                                .show()
                                .width('auto')
                                .drags();
                            originalWidth = newimg.width();
                            originalHeight = newimg.height();
                        })
                        .attr('src', window.URL.createObjectURL(this.files[0]))
                    image.after(newimg);
                    helperWrapper.fadeIn();
                    setTimeout(function() {
                        helperWrapper.fadeOut();
                        buttons.fadeIn();
                    }, 3000);
                }
            });
        })
        if (image.height() > 0) {
            image.trigger('ready');
        } else {
            image.on('load', function() {
                image.trigger('ready');
            });
        }
    }

}).call(this, window, jQuery);

$('#profile_image').avatar('/ajax/upload-image');;