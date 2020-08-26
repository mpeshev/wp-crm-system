jQuery(document).ready(function ($) {

	// Tooltips
	$('.wpcrm-system-help-tip').tooltip({
		content: function() {
			return $(this).prop('title');
		},
		tooltipClass: 'wpcrm-system-ui-tooltip',
		position: {
			my: 'center top',
			at: 'center bottom+10',
			collision: 'flipfit',
		},
		hide: {
			duration: 200,
		},
		show: {
			duration: 200,
		},
	});
});