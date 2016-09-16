SyntaxHighlighter.all();
jQuery(document).ready(function() {
	jQuery("select.wp-crm-system-searchable").searchable();
});

// demo functions
function modifySelect() {
	jQuery("select.wp-crm-system-searchable").get(0).selectedIndex = 5;
}

function appendSelectOption(str) {
	jQuery("select.wp-crm-system-searchable").append("<option value=\"" + str + "\">" + str + "</option>");
}

function applyOptions() {
	jQuery("select.wp-crm-system-searchable").searchable({
		maxListSize: 100,
		maxMultiMatch: 50,
		latency: 200,
		exactMatch: false,
		wildcards: true,
		ignoreCase: true,
		warnMultiMatch: 'top {0} matches...',
		warnNoMatch: 'no matches...',
		zIndex: 'auto'
	});
}
