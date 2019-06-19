<?php 

$contact = get_option('activate'); 
if (@$contact == 5) // wenn button aktiviert ist führe den cpt aus
{
    add_action('init', 'kf_contact_cpt');
    add_filter('manage_kfposttype_posts_columns', 'kf_change_columns'); //hook greift alle automatisch erzeugten Coloumns aus dem admin und reicht sie an die Funktion? 
    add_action('manage_kfposttype_posts_custom_column', 'kf_show_colums_content', 10, 2); //10 priority, 2 variablen in funktion
    add_action('add_meta_boxes', 'kf_meta_box_email'); //aktiviere Meta-box
    add_action('save_post', 'check_update_meta_data_email');
    add_action('save_post', 'check_update_meta_data_name');
}

function kf_contact_cpt()
{
    $labels = array(
        'name'              => __('Mails', 'kontaktformular'),
        'singular_name'     => __('Mail', 'kontaktformular'),
        'menu_name'         => __('Mails', 'kontaktformular'),
        'name_admin_bar'    => __('Mail', 'kontaktformular')
    );

    $args = array(
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'hierarchical'      => false,
        'custom-fields'     => true,
        'menu_icon'         => 'dashicons-email',
        'menu_position'     => 26,
        'supports'          => array('title', 'editor', 'author')                                //author damit er in "add new" noch da ist

    );

    register_post_type( 'kfposttype', $args );
}

function kf_change_columns($columns)
{
    $newColumns = array();
    $newColumns['title'] = __('Betreff', 'kontaktformular');
    $newColumns['email'] = __('E-mail', 'kontaktformular');
    $newColumns['name'] = __('Name', 'kontaktformular');
    $newColumns['message'] = __('Nachricht', 'kontaktformular');
    $newColumns['date'] = __('Datum', 'kontaktformular');
    return $newColumns;
}
function kf_show_colums_content($column, $post_id)                                              //ist ein loop
{
    switch($column)
    {
        case 'email' :  
            $email = get_post_meta($post_id, '_contact_form_email', true); 
            echo $email; 
            break;
                        
        case 'name' :
            $name = get_post_meta($post_id, '_contact_form_name', true); 
            echo $name;
            break;
        
        case 'message' : 
            echo get_the_excerpt(); 
            break;
    }  
}
function kf_meta_box_email()
{
    add_meta_box('email_meta_box_id', __('Deine Email-Adresse: ', 'kontaktformular'), 'kf_meta_box_callback', 'kfposttype', 'side');
}


function kf_meta_box_callback($post)                                                                // $post automatisch von der meta-box hinzugefügt, enthält alle Infos über den aktuellen Post -> kf-contact
{
    wp_nonce_field('check_update_meta_data_email', 'email_meta_box_nonce');
    $email_meta_box = get_post_meta($post->ID, '_contact_form_email', true); // true: single value not array

    ?>
    <label for="kf_email_field"><?php _e('Deine Email Adresse:', 'kontaktformular'); ?> </label>
    <input type="email" id="kf_email_field" name="kf_email_field" value="<?php esc_attr_e($email_meta_box) ?>" size="25"/><br>
    <?php

    wp_nonce_field('check_update_meta_data_name', 'name_meta_box_nonce');
    $name_meta_box = get_post_meta($post->ID, '_contact_form_name', true); 

    ?>
    <label for="kf_name_field"><?php _e('Dein Namen:', 'kontaktformular'); ?></label>
    <input type="text" id="kf_name_field" name="kf_name_field" value="<?php esc_attr_e($name_meta_box) ?>" size="25"/><br>
    <?php
}


function check_update_meta_data_email($post_id) 
{

    if( ! isset($_POST['email_meta_box_nonce']))
    {
        return;
    }
    if( ! wp_verify_nonce($_POST['email_meta_box_nonce'], 'check_update_meta_data_email' ))
    {
        return;
    }
    if( ! current_user_can('edit_post', $post_id))                                                                              // check ob User die benötigte Priorität hat was zu ändern
    {
        return;
    }

    if ( isset($_POST['kf_email_field']))
    { 
        $email_data = sanitize_text_field( $_POST['kf_email_field'] );  
        update_post_meta( $post_id, '_contact_form_email', $email_data );
    }
}
function check_update_meta_data_name($post_id)
{
    if( ! isset($_POST['name_meta_box_nonce']))
    {
        return;
    }
    if( ! wp_verify_nonce($_POST['name_meta_box_nonce'], 'check_update_meta_data_name' ))
    {
        return;
    }
    if( ! current_user_can('edit_post', $post_id)) // check ob User die benötigte Priorität hat was zu ändern
    {
        return;
    }

    if ( isset($_POST['kf_name_field']))
    { 
        $name_data = sanitize_text_field( $_POST['kf_name_field'] );  
        update_post_meta( $post_id, '_contact_form_name', $name_data );
    }
}



?>