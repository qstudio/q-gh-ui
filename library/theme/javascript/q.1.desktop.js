/*
* q/theme/1/desktop
*/

// jQuery ##
if ( typeof jQuery !== 'undefined' ) {

	jQuery(document).ready(function($) {

		bbHeight = jQuery('.widget-brand-bar').height();
		if(bbHeight > 37 ) bbHeight = 37;
		//jQuery('.widget-brand-bar').height(), // @benny - why is this figure hardcoded - what about zoomed viewports? - fixed
		footerHeight = jQuery('footer').outerHeight();

		jQuery(window).on('load', function(){ // load
			
			q_equal_heights();

		});

		jQuery(window).on('resize orientationchange', function(){ // load

			htmlHeight = jQuery('html').outerHeight()

			// q_equal_heights();

		});

		jQuery(window).on('load resize orientationchange scroll', function(){

			q_sticky_faq();

		});
	
		// SET NAVIGATION DROP-DOWN ON HOVER - while preserving click functionality for mobile
		jQuery('ul.nav li.dropdown').hover(function() {
			jQuery(this).find('.dropdown-menu').stop(true, true).delay(250).fadeIn('fast');
		}, function() {
			jQuery(this).find('.dropdown-menu').stop(true, true).delay(250).fadeOut('fast');
		});

		// SET NAVIGATION PARENT TO BE ACTIVE WHEN SUBMENU IS ACTIVE
		if( open_sub = jQuery('ul.nav > li.dropdown > ul.dropdown-menu').find('li.active') ){
			
			jQuery( open_sub.parent().parent().first() ).addClass('active');
			
		}
		
		// BEATING HEART HOVER EFFECT
		jQuery('.logo_wrap').hover(function() {
			jQuery('.logo_left').css('animation', '.7s 1 beatHeart ease-in');
		}, function() {
			// on mouseout, reset the background colour
			jQuery('.logo_left').css('animation', 'none');
		});

	});

}

// @benny @todo - these hardcoded figures seem risky in case of zoom or browser differences ## - these are just defaults because you cant declare defaults in IE, they are overwritten later
var 
	htmlHeight = 0,
	bbHeight = 37,
	headerHeight = 80,
	footerHeight = 141,
	max_height = null;

function q_equal_heights() {
	jQuery('.equal-group').each(function(){
		// console.log( 'Doing q_equal_heights...' );
		jQuery(this).addClass('equal-current');
		var numPerRow = jQuery(this).data('equal-cols');
		q_even_elements('.equal-current .equal-item', numPerRow);
		jQuery(this).removeClass('equal-current');
	});
}

// Equal heights function for siblings
// number - number of elements in a row
function q_even_elements(selector, number) {
	jQuery(selector).css('height', 'auto');
	var rows = Math.ceil(jQuery(selector).length/number);
	for (var i = 0; i < rows; i++) {
		var first_element = i * number;
		// var max_height = 0;
		for (var j = 0; j < number; j++) {
			var this_height = jQuery(selector+':eq('+ (first_element + j) + ')').height();
			max_height = Math.max(max_height, this_height);
		}

		// console.log( 'Max Height: '+max_height );

		for (var j = 0; j < number; j++) {
			jQuery(selector+':eq(' + (first_element + j) + ')').height(max_height).addClass("equalized");
		}
	}
	max_height = 0;
}

// FAQ ##
function q_sticky_faq() {
	if (jQuery('body').hasClass('donate')) {
		var faqHeight = window.innerHeight - adminBarHeight() - 80;
		jQuery('.donate-faq').css({
			'height': faqHeight + 'px',
		});

	}
}		

function adminBarHeight() {
	return (jQuery('body').hasClass('admin-bar')) ? jQuery('#wpadminbar').height() : 0;
}