<?php

class symbol_widget extends WP_Widget //Widget wird nur dann angezeigt wenn man angemeldet ist
{
	function __construct() {
                // Instantiate the parent object

        parent::__construct( false, 'Kontaktformularsymbol' );   
        add_action('admin_post_send_form', array($this, 'sendMail'));   
        add_action('admin_post_send_form', array($this, 'insertToDataBaseLogin'));

	}

        function widget( $args, $instance ) // Widget output
        {
                if (is_user_logged_in())
                {
                        echo '<p id="brief" style="font-size: 100%; vertical-align: top; text-align: left; margin: 2px 2px;"><b>&#9993; Schreibe eine Nachricht an uns!</b></p>';
                        ?>
                        <form id='form_logged_in' action="<?php echo esc_url(admin_url('admin-post.php'));?>"
                        method='POST' class='ajax_logged_in' >  
                                <input type="text" name="subject_logged_in" id="subject_logged_in" placeholder="Betreff*"/>			
                                <textarea id="message_logged_in" name="message_logged_in" placeholder="Deine Nachricht...*"></textarea>
                                <div id="answer_logged_in"></div>
                                <button type="submit" id="submit_logged_in">Absenden!</button>
                                <input name='action' type='hidden' value='send_form'/>
                        </form>                   
                        <?php           
                }
        }
        function insertToDataBaseLogin()
        {
                global $wpdb;
                $user = wp_get_current_user();


			$table = $wpdb->prefix . 'contactform'; 
			$data = array(
				'contactform_name' => $user->user_login,
				'contactform_email_address' => $user->user_email,
				'contactform_subject' => $_POST['subject_logged_in'],		
				'contactform_message' => $_POST['message_logged_in']
			);
			$format = array(
			'%s', // string-Wert
			'%s',
			'%s',
			'%s'
			);

			$sucessful = $wpdb->insert($table, $data, $format); //function escapes data automatically

			$id = $wpdb->insert_id;

			if($id == false && $sucessful == false)
			{
				"Email konnte nicht in Datenbank gespeichert werden";					}
                        }
        }
        function sendMail()
        {
                // Name und Email aus worpress-Admin in Variable speichern

                if(isset($_POST['subject_logged_in'], $_POST['message_logged_in']))
                {
                        $user = wp_get_current_user();

                        $subject = $_POST['subject_logged_in'];
                        $message = $_POST['message_logged_in'];

                        $mailTo = "Vero@localhost";
                        $headers = 'From: ' . $user->user_email;
                        $body = "Du hast eine Nachricht von " . $user->user_login . " erhalten" . "\n\n" . $message;

                        wp_mail($mailTo, $subject, $body, $headers);  
                        echo "Vielen Dank für deine Nachricht!";
                }
        }

	function update( $new_instance, $old_instance ) { 
                // Save widget options
                // wenn man im Backend was in der Form einträgt und speichert ist hier die funktion zuständig
	}

	function form( $instance ) {
        // Hier kann man was programmieren das dann im Backend vom Widget in der Funktion Kontaktformular
        // (nachdem man es zb in den footer gezogen hat erscheint        
        echo '<h4>Dies verlinkt auf die Kontaktformular-Seite</h4>';
	}
}

function kontaktformular_symbol_widget() {
	register_widget( 'symbol_widget' );
}



//hook in funktion
add_action( 'widgets_init', 'kontaktformular_symbol_widget' );



?>


