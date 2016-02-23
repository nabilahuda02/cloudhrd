;(function(){
    /*
     * fiduswriter/diffDOM Source Available under LGPL-V3 at https://github.com/fiduswriter/diffDOM
     * LGPL-V3 can be viewed at: http://opensource.org/licenses/lgpl-3.0.html
     * fiduswriter/diffDOM diffDOM.js Source Minified on 8-OCT-2014
     */
    ;!function(){function R(a,b,c){!function(d){a[b]=a[c],a[c]=d}(a[b])}var a,b=0,c=1,d=2,e=3,f=4,g=5,h=6,i=7,j=8,k=9,l=10,m=11,n=12,o=13,p=14,q=15,r=16,s=17,t=18,u=19,v=20,w=21,x=22,y=23,z=24,A=25,B=26,C=27,D=28,E=29,F=30,G=function(a){var b=this;Object.keys(a).forEach(function(c){b[c]=a[c]})};G.prototype={toString:function(){return JSON.stringify(this)}};var H=function(a,b){this.old=a,this["new"]=b};H.prototype={contains:function(a){return a.length<this.length?a["new"]>=this["new"]&&a["new"]<this["new"]+this.length:!1},toString:function(){return this.length+" element subset, first mapping: old "+this.old+" \u2192 new "+this["new"]}};var I=function X(a,b,c){if(!a||!b)return!1;if(a.nodeType!==b.nodeType)return!1;if(3===a.nodeType)return 3!==b.nodeType?!1:c?!0:a.data===b.data;if(a.nodeName!==b.nodeName)return!1;if(a.childNodes.length!==b.childNodes.length)return!1;for(var d=!0,e=a.childNodes.length-1;e>=0;e--)d=c?d&&a.childNodes[e].nodeName===b.childNodes[e].nodeName:d&&X(a.childNodes[e],b.childNodes[e],!0);return d},J=function(a){var c,d,e,f,g,b=a.cloneNode(!0);if(8!=a.nodeType&&3!=a.nodeType){for(c=a.querySelectorAll("textarea"),d=b.querySelectorAll("textarea"),g=0;g<c.length;g++)d[g].value!==c[g].value&&(d[g].value=c[g].value);for(a.value&&a.value!==b.value&&(b.value=a.value),e=a.querySelectorAll("option"),f=b.querySelectorAll("option"),g=0;g<e.length;g++)e[g].selected&&!f[g].selected?f[g].selected=!0:!e[g].selected&&f[g].selected&&(f[g].selected=!1);a.selected&&!b.selected?b.selected=!0:!a.selected&&b.selected&&(b.selected=!1)}return b},K=function(a){var c,b={};if(3===a.nodeType)b[z]=a.data;else if(8===a.nodeType)b[C]=a.data;else{if(b[B]=a.nodeName,a.attributes&&a.attributes.length>0)for(b[A]=[],c=0;c<a.attributes.length;c++)b[A].push([a.attributes[c].name,a.attributes[c].value]);if(a.childNodes&&a.childNodes.length>0)for(b[D]=[],c=0;c<a.childNodes.length;c++)b[D].push(K(a.childNodes[c]));a.value&&(b[y]=a.value),a.checked&&(b[E]=a.checked),a.selected&&(b[F]=a.selected)}return b},L=function(a,b){var c,d;if(a.hasOwnProperty(z))c=document.createTextNode(a[z]);else if(a.hasOwnProperty(C))c=document.createComment(a[C]);else{if("svg"===a[B]||b?(c=document.createElementNS("http://www.w3.org/2000/svg",a[B]),b=!0):c=document.createElement(a[B]),a[A])for(d=0;d<a[A].length;d++)c.setAttribute(a[A][d][0],a[A][d][1]);if(a[D])for(d=0;d<a[D].length;d++)c.appendChild(L(a[D][d],b));a[y]&&(c.value=a[y]),a[E]&&(c.checked=a[E]),a[F]&&(c.selected=a[F])}return c},M=function(a,b,c,d){var j,k,l,e=0,f=[],g=a.length,h=b.length,i=[];for(j=0;g+1>j;j++)i[j]=[];for(k=0;g>k;k++)for(l=0;h>l;l++)c[k]||d[l]||!I(a[k],b[l])?i[k+1][l+1]=0:(i[k+1][l+1]=i[k][l]?i[k][l]+1:1,i[k+1][l+1]>e&&(e=i[k+1][l+1],f=[k+1,l+1]));if(0===e)return!1;var m=[f[0]-e,f[1]-e],n=new H(m[0],m[1]);return n.length=e,n},N=function(a,b){var c=function(a){a.slice();for(var b=0,d=a.length;d>b;b++)a[b]instanceof Array&&(a[b]=c(a[b]))};b instanceof Array&&(b=c(b));var d=function(){return b};return new Array(a).join(".").split(".").map(d)},O=function(a,b,c){var e=N(a.childNodes.length,!0),f=N(b.childNodes.length,!0),g=0;return c.forEach(function(a){var b,c;for(b=a.old,c=b+a.length;c>b;b++)e[b]=g;for(b=a["new"],c=b+a.length;c>b;b++)f[b]=g;g++}),{gaps1:e,gaps2:f}},P=function(a,b){a=J(a),b=J(b);for(var i,c=a.childNodes,d=b.childNodes,e=N(c.length,!1),f=N(d.length,!1),g=[],h=!0;h;)if(h=M(c,d,e,f)){for(g.push(h),i=0;i<h.length;i++)e[h.old+i]=!0;for(i=0;i<h.length;i++)f[h.new+i]=!0}return g},Q=function(a,b,c,d){if(0===c.length)return!1;var x,z,A,D,E,F,H,k=O(a,b,c),l=k.gaps1,m=l.length,n=k.gaps2,o=l.length,B=o>m?m:o,I=o>m?l:n;for(x=0,B=I.length;B>x;x++){if(l[x]===!0){if(E=a.childNodes[x],3===E.nodeType){if(3===b.childNodes[x].nodeType&&E.data!=b.childNodes[x].data){for(H=E;H.nextSibling&&3===H.nextSibling.nodeType;)if(H=H.nextSibling,b.childNodes[x].data===H.data){F=!0;break}if(!F)return A={},A[p]=e,A[q]=d.concat(x),A[r]=E.data,A[s]=b.childNodes[x].data,new G(A)}return A={},A[p]=i,A[q]=d.concat(x),A[y]=E.data,new G(A)}return A={},A[p]=g,A[q]=d.concat(x),A[t]=K(E),new G(A)}if(n[x]===!0)return E=b.childNodes[x],3===E.nodeType?(A={},A[p]=j,A[q]=d.concat(x),A[y]=E.data,new G(A)):(A={},A[p]=h,A[q]=d.concat(x),A[t]=K(E),new G(A));if(l[x]!=n[x]){D=c[l[x]];var J=Math.min(D["new"],a.childNodes.length-D.length);if(J!=x){var L=!1;for(z=0;z<D.length;z++)a.childNodes[J+z].isEqualNode(a.childNodes[x+z])||(L=!0);if(L)return A={},A[p]=f,A[u]=D,A[v]=x,A[w]=J,A[q]=d,new G(A)}}}return!1},S=function(){this.list=[]};S.prototype={list:!1,add:function(a){var b=this.list;a.forEach(function(a){b.push(a)})},forEach:function(a){this.list.forEach(a)}};var T=function(a,o){"undefined"==typeof a?a=!1:(b="add attribute",c="modify attribute",d="remove attribute",e="modify text element",f="relocate group",g="remove element",h="add element",i="remove text element",j="add text element",k="replace element",l="modify value",m="modify checked",n="modify selected",p="action",q="route",r="oldValue",s="newValue",t="element",u="group",v="from",w="to",x="name",y="value",z="text",A="attributes",B="nodeName",C="comment",D="childNodes",E="checked",F="selected"),"undefined"==typeof o&&(o=10),this.debug=a,this.diffcap=o};T.prototype={diff:function(b,c){return a=0,b=J(b),c=J(c),this.debug&&(this.t1Orig=K(b),this.t2Orig=K(c)),this.tracker=new S,this.findDiffs(b,c)},findDiffs:function(b,c){do{if(this.debug&&(a++,a>this.diffcap))throw window.diffError=[this.t1Orig,this.t2Orig],new Error("surpassed diffcap:"+JSON.stringify(this.t1Orig)+" -> "+JSON.stringify(this.t2Orig));difflist=this.findFirstDiff(b,c,[]),difflist&&(difflist.length||(difflist=[difflist]),this.tracker.add(difflist),this.apply(b,difflist))}while(difflist);return this.tracker.list},findFirstDiff:function(a,b,c){var d=this.findOuterDiff(a,b,c);if(d.length>0)return d;var e=this.findInnerDiff(a,b,c);return e&&("undefined"==typeof e.length&&(e=[e]),e.length>0)?e:!1},findOuterDiff:function(a,e,f){var g;if(a.nodeName!=e.nodeName)return g={},g[p]=k,g[r]=K(a),g[s]=K(e),g[q]=f,[new G(g)];var h=Array.prototype.slice,i=function(a,b){return a.name>b.name},j=a.attributes?h.call(a.attributes).sort(i):[],t=e.attributes?h.call(e.attributes).sort(i):[],u=function(a,b){for(var c=0,d=b.length;d>c;c++)if(b[c].name===a.name)return c;return-1},v=[];if((a.value||e.value)&&a.value!==e.value&&"OPTION"!==a.nodeName&&(g={},g[p]=l,g[r]=a.value,g[s]=e.value,g[q]=f,v.push(new G(g))),(a.checked||e.checked)&&a.checked!==e.checked&&(g={},g[p]=m,g[r]=a.checked,g[s]=e.checked,g[q]=f,v.push(new G(g))),j.forEach(function(a){var e,b=u(a,t);if(-1===b)return e={},e[p]=d,e[q]=f,e[x]=a.name,e[y]=a.value,v.push(new G(e)),v;var g=t.splice(b,1)[0];a.value!==g.value&&(e={},e[p]=c,e[q]=f,e[x]=a.name,e[r]=a.value,e[s]=g.value,v.push(new G(e)))}),a.attributes||a.data===e.data||(g={},g[p]=o,g[q]=f,g[r]=a.data,g[s]=e.data,v.push(new G(g))),v.length>0)return v;if(t.forEach(function(a){var c;c={},c[p]=b,c[q]=f,c[x]=a.name,c[y]=a.value,v.push(new G(c))}),(a.selected||e.selected)&&a.selected!==e.selected){if(v.length>0)return v;g={},g[p]=n,g[r]=a.selected,g[s]=e.selected,g[q]=f,v.push(new G(g))}return v},findInnerDiff:function(a,b,c){var k,d=P(a,b),f=d.length;if(0===f&&3===a.nodeType&&3===b.nodeType&&a.data!==b.data)return k={},k[p]=e,k[r]=a.data,k[s]=b.data,k[q]=c,new G(k);if(2>f){var l,m,n,o,u,v;for(n=0,o=Math.max(a.childNodes.length,b.childNodes.length);o>n;n++){if(u=a.childNodes[n],v=b.childNodes[n],u&&!v)return 3===u.nodeType?(k={},k[p]=i,k[q]=c.concat(n),k[y]=u.data,new G(k)):(k={},k[p]=g,k[q]=c.concat(n),k[t]=K(u),new G(k));if(v&&!u)return 3===v.nodeType?(k={},k[p]=j,k[q]=c.concat(n),k[y]=v.data,new G(k)):(k={},k[p]=h,k[q]=c.concat(n),k[t]=K(v),new G(k));if((3!=u.nodeType||3!=v.nodeType)&&(m=this.findOuterDiff(u,v,c.concat(n)),m.length>0))return m;if(l=this.findInnerDiff(u,v,c.concat(n)))return l}}return this.findFirstInnerDiff(a,b,d,c)},findFirstInnerDiff:Q,apply:function(a,b){var c=this;return"undefined"==typeof b.length&&(b=[b]),0===b.length?!0:(b.forEach(function(b){return c.applyDiff(a,b)?void 0:!1}),!0)},getFromRoute:function(a,b){b=b.slice();for(var c,d=a;b.length>0;){if(!d.childNodes)return!1;c=b.splice(0,1)[0],d=d.childNodes[c]}return d},textDiff:function(a,b,c,d){a.data=d},applyDiff:function(a,z){var A=this.getFromRoute(a,z[q]);if(z[p]===b){if(!A||!A.setAttribute)return!1;A.setAttribute(z[x],z[y])}else if(z[p]===c){if(!A||!A.setAttribute)return!1;A.setAttribute(z[x],z[s])}else if(z[p]===d){if(!A||!A.removeAttribute)return!1;A.removeAttribute(z[x])}else if(z[p]===l){if(!A||"undefined"==typeof A.value)return!1;A.value=z[s]}else if(z[p]===o){if(!A||"undefined"==typeof A.data)return!1;A.data=z[s]}else if(z[p]===m){if(!A||"undefined"==typeof A.checked)return!1;A.checked=z[s]}else if(z[p]===n){if(!A||"undefined"==typeof A.selected)return!1;A.selected=z[s]}else if(z[p]===e){if(!A||3!=A.nodeType)return!1;this.textDiff(A,A.data,z[r],z[s])}else if(z[p]===k){var B=L(z[s]);A.parentNode.replaceChild(B,A)}else if(z[p]===f){var F,G,C=z[u],D=z[v],E=z[w];if(G=A.childNodes[E+C.length],E>D)for(var H=0;H<C.length;H++)F=A.childNodes[D],A.insertBefore(F,G);else{G=A.childNodes[E];for(var H=0;H<C.length;H++)F=A.childNodes[D+H],A.insertBefore(F,G)}}else if(z[p]===g)A.parentNode.removeChild(A);else if(z[p]===i){if(!A||3!=A.nodeType)return!1;A.parentNode.removeChild(A)}else if(z[p]===h){var I=z[q].slice(),J=I.splice(I.length-1,1)[0];A=this.getFromRoute(a,I);var B=L(z[t]);if(J>=A.childNodes.length)A.appendChild(B);else{var G=A.childNodes[J];A.insertBefore(B,G)}}else if(z[p]===j){var I=z[q].slice(),J=I.splice(I.length-1,1)[0],B=document.createTextNode(z[y]);if(A=this.getFromRoute(a,I),!A||!A.childNodes)return!1;if(J>=A.childNodes.length)A.appendChild(B);else{var G=A.childNodes[J];A.insertBefore(B,G)}}return!0},undo:function(a,b){b=b.slice();var c=this;b.length||(b=[b]),b.reverse(),b.forEach(function(b){c.undoDiff(a,b)})},undoDiff:function(a,q){q[p]===b?(q[p]=d,this.applyDiff(a,q)):q[p]===c?(R(q,r,s),this.applyDiff(a,q)):q[p]===d?(q[p]=b,this.applyDiff(a,q)):q[p]===e?(R(q,r,s),this.applyDiff(a,q)):q[p]===l?(R(q,r,s),this.applyDiff(a,q)):q[p]===o?(R(q,r,s),this.applyDiff(a,q)):q[p]===m?(R(q,r,s),this.applyDiff(a,q)):q[p]===n?(R(q,r,s),this.applyDiff(a,q)):q[p]===k?(R(q,r,s),this.applyDiff(a,q)):q[p]===f?(R(q,v,w),this.applyDiff(a,q)):q[p]===g?(q[p]=h,this.applyDiff(a,q)):q[p]===h?(q[p]=g,this.applyDiff(a,q)):q[p]===i?(q[p]=j,this.applyDiff(a,q)):q[p]===j&&(q[p]=i,this.applyDiff(a,q))}},"undefined"!=typeof exports?("undefined"!=typeof module&&module.exports&&(exports=module.exports=T),exports.diffDOM=T):this.diffDOM=T}.call(this);

    var dd = new diffDOM();
    // dd.textDiff = function (node, currentValue, expectedValue, newValue) {
    //     if (currentValue===expectedValue) {
    //         // The text node contains the text we expect it to contain, so we simple change the text of it to the new value.
    //         node.data = newValue;
    //     } else {
    //         // The text node currently does not contain what we expected it to contain, so we need to merge. 
    //         difference = TEXTDIFF(expectedValue, currentValue);
    //         node.data = TEXTPATCH(newValue, difference);
    //     }
    //     return true;
    // };
    Backbone.VirtualDomRenderer = function(){
        if(!this._dom)
            this._dom = document.createElement('div');
        if(!this._applyDiff)
            this._applyDiff = _.debounce(function(){
                this._dom.innerHTML = this.template(this.templateData());
                var diff = dd.diff(this.el, this._dom);
                if(diff.length > 0) {
                    dd.apply(this.el, diff);
                    this.trigger('virtualdomrenderer:rendered');
                }
            }, 17);
        if(!this.template)
            throw 'template method must be callable on view';
        if(!this.templateData)
            throw 'templateData method must be callable on view';
        this._applyDiff();
        return this;
    };
}).call(this);
/*!
	Autosize 3.0.15
	license: MIT
	http://www.jacklmoore.com/autosize
*/
(function (global, factory) {
	if (typeof define === 'function' && define.amd) {
		define(['exports', 'module'], factory);
	} else if (typeof exports !== 'undefined' && typeof module !== 'undefined') {
		factory(exports, module);
	} else {
		var mod = {
			exports: {}
		};
		factory(mod.exports, mod);
		global.autosize = mod.exports;
	}
})(this, function (exports, module) {
	'use strict';

	var set = typeof Set === 'function' ? new Set() : (function () {
		var list = [];

		return {
			has: function has(key) {
				return Boolean(list.indexOf(key) > -1);
			},
			add: function add(key) {
				list.push(key);
			},
			'delete': function _delete(key) {
				list.splice(list.indexOf(key), 1);
			} };
	})();

	var createEvent = function createEvent(name) {
		return new Event(name);
	};
	try {
		new Event('test');
	} catch (e) {
		// IE does not support `new Event()`
		createEvent = function (name) {
			var evt = document.createEvent('Event');
			evt.initEvent(name, true, false);
			return evt;
		};
	}

	function assign(ta) {
		var _ref = arguments[1] === undefined ? {} : arguments[1];

		var _ref$setOverflowX = _ref.setOverflowX;
		var setOverflowX = _ref$setOverflowX === undefined ? true : _ref$setOverflowX;
		var _ref$setOverflowY = _ref.setOverflowY;
		var setOverflowY = _ref$setOverflowY === undefined ? true : _ref$setOverflowY;

		if (!ta || !ta.nodeName || ta.nodeName !== 'TEXTAREA' || set.has(ta)) return;

		var heightOffset = null;
		var overflowY = null;
		var clientWidth = ta.clientWidth;

		function init() {
			var style = window.getComputedStyle(ta, null);

			overflowY = style.overflowY;

			if (style.resize === 'vertical') {
				ta.style.resize = 'none';
			} else if (style.resize === 'both') {
				ta.style.resize = 'horizontal';
			}

			if (style.boxSizing === 'content-box') {
				heightOffset = -(parseFloat(style.paddingTop) + parseFloat(style.paddingBottom));
			} else {
				heightOffset = parseFloat(style.borderTopWidth) + parseFloat(style.borderBottomWidth);
			}
			// Fix when a textarea is not on document body and heightOffset is Not a Number
			if (isNaN(heightOffset)) {
				heightOffset = 0;
			}

			update();
		}

		function changeOverflow(value) {
			{
				// Chrome/Safari-specific fix:
				// When the textarea y-overflow is hidden, Chrome/Safari do not reflow the text to account for the space
				// made available by removing the scrollbar. The following forces the necessary text reflow.
				var width = ta.style.width;
				ta.style.width = '0px';
				// Force reflow:
				/* jshint ignore:start */
				ta.offsetWidth;
				/* jshint ignore:end */
				ta.style.width = width;
			}

			overflowY = value;

			if (setOverflowY) {
				ta.style.overflowY = value;
			}

			resize();
		}

		function resize() {
			var htmlTop = window.pageYOffset;
			var bodyTop = document.body.scrollTop;
			var originalHeight = ta.style.height;

			ta.style.height = 'auto';

			var endHeight = ta.scrollHeight + heightOffset;

			if (ta.scrollHeight === 0) {
				// If the scrollHeight is 0, then the element probably has display:none or is detached from the DOM.
				ta.style.height = originalHeight;
				return;
			}

			ta.style.height = endHeight + 'px';

			// used to check if an update is actually necessary on window.resize
			clientWidth = ta.clientWidth;

			// prevents scroll-position jumping
			document.documentElement.scrollTop = htmlTop;
			document.body.scrollTop = bodyTop;
		}

		function update() {
			var startHeight = ta.style.height;

			resize();

			var style = window.getComputedStyle(ta, null);

			if (style.height !== ta.style.height) {
				if (overflowY !== 'visible') {
					changeOverflow('visible');
				}
			} else {
				if (overflowY !== 'hidden') {
					changeOverflow('hidden');
				}
			}

			if (startHeight !== ta.style.height) {
				var evt = createEvent('autosize:resized');
				ta.dispatchEvent(evt);
			}
		}

		var pageResize = function pageResize() {
			if (ta.clientWidth !== clientWidth) {
				update();
			}
		};

		var destroy = (function (style) {
			window.removeEventListener('resize', pageResize, false);
			ta.removeEventListener('input', update, false);
			ta.removeEventListener('keyup', update, false);
			ta.removeEventListener('autosize:destroy', destroy, false);
			ta.removeEventListener('autosize:update', update, false);
			set['delete'](ta);

			Object.keys(style).forEach(function (key) {
				ta.style[key] = style[key];
			});
		}).bind(ta, {
			height: ta.style.height,
			resize: ta.style.resize,
			overflowY: ta.style.overflowY,
			overflowX: ta.style.overflowX,
			wordWrap: ta.style.wordWrap });

		ta.addEventListener('autosize:destroy', destroy, false);

		// IE9 does not fire onpropertychange or oninput for deletions,
		// so binding to onkeyup to catch most of those events.
		// There is no way that I know of to detect something like 'cut' in IE9.
		if ('onpropertychange' in ta && 'oninput' in ta) {
			ta.addEventListener('keyup', update, false);
		}

		window.addEventListener('resize', pageResize, false);
		ta.addEventListener('input', update, false);
		ta.addEventListener('autosize:update', update, false);
		set.add(ta);

		if (setOverflowX) {
			ta.style.overflowX = 'hidden';
			ta.style.wordWrap = 'break-word';
		}

		init();
	}

	function destroy(ta) {
		if (!(ta && ta.nodeName && ta.nodeName === 'TEXTAREA')) return;
		var evt = createEvent('autosize:destroy');
		ta.dispatchEvent(evt);
	}

	function update(ta) {
		if (!(ta && ta.nodeName && ta.nodeName === 'TEXTAREA')) return;
		var evt = createEvent('autosize:update');
		ta.dispatchEvent(evt);
	}

	var autosize = null;

	// Do nothing in Node.js environment and IE8 (or lower)
	if (typeof window === 'undefined' || typeof window.getComputedStyle !== 'function') {
		autosize = function (el) {
			return el;
		};
		autosize.destroy = function (el) {
			return el;
		};
		autosize.update = function (el) {
			return el;
		};
	} else {
		autosize = function (el, options) {
			if (el) {
				Array.prototype.forEach.call(el.length ? el : [el], function (x) {
					return assign(x, options);
				});
			}
			return el;
		};
		autosize.destroy = function (el) {
			if (el) {
				Array.prototype.forEach.call(el.length ? el : [el], destroy);
			}
			return el;
		};
		autosize.update = function (el) {
			if (el) {
				Array.prototype.forEach.call(el.length ? el : [el], update);
			}
			return el;
		};
	}

	module.exports = autosize;
});
//! moment.js
//! version : 2.7.0
//! authors : Tim Wood, Iskren Chernev, Moment.js contributors
//! license : MIT
//! momentjs.com
(function(a){function b(a,b,c){switch(arguments.length){case 2:return null!=a?a:b;case 3:return null!=a?a:null!=b?b:c;default:throw new Error("Implement me")}}function c(){return{empty:!1,unusedTokens:[],unusedInput:[],overflow:-2,charsLeftOver:0,nullInput:!1,invalidMonth:null,invalidFormat:!1,userInvalidated:!1,iso:!1}}function d(a,b){function c(){mb.suppressDeprecationWarnings===!1&&"undefined"!=typeof console&&console.warn&&console.warn("Deprecation warning: "+a)}var d=!0;return j(function(){return d&&(c(),d=!1),b.apply(this,arguments)},b)}function e(a,b){return function(c){return m(a.call(this,c),b)}}function f(a,b){return function(c){return this.lang().ordinal(a.call(this,c),b)}}function g(){}function h(a){z(a),j(this,a)}function i(a){var b=s(a),c=b.year||0,d=b.quarter||0,e=b.month||0,f=b.week||0,g=b.day||0,h=b.hour||0,i=b.minute||0,j=b.second||0,k=b.millisecond||0;this._milliseconds=+k+1e3*j+6e4*i+36e5*h,this._days=+g+7*f,this._months=+e+3*d+12*c,this._data={},this._bubble()}function j(a,b){for(var c in b)b.hasOwnProperty(c)&&(a[c]=b[c]);return b.hasOwnProperty("toString")&&(a.toString=b.toString),b.hasOwnProperty("valueOf")&&(a.valueOf=b.valueOf),a}function k(a){var b,c={};for(b in a)a.hasOwnProperty(b)&&Ab.hasOwnProperty(b)&&(c[b]=a[b]);return c}function l(a){return 0>a?Math.ceil(a):Math.floor(a)}function m(a,b,c){for(var d=""+Math.abs(a),e=a>=0;d.length<b;)d="0"+d;return(e?c?"+":"":"-")+d}function n(a,b,c,d){var e=b._milliseconds,f=b._days,g=b._months;d=null==d?!0:d,e&&a._d.setTime(+a._d+e*c),f&&hb(a,"Date",gb(a,"Date")+f*c),g&&fb(a,gb(a,"Month")+g*c),d&&mb.updateOffset(a,f||g)}function o(a){return"[object Array]"===Object.prototype.toString.call(a)}function p(a){return"[object Date]"===Object.prototype.toString.call(a)||a instanceof Date}function q(a,b,c){var d,e=Math.min(a.length,b.length),f=Math.abs(a.length-b.length),g=0;for(d=0;e>d;d++)(c&&a[d]!==b[d]||!c&&u(a[d])!==u(b[d]))&&g++;return g+f}function r(a){if(a){var b=a.toLowerCase().replace(/(.)s$/,"$1");a=bc[a]||cc[b]||b}return a}function s(a){var b,c,d={};for(c in a)a.hasOwnProperty(c)&&(b=r(c),b&&(d[b]=a[c]));return d}function t(b){var c,d;if(0===b.indexOf("week"))c=7,d="day";else{if(0!==b.indexOf("month"))return;c=12,d="month"}mb[b]=function(e,f){var g,h,i=mb.fn._lang[b],j=[];if("number"==typeof e&&(f=e,e=a),h=function(a){var b=mb().utc().set(d,a);return i.call(mb.fn._lang,b,e||"")},null!=f)return h(f);for(g=0;c>g;g++)j.push(h(g));return j}}function u(a){var b=+a,c=0;return 0!==b&&isFinite(b)&&(c=b>=0?Math.floor(b):Math.ceil(b)),c}function v(a,b){return new Date(Date.UTC(a,b+1,0)).getUTCDate()}function w(a,b,c){return bb(mb([a,11,31+b-c]),b,c).week}function x(a){return y(a)?366:365}function y(a){return a%4===0&&a%100!==0||a%400===0}function z(a){var b;a._a&&-2===a._pf.overflow&&(b=a._a[tb]<0||a._a[tb]>11?tb:a._a[ub]<1||a._a[ub]>v(a._a[sb],a._a[tb])?ub:a._a[vb]<0||a._a[vb]>23?vb:a._a[wb]<0||a._a[wb]>59?wb:a._a[xb]<0||a._a[xb]>59?xb:a._a[yb]<0||a._a[yb]>999?yb:-1,a._pf._overflowDayOfYear&&(sb>b||b>ub)&&(b=ub),a._pf.overflow=b)}function A(a){return null==a._isValid&&(a._isValid=!isNaN(a._d.getTime())&&a._pf.overflow<0&&!a._pf.empty&&!a._pf.invalidMonth&&!a._pf.nullInput&&!a._pf.invalidFormat&&!a._pf.userInvalidated,a._strict&&(a._isValid=a._isValid&&0===a._pf.charsLeftOver&&0===a._pf.unusedTokens.length)),a._isValid}function B(a){return a?a.toLowerCase().replace("_","-"):a}function C(a,b){return b._isUTC?mb(a).zone(b._offset||0):mb(a).local()}function D(a,b){return b.abbr=a,zb[a]||(zb[a]=new g),zb[a].set(b),zb[a]}function E(a){delete zb[a]}function F(a){var b,c,d,e,f=0,g=function(a){if(!zb[a]&&Bb)try{require("./lang/"+a)}catch(b){}return zb[a]};if(!a)return mb.fn._lang;if(!o(a)){if(c=g(a))return c;a=[a]}for(;f<a.length;){for(e=B(a[f]).split("-"),b=e.length,d=B(a[f+1]),d=d?d.split("-"):null;b>0;){if(c=g(e.slice(0,b).join("-")))return c;if(d&&d.length>=b&&q(e,d,!0)>=b-1)break;b--}f++}return mb.fn._lang}function G(a){return a.match(/\[[\s\S]/)?a.replace(/^\[|\]$/g,""):a.replace(/\\/g,"")}function H(a){var b,c,d=a.match(Fb);for(b=0,c=d.length;c>b;b++)d[b]=hc[d[b]]?hc[d[b]]:G(d[b]);return function(e){var f="";for(b=0;c>b;b++)f+=d[b]instanceof Function?d[b].call(e,a):d[b];return f}}function I(a,b){return a.isValid()?(b=J(b,a.lang()),dc[b]||(dc[b]=H(b)),dc[b](a)):a.lang().invalidDate()}function J(a,b){function c(a){return b.longDateFormat(a)||a}var d=5;for(Gb.lastIndex=0;d>=0&&Gb.test(a);)a=a.replace(Gb,c),Gb.lastIndex=0,d-=1;return a}function K(a,b){var c,d=b._strict;switch(a){case"Q":return Rb;case"DDDD":return Tb;case"YYYY":case"GGGG":case"gggg":return d?Ub:Jb;case"Y":case"G":case"g":return Wb;case"YYYYYY":case"YYYYY":case"GGGGG":case"ggggg":return d?Vb:Kb;case"S":if(d)return Rb;case"SS":if(d)return Sb;case"SSS":if(d)return Tb;case"DDD":return Ib;case"MMM":case"MMMM":case"dd":case"ddd":case"dddd":return Mb;case"a":case"A":return F(b._l)._meridiemParse;case"X":return Pb;case"Z":case"ZZ":return Nb;case"T":return Ob;case"SSSS":return Lb;case"MM":case"DD":case"YY":case"GG":case"gg":case"HH":case"hh":case"mm":case"ss":case"ww":case"WW":return d?Sb:Hb;case"M":case"D":case"d":case"H":case"h":case"m":case"s":case"w":case"W":case"e":case"E":return Hb;case"Do":return Qb;default:return c=new RegExp(T(S(a.replace("\\","")),"i"))}}function L(a){a=a||"";var b=a.match(Nb)||[],c=b[b.length-1]||[],d=(c+"").match(_b)||["-",0,0],e=+(60*d[1])+u(d[2]);return"+"===d[0]?-e:e}function M(a,b,c){var d,e=c._a;switch(a){case"Q":null!=b&&(e[tb]=3*(u(b)-1));break;case"M":case"MM":null!=b&&(e[tb]=u(b)-1);break;case"MMM":case"MMMM":d=F(c._l).monthsParse(b),null!=d?e[tb]=d:c._pf.invalidMonth=b;break;case"D":case"DD":null!=b&&(e[ub]=u(b));break;case"Do":null!=b&&(e[ub]=u(parseInt(b,10)));break;case"DDD":case"DDDD":null!=b&&(c._dayOfYear=u(b));break;case"YY":e[sb]=mb.parseTwoDigitYear(b);break;case"YYYY":case"YYYYY":case"YYYYYY":e[sb]=u(b);break;case"a":case"A":c._isPm=F(c._l).isPM(b);break;case"H":case"HH":case"h":case"hh":e[vb]=u(b);break;case"m":case"mm":e[wb]=u(b);break;case"s":case"ss":e[xb]=u(b);break;case"S":case"SS":case"SSS":case"SSSS":e[yb]=u(1e3*("0."+b));break;case"X":c._d=new Date(1e3*parseFloat(b));break;case"Z":case"ZZ":c._useUTC=!0,c._tzm=L(b);break;case"dd":case"ddd":case"dddd":d=F(c._l).weekdaysParse(b),null!=d?(c._w=c._w||{},c._w.d=d):c._pf.invalidWeekday=b;break;case"w":case"ww":case"W":case"WW":case"d":case"e":case"E":a=a.substr(0,1);case"gggg":case"GGGG":case"GGGGG":a=a.substr(0,2),b&&(c._w=c._w||{},c._w[a]=u(b));break;case"gg":case"GG":c._w=c._w||{},c._w[a]=mb.parseTwoDigitYear(b)}}function N(a){var c,d,e,f,g,h,i,j;c=a._w,null!=c.GG||null!=c.W||null!=c.E?(g=1,h=4,d=b(c.GG,a._a[sb],bb(mb(),1,4).year),e=b(c.W,1),f=b(c.E,1)):(j=F(a._l),g=j._week.dow,h=j._week.doy,d=b(c.gg,a._a[sb],bb(mb(),g,h).year),e=b(c.w,1),null!=c.d?(f=c.d,g>f&&++e):f=null!=c.e?c.e+g:g),i=cb(d,e,f,h,g),a._a[sb]=i.year,a._dayOfYear=i.dayOfYear}function O(a){var c,d,e,f,g=[];if(!a._d){for(e=Q(a),a._w&&null==a._a[ub]&&null==a._a[tb]&&N(a),a._dayOfYear&&(f=b(a._a[sb],e[sb]),a._dayOfYear>x(f)&&(a._pf._overflowDayOfYear=!0),d=Z(f,0,a._dayOfYear),a._a[tb]=d.getUTCMonth(),a._a[ub]=d.getUTCDate()),c=0;3>c&&null==a._a[c];++c)a._a[c]=g[c]=e[c];for(;7>c;c++)a._a[c]=g[c]=null==a._a[c]?2===c?1:0:a._a[c];a._d=(a._useUTC?Z:Y).apply(null,g),null!=a._tzm&&a._d.setUTCMinutes(a._d.getUTCMinutes()+a._tzm)}}function P(a){var b;a._d||(b=s(a._i),a._a=[b.year,b.month,b.day,b.hour,b.minute,b.second,b.millisecond],O(a))}function Q(a){var b=new Date;return a._useUTC?[b.getUTCFullYear(),b.getUTCMonth(),b.getUTCDate()]:[b.getFullYear(),b.getMonth(),b.getDate()]}function R(a){if(a._f===mb.ISO_8601)return void V(a);a._a=[],a._pf.empty=!0;var b,c,d,e,f,g=F(a._l),h=""+a._i,i=h.length,j=0;for(d=J(a._f,g).match(Fb)||[],b=0;b<d.length;b++)e=d[b],c=(h.match(K(e,a))||[])[0],c&&(f=h.substr(0,h.indexOf(c)),f.length>0&&a._pf.unusedInput.push(f),h=h.slice(h.indexOf(c)+c.length),j+=c.length),hc[e]?(c?a._pf.empty=!1:a._pf.unusedTokens.push(e),M(e,c,a)):a._strict&&!c&&a._pf.unusedTokens.push(e);a._pf.charsLeftOver=i-j,h.length>0&&a._pf.unusedInput.push(h),a._isPm&&a._a[vb]<12&&(a._a[vb]+=12),a._isPm===!1&&12===a._a[vb]&&(a._a[vb]=0),O(a),z(a)}function S(a){return a.replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g,function(a,b,c,d,e){return b||c||d||e})}function T(a){return a.replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&")}function U(a){var b,d,e,f,g;if(0===a._f.length)return a._pf.invalidFormat=!0,void(a._d=new Date(0/0));for(f=0;f<a._f.length;f++)g=0,b=j({},a),b._pf=c(),b._f=a._f[f],R(b),A(b)&&(g+=b._pf.charsLeftOver,g+=10*b._pf.unusedTokens.length,b._pf.score=g,(null==e||e>g)&&(e=g,d=b));j(a,d||b)}function V(a){var b,c,d=a._i,e=Xb.exec(d);if(e){for(a._pf.iso=!0,b=0,c=Zb.length;c>b;b++)if(Zb[b][1].exec(d)){a._f=Zb[b][0]+(e[6]||" ");break}for(b=0,c=$b.length;c>b;b++)if($b[b][1].exec(d)){a._f+=$b[b][0];break}d.match(Nb)&&(a._f+="Z"),R(a)}else a._isValid=!1}function W(a){V(a),a._isValid===!1&&(delete a._isValid,mb.createFromInputFallback(a))}function X(b){var c=b._i,d=Cb.exec(c);c===a?b._d=new Date:d?b._d=new Date(+d[1]):"string"==typeof c?W(b):o(c)?(b._a=c.slice(0),O(b)):p(c)?b._d=new Date(+c):"object"==typeof c?P(b):"number"==typeof c?b._d=new Date(c):mb.createFromInputFallback(b)}function Y(a,b,c,d,e,f,g){var h=new Date(a,b,c,d,e,f,g);return 1970>a&&h.setFullYear(a),h}function Z(a){var b=new Date(Date.UTC.apply(null,arguments));return 1970>a&&b.setUTCFullYear(a),b}function $(a,b){if("string"==typeof a)if(isNaN(a)){if(a=b.weekdaysParse(a),"number"!=typeof a)return null}else a=parseInt(a,10);return a}function _(a,b,c,d,e){return e.relativeTime(b||1,!!c,a,d)}function ab(a,b,c){var d=rb(Math.abs(a)/1e3),e=rb(d/60),f=rb(e/60),g=rb(f/24),h=rb(g/365),i=d<ec.s&&["s",d]||1===e&&["m"]||e<ec.m&&["mm",e]||1===f&&["h"]||f<ec.h&&["hh",f]||1===g&&["d"]||g<=ec.dd&&["dd",g]||g<=ec.dm&&["M"]||g<ec.dy&&["MM",rb(g/30)]||1===h&&["y"]||["yy",h];return i[2]=b,i[3]=a>0,i[4]=c,_.apply({},i)}function bb(a,b,c){var d,e=c-b,f=c-a.day();return f>e&&(f-=7),e-7>f&&(f+=7),d=mb(a).add("d",f),{week:Math.ceil(d.dayOfYear()/7),year:d.year()}}function cb(a,b,c,d,e){var f,g,h=Z(a,0,1).getUTCDay();return h=0===h?7:h,c=null!=c?c:e,f=e-h+(h>d?7:0)-(e>h?7:0),g=7*(b-1)+(c-e)+f+1,{year:g>0?a:a-1,dayOfYear:g>0?g:x(a-1)+g}}function db(b){var c=b._i,d=b._f;return null===c||d===a&&""===c?mb.invalid({nullInput:!0}):("string"==typeof c&&(b._i=c=F().preparse(c)),mb.isMoment(c)?(b=k(c),b._d=new Date(+c._d)):d?o(d)?U(b):R(b):X(b),new h(b))}function eb(a,b){var c,d;if(1===b.length&&o(b[0])&&(b=b[0]),!b.length)return mb();for(c=b[0],d=1;d<b.length;++d)b[d][a](c)&&(c=b[d]);return c}function fb(a,b){var c;return"string"==typeof b&&(b=a.lang().monthsParse(b),"number"!=typeof b)?a:(c=Math.min(a.date(),v(a.year(),b)),a._d["set"+(a._isUTC?"UTC":"")+"Month"](b,c),a)}function gb(a,b){return a._d["get"+(a._isUTC?"UTC":"")+b]()}function hb(a,b,c){return"Month"===b?fb(a,c):a._d["set"+(a._isUTC?"UTC":"")+b](c)}function ib(a,b){return function(c){return null!=c?(hb(this,a,c),mb.updateOffset(this,b),this):gb(this,a)}}function jb(a){mb.duration.fn[a]=function(){return this._data[a]}}function kb(a,b){mb.duration.fn["as"+a]=function(){return+this/b}}function lb(a){"undefined"==typeof ender&&(nb=qb.moment,qb.moment=a?d("Accessing Moment through the global scope is deprecated, and will be removed in an upcoming release.",mb):mb)}for(var mb,nb,ob,pb="2.7.0",qb="undefined"!=typeof global?global:this,rb=Math.round,sb=0,tb=1,ub=2,vb=3,wb=4,xb=5,yb=6,zb={},Ab={_isAMomentObject:null,_i:null,_f:null,_l:null,_strict:null,_tzm:null,_isUTC:null,_offset:null,_pf:null,_lang:null},Bb="undefined"!=typeof module&&module.exports,Cb=/^\/?Date\((\-?\d+)/i,Db=/(\-)?(?:(\d*)\.)?(\d+)\:(\d+)(?:\:(\d+)\.?(\d{3})?)?/,Eb=/^(-)?P(?:(?:([0-9,.]*)Y)?(?:([0-9,.]*)M)?(?:([0-9,.]*)D)?(?:T(?:([0-9,.]*)H)?(?:([0-9,.]*)M)?(?:([0-9,.]*)S)?)?|([0-9,.]*)W)$/,Fb=/(\[[^\[]*\])|(\\)?(Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Q|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|mm?|ss?|S{1,4}|X|zz?|ZZ?|.)/g,Gb=/(\[[^\[]*\])|(\\)?(LT|LL?L?L?|l{1,4})/g,Hb=/\d\d?/,Ib=/\d{1,3}/,Jb=/\d{1,4}/,Kb=/[+\-]?\d{1,6}/,Lb=/\d+/,Mb=/[0-9]*['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+|[\u0600-\u06FF\/]+(\s*?[\u0600-\u06FF]+){1,2}/i,Nb=/Z|[\+\-]\d\d:?\d\d/gi,Ob=/T/i,Pb=/[\+\-]?\d+(\.\d{1,3})?/,Qb=/\d{1,2}/,Rb=/\d/,Sb=/\d\d/,Tb=/\d{3}/,Ub=/\d{4}/,Vb=/[+-]?\d{6}/,Wb=/[+-]?\d+/,Xb=/^\s*(?:[+-]\d{6}|\d{4})-(?:(\d\d-\d\d)|(W\d\d$)|(W\d\d-\d)|(\d\d\d))((T| )(\d\d(:\d\d(:\d\d(\.\d+)?)?)?)?([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/,Yb="YYYY-MM-DDTHH:mm:ssZ",Zb=[["YYYYYY-MM-DD",/[+-]\d{6}-\d{2}-\d{2}/],["YYYY-MM-DD",/\d{4}-\d{2}-\d{2}/],["GGGG-[W]WW-E",/\d{4}-W\d{2}-\d/],["GGGG-[W]WW",/\d{4}-W\d{2}/],["YYYY-DDD",/\d{4}-\d{3}/]],$b=[["HH:mm:ss.SSSS",/(T| )\d\d:\d\d:\d\d\.\d+/],["HH:mm:ss",/(T| )\d\d:\d\d:\d\d/],["HH:mm",/(T| )\d\d:\d\d/],["HH",/(T| )\d\d/]],_b=/([\+\-]|\d\d)/gi,ac=("Date|Hours|Minutes|Seconds|Milliseconds".split("|"),{Milliseconds:1,Seconds:1e3,Minutes:6e4,Hours:36e5,Days:864e5,Months:2592e6,Years:31536e6}),bc={ms:"millisecond",s:"second",m:"minute",h:"hour",d:"day",D:"date",w:"week",W:"isoWeek",M:"month",Q:"quarter",y:"year",DDD:"dayOfYear",e:"weekday",E:"isoWeekday",gg:"weekYear",GG:"isoWeekYear"},cc={dayofyear:"dayOfYear",isoweekday:"isoWeekday",isoweek:"isoWeek",weekyear:"weekYear",isoweekyear:"isoWeekYear"},dc={},ec={s:45,m:45,h:22,dd:25,dm:45,dy:345},fc="DDD w W M D d".split(" "),gc="M D H h m s w W".split(" "),hc={M:function(){return this.month()+1},MMM:function(a){return this.lang().monthsShort(this,a)},MMMM:function(a){return this.lang().months(this,a)},D:function(){return this.date()},DDD:function(){return this.dayOfYear()},d:function(){return this.day()},dd:function(a){return this.lang().weekdaysMin(this,a)},ddd:function(a){return this.lang().weekdaysShort(this,a)},dddd:function(a){return this.lang().weekdays(this,a)},w:function(){return this.week()},W:function(){return this.isoWeek()},YY:function(){return m(this.year()%100,2)},YYYY:function(){return m(this.year(),4)},YYYYY:function(){return m(this.year(),5)},YYYYYY:function(){var a=this.year(),b=a>=0?"+":"-";return b+m(Math.abs(a),6)},gg:function(){return m(this.weekYear()%100,2)},gggg:function(){return m(this.weekYear(),4)},ggggg:function(){return m(this.weekYear(),5)},GG:function(){return m(this.isoWeekYear()%100,2)},GGGG:function(){return m(this.isoWeekYear(),4)},GGGGG:function(){return m(this.isoWeekYear(),5)},e:function(){return this.weekday()},E:function(){return this.isoWeekday()},a:function(){return this.lang().meridiem(this.hours(),this.minutes(),!0)},A:function(){return this.lang().meridiem(this.hours(),this.minutes(),!1)},H:function(){return this.hours()},h:function(){return this.hours()%12||12},m:function(){return this.minutes()},s:function(){return this.seconds()},S:function(){return u(this.milliseconds()/100)},SS:function(){return m(u(this.milliseconds()/10),2)},SSS:function(){return m(this.milliseconds(),3)},SSSS:function(){return m(this.milliseconds(),3)},Z:function(){var a=-this.zone(),b="+";return 0>a&&(a=-a,b="-"),b+m(u(a/60),2)+":"+m(u(a)%60,2)},ZZ:function(){var a=-this.zone(),b="+";return 0>a&&(a=-a,b="-"),b+m(u(a/60),2)+m(u(a)%60,2)},z:function(){return this.zoneAbbr()},zz:function(){return this.zoneName()},X:function(){return this.unix()},Q:function(){return this.quarter()}},ic=["months","monthsShort","weekdays","weekdaysShort","weekdaysMin"];fc.length;)ob=fc.pop(),hc[ob+"o"]=f(hc[ob],ob);for(;gc.length;)ob=gc.pop(),hc[ob+ob]=e(hc[ob],2);for(hc.DDDD=e(hc.DDD,3),j(g.prototype,{set:function(a){var b,c;for(c in a)b=a[c],"function"==typeof b?this[c]=b:this["_"+c]=b},_months:"January_February_March_April_May_June_July_August_September_October_November_December".split("_"),months:function(a){return this._months[a.month()]},_monthsShort:"Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),monthsShort:function(a){return this._monthsShort[a.month()]},monthsParse:function(a){var b,c,d;for(this._monthsParse||(this._monthsParse=[]),b=0;12>b;b++)if(this._monthsParse[b]||(c=mb.utc([2e3,b]),d="^"+this.months(c,"")+"|^"+this.monthsShort(c,""),this._monthsParse[b]=new RegExp(d.replace(".",""),"i")),this._monthsParse[b].test(a))return b},_weekdays:"Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),weekdays:function(a){return this._weekdays[a.day()]},_weekdaysShort:"Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),weekdaysShort:function(a){return this._weekdaysShort[a.day()]},_weekdaysMin:"Su_Mo_Tu_We_Th_Fr_Sa".split("_"),weekdaysMin:function(a){return this._weekdaysMin[a.day()]},weekdaysParse:function(a){var b,c,d;for(this._weekdaysParse||(this._weekdaysParse=[]),b=0;7>b;b++)if(this._weekdaysParse[b]||(c=mb([2e3,1]).day(b),d="^"+this.weekdays(c,"")+"|^"+this.weekdaysShort(c,"")+"|^"+this.weekdaysMin(c,""),this._weekdaysParse[b]=new RegExp(d.replace(".",""),"i")),this._weekdaysParse[b].test(a))return b},_longDateFormat:{LT:"h:mm A",L:"MM/DD/YYYY",LL:"MMMM D YYYY",LLL:"MMMM D YYYY LT",LLLL:"dddd, MMMM D YYYY LT"},longDateFormat:function(a){var b=this._longDateFormat[a];return!b&&this._longDateFormat[a.toUpperCase()]&&(b=this._longDateFormat[a.toUpperCase()].replace(/MMMM|MM|DD|dddd/g,function(a){return a.slice(1)}),this._longDateFormat[a]=b),b},isPM:function(a){return"p"===(a+"").toLowerCase().charAt(0)},_meridiemParse:/[ap]\.?m?\.?/i,meridiem:function(a,b,c){return a>11?c?"pm":"PM":c?"am":"AM"},_calendar:{sameDay:"[Today at] LT",nextDay:"[Tomorrow at] LT",nextWeek:"dddd [at] LT",lastDay:"[Yesterday at] LT",lastWeek:"[Last] dddd [at] LT",sameElse:"L"},calendar:function(a,b){var c=this._calendar[a];return"function"==typeof c?c.apply(b):c},_relativeTime:{future:"in %s",past:"%s ago",s:"a few seconds",m:"a minute",mm:"%d minutes",h:"an hour",hh:"%d hours",d:"a day",dd:"%d days",M:"a month",MM:"%d months",y:"a year",yy:"%d years"},relativeTime:function(a,b,c,d){var e=this._relativeTime[c];return"function"==typeof e?e(a,b,c,d):e.replace(/%d/i,a)},pastFuture:function(a,b){var c=this._relativeTime[a>0?"future":"past"];return"function"==typeof c?c(b):c.replace(/%s/i,b)},ordinal:function(a){return this._ordinal.replace("%d",a)},_ordinal:"%d",preparse:function(a){return a},postformat:function(a){return a},week:function(a){return bb(a,this._week.dow,this._week.doy).week},_week:{dow:0,doy:6},_invalidDate:"Invalid date",invalidDate:function(){return this._invalidDate}}),mb=function(b,d,e,f){var g;return"boolean"==typeof e&&(f=e,e=a),g={},g._isAMomentObject=!0,g._i=b,g._f=d,g._l=e,g._strict=f,g._isUTC=!1,g._pf=c(),db(g)},mb.suppressDeprecationWarnings=!1,mb.createFromInputFallback=d("moment construction falls back to js Date. This is discouraged and will be removed in upcoming major release. Please refer to https://github.com/moment/moment/issues/1407 for more info.",function(a){a._d=new Date(a._i)}),mb.min=function(){var a=[].slice.call(arguments,0);return eb("isBefore",a)},mb.max=function(){var a=[].slice.call(arguments,0);return eb("isAfter",a)},mb.utc=function(b,d,e,f){var g;return"boolean"==typeof e&&(f=e,e=a),g={},g._isAMomentObject=!0,g._useUTC=!0,g._isUTC=!0,g._l=e,g._i=b,g._f=d,g._strict=f,g._pf=c(),db(g).utc()},mb.unix=function(a){return mb(1e3*a)},mb.duration=function(a,b){var c,d,e,f=a,g=null;return mb.isDuration(a)?f={ms:a._milliseconds,d:a._days,M:a._months}:"number"==typeof a?(f={},b?f[b]=a:f.milliseconds=a):(g=Db.exec(a))?(c="-"===g[1]?-1:1,f={y:0,d:u(g[ub])*c,h:u(g[vb])*c,m:u(g[wb])*c,s:u(g[xb])*c,ms:u(g[yb])*c}):(g=Eb.exec(a))&&(c="-"===g[1]?-1:1,e=function(a){var b=a&&parseFloat(a.replace(",","."));return(isNaN(b)?0:b)*c},f={y:e(g[2]),M:e(g[3]),d:e(g[4]),h:e(g[5]),m:e(g[6]),s:e(g[7]),w:e(g[8])}),d=new i(f),mb.isDuration(a)&&a.hasOwnProperty("_lang")&&(d._lang=a._lang),d},mb.version=pb,mb.defaultFormat=Yb,mb.ISO_8601=function(){},mb.momentProperties=Ab,mb.updateOffset=function(){},mb.relativeTimeThreshold=function(b,c){return ec[b]===a?!1:(ec[b]=c,!0)},mb.lang=function(a,b){var c;return a?(b?D(B(a),b):null===b?(E(a),a="en"):zb[a]||F(a),c=mb.duration.fn._lang=mb.fn._lang=F(a),c._abbr):mb.fn._lang._abbr},mb.langData=function(a){return a&&a._lang&&a._lang._abbr&&(a=a._lang._abbr),F(a)},mb.isMoment=function(a){return a instanceof h||null!=a&&a.hasOwnProperty("_isAMomentObject")},mb.isDuration=function(a){return a instanceof i},ob=ic.length-1;ob>=0;--ob)t(ic[ob]);mb.normalizeUnits=function(a){return r(a)},mb.invalid=function(a){var b=mb.utc(0/0);return null!=a?j(b._pf,a):b._pf.userInvalidated=!0,b},mb.parseZone=function(){return mb.apply(null,arguments).parseZone()},mb.parseTwoDigitYear=function(a){return u(a)+(u(a)>68?1900:2e3)},j(mb.fn=h.prototype,{clone:function(){return mb(this)},valueOf:function(){return+this._d+6e4*(this._offset||0)},unix:function(){return Math.floor(+this/1e3)},toString:function(){return this.clone().lang("en").format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ")},toDate:function(){return this._offset?new Date(+this):this._d},toISOString:function(){var a=mb(this).utc();return 0<a.year()&&a.year()<=9999?I(a,"YYYY-MM-DD[T]HH:mm:ss.SSS[Z]"):I(a,"YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]")},toArray:function(){var a=this;return[a.year(),a.month(),a.date(),a.hours(),a.minutes(),a.seconds(),a.milliseconds()]},isValid:function(){return A(this)},isDSTShifted:function(){return this._a?this.isValid()&&q(this._a,(this._isUTC?mb.utc(this._a):mb(this._a)).toArray())>0:!1},parsingFlags:function(){return j({},this._pf)},invalidAt:function(){return this._pf.overflow},utc:function(){return this.zone(0)},local:function(){return this.zone(0),this._isUTC=!1,this},format:function(a){var b=I(this,a||mb.defaultFormat);return this.lang().postformat(b)},add:function(a,b){var c;return c="string"==typeof a&&"string"==typeof b?mb.duration(isNaN(+b)?+a:+b,isNaN(+b)?b:a):"string"==typeof a?mb.duration(+b,a):mb.duration(a,b),n(this,c,1),this},subtract:function(a,b){var c;return c="string"==typeof a&&"string"==typeof b?mb.duration(isNaN(+b)?+a:+b,isNaN(+b)?b:a):"string"==typeof a?mb.duration(+b,a):mb.duration(a,b),n(this,c,-1),this},diff:function(a,b,c){var d,e,f=C(a,this),g=6e4*(this.zone()-f.zone());return b=r(b),"year"===b||"month"===b?(d=432e5*(this.daysInMonth()+f.daysInMonth()),e=12*(this.year()-f.year())+(this.month()-f.month()),e+=(this-mb(this).startOf("month")-(f-mb(f).startOf("month")))/d,e-=6e4*(this.zone()-mb(this).startOf("month").zone()-(f.zone()-mb(f).startOf("month").zone()))/d,"year"===b&&(e/=12)):(d=this-f,e="second"===b?d/1e3:"minute"===b?d/6e4:"hour"===b?d/36e5:"day"===b?(d-g)/864e5:"week"===b?(d-g)/6048e5:d),c?e:l(e)},from:function(a,b){return mb.duration(this.diff(a)).lang(this.lang()._abbr).humanize(!b)},fromNow:function(a){return this.from(mb(),a)},calendar:function(a){var b=a||mb(),c=C(b,this).startOf("day"),d=this.diff(c,"days",!0),e=-6>d?"sameElse":-1>d?"lastWeek":0>d?"lastDay":1>d?"sameDay":2>d?"nextDay":7>d?"nextWeek":"sameElse";return this.format(this.lang().calendar(e,this))},isLeapYear:function(){return y(this.year())},isDST:function(){return this.zone()<this.clone().month(0).zone()||this.zone()<this.clone().month(5).zone()},day:function(a){var b=this._isUTC?this._d.getUTCDay():this._d.getDay();return null!=a?(a=$(a,this.lang()),this.add({d:a-b})):b},month:ib("Month",!0),startOf:function(a){switch(a=r(a)){case"year":this.month(0);case"quarter":case"month":this.date(1);case"week":case"isoWeek":case"day":this.hours(0);case"hour":this.minutes(0);case"minute":this.seconds(0);case"second":this.milliseconds(0)}return"week"===a?this.weekday(0):"isoWeek"===a&&this.isoWeekday(1),"quarter"===a&&this.month(3*Math.floor(this.month()/3)),this},endOf:function(a){return a=r(a),this.startOf(a).add("isoWeek"===a?"week":a,1).subtract("ms",1)},isAfter:function(a,b){return b="undefined"!=typeof b?b:"millisecond",+this.clone().startOf(b)>+mb(a).startOf(b)},isBefore:function(a,b){return b="undefined"!=typeof b?b:"millisecond",+this.clone().startOf(b)<+mb(a).startOf(b)},isSame:function(a,b){return b=b||"ms",+this.clone().startOf(b)===+C(a,this).startOf(b)},min:d("moment().min is deprecated, use moment.min instead. https://github.com/moment/moment/issues/1548",function(a){return a=mb.apply(null,arguments),this>a?this:a}),max:d("moment().max is deprecated, use moment.max instead. https://github.com/moment/moment/issues/1548",function(a){return a=mb.apply(null,arguments),a>this?this:a}),zone:function(a,b){var c=this._offset||0;return null==a?this._isUTC?c:this._d.getTimezoneOffset():("string"==typeof a&&(a=L(a)),Math.abs(a)<16&&(a=60*a),this._offset=a,this._isUTC=!0,c!==a&&(!b||this._changeInProgress?n(this,mb.duration(c-a,"m"),1,!1):this._changeInProgress||(this._changeInProgress=!0,mb.updateOffset(this,!0),this._changeInProgress=null)),this)},zoneAbbr:function(){return this._isUTC?"UTC":""},zoneName:function(){return this._isUTC?"Coordinated Universal Time":""},parseZone:function(){return this._tzm?this.zone(this._tzm):"string"==typeof this._i&&this.zone(this._i),this},hasAlignedHourOffset:function(a){return a=a?mb(a).zone():0,(this.zone()-a)%60===0},daysInMonth:function(){return v(this.year(),this.month())},dayOfYear:function(a){var b=rb((mb(this).startOf("day")-mb(this).startOf("year"))/864e5)+1;return null==a?b:this.add("d",a-b)},quarter:function(a){return null==a?Math.ceil((this.month()+1)/3):this.month(3*(a-1)+this.month()%3)},weekYear:function(a){var b=bb(this,this.lang()._week.dow,this.lang()._week.doy).year;return null==a?b:this.add("y",a-b)},isoWeekYear:function(a){var b=bb(this,1,4).year;return null==a?b:this.add("y",a-b)},week:function(a){var b=this.lang().week(this);return null==a?b:this.add("d",7*(a-b))},isoWeek:function(a){var b=bb(this,1,4).week;return null==a?b:this.add("d",7*(a-b))},weekday:function(a){var b=(this.day()+7-this.lang()._week.dow)%7;return null==a?b:this.add("d",a-b)},isoWeekday:function(a){return null==a?this.day()||7:this.day(this.day()%7?a:a-7)},isoWeeksInYear:function(){return w(this.year(),1,4)},weeksInYear:function(){var a=this._lang._week;return w(this.year(),a.dow,a.doy)},get:function(a){return a=r(a),this[a]()},set:function(a,b){return a=r(a),"function"==typeof this[a]&&this[a](b),this},lang:function(b){return b===a?this._lang:(this._lang=F(b),this)}}),mb.fn.millisecond=mb.fn.milliseconds=ib("Milliseconds",!1),mb.fn.second=mb.fn.seconds=ib("Seconds",!1),mb.fn.minute=mb.fn.minutes=ib("Minutes",!1),mb.fn.hour=mb.fn.hours=ib("Hours",!0),mb.fn.date=ib("Date",!0),mb.fn.dates=d("dates accessor is deprecated. Use date instead.",ib("Date",!0)),mb.fn.year=ib("FullYear",!0),mb.fn.years=d("years accessor is deprecated. Use year instead.",ib("FullYear",!0)),mb.fn.days=mb.fn.day,mb.fn.months=mb.fn.month,mb.fn.weeks=mb.fn.week,mb.fn.isoWeeks=mb.fn.isoWeek,mb.fn.quarters=mb.fn.quarter,mb.fn.toJSON=mb.fn.toISOString,j(mb.duration.fn=i.prototype,{_bubble:function(){var a,b,c,d,e=this._milliseconds,f=this._days,g=this._months,h=this._data;h.milliseconds=e%1e3,a=l(e/1e3),h.seconds=a%60,b=l(a/60),h.minutes=b%60,c=l(b/60),h.hours=c%24,f+=l(c/24),h.days=f%30,g+=l(f/30),h.months=g%12,d=l(g/12),h.years=d},weeks:function(){return l(this.days()/7)},valueOf:function(){return this._milliseconds+864e5*this._days+this._months%12*2592e6+31536e6*u(this._months/12)},humanize:function(a){var b=+this,c=ab(b,!a,this.lang());return a&&(c=this.lang().pastFuture(b,c)),this.lang().postformat(c)},add:function(a,b){var c=mb.duration(a,b);return this._milliseconds+=c._milliseconds,this._days+=c._days,this._months+=c._months,this._bubble(),this},subtract:function(a,b){var c=mb.duration(a,b);return this._milliseconds-=c._milliseconds,this._days-=c._days,this._months-=c._months,this._bubble(),this},get:function(a){return a=r(a),this[a.toLowerCase()+"s"]()},as:function(a){return a=r(a),this["as"+a.charAt(0).toUpperCase()+a.slice(1)+"s"]()},lang:mb.fn.lang,toIsoString:function(){var a=Math.abs(this.years()),b=Math.abs(this.months()),c=Math.abs(this.days()),d=Math.abs(this.hours()),e=Math.abs(this.minutes()),f=Math.abs(this.seconds()+this.milliseconds()/1e3);return this.asSeconds()?(this.asSeconds()<0?"-":"")+"P"+(a?a+"Y":"")+(b?b+"M":"")+(c?c+"D":"")+(d||e||f?"T":"")+(d?d+"H":"")+(e?e+"M":"")+(f?f+"S":""):"P0D"}});for(ob in ac)ac.hasOwnProperty(ob)&&(kb(ob,ac[ob]),jb(ob.toLowerCase()));kb("Weeks",6048e5),mb.duration.fn.asMonths=function(){return(+this-31536e6*this.years())/2592e6+12*this.years()},mb.lang("en",{ordinal:function(a){var b=a%10,c=1===u(a%100/10)?"th":1===b?"st":2===b?"nd":3===b?"rd":"th";return a+c}}),Bb?module.exports=mb:"function"==typeof define&&define.amd?(define("moment",function(a,b,c){return c.config&&c.config()&&c.config().noGlobal===!0&&(qb.moment=nb),mb}),lb(!0)):lb()}).call(this);

function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>';
    return (str + '')
    .replace(/(<([^>]+)>)/ig,"")
    .replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
;(function(){
    var hidden, visibilityChange; 
    if (typeof document.hidden !== "undefined") {
        hidden = "hidden";
        visibilityChange = "visibilitychange";
    } else if (typeof document.mozHidden !== "undefined") {
        hidden = "mozHidden";
        visibilityChange = "mozvisibilitychange";
    } else if (typeof document.msHidden !== "undefined") {
        hidden = "msHidden";
        visibilityChange = "msvisibilitychange";
    } else if (typeof document.webkitHidden !== "undefined") {
        hidden = "webkitHidden";
        visibilityChange = "webkitvisibilitychange";
    }
    var videoElement = document.getElementById("videoElement");
    var pageIsShown = true;
    function handleVisibilityChange() {
        if (document[hidden]) {
            pageIsShown = false;
        } else {
            pageIsShown = true;
        }
    }
    var audioAlert = $('<audio id="soundHandle" style="display:none;"></audio>').attr('src','/alert.mp3')[0];
    if (typeof document.addEventListener === "undefined" || 
        typeof document[hidden] === "undefined") {
    } else {
        document.addEventListener(visibilityChange, handleVisibilityChange, false);
    }
    var shareInput = new (Backbone.View.extend({
        events : {
            'keyup textarea' : function(e) {
                if(e.keyCode === 13 && e.shiftKey && this.input.val().trim()) {
                    this.submit();
                    e.preventDefault();
                    return false;
                }
            },
            'click #submit': function() {
                this.submit();
            }
        },
        submit: function() {
            $.post('/wall/create-share', {
                type: 'text',
                content: this.input.val().trim()
            });
            this.reset();
        },
        reset: function() {
            this.input.val('');
        },
        initialize: function() {
            this.input = $('textarea', this.$el);
            autosize(this.input);
        }
    }))({
        el: '#new-share'
    });
    var sharesCollection = new Backbone.Collection();
    var commentsCollection = new Backbone.Collection();
    var feedsources = [];
    var currentLength = 4;
    var noDataTimeout;
    var setEventSourceLength = _.debounce(function(len) {
        clearTimeout(noDataTimeout);
        $('#load-older').button('loading');
        currentLength = len;
        if(feedsources[0] && feedsources[0].url) {
            feedsources[0].close();
            feedsources[0] = null;
        }
        if(feedsources[1] && feedsources[1].url) {
            feedsources[1].close();
            feedsources[1] = null;
        }
        // $.eventsource({
        //     url: "/wall/shares/" + len,
        //     dataType: "json", 
        //     open: function(data) {
        //         feedsources[0] = data.target;
        //     },
        //     message: function(data) {
        //         if(!pageIsShown)
        //             audioAlert.play();
        //         sharesCollection.set(data);
        //     }
        // });
        // $.eventsource({
        //     url: "/wall/comments/" + len,
        //     dataType: "json", 
        //     open: function(data) {
        //         feedsources[1] = data.target;
        //     },
        //     message: function(data) {
        //         if(!pageIsShown)
        //             audioAlert.play();
        //         commentsCollection.set(data);
        //     }
        // });
        noDataTimeout = setTimeout(function() {
            $('#load-older').text('No Data Found');
        }, 1500);
    }, 500);
    setEventSourceLength(5);
    var CommentsView = Backbone.View.extend({
        events: {
            'click .toggle-more': function(e) {
                this.opened = true;
                this.comments.toggleClass('more');
                $(e.target).remove();
            },
            'click .remove-comment': function(e) {
                var target = $(e.currentTarget);
                bootbox.confirm('Are you sure you want to remove this comment?', function(response){
                    if(response)
                        $.get('/wall/remove-comment/' + target.data('commentid'));
                });
            },
        },
        collection: commentsCollection,
        template: _.template($('#comments-template').text()),
        templateData: function() {
            return {
                comments: this.collection.toJSON().filter(function(comment){
                    return comment.share_id === this.collectionId;
                }.bind(this)),
                opened: this.opened
            };
        },
        render: Backbone.VirtualDomRenderer,
        initialize: function(opt) {
            this.opened = false;
            this.oldData = '{}';
            this.comments = this.$el.parents('.comments');
            this.collectionId = opt.collectionId;
            this.listenTo(this.collection, 'all', function(){
                var templateData = this.templateData();
                if(JSON.stringify(templateData) != this.oldData) {
                    this.oldData = JSON.stringify(templateData);
                    this.render();
                }
            }.bind(this));
            this.on('virtualdomrenderer:rendered', function(){
                if(!this.opened) {
                    if(this.templateData().comments.length > 2) {
                        this.comments.removeClass('less-than-three-comments');
                    } else {
                        this.comments.addClass('less-than-three-comments');
                    }
                }
            });
            this.render();
        }
    });
    var feedsView = new(Backbone.View.extend({
        events: {
            'click #load-older': function(e) {
                setEventSourceLength(currentLength + 5);
            },
            'keyup textarea' : function(e) {
                var target = $(e.currentTarget);
                if(e.keyCode === 13 && e.shiftKey && target.val().trim()) {
                    this.submit(target.data('feedid'), target.val().trim());
                    target.val('');
                    e.preventDefault();
                    return false;
                }
            },
            'click .remove-share': function(e) {
                var target = $(e.currentTarget);
                bootbox.confirm('Are you sure you want to remove this share?', function(response){
                    if(response)
                        $.get('/wall/remove-share/' + target.data('shareid'));
                });
            },
            'click .pin-share': function(e) {
                var target = $('.fa', e.currentTarget);
                if(target.hasClass('active')) {
                    target.removeClass('active');
                    $.get('/wall/unset-pin/' + target.data('shareid'));
                } else {
                    target.addClass('active');                
                    $.get('/wall/set-pin/' + target.data('shareid'));    
                }
            },
        },
        submit: function(id, comment) {
            $.post('/wall/create-comment/' + id, {
                comment: comment
            });
        },
        collection: sharesCollection,
        template: _.template($('#feeds-template').text()),
        templateData: function() {
            return {
                shares : this.collection.toJSON()
            };
        },
        indexes: '',
        children: [],
        rendered: null,
        render: Backbone.VirtualDomRenderer,
        initialize: function() {
            this.listenTo(this.collection, 'all', function(){
                if(JSON.stringify(this.collection.pluck('id')) !== this.indexes ) {
                    this.indexes = JSON.stringify(this.collection.pluck('id'));
                    this.render();
                }
            }.bind(this));
            this.on('virtualdomrenderer:rendered', function() {
                this.children.forEach(function(child, index){
                    child.remove();
                    delete this.children[index]
                }.bind(this));
                this.rendered = $(this.template(this.templateData()));
                this.$el.html(this.rendered);
                this.rendered.find('.comments-view').each(function(index, div){
                    this.children.push(new CommentsView({el : div, collectionId: $(div).data('shareid')}));
                }.bind(this));
            });
            this.render();
        }
    }))({el: '#feeds .inner'});

    var todos = new (Backbone.View.extend({
        template: _.template($('#task-template').text()),
        render: _.debounce(function() {
            this.$todoEl.html('');
            this.collection.toJSON().filter(function(model){
                return model.archived === 0;
            }).forEach(function(model){
                this.$todoEl.append(this.template({
                    model: model
                }))
            }.bind(this));
            $('.has-tooltip').tooltip();
            // console.log(this.collection.length)
        }, 17),
        collection: (new Backbone.Collection()),
        initialize: function() {
            this.$todoEl = $('#todo-el', this.$el);
            // $.eventsource({
            //     url: "/task/stream",
            //     dataType: "json",
            //     message: function(data) {
            //         this.collection.set(data, {
            //             delete: false
            //         })
            //     }.bind(this)
            // });
            this.listenTo(this.collection, 'all', this.render.bind(this));
        }
    }))({
        el: '#todos'
    });

}.call(this));