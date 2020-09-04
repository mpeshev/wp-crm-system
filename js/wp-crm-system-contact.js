jQuery(document).ready(function($) {

    $('#publish').click(function(){
        var firstName = $('#_wpcrm_contact-first-name');
        var lastName = $('#_wpcrm_contact-last-name');
        var emailAddress = $('#_wpcrm_contact-email').val();
        var phoneNumber = $('#_wpcrm_contact-phone').val();
        var mobileNumber = $('#_wpcrm_contact-mobile-phone').val();
        var websiteURL = $('#_wpcrm_contact-website').val();
        var handleError = 0;

        if (firstName.val().length < 1){
            if( $('#required-name-message').length == 0){
                $('.contact-first-name').after('<label id="required-name-message">* Contact first name is required</label>'); 
                $('#required-name-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#required-name-message" ).remove();
        }

        if (lastName.val().length < 1){
            if( $('#required-last-name-message').length == 0){
                $('.contact-last-name').after('<label id="required-last-name-message">* Contact last name is required</label>'); 
                $('#required-last-name-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#required-last-name-message" ).remove();
        }

        if( !validateEmail(emailAddress)){
            if( $('#invalid-email-message').length == 0){
                $('#_wpcrm_contact-email').after('<label id="invalid-email-message">* Please enter a valid email address</label>'); 
                $('#invalid-email-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#invalid-email-message" ).remove();
        }

        if( !validatePhone(phoneNumber) && phoneNumber.length > 0){
            if( $('#invalid-phone-message').length == 0){
                $('#_wpcrm_contact-phone').after('<label id="invalid-phone-message">* Please enter a valid phone number</label>'); 
                $('#invalid-phone-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#invalid-phone-message" ).remove();
        }

        if( !validatePhone(mobileNumber) && mobileNumber.length > 0){
            if( $('#invalid-mobile-message').length == 0){
                $('#_wpcrm_contact-mobile-phone').after('<label id="invalid-mobile-message">* Please enter a valid mobile number</label>'); 
                $('#invalid-mobile-message').css('color', 'red');
            }
            handleError = 1;
        }else{
            $( "#invalid-mobile-message" ).remove();
        }

        if( !validateURL(websiteURL) && websiteURL.length > 0){
            if( $('#invalid-url-message').length == 0){
                $('#_wpcrm_contact-website').after('<label id="invalid-url-message">* Please enter a valid URL adress</label>'); 
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