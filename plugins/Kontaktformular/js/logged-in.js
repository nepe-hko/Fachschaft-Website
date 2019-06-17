// //logged in Widget

 jQuery(document).ready(function ($) {
    var formOptions = true;
    $('#brief').click(function () {
        if(formOptions)                                                 // bei Klick auf Briefüberschrift wird unsichtbare Form geöffnet
        {
            $('#form_logged_in').slideDown('slow');
            formOptions = false;
        }
        else                                                            // sichtbare Form wird geschlossen
        {
            $('#form_logged_in').slideUp(1);                            // 1 ms läuft die Animation
            formOptions = true;
            document.getElementById('form_logged_in').reset();          // setzt Forminput zurück
            $('#answer_logged_in').text(' ');                           // setzt Nachricht aus Antwortbox zurück (falls vorhanden)
            
        }
    });

    $('form.ajax_logged_in').submit(function (e) {
        if ($('#subject_logged_in').val() == '' || $('#message_logged_in').val() == '') {
            $('#answer_logged_in').text('Bitte alle Felder ausfüllen.');
            e.preventDefault();                                          // verhindert Wechsel zur admin-post.php 
        }
        else 
        {
            e.preventDefault();         
            
            var form_data_login = $('#form_logged_in').serialize();
            var login_nonce = kf_ajax_data_login.ajax_nonce_login;      // nonce Sicherheit 
            form_data_login = form_data_login + '&ajaxformlogin=true&submit=Form+Send&secure=' + login_nonce;
    
            $.ajax({
                url: kf_ajax_data_login.ajaxurl,
                type: 'post',
                data: form_data_login                                   // alle Input daten
            })
            .done( function () { 
                $('#answer_logged_in').text('Nachricht wurde versendet.');
            })
            .fail( function() {
                $('#answer_logged_in').text('Es ist ein Fehler unterlaufen');
            })
            .always( function(){  
                e.target.reset();                                       // löscht Forminput
            });
        
        }
   });

});













