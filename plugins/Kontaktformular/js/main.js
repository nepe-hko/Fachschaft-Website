//Validierung


jQuery(document).ready(function ($) {    //logged out
    $('form.ajax_logged_out').submit(function (e) {
   /*     if ($('#name').val() == '' || $('#mail').val() == '' || $('#subject').val() == '' || $('#message').val() == '') {
            $('#answer').html('Bitte kalle Felder ausfüllen. Log out alde');
            e.preventDefault();            // blockiert Action-Methode
        }
        else 
    //     {*/
            e.preventDefault();         
            
            var form_data = $('#form_logged_out').serialize();
            form_data = form_data + '&ajaxform=true&submit=Form+Send';

            
            $.ajax({
                url: kf_ajax_data.ajaxurl,
                type: 'post',
                data: form_data
            })
            .done( function ( response ) { //wenn alles geklappt hat
                $('#answer').text('Nachricht wurde versendet.');
            })
            .fail( function() {
                $('#answer').text('Es ist ein Fehler unterlaufen');
            })
            .always( function(){
                e.target.reset();
            });
    });

});




