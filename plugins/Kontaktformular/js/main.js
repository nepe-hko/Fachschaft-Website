//Validierung
jQuery(document).ready(function($){    
    $("form.ajax").submit(function(e)
    {
	    if($("#name").val() == "" || $("#mail").val() == "" || $("#subject").val() == "" || $("#message").val() == "")
	    {
            $("#answer").html("Bitte alle Felder ausfüllen.");
            return false;                 // blockiert Action-Methode
        }
        else
        {
            var that = $(this),               // that: beinhaltet alle Daten aus aktuellen Objekt wo man sich gerade befindet, also form
            url = that.attr('action'),
            method = that.attr('method'),
            data = {};                         // leeres JavaScript-Objekt das später die Daten enthält, welche dann mit ajax verschickt wird

                that.find('[name]').each(function(index, value) {   // läuft durch alle Elemente die eine Eigenschaft "name" haben(alle input + textarea)
                    var that = $(this),
                    name = that.attr('name'),
                    value = that.val();

                    data[name] = value;
                });

    
                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function(response)    // Variable die Zielantwort von kontaktformular.php enthält
                    {
                        $('#answer').html(response);
                        document.getElementById('formId').reset(); // leert die input-felder und den Textbereich nach drücken des Buttons aus
                    }
                });
                return false; 
        }              
     });
	 return false;
});



