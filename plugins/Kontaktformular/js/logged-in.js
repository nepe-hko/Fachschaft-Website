//logged in Widget

jQuery(document).ready(function ($) {
    $('#brief').click(function () {
        var einblendenForm = document.getElementById('form_logged_in');
        einblendenForm.style.display = 'block';

    });
    $('form.ajax_logged_in').submit(function () {
        if ($('#subject_logged_in').val() == '' || $('#message_logged_in').val() == '') {
            $('#answer_logged_in').html('Bitte alle Felder ausfüllen. Log in');
            return false;
        }
        else {
            var that = $(this),               // that: beinhaltet alle Daten aus aktuellen Objekt wo man sich gerade befindet, also form
                url = that.attr('action'),
                method = that.attr('method'),
                data = {};                         // leeres JavaScript-Objekt das später die Daten enthält, welche dann mit ajax verschickt wird

            that.find('[name]').each(function (index, value) {   // läuft durch alle Elemente die eine Eigenschaft 'name' haben(alle input + textarea)
                var that = $(this),
                    name = that.attr('name'),
                    value = that.val();

                data[name] = value;
            });

            $.ajax({
                url: url,
                type: method,
                data: data,
                success: function (response)    // Variable die Zielantwort von kontaktformular.php enthält
                {
                    if (!(typeof ($('#answer_logged_in')) === 'undefined')) {
                        $('#answer_logged_in').html(response);
                        document.getElementById('form_logged_in').reset();
                    }

                }
            });
            return false;
        }
    });

});













