jQuery(document).ready(function($) {

    $('#publish').click(function(){
        var title = $('[id^=\"titlediv\"]').find('#title');
        var value = $('#_wpcrm_project-value').val();        
        var handleError = 0;

        if (title.val().length < 1){
            if( $('#required-title-message').length == 0){
                $('#titlewrap').after('<label id="required-title-message">* Campaign title is required</label>'); 
                $('#required-title-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#required-title-message" ).remove();
        }

        if( !validateNumeric(value) && value.length > 0){
            if( $('#invalid-value-message').length == 0){
                $('#_wpcrm_project-value').after('<label id="invalid-value-message">* Please enter a Actual Cost value</label>'); 
                $('#invalid-value-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#invalid-value-message" ).remove();
        }

        if(handleError === 1){
            return false;
        }
    });
});

function validateNumeric(value) {
    var filter = /^[+]?[1-9]\d*$/;
    return filter.test(value); 
}