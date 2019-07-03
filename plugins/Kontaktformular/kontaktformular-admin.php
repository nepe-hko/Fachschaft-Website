<?php 

add_action( 'admin_menu', 'kf_add_menu_page' );
add_action( 'admin_init', 'kf_custom_settings' );

function kf_add_menu_page()                                                                       // custom administration page und subpage
{
    //admin page
    add_menu_page( __( 'Kontaktformular', 'kontaktformular' ), __( 'Kontaktformular', 'kontaktformular' ), 'manage_options', 'kf', 'kf_create_page_callback', 'dashicons-email', 115 );    

    add_submenu_page( 'kf', __( 'activedeactive', 'kontaktformular' ), __( 'Activate/Deactivate', 'kontaktformular' ), 'manage_options', 'kf_mail_contact', 'kf_contact_form_page_callback' );
}
function kf_create_page_callback()                                                               // Seiteninhalt der Startseite
{
    ?><h1><?php _e( 'Kontaktformular Plugin', 'kontaktformular' ); ?></h1><br>

    <h4><u><?php _e( 'Mail-Inbox Aktvierung:', 'kontaktformular' ); ?></u></h4>
    <p><?php _e( 'Im Untermenu \'Activate/Deactivate\' kannst du die Mail Inbox ein- oder ausblenden lassen.', 'kontaktformular' ); ?></p>
    <p><?php _e( 'Wenn du es angeschaltet hast, kannst du unter \'Mails\' die eingehenden Nachrichten anschauen.', 'kontaktformular' ); ?></p><br>
    
    <h4><u><?php _e( 'Kontaktformular Aktivierung für den Logout Bereich:', 'kontaktformular' ); ?></u></h4>
    <p><?php _e( 'Erstelle eine Seite für das Kontaktformular und gib den folgenden Code ein: ', 'kontaktformular' ); ?><code>[contactform]</code></p>
    <p><?php _e( 'Achte darauf das im Unterpunkt \'Permalink\' für den \'URL Slug\' ', 'kontaktformular' ); ?><strong>kontaktformular</strong><?php _e( ' eingegeben ist.', 'kontaktformular' ); ?></p><br>

    <h4><u><?php _e( 'Kontaktformular Aktivierung für den Login Bereich:', 'kontaktformular' ); ?></u></h4>
    <p><?php _e( 'Klick auf den Menupunkt \'Appearance/Widgets\' und füge das Kontaktformular-Widget in den gewünschten Bereich ein.', 'kontaktformular' ); ?></p><?php
}
function kf_custom_settings() 
{
    register_setting( 'contactform-options', 'activate' );                                      // erstellt einen Bereich im wp_options der Datenbank

    add_settings_section( 'kf-section', __( 'Kontaktformular', 'kontaktformular' ), 'kf_section_callback', 'kf_mail_contact' );                                 //speichert check-box feld in diesem Bereich
    add_settings_field( 'activate-form', __( 'Activate Contact Form', 'kontaktformular' ), 'kf_activate_contact_callback', 'kf_mail_contact', 'kf-section' );   // fügt Check-box Feld mit seiner Call-Back-Funktion hinzu
}
function kf_contact_form_page_callback()                                                        // Seiteninhalt der subpage
{
    ?><form method="post" action="options.php" class="kf-form" ><?php                           // options.php: dort werden Änderungen gespeichert
    settings_fields( 'contactform-options' );                                                   // erstellt hidden Input Felder für die Sicherheit
    do_settings_sections( 'kf_mail_contact' );
    submit_button();                                                                            // erstellt WordPress-Button
    ?></form><?php
    settings_errors();                                                                          // WordPress Antwort nachdem die action ausgeführt wurde 
}                                                                                                
function kf_section_callback()
{
    _e( 'Aktiviere oder deaktiviere die Mailansicht.', 'kontaktformular' );
}
function kf_activate_contact_callback()
{
    $options = esc_attr( get_option( 'activate' ) );                                              // gibt den Wert zurück der in Datenbank gespeichert wird
    $checked = ( @$options == 5 ? 'checked' : '' );                                               
    echo '<input type="checkbox" name="activate" value="5" ' .$checked. '/>';
}
?>