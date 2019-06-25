jQuery( document ).ready( function( $ ) 
{
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

