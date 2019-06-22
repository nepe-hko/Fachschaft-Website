<?php
if(! class_exists('Polls'))
{
    class Polls
    {
        public function __construct()
        {

            add_action('init', array($this,'create_posttype'));

            add_filter('manage_poll_posts_columns', array($this,'change_columns')); 
            add_action('manage_poll_posts_custom_column', array($this, 'show_colums_content', 10, 2));

            add_action('add_meta_boxes',array($this,'meta_box_poll'));

            add_action('save_post', array($this, 'save_poll_data'));
        }


        public function create_posttype()
        {
            $labels = array(
                'name' => 'Polls',
                'singular_name' => 'Poll',
                'menu_name' => 'Polls',
                'add_new_item' => 'Add New Poll'
            );

            $args = array(
                'labels' => $labels,
                'show_ui' => true,
                'show_in_menu' => true,
                'hierarchical' => false,
                'custom-fields' => true,
                'menu_position' => 25 ,
                'supports'=> array( 'title', 'editor', 'author'),
                'menu_icon' =>  'dashicons-chart-bar'
            );

            register_post_type( 'poll', $args );
        }

        function change_columns($columns)
        {
            $columns = array();
            $columns['qst'] = 'Frage';
            $columns['yes'] = 'Ja';
            $columns['no'] = 'Nein';
            $columns['date'] = 'Datum';
            return $columns;
        }

        function show_colums_content($column, $post_id) 
        {
            switch($column)
            {
                case 'qst' : 
                    echo get_post_meta( $post_id, '_poll_value_key', true );
                    break;
                case 'yes' :  
                    echo get_the_excerpt(); 
                     break;
                        
                case 'no' :
                    echo get_the_excerpt(); 
                    break;
            }  
        }
        
        //Add Meta Box
        public function meta_box_poll()
        {
            add_meta_box
            (
                'poll-meta-box-id',             // $id
                'Abstimmung',                   // $title
                array($this,'print_meta'),      // $callback
                'poll',                         // $page
                'normal',                       // $context
                'high'                          // $priority
            );
        }

        public function print_meta($post)
        {
            wp_nonce_field('save_data','poll_meta_box_nonce');
            $value = get_post_meta( $post -> ID, '_poll_value_key', true);

            echo '<label  for="qst">Ihre Frage: </label>';
            echo '<input type="text" id="qst" name="qst" value="'. esc_attr( $value ) .'"';
        }

        public function save_poll_data($post_id)
        {
            if(! isset ($_POST['poll_meta_box_nonce']))
            {
                return;
            }
            if( ! wp_verify_nonce( $_POST['poll_meta_box_nonce'], 'save_poll_data'))
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
            if( ! isset( $_POST['qst']))
            {
                $data = sanitize_text_field( $_POST['qst'] );  
                update_post_meta( $post_id, '_poll_value_key', $data );       
            }
        }
        
    }
}

new Polls();