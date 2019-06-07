//Validierung
jQuery(document).ready(function ($) {    //logged out
    $('form.ajax_logged_out').submit(function () {
        if ($('#name').val() == '' || $('#mail').val() == '' || $('#subject').val() == '' || $('#message').val() == '') {
            $('#answer').html('Bitte alle Felder ausfüllen. Log out');
            return false;                 // blockiert Action-Methode
        }
        else 
        {
           
        }
    });

});




