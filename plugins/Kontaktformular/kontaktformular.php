<?php
/*
Plugin Name: Kontaktformular
Plugin URI: http://localhost/wordpress/wp-admin/plugins.php
Author: Veronika Sedlackova
Version: 1.0
Description: Plugin das ein Kontaktformular anbietet
*/


// SICHERHEIT: exit if access directly geht nicht
if(!defined('ABSPATH'))
{
	echo 'Du kommst so nicht rein, Opfer';
	exit;
}


//lädt widget
require_once(plugin_dir_path(__FILE__). '/widget.php'); // Damit widet im Backend sichtbar ist
// lädt warteschlangen-srcipt 
require_once(plugin_dir_path(__FILE__). '/includes/kf-scripts.php'); //__FILE__: startet ab dem Ordner wo die datei in der wir sind (kontaktformular.php) liegt 


// wenn man nicht angemeldet ist
function formInput() 
{   
	?>
  		<form id="formId" action="<?php echo plugin_dir_url(__FILE__) . 'send.php'; ?>" method="post" class="ajax">  
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

add_shortcode('formular', 'formInput'); 

?>