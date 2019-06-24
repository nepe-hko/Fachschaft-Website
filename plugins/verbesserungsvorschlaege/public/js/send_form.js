jQuery(document).ready(function($){

    $("form.vbv_form").submit( function() {
        let data = $(".vbv_form").serialize();
        data = data + "&vbv_secure=" + vbv_ajax_data.vbv_secure;

        $.ajax({
            url: vbv_ajax_data.ajaxurl,
            type: "post",
            data: data,
            success: res => {
                $("form.vbv_form").hide();
                $('#vbv_response').html(res);
            },
            fail: () => $('#vbv_response').html("Fehler beim übermitteln der Daten. Versuche es später nocheinmal.")
        });

        return false; 
    });
    
    $("#vbv_btn_show").click( function() {
        $(".vbv").addClass('show');
        $(this).addClass('hide');
    });
});

