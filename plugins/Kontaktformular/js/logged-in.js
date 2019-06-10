// //logged in Widget

 jQuery(document).ready(function ($) {
    $('#brief').click(function () {
        $('#form_logged_in').slideDown('slow');
    });

    $('form.ajax_logged_in').submit(function (e) {
        if ($('#subject_logged_in').val() == '' || $('#message_logged_in').val() == '') {
            $('#answer_logged_in').html('Bitte alle Felder ausfüllen. Log in');
            return false;
        }
        else 
        {
            e.preventDefault();         
            
            var form_data_login = $('#form_logged_in').serialize();
            var login_nonce = kf_ajax_data_login.ajax_nonce_login;
            form_data_login = form_data_login + '&ajaxformlogin=true&submit=Form+Send&secure=' + login_nonce;
    
            $.ajax({
                url: kf_ajax_data_login.ajaxurl,
                type: 'post',
                data: form_data_login
            })
            .done( function () { //wenn alles geklappt hat
                $('#answer_logged_in').text('Nachricht wurde versendet.');
            })
            .fail( function() {
                $('#answer_logged_in').text('Es ist ein Fehler unterlaufen');
            })
            .always( function(){
                e.target.reset();
            });
        
        }
   });

});













