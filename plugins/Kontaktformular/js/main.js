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

            var input_name = $('input:eq(0)').val(),
                input_mail = $('input:eq(1)').val(),
                input_subject = $('input:eq(2)').val(),
                input_message = $('textarea').val();

            $.post('send.php', {name: input_name, email: input_mail, subject: input_subject, message: input_message}, function(data)
            {
                $('#answer').text('Danke für deine Nachricht');

            });
            
            
            
            
            
            
            
            
            
            
            
            
            /*	
            var that = $(this),                               // that: beinhaltet alle Daten aus aktuellen Objekt wo man sich gerade befindet, also form
            url = that.attr('action'),
            method = that.attr('method'),
            data = {};                                       // leeres JavaScript-Objekt das später die Daten enthält, welche dann mit ajax verschickt wird

                that.find('[name]').each(function(index, value) {          // läuft durch alle Elemente die eine Eigenschaft "name" haben(alle input + textarea)
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
                    }
				});
				return false; */
				 
        }           
	 });
	// return false;
});


