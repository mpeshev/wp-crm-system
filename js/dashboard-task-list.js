jQuery(document).ready(function($) {
	$('#dashboard-task-list').change(function() {
		$('#task_list_loading').show();
		
		data = {
			action: 'task_list_response',
			task_list_nonce: task_list_vars.task_list_nonce,
			task_id: $('#dashboard-task-list').val()
		};

		$.post(ajaxurl, data, function (response) {
			$('#task_list_results').html(response);
			$('#task_list_loading').hide();
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