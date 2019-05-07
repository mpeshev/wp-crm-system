jQuery(document).ready(function($) {
	$('#dashboard-address-book').change(function() {
		$('#address_book_loading').show();

		data = {
			action: 'address_book_response',
			address_book_nonce: address_book_vars.address_book_nonce,
			contact_id: $('#dashboard-address-book').val()
		};

		$.post(ajaxurl, data, function (response) {
			$('#address_book_results').html(response);
			$('#address_book_loading').hide();
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