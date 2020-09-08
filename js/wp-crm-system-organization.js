jQuery(document).ready(function($) {

    $('#publish').click(function(){
        var title = $('[id^=\"titlediv\"]').find('#title');
        var emailAddress = $('#_wpcrm_organization-email').val();
        var phoneNumber = $('#_wpcrm_organization-phone').val();
        var websiteURL = $('#_wpcrm_organization-website').val();
        var handleError = 0;

        if (title.val().length < 1){
            if( $('#required-title-message').length == 0){
                $('#titlewrap').after('<label id="required-title-message">* Organization title is required</label>'); 
                $('#required-title-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#required-title-message" ).remove();
        }

        if( !validateEmail(emailAddress)){
            if( $('#invalid-email-message').length == 0){
                $('#_wpcrm_organization-email').after('<label id="invalid-email-message">* Please enter a valid email address</label>'); 
                $('#invalid-email-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#invalid-email-message" ).remove();
        }

        if( !validatePhone(phoneNumber) && phoneNumber.length > 0){
            if( $('#invalid-phone-message').length == 0){
                $('#_wpcrm_organization-phone').after('<label id="invalid-phone-message">* Please enter a valid phone number</label>'); 
                $('#invalid-phone-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#invalid-phone-message" ).remove();
        }

        if( !validateURL(websiteURL) && websiteURL.length > 0){
            if( $('#invalid-url-message').length == 0){
                $('#_wpcrm_organization-website').after('<label id="invalid-url-message">* Please enter a valid URL adress</label>'); 
                $('#invalid-url-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#invalid-url-message" ).remove();
        }

        if(handleError === 1){
            return false;
        }
    });
});

function validateEmail(email) {
    var filter = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return filter.test( email );
}

function validatePhone(phone) {
    var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
    return filter.test(phone); 
}

function validateURL(url) {
    var filter = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    return filter.test(url); 
}