jQuery( document ).ready( function( $ ) 
{
    var password = document.getElementById('passwort'); 
    var meter = document.getElementById('password-strength-meter');

    password.addEventListener('input', function() 
    {
        var val = password.value;
        var result = zxcvbn(val);

        // Update the password strength meter
        meter.value = result.score;
    });

    $( 'form.reg_ajax_class' ).submit(function(e) 
    {
        e.preventDefault();
      
        var data = $('#reg_ajax_id').serialize();
        
        $.ajax({
            url: reg_ajax_data.ajaxurl,
            type: 'post',
            data: data,
            success: function(response)
            {
                e.target.reset();
                $(".msg").html(response);
            }
        });
        return false;  
    });

});

