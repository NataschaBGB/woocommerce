jQuery(document).ready(function ($){

	/* Mobile navigation
	------------------------------------------------------------------ */

	jQuery('#mobileicon, #mobilemenu-overlay').on('click', function () {
		jQuery('body').toggleClass('overflow');
		jQuery('body').toggleClass('open-mobilemenu');
	});

	jQuery('#mobilemenu nav ul li > span').on('click', function () {
		jQuery(this).toggleClass('open');
		jQuery(this).parent().find('.sub-menu').slideToggle(300);
	});

	jQuery('.current-menu-parent span').addClass('open');
});

function is_touch_device() {
	var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
	var mq = function (query) {
		return window.matchMedia(query).matches;
	}

	if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
		return true;
	}

	// include the 'heartz' as a way to have a non matching MQ to help terminate the join
	// https://git.io/vznFH
	var query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
	return mq(query);
}
