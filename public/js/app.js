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

    /*
    // ToDo: implement 'already logged in' message to logged in user trying to log in again, and delay page redirect.
     */

    /*
    Prevent uploading of No Image.
     */
    $('#upload-image').click( function ( submission ) {

        if ( $('#profile-image-file').val() === '') {

            alert("Please Attach a file before submitting!");
            submission.preventDefault();
        }
    });

    /*
    ToDo: implement code to only show upload button when file is selected
     */
});