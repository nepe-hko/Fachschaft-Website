jQuery( document ).ready( function( $ ) 
{
    $( 'form.reg_ajax_class' ).submit(function(e) 
    {
        e.preventDefault(); //default action of the event will not be triggered.
      
        var data_res = $('#reg_ajax_id').serialize();

        $.ajax({ //form gets send to admin-ajax.php
            url: reg_ajax_data.ajaxurl,
            type: 'post',
            data: data_res,
            success: function(response)
            {
                e.target.reset();   //restores a form element's default values.
                $(".msg").html(response);  //sets the content of the selected elements.
            }
        });
        return false;  
    });

    //Password Strength Meter
    var password = document.getElementById('passwort'); 
    var meter = document.getElementById('password-strength-meter');
    $('#passwort').keyup(function(event) //keyup - key is released
    {
        var val = password.value;
        var result = zxcvbn(val);
    
        // Updates the password strength meter
        meter.value = result.score;
    });
});
