jQuery(document).ready(function($) {
	$('#dashboard-project-list').change(function() {
		$('#project_list_loading').show();
		
		data = {
			action: 'project_list_response',
			project_list_nonce: project_list_vars.project_list_nonce,
			project_id: $('#dashboard-project-list').val()
		};

		$.post(ajaxurl, data, function (response) {
			$('#project_list_results').html(response);
			$('#project_list_loading').hide();
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