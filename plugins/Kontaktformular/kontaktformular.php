<?php
/*
Plugin Name: Kontaktformular
Plugin URI: http://localhost/wordpress/wp-admin/plugins.php
Description: Plugin das ein Kontaktformular öffnet
Author: Veronika Sedlackova
Version: 1.0
*/



// SICHERHEIT: exit if access directly geht nicht
if(!defined('ABSPATH'))
{
	echo 'Du kommst so nicht rein, Opfer';
	exit;
}


//(youtube: build a useful wordpress widget plugin)


//unsicher ob mit rein
require_once(plugin_dir_path(__FILE__). '/send.php');
//lädt widget
require_once(plugin_dir_path(__FILE__). '/widget.php'); // Damit widet im Backend sichtbar ist
// lädt warteschlangen-srcipt 
require_once(plugin_dir_path(__FILE__). '/includes/kf-scripts.php'); //__FILE__: startet ab dem Ordner wo die datei in der wir sind (kontaktformular.php) liegt 




/*
	function formInput()
	{   
		?>
  	    <form id="formId">  
					<p>Schreibe eine Nachricht an uns!</p>
					<input type="text" name="name" id="name" placeholder="Vor- und Nachname">
					<input type="email" name="mail" id="mail" placeholder="Deine E-Mail-Adresse" required>
					<input type="text" name="subject" id="subject" placeholder="Betreff">			
					<textarea id="message" name="message" placeholder="Deine Nachricht..."></textarea>
					<div id="answer"></div>
					<button type="submit" id="submit">Absenden!</button>	
				</form>
		 <?php
		 
			do_action('formInput');
	}


function kontaktformular_styles_enqueue() // css einbinden
	{
		wp_register_style( 'CSS_style', '/wp-content/plugins/Kontaktformular2/contactform.css' );
		wp_enqueue_style( 'CSS_style' );
	}

*/

//add_action('formInput', 'kontaktformular_briefsymbol_enqueue');
//add_shortcode('formular', 'formInput'); // Name im backend, funktionsname




?>