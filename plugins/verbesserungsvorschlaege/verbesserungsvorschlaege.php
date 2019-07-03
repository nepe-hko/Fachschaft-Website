<?php
/*
Plugin Name: Verbesserungsvorschlaege
Version: 1.0
Author: Daniel Hauk
Description: Einreichen von Verbesserungsvorschlägen
*/


class Improvements
{

    private static $instance;

    static function get_instance(){
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {

        add_action('init', array($this, 'register_improvement_post_type'));
        add_action('admin_init', array($this, 'add_custom_meta_boxes'));
        add_action('save_post', array($this, 'save_from_admin'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
        add_action( 'wp_ajax_vbv_submit', array($this, 'vbv_submit') );
        add_action( 'wp_ajax_nopriv_vbv_submit', array($this, 'vbv_submit') );
    }

    function enqueue() {

        // enqueue javascript and jquery to frontend.
        wp_register_script( 'send_form_script', plugins_url('/public/js/send_form.js', __FILE__ ), array('jquery') );
        wp_enqueue_script('send_form_script');

        // add ajax url to script
        wp_localize_script('send_form_script', 'vbv_ajax_data', array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'vbv_secure' => wp_create_nonce('vbv_secure')
        ));
        
        // enqueue style to frontend
        wp_register_style('vbv_style', plugins_url( 'public/css/vbv_style.css', __FILE__ ));
        wp_enqueue_style( 'vbv_style' );
    }

    // callback function for submitting new improvement from frontend
    function vbv_submit() {

        
        if( !check_ajax_referer('vbv_secure', 'vbv_secure')) {
            echo __('Fehler beim übertragen der Daten', 'improvements' );
            die();
        }
 
        if ( !isset($_POST['title']) && !isset($_POST['content']) ) {
            return;
        }

        if (!is_user_logged_in()) {
            return;
        }

        // get data from frontend
        $title = sanitize_text_field($_POST['title']);
        $content = sanitize_textarea_field($_POST['content']);
                    
        // create new improvement-post
        $new_post = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'pending',
            'post_votes' => 0,
            'post_type' => 'improvement',

        );
        $post_id = wp_insert_post($new_post);
        echo __( 'vielen Dank', 'improvements' ); 
        echo '<br>';
        echo __( 'Dein Vorschlag wurde eingereicht und wird nach einer Prüfung veröffentlicht', 'improvements');            
         
        
        die();
    }

    // register custom post type "improvement"
    function register_improvement_post_type() {
        $args = array(
    		'label'                 => __( 'Verbesserungen', 'improvements' ),
    		'public'                => true,
    		'ly_queryable'          => true,
    		'exclude_from_search'   => false,
    		'show_ui'               => true,
    		'show_in_menu'          => true,
    		'has_archive'           => true,
    		'rewrite'               => true,
    		'query_var'             => true,
            'supports'              => array( 'title', 'editor', 'author', 'comments'),
            'menu_icon'             =>  'dashicons-lightbulb',
    	);
    	register_post_type( 'improvement', $args );
    }


    // add meta box
    function add_custom_meta_boxes() {
        add_meta_box(
            'improvement_infos',
            __( 'Kommentar der Fachschaft', 'improvements' ),
            array($this,'print_info_meta_box'), 
            'improvement',                      
            'normal',                           
            'high'                              
        ); 
    }

    // outputs the meta boxes in backend
    function print_info_meta_box($post){

        wp_nonce_field('save_improvement_data', 'improvement_admin_nonce');
        $admin_comment = esc_html(get_post_meta($post->ID,'admin_comment', true));
        echo "<textarea type='text' name='admin_comment' rows='10' style='width:99%'>$admin_comment</textarea><br>";
        
    }

    // saves changes from backend
    function save_from_admin($post_id) {

        if(!isset( $_POST['improvement_admin_nonce'])){
            return;
        }
        if(! wp_verify_nonce($_POST['improvement_admin_nonce'], 'save_improvement_data')){
            return;
        }
        if(!isset($_POST['admin_comment'])){
            return;
        }
        if(!current_user_can('edit_post',$post_id)){
            return;
        }

        $admin_comment = sanitize_textarea_field($_POST['admin_comment']);
        update_post_meta($post_id, 'admin_comment', $admin_comment);
   
    }
}

$plugin = Improvements::get_instance();
?>