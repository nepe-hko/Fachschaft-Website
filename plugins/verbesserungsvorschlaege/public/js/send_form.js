jQuery(document).ready(function($){

    $("#submit").click( function(e) {
        /*                   
        let data = {};                                       

        $(this).find('[name]').each( function() {
            let name = $(this).attr('name');
            data[name] = $(this).val();
        });
        */
        e.preventDefault();

        $.ajax({
            type : 'post',
            dataType : 'json',
            url: jsforwp_globals.ajax_url,
            data: {
                action: 'jsforwp_add_like',
                _ajax_nonce : jsforwp_globals.nonce
            },
            success: function(res) {
                if('success == res.type') {
                    alert('okay');
                } else {
                    alert('something went wrong');
                }
            }
        });
        return false;
        /*
        $.post({
            url: url,
            data: data,
            success: res => {
                $("form.improvement").hide();
                $('#res').html(res);
            },
            fail: () => $('#res').html("Fehler beim übermitteln der Daten. Versuche es später nocheinmal.")
        });
        */

	});
});

