jQuery(document).ready(function($) {

    $('#publish').click(function(){
        var title = $('[id^=\"titlediv\"]').find('#title');
        var budgetCost = $('#_wpcrm_campaign-budgetcost').val();
        var actualCost = $('#_wpcrm_campaign-actualcost').val();        
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

        if( !validateNumeric(budgetCost)){
            if( $('#invalid-budget-message').length == 0){
                $('#_wpcrm_campaign-budgetcost').after('<label id="invalid-budget-message">* Please enter a Budget Cost value</label>'); 
                $('#invalid-budget-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#invalid-budget-message" ).remove();
        }


        if( !validateNumeric(actualCost)){
            if( $('#invalid-actual-message').length == 0){
                $('#_wpcrm_campaign-actualcost').after('<label id="invalid-actual-message">* Please enter a Actual Cost value</label>'); 
                $('#invalid-actual-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#invalid-actual-message" ).remove();
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