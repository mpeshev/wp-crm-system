jQuery(document).ready(function($) {
	$('#dashboard-opportunity-list').change(function() {
		$('#opportunity_list_loading').show();
		
		data = {
			action: 'opportunity_list_response',
			opportunity_list_nonce: opportunity_list_vars.opportunity_list_nonce,
			opportunity_id: $('#dashboard-opportunity-list').val()
		};

		$.post(ajaxurl, data, function (response) {
			$('#opportunity_list_results').html(response);
			$('#opportunity_list_loading').hide();
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
		
		return false;
	});
});