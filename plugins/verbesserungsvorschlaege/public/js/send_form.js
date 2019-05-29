jQuery(document).ready(function($){

    $("form.improvement").submit( function() {
                           
        let url = $(this).attr('action');
        let data = {};                                       

        $(this).find('[name]').each( function() {
            let name = $(this).attr('name');
            data[name] = $(this).val();
        });

        $.post({
            url: url,
            data: data,
            success: res => {
                $("form.improvement").hide();
                $('#res').html(res);
            },
            fail: () => $('#res').html("Fehler beim übermitteln der Daten. Versuche es später nocheinmal.")
        });

        return false; 
	});
});

