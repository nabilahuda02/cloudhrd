/*!
 * jQuery UI Sortable 1.11.4
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/sortable/
 */
(function( factory ) {
	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define([
			"jquery",
			"./core",
			"./mouse",
			"./widget"
		], factory );
	} else {

		// Browser globals
		factory( jQuery );
	}
}(function( $ ) {

return $.widget("ui.sortable", $.ui.mouse, {
	version: "1.11.4",
	widgetEventPrefix: "sort",
	ready: false,
	options: {
		appendTo: "parent",
		axis: false,
		connectWith: false,
		containment: false,
		cursor: "auto",
		cursorAt: false,
		dropOnEmpty: true,
		forcePlaceholderSize: false,
		forceHelperSize: false,
		grid: false,
		handle: false,
		helper: "original",
		items: "> *",
		opacity: false,
		placeholder: false,
		revert: false,
		scroll: true,
		scrollSensitivity: 20,
		scrollSpeed: 20,
		scope: "default",
		tolerance: "intersect",
		zIndex: 1000,

		// callbacks
		activate: null,
		beforeStop: null,
		change: null,
		deactivate: null,
		out: null,
		over: null,
		receive: null,
		remove: null,
		sort: null,
		start: null,
		stop: null,
		update: null
	},

	_isOverAxis: function( x, reference, size ) {
		return ( x >= reference ) && ( x < ( reference + size ) );
	},

	_isFloating: function( item ) {
		return (/left|right/).test(item.css("float")) || (/inline|table-cell/).test(item.css("display"));
	},

	_create: function() {
		this.containerCache = {};
		this.element.addClass("ui-sortable");

		//Get the items
		this.refresh();

		//Let's determine the parent's offset
		this.offset = this.element.offset();

		//Initialize mouse events for interaction
		this._mouseInit();

		this._setHandleClassName();

		//We're ready to go
		this.ready = true;

	},

	_setOption: function( key, value ) {
		this._super( key, value );

		if ( key === "handle" ) {
			this._setHandleClassName();
		}
	},

	_setHandleClassName: function() {
		this.element.find( ".ui-sortable-handle" ).removeClass( "ui-sortable-handle" );
		$.each( this.items, function() {
			( this.instance.options.handle ?
				this.item.find( this.instance.options.handle ) : this.item )
				.addClass( "ui-sortable-handle" );
		});
	},

	_destroy: function() {
		this.element
			.removeClass( "ui-sortable ui-sortable-disabled" )
			.find( ".ui-sortable-handle" )
				.removeClass( "ui-sortable-handle" );
		this._mouseDestroy();

		for ( var i = this.items.length - 1; i >= 0; i-- ) {
			this.items[i].item.removeData(this.widgetName + "-item");
		}

		return this;
	},

	_mouseCapture: function(event, overrideHandle) {
		var currentItem = null,
			validHandle = false,
			that = this;

		if (this.reverting) {
			return false;
		}

		if(this.options.disabled || this.options.type === "static") {
			return false;
		}

		//We have to refresh the items data once first
		this._refreshItems(event);

		//Find out if the clicked node (or one of its parents) is a actual item in this.items
		$(event.target).parents().each(function() {
			if($.data(this, that.widgetName + "-item") === that) {
				currentItem = $(this);
				return false;
			}
		});
		if($.data(event.target, that.widgetName + "-item") === that) {
			currentItem = $(event.target);
		}

		if(!currentItem) {
			return false;
		}
		if(this.options.handle && !overrideHandle) {
			$(this.options.handle, currentItem).find("*").addBack().each(function() {
				if(this === event.target) {
					validHandle = true;
				}
			});
			if(!validHandle) {
				return false;
			}
		}

		this.currentItem = currentItem;
		this._removeCurrentsFromItems();
		return true;

	},

	_mouseStart: function(event, overrideHandle, noActivation) {

		var i, body,
			o = this.options;

		this.currentContainer = this;

		//We only need to call refreshPositions, because the refreshItems call has been moved to mouseCapture
		this.refreshPositions();

		//Create and append the visible helper
		this.helper = this._createHelper(event);

		//Cache the helper size
		this._cacheHelperProportions();

		/*
		 * - Position generation -
		 * This block generates everything position related - it's the core of draggables.
		 */

		//Cache the margins of the original element
		this._cacheMargins();

		//Get the next scrolling parent
		this.scrollParent = this.helper.scrollParent();

		//The element's absolute position on the page minus margins
		this.offset = this.currentItem.offset();
		this.offset = {
			top: this.offset.top - this.margins.top,
			left: this.offset.left - this.margins.left
		};

		$.extend(this.offset, {
			click: { //Where the click happened, relative to the element
				left: event.pageX - this.offset.left,
				top: event.pageY - this.offset.top
			},
			parent: this._getParentOffset(),
			relative: this._getRelativeOffset() //This is a relative to absolute position minus the actual position calculation - only used for relative positioned helper
		});

		// Only after we got the offset, we can change the helper's position to absolute
		// TODO: Still need to figure out a way to make relative sorting possible
		this.helper.css("position", "absolute");
		this.cssPosition = this.helper.css("position");

		//Generate the original position
		this.originalPosition = this._generatePosition(event);
		this.originalPageX = event.pageX;
		this.originalPageY = event.pageY;

		//Adjust the mouse offset relative to the helper if "cursorAt" is supplied
		(o.cursorAt && this._adjustOffsetFromHelper(o.cursorAt));

		//Cache the former DOM position
		this.domPosition = { prev: this.currentItem.prev()[0], parent: this.currentItem.parent()[0] };

		//If the helper is not the original, hide the original so it's not playing any role during the drag, won't cause anything bad this way
		if(this.helper[0] !== this.currentItem[0]) {
			this.currentItem.hide();
		}

		//Create the placeholder
		this._createPlaceholder();

		//Set a containment if given in the options
		if(o.containment) {
			this._setContainment();
		}

		if( o.cursor && o.cursor !== "auto" ) { // cursor option
			body = this.document.find( "body" );

			// support: IE
			this.storedCursor = body.css( "cursor" );
			body.css( "cursor", o.cursor );

			this.storedStylesheet = $( "<style>*{ cursor: "+o.cursor+" !important; }</style>" ).appendTo( body );
		}

		if(o.opacity) { // opacity option
			if (this.helper.css("opacity")) {
				this._storedOpacity = this.helper.css("opacity");
			}
			this.helper.css("opacity", o.opacity);
		}

		if(o.zIndex) { // zIndex option
			if (this.helper.css("zIndex")) {
				this._storedZIndex = this.helper.css("zIndex");
			}
			this.helper.css("zIndex", o.zIndex);
		}

		//Prepare scrolling
		if(this.scrollParent[0] !== this.document[0] && this.scrollParent[0].tagName !== "HTML") {
			this.overflowOffset = this.scrollParent.offset();
		}

		//Call callbacks
		this._trigger("start", event, this._uiHash());

		//Recache the helper size
		if(!this._preserveHelperProportions) {
			this._cacheHelperProportions();
		}


		//Post "activate" events to possible containers
		if( !noActivation ) {
			for ( i = this.containers.length - 1; i >= 0; i-- ) {
				this.containers[ i ]._trigger( "activate", event, this._uiHash( this ) );
			}
		}

		//Prepare possible droppables
		if($.ui.ddmanager) {
			$.ui.ddmanager.current = this;
		}

		if ($.ui.ddmanager && !o.dropBehaviour) {
			$.ui.ddmanager.prepareOffsets(this, event);
		}

		this.dragging = true;

		this.helper.addClass("ui-sortable-helper");
		this._mouseDrag(event); //Execute the drag once - this causes the helper not to be visible before getting its correct position
		return true;

	},

	_mouseDrag: function(event) {
		var i, item, itemElement, intersection,
			o = this.options,
			scrolled = false;

		//Compute the helpers position
		this.position = this._generatePosition(event);
		this.positionAbs = this._convertPositionTo("absolute");

		if (!this.lastPositionAbs) {
			this.lastPositionAbs = this.positionAbs;
		}

		//Do scrolling
		if(this.options.scroll) {
			if(this.scrollParent[0] !== this.document[0] && this.scrollParent[0].tagName !== "HTML") {

				if((this.overflowOffset.top + this.scrollParent[0].offsetHeight) - event.pageY < o.scrollSensitivity) {
					this.scrollParent[0].scrollTop = scrolled = this.scrollParent[0].scrollTop + o.scrollSpeed;
				} else if(event.pageY - this.overflowOffset.top < o.scrollSensitivity) {
					this.scrollParent[0].scrollTop = scrolled = this.scrollParent[0].scrollTop - o.scrollSpeed;
				}

				if((this.overflowOffset.left + this.scrollParent[0].offsetWidth) - event.pageX < o.scrollSensitivity) {
					this.scrollParent[0].scrollLeft = scrolled = this.scrollParent[0].scrollLeft + o.scrollSpeed;
				} else if(event.pageX - this.overflowOffset.left < o.scrollSensitivity) {
					this.scrollParent[0].scrollLeft = scrolled = this.scrollParent[0].scrollLeft - o.scrollSpeed;
				}

			} else {

				if(event.pageY - this.document.scrollTop() < o.scrollSensitivity) {
					scrolled = this.document.scrollTop(this.document.scrollTop() - o.scrollSpeed);
				} else if(this.window.height() - (event.pageY - this.document.scrollTop()) < o.scrollSensitivity) {
					scrolled = this.document.scrollTop(this.document.scrollTop() + o.scrollSpeed);
				}

				if(event.pageX - this.document.scrollLeft() < o.scrollSensitivity) {
					scrolled = this.document.scrollLeft(this.document.scrollLeft() - o.scrollSpeed);
				} else if(this.window.width() - (event.pageX - this.document.scrollLeft()) < o.scrollSensitivity) {
					scrolled = this.document.scrollLeft(this.document.scrollLeft() + o.scrollSpeed);
				}

			}

			if(scrolled !== false && $.ui.ddmanager && !o.dropBehaviour) {
				$.ui.ddmanager.prepareOffsets(this, event);
			}
		}

		//Regenerate the absolute position used for position checks
		this.positionAbs = this._convertPositionTo("absolute");

		//Set the helper position
		if(!this.options.axis || this.options.axis !== "y") {
			this.helper[0].style.left = this.position.left+"px";
		}
		if(!this.options.axis || this.options.axis !== "x") {
			this.helper[0].style.top = this.position.top+"px";
		}

		//Rearrange
		for (i = this.items.length - 1; i >= 0; i--) {

			//Cache variables and intersection, continue if no intersection
			item = this.items[i];
			itemElement = item.item[0];
			intersection = this._intersectsWithPointer(item);
			if (!intersection) {
				continue;
			}

			// Only put the placeholder inside the current Container, skip all
			// items from other containers. This works because when moving
			// an item from one container to another the
			// currentContainer is switched before the placeholder is moved.
			//
			// Without this, moving items in "sub-sortables" can cause
			// the placeholder to jitter between the outer and inner container.
			if (item.instance !== this.currentContainer) {
				continue;
			}

			// cannot intersect with itself
			// no useless actions that have been done before
			// no action if the item moved is the parent of the item checked
			if (itemElement !== this.currentItem[0] &&
				this.placeholder[intersection === 1 ? "next" : "prev"]()[0] !== itemElement &&
				!$.contains(this.placeholder[0], itemElement) &&
				(this.options.type === "semi-dynamic" ? !$.contains(this.element[0], itemElement) : true)
			) {

				this.direction = intersection === 1 ? "down" : "up";

				if (this.options.tolerance === "pointer" || this._intersectsWithSides(item)) {
					this._rearrange(event, item);
				} else {
					break;
				}

				this._trigger("change", event, this._uiHash());
				break;
			}
		}

		//Post events to containers
		this._contactContainers(event);

		//Interconnect with droppables
		if($.ui.ddmanager) {
			$.ui.ddmanager.drag(this, event);
		}

		//Call callbacks
		this._trigger("sort", event, this._uiHash());

		this.lastPositionAbs = this.positionAbs;
		return false;

	},

	_mouseStop: function(event, noPropagation) {

		if(!event) {
			return;
		}

		//If we are using droppables, inform the manager about the drop
		if ($.ui.ddmanager && !this.options.dropBehaviour) {
			$.ui.ddmanager.drop(this, event);
		}

		if(this.options.revert) {
			var that = this,
				cur = this.placeholder.offset(),
				axis = this.options.axis,
				animation = {};

			if ( !axis || axis === "x" ) {
				animation.left = cur.left - this.offset.parent.left - this.margins.left + (this.offsetParent[0] === this.document[0].body ? 0 : this.offsetParent[0].scrollLeft);
			}
			if ( !axis || axis === "y" ) {
				animation.top = cur.top - this.offset.parent.top - this.margins.top + (this.offsetParent[0] === this.document[0].body ? 0 : this.offsetParent[0].scrollTop);
			}
			this.reverting = true;
			$(this.helper).animate( animation, parseInt(this.options.revert, 10) || 500, function() {
				that._clear(event);
			});
		} else {
			this._clear(event, noPropagation);
		}

		return false;

	},

	cancel: function() {

		if(this.dragging) {

			this._mouseUp({ target: null });

			if(this.options.helper === "original") {
				this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper");
			} else {
				this.currentItem.show();
			}

			//Post deactivating events to containers
			for (var i = this.containers.length - 1; i >= 0; i--){
				this.containers[i]._trigger("deactivate", null, this._uiHash(this));
				if(this.containers[i].containerCache.over) {
					this.containers[i]._trigger("out", null, this._uiHash(this));
					this.containers[i].containerCache.over = 0;
				}
			}

		}

		if (this.placeholder) {
			//$(this.placeholder[0]).remove(); would have been the jQuery way - unfortunately, it unbinds ALL events from the original node!
			if(this.placeholder[0].parentNode) {
				this.placeholder[0].parentNode.removeChild(this.placeholder[0]);
			}
			if(this.options.helper !== "original" && this.helper && this.helper[0].parentNode) {
				this.helper.remove();
			}

			$.extend(this, {
				helper: null,
				dragging: false,
				reverting: false,
				_noFinalSort: null
			});

			if(this.domPosition.prev) {
				$(this.domPosition.prev).after(this.currentItem);
			} else {
				$(this.domPosition.parent).prepend(this.currentItem);
			}
		}

		return this;

	},

	serialize: function(o) {

		var items = this._getItemsAsjQuery(o && o.connected),
			str = [];
		o = o || {};

		$(items).each(function() {
			var res = ($(o.item || this).attr(o.attribute || "id") || "").match(o.expression || (/(.+)[\-=_](.+)/));
			if (res) {
				str.push((o.key || res[1]+"[]")+"="+(o.key && o.expression ? res[1] : res[2]));
			}
		});

		if(!str.length && o.key) {
			str.push(o.key + "=");
		}

		return str.join("&");

	},

	toArray: function(o) {

		var items = this._getItemsAsjQuery(o && o.connected),
			ret = [];

		o = o || {};

		items.each(function() { ret.push($(o.item || this).attr(o.attribute || "id") || ""); });
		return ret;

	},

	/* Be careful with the following core functions */
	_intersectsWith: function(item) {

		var x1 = this.positionAbs.left,
			x2 = x1 + this.helperProportions.width,
			y1 = this.positionAbs.top,
			y2 = y1 + this.helperProportions.height,
			l = item.left,
			r = l + item.width,
			t = item.top,
			b = t + item.height,
			dyClick = this.offset.click.top,
			dxClick = this.offset.click.left,
			isOverElementHeight = ( this.options.axis === "x" ) || ( ( y1 + dyClick ) > t && ( y1 + dyClick ) < b ),
			isOverElementWidth = ( this.options.axis === "y" ) || ( ( x1 + dxClick ) > l && ( x1 + dxClick ) < r ),
			isOverElement = isOverElementHeight && isOverElementWidth;

		if ( this.options.tolerance === "pointer" ||
			this.options.forcePointerForContainers ||
			(this.options.tolerance !== "pointer" && this.helperProportions[this.floating ? "width" : "height"] > item[this.floating ? "width" : "height"])
		) {
			return isOverElement;
		} else {

			return (l < x1 + (this.helperProportions.width / 2) && // Right Half
				x2 - (this.helperProportions.width / 2) < r && // Left Half
				t < y1 + (this.helperProportions.height / 2) && // Bottom Half
				y2 - (this.helperProportions.height / 2) < b ); // Top Half

		}
	},

	_intersectsWithPointer: function(item) {

		var isOverElementHeight = (this.options.axis === "x") || this._isOverAxis(this.positionAbs.top + this.offset.click.top, item.top, item.height),
			isOverElementWidth = (this.options.axis === "y") || this._isOverAxis(this.positionAbs.left + this.offset.click.left, item.left, item.width),
			isOverElement = isOverElementHeight && isOverElementWidth,
			verticalDirection = this._getDragVerticalDirection(),
			horizontalDirection = this._getDragHorizontalDirection();

		if (!isOverElement) {
			return false;
		}

		return this.floating ?
			( ((horizontalDirection && horizontalDirection === "right") || verticalDirection === "down") ? 2 : 1 )
			: ( verticalDirection && (verticalDirection === "down" ? 2 : 1) );

	},

	_intersectsWithSides: function(item) {

		var isOverBottomHalf = this._isOverAxis(this.positionAbs.top + this.offset.click.top, item.top + (item.height/2), item.height),
			isOverRightHalf = this._isOverAxis(this.positionAbs.left + this.offset.click.left, item.left + (item.width/2), item.width),
			verticalDirection = this._getDragVerticalDirection(),
			horizontalDirection = this._getDragHorizontalDirection();

		if (this.floating && horizontalDirection) {
			return ((horizontalDirection === "right" && isOverRightHalf) || (horizontalDirection === "left" && !isOverRightHalf));
		} else {
			return verticalDirection && ((verticalDirection === "down" && isOverBottomHalf) || (verticalDirection === "up" && !isOverBottomHalf));
		}

	},

	_getDragVerticalDirection: function() {
		var delta = this.positionAbs.top - this.lastPositionAbs.top;
		return delta !== 0 && (delta > 0 ? "down" : "up");
	},

	_getDragHorizontalDirection: function() {
		var delta = this.positionAbs.left - this.lastPositionAbs.left;
		return delta !== 0 && (delta > 0 ? "right" : "left");
	},

	refresh: function(event) {
		this._refreshItems(event);
		this._setHandleClassName();
		this.refreshPositions();
		return this;
	},

	_connectWith: function() {
		var options = this.options;
		return options.connectWith.constructor === String ? [options.connectWith] : options.connectWith;
	},

	_getItemsAsjQuery: function(connected) {

		var i, j, cur, inst,
			items = [],
			queries = [],
			connectWith = this._connectWith();

		if(connectWith && connected) {
			for (i = connectWith.length - 1; i >= 0; i--){
				cur = $(connectWith[i], this.document[0]);
				for ( j = cur.length - 1; j >= 0; j--){
					inst = $.data(cur[j], this.widgetFullName);
					if(inst && inst !== this && !inst.options.disabled) {
						queries.push([$.isFunction(inst.options.items) ? inst.options.items.call(inst.element) : $(inst.options.items, inst.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), inst]);
					}
				}
			}
		}

		queries.push([$.isFunction(this.options.items) ? this.options.items.call(this.element, null, { options: this.options, item: this.currentItem }) : $(this.options.items, this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), this]);

		function addItems() {
			items.push( this );
		}
		for (i = queries.length - 1; i >= 0; i--){
			queries[i][0].each( addItems );
		}

		return $(items);

	},

	_removeCurrentsFromItems: function() {

		var list = this.currentItem.find(":data(" + this.widgetName + "-item)");

		this.items = $.grep(this.items, function (item) {
			for (var j=0; j < list.length; j++) {
				if(list[j] === item.item[0]) {
					return false;
				}
			}
			return true;
		});

	},

	_refreshItems: function(event) {

		this.items = [];
		this.containers = [this];

		var i, j, cur, inst, targetData, _queries, item, queriesLength,
			items = this.items,
			queries = [[$.isFunction(this.options.items) ? this.options.items.call(this.element[0], event, { item: this.currentItem }) : $(this.options.items, this.element), this]],
			connectWith = this._connectWith();

		if(connectWith && this.ready) { //Shouldn't be run the first time through due to massive slow-down
			for (i = connectWith.length - 1; i >= 0; i--){
				cur = $(connectWith[i], this.document[0]);
				for (j = cur.length - 1; j >= 0; j--){
					inst = $.data(cur[j], this.widgetFullName);
					if(inst && inst !== this && !inst.options.disabled) {
						queries.push([$.isFunction(inst.options.items) ? inst.options.items.call(inst.element[0], event, { item: this.currentItem }) : $(inst.options.items, inst.element), inst]);
						this.containers.push(inst);
					}
				}
			}
		}

		for (i = queries.length - 1; i >= 0; i--) {
			targetData = queries[i][1];
			_queries = queries[i][0];

			for (j=0, queriesLength = _queries.length; j < queriesLength; j++) {
				item = $(_queries[j]);

				item.data(this.widgetName + "-item", targetData); // Data for target checking (mouse manager)

				items.push({
					item: item,
					instance: targetData,
					width: 0, height: 0,
					left: 0, top: 0
				});
			}
		}

	},

	refreshPositions: function(fast) {

		// Determine whether items are being displayed horizontally
		this.floating = this.items.length ?
			this.options.axis === "x" || this._isFloating( this.items[ 0 ].item ) :
			false;

		//This has to be redone because due to the item being moved out/into the offsetParent, the offsetParent's position will change
		if(this.offsetParent && this.helper) {
			this.offset.parent = this._getParentOffset();
		}

		var i, item, t, p;

		for (i = this.items.length - 1; i >= 0; i--){
			item = this.items[i];

			//We ignore calculating positions of all connected containers when we're not over them
			if(item.instance !== this.currentContainer && this.currentContainer && item.item[0] !== this.currentItem[0]) {
				continue;
			}

			t = this.options.toleranceElement ? $(this.options.toleranceElement, item.item) : item.item;

			if (!fast) {
				item.width = t.outerWidth();
				item.height = t.outerHeight();
			}

			p = t.offset();
			item.left = p.left;
			item.top = p.top;
		}

		if(this.options.custom && this.options.custom.refreshContainers) {
			this.options.custom.refreshContainers.call(this);
		} else {
			for (i = this.containers.length - 1; i >= 0; i--){
				p = this.containers[i].element.offset();
				this.containers[i].containerCache.left = p.left;
				this.containers[i].containerCache.top = p.top;
				this.containers[i].containerCache.width = this.containers[i].element.outerWidth();
				this.containers[i].containerCache.height = this.containers[i].element.outerHeight();
			}
		}

		return this;
	},

	_createPlaceholder: function(that) {
		that = that || this;
		var className,
			o = that.options;

		if(!o.placeholder || o.placeholder.constructor === String) {
			className = o.placeholder;
			o.placeholder = {
				element: function() {

					var nodeName = that.currentItem[0].nodeName.toLowerCase(),
						element = $( "<" + nodeName + ">", that.document[0] )
							.addClass(className || that.currentItem[0].className+" ui-sortable-placeholder")
							.removeClass("ui-sortable-helper");

					if ( nodeName === "tbody" ) {
						that._createTrPlaceholder(
							that.currentItem.find( "tr" ).eq( 0 ),
							$( "<tr>", that.document[ 0 ] ).appendTo( element )
						);
					} else if ( nodeName === "tr" ) {
						that._createTrPlaceholder( that.currentItem, element );
					} else if ( nodeName === "img" ) {
						element.attr( "src", that.currentItem.attr( "src" ) );
					}

					if ( !className ) {
						element.css( "visibility", "hidden" );
					}

					return element;
				},
				update: function(container, p) {

					// 1. If a className is set as 'placeholder option, we don't force sizes - the class is responsible for that
					// 2. The option 'forcePlaceholderSize can be enabled to force it even if a class name is specified
					if(className && !o.forcePlaceholderSize) {
						return;
					}

					//If the element doesn't have a actual height by itself (without styles coming from a stylesheet), it receives the inline height from the dragged item
					if(!p.height()) { p.height(that.currentItem.innerHeight() - parseInt(that.currentItem.css("paddingTop")||0, 10) - parseInt(that.currentItem.css("paddingBottom")||0, 10)); }
					if(!p.width()) { p.width(that.currentItem.innerWidth() - parseInt(that.currentItem.css("paddingLeft")||0, 10) - parseInt(that.currentItem.css("paddingRight")||0, 10)); }
				}
			};
		}

		//Create the placeholder
		that.placeholder = $(o.placeholder.element.call(that.element, that.currentItem));

		//Append it after the actual current item
		that.currentItem.after(that.placeholder);

		//Update the size of the placeholder (TODO: Logic to fuzzy, see line 316/317)
		o.placeholder.update(that, that.placeholder);

	},

	_createTrPlaceholder: function( sourceTr, targetTr ) {
		var that = this;

		sourceTr.children().each(function() {
			$( "<td>&#160;</td>", that.document[ 0 ] )
				.attr( "colspan", $( this ).attr( "colspan" ) || 1 )
				.appendTo( targetTr );
		});
	},

	_contactContainers: function(event) {
		var i, j, dist, itemWithLeastDistance, posProperty, sizeProperty, cur, nearBottom, floating, axis,
			innermostContainer = null,
			innermostIndex = null;

		// get innermost container that intersects with item
		for (i = this.containers.length - 1; i >= 0; i--) {

			// never consider a container that's located within the item itself
			if($.contains(this.currentItem[0], this.containers[i].element[0])) {
				continue;
			}

			if(this._intersectsWith(this.containers[i].containerCache)) {

				// if we've already found a container and it's more "inner" than this, then continue
				if(innermostContainer && $.contains(this.containers[i].element[0], innermostContainer.element[0])) {
					continue;
				}

				innermostContainer = this.containers[i];
				innermostIndex = i;

			} else {
				// container doesn't intersect. trigger "out" event if necessary
				if(this.containers[i].containerCache.over) {
					this.containers[i]._trigger("out", event, this._uiHash(this));
					this.containers[i].containerCache.over = 0;
				}
			}

		}

		// if no intersecting containers found, return
		if(!innermostContainer) {
			return;
		}

		// move the item into the container if it's not there already
		if(this.containers.length === 1) {
			if (!this.containers[innermostIndex].containerCache.over) {
				this.containers[innermostIndex]._trigger("over", event, this._uiHash(this));
				this.containers[innermostIndex].containerCache.over = 1;
			}
		} else {

			//When entering a new container, we will find the item with the least distance and append our item near it
			dist = 10000;
			itemWithLeastDistance = null;
			floating = innermostContainer.floating || this._isFloating(this.currentItem);
			posProperty = floating ? "left" : "top";
			sizeProperty = floating ? "width" : "height";
			axis = floating ? "clientX" : "clientY";

			for (j = this.items.length - 1; j >= 0; j--) {
				if(!$.contains(this.containers[innermostIndex].element[0], this.items[j].item[0])) {
					continue;
				}
				if(this.items[j].item[0] === this.currentItem[0]) {
					continue;
				}

				cur = this.items[j].item.offset()[posProperty];
				nearBottom = false;
				if ( event[ axis ] - cur > this.items[ j ][ sizeProperty ] / 2 ) {
					nearBottom = true;
				}

				if ( Math.abs( event[ axis ] - cur ) < dist ) {
					dist = Math.abs( event[ axis ] - cur );
					itemWithLeastDistance = this.items[ j ];
					this.direction = nearBottom ? "up": "down";
				}
			}

			//Check if dropOnEmpty is enabled
			if(!itemWithLeastDistance && !this.options.dropOnEmpty) {
				return;
			}

			if(this.currentContainer === this.containers[innermostIndex]) {
				if ( !this.currentContainer.containerCache.over ) {
					this.containers[ innermostIndex ]._trigger( "over", event, this._uiHash() );
					this.currentContainer.containerCache.over = 1;
				}
				return;
			}

			itemWithLeastDistance ? this._rearrange(event, itemWithLeastDistance, null, true) : this._rearrange(event, null, this.containers[innermostIndex].element, true);
			this._trigger("change", event, this._uiHash());
			this.containers[innermostIndex]._trigger("change", event, this._uiHash(this));
			this.currentContainer = this.containers[innermostIndex];

			//Update the placeholder
			this.options.placeholder.update(this.currentContainer, this.placeholder);

			this.containers[innermostIndex]._trigger("over", event, this._uiHash(this));
			this.containers[innermostIndex].containerCache.over = 1;
		}


	},

	_createHelper: function(event) {

		var o = this.options,
			helper = $.isFunction(o.helper) ? $(o.helper.apply(this.element[0], [event, this.currentItem])) : (o.helper === "clone" ? this.currentItem.clone() : this.currentItem);

		//Add the helper to the DOM if that didn't happen already
		if(!helper.parents("body").length) {
			$(o.appendTo !== "parent" ? o.appendTo : this.currentItem[0].parentNode)[0].appendChild(helper[0]);
		}

		if(helper[0] === this.currentItem[0]) {
			this._storedCSS = { width: this.currentItem[0].style.width, height: this.currentItem[0].style.height, position: this.currentItem.css("position"), top: this.currentItem.css("top"), left: this.currentItem.css("left") };
		}

		if(!helper[0].style.width || o.forceHelperSize) {
			helper.width(this.currentItem.width());
		}
		if(!helper[0].style.height || o.forceHelperSize) {
			helper.height(this.currentItem.height());
		}

		return helper;

	},

	_adjustOffsetFromHelper: function(obj) {
		if (typeof obj === "string") {
			obj = obj.split(" ");
		}
		if ($.isArray(obj)) {
			obj = {left: +obj[0], top: +obj[1] || 0};
		}
		if ("left" in obj) {
			this.offset.click.left = obj.left + this.margins.left;
		}
		if ("right" in obj) {
			this.offset.click.left = this.helperProportions.width - obj.right + this.margins.left;
		}
		if ("top" in obj) {
			this.offset.click.top = obj.top + this.margins.top;
		}
		if ("bottom" in obj) {
			this.offset.click.top = this.helperProportions.height - obj.bottom + this.margins.top;
		}
	},

	_getParentOffset: function() {


		//Get the offsetParent and cache its position
		this.offsetParent = this.helper.offsetParent();
		var po = this.offsetParent.offset();

		// This is a special case where we need to modify a offset calculated on start, since the following happened:
		// 1. The position of the helper is absolute, so it's position is calculated based on the next positioned parent
		// 2. The actual offset parent is a child of the scroll parent, and the scroll parent isn't the document, which means that
		//    the scroll is included in the initial calculation of the offset of the parent, and never recalculated upon drag
		if(this.cssPosition === "absolute" && this.scrollParent[0] !== this.document[0] && $.contains(this.scrollParent[0], this.offsetParent[0])) {
			po.left += this.scrollParent.scrollLeft();
			po.top += this.scrollParent.scrollTop();
		}

		// This needs to be actually done for all browsers, since pageX/pageY includes this information
		// with an ugly IE fix
		if( this.offsetParent[0] === this.document[0].body || (this.offsetParent[0].tagName && this.offsetParent[0].tagName.toLowerCase() === "html" && $.ui.ie)) {
			po = { top: 0, left: 0 };
		}

		return {
			top: po.top + (parseInt(this.offsetParent.css("borderTopWidth"),10) || 0),
			left: po.left + (parseInt(this.offsetParent.css("borderLeftWidth"),10) || 0)
		};

	},

	_getRelativeOffset: function() {

		if(this.cssPosition === "relative") {
			var p = this.currentItem.position();
			return {
				top: p.top - (parseInt(this.helper.css("top"),10) || 0) + this.scrollParent.scrollTop(),
				left: p.left - (parseInt(this.helper.css("left"),10) || 0) + this.scrollParent.scrollLeft()
			};
		} else {
			return { top: 0, left: 0 };
		}

	},

	_cacheMargins: function() {
		this.margins = {
			left: (parseInt(this.currentItem.css("marginLeft"),10) || 0),
			top: (parseInt(this.currentItem.css("marginTop"),10) || 0)
		};
	},

	_cacheHelperProportions: function() {
		this.helperProportions = {
			width: this.helper.outerWidth(),
			height: this.helper.outerHeight()
		};
	},

	_setContainment: function() {

		var ce, co, over,
			o = this.options;
		if(o.containment === "parent") {
			o.containment = this.helper[0].parentNode;
		}
		if(o.containment === "document" || o.containment === "window") {
			this.containment = [
				0 - this.offset.relative.left - this.offset.parent.left,
				0 - this.offset.relative.top - this.offset.parent.top,
				o.containment === "document" ? this.document.width() : this.window.width() - this.helperProportions.width - this.margins.left,
				(o.containment === "document" ? this.document.width() : this.window.height() || this.document[0].body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top
			];
		}

		if(!(/^(document|window|parent)$/).test(o.containment)) {
			ce = $(o.containment)[0];
			co = $(o.containment).offset();
			over = ($(ce).css("overflow") !== "hidden");

			this.containment = [
				co.left + (parseInt($(ce).css("borderLeftWidth"),10) || 0) + (parseInt($(ce).css("paddingLeft"),10) || 0) - this.margins.left,
				co.top + (parseInt($(ce).css("borderTopWidth"),10) || 0) + (parseInt($(ce).css("paddingTop"),10) || 0) - this.margins.top,
				co.left+(over ? Math.max(ce.scrollWidth,ce.offsetWidth) : ce.offsetWidth) - (parseInt($(ce).css("borderLeftWidth"),10) || 0) - (parseInt($(ce).css("paddingRight"),10) || 0) - this.helperProportions.width - this.margins.left,
				co.top+(over ? Math.max(ce.scrollHeight,ce.offsetHeight) : ce.offsetHeight) - (parseInt($(ce).css("borderTopWidth"),10) || 0) - (parseInt($(ce).css("paddingBottom"),10) || 0) - this.helperProportions.height - this.margins.top
			];
		}

	},

	_convertPositionTo: function(d, pos) {

		if(!pos) {
			pos = this.position;
		}
		var mod = d === "absolute" ? 1 : -1,
			scroll = this.cssPosition === "absolute" && !(this.scrollParent[0] !== this.document[0] && $.contains(this.scrollParent[0], this.offsetParent[0])) ? this.offsetParent : this.scrollParent,
			scrollIsRootNode = (/(html|body)/i).test(scroll[0].tagName);

		return {
			top: (
				pos.top	+																// The absolute mouse position
				this.offset.relative.top * mod +										// Only for relative positioned nodes: Relative offset from element to offset parent
				this.offset.parent.top * mod -											// The offsetParent's offset without borders (offset + border)
				( ( this.cssPosition === "fixed" ? -this.scrollParent.scrollTop() : ( scrollIsRootNode ? 0 : scroll.scrollTop() ) ) * mod)
			),
			left: (
				pos.left +																// The absolute mouse position
				this.offset.relative.left * mod +										// Only for relative positioned nodes: Relative offset from element to offset parent
				this.offset.parent.left * mod	-										// The offsetParent's offset without borders (offset + border)
				( ( this.cssPosition === "fixed" ? -this.scrollParent.scrollLeft() : scrollIsRootNode ? 0 : scroll.scrollLeft() ) * mod)
			)
		};

	},

	_generatePosition: function(event) {

		var top, left,
			o = this.options,
			pageX = event.pageX,
			pageY = event.pageY,
			scroll = this.cssPosition === "absolute" && !(this.scrollParent[0] !== this.document[0] && $.contains(this.scrollParent[0], this.offsetParent[0])) ? this.offsetParent : this.scrollParent, scrollIsRootNode = (/(html|body)/i).test(scroll[0].tagName);

		// This is another very weird special case that only happens for relative elements:
		// 1. If the css position is relative
		// 2. and the scroll parent is the document or similar to the offset parent
		// we have to refresh the relative offset during the scroll so there are no jumps
		if(this.cssPosition === "relative" && !(this.scrollParent[0] !== this.document[0] && this.scrollParent[0] !== this.offsetParent[0])) {
			this.offset.relative = this._getRelativeOffset();
		}

		/*
		 * - Position constraining -
		 * Constrain the position to a mix of grid, containment.
		 */

		if(this.originalPosition) { //If we are not dragging yet, we won't check for options

			if(this.containment) {
				if(event.pageX - this.offset.click.left < this.containment[0]) {
					pageX = this.containment[0] + this.offset.click.left;
				}
				if(event.pageY - this.offset.click.top < this.containment[1]) {
					pageY = this.containment[1] + this.offset.click.top;
				}
				if(event.pageX - this.offset.click.left > this.containment[2]) {
					pageX = this.containment[2] + this.offset.click.left;
				}
				if(event.pageY - this.offset.click.top > this.containment[3]) {
					pageY = this.containment[3] + this.offset.click.top;
				}
			}

			if(o.grid) {
				top = this.originalPageY + Math.round((pageY - this.originalPageY) / o.grid[1]) * o.grid[1];
				pageY = this.containment ? ( (top - this.offset.click.top >= this.containment[1] && top - this.offset.click.top <= this.containment[3]) ? top : ((top - this.offset.click.top >= this.containment[1]) ? top - o.grid[1] : top + o.grid[1])) : top;

				left = this.originalPageX + Math.round((pageX - this.originalPageX) / o.grid[0]) * o.grid[0];
				pageX = this.containment ? ( (left - this.offset.click.left >= this.containment[0] && left - this.offset.click.left <= this.containment[2]) ? left : ((left - this.offset.click.left >= this.containment[0]) ? left - o.grid[0] : left + o.grid[0])) : left;
			}

		}

		return {
			top: (
				pageY -																// The absolute mouse position
				this.offset.click.top -													// Click offset (relative to the element)
				this.offset.relative.top	-											// Only for relative positioned nodes: Relative offset from element to offset parent
				this.offset.parent.top +												// The offsetParent's offset without borders (offset + border)
				( ( this.cssPosition === "fixed" ? -this.scrollParent.scrollTop() : ( scrollIsRootNode ? 0 : scroll.scrollTop() ) ))
			),
			left: (
				pageX -																// The absolute mouse position
				this.offset.click.left -												// Click offset (relative to the element)
				this.offset.relative.left	-											// Only for relative positioned nodes: Relative offset from element to offset parent
				this.offset.parent.left +												// The offsetParent's offset without borders (offset + border)
				( ( this.cssPosition === "fixed" ? -this.scrollParent.scrollLeft() : scrollIsRootNode ? 0 : scroll.scrollLeft() ))
			)
		};

	},

	_rearrange: function(event, i, a, hardRefresh) {

		a ? a[0].appendChild(this.placeholder[0]) : i.item[0].parentNode.insertBefore(this.placeholder[0], (this.direction === "down" ? i.item[0] : i.item[0].nextSibling));

		//Various things done here to improve the performance:
		// 1. we create a setTimeout, that calls refreshPositions
		// 2. on the instance, we have a counter variable, that get's higher after every append
		// 3. on the local scope, we copy the counter variable, and check in the timeout, if it's still the same
		// 4. this lets only the last addition to the timeout stack through
		this.counter = this.counter ? ++this.counter : 1;
		var counter = this.counter;

		this._delay(function() {
			if(counter === this.counter) {
				this.refreshPositions(!hardRefresh); //Precompute after each DOM insertion, NOT on mousemove
			}
		});

	},

	_clear: function(event, noPropagation) {

		this.reverting = false;
		// We delay all events that have to be triggered to after the point where the placeholder has been removed and
		// everything else normalized again
		var i,
			delayedTriggers = [];

		// We first have to update the dom position of the actual currentItem
		// Note: don't do it if the current item is already removed (by a user), or it gets reappended (see #4088)
		if(!this._noFinalSort && this.currentItem.parent().length) {
			this.placeholder.before(this.currentItem);
		}
		this._noFinalSort = null;

		if(this.helper[0] === this.currentItem[0]) {
			for(i in this._storedCSS) {
				if(this._storedCSS[i] === "auto" || this._storedCSS[i] === "static") {
					this._storedCSS[i] = "";
				}
			}
			this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper");
		} else {
			this.currentItem.show();
		}

		if(this.fromOutside && !noPropagation) {
			delayedTriggers.push(function(event) { this._trigger("receive", event, this._uiHash(this.fromOutside)); });
		}
		if((this.fromOutside || this.domPosition.prev !== this.currentItem.prev().not(".ui-sortable-helper")[0] || this.domPosition.parent !== this.currentItem.parent()[0]) && !noPropagation) {
			delayedTriggers.push(function(event) { this._trigger("update", event, this._uiHash()); }); //Trigger update callback if the DOM position has changed
		}

		// Check if the items Container has Changed and trigger appropriate
		// events.
		if (this !== this.currentContainer) {
			if(!noPropagation) {
				delayedTriggers.push(function(event) { this._trigger("remove", event, this._uiHash()); });
				delayedTriggers.push((function(c) { return function(event) { c._trigger("receive", event, this._uiHash(this)); };  }).call(this, this.currentContainer));
				delayedTriggers.push((function(c) { return function(event) { c._trigger("update", event, this._uiHash(this));  }; }).call(this, this.currentContainer));
			}
		}


		//Post events to containers
		function delayEvent( type, instance, container ) {
			return function( event ) {
				container._trigger( type, event, instance._uiHash( instance ) );
			};
		}
		for (i = this.containers.length - 1; i >= 0; i--){
			if (!noPropagation) {
				delayedTriggers.push( delayEvent( "deactivate", this, this.containers[ i ] ) );
			}
			if(this.containers[i].containerCache.over) {
				delayedTriggers.push( delayEvent( "out", this, this.containers[ i ] ) );
				this.containers[i].containerCache.over = 0;
			}
		}

		//Do what was originally in plugins
		if ( this.storedCursor ) {
			this.document.find( "body" ).css( "cursor", this.storedCursor );
			this.storedStylesheet.remove();
		}
		if(this._storedOpacity) {
			this.helper.css("opacity", this._storedOpacity);
		}
		if(this._storedZIndex) {
			this.helper.css("zIndex", this._storedZIndex === "auto" ? "" : this._storedZIndex);
		}

		this.dragging = false;

		if(!noPropagation) {
			this._trigger("beforeStop", event, this._uiHash());
		}

		//$(this.placeholder[0]).remove(); would have been the jQuery way - unfortunately, it unbinds ALL events from the original node!
		this.placeholder[0].parentNode.removeChild(this.placeholder[0]);

		if ( !this.cancelHelperRemoval ) {
			if ( this.helper[ 0 ] !== this.currentItem[ 0 ] ) {
				this.helper.remove();
			}
			this.helper = null;
		}

		if(!noPropagation) {
			for (i=0; i < delayedTriggers.length; i++) {
				delayedTriggers[i].call(this, event);
			} //Trigger all delayed events
			this._trigger("stop", event, this._uiHash());
		}

		this.fromOutside = false;
		return !this.cancelHelperRemoval;

	},

	_trigger: function() {
		if ($.Widget.prototype._trigger.apply(this, arguments) === false) {
			this.cancel();
		}
	},

	_uiHash: function(_inst) {
		var inst = _inst || this;
		return {
			helper: inst.helper,
			placeholder: inst.placeholder || $([]),
			position: inst.position,
			originalPosition: inst.originalPosition,
			offset: inst.positionAbs,
			item: inst.currentItem,
			sender: _inst ? _inst.element : null
		};
	}

});

}));

/*!
	Autosize 3.0.15
	license: MIT
	http://www.jacklmoore.com/autosize
*/
!function(e,t){if("function"==typeof define&&define.amd)define(["exports","module"],t);else if("undefined"!=typeof exports&&"undefined"!=typeof module)t(exports,module);else{var n={exports:{}};t(n.exports,n),e.autosize=n.exports}}(this,function(e,t){"use strict";function n(e){function t(){var t=window.getComputedStyle(e,null);p=t.overflowY,"vertical"===t.resize?e.style.resize="none":"both"===t.resize&&(e.style.resize="horizontal"),c="content-box"===t.boxSizing?-(parseFloat(t.paddingTop)+parseFloat(t.paddingBottom)):parseFloat(t.borderTopWidth)+parseFloat(t.borderBottomWidth),isNaN(c)&&(c=0),i()}function n(t){var n=e.style.width;e.style.width="0px",e.offsetWidth,e.style.width=n,p=t,f&&(e.style.overflowY=t),o()}function o(){var t=window.pageYOffset,n=document.body.scrollTop,o=e.style.height;e.style.height="auto";var i=e.scrollHeight+c;return 0===e.scrollHeight?void(e.style.height=o):(e.style.height=i+"px",v=e.clientWidth,document.documentElement.scrollTop=t,void(document.body.scrollTop=n))}function i(){var t=e.style.height;o();var i=window.getComputedStyle(e,null);if(i.height!==e.style.height?"visible"!==p&&n("visible"):"hidden"!==p&&n("hidden"),t!==e.style.height){var r=d("autosize:resized");e.dispatchEvent(r)}}var s=void 0===arguments[1]?{}:arguments[1],a=s.setOverflowX,l=void 0===a?!0:a,u=s.setOverflowY,f=void 0===u?!0:u;if(e&&e.nodeName&&"TEXTAREA"===e.nodeName&&!r.has(e)){var c=null,p=null,v=e.clientWidth,h=function(){e.clientWidth!==v&&i()},y=function(t){window.removeEventListener("resize",h,!1),e.removeEventListener("input",i,!1),e.removeEventListener("keyup",i,!1),e.removeEventListener("autosize:destroy",y,!1),e.removeEventListener("autosize:update",i,!1),r["delete"](e),Object.keys(t).forEach(function(n){e.style[n]=t[n]})}.bind(e,{height:e.style.height,resize:e.style.resize,overflowY:e.style.overflowY,overflowX:e.style.overflowX,wordWrap:e.style.wordWrap});e.addEventListener("autosize:destroy",y,!1),"onpropertychange"in e&&"oninput"in e&&e.addEventListener("keyup",i,!1),window.addEventListener("resize",h,!1),e.addEventListener("input",i,!1),e.addEventListener("autosize:update",i,!1),r.add(e),l&&(e.style.overflowX="hidden",e.style.wordWrap="break-word"),t()}}function o(e){if(e&&e.nodeName&&"TEXTAREA"===e.nodeName){var t=d("autosize:destroy");e.dispatchEvent(t)}}function i(e){if(e&&e.nodeName&&"TEXTAREA"===e.nodeName){var t=d("autosize:update");e.dispatchEvent(t)}}var r="function"==typeof Set?new Set:function(){var e=[];return{has:function(t){return Boolean(e.indexOf(t)>-1)},add:function(t){e.push(t)},"delete":function(t){e.splice(e.indexOf(t),1)}}}(),d=function(e){return new Event(e)};try{new Event("test")}catch(s){d=function(e){var t=document.createEvent("Event");return t.initEvent(e,!0,!1),t}}var a=null;"undefined"==typeof window||"function"!=typeof window.getComputedStyle?(a=function(e){return e},a.destroy=function(e){return e},a.update=function(e){return e}):(a=function(e,t){return e&&Array.prototype.forEach.call(e.length?e:[e],function(e){return n(e,t)}),e},a.destroy=function(e){return e&&Array.prototype.forEach.call(e.length?e:[e],o),e},a.update=function(e){return e&&Array.prototype.forEach.call(e.length?e:[e],i),e}),t.exports=a});
/*! X-editable - v1.5.1 
* In-place editing with Twitter Bootstrap, jQuery UI or pure jQuery
* http://github.com/vitalets/x-editable
* Copyright (c) 2013 Vitaliy Potapov; Licensed MIT */
!function(a){"use strict";var b=function(b,c){this.options=a.extend({},a.fn.editableform.defaults,c),this.$div=a(b),this.options.scope||(this.options.scope=this)};b.prototype={constructor:b,initInput:function(){this.input=this.options.input,this.value=this.input.str2value(this.options.value),this.input.prerender()},initTemplate:function(){this.$form=a(a.fn.editableform.template)},initButtons:function(){var b=this.$form.find(".editable-buttons");b.append(a.fn.editableform.buttons),"bottom"===this.options.showbuttons&&b.addClass("editable-buttons-bottom")},render:function(){this.$loading=a(a.fn.editableform.loading),this.$div.empty().append(this.$loading),this.initTemplate(),this.options.showbuttons?this.initButtons():this.$form.find(".editable-buttons").remove(),this.showLoading(),this.isSaving=!1,this.$div.triggerHandler("rendering"),this.initInput(),this.$form.find("div.editable-input").append(this.input.$tpl),this.$div.append(this.$form),a.when(this.input.render()).then(a.proxy(function(){if(this.options.showbuttons||this.input.autosubmit(),this.$form.find(".editable-cancel").click(a.proxy(this.cancel,this)),this.input.error)this.error(this.input.error),this.$form.find(".editable-submit").attr("disabled",!0),this.input.$input.attr("disabled",!0),this.$form.submit(function(a){a.preventDefault()});else{this.error(!1),this.input.$input.removeAttr("disabled"),this.$form.find(".editable-submit").removeAttr("disabled");var b=null===this.value||void 0===this.value||""===this.value?this.options.defaultValue:this.value;this.input.value2input(b),this.$form.submit(a.proxy(this.submit,this))}this.$div.triggerHandler("rendered"),this.showForm(),this.input.postrender&&this.input.postrender()},this))},cancel:function(){this.$div.triggerHandler("cancel")},showLoading:function(){var a,b;this.$form?(a=this.$form.outerWidth(),b=this.$form.outerHeight(),a&&this.$loading.width(a),b&&this.$loading.height(b),this.$form.hide()):(a=this.$loading.parent().width(),a&&this.$loading.width(a)),this.$loading.show()},showForm:function(a){this.$loading.hide(),this.$form.show(),a!==!1&&this.input.activate(),this.$div.triggerHandler("show")},error:function(b){var c,d=this.$form.find(".control-group"),e=this.$form.find(".editable-error-block");if(b===!1)d.removeClass(a.fn.editableform.errorGroupClass),e.removeClass(a.fn.editableform.errorBlockClass).empty().hide();else{if(b){c=(""+b).split("\n");for(var f=0;f<c.length;f++)c[f]=a("<div>").text(c[f]).html();b=c.join("<br>")}d.addClass(a.fn.editableform.errorGroupClass),e.addClass(a.fn.editableform.errorBlockClass).html(b).show()}},submit:function(b){b.stopPropagation(),b.preventDefault();var c=this.input.input2value(),d=this.validate(c);if("object"===a.type(d)&&void 0!==d.newValue){if(c=d.newValue,this.input.value2input(c),"string"==typeof d.msg)return this.error(d.msg),this.showForm(),void 0}else if(d)return this.error(d),this.showForm(),void 0;if(!this.options.savenochange&&this.input.value2str(c)==this.input.value2str(this.value))return this.$div.triggerHandler("nochange"),void 0;var e=this.input.value2submit(c);this.isSaving=!0,a.when(this.save(e)).done(a.proxy(function(a){this.isSaving=!1;var b="function"==typeof this.options.success?this.options.success.call(this.options.scope,a,c):null;return b===!1?(this.error(!1),this.showForm(!1),void 0):"string"==typeof b?(this.error(b),this.showForm(),void 0):(b&&"object"==typeof b&&b.hasOwnProperty("newValue")&&(c=b.newValue),this.error(!1),this.value=c,this.$div.triggerHandler("save",{newValue:c,submitValue:e,response:a}),void 0)},this)).fail(a.proxy(function(a){this.isSaving=!1;var b;b="function"==typeof this.options.error?this.options.error.call(this.options.scope,a,c):"string"==typeof a?a:a.responseText||a.statusText||"Unknown error!",this.error(b),this.showForm()},this))},save:function(b){this.options.pk=a.fn.editableutils.tryParseJson(this.options.pk,!0);var c,d="function"==typeof this.options.pk?this.options.pk.call(this.options.scope):this.options.pk,e=!!("function"==typeof this.options.url||this.options.url&&("always"===this.options.send||"auto"===this.options.send&&null!==d&&void 0!==d));return e?(this.showLoading(),c={name:this.options.name||"",value:b,pk:d},"function"==typeof this.options.params?c=this.options.params.call(this.options.scope,c):(this.options.params=a.fn.editableutils.tryParseJson(this.options.params,!0),a.extend(c,this.options.params)),"function"==typeof this.options.url?this.options.url.call(this.options.scope,c):a.ajax(a.extend({url:this.options.url,data:c,type:"POST"},this.options.ajaxOptions))):void 0},validate:function(a){return void 0===a&&(a=this.value),"function"==typeof this.options.validate?this.options.validate.call(this.options.scope,a):void 0},option:function(a,b){a in this.options&&(this.options[a]=b),"value"===a&&this.setValue(b)},setValue:function(a,b){this.value=b?this.input.str2value(a):a,this.$form&&this.$form.is(":visible")&&this.input.value2input(this.value)}},a.fn.editableform=function(c){var d=arguments;return this.each(function(){var e=a(this),f=e.data("editableform"),g="object"==typeof c&&c;f||e.data("editableform",f=new b(this,g)),"string"==typeof c&&f[c].apply(f,Array.prototype.slice.call(d,1))})},a.fn.editableform.Constructor=b,a.fn.editableform.defaults={type:"text",url:null,params:null,name:null,pk:null,value:null,defaultValue:null,send:"auto",validate:null,success:null,error:null,ajaxOptions:null,showbuttons:!0,scope:null,savenochange:!1},a.fn.editableform.template='<form class="form-inline editableform"><div class="control-group"><div><div class="editable-input"></div><div class="editable-buttons"></div></div><div class="editable-error-block"></div></div></form>',a.fn.editableform.loading='<div class="editableform-loading"></div>',a.fn.editableform.buttons='<button type="submit" class="editable-submit">ok</button><button type="button" class="editable-cancel">cancel</button>',a.fn.editableform.errorGroupClass=null,a.fn.editableform.errorBlockClass="editable-error",a.fn.editableform.engine="jquery"}(window.jQuery),function(a){"use strict";a.fn.editableutils={inherit:function(a,b){var c=function(){};c.prototype=b.prototype,a.prototype=new c,a.prototype.constructor=a,a.superclass=b.prototype},setCursorPosition:function(a,b){if(a.setSelectionRange)a.setSelectionRange(b,b);else if(a.createTextRange){var c=a.createTextRange();c.collapse(!0),c.moveEnd("character",b),c.moveStart("character",b),c.select()}},tryParseJson:function(a,b){if("string"==typeof a&&a.length&&a.match(/^[\{\[].*[\}\]]$/))if(b)try{a=new Function("return "+a)()}catch(c){}finally{return a}else a=new Function("return "+a)();return a},sliceObj:function(b,c,d){var e,f,g={};if(!a.isArray(c)||!c.length)return g;for(var h=0;h<c.length;h++)e=c[h],b.hasOwnProperty(e)&&(g[e]=b[e]),d!==!0&&(f=e.toLowerCase(),b.hasOwnProperty(f)&&(g[e]=b[f]));return g},getConfigData:function(b){var c={};return a.each(b.data(),function(a,b){("object"!=typeof b||b&&"object"==typeof b&&(b.constructor===Object||b.constructor===Array))&&(c[a]=b)}),c},objectKeys:function(a){if(Object.keys)return Object.keys(a);if(a!==Object(a))throw new TypeError("Object.keys called on a non-object");var b,c=[];for(b in a)Object.prototype.hasOwnProperty.call(a,b)&&c.push(b);return c},escape:function(b){return a("<div>").text(b).html()},itemsByValue:function(b,c,d){if(!c||null===b)return[];if("function"!=typeof d){var e=d||"value";d=function(a){return a[e]}}var f=a.isArray(b),g=[],h=this;return a.each(c,function(c,e){if(e.children)g=g.concat(h.itemsByValue(b,e.children,d));else if(f)a.grep(b,function(a){return a==(e&&"object"==typeof e?d(e):e)}).length&&g.push(e);else{var i=e&&"object"==typeof e?d(e):e;b==i&&g.push(e)}}),g},createInput:function(b){var c,d,e,f=b.type;return"date"===f&&("inline"===b.mode?a.fn.editabletypes.datefield?f="datefield":a.fn.editabletypes.dateuifield&&(f="dateuifield"):a.fn.editabletypes.date?f="date":a.fn.editabletypes.dateui&&(f="dateui"),"date"!==f||a.fn.editabletypes.date||(f="combodate")),"datetime"===f&&"inline"===b.mode&&(f="datetimefield"),"wysihtml5"!==f||a.fn.editabletypes[f]||(f="textarea"),"function"==typeof a.fn.editabletypes[f]?(c=a.fn.editabletypes[f],d=this.sliceObj(b,this.objectKeys(c.defaults)),e=new c(d)):(a.error("Unknown type: "+f),!1)},supportsTransitions:function(){var a=document.body||document.documentElement,b=a.style,c="transition",d=["Moz","Webkit","Khtml","O","ms"];if("string"==typeof b[c])return!0;c=c.charAt(0).toUpperCase()+c.substr(1);for(var e=0;e<d.length;e++)if("string"==typeof b[d[e]+c])return!0;return!1}}}(window.jQuery),function(a){"use strict";var b=function(a,b){this.init(a,b)},c=function(a,b){this.init(a,b)};b.prototype={containerName:null,containerDataName:null,innerCss:null,containerClass:"editable-container editable-popup",defaults:{},init:function(c,d){this.$element=a(c),this.options=a.extend({},a.fn.editableContainer.defaults,d),this.splitOptions(),this.formOptions.scope=this.$element[0],this.initContainer(),this.delayedHide=!1,this.$element.on("destroyed",a.proxy(function(){this.destroy()},this)),a(document).data("editable-handlers-attached")||(a(document).on("keyup.editable",function(b){27===b.which&&a(".editable-open").editableContainer("hide")}),a(document).on("click.editable",function(c){var d,e=a(c.target),f=[".editable-container",".ui-datepicker-header",".datepicker",".modal-backdrop",".bootstrap-wysihtml5-insert-image-modal",".bootstrap-wysihtml5-insert-link-modal"];if(a.contains(document.documentElement,c.target)&&!e.is(document)){for(d=0;d<f.length;d++)if(e.is(f[d])||e.parents(f[d]).length)return;b.prototype.closeOthers(c.target)}}),a(document).data("editable-handlers-attached",!0))},splitOptions:function(){if(this.containerOptions={},this.formOptions={},!a.fn[this.containerName])throw new Error(this.containerName+" not found. Have you included corresponding js file?");for(var b in this.options)b in this.defaults?this.containerOptions[b]=this.options[b]:this.formOptions[b]=this.options[b]},tip:function(){return this.container()?this.container().$tip:null},container:function(){var a;return this.containerDataName&&(a=this.$element.data(this.containerDataName))?a:a=this.$element.data(this.containerName)},call:function(){this.$element[this.containerName].apply(this.$element,arguments)},initContainer:function(){this.call(this.containerOptions)},renderForm:function(){this.$form.editableform(this.formOptions).on({save:a.proxy(this.save,this),nochange:a.proxy(function(){this.hide("nochange")},this),cancel:a.proxy(function(){this.hide("cancel")},this),show:a.proxy(function(){this.delayedHide?(this.hide(this.delayedHide.reason),this.delayedHide=!1):this.setPosition()},this),rendering:a.proxy(this.setPosition,this),resize:a.proxy(this.setPosition,this),rendered:a.proxy(function(){this.$element.triggerHandler("shown",a(this.options.scope).data("editable"))},this)}).editableform("render")},show:function(b){this.$element.addClass("editable-open"),b!==!1&&this.closeOthers(this.$element[0]),this.innerShow(),this.tip().addClass(this.containerClass),this.$form,this.$form=a("<div>"),this.tip().is(this.innerCss)?this.tip().append(this.$form):this.tip().find(this.innerCss).append(this.$form),this.renderForm()},hide:function(a){if(this.tip()&&this.tip().is(":visible")&&this.$element.hasClass("editable-open")){if(this.$form.data("editableform").isSaving)return this.delayedHide={reason:a},void 0;this.delayedHide=!1,this.$element.removeClass("editable-open"),this.innerHide(),this.$element.triggerHandler("hidden",a||"manual")}},innerShow:function(){},innerHide:function(){},toggle:function(a){this.container()&&this.tip()&&this.tip().is(":visible")?this.hide():this.show(a)},setPosition:function(){},save:function(a,b){this.$element.triggerHandler("save",b),this.hide("save")},option:function(a,b){this.options[a]=b,a in this.containerOptions?(this.containerOptions[a]=b,this.setContainerOption(a,b)):(this.formOptions[a]=b,this.$form&&this.$form.editableform("option",a,b))},setContainerOption:function(a,b){this.call("option",a,b)},destroy:function(){this.hide(),this.innerDestroy(),this.$element.off("destroyed"),this.$element.removeData("editableContainer")},innerDestroy:function(){},closeOthers:function(b){a(".editable-open").each(function(c,d){if(d!==b&&!a(d).find(b).length){var e=a(d),f=e.data("editableContainer");f&&("cancel"===f.options.onblur?e.data("editableContainer").hide("onblur"):"submit"===f.options.onblur&&e.data("editableContainer").tip().find("form").submit())}})},activate:function(){this.tip&&this.tip().is(":visible")&&this.$form&&this.$form.data("editableform").input.activate()}},a.fn.editableContainer=function(d){var e=arguments;return this.each(function(){var f=a(this),g="editableContainer",h=f.data(g),i="object"==typeof d&&d,j="inline"===i.mode?c:b;h||f.data(g,h=new j(this,i)),"string"==typeof d&&h[d].apply(h,Array.prototype.slice.call(e,1))})},a.fn.editableContainer.Popup=b,a.fn.editableContainer.Inline=c,a.fn.editableContainer.defaults={value:null,placement:"top",autohide:!0,onblur:"cancel",anim:!1,mode:"popup"},jQuery.event.special.destroyed={remove:function(a){a.handler&&a.handler()}}}(window.jQuery),function(a){"use strict";a.extend(a.fn.editableContainer.Inline.prototype,a.fn.editableContainer.Popup.prototype,{containerName:"editableform",innerCss:".editable-inline",containerClass:"editable-container editable-inline",initContainer:function(){this.$tip=a("<span></span>"),this.options.anim||(this.options.anim=0)},splitOptions:function(){this.containerOptions={},this.formOptions=this.options},tip:function(){return this.$tip},innerShow:function(){this.$element.hide(),this.tip().insertAfter(this.$element).show()},innerHide:function(){this.$tip.hide(this.options.anim,a.proxy(function(){this.$element.show(),this.innerDestroy()},this))},innerDestroy:function(){this.tip()&&this.tip().empty().remove()}})}(window.jQuery),function(a){"use strict";var b=function(b,c){this.$element=a(b),this.options=a.extend({},a.fn.editable.defaults,c,a.fn.editableutils.getConfigData(this.$element)),this.options.selector?this.initLive():this.init(),this.options.highlight&&!a.fn.editableutils.supportsTransitions()&&(this.options.highlight=!1)};b.prototype={constructor:b,init:function(){var b,c=!1;if(this.options.name=this.options.name||this.$element.attr("id"),this.options.scope=this.$element[0],this.input=a.fn.editableutils.createInput(this.options),this.input){switch(void 0===this.options.value||null===this.options.value?(this.value=this.input.html2value(a.trim(this.$element.html())),c=!0):(this.options.value=a.fn.editableutils.tryParseJson(this.options.value,!0),this.value="string"==typeof this.options.value?this.input.str2value(this.options.value):this.options.value),this.$element.addClass("editable"),"textarea"===this.input.type&&this.$element.addClass("editable-pre-wrapped"),"manual"!==this.options.toggle?(this.$element.addClass("editable-click"),this.$element.on(this.options.toggle+".editable",a.proxy(function(a){if(this.options.disabled||a.preventDefault(),"mouseenter"===this.options.toggle)this.show();else{var b="click"!==this.options.toggle;this.toggle(b)}},this))):this.$element.attr("tabindex",-1),"function"==typeof this.options.display&&(this.options.autotext="always"),this.options.autotext){case"always":b=!0;break;case"auto":b=!a.trim(this.$element.text()).length&&null!==this.value&&void 0!==this.value&&!c;break;default:b=!1}a.when(b?this.render():!0).then(a.proxy(function(){this.options.disabled?this.disable():this.enable(),this.$element.triggerHandler("init",this)},this))}},initLive:function(){var b=this.options.selector;this.options.selector=!1,this.options.autotext="never",this.$element.on(this.options.toggle+".editable",b,a.proxy(function(b){var c=a(b.target);c.data("editable")||(c.hasClass(this.options.emptyclass)&&c.empty(),c.editable(this.options).trigger(b))},this))},render:function(a){return this.options.display!==!1?this.input.value2htmlFinal?this.input.value2html(this.value,this.$element[0],this.options.display,a):"function"==typeof this.options.display?this.options.display.call(this.$element[0],this.value,a):this.input.value2html(this.value,this.$element[0]):void 0},enable:function(){this.options.disabled=!1,this.$element.removeClass("editable-disabled"),this.handleEmpty(this.isEmpty),"manual"!==this.options.toggle&&"-1"===this.$element.attr("tabindex")&&this.$element.removeAttr("tabindex")},disable:function(){this.options.disabled=!0,this.hide(),this.$element.addClass("editable-disabled"),this.handleEmpty(this.isEmpty),this.$element.attr("tabindex",-1)},toggleDisabled:function(){this.options.disabled?this.enable():this.disable()},option:function(b,c){return b&&"object"==typeof b?(a.each(b,a.proxy(function(b,c){this.option(a.trim(b),c)},this)),void 0):(this.options[b]=c,"disabled"===b?c?this.disable():this.enable():("value"===b&&this.setValue(c),this.container&&this.container.option(b,c),this.input.option&&this.input.option(b,c),void 0))},handleEmpty:function(b){this.options.display!==!1&&(this.isEmpty=void 0!==b?b:"function"==typeof this.input.isEmpty?this.input.isEmpty(this.$element):""===a.trim(this.$element.html()),this.options.disabled?this.isEmpty&&(this.$element.empty(),this.options.emptyclass&&this.$element.removeClass(this.options.emptyclass)):this.isEmpty?(this.$element.html(this.options.emptytext),this.options.emptyclass&&this.$element.addClass(this.options.emptyclass)):this.options.emptyclass&&this.$element.removeClass(this.options.emptyclass))},show:function(b){if(!this.options.disabled){if(this.container){if(this.container.tip().is(":visible"))return}else{var c=a.extend({},this.options,{value:this.value,input:this.input});this.$element.editableContainer(c),this.$element.on("save.internal",a.proxy(this.save,this)),this.container=this.$element.data("editableContainer")}this.container.show(b)}},hide:function(){this.container&&this.container.hide()},toggle:function(a){this.container&&this.container.tip().is(":visible")?this.hide():this.show(a)},save:function(a,b){if(this.options.unsavedclass){var c=!1;c=c||"function"==typeof this.options.url,c=c||this.options.display===!1,c=c||void 0!==b.response,c=c||this.options.savenochange&&this.input.value2str(this.value)!==this.input.value2str(b.newValue),c?this.$element.removeClass(this.options.unsavedclass):this.$element.addClass(this.options.unsavedclass)}if(this.options.highlight){var d=this.$element,e=d.css("background-color");d.css("background-color",this.options.highlight),setTimeout(function(){"transparent"===e&&(e=""),d.css("background-color",e),d.addClass("editable-bg-transition"),setTimeout(function(){d.removeClass("editable-bg-transition")},1700)},10)}this.setValue(b.newValue,!1,b.response)},validate:function(){return"function"==typeof this.options.validate?this.options.validate.call(this,this.value):void 0},setValue:function(b,c,d){this.value=c?this.input.str2value(b):b,this.container&&this.container.option("value",this.value),a.when(this.render(d)).then(a.proxy(function(){this.handleEmpty()},this))},activate:function(){this.container&&this.container.activate()},destroy:function(){this.disable(),this.container&&this.container.destroy(),this.input.destroy(),"manual"!==this.options.toggle&&(this.$element.removeClass("editable-click"),this.$element.off(this.options.toggle+".editable")),this.$element.off("save.internal"),this.$element.removeClass("editable editable-open editable-disabled"),this.$element.removeData("editable")}},a.fn.editable=function(c){var d={},e=arguments,f="editable";switch(c){case"validate":return this.each(function(){var b,c=a(this),e=c.data(f);e&&(b=e.validate())&&(d[e.options.name]=b)}),d;case"getValue":return 2===arguments.length&&arguments[1]===!0?d=this.eq(0).data(f).value:this.each(function(){var b=a(this),c=b.data(f);c&&void 0!==c.value&&null!==c.value&&(d[c.options.name]=c.input.value2submit(c.value))}),d;case"submit":var g=arguments[1]||{},h=this,i=this.editable("validate");if(a.isEmptyObject(i)){var j={};if(1===h.length){var k=h.data("editable"),l={name:k.options.name||"",value:k.input.value2submit(k.value),pk:"function"==typeof k.options.pk?k.options.pk.call(k.options.scope):k.options.pk};"function"==typeof k.options.params?l=k.options.params.call(k.options.scope,l):(k.options.params=a.fn.editableutils.tryParseJson(k.options.params,!0),a.extend(l,k.options.params)),j={url:k.options.url,data:l,type:"POST"},g.success=g.success||k.options.success,g.error=g.error||k.options.error}else{var m=this.editable("getValue");j={url:g.url,data:m,type:"POST"}}j.success="function"==typeof g.success?function(a){g.success.call(h,a,g)}:a.noop,j.error="function"==typeof g.error?function(){g.error.apply(h,arguments)}:a.noop,g.ajaxOptions&&a.extend(j,g.ajaxOptions),g.data&&a.extend(j.data,g.data),a.ajax(j)}else"function"==typeof g.error&&g.error.call(h,i);return this}return this.each(function(){var d=a(this),g=d.data(f),h="object"==typeof c&&c;return h&&h.selector?(g=new b(this,h),void 0):(g||d.data(f,g=new b(this,h)),"string"==typeof c&&g[c].apply(g,Array.prototype.slice.call(e,1)),void 0)})},a.fn.editable.defaults={type:"text",disabled:!1,toggle:"click",emptytext:"Empty",autotext:"auto",value:null,display:null,emptyclass:"editable-empty",unsavedclass:"editable-unsaved",selector:null,highlight:"#FFFF80"}}(window.jQuery),function(a){"use strict";a.fn.editabletypes={};var b=function(){};b.prototype={init:function(b,c,d){this.type=b,this.options=a.extend({},d,c)},prerender:function(){this.$tpl=a(this.options.tpl),this.$input=this.$tpl,this.$clear=null,this.error=null},render:function(){},value2html:function(b,c){a(c)[this.options.escape?"text":"html"](a.trim(b))},html2value:function(b){return a("<div>").html(b).text()},value2str:function(a){return a},str2value:function(a){return a},value2submit:function(a){return a},value2input:function(a){this.$input.val(a)},input2value:function(){return this.$input.val()},activate:function(){this.$input.is(":visible")&&this.$input.focus()},clear:function(){this.$input.val(null)},escape:function(b){return a("<div>").text(b).html()},autosubmit:function(){},destroy:function(){},setClass:function(){this.options.inputclass&&this.$input.addClass(this.options.inputclass)},setAttr:function(a){void 0!==this.options[a]&&null!==this.options[a]&&this.$input.attr(a,this.options[a])},option:function(a,b){this.options[a]=b}},b.defaults={tpl:"",inputclass:null,escape:!0,scope:null,showbuttons:!0},a.extend(a.fn.editabletypes,{abstractinput:b})}(window.jQuery),function(a){"use strict";var b=function(){};a.fn.editableutils.inherit(b,a.fn.editabletypes.abstractinput),a.extend(b.prototype,{render:function(){var b=a.Deferred();return this.error=null,this.onSourceReady(function(){this.renderList(),b.resolve()},function(){this.error=this.options.sourceError,b.resolve()}),b.promise()},html2value:function(){return null},value2html:function(b,c,d,e){var f=a.Deferred(),g=function(){"function"==typeof d?d.call(c,b,this.sourceData,e):this.value2htmlFinal(b,c),f.resolve()};return null===b?g.call(this):this.onSourceReady(g,function(){f.resolve()}),f.promise()},onSourceReady:function(b,c){var d;if(a.isFunction(this.options.source)?(d=this.options.source.call(this.options.scope),this.sourceData=null):d=this.options.source,this.options.sourceCache&&a.isArray(this.sourceData))return b.call(this),void 0;try{d=a.fn.editableutils.tryParseJson(d,!1)}catch(e){return c.call(this),void 0}if("string"==typeof d){if(this.options.sourceCache){var f,g=d;if(a(document).data(g)||a(document).data(g,{}),f=a(document).data(g),f.loading===!1&&f.sourceData)return this.sourceData=f.sourceData,this.doPrepend(),b.call(this),void 0;if(f.loading===!0)return f.callbacks.push(a.proxy(function(){this.sourceData=f.sourceData,this.doPrepend(),b.call(this)},this)),f.err_callbacks.push(a.proxy(c,this)),void 0;f.loading=!0,f.callbacks=[],f.err_callbacks=[]}var h=a.extend({url:d,type:"get",cache:!1,dataType:"json",success:a.proxy(function(d){f&&(f.loading=!1),this.sourceData=this.makeArray(d),a.isArray(this.sourceData)?(f&&(f.sourceData=this.sourceData,a.each(f.callbacks,function(){this.call()})),this.doPrepend(),b.call(this)):(c.call(this),f&&a.each(f.err_callbacks,function(){this.call()}))},this),error:a.proxy(function(){c.call(this),f&&(f.loading=!1,a.each(f.err_callbacks,function(){this.call()}))},this)},this.options.sourceOptions);a.ajax(h)}else this.sourceData=this.makeArray(d),a.isArray(this.sourceData)?(this.doPrepend(),b.call(this)):c.call(this)},doPrepend:function(){null!==this.options.prepend&&void 0!==this.options.prepend&&(a.isArray(this.prependData)||(a.isFunction(this.options.prepend)&&(this.options.prepend=this.options.prepend.call(this.options.scope)),this.options.prepend=a.fn.editableutils.tryParseJson(this.options.prepend,!0),"string"==typeof this.options.prepend&&(this.options.prepend={"":this.options.prepend}),this.prependData=this.makeArray(this.options.prepend)),a.isArray(this.prependData)&&a.isArray(this.sourceData)&&(this.sourceData=this.prependData.concat(this.sourceData)))},renderList:function(){},value2htmlFinal:function(){},makeArray:function(b){var c,d,e,f,g=[];if(!b||"string"==typeof b)return null;if(a.isArray(b)){f=function(a,b){return d={value:a,text:b},c++>=2?!1:void 0};for(var h=0;h<b.length;h++)e=b[h],"object"==typeof e?(c=0,a.each(e,f),1===c?g.push(d):c>1&&(e.children&&(e.children=this.makeArray(e.children)),g.push(e))):g.push({value:e,text:e})}else a.each(b,function(a,b){g.push({value:a,text:b})});return g},option:function(a,b){this.options[a]=b,"source"===a&&(this.sourceData=null),"prepend"===a&&(this.prependData=null)}}),b.defaults=a.extend({},a.fn.editabletypes.abstractinput.defaults,{source:null,prepend:!1,sourceError:"Error when loading list",sourceCache:!0,sourceOptions:null}),a.fn.editabletypes.list=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("text",a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.abstractinput),a.extend(b.prototype,{render:function(){this.renderClear(),this.setClass(),this.setAttr("placeholder")},activate:function(){this.$input.is(":visible")&&(this.$input.focus(),a.fn.editableutils.setCursorPosition(this.$input.get(0),this.$input.val().length),this.toggleClear&&this.toggleClear())},renderClear:function(){this.options.clear&&(this.$clear=a('<span class="editable-clear-x"></span>'),this.$input.after(this.$clear).css("padding-right",24).keyup(a.proxy(function(b){if(!~a.inArray(b.keyCode,[40,38,9,13,27])){clearTimeout(this.t);var c=this;this.t=setTimeout(function(){c.toggleClear(b)},100)}},this)).parent().css("position","relative"),this.$clear.click(a.proxy(this.clear,this)))},postrender:function(){},toggleClear:function(){if(this.$clear){var a=this.$input.val().length,b=this.$clear.is(":visible");a&&!b&&this.$clear.show(),!a&&b&&this.$clear.hide()}},clear:function(){this.$clear.hide(),this.$input.val("").focus()}}),b.defaults=a.extend({},a.fn.editabletypes.abstractinput.defaults,{tpl:'<input type="text">',placeholder:null,clear:!0}),a.fn.editabletypes.text=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("textarea",a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.abstractinput),a.extend(b.prototype,{render:function(){this.setClass(),this.setAttr("placeholder"),this.setAttr("rows"),this.$input.keydown(function(b){b.ctrlKey&&13===b.which&&a(this).closest("form").submit()})},activate:function(){a.fn.editabletypes.text.prototype.activate.call(this)}}),b.defaults=a.extend({},a.fn.editabletypes.abstractinput.defaults,{tpl:"<textarea></textarea>",inputclass:"input-large",placeholder:null,rows:7}),a.fn.editabletypes.textarea=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("select",a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.list),a.extend(b.prototype,{renderList:function(){this.$input.empty();var b=function(c,d){var e;if(a.isArray(d))for(var f=0;f<d.length;f++)e={},d[f].children?(e.label=d[f].text,c.append(b(a("<optgroup>",e),d[f].children))):(e.value=d[f].value,d[f].disabled&&(e.disabled=!0),c.append(a("<option>",e).text(d[f].text)));return c};b(this.$input,this.sourceData),this.setClass(),this.$input.on("keydown.editable",function(b){13===b.which&&a(this).closest("form").submit()})},value2htmlFinal:function(b,c){var d="",e=a.fn.editableutils.itemsByValue(b,this.sourceData);e.length&&(d=e[0].text),a.fn.editabletypes.abstractinput.prototype.value2html.call(this,d,c)},autosubmit:function(){this.$input.off("keydown.editable").on("change.editable",function(){a(this).closest("form").submit()})}}),b.defaults=a.extend({},a.fn.editabletypes.list.defaults,{tpl:"<select></select>"}),a.fn.editabletypes.select=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("checklist",a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.list),a.extend(b.prototype,{renderList:function(){var b;if(this.$tpl.empty(),a.isArray(this.sourceData)){for(var c=0;c<this.sourceData.length;c++)b=a("<label>").append(a("<input>",{type:"checkbox",value:this.sourceData[c].value})).append(a("<span>").text(" "+this.sourceData[c].text)),a("<div>").append(b).appendTo(this.$tpl);this.$input=this.$tpl.find('input[type="checkbox"]'),this.setClass()}},value2str:function(b){return a.isArray(b)?b.sort().join(a.trim(this.options.separator)):""},str2value:function(b){var c,d=null;return"string"==typeof b&&b.length?(c=new RegExp("\\s*"+a.trim(this.options.separator)+"\\s*"),d=b.split(c)):d=a.isArray(b)?b:[b],d},value2input:function(b){this.$input.prop("checked",!1),a.isArray(b)&&b.length&&this.$input.each(function(c,d){var e=a(d);a.each(b,function(a,b){e.val()==b&&e.prop("checked",!0)})})},input2value:function(){var b=[];return this.$input.filter(":checked").each(function(c,d){b.push(a(d).val())}),b},value2htmlFinal:function(b,c){var d=[],e=a.fn.editableutils.itemsByValue(b,this.sourceData),f=this.options.escape;e.length?(a.each(e,function(b,c){var e=f?a.fn.editableutils.escape(c.text):c.text;d.push(e)}),a(c).html(d.join("<br>"))):a(c).empty()},activate:function(){this.$input.first().focus()},autosubmit:function(){this.$input.on("keydown",function(b){13===b.which&&a(this).closest("form").submit()})}}),b.defaults=a.extend({},a.fn.editabletypes.list.defaults,{tpl:'<div class="editable-checklist"></div>',inputclass:null,separator:","}),a.fn.editabletypes.checklist=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("password",a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.text),a.extend(b.prototype,{value2html:function(b,c){b?a(c).text("[hidden]"):a(c).empty()},html2value:function(){return null}}),b.defaults=a.extend({},a.fn.editabletypes.text.defaults,{tpl:'<input type="password">'}),a.fn.editabletypes.password=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("email",a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.text),b.defaults=a.extend({},a.fn.editabletypes.text.defaults,{tpl:'<input type="email">'}),a.fn.editabletypes.email=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("url",a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.text),b.defaults=a.extend({},a.fn.editabletypes.text.defaults,{tpl:'<input type="url">'}),a.fn.editabletypes.url=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("tel",a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.text),b.defaults=a.extend({},a.fn.editabletypes.text.defaults,{tpl:'<input type="tel">'}),a.fn.editabletypes.tel=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("number",a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.text),a.extend(b.prototype,{render:function(){b.superclass.render.call(this),this.setAttr("min"),this.setAttr("max"),this.setAttr("step")},postrender:function(){this.$clear&&this.$clear.css({right:24})}}),b.defaults=a.extend({},a.fn.editabletypes.text.defaults,{tpl:'<input type="number">',inputclass:"input-mini",min:null,max:null,step:null}),a.fn.editabletypes.number=b}(window.jQuery),function(a){"use strict";
var b=function(a){this.init("range",a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.number),a.extend(b.prototype,{render:function(){this.$input=this.$tpl.filter("input"),this.setClass(),this.setAttr("min"),this.setAttr("max"),this.setAttr("step"),this.$input.on("input",function(){a(this).siblings("output").text(a(this).val())})},activate:function(){this.$input.focus()}}),b.defaults=a.extend({},a.fn.editabletypes.number.defaults,{tpl:'<input type="range"><output style="width: 30px; display: inline-block"></output>',inputclass:"input-medium"}),a.fn.editabletypes.range=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("time",a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.abstractinput),a.extend(b.prototype,{render:function(){this.setClass()}}),b.defaults=a.extend({},a.fn.editabletypes.abstractinput.defaults,{tpl:'<input type="time">'}),a.fn.editabletypes.time=b}(window.jQuery),function(a){"use strict";var b=function(c){if(this.init("select2",c,b.defaults),c.select2=c.select2||{},this.sourceData=null,c.placeholder&&(c.select2.placeholder=c.placeholder),!c.select2.tags&&c.source){var d=c.source;a.isFunction(c.source)&&(d=c.source.call(c.scope)),"string"==typeof d?(c.select2.ajax=c.select2.ajax||{},c.select2.ajax.data||(c.select2.ajax.data=function(a){return{query:a}}),c.select2.ajax.results||(c.select2.ajax.results=function(a){return{results:a}}),c.select2.ajax.url=d):(this.sourceData=this.convertSource(d),c.select2.data=this.sourceData)}if(this.options.select2=a.extend({},b.defaults.select2,c.select2),this.isMultiple=this.options.select2.tags||this.options.select2.multiple,this.isRemote="ajax"in this.options.select2,this.idFunc=this.options.select2.id,"function"!=typeof this.idFunc){var e=this.idFunc||"id";this.idFunc=function(a){return a[e]}}this.formatSelection=this.options.select2.formatSelection,"function"!=typeof this.formatSelection&&(this.formatSelection=function(a){return a.text})};a.fn.editableutils.inherit(b,a.fn.editabletypes.abstractinput),a.extend(b.prototype,{render:function(){this.setClass(),this.isRemote&&this.$input.on("select2-loaded",a.proxy(function(a){this.sourceData=a.items.results},this)),this.isMultiple&&this.$input.on("change",function(){a(this).closest("form").parent().triggerHandler("resize")})},value2html:function(c,d){var e,f="",g=this;this.options.select2.tags?e=c:this.sourceData&&(e=a.fn.editableutils.itemsByValue(c,this.sourceData,this.idFunc)),a.isArray(e)?(f=[],a.each(e,function(a,b){f.push(b&&"object"==typeof b?g.formatSelection(b):b)})):e&&(f=g.formatSelection(e)),f=a.isArray(f)?f.join(this.options.viewseparator):f,b.superclass.value2html.call(this,f,d)},html2value:function(a){return this.options.select2.tags?this.str2value(a,this.options.viewseparator):null},value2input:function(b){if(a.isArray(b)&&(b=b.join(this.getSeparator())),this.$input.data("select2")?this.$input.val(b).trigger("change",!0):(this.$input.val(b),this.$input.select2(this.options.select2)),this.isRemote&&!this.isMultiple&&!this.options.select2.initSelection){var c=this.options.select2.id,d=this.options.select2.formatSelection;if(!c&&!d){var e=a(this.options.scope);if(!e.data("editable").isEmpty){var f={id:b,text:e.text()};this.$input.select2("data",f)}}}},input2value:function(){return this.$input.select2("val")},str2value:function(b,c){if("string"!=typeof b||!this.isMultiple)return b;c=c||this.getSeparator();var d,e,f;if(null===b||b.length<1)return null;for(d=b.split(c),e=0,f=d.length;f>e;e+=1)d[e]=a.trim(d[e]);return d},autosubmit:function(){this.$input.on("change",function(b,c){c||a(this).closest("form").submit()})},getSeparator:function(){return this.options.select2.separator||a.fn.select2.defaults.separator},convertSource:function(b){if(a.isArray(b)&&b.length&&void 0!==b[0].value)for(var c=0;c<b.length;c++)void 0!==b[c].value&&(b[c].id=b[c].value,delete b[c].value);return b},destroy:function(){this.$input.data("select2")&&this.$input.select2("destroy")}}),b.defaults=a.extend({},a.fn.editabletypes.abstractinput.defaults,{tpl:'<input type="hidden">',select2:null,placeholder:null,source:null,viewseparator:", "}),a.fn.editabletypes.select2=b}(window.jQuery),function(a){var b=function(b,c){return this.$element=a(b),this.$element.is("input")?(this.options=a.extend({},a.fn.combodate.defaults,c,this.$element.data()),this.init(),void 0):(a.error("Combodate should be applied to INPUT element"),void 0)};b.prototype={constructor:b,init:function(){this.map={day:["D","date"],month:["M","month"],year:["Y","year"],hour:["[Hh]","hours"],minute:["m","minutes"],second:["s","seconds"],ampm:["[Aa]",""]},this.$widget=a('<span class="combodate"></span>').html(this.getTemplate()),this.initCombos(),this.$widget.on("change","select",a.proxy(function(b){this.$element.val(this.getValue()).change(),this.options.smartDays&&(a(b.target).is(".month")||a(b.target).is(".year"))&&this.fillCombo("day")},this)),this.$widget.find("select").css("width","auto"),this.$element.hide().after(this.$widget),this.setValue(this.$element.val()||this.options.value)},getTemplate:function(){var b=this.options.template;return a.each(this.map,function(a,c){c=c[0];var d=new RegExp(c+"+"),e=c.length>1?c.substring(1,2):c;b=b.replace(d,"{"+e+"}")}),b=b.replace(/ /g,"&nbsp;"),a.each(this.map,function(a,c){c=c[0];var d=c.length>1?c.substring(1,2):c;b=b.replace("{"+d+"}",'<select class="'+a+'"></select>')}),b},initCombos:function(){for(var a in this.map){var b=this.$widget.find("."+a);this["$"+a]=b.length?b:null,this.fillCombo(a)}},fillCombo:function(a){var b=this["$"+a];if(b){var c="fill"+a.charAt(0).toUpperCase()+a.slice(1),d=this[c](),e=b.val();b.empty();for(var f=0;f<d.length;f++)b.append('<option value="'+d[f][0]+'">'+d[f][1]+"</option>");b.val(e)}},fillCommon:function(a){var b,c=[];if("name"===this.options.firstItem){b=moment.relativeTime||moment.langData()._relativeTime;var d="function"==typeof b[a]?b[a](1,!0,a,!1):b[a];d=d.split(" ").reverse()[0],c.push(["",d])}else"empty"===this.options.firstItem&&c.push(["",""]);return c},fillDay:function(){var a,b,c=this.fillCommon("d"),d=-1!==this.options.template.indexOf("DD"),e=31;if(this.options.smartDays&&this.$month&&this.$year){var f=parseInt(this.$month.val(),10),g=parseInt(this.$year.val(),10);isNaN(f)||isNaN(g)||(e=moment([g,f]).daysInMonth())}for(b=1;e>=b;b++)a=d?this.leadZero(b):b,c.push([b,a]);return c},fillMonth:function(){var a,b,c=this.fillCommon("M"),d=-1!==this.options.template.indexOf("MMMM"),e=-1!==this.options.template.indexOf("MMM"),f=-1!==this.options.template.indexOf("MM");for(b=0;11>=b;b++)a=d?moment().date(1).month(b).format("MMMM"):e?moment().date(1).month(b).format("MMM"):f?this.leadZero(b+1):b+1,c.push([b,a]);return c},fillYear:function(){var a,b,c=[],d=-1!==this.options.template.indexOf("YYYY");for(b=this.options.maxYear;b>=this.options.minYear;b--)a=d?b:(b+"").substring(2),c[this.options.yearDescending?"push":"unshift"]([b,a]);return c=this.fillCommon("y").concat(c)},fillHour:function(){var a,b,c=this.fillCommon("h"),d=-1!==this.options.template.indexOf("h"),e=(-1!==this.options.template.indexOf("H"),-1!==this.options.template.toLowerCase().indexOf("hh")),f=d?1:0,g=d?12:23;for(b=f;g>=b;b++)a=e?this.leadZero(b):b,c.push([b,a]);return c},fillMinute:function(){var a,b,c=this.fillCommon("m"),d=-1!==this.options.template.indexOf("mm");for(b=0;59>=b;b+=this.options.minuteStep)a=d?this.leadZero(b):b,c.push([b,a]);return c},fillSecond:function(){var a,b,c=this.fillCommon("s"),d=-1!==this.options.template.indexOf("ss");for(b=0;59>=b;b+=this.options.secondStep)a=d?this.leadZero(b):b,c.push([b,a]);return c},fillAmpm:function(){var a=-1!==this.options.template.indexOf("a"),b=(-1!==this.options.template.indexOf("A"),[["am",a?"am":"AM"],["pm",a?"pm":"PM"]]);return b},getValue:function(b){var c,d={},e=this,f=!1;return a.each(this.map,function(a){if("ampm"!==a){var b="day"===a?1:0;return d[a]=e["$"+a]?parseInt(e["$"+a].val(),10):b,isNaN(d[a])?(f=!0,!1):void 0}}),f?"":(this.$ampm&&(d.hour=12===d.hour?"am"===this.$ampm.val()?0:12:"am"===this.$ampm.val()?d.hour:d.hour+12),c=moment([d.year,d.month,d.day,d.hour,d.minute,d.second]),this.highlight(c),b=void 0===b?this.options.format:b,null===b?c.isValid()?c:null:c.isValid()?c.format(b):"")},setValue:function(b){function c(b,c){var d={};return b.children("option").each(function(b,e){var f,g=a(e).attr("value");""!==g&&(f=Math.abs(g-c),("undefined"==typeof d.distance||f<d.distance)&&(d={value:g,distance:f}))}),d.value}if(b){var d="string"==typeof b?moment(b,this.options.format):moment(b),e=this,f={};d.isValid()&&(a.each(this.map,function(a,b){"ampm"!==a&&(f[a]=d[b[1]]())}),this.$ampm&&(f.hour>=12?(f.ampm="pm",f.hour>12&&(f.hour-=12)):(f.ampm="am",0===f.hour&&(f.hour=12))),a.each(f,function(a,b){e["$"+a]&&("minute"===a&&e.options.minuteStep>1&&e.options.roundTime&&(b=c(e["$"+a],b)),"second"===a&&e.options.secondStep>1&&e.options.roundTime&&(b=c(e["$"+a],b)),e["$"+a].val(b))}),this.options.smartDays&&this.fillCombo("day"),this.$element.val(d.format(this.options.format)).change())}},highlight:function(a){a.isValid()?this.options.errorClass?this.$widget.removeClass(this.options.errorClass):this.$widget.find("select").css("border-color",this.borderColor):this.options.errorClass?this.$widget.addClass(this.options.errorClass):(this.borderColor||(this.borderColor=this.$widget.find("select").css("border-color")),this.$widget.find("select").css("border-color","red"))},leadZero:function(a){return 9>=a?"0"+a:a},destroy:function(){this.$widget.remove(),this.$element.removeData("combodate").show()}},a.fn.combodate=function(c){var d,e=Array.apply(null,arguments);return e.shift(),"getValue"===c&&this.length&&(d=this.eq(0).data("combodate"))?d.getValue.apply(d,e):this.each(function(){var d=a(this),f=d.data("combodate"),g="object"==typeof c&&c;f||d.data("combodate",f=new b(this,g)),"string"==typeof c&&"function"==typeof f[c]&&f[c].apply(f,e)})},a.fn.combodate.defaults={format:"DD-MM-YYYY HH:mm",template:"D / MMM / YYYY   H : mm",value:null,minYear:1970,maxYear:2015,yearDescending:!0,minuteStep:5,secondStep:1,firstItem:"empty",errorClass:null,roundTime:!0,smartDays:!1}}(window.jQuery),function(a){"use strict";var b=function(c){this.init("combodate",c,b.defaults),this.options.viewformat||(this.options.viewformat=this.options.format),c.combodate=a.fn.editableutils.tryParseJson(c.combodate,!0),this.options.combodate=a.extend({},b.defaults.combodate,c.combodate,{format:this.options.format,template:this.options.template})};a.fn.editableutils.inherit(b,a.fn.editabletypes.abstractinput),a.extend(b.prototype,{render:function(){this.$input.combodate(this.options.combodate),"bs3"===a.fn.editableform.engine&&this.$input.siblings().find("select").addClass("form-control"),this.options.inputclass&&this.$input.siblings().find("select").addClass(this.options.inputclass)},value2html:function(a,c){var d=a?a.format(this.options.viewformat):"";b.superclass.value2html.call(this,d,c)},html2value:function(a){return a?moment(a,this.options.viewformat):null},value2str:function(a){return a?a.format(this.options.format):""},str2value:function(a){return a?moment(a,this.options.format):null},value2submit:function(a){return this.value2str(a)},value2input:function(a){this.$input.combodate("setValue",a)},input2value:function(){return this.$input.combodate("getValue",null)},activate:function(){this.$input.siblings(".combodate").find("select").eq(0).focus()},autosubmit:function(){}}),b.defaults=a.extend({},a.fn.editabletypes.abstractinput.defaults,{tpl:'<input type="text">',inputclass:null,format:"YYYY-MM-DD",viewformat:null,template:"D / MMM / YYYY",combodate:null}),a.fn.editabletypes.combodate=b}(window.jQuery),function(a){"use strict";var b=a.fn.editableform.Constructor.prototype.initInput;a.extend(a.fn.editableform.Constructor.prototype,{initTemplate:function(){this.$form=a(a.fn.editableform.template),this.$form.find(".control-group").addClass("form-group"),this.$form.find(".editable-error-block").addClass("help-block")},initInput:function(){b.apply(this);var c=null===this.input.options.inputclass||this.input.options.inputclass===!1,d="input-sm",e="text,select,textarea,password,email,url,tel,number,range,time,typeaheadjs".split(",");~a.inArray(this.input.type,e)&&(this.input.$input.addClass("form-control"),c&&(this.input.options.inputclass=d,this.input.$input.addClass(d)));for(var f=this.$form.find(".editable-buttons"),g=c?[d]:this.input.options.inputclass.split(" "),h=0;h<g.length;h++)"input-lg"===g[h].toLowerCase()&&f.find("button").removeClass("btn-sm").addClass("btn-lg")}}),a.fn.editableform.buttons='<button type="submit" class="btn btn-primary btn-sm editable-submit"><i class="glyphicon glyphicon-ok"></i></button><button type="button" class="btn btn-default btn-sm editable-cancel"><i class="glyphicon glyphicon-remove"></i></button>',a.fn.editableform.errorGroupClass="has-error",a.fn.editableform.errorBlockClass=null,a.fn.editableform.engine="bs3"}(window.jQuery),function(a){"use strict";a.extend(a.fn.editableContainer.Popup.prototype,{containerName:"popover",containerDataName:"bs.popover",innerCss:".popover-content",defaults:a.fn.popover.Constructor.DEFAULTS,initContainer:function(){a.extend(this.containerOptions,{trigger:"manual",selector:!1,content:" ",template:this.defaults.template});var b;this.$element.data("template")&&(b=this.$element.data("template"),this.$element.removeData("template")),this.call(this.containerOptions),b&&this.$element.data("template",b)},innerShow:function(){this.call("show")},innerHide:function(){this.call("hide")},innerDestroy:function(){this.call("destroy")},setContainerOption:function(a,b){this.container().options[a]=b},setPosition:function(){!function(){var a=this.tip(),b="function"==typeof this.options.placement?this.options.placement.call(this,a[0],this.$element[0]):this.options.placement,c=/\s?auto?\s?/i,d=c.test(b);d&&(b=b.replace(c,"")||"top");var e=this.getPosition(),f=a[0].offsetWidth,g=a[0].offsetHeight;if(d){var h=this.$element.parent(),i=b,j=document.documentElement.scrollTop||document.body.scrollTop,k="body"==this.options.container?window.innerWidth:h.outerWidth(),l="body"==this.options.container?window.innerHeight:h.outerHeight(),m="body"==this.options.container?0:h.offset().left;b="bottom"==b&&e.top+e.height+g-j>l?"top":"top"==b&&e.top-j-g<0?"bottom":"right"==b&&e.right+f>k?"left":"left"==b&&e.left-f<m?"right":b,a.removeClass(i).addClass(b)}var n=this.getCalculatedOffset(b,e,f,g);this.applyPlacement(n,b)}.call(this.container())}})}(window.jQuery),function(a){function b(){return new Date(Date.UTC.apply(Date,arguments))}function c(b,c){var d,e=a(b).data(),f={},g=new RegExp("^"+c.toLowerCase()+"([A-Z])"),c=new RegExp("^"+c.toLowerCase());for(var h in e)c.test(h)&&(d=h.replace(g,function(a,b){return b.toLowerCase()}),f[d]=e[h]);return f}function d(b){var c={};if(k[b]||(b=b.split("-")[0],k[b])){var d=k[b];return a.each(j,function(a,b){b in d&&(c[b]=d[b])}),c}}var e=function(b,c){this._process_options(c),this.element=a(b),this.isInline=!1,this.isInput=this.element.is("input"),this.component=this.element.is(".date")?this.element.find(".add-on, .btn"):!1,this.hasInput=this.component&&this.element.find("input").length,this.component&&0===this.component.length&&(this.component=!1),this.picker=a(l.template),this._buildEvents(),this._attachEvents(),this.isInline?this.picker.addClass("datepicker-inline").appendTo(this.element):this.picker.addClass("datepicker-dropdown dropdown-menu"),this.o.rtl&&(this.picker.addClass("datepicker-rtl"),this.picker.find(".prev i, .next i").toggleClass("icon-arrow-left icon-arrow-right")),this.viewMode=this.o.startView,this.o.calendarWeeks&&this.picker.find("tfoot th.today").attr("colspan",function(a,b){return parseInt(b)+1}),this._allow_update=!1,this.setStartDate(this.o.startDate),this.setEndDate(this.o.endDate),this.setDaysOfWeekDisabled(this.o.daysOfWeekDisabled),this.fillDow(),this.fillMonths(),this._allow_update=!0,this.update(),this.showMode(),this.isInline&&this.show()};e.prototype={constructor:e,_process_options:function(b){this._o=a.extend({},this._o,b);var c=this.o=a.extend({},this._o),d=c.language;switch(k[d]||(d=d.split("-")[0],k[d]||(d=i.language)),c.language=d,c.startView){case 2:case"decade":c.startView=2;break;case 1:case"year":c.startView=1;break;default:c.startView=0}switch(c.minViewMode){case 1:case"months":c.minViewMode=1;break;case 2:case"years":c.minViewMode=2;break;default:c.minViewMode=0}c.startView=Math.max(c.startView,c.minViewMode),c.weekStart%=7,c.weekEnd=(c.weekStart+6)%7;var e=l.parseFormat(c.format);c.startDate!==-1/0&&(c.startDate=l.parseDate(c.startDate,e,c.language)),1/0!==c.endDate&&(c.endDate=l.parseDate(c.endDate,e,c.language)),c.daysOfWeekDisabled=c.daysOfWeekDisabled||[],a.isArray(c.daysOfWeekDisabled)||(c.daysOfWeekDisabled=c.daysOfWeekDisabled.split(/[,\s]*/)),c.daysOfWeekDisabled=a.map(c.daysOfWeekDisabled,function(a){return parseInt(a,10)})},_events:[],_secondaryEvents:[],_applyEvents:function(a){for(var b,c,d=0;d<a.length;d++)b=a[d][0],c=a[d][1],b.on(c)},_unapplyEvents:function(a){for(var b,c,d=0;d<a.length;d++)b=a[d][0],c=a[d][1],b.off(c)},_buildEvents:function(){this.isInput?this._events=[[this.element,{focus:a.proxy(this.show,this),keyup:a.proxy(this.update,this),keydown:a.proxy(this.keydown,this)}]]:this.component&&this.hasInput?this._events=[[this.element.find("input"),{focus:a.proxy(this.show,this),keyup:a.proxy(this.update,this),keydown:a.proxy(this.keydown,this)}],[this.component,{click:a.proxy(this.show,this)}]]:this.element.is("div")?this.isInline=!0:this._events=[[this.element,{click:a.proxy(this.show,this)}]],this._secondaryEvents=[[this.picker,{click:a.proxy(this.click,this)}],[a(window),{resize:a.proxy(this.place,this)}],[a(document),{mousedown:a.proxy(function(a){this.element.is(a.target)||this.element.find(a.target).size()||this.picker.is(a.target)||this.picker.find(a.target).size()||this.hide()},this)}]]},_attachEvents:function(){this._detachEvents(),this._applyEvents(this._events)},_detachEvents:function(){this._unapplyEvents(this._events)},_attachSecondaryEvents:function(){this._detachSecondaryEvents(),this._applyEvents(this._secondaryEvents)},_detachSecondaryEvents:function(){this._unapplyEvents(this._secondaryEvents)},_trigger:function(b,c){var d=c||this.date,e=new Date(d.getTime()+6e4*d.getTimezoneOffset());this.element.trigger({type:b,date:e,format:a.proxy(function(a){var b=a||this.o.format;return l.formatDate(d,b,this.o.language)},this)})},show:function(a){this.isInline||this.picker.appendTo("body"),this.picker.show(),this.height=this.component?this.component.outerHeight():this.element.outerHeight(),this.place(),this._attachSecondaryEvents(),a&&a.preventDefault(),this._trigger("show")},hide:function(){this.isInline||this.picker.is(":visible")&&(this.picker.hide().detach(),this._detachSecondaryEvents(),this.viewMode=this.o.startView,this.showMode(),this.o.forceParse&&(this.isInput&&this.element.val()||this.hasInput&&this.element.find("input").val())&&this.setValue(),this._trigger("hide"))},remove:function(){this.hide(),this._detachEvents(),this._detachSecondaryEvents(),this.picker.remove(),delete this.element.data().datepicker,this.isInput||delete this.element.data().date},getDate:function(){var a=this.getUTCDate();return new Date(a.getTime()+6e4*a.getTimezoneOffset())},getUTCDate:function(){return this.date},setDate:function(a){this.setUTCDate(new Date(a.getTime()-6e4*a.getTimezoneOffset()))},setUTCDate:function(a){this.date=a,this.setValue()},setValue:function(){var a=this.getFormattedDate();this.isInput?this.element.val(a):this.component&&this.element.find("input").val(a)},getFormattedDate:function(a){return void 0===a&&(a=this.o.format),l.formatDate(this.date,a,this.o.language)},setStartDate:function(a){this._process_options({startDate:a}),this.update(),this.updateNavArrows()},setEndDate:function(a){this._process_options({endDate:a}),this.update(),this.updateNavArrows()},setDaysOfWeekDisabled:function(a){this._process_options({daysOfWeekDisabled:a}),this.update(),this.updateNavArrows()},place:function(){if(!this.isInline){var b=parseInt(this.element.parents().filter(function(){return"auto"!=a(this).css("z-index")}).first().css("z-index"))+10,c=this.component?this.component.parent().offset():this.element.offset(),d=this.component?this.component.outerHeight(!0):this.element.outerHeight(!0);this.picker.css({top:c.top+d,left:c.left,zIndex:b})}},_allow_update:!0,update:function(){if(this._allow_update){var a,b=!1;arguments&&arguments.length&&("string"==typeof arguments[0]||arguments[0]instanceof Date)?(a=arguments[0],b=!0):(a=this.isInput?this.element.val():this.element.data("date")||this.element.find("input").val(),delete this.element.data().date),this.date=l.parseDate(a,this.o.format,this.o.language),b&&this.setValue(),this.viewDate=this.date<this.o.startDate?new Date(this.o.startDate):this.date>this.o.endDate?new Date(this.o.endDate):new Date(this.date),this.fill()}},fillDow:function(){var a=this.o.weekStart,b="<tr>";if(this.o.calendarWeeks){var c='<th class="cw">&nbsp;</th>';b+=c,this.picker.find(".datepicker-days thead tr:first-child").prepend(c)}for(;a<this.o.weekStart+7;)b+='<th class="dow">'+k[this.o.language].daysMin[a++%7]+"</th>";b+="</tr>",this.picker.find(".datepicker-days thead").append(b)},fillMonths:function(){for(var a="",b=0;12>b;)a+='<span class="month">'+k[this.o.language].monthsShort[b++]+"</span>";this.picker.find(".datepicker-months td").html(a)},setRange:function(b){b&&b.length?this.range=a.map(b,function(a){return a.valueOf()}):delete this.range,this.fill()},getClassNames:function(b){var c=[],d=this.viewDate.getUTCFullYear(),e=this.viewDate.getUTCMonth(),f=this.date.valueOf(),g=new Date;return b.getUTCFullYear()<d||b.getUTCFullYear()==d&&b.getUTCMonth()<e?c.push("old"):(b.getUTCFullYear()>d||b.getUTCFullYear()==d&&b.getUTCMonth()>e)&&c.push("new"),this.o.todayHighlight&&b.getUTCFullYear()==g.getFullYear()&&b.getUTCMonth()==g.getMonth()&&b.getUTCDate()==g.getDate()&&c.push("today"),f&&b.valueOf()==f&&c.push("active"),(b.valueOf()<this.o.startDate||b.valueOf()>this.o.endDate||-1!==a.inArray(b.getUTCDay(),this.o.daysOfWeekDisabled))&&c.push("disabled"),this.range&&(b>this.range[0]&&b<this.range[this.range.length-1]&&c.push("range"),-1!=a.inArray(b.valueOf(),this.range)&&c.push("selected")),c},fill:function(){var c,d=new Date(this.viewDate),e=d.getUTCFullYear(),f=d.getUTCMonth(),g=this.o.startDate!==-1/0?this.o.startDate.getUTCFullYear():-1/0,h=this.o.startDate!==-1/0?this.o.startDate.getUTCMonth():-1/0,i=1/0!==this.o.endDate?this.o.endDate.getUTCFullYear():1/0,j=1/0!==this.o.endDate?this.o.endDate.getUTCMonth():1/0;this.date&&this.date.valueOf(),this.picker.find(".datepicker-days thead th.datepicker-switch").text(k[this.o.language].months[f]+" "+e),this.picker.find("tfoot th.today").text(k[this.o.language].today).toggle(this.o.todayBtn!==!1),this.picker.find("tfoot th.clear").text(k[this.o.language].clear).toggle(this.o.clearBtn!==!1),this.updateNavArrows(),this.fillMonths();var m=b(e,f-1,28,0,0,0,0),n=l.getDaysInMonth(m.getUTCFullYear(),m.getUTCMonth());m.setUTCDate(n),m.setUTCDate(n-(m.getUTCDay()-this.o.weekStart+7)%7);var o=new Date(m);o.setUTCDate(o.getUTCDate()+42),o=o.valueOf();for(var p,q=[];m.valueOf()<o;){if(m.getUTCDay()==this.o.weekStart&&(q.push("<tr>"),this.o.calendarWeeks)){var r=new Date(+m+864e5*((this.o.weekStart-m.getUTCDay()-7)%7)),s=new Date(+r+864e5*((11-r.getUTCDay())%7)),t=new Date(+(t=b(s.getUTCFullYear(),0,1))+864e5*((11-t.getUTCDay())%7)),u=(s-t)/864e5/7+1;q.push('<td class="cw">'+u+"</td>")}p=this.getClassNames(m),p.push("day");var v=this.o.beforeShowDay(m);void 0===v?v={}:"boolean"==typeof v?v={enabled:v}:"string"==typeof v&&(v={classes:v}),v.enabled===!1&&p.push("disabled"),v.classes&&(p=p.concat(v.classes.split(/\s+/))),v.tooltip&&(c=v.tooltip),p=a.unique(p),q.push('<td class="'+p.join(" ")+'"'+(c?' title="'+c+'"':"")+">"+m.getUTCDate()+"</td>"),m.getUTCDay()==this.o.weekEnd&&q.push("</tr>"),m.setUTCDate(m.getUTCDate()+1)}this.picker.find(".datepicker-days tbody").empty().append(q.join(""));var w=this.date&&this.date.getUTCFullYear(),x=this.picker.find(".datepicker-months").find("th:eq(1)").text(e).end().find("span").removeClass("active");w&&w==e&&x.eq(this.date.getUTCMonth()).addClass("active"),(g>e||e>i)&&x.addClass("disabled"),e==g&&x.slice(0,h).addClass("disabled"),e==i&&x.slice(j+1).addClass("disabled"),q="",e=10*parseInt(e/10,10);var y=this.picker.find(".datepicker-years").find("th:eq(1)").text(e+"-"+(e+9)).end().find("td");e-=1;for(var z=-1;11>z;z++)q+='<span class="year'+(-1==z?" old":10==z?" new":"")+(w==e?" active":"")+(g>e||e>i?" disabled":"")+'">'+e+"</span>",e+=1;y.html(q)},updateNavArrows:function(){if(this._allow_update){var a=new Date(this.viewDate),b=a.getUTCFullYear(),c=a.getUTCMonth();switch(this.viewMode){case 0:this.o.startDate!==-1/0&&b<=this.o.startDate.getUTCFullYear()&&c<=this.o.startDate.getUTCMonth()?this.picker.find(".prev").css({visibility:"hidden"}):this.picker.find(".prev").css({visibility:"visible"}),1/0!==this.o.endDate&&b>=this.o.endDate.getUTCFullYear()&&c>=this.o.endDate.getUTCMonth()?this.picker.find(".next").css({visibility:"hidden"}):this.picker.find(".next").css({visibility:"visible"});break;case 1:case 2:this.o.startDate!==-1/0&&b<=this.o.startDate.getUTCFullYear()?this.picker.find(".prev").css({visibility:"hidden"}):this.picker.find(".prev").css({visibility:"visible"}),1/0!==this.o.endDate&&b>=this.o.endDate.getUTCFullYear()?this.picker.find(".next").css({visibility:"hidden"}):this.picker.find(".next").css({visibility:"visible"})}}},click:function(c){c.preventDefault();var d=a(c.target).closest("span, td, th");if(1==d.length)switch(d[0].nodeName.toLowerCase()){case"th":switch(d[0].className){case"datepicker-switch":this.showMode(1);break;case"prev":case"next":var e=l.modes[this.viewMode].navStep*("prev"==d[0].className?-1:1);switch(this.viewMode){case 0:this.viewDate=this.moveMonth(this.viewDate,e);break;case 1:case 2:this.viewDate=this.moveYear(this.viewDate,e)}this.fill();break;case"today":var f=new Date;f=b(f.getFullYear(),f.getMonth(),f.getDate(),0,0,0),this.showMode(-2);var g="linked"==this.o.todayBtn?null:"view";this._setDate(f,g);break;case"clear":var h;this.isInput?h=this.element:this.component&&(h=this.element.find("input")),h&&h.val("").change(),this._trigger("changeDate"),this.update(),this.o.autoclose&&this.hide()}break;case"span":if(!d.is(".disabled")){if(this.viewDate.setUTCDate(1),d.is(".month")){var i=1,j=d.parent().find("span").index(d),k=this.viewDate.getUTCFullYear();this.viewDate.setUTCMonth(j),this._trigger("changeMonth",this.viewDate),1===this.o.minViewMode&&this._setDate(b(k,j,i,0,0,0,0))}else{var k=parseInt(d.text(),10)||0,i=1,j=0;this.viewDate.setUTCFullYear(k),this._trigger("changeYear",this.viewDate),2===this.o.minViewMode&&this._setDate(b(k,j,i,0,0,0,0))}this.showMode(-1),this.fill()}break;case"td":if(d.is(".day")&&!d.is(".disabled")){var i=parseInt(d.text(),10)||1,k=this.viewDate.getUTCFullYear(),j=this.viewDate.getUTCMonth();d.is(".old")?0===j?(j=11,k-=1):j-=1:d.is(".new")&&(11==j?(j=0,k+=1):j+=1),this._setDate(b(k,j,i,0,0,0,0))}}},_setDate:function(a,b){b&&"date"!=b||(this.date=new Date(a)),b&&"view"!=b||(this.viewDate=new Date(a)),this.fill(),this.setValue(),this._trigger("changeDate");var c;this.isInput?c=this.element:this.component&&(c=this.element.find("input")),c&&(c.change(),!this.o.autoclose||b&&"date"!=b||this.hide())},moveMonth:function(a,b){if(!b)return a;var c,d,e=new Date(a.valueOf()),f=e.getUTCDate(),g=e.getUTCMonth(),h=Math.abs(b);if(b=b>0?1:-1,1==h)d=-1==b?function(){return e.getUTCMonth()==g}:function(){return e.getUTCMonth()!=c},c=g+b,e.setUTCMonth(c),(0>c||c>11)&&(c=(c+12)%12);else{for(var i=0;h>i;i++)e=this.moveMonth(e,b);c=e.getUTCMonth(),e.setUTCDate(f),d=function(){return c!=e.getUTCMonth()}}for(;d();)e.setUTCDate(--f),e.setUTCMonth(c);return e},moveYear:function(a,b){return this.moveMonth(a,12*b)},dateWithinRange:function(a){return a>=this.o.startDate&&a<=this.o.endDate},keydown:function(a){if(this.picker.is(":not(:visible)"))return 27==a.keyCode&&this.show(),void 0;var b,c,d,e=!1;switch(a.keyCode){case 27:this.hide(),a.preventDefault();break;case 37:case 39:if(!this.o.keyboardNavigation)break;b=37==a.keyCode?-1:1,a.ctrlKey?(c=this.moveYear(this.date,b),d=this.moveYear(this.viewDate,b)):a.shiftKey?(c=this.moveMonth(this.date,b),d=this.moveMonth(this.viewDate,b)):(c=new Date(this.date),c.setUTCDate(this.date.getUTCDate()+b),d=new Date(this.viewDate),d.setUTCDate(this.viewDate.getUTCDate()+b)),this.dateWithinRange(c)&&(this.date=c,this.viewDate=d,this.setValue(),this.update(),a.preventDefault(),e=!0);break;case 38:case 40:if(!this.o.keyboardNavigation)break;b=38==a.keyCode?-1:1,a.ctrlKey?(c=this.moveYear(this.date,b),d=this.moveYear(this.viewDate,b)):a.shiftKey?(c=this.moveMonth(this.date,b),d=this.moveMonth(this.viewDate,b)):(c=new Date(this.date),c.setUTCDate(this.date.getUTCDate()+7*b),d=new Date(this.viewDate),d.setUTCDate(this.viewDate.getUTCDate()+7*b)),this.dateWithinRange(c)&&(this.date=c,this.viewDate=d,this.setValue(),this.update(),a.preventDefault(),e=!0);break;case 13:this.hide(),a.preventDefault();break;case 9:this.hide()}if(e){this._trigger("changeDate");var f;this.isInput?f=this.element:this.component&&(f=this.element.find("input")),f&&f.change()}},showMode:function(a){a&&(this.viewMode=Math.max(this.o.minViewMode,Math.min(2,this.viewMode+a))),this.picker.find(">div").hide().filter(".datepicker-"+l.modes[this.viewMode].clsName).css("display","block"),this.updateNavArrows()}};var f=function(b,c){this.element=a(b),this.inputs=a.map(c.inputs,function(a){return a.jquery?a[0]:a}),delete c.inputs,a(this.inputs).datepicker(c).bind("changeDate",a.proxy(this.dateUpdated,this)),this.pickers=a.map(this.inputs,function(b){return a(b).data("datepicker")}),this.updateDates()};f.prototype={updateDates:function(){this.dates=a.map(this.pickers,function(a){return a.date}),this.updateRanges()},updateRanges:function(){var b=a.map(this.dates,function(a){return a.valueOf()});a.each(this.pickers,function(a,c){c.setRange(b)})},dateUpdated:function(b){var c=a(b.target).data("datepicker"),d=c.getUTCDate(),e=a.inArray(b.target,this.inputs),f=this.inputs.length;if(-1!=e){if(d<this.dates[e])for(;e>=0&&d<this.dates[e];)this.pickers[e--].setUTCDate(d);else if(d>this.dates[e])for(;f>e&&d>this.dates[e];)this.pickers[e++].setUTCDate(d);this.updateDates()}},remove:function(){a.map(this.pickers,function(a){a.remove()}),delete this.element.data().datepicker}};var g=a.fn.datepicker,h=a.fn.datepicker=function(b){var g=Array.apply(null,arguments);g.shift();var h;return this.each(function(){var j=a(this),k=j.data("datepicker"),l="object"==typeof b&&b;if(!k){var m=c(this,"date"),n=a.extend({},i,m,l),o=d(n.language),p=a.extend({},i,o,m,l);if(j.is(".input-daterange")||p.inputs){var q={inputs:p.inputs||j.find("input").toArray()};j.data("datepicker",k=new f(this,a.extend(p,q)))}else j.data("datepicker",k=new e(this,p))}return"string"==typeof b&&"function"==typeof k[b]&&(h=k[b].apply(k,g),void 0!==h)?!1:void 0}),void 0!==h?h:this},i=a.fn.datepicker.defaults={autoclose:!1,beforeShowDay:a.noop,calendarWeeks:!1,clearBtn:!1,daysOfWeekDisabled:[],endDate:1/0,forceParse:!0,format:"mm/dd/yyyy",keyboardNavigation:!0,language:"en",minViewMode:0,rtl:!1,startDate:-1/0,startView:0,todayBtn:!1,todayHighlight:!1,weekStart:0},j=a.fn.datepicker.locale_opts=["format","rtl","weekStart"];a.fn.datepicker.Constructor=e;var k=a.fn.datepicker.dates={en:{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],daysShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat","Sun"],daysMin:["Su","Mo","Tu","We","Th","Fr","Sa","Su"],months:["January","February","March","April","May","June","July","August","September","October","November","December"],monthsShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],today:"Today",clear:"Clear"}},l={modes:[{clsName:"days",navFnc:"Month",navStep:1},{clsName:"months",navFnc:"FullYear",navStep:1},{clsName:"years",navFnc:"FullYear",navStep:10}],isLeapYear:function(a){return 0===a%4&&0!==a%100||0===a%400
},getDaysInMonth:function(a,b){return[31,l.isLeapYear(a)?29:28,31,30,31,30,31,31,30,31,30,31][b]},validParts:/dd?|DD?|mm?|MM?|yy(?:yy)?/g,nonpunctuation:/[^ -\/:-@\[\u3400-\u9fff-`{-~\t\n\r]+/g,parseFormat:function(a){var b=a.replace(this.validParts,"\0").split("\0"),c=a.match(this.validParts);if(!b||!b.length||!c||0===c.length)throw new Error("Invalid date format.");return{separators:b,parts:c}},parseDate:function(c,d,f){if(c instanceof Date)return c;if("string"==typeof d&&(d=l.parseFormat(d)),/^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/.test(c)){var g,h,i=/([\-+]\d+)([dmwy])/,j=c.match(/([\-+]\d+)([dmwy])/g);c=new Date;for(var m=0;m<j.length;m++)switch(g=i.exec(j[m]),h=parseInt(g[1]),g[2]){case"d":c.setUTCDate(c.getUTCDate()+h);break;case"m":c=e.prototype.moveMonth.call(e.prototype,c,h);break;case"w":c.setUTCDate(c.getUTCDate()+7*h);break;case"y":c=e.prototype.moveYear.call(e.prototype,c,h)}return b(c.getUTCFullYear(),c.getUTCMonth(),c.getUTCDate(),0,0,0)}var n,o,g,j=c&&c.match(this.nonpunctuation)||[],c=new Date,p={},q=["yyyy","yy","M","MM","m","mm","d","dd"],r={yyyy:function(a,b){return a.setUTCFullYear(b)},yy:function(a,b){return a.setUTCFullYear(2e3+b)},m:function(a,b){for(b-=1;0>b;)b+=12;for(b%=12,a.setUTCMonth(b);a.getUTCMonth()!=b;)a.setUTCDate(a.getUTCDate()-1);return a},d:function(a,b){return a.setUTCDate(b)}};r.M=r.MM=r.mm=r.m,r.dd=r.d,c=b(c.getFullYear(),c.getMonth(),c.getDate(),0,0,0);var s=d.parts.slice();if(j.length!=s.length&&(s=a(s).filter(function(b,c){return-1!==a.inArray(c,q)}).toArray()),j.length==s.length){for(var m=0,t=s.length;t>m;m++){if(n=parseInt(j[m],10),g=s[m],isNaN(n))switch(g){case"MM":o=a(k[f].months).filter(function(){var a=this.slice(0,j[m].length),b=j[m].slice(0,a.length);return a==b}),n=a.inArray(o[0],k[f].months)+1;break;case"M":o=a(k[f].monthsShort).filter(function(){var a=this.slice(0,j[m].length),b=j[m].slice(0,a.length);return a==b}),n=a.inArray(o[0],k[f].monthsShort)+1}p[g]=n}for(var u,m=0;m<q.length;m++)u=q[m],u in p&&!isNaN(p[u])&&r[u](c,p[u])}return c},formatDate:function(b,c,d){"string"==typeof c&&(c=l.parseFormat(c));var e={d:b.getUTCDate(),D:k[d].daysShort[b.getUTCDay()],DD:k[d].days[b.getUTCDay()],m:b.getUTCMonth()+1,M:k[d].monthsShort[b.getUTCMonth()],MM:k[d].months[b.getUTCMonth()],yy:b.getUTCFullYear().toString().substring(2),yyyy:b.getUTCFullYear()};e.dd=(e.d<10?"0":"")+e.d,e.mm=(e.m<10?"0":"")+e.m;for(var b=[],f=a.extend([],c.separators),g=0,h=c.parts.length;h>=g;g++)f.length&&b.push(f.shift()),b.push(e[c.parts[g]]);return b.join("")},headTemplate:'<thead><tr><th class="prev"><i class="icon-arrow-left"/></th><th colspan="5" class="datepicker-switch"></th><th class="next"><i class="icon-arrow-right"/></th></tr></thead>',contTemplate:'<tbody><tr><td colspan="7"></td></tr></tbody>',footTemplate:'<tfoot><tr><th colspan="7" class="today"></th></tr><tr><th colspan="7" class="clear"></th></tr></tfoot>'};l.template='<div class="datepicker"><div class="datepicker-days"><table class=" table-condensed">'+l.headTemplate+"<tbody></tbody>"+l.footTemplate+"</table>"+"</div>"+'<div class="datepicker-months">'+'<table class="table-condensed">'+l.headTemplate+l.contTemplate+l.footTemplate+"</table>"+"</div>"+'<div class="datepicker-years">'+'<table class="table-condensed">'+l.headTemplate+l.contTemplate+l.footTemplate+"</table>"+"</div>"+"</div>",a.fn.datepicker.DPGlobal=l,a.fn.datepicker.noConflict=function(){return a.fn.datepicker=g,this},a(document).on("focus.datepicker.data-api click.datepicker.data-api",'[data-provide="datepicker"]',function(b){var c=a(this);c.data("datepicker")||(b.preventDefault(),h.call(c,"show"))}),a(function(){h.call(a('[data-provide="datepicker-inline"]'))})}(window.jQuery),function(a){"use strict";a.fn.bdatepicker=a.fn.datepicker.noConflict(),a.fn.datepicker||(a.fn.datepicker=a.fn.bdatepicker);var b=function(a){this.init("date",a,b.defaults),this.initPicker(a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.abstractinput),a.extend(b.prototype,{initPicker:function(b,c){this.options.viewformat||(this.options.viewformat=this.options.format),b.datepicker=a.fn.editableutils.tryParseJson(b.datepicker,!0),this.options.datepicker=a.extend({},c.datepicker,b.datepicker,{format:this.options.viewformat}),this.options.datepicker.language=this.options.datepicker.language||"en",this.dpg=a.fn.bdatepicker.DPGlobal,this.parsedFormat=this.dpg.parseFormat(this.options.format),this.parsedViewFormat=this.dpg.parseFormat(this.options.viewformat)},render:function(){this.$input.bdatepicker(this.options.datepicker),this.options.clear&&(this.$clear=a('<a href="#"></a>').html(this.options.clear).click(a.proxy(function(a){a.preventDefault(),a.stopPropagation(),this.clear()},this)),this.$tpl.parent().append(a('<div class="editable-clear">').append(this.$clear)))},value2html:function(a,c){var d=a?this.dpg.formatDate(a,this.parsedViewFormat,this.options.datepicker.language):"";b.superclass.value2html.call(this,d,c)},html2value:function(a){return this.parseDate(a,this.parsedViewFormat)},value2str:function(a){return a?this.dpg.formatDate(a,this.parsedFormat,this.options.datepicker.language):""},str2value:function(a){return this.parseDate(a,this.parsedFormat)},value2submit:function(a){return this.value2str(a)},value2input:function(a){this.$input.bdatepicker("update",a)},input2value:function(){return this.$input.data("datepicker").date},activate:function(){},clear:function(){this.$input.data("datepicker").date=null,this.$input.find(".active").removeClass("active"),this.options.showbuttons||this.$input.closest("form").submit()},autosubmit:function(){this.$input.on("mouseup",".day",function(b){if(!a(b.currentTarget).is(".old")&&!a(b.currentTarget).is(".new")){var c=a(this).closest("form");setTimeout(function(){c.submit()},200)}})},parseDate:function(a,b){var c,d=null;return a&&(d=this.dpg.parseDate(a,b,this.options.datepicker.language),"string"==typeof a&&(c=this.dpg.formatDate(d,b,this.options.datepicker.language),a!==c&&(d=null))),d}}),b.defaults=a.extend({},a.fn.editabletypes.abstractinput.defaults,{tpl:'<div class="editable-date well"></div>',inputclass:null,format:"yyyy-mm-dd",viewformat:null,datepicker:{weekStart:0,startView:0,minViewMode:0,autoclose:!1},clear:"&times; clear"}),a.fn.editabletypes.date=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("datefield",a,b.defaults),this.initPicker(a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.date),a.extend(b.prototype,{render:function(){this.$input=this.$tpl.find("input"),this.setClass(),this.setAttr("placeholder"),this.$tpl.bdatepicker(this.options.datepicker),this.$input.off("focus keydown"),this.$input.keyup(a.proxy(function(){this.$tpl.removeData("date"),this.$tpl.bdatepicker("update")},this))},value2input:function(a){this.$input.val(a?this.dpg.formatDate(a,this.parsedViewFormat,this.options.datepicker.language):""),this.$tpl.bdatepicker("update")},input2value:function(){return this.html2value(this.$input.val())},activate:function(){a.fn.editabletypes.text.prototype.activate.call(this)},autosubmit:function(){}}),b.defaults=a.extend({},a.fn.editabletypes.date.defaults,{tpl:'<div class="input-append date"><input type="text"/><span class="add-on"><i class="icon-th"></i></span></div>',inputclass:"input-small",datepicker:{weekStart:0,startView:0,minViewMode:0,autoclose:!0}}),a.fn.editabletypes.datefield=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("datetime",a,b.defaults),this.initPicker(a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.abstractinput),a.extend(b.prototype,{initPicker:function(b,c){this.options.viewformat||(this.options.viewformat=this.options.format),b.datetimepicker=a.fn.editableutils.tryParseJson(b.datetimepicker,!0),this.options.datetimepicker=a.extend({},c.datetimepicker,b.datetimepicker,{format:this.options.viewformat}),this.options.datetimepicker.language=this.options.datetimepicker.language||"en",this.dpg=a.fn.datetimepicker.DPGlobal,this.parsedFormat=this.dpg.parseFormat(this.options.format,this.options.formatType),this.parsedViewFormat=this.dpg.parseFormat(this.options.viewformat,this.options.formatType)},render:function(){this.$input.datetimepicker(this.options.datetimepicker),this.$input.on("changeMode",function(){var b=a(this).closest("form").parent();setTimeout(function(){b.triggerHandler("resize")},0)}),this.options.clear&&(this.$clear=a('<a href="#"></a>').html(this.options.clear).click(a.proxy(function(a){a.preventDefault(),a.stopPropagation(),this.clear()},this)),this.$tpl.parent().append(a('<div class="editable-clear">').append(this.$clear)))},value2html:function(a,c){var d=a?this.dpg.formatDate(this.toUTC(a),this.parsedViewFormat,this.options.datetimepicker.language,this.options.formatType):"";return c?(b.superclass.value2html.call(this,d,c),void 0):d},html2value:function(a){var b=this.parseDate(a,this.parsedViewFormat);return b?this.fromUTC(b):null},value2str:function(a){return a?this.dpg.formatDate(this.toUTC(a),this.parsedFormat,this.options.datetimepicker.language,this.options.formatType):""},str2value:function(a){var b=this.parseDate(a,this.parsedFormat);return b?this.fromUTC(b):null},value2submit:function(a){return this.value2str(a)},value2input:function(a){a&&this.$input.data("datetimepicker").setDate(a)},input2value:function(){var a=this.$input.data("datetimepicker");return a.date?a.getDate():null},activate:function(){},clear:function(){this.$input.data("datetimepicker").date=null,this.$input.find(".active").removeClass("active"),this.options.showbuttons||this.$input.closest("form").submit()},autosubmit:function(){this.$input.on("mouseup",".minute",function(){var b=a(this).closest("form");setTimeout(function(){b.submit()},200)})},toUTC:function(a){return a?new Date(a.valueOf()-6e4*a.getTimezoneOffset()):a},fromUTC:function(a){return a?new Date(a.valueOf()+6e4*a.getTimezoneOffset()):a},parseDate:function(a,b){var c,d=null;return a&&(d=this.dpg.parseDate(a,b,this.options.datetimepicker.language,this.options.formatType),"string"==typeof a&&(c=this.dpg.formatDate(d,b,this.options.datetimepicker.language,this.options.formatType),a!==c&&(d=null))),d}}),b.defaults=a.extend({},a.fn.editabletypes.abstractinput.defaults,{tpl:'<div class="editable-date well"></div>',inputclass:null,format:"yyyy-mm-dd hh:ii",formatType:"standard",viewformat:null,datetimepicker:{todayHighlight:!1,autoclose:!1},clear:"&times; clear"}),a.fn.editabletypes.datetime=b}(window.jQuery),function(a){"use strict";var b=function(a){this.init("datetimefield",a,b.defaults),this.initPicker(a,b.defaults)};a.fn.editableutils.inherit(b,a.fn.editabletypes.datetime),a.extend(b.prototype,{render:function(){this.$input=this.$tpl.find("input"),this.setClass(),this.setAttr("placeholder"),this.$tpl.datetimepicker(this.options.datetimepicker),this.$input.off("focus keydown"),this.$input.keyup(a.proxy(function(){this.$tpl.removeData("date"),this.$tpl.datetimepicker("update")},this))},value2input:function(a){this.$input.val(this.value2html(a)),this.$tpl.datetimepicker("update")},input2value:function(){return this.html2value(this.$input.val())},activate:function(){a.fn.editabletypes.text.prototype.activate.call(this)},autosubmit:function(){}}),b.defaults=a.extend({},a.fn.editabletypes.datetime.defaults,{tpl:'<div class="input-append date"><input type="text"/><span class="add-on"><i class="icon-th"></i></span></div>',inputclass:"input-medium",datetimepicker:{todayHighlight:!1,autoclose:!0}}),a.fn.editabletypes.datetimefield=b}(window.jQuery);
;(function ($) {
    "use strict";

    var TaskHeading = function (options) {
        this.init('taskheading', options, TaskHeading.defaults);
    };
    $.fn.editableutils.inherit(TaskHeading, $.fn.editabletypes.abstractinput);
    $.extend(TaskHeading.prototype, {
        render: function() {
            this.$input = this.$tpl.find('input, select');
        },
        value2html: function(value, element) {
            if(!value) {
                $(element).empty();
                return; 
            }
            $(element).text(value.name); 
        },
        html2value: function(html) {        
            return null;  
        },
        value2str: function(value) {
            var str = '';
            if(value) {
                for(var k in value) {
                    str = str + k + ':' + value[k] + ';';  
                }
            }
            return str;
        }, 
        str2value: function(str) {
            return str;
        },                
        value2input: function(value) {
            if(!value) {
                return;
            }
            this.$input.filter('[name="name"]').val(value.name);
            this.$input.filter('[name="label"]').val(value.label);
        },        
        input2value: function() { 
            var values = {
                name: this.$input.filter('[name="name"]').val(), 
                label: this.$input.filter('[name="label"]').val()
            };
            this.$input.parents('.panel')
                .removeClass('panel-default')
                .removeClass('panel-success')
                .removeClass('panel-primary')
                .removeClass('panel-info')
                .removeClass('panel-warning')
                .removeClass('panel-danger')
                .addClass('panel-' + values.label);
            return values;
        },     
        activate: function() {
            this.$input.filter('[name="name"]').focus();
            $('.editable-buttons').append('<button type="button" class="btn btn-danger btn-sm editable-remove"><i class="glyphicon glyphicon-trash"></i></button>')
        },     
        autosubmit: function() {
            this.$input.keydown(function (e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
            });
        }       
    });

TaskHeading.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
    tpl: '<div class="editable-taskheading"><label><span>Title: </span><input type="text" name="name" class="input-small"></label></div>'+
    '<div class="editable-taskheading"><label><span>Label: </span><select type="text" name="label" class="input-small form-control"><option value="default">Default</option><option value="primary">Primary</option><option value="success">Success</option><option value="info">Info</option><option value="warning">Warning</option><option value="danger">Danger</option></select></label></div>',
    inputclass: ''
});

$.fn.editabletypes.taskheading = TaskHeading;

}(window.jQuery));


function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>';
    return (str + '')
        .replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function getNotDone(items) {
    return _.where(items, {is_done: 0});
}

// ;(function(){

    $.fn.editable.defaults.ajaxOptions = {type: "put"};

    // $('.inner-task').sortable({
    //     connectWith: '.inner-task',
    //     placeholder: 'ui-state-highlight'
    // }).disableSelection();
    $('.btn').button();

    var taskCategoriesCollection = new (Backbone.Collection.extend({
        url : '/task-categories'
    }))(taskCategories);
    var taskTagsCollection = new (Backbone.Collection.extend({
        url: '/task-tags'
    }))(taskTags);
    var tasksCollection = new(Backbone.Collection.extend({
        model: Backbone.Model.extend({
            url: function() {
                return '/tasks/' + this.id
            },
            currentTag: function() {
                var activeCategory = actionModel.get('activeCategory');
                var tag = this.get('tags').filter(function(tag){
                    return tag.tag_category_id === activeCategory;
                });
                if(tag.length > 0) {
                    return tag[0];
                }
                console.error('tag not found for categoryid ' + activeCategory)
                return {};
            },
            currentOrder: function() {
                var activeCategory = actionModel.get('activeCategory');
                var order = this.get('orders').filter(function(order){
                    return order.tag_category_id === activeCategory;
                });
                if(order.length > 0) {
                    return order[0].order;
                }
                return 0;
            },
            setArchived: function() {
                this.set({
                    archived: true
                });
                $.post('/task/' + this.get('id') + '/set-archived');
            },
            unsetArchived: function() {
                $.post('/task/' + this.get('id') + '/unset-archived');
            }
        })
    }));

    // $.eventsource({
    //     url: "/task/stream",
    //     dataType: "json", 
    //     open: function(data) {
    //     },
    //     message: function(data) {
    //         tasksCollection.set(data);
    //     }
    // });

    var actionModel = new (Backbone.Model.extend({
        save: function() {
            window.localStorage.setItem('taskActionConfig', (JSON.stringify(this.toJSON())));
        },
        initialize: function() {
            taskActionConfig = JSON.parse(window.localStorage.getItem('taskActionConfig')) || {
                activeCategory: 1
            };
            this.set(taskActionConfig);
        }
    }));

    var actionView = new(Backbone.View.extend({
        collection: taskCategoriesCollection,
        model: actionModel,
        events: {
            'change #control-archived': function(e) {
                if($(e.currentTarget).is(':checked')) {
                    this.model.set({showArchived: true});
                    return;
                }
                this.model.set({showArchived: false});
            },
            'click [data-categoryid]': function(e) {
                var categoryId = $(e.currentTarget).data('categoryid');
                var category = this.collection.get(categoryId);
                if(category) {
                    this.setActiveCategory(category);
                }
            },
            'click #new-group': function(e) {
                bootbox.prompt('New Group Name', function(res){
                    if(res && res.trim()) {
                        this.collection.create({
                            name: res.trim()
                        }, {
                            success: function(model) {
                                 this.setActiveCategory(model);
                                 window.location.reload(true);
                            }.bind(this)
                        });
                    }
                }.bind(this))
            },
            'click #del-group': function(e) {
                bootbox.confirm('Are you sure you want to remove this group?', function(res){
                    var ids = this.collection.pluck('id');
                    if(res && ids.length >= 2) {
                        var current = this.model.get('activeCategory');
                        var active = _.without(ids, current).shift();
                        var category = this.collection.get(active);
                        this.setActiveCategory(category);
                        this.collection.get(current).destroy();
                    }
                }.bind(this))
            },
            'click #new-heading': function(e) {
                bootbox.prompt('New Tag Name', function(res){
                    if(res && res.trim()) {
                        taskTagsCollection.create({
                            name: res.trim(),
                            tag_category_id: this.model.get('activeCategory'),
                            label: 'default'
                        }, {
                            success: function(model) {
                                 window.location.reload(true);
                            }.bind(this)
                        });
                    }
                }.bind(this));
            }
        },
        setActiveCategory: function(category) {
            this.model.set('activeCategory', category.id).save();
            this.$activeCategory.text(category.get('name'));
            $('.group-name', this.$el).text(category.get('name'));
        },
        render: function() {
            this.$menu.html('');
            var activeCategory = this.model.get('activeCategory');
            this.collection.each(function(category){
                if(category.id === activeCategory) {
                    this.setActiveCategory(category);
                }
                this.$menu.append('<li data-categoryid="' + category.id + '"><a>' + category.get('name') + '</a></li>');
            }.bind(this));
            if(this.model.get('showArchived')) {
                $('#control-archived', this.$el).attr('checked', true);
            } else {
                $('#control-archived', this.$el).attr('checked', false);
            }
        },
        initialize: function() {
            this.$menu = $('#group-menu', this.$el);
            this.$activeCategory = $('#active-category', this.$el);
            var render = _.debounce(function(){
                this.render()
            }.bind(this), 16);
            this.listenTo(this.collection, 'all', render);
            this.listenTo(this.model, 'all', render);
            render();
        }
    }))({
        el: '#groups'
    });

    var taskHeadingsView = new(Backbone.View.extend({
        tags: taskTagsCollection,
        model: actionModel,
        template: _.template($('#task-headings-template').text()),
        handleTagDrop: _.debounce(function(e, ui) {
            /*
                1. get original tag
                2. check leftright
                3. check order
                4. update if neccessary
             */
            var item = $(ui.item[0]);
            var id = item.data('tagid');
            if(id) {
                var tag = this.collection.get(id);
                var parentId = item.parents('.panels').attr('id');
                var order = $('.panel').index(item);
                if(tag.get('placement') !== parentId) {
                    tag.set({'placement': parentId}).save();
                }
                if(tag.get('order') !== order) {
                    $('.panel').each(function(index, panel){
                        this.collection.get($(panel).data('tagid')).set({
                            order: index
                        }).save()
                    }.bind(this))
                }
            }
        }, 16),
        render: function() {
            if(this.collection) {
                this.taskHeadingViews.forEach(function(view, index) {
                    view.unbind();
                    view.remove();
                });
                this.$el.html(this.template());
                this.collection.each(function(model){
                    this.taskHeadingViews.push(new taskHeadingView(model));
                }.bind(this));
                $('.panels').sortable({
                    start: function(){
                        isNotDragging = false;
                    },
                    stop: function() {
                        isNotDragging = true;
                    },
                    connectWith: '.panels',
                    placeholder: 'ui-state-highlight',
                    handle: '.panel-heading',
                    update: this.handleTagDrop.bind(this)
                }).disableSelection();
            }
        },
        setCollection: function() {
            var parent = this;
            this.collection = new (Backbone.Collection.extend({
                comparator: function(a, b) {
                    return a.get('order') > b.get('order') ? 1 : -1;
                },
                initialize: function() {
                    setTimeout(function() {
                        parent.render();
                    }, 1);
                    return this;
                }
            }))(taskTagsCollection.where({'tag_category_id': this.model.get('activeCategory')}));
        },
        initialize: function() {
            this.taskHeadingViews = [];
            var setCollection = _.debounce(function(){
                this.setCollection()
            }.bind(this), 16);
            this.listenTo(this.model, 'all', setCollection);
            setCollection();
        }
    }))({
        el: '#task-headings'
    });

    var tagChange = false;
    var timeout;
    var isNotDragging = true;
    var taskHeadingView = Backbone.View.extend({
        template: _.template($('#task-heading-template').text()),
        events: {
            'click .create-todo': 'create',
            // firefox fix
            'click .new-input': function(e){
                $(e.currentTarget).focus();
            },
            'keyup .new-input': function(e) {
                if(e.keyCode === 13 && e.shiftKey) {
                    this.create();
                    e.preventDefault();
                    return false;
                }
            },
            'click .editable-remove' : function(e) {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    var panel = $(e.target).parents('.panel');
                    var id = panel.data('tagid');
                    if(id) {
                        var model = taskTagsCollection.get(id);
                        bootbox.confirm('Are you sure you want to delete ' + model.get('name') + '?', function(res){
                            if(res) {
                                model.destroy();
                                panel.remove();
                                window.location.reload();
                            }
                        });
                    }
                }, 17);
            }
        },
        create: function() {
            var target = $('.new-input', this.element);
            var val = target.val().trim();
            if(val) {
                $.post('/tasks', {
                    description: val,
                    tag_id: this.model.get('id'),
                    category_id: this.model.get('tag_category_id')
                });
                target.val('');
            }
            return true;
        },
        handleTaskDrop: function(e, ui) {
            var item = $(ui.item[0]);
            var id = item.data('taskid');
            var parentTag = item.parents('.panel');
            var parentTagId = parentTag.data('tagid');
            var tag = this.model.toJSON();
            if(id && parentTagId) {
                if(tag.id !== parentTagId) {
                    var task = tasksCollection.get(id);
                    tagChange = true;
                    $.get('/tasks/' + task.id + '/set-tag/' + parentTagId, function(data){
                        $.post('/task/set-order/' + tag.tag_category_id, {
                            order: parentTag.find('li').map(function(idx, el){
                                return $(el).data('taskid');
                            }).toArray()
                        }, function(){
                            tagChange = false;
                        });
                    });
                } else if(!tagChange) {
                    $.post('/task/set-order/' + tag.tag_category_id, {
                        order: parentTag.find('li').map(function(idx, el){
                            return $(el).data('taskid');
                        }).toArray()
                    }, function(){
                        tagChange = false;
                    });
                }
            }
        },
        renderChildren: function() {
            var id = this.model.id;
            var showArchived = actionModel.get('showArchived');
            $('.inner-task', this.element).html('');
            tasksCollection.filter(function(task){
                return task.get('tags').map(function(tag){
                    return tag.id;
                }).indexOf(id) > -1;
            }).sort(function(a, b){
                if(a.get('archived')) {
                    return 1;
                }
                if(b.get('archived')) {
                    return -1;
                }
                return (a.currentOrder() > b.currentOrder()) ? 1 : -1;
            }).forEach(function(model){
                if(model.get('archived') && showArchived){
                    this.taskViews.push(new taskView({el : $('.inner-task', this.element), model: model}));
                } else if (model.get('archived') === 0) {
                    this.taskViews.push(new taskView({el : $('.inner-task', this.element), model: model}));
                }
            }.bind(this))
            $('.inner-task', this.element).sortable({
                start: function(){
                    isNotDragging = false;
                },
                stop: function() {
                    isNotDragging = true;
                },
                connectWith: '.inner-task',
                placeholder: 'ui-state-highlight',
                update: _.debounce(this.handleTaskDrop, 17).bind(this)
            }).disableSelection();
        },
        render: function() {
            this.element = $(this.template());
            this.element.appendTo(this.$el);
            if(is_admin)
                $('.panel-title', this.element).editable();
            this.renderChildren();
        },
        initialize: function(model) {
            this.taskViews = [];
            this.model = model;
            this.$el = $('#' + this.model.get('placement'));
            this.render();
            this.listenTo(tasksCollection, 'add change remove', _.debounce(function() {
                if(isNotDragging) {
                    this.renderChildren();
                }
            }, 100).bind(this));
        }
    });
    var taskViews = {};
    var taskView = Backbone.View.extend({
        events: {
            'click .category-label': function(e) {
                var category = taskCategoriesCollection.get($(e.currentTarget).data('categoryid'));
                actionView.setActiveCategory(category);
            },
            'click img' : function(e) {
                TaskInfoModalView.setId(this.model.id);
            },
            'click .archive': function() {
                this.model.setArchived();
            },
            'click .unarchive': function() {
                this.model.unsetArchived();
            },
            'click .delete': function() {
                bootbox.confirm('Are you sure you want to delete this task?', function(res){
                    if(res) {
                        this.model.destroy();
                    }
                }.bind(this))
            }
        },
        template: _.template($('#task-template').text()),
        render: function() {
            this.$el = $('<li data-taskid="' + this.model.get('id') + '" />').html(this.template({
                model: this.model.toJSON(), 
                currentCategoryId: actionModel.get('activeCategory')
            }));
            this.$el.appendTo(this.parent);
            $('[data-toggle=tooltip]').tooltip();
        },
        initialize: function(opt) {
            this.parent = this.$el;
            this.model = opt.model;
            this.render();
        }
    });

    var TaskInfoModalView = new(Backbone.View.extend({
        id: null,
        data: {},
        followerTemplate: _.template(' <li class="is-follower" data-toggle="tooltip" title="<%-first_name%> <%-last_name%>" data-userid="<%-user_id%>"> <a href="#" class="user-avatar"><img width="100%" src="<%-(user_image) ? user_image.replace("original", "avatar") : "/images/user.jpg"%>"></a> </li> '),
        subtaskTemplate: _.template(' <li class="list-group-item <%if(is_done){%>is_done<%}%>"> <%if(is_done){%><button class="btn btn-danger btn-sm pull-right delete-subtask" data-subtaskid="<%-id%>">×</button><%}%> <label><input type="checkbox" data-subtaskid="<%-id%>" <%if(is_done){%>checked<%}%> class="subtask-set-done"> <%-name%></label></li> '),
        events: {
            'click .is-follower': function(e) {
                var target = $(e.currentTarget);
                if($('.is-follower').index(target[0]) > 0) {
                    bootbox.confirm('Are you sure you want to remove this follower?', function(res){
                        if(res) {
                            $.get('/tasks/' + this.id + '/remove-follower/' + target.data('userid'), function(){
                                this.reload();
                            }.bind(this))
                        }
                    }.bind(this))
                }
            },
            'click .delete-subtask': function(e) {
                bootbox.confirm('Are you sure you want to delete this subtask?', function(res){
                    if(res) {
                        $.ajax({
                            method: 'DELETE',
                            url: '/subtasks/' + $(e.target).data('subtaskid')
                        }).success(function(){
                            this.reload();
                        }.bind(this))
                    }
                }.bind(this));
            },
            'change .subtask-set-done': function(e) {
                var target = $(e.currentTarget);
                if(target.is(':checked')) {
                    $.get('/subtasks/' + target.data('subtaskid') + '/set-done', function(){
                        this.reload();
                    }.bind(this));
                } else {
                    $.get('/subtasks/' + target.data('subtaskid') + '/set-undone', function(){
                        this.reload();
                    }.bind(this));
                }
            },
            'keyup #new-subtask': function(e) {
                var target = $(e.currentTarget);
                var val = target.val().trim();
                if(e.keyCode === 13 && e.shiftKey && val) {
                    $.post('/subtasks', {
                        todo_id: this.id,
                        name: val
                    }, function(){
                        this.reload();
                    }.bind(this));
                    target.val('');
                }
            },
            'click .delete-task': function() {
                bootbox.confirm('Are you sure you want to delete this task?', function(res){
                    var task = tasksCollection.get(this.id);
                    if(res && task) {
                        task.destroy();
                        this.$el.modal('hide');
                        $.notify('Task deleted.', 'success');
                    }
                }.bind(this))
            },
            'click .archive-task': function() {
                var task = tasksCollection.get(this.id);
                if(task) {
                    task.set({archived: 1}).save();
                    this.reload();
                }
            },
            'click .unarchive-task': function() {
                var task = tasksCollection.get(this.id);
                if(task) {
                    task.set({archived: 0}).save();
                    this.reload();
                }
            }
        },
        setId: function(id) {
            this.id = id;
            TaskNoteView.setId(id);
            this.reload();
        },
        populate: function() {
            $('.is-follower', this.el).remove();
            this.data.followers.forEach(function(follower){
                if(follower.id !== this.data.owner.id)
                    $('.followers', this.el).prepend(this.followerTemplate(follower.profile));
            }.bind(this));
            var first;
            $('.followers', this.el)
                .prepend(this.followerTemplate(this.data.owner.profile))
                .sortable({
                    start: function(e, ui) {
                        first = $('.is-follower').first()[0];
                    },
                    update: function(e, ui) {
                        if($('.is-follower').first()[0] !== first) {
                            $.get('/tasks/' + this.id + '/set-owner/' + $('.is-follower').first().data('userid'), function(){
                                // this.reload();
                            }.bind(this))
                        }
                    }.bind(this)
                });
            $('#subtasks-list').html('');
            this.data.subtasks.sort(function(a, b){
                if(a.is_done) {
                    return -1;
                }
                if(b.is_done) {
                    return 1;
                }
                return (new Date(a.created_at).getTime() > new Date(b.created_at).getTime()) ? 1 : -1;
            }).forEach(function(subtask){
                $('#subtasks-list').prepend(this.subtaskTemplate(subtask))
            }.bind(this))
            $('.unarchive-task,.archive-task').hide();
            if(this.data.archived) {
                $('.unarchive-task').show();
            } else {
                $('.archive-task').show();
            }
            $('.modal-title')
            .editable('destroy')
            .text(this.data.description)
            .data('value', this.data.description)
            .editable({
                value: this.data.description,
                success: function(xhr, val) {
                    var task = tasksCollection.get(this.id);
                    var val = val.trim();
                    if(task && val) {
                        task.set({description: val}).save();
                        this.reload();
                    }
                }.bind(this)
            });
            $('[data-toggle=tooltip]').tooltip();
            this.show();
        },
        reload: function() {
            $.get('/taskinfo/' + this.id, function(task){
                this.data = task;
                this.populate();
            }.bind(this));
        },
        show: function() {
            this.$el.modal('show');
        },
        initialize: function() {
            $('#add-follower').editable({
                display: function() {
                    return '+';
                },
                success: function(response, value) {
                    $.get('/tasks/' + this.id + '/add-follower/' + value, function(){
                        this.reload();
                    }.bind(this))
                }.bind(this)
            });
        }
    }))({
        el: '#task-info'
    });

    var format_date = function(date) {
        return $.datepicker.formatDate(app_locale.long_date, new Date(date)) + ' @ ' + date.split(' ').pop();
    }

    var TaskNoteView = new(Backbone.View.extend({
        id: null,
        data: [],
        isEditing: false,
        activeNoteTemplate: _.template('<div class="panel-heading"><h3 class="panel-title"><%-format_date(updated_at)%></h3></div><div class="panel-body"><%=nl2br(note)%></div><div class="panel-footer text-right"><a class="btn btn-primary btn-sm" id="edit-note" data-noteid="<%-id%>">Edit</a> <a class="btn btn-danger btn-sm" data-noteid="<%-id%>" id="delete-note">Delete</a></div>'),
        noteLiTemplate: _.template('<li data-noteid="<%-id%>"><a href="#" data-toggle="tooltip" data-title=""><%-note.substr(0, 20)%></a></li>'),
        events: {
            'keyup #note-content': function(e) {
                if(e.keyCode === 13 && e.shiftKey) {
                    this.saveNote();
                }
            },
            'click #add-new-note': function() {
                this.isEditing = false;
                $('#active-note').hide();
                $('#new-note').show();
                $('#note-content').val('').focus();
                $('.note-titles li').removeClass('active');
                $('#note-input-title').text('New Note');
            },
            'click #save-new-note': 'saveNote',
            'click .note-titles li' : function(e) {
                var target = $(e.currentTarget);
                this.showNote(target.data('noteid'));
            },
            'click #delete-note': function(e) {
                var target = $(e.currentTarget);
                bootbox.confirm('Are you sure you want to delete this note?', function(res){
                    if(res) {
                        $.ajax({
                            method: 'DELETE',
                            url: '/tasks/' + target.data('noteid') + '/notes'
                        }).success(function(){
                            this.reload();
                        }.bind(this));
                    }
                }.bind(this))
            },
            'click #edit-note': function(e) {
                var target = $(e.currentTarget);
                var activeNote = _.findWhere(this.data, {
                    id: target.data('noteid')
                });
                if(activeNote) {
                    this.isEditing = activeNote.id;
                    $('#note-input-title').text('Editing');
                    $('#note-content').val(activeNote.note);
                    $('#active-note').hide();
                    $('#new-note').show();
                    $('#note-content').focus();
                }
            }
        },
        saveNote: function() {
            var val = $('#note-content').val().trim();
            if(val) {
                if(!this.isEditing) {
                    $.post('/tasks/'+ this.id +'/notes', {
                        note: val
                    }, function(data){
                        this.reload();
                    }.bind(this));
                    $('#note-content').val('');
                } else {
                    $.ajax({
                        method: 'PUT',
                        data: {
                            note: val
                        },
                        url: '/tasks/'+ this.isEditing +'/notes'
                    }).success(function(){
                        this.reload();
                    }.bind(this))
                }
            }
        },
        showNote: function(id) {
            var activeNote = _.findWhere(this.data, {
                id: id
            });
            $('#new-note').hide();
            $('.note-titles li').removeClass('active');
            $('[data-noteid="' + id + '"]').addClass('active');
            $(".panel-primary", $('#active-note')[0]).html(this.activeNoteTemplate(activeNote))
            $('#active-note').show();
        },
        setId: function(id) {
            this.id = id;
            this.reload();
        },
        reload: function() {
            $.get('/tasks/' + this.id + '/notes', function(data){
                this.data = data;
                this.render();
            }.bind(this));
        },
        render: function() {
            $('.note-titles').html('');
            this.data.forEach(function(note){
                $('.note-titles').append(this.noteLiTemplate(note));
            }.bind(this));
            if(this.data.length > 0) {
                this.showNote(this.data[0].id);
            } else {
                $('#add-new-note').trigger('click');
            }
        },
        initialize: function() {
            $('#new-note').hide();
            autosize($('#note-content'));
            $('#active-note').hide();
        }
    }))({
        el: '#notes'
    })

// }).call(this);