<?php
/*
Plugin Name: kontaktformular
Plugin URI: http://localhost/wordpress/wp-admin/plugins.php
Author: Veronika Sedlackova
Version: 1.0
Text Domain: kontaktformular
Domain Path: /languages
Description: Plugin das ein Kontaktformular anbietet
*/


//exit, falls direkt auf diese Datei zugegriffen wird
if( !defined( 'ABSPATH' ) )
{
	exit();
}

// lädt benötigte Dateien
require_once( plugin_dir_path( __FILE__ ). '/widget.php' ); 
require_once( plugin_dir_path( __FILE__ ). '/kontaktformular-admin.php' );
require_once( plugin_dir_path( __FILE__ ). '/custom-post-type.php' );
register_activation_hook( __FILE__, array( 'kontaktformular', 'createTable' ) );


// lädt textdomain
function kf_plugin_load_text_domain() 
{
	load_plugin_textdomain( 'kontaktformular', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'kf_plugin_load_text_domain' );


if( !class_exists( 'kontaktformular' ) )
{
	class kontaktformular 
	{
		function __construct()
		{
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) ); 						//Frontend
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) ); 						//Backend
			 
			add_action( 'admin_post_nopriv_do_function', array( $this, 'do_function' ) );			// hook für Mail- und Datenbankfunktion

			add_action( 'wp_ajax_nopriv_do_function', array( $this, 'do_function' ) );			//hook für ajax-Funktion

			$plugin = plugin_basename( __FILE__ );
			add_filter( "plugin_action_links_$plugin", array( $this, 'linkToPlugin' ) );			// hook zum Link zur Kontaktformularseite im Plugin-Admin-Bereich

			add_shortcode( 'contactform', array( $this, 'formInput' ) );
		}
		function linkToPlugin($link)
		{
			$pluginlink = '<a href="admin.php?page=kf">' . __( 'Kontaktformularseite', 'kontaktformular' ) . '</a>';			
			array_push( $link, $pluginlink );														// fügt contactform-link zum array, welcher an hook angehängt wird
			return $link;
		}
		public static function createTable()													// erstellt eigene Tabelle im phpmyadmin
		{					
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

			if( !function_exists( 'dbDelta'))													// falls dbDelta-Funktion nicht existiert, soll sie hinzugefügt werden
			{
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			}

			dbDelta( $sql );																		// führt query aus um eine Tabelle in DB zu erzeugen

			$db_version = '1.0';
			add_option( 'db_version', $db_version );
		}
		function formInput() 																	// Form für ausgeloggte User
		{
			if( !is_user_logged_in() )
			{
				?><form id='form_logged_out' action='<?php echo esc_url( admin_url ( 'admin-post.php' ) ); ?>' method='post' class='ajax_logged_out'> 
					<p><?php _e( 'Irgendwelche Anliegen?', 'kontaktformular' ); ?><br><?php _e( 'Dann teile sie uns mit!', 'kontaktformular' ); ?></p>
					<input type='text' name='name' id='name' maxlength='55' placeholder='<?php esc_html_e( 'Vor- und Nachname *', 'kontaktformular' ); ?>' />
					<input type='email' name='mail' id='mail' maxlength='55' placeholder='<?php esc_html_e( 'Deine E-Mail-Adresse *', 'kontaktformular' ); ?>' required/>
					<input type='text' name='subject' id='subject' maxlength='55' placeholder='<?php esc_html_e( 'Betreff *', 'kontaktformular' ); ?>'/>		
					<textarea id='message' name='message' maxlength='200' placeholder='<?php esc_html_e( 'Deine Nachricht... *', 'kontaktformular' ); ?>'></textarea>

					<input type='hidden' name='action' value='do_function' />	
					<div id='answer'></div>
					<button type='submit' id='submit'><?php _e( 'Absenden!', 'kontaktformular' ); ?></button>
				</form><?php 
			}	 
		}	
		public static function do_function()
		{
			// falls ausgeloggt
			if( ( isset( $_POST['name'] ) ) && ( isset( $_POST['mail'] ) && is_email( $_POST['mail'] ) ) && ( isset( $_POST['subject'] ) ) && ( isset( $_POST['message'] ) ) )
			{
				if( check_ajax_referer( 'nonce','security' ) )
				{
					$name = sanitize_text_field( $_POST['name'] ); //sanitize: Cleaning User Input
					$mailFrom = sanitize_email( $_POST['mail'] );
					$subject = sanitize_text_field( $_POST['subject'] );
					$message = sanitize_textarea_field( $_POST['message'] );
				}
			}

			// falls eingeloggt
			if( ( isset( $_POST['subject_logged_in'] ) ) && ( isset( $_POST['message_logged_in'] ) ) )
			{
				if( check_ajax_referer( 'nonce_login', 'secure' ) )
				{
					$user = wp_get_current_user(); //Name und Email aus worpress-Admin in Variable speichern

					$name = esc_html( $user->user_login );
					$mailFrom = esc_html( $user->user_email );
					$subject = sanitize_text_field( $_POST['subject_logged_in'] );
					$message = sanitize_textarea_field( $_POST['message_logged_in'] );
				}
			}

			// Nachricht in Mail-Backend laden
			$args = array(
				'post_title' 				=> $subject,
				'post_content' 				=> $message,
				'post_type' 				=> 'kfposttype',
				'post_status' 				=> 'publish', 												// damit nicht "Draft" im Email Backend angezeigt wird
				'meta_input' 				=> array(
					'_contact_form_email' 		=> $mailFrom,
					'_contact_form_name' 		=> $name
				)
			);
			$postID = wp_insert_post( $args ); 															
			
			
			// Mailversand
			$mailTo = 'Vero@localhost';
			$headers = __( 'Von: ', 'kontaktformular' ) . $mailFrom;
			$body = __( 'Du hast eine Nachricht von ', 'kontaktformular' ) . $name . __( ' erhalten', 'kontaktformular' ) . "\n\n" . $message;
			
			wp_mail( $mailTo, $subject, $body, $headers );  												
				


			//Eintrag in Datenbank
			global $wpdb;

			$table = $wpdb->prefix . 'contactform'; 
			$data = array(
				'contactform_name' => $name,
				'contactform_email_address' => $mailFrom,
				'contactform_subject' => $subject,		
				'contactform_message' => $message
			);
			$format = array(
				'%s', 																					// string-Wert
				'%s',
				'%s',
				'%s'
			);

			$sucessful = $wpdb->insert( $table, $data, $format ); 										//Funktion escaped Daten automatisch

			$id = $wpdb->insert_id;


			if( $id == false && $sucessful == false )														// Test ob es in DB gespeichert werden konnte
			{
				_e( 'Email konnte nicht in Datenbank gespeichert werden', 'kontaktformular' );
			}
			wp_die(); 																					// sofortige Beendung und Rückgabe einer ordnungsgemäße Antwort
		
		}
		public function enqueue() 																		// wird von wp aufgerufen deswegen muss es public sein
		{
			//javascriptdatei für Logout
			wp_register_script( 'ajax_script_logout', plugins_url(). '/kontaktformular/js/logged-out.js', array( 'jquery' ) ); // lädt jquery-Bibliothek vor dem logged-out.js Skript
			wp_enqueue_script( 'ajax_script_logout' );
			wp_localize_script( 'ajax_script_logout', 'kf_ajax_data_logout', array( 
				 	'ajaxurl' 					=> admin_url( 'admin-ajax.php' ),
					'ajax_nonce' 				=> wp_create_nonce( 'nonce' ),
					'answer_all_fields_filled' 	=> esc_html__( 'Bitte alle Felder ausfuellen.', 'kontaktformular' ),
					'ajax_success_message' 		=> esc_html__( 'Nachricht wurde versendet.', 'kontaktformular' ),
					'ajax_fail_message'			=> esc_html__( 'Es ist ein Fehler unterlaufen', 'kontaktformular' )
				) );

			//JavaScriptdatei für login
			wp_register_script( 'ajax_script_login', plugins_url(). '/kontaktformular/js/logged-in.js', array( 'jquery' ) );
			wp_enqueue_script( 'ajax_script_login' );
			wp_localize_script( 'ajax_script_login', 'kf_ajax_data_login', array( 
				 	'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'ajax_nonce_login' => wp_create_nonce( 'nonce_login' ),
					'answer_all_fields_filled' 	=> esc_html__( 'Bitte alle Felder ausfuellen.', 'kontaktformular' ),
					'ajax_success_message' 		=> esc_html__( 'Nachricht wurde versendet.', 'kontaktformular' ),
					'ajax_fail_message'			=> esc_html__( 'Es ist ein Fehler unterlaufen', 'kontaktformular' )		
				) );

			//CSS-Datei Hinzufügung
			wp_register_style( 'kf_main_style', plugins_url(). '/kontaktformular/css/kf-style.css' );
			wp_enqueue_style( 'kf_main_style' );
		}
	}
}

$kf = new kontaktformular();


