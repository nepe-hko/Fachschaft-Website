<?php
/*
Plugin Name: kontaktformular
Plugin URI: http://localhost/wordpress/wp-admin/plugins.php
Author: Veronika Sedlackova
Version: 1.0
Description: Plugin das ein Kontaktformular anbietet
*/



// SICHERHEIT: exit if access directly geht nicht
if(!defined('ABSPATH'))
{
	exit();
}

require_once(plugin_dir_path(__FILE__). '/widget.php'); // Damit widet im Backend sichtbar ist
//__FILE__: startet ab dem Ordner wo die datei in der wir sind (kontaktformular.php) liegt 

register_activation_hook(__FILE__, array('kontaktformular', 'activate'));
//register_deactivation_hook(__FILE__, array('kontaktformular', 'deactivate'));


if (!class_exists ('kontaktformular'))
{
	class kontaktformular 
	{
		function __construct()
		{
			add_action('wp_enqueue_scripts', array($this, 'enqueue')); //Frontend
			add_action('admin_enqueue_scripts', array($this, 'enqueue')); //Backend	
			add_action('admin_post_nopriv_send_formInput', array($this, 'sendMailInput'));
			add_shortcode('form', array($this, 'formInput'));

		}
		function formInput() // für ausgeloggte User
		{
			if (!is_user_logged_in())
			{
				?>
  					<form id="form_logged_out" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" class="ajax_logged_out"> 
						<p>Schreibe eine Nachricht an uns!</p>
						<input type="text" name="name" id="name" placeholder="Vor- und Nachname *"/>
						<input type="email" name="mail" id="mail" placeholder="Deine E-Mail-Adresse *" required/>
						<input type="text" name="subject" id="subject" placeholder="Betreff *"/>		
						<input type='hidden' name='action' value='send_formInput' />	
						<textarea id="message" name="message" placeholder="Deine Nachricht... *"></textarea>
						<div id="answer"></div>
						<button type="submit" id="submit">Absenden!</button>							
					</form>
				<?php 	
			}	 
		}	
		function sendMailInput()
		{	
			if (isset($_POST['name'], $_POST['mail'], $_POST['subject'], $_POST['message']))   
   	 		{
        		$name = $_POST['name'];
        		$mailFrom = $_POST['mail'];
        		$subject = $_POST['subject'];
        		$message = $_POST['message'];
			
				$mailTo = "Vero@localhost";
        		$headers = 'From: ' . $mailFrom;
        		$body = "Du hast eine Nachricht von " . $name . " erhalten" . "\n\n" . $message;

        		wp_mail($mailTo, $subject, $body, $headers);  
				echo "Vielen Dank für deine Nachricht!";	
			}
			
		}


		public static function activate()
		{
			ob_start();
			// schauen ob jemand die berechtigungen hat?
		}
		function enqueue()
		{
			//snippet für javaScript Bibliothek, immer vor der java-datei!
			wp_register_script('js-snippet', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js');
			wp_enqueue_script('js-snippet');
			
			//JS hinzufügen
			wp_enqueue_script('kf-main-script', plugins_url(). '/kontaktformular/js/main.js');
			wp_enqueue_script('kf-main-logged-in-script', plugins_url(). '/kontaktformular/js/logged-in.js');
			
			//CSS hinzufügen
			 wp_register_style('kf-main-style', plugins_url(). '/kontaktformular/css/kf-style.css');
			 wp_enqueue_style('kf-main-style');
		}

	}

}

$kf = new kontaktformular;

















/*
if(!defined('ABSPATH'))
{
	exit();
}
//Erfolgt der Aufruf das Plugin zu löschen, wirklich durch WordPress
/*if (!defined('WP_UNINSTALL_PLUGIN'))
{
	exit();
}	


//lädt widget
require_once(plugin_dir_path(__FILE__). '/widget.php'); // Damit widet im Backend sichtbar ist
// lädt warteschlangen-srcipt 
require_once(plugin_dir_path(__FILE__). '/includes/kf-scripts.php'); //__FILE__: startet ab dem Ordner wo die datei in der wir sind (kontaktformular.php) liegt 


// wenn man nicht angemeldet ist ganzes kontaktformular anzeigen

function formInput() 
{   
	if (!is_user_logged_in())
	{
	?>
  		<form id="form_logged_out" action="<?php echo plugin_dir_url(__FILE__) . 'send.php'; ?>" method="post" class="ajax_logged_out">  
			<p>Schreibe eine Nachricht an uns!</p>
			<input type="text" name="name" id="name" placeholder="Vor- und Nachname">
			<input type="email" name="mail" id="mail" placeholder="Deine E-Mail-Adresse" required>
			<input type="text" name="subject" id="subject" placeholder="Betreff">			
			<textarea id="message" name="message" placeholder="Deine Nachricht..."></textarea>
			<div id="answer"></div>
			<button type="submit" id="submit">Absenden!</button>	
		</form>
	<?php
	
	}	 
	
}

add_shortcode('formular', 'formInput'); 



/*
class SendEmail {
	public function __construct()
	{
		register_activation_hool(__FILE__, array($this, 'send'));
	}

	public function send()
	{
		if(isset($_POST['subject_logged_in'], $_POST['message_logged_in']))
		{
			echo 'geschafft';
		}
	}
}*/
?>

