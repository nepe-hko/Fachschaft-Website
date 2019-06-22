<?php 
// if(! class_exists('CPT'))
// {
//     class CPT
//     {
        // public function __construct()
        // {

        //     // add_action('init', array($this,'create_posttype'));

        //     // add_filter('manage_reg_posts_columns', array($this,'change_columns')); 

        //     // add_action('manage_reg_posts_custom_column', array($this, 'show_columns_content',10,2));

        //     // add_action('add_meta_boxes',array($this,'meta_box_reg'));

        //     // add_action('save_post', array($this, 'save_reg_data'));
        // }
        add_action('init', 'create_posttype');

        add_filter('manage_reg_posts_columns', 'change_columns'); 

        add_action('manage_reg_posts_custom_column',  'show_columns_content',10,2);

        add_action('add_meta_boxes','meta_box_reg');

        add_action('save_post', 'save_reg_data');


         function create_posttype()
        {
            $labels = array(
                'name' => 'BenutzerReg',
                'singular_name' => 'Benutzer',
                //'menu_name' => 'Benutzer',
                'name_admin_bar' => 'Benutzer',
                'add_new_item' => 'Neuen Benutzer erstellen'
            );

            $args = array(
                'labels' => $labels,
                'show_ui' => true,
                'show_in_menu' => true,
                'hierarchical' => false,
                'custom-fields' => true,
                'menu_position' => 25 ,
                'supports'=> array( ' '),
                'menu_icon' =>  'dashicons-groups'
            );

            register_post_type( 'reg', $args );
        }

         function change_columns($columns)
        {
            $columns = array();
            $columns['vorname'] = 'Vorname';
            $columns['nachname'] = 'Nachname';
            $columns['email'] = 'Email';
            $columns['username'] = 'Username';
            $columns['role'] = 'Rolle';            
            return $columns;
        }

         function show_columns_content($column, $post_id) 
        {
            switch($column)
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

                // case 'role' : 
                //     echo get_post_meta( $post_id, 'role', true );
                //     break;
            }  
        }
        
        //Add Meta Box
         function meta_box_reg()
        {
            add_meta_box
            (
                'reg-meta-box-id',             // $id
                'Benutzer',                   // $title
                //array($this,'print_meta'),      // $callback
                'print_meta',
                'reg',                         // $page
                'normal'                       // $context
            );

        }

         function print_meta($post)
        {
            wp_nonce_field('save_data','reg_meta_box_nonce');
            $value_vorname = get_post_meta( $post -> ID, '_vorname_value_key', true);
            $value_nachname = get_post_meta( $post -> ID, '_nachname_value_key', true);
            $value_email = get_post_meta( $post -> ID, '_email_value_key', true);
            $value_username = get_post_meta( $post -> ID, '_username_value_key', true);
?>          
    	    <table>
                <tr>
                    <td>Vorname: </td>
                    <td><input type="text" id="vorname" name="vorname" value="<?php  esc_attr( $value_vorname )  ?>"></td>
                </tr>
                <tr>
                    <td>Nachname: </td>
                    <td><input type="text" id="nachname" name="nachname" value="<?php esc_attr( $value_nachname ) ?>"></td>
                </tr>
                <tr>
                    <td>Email: </td>
                    <td><input type="text" id="email" name="email" value="<?php esc_attr( $value_email ) ?>"></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><input type="text" id="username" name="username" value="<?php esc_attr( $value_username ) ?>"></td>
                </tr>
            </table>
<?php
        }

         function save_reg_data($post_id)
        {   
            if(! isset ($_POST['reg_meta_box_nonce']))
            {
                return;
            }
            if( ! wp_verify_nonce( $_POST['reg_meta_box_nonce'], 'save_reg_data'))
            {
                return;
            }
            if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            {
                return;
            }
            if( ! current_user_can('edit_post', $post_id))
            {
                return;
            } 
            if( isset( $_REQUEST['vorname']))
            {
                update_post_meta( $post_id, '_vorname_value_key', sanitize_text_field( $_POST['vorname'] ) );               
            }
    

            // if( isset( $_POST['nachname']))
            // {
            //     update_post_meta( $post_id, '_nachname_value_key', sanitize_text_field( $_POST['nachname'] ) );       
            // }

            // if( isset( $_POST['email']))
            // {
            //     update_post_meta( $post_id, '_email_value_key', sanitize_text_field( $_POST['email'] ) );       
            // }

            // if( isset( $_POST['username']))
            // {
            //     update_post_meta( $post_id, '_username_value_key', sanitize_text_field( $_POST['username'] ) );       
            // }

        }
        
//     }
// }

// $cpt = new CPT();