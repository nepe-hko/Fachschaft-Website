jQuery(document).ready(function ($) {    

    $('form.ajax_logged_out').submit(function (e) {
        if ($('#name').val() == '' || $('#mail').val() == '' || $('#subject').val() == '' || $('#message').val() == '')         // wenn Eingabefeldern leer sind
        {
            $('#answer').html(kf_ajax_data_logout.answer_all_fields_filled);
            e.preventDefault();                                                                                                 // verhindert Wechsel zur admin-post.php                                             
        }
        else                                                                            
        {
            e.preventDefault();         

            var form_data = $('#form_logged_out').serialize();
            var logout_nonce = kf_ajax_data_logout.ajax_nonce;                                                                  // nonce Sicherheit 
            form_data = form_data + '&ajaxform=true&submit=Form+Send&security=' + logout_nonce ;
    
            $.ajax({
                url: kf_ajax_data_logout.ajaxurl,                                                                               // Link zur ajax-post.php
                type: 'post',
                data: form_data                                                                                                 // alle Input Daten
            })
            .done( function () {
                $('#answer').html(kf_ajax_data_logout.ajax_success_message);
            })
            .fail( function() {
                $('#answer').html(kf_ajax_data_logout.ajax_fail_message);
            })
            .always( function(){
                e.target.reset();                                                                                               // setzt Forminput und Antwortnachricht zurück                                               
            });
        }
    });

});