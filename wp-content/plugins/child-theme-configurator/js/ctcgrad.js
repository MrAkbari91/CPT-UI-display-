/*!
 * CTC Gradient Functions
 * Adapted from Iris
 * Copyright (c) 2012–2014 Automattic.
 * Licensed under the GPLv2 license. 
 */   
(function( $, undef ){
    var _html, nonGradientIE, gradientType, vendorPrefixes, _css, Iris, UA, isIE, IEVersion;
    UA = navigator.userAgent.toLowerCase();
    isIE = navigator.appName === 'Microsoft Internet Explorer';
    IEVersion = isIE ? parseFloat( UA.match( /msie ([0-9]{1,}[\.0-9]{0,})/ )[1] ) : 0;
    nonGradientIE = ( isIE && IEVersion < 10 );
    // we don't bother with an unprefixed version, as it has a different syntax
    vendorPrefixes = ['-moz-', '-webkit-', '-o-', '-ms-' ];
    testGradientType();

    // Bail for IE <= 7
    if ( nonGradientIE && IEVersion <= 7 ) {
        $.fn.ctcgrad = $.noop;
        $.support.ctcgrad = false;
        return;
    }

    $.support.ctcgrad = true;

    function testGradientType() {
        var el, base;
        if ( nonGradientIE ) {
            gradientType = 'filter';
        }
        else {
            el = $('<div id="ctcgrad-gradtest" />');
            base = "linear-gradient(top,#fff,#000)";
            $.each( vendorPrefixes, function( i, val ){
                el.css( 'backgroundImage', val + base );
                if ( el.css( 'backgroundImage').match('gradient') ) {
                    gradientType = i;
                    return false;
                }
            });
            // check for legacy webkit gradient syntax
            if ( gradientType === false ) {
                el.css( 'background', '-webkit-gradient(linear,0% 0%,0% 100%,from(#fff),to(#000))' );
                if ( el.css( 'backgroundImage').match('gradient') )
                    gradientType = 'webkit';
            }
            el.remove();
        }

    }

    /**
     * Only for CSS3 gradients. oldIE will use a separate function.
     *
     * Accepts as many color stops as necessary from 2nd arg on, or 2nd
     * arg can be an array of color stops
     *
     * @param  {string} origin Gradient origin - top bottom left right (n)deg
     * @return {string}        Appropriate CSS3 gradient string for use in
     */
    function createGradient( origin, stops ) {
        origin = ( origin.match(/(\d+deg|top|left|bottom|right)( (top|left|bottom|right))?/) ? origin : 'top');
        stops = $.isArray( stops ) ? stops : Array.prototype.slice.call(arguments, 1);
        if ( gradientType === 'webkit' )
            return legacyWebkitGradient( origin, stops );
        else
            return vendorPrefixes[gradientType] + 'linear-gradient(' + origin + ', ' + stops.join(', ') + ')';
    }

    /**
     * Stupid gradients for a stupid browser.
     */
    function stupidIEGradient( origin, stops ) {
        var type, self, lastIndex, filter, startPosProp, endPosProp, dimensionProp, template, html, filterVal;

        origin = ( origin === 'left' ) ? 'left' : 'top';
        stops = $.isArray( stops ) ? stops : Array.prototype.slice.call(arguments, 1);
        // 8 hex: AARRGGBB
        // GradientType: 0 vertical, 1 horizontal
        type = ( origin === 'top' ) ? 0 : 1;
        self = $( this );
        lastIndex = stops.length - 1;
        filter = 'filter';
        startPosProp = ( type === 1 ) ? 'left' : 'top';
        endPosProp = ( type === 1 ) ? 'right' : 'bottom';
        dimensionProp = ( type === 1 ) ? 'height' : 'width';
        // need a positioning context
        if ( self.css('position') === 'static' )
            self.css( {position: 'relative' } );

        stops = fillColorStops( stops );
        $.each(stops, function( i, startColor ) {
            var endColor, endStop;

            // we want two at a time. if we're on the last pair, bail.
            if ( i === lastIndex )
                return false;

            endColor = stops[ i + 1 ];
            //if our pairs are at the same color stop, moving along.
            if ( startColor.stop === endColor.stop )
                return;

            endStop = 100 - parseFloat( endColor.stop ) + '%';
            startColor.octoHex = new Color( startColor.color ).toIEOctoHex();
            endColor.octoHex = new Color( endColor.color ).toIEOctoHex();

            filterVal = "progid:DXImageTransform.Microsoft.Gradient(GradientType=" + type + ", StartColorStr='" + startColor.octoHex + "', EndColorStr='" + endColor.octoHex + "')";
        });
        return filterVal;
    }

    function legacyWebkitGradient( origin, colorList ) {
        var stops = [];
        origin = ( origin === 'top' ) ? '0% 0%,0% 100%,' : '0% 100%,100% 100%,';
        colorList = fillColorStops( colorList );
        $.each( colorList, function( i, val ){
            stops.push( 'color-stop(' + ( parseFloat( val.stop ) / 100 ) + ', ' + val.color + ')' );
        });
        return '-webkit-gradient(linear,' + origin + stops.join(',') + ')';
    }

    function fillColorStops( colorList ) {
        var colors = [],
            percs = [],
            newColorList = [],
            lastIndex = colorList.length - 1;

        $.each( colorList, function( index, val ) {
            var color = val,
                perc = false,
                match = val.match(/1?[0-9]{1,2}%$/);

            if ( match ) {
                color = val.replace(/\s?1?[0-9]{1,2}%$/, '');
                perc = match.shift();
            }
            colors.push( color );
            percs.push( perc );
        });

        // back fill first and last
        if ( percs[0] === false )
            percs[0] = '0%';

        if ( percs[lastIndex] === false )
            percs[lastIndex] = '100%';

        percs = backFillColorStops( percs );

        $.each( percs, function( i ){
            newColorList[i] = { color: colors[i], stop: percs[i] };
        });
        return newColorList;
    }

    function backFillColorStops( stops ) {
        var first = 0,
            last = stops.length - 1,
            i = 0,
            foundFirst = false,
            incr,
            steps,
            step,
            firstVal;

        if ( stops.length <= 2 || $.inArray( false, stops ) < 0 ) {
            return stops;
        }
        while ( i < stops.length - 1 ) {
            if ( ! foundFirst && stops[i] === false ) {
                first = i - 1;
                foundFirst = true;
            } else if ( foundFirst && stops[i] !== false ) {
                last = i;
                i = stops.length;
            }
            i++;
        }
        steps = last - first;
        firstVal = parseInt( stops[first].replace('%'), 10 );
        incr = ( parseFloat( stops[last].replace('%') ) - firstVal ) / steps;
        i = first + 1;
        step = 1;
        while ( i < last ) {
            stops[i] = ( firstVal + ( step * incr ) ) + '%';
            step++;
            i++;
        }
        return backFillColorStops( stops );
    }

    $.fn.ctcgrad = function( origin ) {
        var args = arguments;
            // this'll be oldishIE
            if ( nonGradientIE )
                $(this).css('filter', 
                stupidIEGradient.apply( this, args ));
            else // new hotness
                $( this ).css( 'backgroundImage', createGradient.apply( this, args ) );
    };

}( jQuery ));