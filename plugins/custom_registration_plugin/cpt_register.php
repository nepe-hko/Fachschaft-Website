<?php

    add_action( 'init', 'create_posttype');
    add_action( 'wp_loaded', 'insert_in_database');

    add_filter( 'manage_register-cpt_posts_columns', 'set_columns');
    add_action( 'manage_register-cpt_posts_custom_column', 'custom_column', 10, 2 );

    add_action( 'add_meta_boxes', 'registration_add_meta_box');

    add_action( 'save_post', 'register_save_vn_data' );
    add_action( 'save_post', 'register_save_nn_data' );
    add_action( 'save_post', 'register_save_email_data' );
    add_action( 'save_post', 'register_save_user_data' );
    add_action( 'save_post', 'register_save_role_data' );

function create_posttype()
{
    $labels = array(
        'name' => __('Benutzer', 'custom_registration_plugin'),
        'singular_name' => __('Benutzer', 'custom_registration_plugin'),
        'name_admin_bar' => __('Benutzer', 'custom_registration_plugin'),
        'add_new_item' => __('Neuen Benutzer erstellen', 'custom_registration_plugin'),
    );

    $args = array(
        'labels'          => $labels,
        'public'          => true,
        'has_archive'     => true,
        'rewrite'         => true,
        'show_ui'         => true,
        'show_in_menu'    => true,
        'custom-fields'   => true,
        'menu_position'   => 25 ,
        'supports'        => false,
        'capabilities' => array(
            'edit_post'          => 'update_core',
            'read_post'          => 'update_core',
            'delete_post'        => 'update_core',
            'edit_posts'         => 'update_core',
            'edit_others_posts'  => 'update_core',
            'delete_posts'       => 'update_core',
            'publish_posts'      => 'update_core',
            'read_private_posts' => 'update_core'
        ),
        'map_meta_cap' => true,
        'menu_icon'       =>  'dashicons-groups'
    );
    register_post_type( 'register-cpt', $args );

}

function set_columns( $columns )
{
    $columns = array();
    $columns['vorname']     = __('Vorname', 'custom_registration_plugin' );
    $columns['nachname']    = __('Nachname', 'custom_registration_plugin' );
    $columns['email']       = __('Email', 'custom_registration_plugin' );
    $columns['username']    = __('Username', 'custom_registration_plugin' );
    $columns['role']        = __('Rolle', 'custom_registration_plugin' );   
    $columns['date']        = __('Datum', 'custom_registration_pluginn' );
    return $columns;
}

function custom_column( $column, $post_id )
{
    switch( $column )
    {
        case 'vorname' :
            echo get_post_meta( $post_id, '_vorname_value_key', true );
            break;
        case 'nachname' : 
            echo get_post_meta( $post_id, '_nachname_value_key', true );
            break;   
        case 'email' : 
            echo get_post_meta( $post_id, '_email_value_key', true );
            break;
        case 'username' : 
            echo get_post_meta( $post_id, '_username_value_key', true );
            break;
        case 'role' : 
            echo get_post_meta( $post_id, '_role_value_key', true );
            break;
    }
}

function registration_add_meta_box()
{
    add_meta_box('register_user', __('Benutzer erstellen', 'custom_registration_plugin'), 'register_user_callback', 'register-cpt', 'normal', 'high');
}

function register_user_callback( $post )
{
    wp_nonce_field('register_save_vn_data', 'register_vn_meta_box_nonce');
    wp_nonce_field('register_save_nn_data', 'register_nn_meta_box_nonce');
    wp_nonce_field('register_save_email_data', 'register_email_meta_box_nonce');
    wp_nonce_field('register_save_user_data', 'register_user_meta_box_nonce');
    wp_nonce_field('register_save_role_data', 'register_role_meta_box_nonce');

    $value_vorname = get_post_meta( $post -> ID, '_vorname_value_key', true );
    $value_nachname = get_post_meta( $post -> ID, '_nachname_value_key', true );
    $value_email    = get_post_meta( $post -> ID, '_email_value_key', true );
    $value_username = get_post_meta( $post -> ID, '_username_value_key', true );
    $value_role = get_post_meta( $post -> ID, '_role_value_key', true );

?>
    	    <table>
                <tr>
                    <td><?php _e('Vorname: ', 'custom_registration_plugin' ); ?></td>
                    <td><input type="text" id="register_vn_field" name="register_vn_field" value="<?php esc_attr_e( $value_vorname, 'custom_registration_plugin' ) ?>"></td>
                </tr>
                <tr>
                    <td><?php _e('Nachname: ', 'custom_registration_plugin' ); ?></td>
                    <td><input type="text" id="register_nn_field" name="register_nn_field" value="<?php esc_attr_e( $value_nachname, 'custom_registration_plugin' ) ?>"></td>
                </tr>
                <tr>
                    <td><?php _e('Email: ', 'custom_registration_plugin' ); ?></td>
                    <td><input type="email" id="register_email_field" name="register_email_field" value="<?php esc_attr_e( $value_email, 'custom_registration_plugin' ) ?>"></td>
                </tr>
                <tr>
                    <td><?php _e('Username: ', 'custom_registration_plugin' ); ?></td>
                    <td><input type="text" id="register_user_field" name="register_user_field" value="<?php esc_attr_e( $value_username, 'custom_registration_plugin' ) ?>"></td>
                </tr>
                <tr>
                    <td><?php _e('Rolle: ', 'custom_registration_plugin' ); ?></td>
                    <td>            
                        <select name="register_role_field" id="register_role_field">
			                <option value='subscriber'><?php _e('Abonnent', 'custom_registration_plugin' ); ?></option>
                            <option value='contributor'><?php _e('Mitarbeiter', 'custom_registration_plugin' ); ?></option>
                            <option value='author'><?php _e('Autor', 'custom_registration_plugin' ); ?></option>
                            <option value='editor'><?php _e('Redakteur', 'custom_registration_plugin' ); ?></option>
                            <option value='administrator'><?php _e('Administrator', 'custom_registration_plugin' ); ?></option>			
                        </select>
                    </td>
                </tr>
            </table>
<?php
}

function insert_in_database()
{
    
    if(isset( $_POST['register_nn_field'] ) &&  isset( $_POST['register_vn_field'] ) &&  isset( $_POST['register_email_field'] ) && isset( $_POST['register_user_field'] ) && isset( $_POST['register_role_field'] ))
    {
        $screen = get_current_screen(); 

        $vorname = sanitize_text_field($_POST['register_vn_field']);
        $nachname = sanitize_text_field($_POST['register_nn_field']);
        $username = sanitize_text_field($_POST['register_user_field']);
        $email = sanitize_email($_POST['register_email_field']);
        $role = $_POST['register_role_field'];


        $passwort = wp_generate_password( 12, false );

        $user_data = array
        (
            'first_name' => $vorname,
            'last_name' => $nachname,
            'user_login' => $username, 
            'user_email' => $email,
            'user_pass' => $passwort,
            'role' => $role
        );

        $user_id = wp_insert_user($user_data);
        wp_new_user_notification( $user_id, $passwort );

    }
}

function register_save_vn_data( $post_id )
{
    if( (! isset( $_POST['register_vn_meta_box_nonce'] ) ) || ( ! wp_verify_nonce( $_POST['register_vn_meta_box_nonce'], 'register_save_vn_data' ) ) || ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || ( ! isset( $_POST['register_vn_field'] ) ) || ( ! current_user_can( 'edit_post', $post_id ) ))
    {
        return;
    }

    $my_data_vn = sanitize_text_field( $_POST['register_vn_field'] );
    update_post_meta( $post_id, '_vorname_value_key', $my_data_vn );
}

function register_save_nn_data( $post_id )
{
    if( (! isset( $_POST['register_nn_meta_box_nonce'] ) ) || ( ! wp_verify_nonce( $_POST['register_nn_meta_box_nonce'], 'register_save_nn_data' ) ) || ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || ( ! isset( $_POST['register_nn_field'] ) ) || ( ! current_user_can( 'edit_post', $post_id ) ))
    {
        return;
    }

    $my_data_nn = sanitize_text_field( $_POST['register_nn_field'] );
    update_post_meta( $post_id, '_nachname_value_key', $my_data_nn );
}

function register_save_email_data( $post_id )
{
    if( (! isset( $_POST['register_email_meta_box_nonce'] ) ) || ( ! wp_verify_nonce( $_POST['register_email_meta_box_nonce'], 'register_save_email_data' ) ) || ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || ( ! isset( $_POST['register_email_field'] ) ) || ( ! current_user_can( 'edit_post', $post_id ) ))
    {
        return;
    }

    $my_data_email = sanitize_text_field( $_POST['register_email_field'] );
    update_post_meta( $post_id, '_email_value_key', $my_data_email );
}

function register_save_user_data( $post_id )
{
    if( (! isset( $_POST['register_user_meta_box_nonce'] ) ) || ( ! wp_verify_nonce( $_POST['register_user_meta_box_nonce'], 'register_save_user_data' ) ) || ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || ( ! isset( $_POST['register_user_field'] ) ) || ( ! current_user_can( 'edit_post', $post_id ) ))
    {
        return;
    }

    $my_data_user = sanitize_text_field( $_POST['register_user_field'] );
    update_post_meta( $post_id, '_username_value_key', $my_data_user );
}

function register_save_role_data( $post_id )
{
    if( (! isset( $_POST['register_role_meta_box_nonce'] ) ) || ( ! wp_verify_nonce( $_POST['register_role_meta_box_nonce'], 'register_save_role_data' ) ) || ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || ( ! isset( $_POST['register_role_field'] ) ) || ( ! current_user_can( 'edit_post', $post_id ) ))
    {
        return;
    }

    $my_data_role = $_POST['register_role_field'];
    update_post_meta( $post_id, '_role_value_key', $my_data_role );
}
