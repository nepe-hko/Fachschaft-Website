//logged out

jQuery(document).ready(function ($) {    

    $('form.ajax_logged_out').submit(function (e) {
        if ($('#name').val() == '' || $('#mail').val() == '' || $('#subject').val() == '' || $('#message').val() == '') 
        {
            $('#answer').html(kf_ajax_data_logout.answer_all_fields_filled);
            e.preventDefault();                                                                                         // blockiert Action-Methode
        }
        else 
        {
            e.preventDefault();         

            var form_data = $('#form_logged_out').serialize();
            var logout_nonce = kf_ajax_data_logout.ajax_nonce;
            form_data = form_data + '&ajaxform=true&submit=Form+Send&security=' + logout_nonce ;
    
            $.ajax({
                url: kf_ajax_data_logout.ajaxurl,
                type: 'post',
                data: form_data
            })
            .done( function () {
                $('#answer').html(kf_ajax_data_logout.ajax_success_message);
            })
            .fail( function() {
                $('#answer').html(kf_ajax_data_logout.ajax_fail_message);
            })
            .always( function(){
                e.target.reset();
            });
        }
    });

});




