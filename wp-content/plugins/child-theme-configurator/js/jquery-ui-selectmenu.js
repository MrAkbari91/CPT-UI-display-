/*! jQuery UI - v1.11.2 - 2014-12-04
* http://jqueryui.com
* Includes: core.js, widget.js, position.js, menu.js, selectmenu.js
* Copyright 2014 jQuery Foundation and other contributors; Licensed MIT */

(function( factory ) {
    if ( typeof define === "function" && define.amd ) {

        // AMD. Register as an anonymous module.
        define([ "jquery" ], factory );
    } else {

        // Browser globals
        factory( jQuery );
    }
}(function( $ ) {


/*!
 * jQuery UI Widget 1.11.2
 * http://jqueryui.com
 *
 * Copyright 2014 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/jQuery.widget/
 */


var widget_uuid = 0,
    widget_slice = Array.prototype.slice;

$.cleanData = (function( orig ) {
    return function( elems ) {
        var events, elem, i;
        for ( i = 0; (elem = elems[i]) != null; i++ ) {
            try {

                // Only trigger remove when necessary to save time
                events = $._data( elem, "events" );
                if ( events && events.remove ) {
                    $( elem ).triggerHandler( "remove" );
                }

            // http://bugs.jquery.com/ticket/8235
            } catch ( e ) {}
        }
        orig( elems );
    };
})( $.cleanData );

$.widget = function( name, base, prototype ) {
    var fullName, existingConstructor, constructor, basePrototype,
        // proxiedPrototype allows the provided prototype to remain unmodified
        // so that it can be used as a mixin for multiple widgets (#8876)
        proxiedPrototype = {},
        namespace = name.split( "." )[ 0 ];

    name = name.split( "." )[ 1 ];
    fullName = namespace + "-" + name;

    if ( !prototype ) {
        prototype = base;
        base = $.Widget;
    }

    // create selector for plugin
    $.expr[ ":" ][ fullName.toLowerCase() ] = function( elem ) {
        return !!$.data( elem, fullName );
    };

    $[ namespace ] = $[ namespace ] || {};
    existingConstructor = $[ namespace ][ name ];
    constructor = $[ namespace ][ name ] = function( options, element ) {
        // allow instantiation without "new" keyword
        if ( !this._createWidget ) {
            return new constructor( options, element );
        }

        // allow instantiation without initializing for simple inheritance
        // must use "new" keyword (the code above always passes args)
        if ( arguments.length ) {
            this._createWidget( options, element );
        }
    };
    // extend with the existing constructor to carry over any static properties
    $.extend( constructor, existingConstructor, {
        version: prototype.version,
        // copy the object used to create the prototype in case we need to
        // redefine the widget later
        _proto: $.extend( {}, prototype ),
        // track widgets that inherit from this widget in case this widget is
        // redefined after a widget inherits from it
        _childConstructors: []
    });

    basePrototype = new base();
    // we need to make the options hash a property directly on the new instance
    // otherwise we'll modify the options hash on the prototype that we're
    // inheriting from
    basePrototype.options = $.widget.extend( {}, basePrototype.options );
    $.each( prototype, function( prop, value ) {
        if ( !$.isFunction( value ) ) {
            proxiedPrototype[ prop ] = value;
            return;
        }
        proxiedPrototype[ prop ] = (function() {
            var _super = function() {
                    return base.prototype[ prop ].apply( this, arguments );
                },
                _superApply = function( args ) {
                    return base.prototype[ prop ].apply( this, args );
                };
            return function() {
                var __super = this._super,
                    __superApply = this._superApply,
                    returnValue;

                this._super = _super;
                this._superApply = _superApply;

                returnValue = value.apply( this, arguments );

                this._super = __super;
                this._superApply = __superApply;

                return returnValue;
            };
        })();
    });
    constructor.prototype = $.widget.extend( basePrototype, {
        // TODO: remove support for widgetEventPrefix
        // always use the name + a colon as the prefix, e.g., draggable:start
        // don't prefix for widgets that aren't DOM-based
        widgetEventPrefix: existingConstructor ? (basePrototype.widgetEventPrefix || name) : name
    }, proxiedPrototype, {
        constructor: constructor,
        namespace: namespace,
        widgetName: name,
        widgetFullName: fullName
    });

    // If this widget is being redefined then we need to find all widgets that
    // are inheriting from it and redefine all of them so that they inherit from
    // the new version of this widget. We're essentially trying to replace one
    // level in the prototype chain.
    if ( existingConstructor ) {
        $.each( existingConstructor._childConstructors, function( i, child ) {
            var childPrototype = child.prototype;

            // redefine the child widget using the same prototype that was
            // originally used, but inherit from the new version of the base
            $.widget( childPrototype.namespace + "." + childPrototype.widgetName, constructor, child._proto );
        });
        // remove the list of existing child constructors from the old constructor
        // so the old child constructors can be garbage collected
        delete existingConstructor._childConstructors;
    } else {
        base._childConstructors.push( constructor );
    }

    $.widget.bridge( name, constructor );

    return constructor;
};

$.widget.extend = function( target ) {
    var input = widget_slice.call( arguments, 1 ),
        inputIndex = 0,
        inputLength = input.length,
        key,
        value;
    for ( ; inputIndex < inputLength; inputIndex++ ) {
        for ( key in input[ inputIndex ] ) {
            value = input[ inputIndex ][ key ];
            if ( input[ inputIndex ].hasOwnProperty( key ) && value !== undefined ) {
                // Clone objects
                if ( $.isPlainObject( value ) ) {
                    target[ key ] = $.isPlainObject( target[ key ] ) ?
                        $.widget.extend( {}, target[ key ], value ) :
                        // Don't extend strings, arrays, etc. with objects
                        $.widget.extend( {}, value );
                // Copy everything else by reference
                } else {
                    target[ key ] = value;
                }
            }
        }
    }
    return target;
};

$.widget.bridge = function( name, object ) {
    var fullName = object.prototype.widgetFullName || name;
    $.fn[ name ] = function( options ) {
        var isMethodCall = typeof options === "string",
            args = widget_slice.call( arguments, 1 ),
            returnValue = this;

        // allow multiple hashes to be passed on init
        options = !isMethodCall && args.length ?
            $.widget.extend.apply( null, [ options ].concat(args) ) :
            options;

        if ( isMethodCall ) {
            this.each(function() {
                var methodValue,
                    instance = $.data( this, fullName );
                if ( options === "instance" ) {
                    returnValue = instance;
                    return false;
                }
                if ( !instance ) {
                    return $.error( "cannot call methods on " + name + " prior to initialization; " +
                        "attempted to call method '" + options + "'" );
                }
                if ( !$.isFunction( instance[options] ) || options.charAt( 0 ) === "_" ) {
                    return $.error( "no such method '" + options + "' for " + name + " widget instance" );
                }
                methodValue = instance[ options ].apply( instance, args );
                if ( methodValue !== instance && methodValue !== undefined ) {
                    returnValue = methodValue && methodValue.jquery ?
                        returnValue.pushStack( methodValue.get() ) :
                        methodValue;
                    return false;
                }
            });
        } else {
            this.each(function() {
                var instance = $.data( this, fullName );
                if ( instance ) {
                    instance.option( options || {} );
                    if ( instance._init ) {
                        instance._init();
                    }
                } else {
                    $.data( this, fullName, new object( options, this ) );
                }
            });
        }

        return returnValue;
    };
};

$.Widget = function( /* options, element */  ) {};


$.Widget._childConstructors = [];

$.Widget.prototype = {
    widgetName: "widget",
    widgetEventPrefix: "",
    defaultElement: "<div>",
    options: {
        disabled: false,

        // callbacks
        create: null
    },
    _createWidget: function( options, element ) {
        element = $( element || this.defaultElement || this )[ 0 ];
        this.element = $( element );
        this.uuid = widget_uuid++;
        this.eventNamespace = "." + this.widgetName + this.uuid;

        this.bindings = $();
        this.hoverable = $();
        this.focusable = $();

        if ( element !== this ) {
            $.data( element, this.widgetFullName, this );
            this._on( true, this.element, {
                remove: function( event ) {
                    if ( event.target === element ) {
                        this.destroy();
                    }
                }
            });
            this.document = $( element.style ?
                // element within the document
                element.ownerDocument :
                // element is window or document
                element.document || element );
            this.window = $( this.document[0].defaultView || this.document[0].parentWindow );
        }

        this.options = $.widget.extend( {},
            this.options,
            this._getCreateOptions(),
            options );

        this._create();
        this._trigger( "create", null, this._getCreateEventData() );
        this._init();
    },
    _getCreateOptions: $.noop,
    _getCreateEventData: $.noop,
    _create: $.noop,
    _init: $.noop,

    destroy: function() {
        this._destroy();
        // we can probably remove the unbind calls in 2.0
        // all event bindings should go through this._on()
        this.element
            .unbind( this.eventNamespace )
            .removeData( this.widgetFullName )
            // support: jquery <1.6.4
            // http://bugs.jquery.com/ticket/9413
            .removeData( $.camelCase( this.widgetFullName ) );
        this.widget()
            .unbind( this.eventNamespace )
            .removeAttr( "aria-disabled" )
            .removeClass(
                this.widgetFullName + "-disabled " +
                "ui-state-disabled" );

        // clean up events and states
        this.bindings.unbind( this.eventNamespace );
        this.hoverable.removeClass( "ui-state-hover" );
        this.focusable.removeClass( "ui-state-focus" );
    },
    _destroy: $.noop,

    widget: function() {
        return this.element;
    },

    option: function( key, value ) {
        var options = key,
            parts,
            curOption,
            i;

        if ( arguments.length === 0 ) {
            // don't return a reference to the internal hash
            return $.widget.extend( {}, this.options );
        }

        if ( typeof key === "string" ) {
            // handle nested keys, e.g., "foo.bar" => { foo: { bar: ___ } }
            options = {};
            parts = key.split( "." );
            key = parts.shift();
            if ( parts.length ) {
                curOption = options[ key ] = $.widget.extend( {}, this.options[ key ] );
                for ( i = 0; i < parts.length - 1; i++ ) {
                    curOption[ parts[ i ] ] = curOption[ parts[ i ] ] || {};
                    curOption = curOption[ parts[ i ] ];
                }
                key = parts.pop();
                if ( arguments.length === 1 ) {
                    return curOption[ key ] === undefined ? null : curOption[ key ];
                }
                curOption[ key ] = value;
            } else {
                if ( arguments.length === 1 ) {
                    return this.options[ key ] === undefined ? null : this.options[ key ];
                }
                options[ key ] = value;
            }
        }

        this._setOptions( options );

        return this;
    },
    _setOptions: function( options ) {
        var key;

        for ( key in options ) {
            this._setOption( key, options[ key ] );
        }

        return this;
    },
    _setOption: function( key, value ) {
        this.options[ key ] = value;

        if ( key === "disabled" ) {
            this.widget()
                .toggleClass( this.widgetFullName + "-disabled", !!value );

            // If the widget is becoming disabled, then nothing is interactive
            if ( value ) {
                this.hoverable.removeClass( "ui-state-hover" );
                this.focusable.removeClass( "ui-state-focus" );
            }
        }

        return this;
    },

    enable: function() {
        return this._setOptions({ disabled: false });
    },
    disable: function() {
        return this._setOptions({ disabled: true });
    },

    _on: function( suppressDisabledCheck, element, handlers ) {
        var delegateElement,
            instance = this;

        // no suppressDisabledCheck flag, shuffle arguments
        if ( typeof suppressDisabledCheck !== "boolean" ) {
            handlers = element;
            element = suppressDisabledCheck;
            suppressDisabledCheck = false;
        }

        // no element argument, shuffle and use this.element
        if ( !handlers ) {
            handlers = element;
            element = this.element;
            delegateElement = this.widget();
        } else {
            element = delegateElement = $( element );
            this.bindings = this.bindings.add( element );
        }

        $.each( handlers, function( event, handler ) {
            function handlerProxy() {
                // allow widgets to customize the disabled handling
                // - disabled as an array instead of boolean
                // - disabled class as method for disabling individual parts
                if ( !suppressDisabledCheck &&
                        ( instance.options.disabled === true ||
                            $( this ).hasClass( "ui-state-disabled" ) ) ) {
                    return;
                }
                return ( typeof handler === "string" ? instance[ handler ] : handler )
                    .apply( instance, arguments );
            }

            // copy the guid so direct unbinding works
            if ( typeof handler !== "string" ) {
                handlerProxy.guid = handler.guid =
                    handler.guid || handlerProxy.guid || $.guid++;
            }

            var match = event.match( /^([\w:-]*)\s*(.*)$/ ),
                eventName = match[1] + instance.eventNamespace,
                selector = match[2];
            if ( selector ) {
                delegateElement.delegate( selector, eventName, handlerProxy );
            } else {
                element.bind( eventName, handlerProxy );
            }
        });
    },

    _off: function( element, eventName ) {
        eventName = (eventName || "").split( " " ).join( this.eventNamespace + " " ) +
            this.eventNamespace;
        element.unbind( eventName ).undelegate( eventName );

        // Clear the stack to avoid memory leaks (#10056)
        this.bindings = $( this.bindings.not( element ).get() );
        this.focusable = $( this.focusable.not( element ).get() );
        this.hoverable = $( this.hoverable.not( element ).get() );
    },

    _delay: function( handler, delay ) {
        function handlerProxy() {
            return ( typeof handler === "string" ? instance[ handler ] : handler )
                .apply( instance, arguments );
        }
        var instance = this;
        return setTimeout( handlerProxy, delay || 0 );
    },

    _hoverable: function( element ) {
        this.hoverable = this.hoverable.add( element );
        this._on( element, {
            mouseenter: function( event ) {
                $( event.currentTarget ).addClass( "ui-state-hover" );
            },
            mouseleave: function( event ) {
                $( event.currentTarget ).removeClass( "ui-state-hover" );
            }
        });
    },

    _focusable: function( element ) {
        this.focusable = this.focusable.add( element );
        this._on( element, {
            focusin: function( event ) {
                $( event.currentTarget ).addClass( "ui-state-focus" );
            },
            focusout: function( event ) {
                $( event.currentTarget ).removeClass( "ui-state-focus" );
            }
        });
    },

    _trigger: function( type, event, data ) {
        var prop, orig,
            callback = this.options[ type ];

        data = data || {};
        event = $.Event( event );
        event.type = ( type === this.widgetEventPrefix ?
            type :
            this.widgetEventPrefix + type ).toLowerCase();
        // the original event may come from any element
        // so we need to reset the target on the new event
        event.target = this.element[ 0 ];

        // copy original event properties over to the new event
        orig = event.originalEvent;
        if ( orig ) {
            for ( prop in orig ) {
                if ( !( prop in event ) ) {
                    event[ prop ] = orig[ prop ];
                }
            }
        }

        this.element.trigger( event, data );
        return !( $.isFunction( callback ) &&
            callback.apply( this.element[0], [ event ].concat( data ) ) === false ||
            event.isDefaultPrevented() );
    }
};

$.each( { show: "fadeIn", hide: "fadeOut" }, function( method, defaultEffect ) {
    $.Widget.prototype[ "_" + method ] = function( element, options, callback ) {
        if ( typeof options === "string" ) {
            options = { effect: options };
        }
        var hasOptions,
            effectName = !options ?
                method :
                options === true || typeof options === "number" ?
                    defaultEffect :
                    options.effect || defaultEffect;
        options = options || {};
        if ( typeof options === "number" ) {
            options = { duration: options };
        }
        hasOptions = !$.isEmptyObject( options );
        options.complete = callback;
        if ( options.delay ) {
            element.delay( options.delay );
        }
        if ( hasOptions && $.effects && $.effects.effect[ effectName ] ) {
            element[ method ]( options );
        } else if ( effectName !== method && element[ effectName ] ) {
            element[ effectName ]( options.duration, options.easing, callback );
        } else {
            element.queue(function( next ) {
                $( this )[ method ]();
                if ( callback ) {
                    callback.call( element[ 0 ] );
                }
                next();
            });
        }
    };
});

var widget = $.widget;




/*!
 * jQuery UI Menu 1.11.2
 * http://jqueryui.com
 *
 * Copyright 2014 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/menu/
 */


var menu = $.widget( "ui.menu", {
    version: "1.11.2",
    defaultElement: "<ul>",
    delay: 300,
    options: {
        icons: {
            submenu: "ui-icon-carat-1-e"
        },
        items: "> *",
        menus: "ul",
        position: {
            my: "left-1 top",
            at: "right top"
        },
        role: "menu",

        // callbacks
        blur: null,
        focus: null,
        select: null
    },

    _create: function() {
        this.activeMenu = this.element;

        // Flag used to prevent firing of the click handler
        // as the event bubbles up through nested menus
        this.mouseHandled = false;
        this.element
            .uniqueId()
            .addClass( "ui-menu ui-widget ui-widget-content" )
            .toggleClass( "ui-menu-icons", !!this.element.find( ".ui-icon" ).length )
            .attr({
                role: this.options.role,
                tabIndex: 0
            });

        if ( this.options.disabled ) {
            this.element
                .addClass( "ui-state-disabled" )
                .attr( "aria-disabled", "true" );
        }

        this._on({
            // Prevent focus from sticking to links inside menu after clicking
            // them (focus should always stay on UL during navigation).
            "mousedown .ui-menu-item": function( event ) {
                event.preventDefault();
            },
            "click .ui-menu-item": function( event ) {
                var target = $( event.target );
                if ( !this.mouseHandled && target.not( ".ui-state-disabled" ).length ) {
                    this.select( event );

                    // Only set the mouseHandled flag if the event will bubble, see #9469.
                    if ( !event.isPropagationStopped() ) {
                        this.mouseHandled = true;
                    }

                    // Open submenu on click
                    if ( target.has( ".ui-menu" ).length ) {
                        this.expand( event );
                    } else if ( !this.element.is( ":focus" ) && $( this.document[ 0 ].activeElement ).closest( ".ui-menu" ).length ) {

                        // Redirect focus to the menu
                        this.element.trigger( "focus", [ true ] );

                        // If the active item is on the top level, let it stay active.
                        // Otherwise, blur the active item since it is no longer visible.
                        if ( this.active && this.active.parents( ".ui-menu" ).length === 1 ) {
                            clearTimeout( this.timer );
                        }
                    }
                }
            },
            "mouseenter .ui-menu-item": function( event ) {
                // Ignore mouse events while typeahead is active, see #10458.
                // Prevents focusing the wrong item when typeahead causes a scroll while the mouse
                // is over an item in the menu
                if ( this.previousFilter ) {
                    return;
                }
                var target = $( event.currentTarget );
                // Remove ui-state-active class from siblings of the newly focused menu item
                // to avoid a jump caused by adjacent elements both having a class with a border
                target.siblings( ".ui-state-active" ).removeClass( "ui-state-active" );
                this.focus( event, target );
            },
            mouseleave: "collapseAll",
            "mouseleave .ui-menu": "collapseAll",
            focus: function( event, keepActiveItem ) {
                // If there's already an active item, keep it active
                // If not, activate the first item
                var item = this.active || this.element.find( this.options.items ).eq( 0 );

                if ( !keepActiveItem ) {
                    this.focus( event, item );
                }
            },
            blur: function( event ) {
                this._delay(function() {
                    if ( !$.contains( this.element[0], this.document[0].activeElement ) ) {
                        this.collapseAll( event );
                    }
                });
            },
            keydown: "_keydown"
        });

        this.refresh();

        // Clicks outside of a menu collapse any open menus
        this._on( this.document, {
            click: function( event ) {
                if ( this._closeOnDocumentClick( event ) ) {
                    this.collapseAll( event );
                }

                // Reset the mouseHandled flag
                this.mouseHandled = false;
            }
        });
    },

    _destroy: function() {
        // Destroy (sub)menus
        this.element
            .removeAttr( "aria-activedescendant" )
            .find( ".ui-menu" ).addBack()
                .removeClass( "ui-menu ui-widget ui-widget-content ui-menu-icons ui-front" )
                .removeAttr( "role" )
                .removeAttr( "tabIndex" )
                .removeAttr( "aria-labelledby" )
                .removeAttr( "aria-expanded" )
                .removeAttr( "aria-hidden" )
                .removeAttr( "aria-disabled" )
                .removeUniqueId()
                .show();

        // Destroy menu items
        this.element.find( ".ui-menu-item" )
            .removeClass( "ui-menu-item" )
            .removeAttr( "role" )
            .removeAttr( "aria-disabled" )
            .removeUniqueId()
            .removeClass( "ui-state-hover" )
            .removeAttr( "tabIndex" )
            .removeAttr( "role" )
            .removeAttr( "aria-haspopup" )
            .children().each( function() {
                var elem = $( this );
                if ( elem.data( "ui-menu-submenu-carat" ) ) {
                    elem.remove();
                }
            });

        // Destroy menu dividers
        this.element.find( ".ui-menu-divider" ).removeClass( "ui-menu-divider ui-widget-content" );
    },

    _keydown: function( event ) {
        var match, prev, character, skip,
            preventDefault = true;

        switch ( event.keyCode ) {
        case $.ui.keyCode.PAGE_UP:
            this.previousPage( event );
            break;
        case $.ui.keyCode.PAGE_DOWN:
            this.nextPage( event );
            break;
        case $.ui.keyCode.HOME:
            this._move( "first", "first", event );
            break;
        case $.ui.keyCode.END:
            this._move( "last", "last", event );
            break;
        case $.ui.keyCode.UP:
            this.previous( event );
            break;
        case $.ui.keyCode.DOWN:
            this.next( event );
            break;
        case $.ui.keyCode.LEFT:
            this.collapse( event );
            break;
        case $.ui.keyCode.RIGHT:
            if ( this.active && !this.active.is( ".ui-state-disabled" ) ) {
                this.expand( event );
            }
            break;
        case $.ui.keyCode.ENTER:
        case $.ui.keyCode.SPACE:
            this._activate( event );
            break;
        case $.ui.keyCode.ESCAPE:
            this.collapse( event );
            break;
        default:
            preventDefault = false;
            prev = this.previousFilter || "";
            character = String.fromCharCode( event.keyCode );
            skip = false;

            clearTimeout( this.filterTimer );

            if ( character === prev ) {
                skip = true;
            } else {
                character = prev + character;
            }

            match = this._filterMenuItems( character );
            match = skip && match.index( this.active.next() ) !== -1 ?
                this.active.nextAll( ".ui-menu-item" ) :
                match;

            // If no matches on the current filter, reset to the last character pressed
            // to move down the menu to the first item that starts with that character
            if ( !match.length ) {
                character = String.fromCharCode( event.keyCode );
                match = this._filterMenuItems( character );
            }

            if ( match.length ) {
                this.focus( event, match );
                this.previousFilter = character;
                this.filterTimer = this._delay(function() {
                    delete this.previousFilter;
                }, 1000 );
            } else {
                delete this.previousFilter;
            }
        }

        if ( preventDefault ) {
            event.preventDefault();
        }
    },

    _activate: function( event ) {
        if ( !this.active.is( ".ui-state-disabled" ) ) {
            if ( this.active.is( "[aria-haspopup='true']" ) ) {
                this.expand( event );
            } else {
                this.select( event );
            }
        }
    },

    refresh: function() {
        var menus, items,
            that = this,
            icon = this.options.icons.submenu,
            submenus = this.element.find( this.options.menus );

        this.element.toggleClass( "ui-menu-icons", !!this.element.find( ".ui-icon" ).length );

        // Initialize nested menus
        submenus.filter( ":not(.ui-menu)" )
            .addClass( "ui-menu ui-widget ui-widget-content ui-front" )
            .hide()
            .attr({
                role: this.options.role,
                "aria-hidden": "true",
                "aria-expanded": "false"
            })
            .each(function() {
                var menu = $( this ),
                    item = menu.parent(),
                    submenuCarat = $( "<span>" )
                        .addClass( "ui-menu-icon ui-icon " + icon )
                        .data( "ui-menu-submenu-carat", true );

                item
                    .attr( "aria-haspopup", "true" )
                    .prepend( submenuCarat );
                menu.attr( "aria-labelledby", item.attr( "id" ) );
            });

        menus = submenus.add( this.element );
        items = menus.find( this.options.items );

        // Initialize menu-items containing spaces and/or dashes only as dividers
        items.not( ".ui-menu-item" ).each(function() {
            var item = $( this );
            if ( that._isDivider( item ) ) {
                item.addClass( "ui-widget-content ui-menu-divider" );
            }
        });

        // Don't refresh list items that are already adapted
        items.not( ".ui-menu-item, .ui-menu-divider" )
            .addClass( "ui-menu-item" )
            .uniqueId()
            .attr({
                tabIndex: -1,
                role: this._itemRole()
            });

        // Add aria-disabled attribute to any disabled menu item
        items.filter( ".ui-state-disabled" ).attr( "aria-disabled", "true" );

        // If the active item has been removed, blur the menu
        if ( this.active && !$.contains( this.element[ 0 ], this.active[ 0 ] ) ) {
            this.blur();
        }
    },

    _itemRole: function() {
        return {
            menu: "menuitem",
            listbox: "option"
        }[ this.options.role ];
    },

    _setOption: function( key, value ) {
        if ( key === "icons" ) {
            this.element.find( ".ui-menu-icon" )
                .removeClass( this.options.icons.submenu )
                .addClass( value.submenu );
        }
        if ( key === "disabled" ) {
            this.element
                .toggleClass( "ui-state-disabled", !!value )
                .attr( "aria-disabled", value );
        }
        this._super( key, value );
    },

    focus: function( event, item ) {
        var nested, focused;
        this.blur( event, event && event.type === "focus" );

        this._scrollIntoView( item );

        this.active = item.first();
        focused = this.active.addClass( "ui-state-focus" ).removeClass( "ui-state-active" );
        // Only update aria-activedescendant if there's a role
        // otherwise we assume focus is managed elsewhere
        if ( this.options.role ) {
            this.element.attr( "aria-activedescendant", focused.attr( "id" ) );
        }

        // Highlight active parent menu item, if any
        this.active
            .parent()
            .closest( ".ui-menu-item" )
            .addClass( "ui-state-active" );

        if ( event && event.type === "keydown" ) {
            this._close();
        } else {
            this.timer = this._delay(function() {
                this._close();
            }, this.delay );
        }

        nested = item.children( ".ui-menu" );
        if ( nested.length && event && ( /^mouse/.test( event.type ) ) ) {
            this._startOpening(nested);
        }
        this.activeMenu = item.parent();

        this._trigger( "focus", event, { item: item } );
    },

    _scrollIntoView: function( item ) {
        var borderTop, paddingTop, offset, scroll, elementHeight, itemHeight;
        if ( this._hasScroll() ) {
            borderTop = parseFloat( $.css( this.activeMenu[0], "borderTopWidth" ) ) || 0;
            paddingTop = parseFloat( $.css( this.activeMenu[0], "paddingTop" ) ) || 0;
            offset = item.offset().top - this.activeMenu.offset().top - borderTop - paddingTop;
            scroll = this.activeMenu.scrollTop();
            elementHeight = this.activeMenu.height();
            itemHeight = item.outerHeight();

            if ( offset < 0 ) {
                this.activeMenu.scrollTop( scroll + offset );
            } else if ( offset + itemHeight > elementHeight ) {
                this.activeMenu.scrollTop( scroll + offset - elementHeight + itemHeight );
            }
        }
    },

    blur: function( event, fromFocus ) {
        if ( !fromFocus ) {
            clearTimeout( this.timer );
        }

        if ( !this.active ) {
            return;
        }

        this.active.removeClass( "ui-state-focus" );
        this.active = null;

        this._trigger( "blur", event, { item: this.active } );
    },

    _startOpening: function( submenu ) {
        clearTimeout( this.timer );

        // Don't open if already open fixes a Firefox bug that caused a .5 pixel
        // shift in the submenu position when mousing over the carat icon
        if ( submenu.attr( "aria-hidden" ) !== "true" ) {
            return;
        }

        this.timer = this._delay(function() {
            this._close();
            this._open( submenu );
        }, this.delay );
    },

    _open: function( submenu ) {
        var position = $.extend({
            of: this.active
        }, this.options.position );

        clearTimeout( this.timer );
        this.element.find( ".ui-menu" ).not( submenu.parents( ".ui-menu" ) )
            .hide()
            .attr( "aria-hidden", "true" );

        submenu
            .show()
            .removeAttr( "aria-hidden" )
            .attr( "aria-expanded", "true" )
            .position( position );
    },

    collapseAll: function( event, all ) {
        clearTimeout( this.timer );
        this.timer = this._delay(function() {
            // If we were passed an event, look for the submenu that contains the event
            var currentMenu = all ? this.element :
                $( event && event.target ).closest( this.element.find( ".ui-menu" ) );

            // If we found no valid submenu ancestor, use the main menu to close all sub menus anyway
            if ( !currentMenu.length ) {
                currentMenu = this.element;
            }

            this._close( currentMenu );

            this.blur( event );
            this.activeMenu = currentMenu;
        }, this.delay );
    },

    // With no arguments, closes the currently active menu - if nothing is active
    // it closes all menus.  If passed an argument, it will search for menus BELOW
    _close: function( startMenu ) {
        if ( !startMenu ) {
            startMenu = this.active ? this.active.parent() : this.element;
        }

        startMenu
            .find( ".ui-menu" )
                .hide()
                .attr( "aria-hidden", "true" )
                .attr( "aria-expanded", "false" )
            .end()
            .find( ".ui-state-active" ).not( ".ui-state-focus" )
                .removeClass( "ui-state-active" );
    },

    _closeOnDocumentClick: function( event ) {
        return !$( event.target ).closest( ".ui-menu" ).length;
    },

    _isDivider: function( item ) {

        // Match hyphen, em dash, en dash
        return !/[^\-\u2014\u2013\s]/.test( item.text() );
    },

    collapse: function( event ) {
        var newItem = this.active &&
            this.active.parent().closest( ".ui-menu-item", this.element );
        if ( newItem && newItem.length ) {
            this._close();
            this.focus( event, newItem );
        }
    },

    expand: function( event ) {
        var newItem = this.active &&
            this.active
                .children( ".ui-menu " )
                .find( this.options.items )
                .first();

        if ( newItem && newItem.length ) {
            this._open( newItem.parent() );

            // Delay so Firefox will not hide activedescendant change in expanding submenu from AT
            this._delay(function() {
                this.focus( event, newItem );
            });
        }
    },

    next: function( event ) {
        this._move( "next", "first", event );
    },

    previous: function( event ) {
        this._move( "prev", "last", event );
    },

    isFirstItem: function() {
        return this.active && !this.active.prevAll( ".ui-menu-item" ).length;
    },

    isLastItem: function() {
        return this.active && !this.active.nextAll( ".ui-menu-item" ).length;
    },

    _move: function( direction, filter, event ) {
        var next;
        if ( this.active ) {
            if ( direction === "first" || direction === "last" ) {
                next = this.active
                    [ direction === "first" ? "prevAll" : "nextAll" ]( ".ui-menu-item" )
                    .eq( -1 );
            } else {
                next = this.active
                    [ direction + "All" ]( ".ui-menu-item" )
                    .eq( 0 );
            }
        }
        if ( !next || !next.length || !this.active ) {
            next = this.activeMenu.find( this.options.items )[ filter ]();
        }

        this.focus( event, next );
    },

    nextPage: function( event ) {
        var item, base, height;

        if ( !this.active ) {
            this.next( event );
            return;
        }
        if ( this.isLastItem() ) {
            return;
        }
        if ( this._hasScroll() ) {
            base = this.active.offset().top;
            height = this.element.height();
            this.active.nextAll( ".ui-menu-item" ).each(function() {
                item = $( this );
                return item.offset().top - base - height < 0;
            });

            this.focus( event, item );
        } else {
            this.focus( event, this.activeMenu.find( this.options.items )
                [ !this.active ? "first" : "last" ]() );
        }
    },

    previousPage: function( event ) {
        var item, base, height;
        if ( !this.active ) {
            this.next( event );
            return;
        }
        if ( this.isFirstItem() ) {
            return;
        }
        if ( this._hasScroll() ) {
            base = this.active.offset().top;
            height = this.element.height();
            this.active.prevAll( ".ui-menu-item" ).each(function() {
                item = $( this );
                return item.offset().top - base + height > 0;
            });

            this.focus( event, item );
        } else {
            this.focus( event, this.activeMenu.find( this.options.items ).first() );
        }
    },

    _hasScroll: function() {
        return this.element.outerHeight() < this.element.prop( "scrollHeight" );
    },

    select: function( event ) {
        // TODO: It should never be possible to not have an active item at this
        // point, but the tests don't trigger mouseenter before click.
        this.active = this.active || $( event.target ).closest( ".ui-menu-item" );
        var ui = { item: this.active };
        if ( !this.active.has( ".ui-menu" ).length ) {
            this.collapseAll( event, true );
        }
        this._trigger( "select", event, ui );
    },

    _filterMenuItems: function(character) {
        var escapedCharacter = character.replace( /[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&" ),
            regex = new RegExp( "^" + escapedCharacter, "i" );

        return this.activeMenu
            .find( this.options.items )

            // Only match on items, not dividers or other content (#10571)
            .filter( ".ui-menu-item" )
            .filter(function() {
                return regex.test( $.trim( $( this ).text() ) );
            });
    }
});


/*!
 * jQuery UI Selectmenu 1.11.2
 * http://jqueryui.com
 *
 * Copyright 2014 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/selectmenu
 */


var selectmenu = $.widget( "ui.selectmenu", {
    version: "1.11.2",
    defaultElement: "<select>",
    options: {
        appendTo: null,
        disabled: null,
        icons: {
            button: "ui-icon-triangle-1-s"
        },
        position: {
            my: "left top",
            at: "left bottom",
            collision: "none"
        },
        width: null,

        // callbacks
        change: null,
        close: null,
        focus: null,
        open: null,
        select: null
    },

    _create: function() {
        var selectmenuId = this.element.uniqueId().attr( "id" );
        this.ids = {
            element: selectmenuId,
            button: selectmenuId + "-button",
            menu: selectmenuId + "-menu"
        };

        this._drawButton();
        this._drawMenu();

        if ( this.options.disabled ) {
            this.disable();
        }
    },

    _drawButton: function() {
        var that = this,
            tabindex = this.element.attr( "tabindex" );

        // Associate existing label with the new button
        this.label = $( "label[for='" + this.ids.element + "']" ).attr( "for", this.ids.button );
        this._on( this.label, {
            click: function( event ) {
                this.button.focus();
                event.preventDefault();
            }
        });

        // Hide original select element
        this.element.hide();

        // Create button
        this.button = $( "<span>", {
            "class": "ui-selectmenu-button ui-widget ui-state-default ui-corner-all",
            tabindex: tabindex || this.options.disabled ? -1 : 0,
            id: this.ids.button,
            role: "combobox",
            "aria-expanded": "false",
            "aria-autocomplete": "list",
            "aria-owns": this.ids.menu,
            "aria-haspopup": "true"
        })
            .insertAfter( this.element );

        $( "<span>", {
            "class": "ui-icon " + this.options.icons.button
        })
            .prependTo( this.button );

        this.buttonText = $( "<span>", {
            "class": "ui-selectmenu-text"
        })
            .appendTo( this.button );

        this._setText( this.buttonText, this.element.find( "option:selected" ).text() );
        this._resizeButton();

        this._on( this.button, this._buttonEvents );
        this.button.one( "focusin", function() {

            // Delay rendering the menu items until the button receives focus.
            // The menu may have already been rendered via a programmatic open.
            if ( !that.menuItems ) {
                that._refreshMenu();
            }
        });
        this._hoverable( this.button );
        this._focusable( this.button );
    },

    _drawMenu: function() {
        var that = this;

        // Create menu
        this.menu = $( "<ul>", {
            "aria-hidden": "true",
            "aria-labelledby": this.ids.button,
            id: this.ids.menu
        });

        // Wrap menu
        this.menuWrap = $( "<div>", {
            "class": "ui-selectmenu-menu ui-front"
        })
            .append( this.menu )
            .appendTo( this._appendTo() );

        // Initialize menu widget
        this.menuInstance = this.menu
            .menu({
                role: "listbox",
                select: function( event, ui ) {
                    event.preventDefault();

                    // support: IE8
                    // If the item was selected via a click, the text selection
                    // will be destroyed in IE
                    that._setSelection();

                    that._select( ui.item.data( "ui-selectmenu-item" ), event );
                },
                focus: function( event, ui ) {
                    var item = ui.item.data( "ui-selectmenu-item" );

                    // Prevent inital focus from firing and check if its a newly focused item
                    if ( that.focusIndex != null && item.index !== that.focusIndex ) {
                        that._trigger( "focus", event, { item: item } );
                        if ( !that.isOpen ) {
                            that._select( item, event );
                        }
                    }
                    that.focusIndex = item.index;

                    that.button.attr( "aria-activedescendant",
                        that.menuItems.eq( item.index ).attr( "id" ) );
                }
            })
            .menu( "instance" );

        // Adjust menu styles to dropdown
        this.menu
            .addClass( "ui-corner-bottom" )
            .removeClass( "ui-corner-all" );

        // Don't close the menu on mouseleave
        this.menuInstance._off( this.menu, "mouseleave" );

        // Cancel the menu's collapseAll on document click
        this.menuInstance._closeOnDocumentClick = function() {
            return false;
        };

        // Selects often contain empty items, but never contain dividers
        this.menuInstance._isDivider = function() {
            return false;
        };
    },

    refresh: function() {
        this._refreshMenu();
        this._setText( this.buttonText, this._getSelectedItem().text() );
        if ( !this.options.width ) {
            this._resizeButton();
        }
    },

    _refreshMenu: function() {
        this.menu.empty();

        var item,
            options = this.element.find( "option" );

        if ( !options.length ) {
            return;
        }

        this._parseOptions( options );
        this._renderMenu( this.menu, this.items );

        this.menuInstance.refresh();
        this.menuItems = this.menu.find( "li" ).not( ".ui-selectmenu-optgroup" );

        item = this._getSelectedItem();

        // Update the menu to have the correct item focused
        this.menuInstance.focus( null, item );
        this._setAria( item.data( "ui-selectmenu-item" ) );

        // Set disabled state
        this._setOption( "disabled", this.element.prop( "disabled" ) );
    },

    open: function( event ) {
        if ( this.options.disabled ) {
            return;
        }

        // If this is the first time the menu is being opened, render the items
        if ( !this.menuItems ) {
            this._refreshMenu();
        } else {

            // Menu clears focus on close, reset focus to selected item
            this.menu.find( ".ui-state-focus" ).removeClass( "ui-state-focus" );
            this.menuInstance.focus( null, this._getSelectedItem() );
        }

        this.isOpen = true;
        this._toggleAttr();
        this._resizeMenu();
        this._position();

        this._on( this.document, this._documentClick );

        this._trigger( "open", event );
    },

    _position: function() {
        this.menuWrap.position( $.extend( { of: this.button }, this.options.position ) );
    },

    close: function( event ) {
        if ( !this.isOpen ) {
            return;
        }

        this.isOpen = false;
        this._toggleAttr();

        this.range = null;
        this._off( this.document );

        this._trigger( "close", event );
    },

    widget: function() {
        return this.button;
    },

    menuWidget: function() {
        return this.menu;
    },

    _renderMenu: function( ul, items ) {
        var that = this,
            currentOptgroup = "";

        $.each( items, function( index, item ) {
            if ( item.optgroup !== currentOptgroup ) {
                $( "<li>", {
                    "class": "ui-selectmenu-optgroup ui-menu-divider" +
                        ( item.element.parent( "optgroup" ).prop( "disabled" ) ?
                            " ui-state-disabled" :
                            "" ),
                    text: item.optgroup
                })
                    .appendTo( ul );

                currentOptgroup = item.optgroup;
            }

            that._renderItemData( ul, item );
        });
    },

    _renderItemData: function( ul, item ) {
        return this._renderItem( ul, item ).data( "ui-selectmenu-item", item );
    },

    _renderItem: function( ul, item ) {
        var li = $( "<li>" );

        if ( item.disabled ) {
            li.addClass( "ui-state-disabled" );
        }
        this._setText( li, item.label );

        return li.appendTo( ul );
    },

    _setText: function( element, value ) {
        if ( value ) {
            element.text( value );
        } else {
            element.html( "&#160;" );
        }
    },

    _move: function( direction, event ) {
        var item, next,
            filter = ".ui-menu-item";

        if ( this.isOpen ) {
            item = this.menuItems.eq( this.focusIndex );
        } else {
            item = this.menuItems.eq( this.element[ 0 ].selectedIndex );
            filter += ":not(.ui-state-disabled)";
        }

        if ( direction === "first" || direction === "last" ) {
            next = item[ direction === "first" ? "prevAll" : "nextAll" ]( filter ).eq( -1 );
        } else {
            next = item[ direction + "All" ]( filter ).eq( 0 );
        }

        if ( next.length ) {
            this.menuInstance.focus( event, next );
        }
    },

    _getSelectedItem: function() {
        return this.menuItems.eq( this.element[ 0 ].selectedIndex );
    },

    _toggle: function( event ) {
        this[ this.isOpen ? "close" : "open" ]( event );
    },

    _setSelection: function() {
        var selection;

        if ( !this.range ) {
            return;
        }

        if ( window.getSelection ) {
            selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange( this.range );

        // support: IE8
        } else {
            this.range.select();
        }

        // support: IE
        // Setting the text selection kills the button focus in IE, but
        // restoring the focus doesn't kill the selection.
        this.button.focus();
    },

    _documentClick: {
        mousedown: function( event ) {
            if ( !this.isOpen ) {
                return;
            }

            if ( !$( event.target ).closest( ".ui-selectmenu-menu, #" + this.ids.button ).length ) {
                this.close( event );
            }
        }
    },

    _buttonEvents: {

        // Prevent text selection from being reset when interacting with the selectmenu (#10144)
        mousedown: function() {
            var selection;

            if ( window.getSelection ) {
                selection = window.getSelection();
                if ( selection.rangeCount ) {
                    this.range = selection.getRangeAt( 0 );
                }

            // support: IE8
            } else {
                this.range = document.selection.createRange();
            }
        },

        click: function( event ) {
            this._setSelection();
            this._toggle( event );
        },

        keydown: function( event ) {
            var preventDefault = true;
            switch ( event.keyCode ) {
                case $.ui.keyCode.TAB:
                case $.ui.keyCode.ESCAPE:
                    this.close( event );
                    preventDefault = false;
                    break;
                case $.ui.keyCode.ENTER:
                    if ( this.isOpen ) {
                        this._selectFocusedItem( event );
                    }
                    break;
                case $.ui.keyCode.UP:
                    if ( event.altKey ) {
                        this._toggle( event );
                    } else {
                        this._move( "prev", event );
                    }
                    break;
                case $.ui.keyCode.DOWN:
                    if ( event.altKey ) {
                        this._toggle( event );
                    } else {
                        this._move( "next", event );
                    }
                    break;
                case $.ui.keyCode.SPACE:
                    if ( this.isOpen ) {
                        this._selectFocusedItem( event );
                    } else {
                        this._toggle( event );
                    }
                    break;
                case $.ui.keyCode.LEFT:
                    this._move( "prev", event );
                    break;
                case $.ui.keyCode.RIGHT:
                    this._move( "next", event );
                    break;
                case $.ui.keyCode.HOME:
                case $.ui.keyCode.PAGE_UP:
                    this._move( "first", event );
                    break;
                case $.ui.keyCode.END:
                case $.ui.keyCode.PAGE_DOWN:
                    this._move( "last", event );
                    break;
                default:
                    this.menu.trigger( event );
                    preventDefault = false;
            }

            if ( preventDefault ) {
                event.preventDefault();
            }
        }
    },

    _selectFocusedItem: function( event ) {
        var item = this.menuItems.eq( this.focusIndex );
        if ( !item.hasClass( "ui-state-disabled" ) ) {
            this._select( item.data( "ui-selectmenu-item" ), event );
        }
    },

    _select: function( item, event ) {
        var oldIndex = this.element[ 0 ].selectedIndex;

        // Change native select element
        this.element[ 0 ].selectedIndex = item.index;
        this._setText( this.buttonText, item.label );
        this._setAria( item );
        this._trigger( "select", event, { item: item } );

        if ( item.index !== oldIndex ) {
            this._trigger( "change", event, { item: item } );
        }

        this.close( event );
    },

    _setAria: function( item ) {
        var id = this.menuItems.eq( item.index ).attr( "id" );

        this.button.attr({
            "aria-labelledby": id,
            "aria-activedescendant": id
        });
        this.menu.attr( "aria-activedescendant", id );
    },

    _setOption: function( key, value ) {
        if ( key === "icons" ) {
            this.button.find( "span.ui-icon" )
                .removeClass( this.options.icons.button )
                .addClass( value.button );
        }

        this._super( key, value );

        if ( key === "appendTo" ) {
            this.menuWrap.appendTo( this._appendTo() );
        }

        if ( key === "disabled" ) {
            this.menuInstance.option( "disabled", value );
            this.button
                .toggleClass( "ui-state-disabled", value )
                .attr( "aria-disabled", value );

            this.element.prop( "disabled", value );
            if ( value ) {
                this.button.attr( "tabindex", -1 );
                this.close();
            } else {
                this.button.attr( "tabindex", 0 );
            }
        }

        if ( key === "width" ) {
            this._resizeButton();
        }
    },

    _appendTo: function() {
        var element = this.options.appendTo;

        if ( element ) {
            element = element.jquery || element.nodeType ?
                $( element ) :
                this.document.find( element ).eq( 0 );
        }

        if ( !element || !element[ 0 ] ) {
            element = this.element.closest( ".ui-front" );
        }

        if ( !element.length ) {
            element = this.document[ 0 ].body;
        }

        return element;
    },

    _toggleAttr: function() {
        this.button
            .toggleClass( "ui-corner-top", this.isOpen )
            .toggleClass( "ui-corner-all", !this.isOpen )
            .attr( "aria-expanded", this.isOpen );
        this.menuWrap.toggleClass( "ui-selectmenu-open", this.isOpen );
        this.menu.attr( "aria-hidden", !this.isOpen );
    },

    _resizeButton: function() {
        var width = this.options.width;

        if ( !width ) {
            width = this.element.show().outerWidth();
            this.element.hide();
        }

        this.button.outerWidth( width );
    },

    _resizeMenu: function() {
        this.menu.outerWidth( Math.max(
            this.button.outerWidth(),

            // support: IE10
            // IE10 wraps long text (possibly a rounding bug)
            // so we add 1px to avoid the wrapping
            this.menu.width( "" ).outerWidth() + 1
        ) );
    },

    _getCreateOptions: function() {
        return { disabled: this.element.prop( "disabled" ) };
    },

    _parseOptions: function( options ) {
        var data = [];
        options.each(function( index, item ) {
            var option = $( item ),
                optgroup = option.parent( "optgroup" );
            data.push({
                element: option,
                index: index,
                value: option.attr( "value" ),
                label: option.text(),
                optgroup: optgroup.attr( "label" ) || "",
                disabled: optgroup.prop( "disabled" ) || option.prop( "disabled" )
            });
        });
        this.items = data;
    },

    _destroy: function() {
        this.menuWrap.remove();
        this.button.remove();
        this.element.show();
        this.element.removeUniqueId();
        this.label.attr( "for", this.ids.element );
    }
});



})

);
