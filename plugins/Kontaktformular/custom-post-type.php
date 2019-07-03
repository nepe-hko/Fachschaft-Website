<?php 

$contact = get_option( 'activate' ); 
if ( @$contact == 5 )                                                                               // wenn button haken hat ist führe den cpt aus
{
    add_action( 'init', 'kf_contact_cpt' );
    add_filter( 'manage_kfposttype_posts_columns', 'kf_change_columns' );                           
    add_action( 'manage_kfposttype_posts_custom_column', 'kf_show_colums_content', 10, 2 );         // 10 Priorität, 2 variablen in funktion
    add_action( 'add_meta_boxes', 'kf_meta_box_email' );
    add_action( 'save_post', 'update_meta_box_and_databaseinsertion' );
}

function kf_contact_cpt()                                                                           // registriert CPT
{
    $labels = array(
        'name'              => __( 'Mails', 'kontaktformular' ),
        'singular_name'     => __( 'Mail', 'kontaktformular' ),
        'menu_name'         => __( 'Mails', 'kontaktformular' ),
        'name_admin_bar'    => __( 'Mail', 'kontaktformular' )
    );

    $args = array(
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'custom-fields'     => true,
        'menu_icon'         => 'dashicons-email',
        'menu_position'     => 26,
        'supports'          => array( 'title', 'editor' )                                

    );

    register_post_type( 'kfposttype', $args );
}

function kf_change_columns( $columns )                                                              // neue Spaltenbeschriftungen
{
    $newColumns = array();
    $newColumns['title'] = __( 'Betreff', 'kontaktformular' );
    $newColumns['email'] = __( 'E-mail', 'kontaktformular' );
    $newColumns['name'] = __( 'Name', 'kontaktformular' );
    $newColumns['message'] = __( 'Nachricht', 'kontaktformular' );
    $newColumns['date'] = __( 'Datum', 'kontaktformular' );
    return $newColumns;
}
function kf_show_colums_content( $column, $post_id )                                              // gibt in den Spalten passenden Inhalt aus
{
    switch( $column )
    {
        case 'email' :  
            $email = get_post_meta( $post_id, '_contact_form_email', true ); 
            echo $email; 
            break;
                        
        case 'name' :
            $name = get_post_meta( $post_id, '_contact_form_name', true ); 
            echo $name;
            break;
        
        case 'message' : 
            echo get_the_excerpt(); 
            break;
    }  
}
function kf_meta_box_email()
{
    add_meta_box( 'email_meta_box_id', __( 'Email-Adresse und Name:', 'kontaktformular' ), 'kf_meta_box_callback', 'kfposttype', 'side' );
}
function kf_meta_box_callback( $post )                                                                
{
    wp_nonce_field( 'update_meta_box_and_databaseinsertion', 'meta_box_nonce' );
    $email_meta_box = get_post_meta( $post->ID, '_contact_form_email', true );                      // true: single Wert nicht Array
    $name_meta_box = get_post_meta( $post->ID, '_contact_form_name', true ); 

    ?><label for="kf_email_field"><?php _e( 'Email Adresse:', 'kontaktformular' ); ?></label>
    <input type="email" id="kf_email_field" name="kf_email_field" value="<?php esc_attr_e( $email_meta_box, 'kontaktformular' ) ?>" size="25"/><br><?php

    ?><label for="kf_name_field"><?php _e( 'Name:', 'kontaktformular' ); ?></label><br>
    <input type="text" id="kf_name_field" name="kf_name_field" value="<?php esc_attr_e( $name_meta_box, 'kontaktformular' ) ?>" size="25"/><br><?php
}
function update_meta_box_and_databaseinsertion( $post_id )      
{
    if( ! isset( $_POST['meta_box_nonce'] ) )
    {
        return;
    }
    if( ! wp_verify_nonce( $_POST['meta_box_nonce'], 'update_meta_box_and_databaseinsertion' ) )
    {
        return;
    }
    if( ! current_user_can( 'edit_post', $post_id ) )                                               // Überprüfung ob User benötigte Priorität hat
    {
        return;
    }
    if ( isset( $_POST['kf_email_field'] ) )
    { 
        $email_data = sanitize_text_field( $_POST['kf_email_field'] );  
        update_post_meta( $post_id, '_contact_form_email', $email_data );
    }
    if ( isset( $_POST['kf_name_field'] ) )
    { 
        $name_data = sanitize_text_field( $_POST['kf_name_field'] );  
        update_post_meta( $post_id, '_contact_form_name', $name_data );
    }

    // Datenbank Eintrag
    global $wpdb;
    $table = $wpdb->prefix . 'contactform';
 
    $name =  $_POST['kf_name_field'];
    $mailFrom = $_POST['kf_email_field'];
    
    $aktuellerpost = get_post($post_id);
    $subject = $aktuellerpost->post_title;
    $message = $aktuellerpost->post_content;
        
    $data = array(
        'contactform_name' => $name,
        'contactform_email_address' => $mailFrom,
        'contactform_subject' => $subject,		
        'contactform_message' => $message
    );
    $format = array(
        '%s', 																					
        '%s',
        '%s',
        '%s'
    );
    $wpdb->insert( $table, $data, $format ); 
}
?>