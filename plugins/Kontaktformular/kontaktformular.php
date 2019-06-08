<?php
/*
Plugin Name: kontaktformular
Plugin URI: http://localhost/wordpress/wp-admin/plugins.php
Author: Veronika Sedlackova
Version: 1.0
Description: Plugin das ein Kontaktformular anbietet
*/


//exit if access directly 
if(!defined('ABSPATH'))
{
	exit();
}

require_once(plugin_dir_path(__FILE__). '/widget.php'); // Damit widet im Backend sichtbar ist
//__FILE__: startet ab dem Ordner wo die datei in der wir sind (kontaktformular.php) liegt 

register_activation_hook(__FILE__, array('kontaktformular', 'createTable'));
//register_deactivation_hook(__FILE__, array('kontaktformular', 'deactivate'));


if (!class_exists ('kontaktformular'))
{
	class kontaktformular 
	{
		function __construct()
		{
			add_action('wp_enqueue_scripts', array($this, 'enqueue')); //Frontend
			add_action('admin_enqueue_scripts', array($this, 'enqueue')); //Backend	
			
			// mail und datenbank funktion
			add_action('admin_post_nopriv_do_function', array($this, 'do_function'));
			
			//ajax-function
			add_action('wp_ajax_nopriv_do_function', array($this, 'do_function'));

			add_shortcode('form', array($this, 'formInput'));

		}
		function formInput() // für ausgeloggte User
		{
			if (!is_user_logged_in())
			{
				$custom_nonce = wp_create_nonce('custom_form_nonce');
				?>
  					<form id="form_logged_out" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" class="ajax_logged_out"> 
						<p>Schreibe eine Nachricht an uns!</p>
						<input type="text" name="name" id="name" placeholder="Vor- und Nachname *"/>
						<input type="email" name="mail" id="mail" placeholder="Deine E-Mail-Adresse *" required/>
						<input type="text" name="subject" id="subject" placeholder="Betreff *"/>		
						
						<input type='hidden' name='action' value='do_function' />	
						<input type='hidden' name='input_nonce' value='<?php echo $custom_nonce ?>' />			
						<textarea id="message" name="message" placeholder="Deine Nachricht... *"></textarea>
						<div id="answer"></div>
						<button type="submit" id="submit">Absenden!</button>
					</form>
				<?php 	
				
			}	 
		}	
		// function validationForm()
		// {
		// 	if( ! is_email($_POST['mail']) )
		// 	{
		// 		echo "Bitte eine gültige E-Mail-Adresse eingeben";
		// 	}

		// }
		
		public static function createTable()
		{
			ob_start();				
			
			global $wpdb;
			$table_name = $wpdb->prefix . 'contactform';
			$charset_collate = $wpdb->get_charset_collate();
			
			$sql = "CREATE TABLE $table_name (
				contactform_id int(9) NOT NULL AUTO_INCREMENT,
				contactform_name varchar(55) NOT NULL,
				contactform_email_address varchar(55) NOT NULL,
				contactform_subject varchar(55) NOT NULL,
				contactform_message varchar(200) NOT NULL,
				PRIMARY KEY  (contactform_id)
			) $charset_collate;";

			if (!function_exists('dbDelta'))
			{
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			}

			dbDelta($sql);	// führt query aus um eine Tabelle in DB zu erzeugen

			$db_verion = '1.0';
			add_option('db_version', $db_verion);
		}
		public function do_function()
		{
			
			if( ( isset( $_POST['ajaxform']) && $_POST['ajaxform'] === 'true' ) && (isset( $_POST['input_nonce'] ) && wp_verify_nonce( $_POST['input_nonce'], 'custom_form_nonce' ) ) )
			{
				//Mailversand
				$name = sanitize_text_field($_POST['name']);
        		$mailFrom = sanitize_text_field($_POST['mail']);
        		$subject = sanitize_text_field($_POST['subject']);
        		$message = sanitize_text_field($_POST['message']);
			
				$mailTo = "Vero@localhost";
        		$headers = 'From: ' . $mailFrom;
        		$body = "Du hast eine Nachricht von " . $name . " erhalten" . "\n\n" . $message;

				wp_mail($mailTo, $subject, $body, $headers);  
				
				// Eintrag in Datenbank
				global $wpdb;

				$table = $wpdb->prefix . 'contactform'; 
				$data = array(
					'contactform_name' => $_POST['name'],
					'contactform_email_address' => $_POST['mail'],
					'contactform_subject' => $_POST['subject'],		
					'contactform_message' => $_POST['message']
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
					"Email konnte nicht in Datenbank gespeichert werden";
				}
				wp_die(); // um sofort funktion zu beenden und ordnungsgemäße Antwort zurück zugeben
			}
			else
			{
				wp_die('Ungültige nonce');
			}
		}
		function enqueue()
		{
			//snippet für javaScript Bibliothek, immer vor der java-datei!
			wp_register_script('js_snippet', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js');
			wp_enqueue_script('js_snippet');
			
			//JS hinzufügen
			wp_register_script('ajax_script', plugins_url(). '/kontaktformular/js/main.js');
			wp_enqueue_script( 'ajax_script' );
			wp_localize_script( 'ajax_script', 'kf_ajax_data', array( 
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			));
			//wp_enqueue_script('kf-main-logged-in-script', plugins_url(). '/kontaktformular/js/logged-in.js');
			

			//CSS hinzufügen
			wp_register_style('kf_main_style', plugins_url(). '/kontaktformular/css/kf-style.css');
			wp_enqueue_style('kf_main_style');
		}

	}

}

$kf = new kontaktformular;




?>

