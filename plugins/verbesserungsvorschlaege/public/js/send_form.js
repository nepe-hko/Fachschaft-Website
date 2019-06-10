jQuery(document).ready(function($){

    $("#submit").click( function(e) { 
        
        let container = $('#vbv_container');
        let title = $('#vbv_title');
        let content =$('#vbv_content');
        let response = $('#vbv_response');
        
        $.post({
            url: obj.ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
            data: {
                'action': 'request',
                'title' : title.val(),
                'content' : content.val(),
            },
            success: res => {
                container.hide();
                response.html(res)
            }
        });
        return false  
	});
});

