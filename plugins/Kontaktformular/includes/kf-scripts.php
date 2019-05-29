<?php

// Fügt Scripts in die Warteschlange hinzu
function kf_add_scripts()
{
    //snippet für javaScript Bibliothek, immer vor der java-datei!
    wp_register_script('js-snippet', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js');
    wp_enqueue_script('js-snippet');
    //JS hinzufügen
    wp_enqueue_script('kf-main-script', plugins_url(). '/Kontaktformular/js/main.js');
    
 
    
}
add_action('wp_enqueue_scripts','kf_add_scripts'); //frontend
add_action('admin_enqueue_scripts', 'kf_add_scripts'); //backend
?>