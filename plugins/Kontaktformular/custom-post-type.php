<?php 

$contact = get_option('activate'); 
if (@$contact == 5) // wenn button aktiviert ist führe den cpt aus
{
    add_action('init', 'kf_contact_cpt');
    add_filter('manage_kf-contact_posts_columns', 'kf_change_columns'); //hook greift alle automatisch erzeugten Coloumns aus dem admin und reicht sie an die Funktion? 
    add_action('manage_kf-contact_posts_custom_column', 'kf_show_colums_content', 10, 2); //10 priority, 2 variablen in funktion
    add_action('add_meta_boxes', 'kf_meta_box');
    add_action('save_post', 'check_update_meta_data');
}

function kf_contact_cpt()
{
    $labels = array(
        'name'              => 'Mails',
        'singular_name'     => 'Mail',
        'menu_name'         => 'Mails',
        'name_admin_bar'    => 'Mail'
    );

    $args = array(
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'capability_type'   => 'post',
        'hierarchical'      => false,
        'menu_position'     => 26,
        'supports'          => array('title', 'editor', 'author')  //author damit er in "add new" noch da ist

    );

    register_post_type( 'kf-contact', $args);
}

function kf_change_columns($columns)
{
    $newColumns = array();
    $newColumns['title'] = 'Name';
    $newColumns['email'] = 'Email';
    $newColumns['betreff'] = 'Betreff';
    $newColumns['message'] = 'Nachricht';
    $newColumns['date'] = 'Datum';
    return $newColumns;
}
function kf_show_colums_content($column, $post_id) //ist ein loop
{
    switch($column)
    {
        case 'email' :      $email = get_post_meta($post_id, '_contact_form_email', true);
                            echo $email;
                            break;
        case 'betreff' :    $subject = get_post_meta($post_id, '_contact_form_subject', true);
                            echo $subject;
                            break;
        case 'message' :    echo get_the_excerpt(); 
                            break;

    }
}
function kf_meta_box()
{
    add_meta_box('email_subject', 'Deine Email-Adresse und Betreff', 'kf_email_subject_callback', 'kf-contact', 'side');

}
function kf_email_subject_callback($post)
{
    wp_nonce_field('check_update_meta_data', 'kf_meta_box_nonce');
    $email_meta_box = get_post_meta($post->ID, '_contact_form_email', true); // true: single value not array
    $subject_meta_box = get_post_meta($post->ID, '_contact_form_subject', true);
    
   
    echo '<label for="kf_email_field">Deine Email Adresse: </label>';
    echo '<input type="email" name="kf_email_field" name="kf_email_field" value"' . esc_attr($email_meta_box) . '" /><br>';
    

    echo '<label for="kf_subject_field">Deinen Betreff: </label>';
    echo '<input type="text" name="kf_subject_field" name="kf_subject_field" value"' . esc_attr($subject_meta_box) . '" />';
    
}
function check_update_meta_data($post_id) // irgendwo Fehler drin
{
    if( ! isset($_POST['kf_meta_box_nonce']))
    {
        return;
    }
    if( ! wp_verify_nonce($_POST['kf_meta_box_nonce'], 'check_update_meta_data' ))
    {
        return;
    }
    if(! current_user_can('edit_post', $post_id))
    {
        return;
    }
    if( (! isset($_POST['kf_subject_field'])) || (isset($_POST['kf_email_field'])))
    {
        return;
    }

    $email_data = sanitize_email($_POST['kf_email_field']);
    $subject_data = sanitize_text_field($_POST['kf_subject_field']);

    update_post_meta($post_id, '_contact_form_email', $email_data);
    update_post_meta($post_id, '_contact_form_subject', $subject_data);

}




?>