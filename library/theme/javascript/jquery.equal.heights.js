/*-------------------------------------------------------------------- 
 * JQuery Plugin: "EqualHeights" & "EqualWidths"
 * by:	Scott Jehl, Todd Parker, Maggie Costello Wachs (http://www.filamentgroup.com)
 *
 * Copyright (c) 2007 Filament Group
 * Licensed under GPL (http://www.opensource.org/licenses/gpl-license.php)
 *
 * Description: Compares the heights or widths of the top-level children of a provided element 
 		and sets their min-height to the tallest height (or width to widest width). Sets in em units 
 		by default if pxToEm() method is available.
 * Dependencies: jQuery library, pxToEm method	(article: http://www.filamentgroup.com/lab/retaining_scalable_interfaces_with_pixel_to_em_conversion/)							  
 * Usage Example: jQuery(element).equalHeights();
 * Optional: to set min-height in px, pass a true argument: jQuery(element).equalHeights(true);
 * Version: 2.0, 07.24.2008
 * Changelog:
 *  08.02.2007 initial Version 1.0
 *  07.24.2008 v 2.0 - added support for widths
--------------------------------------------------------------------*/

jQuery.fn.equalHeights = function(px) {
    jQuery(this).each(function(){
        var currentTallest = 0;
        jQuery(this).children().each(function(i){
                if (jQuery(this).height() > currentTallest) { currentTallest = jQuery(this).height(); }
        });
        if (!px || !Number.prototype.pxToEm) currentTallest = currentTallest.pxToEm(); //use ems unless px is specified
        // for ie6, set height since min-height isn't supported
        if (jQuery.browser.msie && jQuery.browser.version == 6.0) { jQuery(this).children().css({'height': currentTallest}); }
        jQuery(this).children().css({'min-height': currentTallest}); 
    });
    return this;
};

/*
// just in case you need it...
jQuery.fn.equalWidths = function(px) {
	jQuery(this).each(function(){
		var currentWidest = 0;
		jQuery(this).children().each(function(i){
                    if(jQuery(this).width() > currentWidest) { currentWidest = jQuery(this).width(); }
		});
		if(!px || !Number.prototype.pxToEm) currentWidest = currentWidest.pxToEm(); //use ems unless px is specified
		// for ie6, set width since min-width isn't supported
		if (jQuery.browser.msie && jQuery.browser.version == 6.0) { jQuery(this).children().css({'width': currentWidest}); }
		jQuery(this).children().css({'min-width': currentWidest}); 
	});
	return this;
};
*/

/*!
* equalWidths jQuery Plugin
* Examples and documentation at: hhttp://aloestudios.com/tools/jquery/equalwidths/
* Copyright (c) 2010 Andy Ford
* Version: 0.1 (2010-04-13)
* Dual licensed under the MIT and GPL licenses:
* http://www.opensource.org/licenses/mit-license.php
* http://www.gnu.org/licenses/gpl.html
* Requires: jQuery v1.2.6+
*/
(function(jQuery){
    jQuery.fn.equalWidths = function(options) {
        /*
        var opts = jQuery.extend({
                stripPadding: 'none' // options: 'child', 'grand-child', 'both'
        },options);
        */
        return this.each(function(){
            var child_count = jQuery(this).children().size();
            //console.log('child count: '+child_count);
            if (child_count > 0) { // only proceed if we've found any children

                // get parent width ##
                var w_parent = jQuery(this).width();
                //console.log('parent width: '+w_parent);

                // get element padding, margin & border before applying new width ##
                var c_outerwidth = jQuery(this).children().outerWidth(true);
                //console.log('child outer width: '+c_outerwidth);

                // get child width ##
                var w_child = Math.floor(w_parent / child_count);
                //console.log('child width: '+w_child);

                // get last child width ##
                var w_child_last = w_parent - ( w_child * (child_count -1) );
                //console.log('last child width: '+w_child_last);
                jQuery(this).children().css({ 'width' : (w_child-c_outerwidth) + 'px' });
                jQuery(this).children(':last-child').css({ 'width' : (w_child_last-c_outerwidth) + 'px' });
                /*
                if((opts.stripPadding == 'child') || (opts.stripPadding == 'both')){
                   jQuery(this).children().css({
                      'padding-right': '0',
                      'padding-left': '0'
                   });
                }
                if((opts.stripPadding == 'grand-child') || (opts.stripPadding == 'both')){
                   jQuery(this).children().children().css({
                      'padding-right': '0',
                      'padding-left': '0'
                   });
                }
                */
             }
        });
    };
})(jQuery);


/*-------------------------------------------------------------------- 
 * javascript method: "pxToEm"
 * by:
   Scott Jehl (scott@filamentgroup.com) 
   Maggie Wachs (maggie@filamentgroup.com)
   http://www.filamentgroup.com
 *
 * Copyright (c) 2008 Filament Group
 * Dual licensed under the MIT (filamentgroup.com/examples/mit-license.txt) and GPL (filamentgroup.com/examples/gpl-license.txt) licenses.
 *
 * Description: Extends the native Number and String objects with pxToEm method. pxToEm converts a pixel value to ems depending on inherited font size.  
 * Article: http://www.filamentgroup.com/lab/retaining_scalable_interfaces_with_pixel_to_em_conversion/
 * Demo: http://www.filamentgroup.com/examples/pxToEm/	 	
 *							
 * Options:  	 								
 		scope: string or jQuery selector for font-size scoping
 		reverse: Boolean, true reverses the conversion to em-px
 * Dependencies: jQuery library						  
 * Usage Example: myPixelValue.pxToEm(); or myPixelValue.pxToEm({'scope':'#navigation', reverse: true});
 *
 * Version: 2.0, 08.01.2008 
 * Changelog:
 *		08.02.2007 initial Version 1.0
 *		08.01.2008 - fixed font-size calculation for IE
--------------------------------------------------------------------*/

Number.prototype.pxToEm = String.prototype.pxToEm = function(settings){
	//set defaults
	settings = jQuery.extend({
		scope: 'body',
		reverse: false
	}, settings);
	
	var pxVal = (this == '') ? 0 : parseFloat(this);
	var scopeVal;
	var getWindowWidth = function(){
		var de = document.documentElement;
		return self.innerWidth || (de && de.clientWidth) || document.body.clientWidth;
	};	
	
	/* When a percentage-based font-size is set on the body, IE returns that percent of the window width as the font-size. 
		For example, if the body font-size is 62.5% and the window width is 1000px, IE will return 625px as the font-size. 	
		When this happens, we calculate the correct body font-size (%) and multiply it by 16 (the standard browser font size) 
		to get an accurate em value. */
				
	if (settings.scope == 'body' && jQuery.browser.msie && (parseFloat(jQuery('body').css('font-size')) / getWindowWidth()).toFixed(1) > 0.0) {
		var calcFontSize = function(){		
			return (parseFloat(jQuery('body').css('font-size'))/getWindowWidth()).toFixed(3) * 16;
		};
		scopeVal = calcFontSize();
	}
	else { scopeVal = parseFloat(jQuery(settings.scope).css("font-size")); };
			
	var result = (settings.reverse == true) ? (pxVal * scopeVal).toFixed(2) + 'px' : (pxVal / scopeVal).toFixed(2) + 'em';
	return result;
};