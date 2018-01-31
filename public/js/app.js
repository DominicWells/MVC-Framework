$( document ).ready(function() {

    /*
    Prevent login form submission if no value has been set in either input field.
     */
    $('#login-form-submission').click( function ( submission ) {

        if ( $('#user').val() === '' || $('#key').val() === '' ) {

            $('#login-form-error').css('display','grid');
            submission.preventDefault();
        }
    });
});