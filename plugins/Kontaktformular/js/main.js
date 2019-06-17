//logged out
jQuery(document).ready(function ($) {    
    $('form.ajax_logged_out').submit(function (e) {
        if ($('#name').val() == '' || $('#mail').val() == '' || $('#subject').val() == '' || $('#message').val() == '') {
            $('#answer').html('Bitte alle Felder ausfüllen.');
            e.preventDefault();            // blockiert Action-Methode
        }
        else 
        {
            e.preventDefault();         

            var form_data = $('#form_logged_out').serialize();
            var logout_nonce = kf_ajax_data.ajax_nonce;
            form_data = form_data + '&ajaxform=true&submit=Form+Send&security=' + logout_nonce ;
    
            $.ajax({
                url: kf_ajax_data.ajaxurl,
                type: 'post',
                data: form_data
            })
            .done( function () { //wenn alles geklappt hat
                $('#answer').text('Nachricht wurde versendet.');
            })
            .fail( function() {
                $('#answer').text('Es ist ein Fehler unterlaufen');
            })
            .always( function(){
                e.target.reset();
            });
        }
    });

});




