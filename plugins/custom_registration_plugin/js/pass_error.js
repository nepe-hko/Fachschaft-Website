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

        // Update the text indicator
        if (val !== "") 
        {
            text.innerHTML = "Strength: " + strength[result.score]; 
        }  
        else 
        {
            text.innerHTML = "";
        }
    });
    
    $( 'form.test' ).bind('submit', function() 
    {
        if(passwort.value != pass_again.value)
        {

          alert("Passwörter müssen die selbe länge haben");
          return false;
        }
        else
        {
          alert("Sie haben sich erfolgreich registriert!");
          return true;
        }
      
    });
});

