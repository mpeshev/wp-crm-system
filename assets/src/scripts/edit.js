function editField(field) {
  	var input = document.getElementById(field + "-input");
	var text = document.getElementById(field + "-text");
	var label = document.getElementById(field + "-label");
	if (document.getElementById(field + "-comment") !== null){
	  var comment = document.getElementById(field + "-comment");
	}
	
	if(input.style.display == 'none') {
		input.style.display = 'inline';
		text.style.display = 'none';
	    if (document.getElementById(field + "-comment") !== null){
	      comment.style.display = 'inline';
	    }
	} else {
		input.style.display = 'none';
		text.style.display = 'inline';
	    if (document.getElementById(field + "-comment") !== null){
	      comment.style.display = 'none';
	    }
	}
	
	if (label.style.display == 'none') {
		label.style.display = 'inline';
	} else {
		label.style.display = 'none';
	}
};
function showEdit(field) {
	var edit = document.getElementById(field + "-edit");
	if (edit.style.display == 'none') {
		edit.style.display = 'inline';
	} else {
		edit.style.display = 'none';
	}
}
function hideEdit(field) {
	var edit = document.getElementById(field + "-edit");
	if (edit.style.display == 'inline') {
		edit.style.display = 'none';
	} else {
		edit.style.display = 'inline';
	}
}
