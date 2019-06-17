<?php 

add_action('admin_menu', 'kf_add_menu_page');
add_action('admin_init', 'kf_custom_settings' );

function kf_add_menu_page() // custom administration page und subpage
{
    //admin page
    add_menu_page('Kontaktformular', 'Kontaktformular', 'manage_options', 'kf_mail', 'kf_create_page_callback', 'dashicons-email', 115); // 6. Parameter icon    

    add_submenu_page('kf_mail', 'activedeactive', 'Activate/Deactivate', 'manage_options', 'kf_mail_contact', 'kf_contact_form_page_callback');
}
function kf_create_page_callback() // Seiteninhalt der Startseite
{
    echo '<h1>Kontaktformular Plugin</h1>';
    echo '<br>';
    echo '<h2>Hallo lieber Wordpress User. Vielen Dank dass du mein Plugin heruntergeladen hast! </h2>';
    echo '<p>Im Untermenu \'Activate/Deactivate\' kannst du die Mail Inbox ein- oder ausblenden lassen.</p>';
    echo '<p>Wenn du es angeschaltet hast, kannst du unter \'Mails\' die eingehenden Nachrichten anschauen.</p>';
    echo '<p>Viel Spaß du Opfer!</p>';
}
function kf_custom_settings() 
{
    register_setting('contactform-options', 'activate');

    add_settings_section('kf-section', 'Kontaktformular', 'kf_section_callback', 'kf_mail_contact');
    add_settings_field('activate-form', 'Activate Contact Form', 'kf_activate_contact_callback', 'kf_mail_contact', 'kf-section');
}
function kf_contact_form_page_callback() // Seiteninhalt der subpage
{

   echo '<form method="post" action="options.php" class="kf-form" >';
   settings_fields('contactform-options');
   do_settings_sections('kf_mail_contact');
   submit_button();
   echo '</form>';
   settings_errors();
}
function kf_section_callback()
{
    echo 'Aktiviere oder deaktiviere den Nachrichteneingang. ';
}
function kf_activate_contact_callback()
{
    $options = get_option('activate');
    $checked = (@$options == 5 ? 'checked' : ''); // wenn options exisiert und true ist dann checked
    echo '<input type="checkbox" name="activate" value="5" ' .$checked. '/>';
}