jQuery(document).ready(function() {
	jQuery("select.wp-crm-system-searchable").chosen({
		disable_search_threshold: 1,
		allow_single_deselect: true,
		disable_search: false,
		no_results_text: "Oops, nothing found!",
		width: "95%"
	})
});