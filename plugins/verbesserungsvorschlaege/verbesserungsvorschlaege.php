<?php
/*
Plugin Name: verbesserungsvorschlaege
Version: 1.0
Author: Daniel Hauk
Description: Einreichen von Verbesserungsvorschlägen
*/


class Verbesserungsvorschlaege
{

    private static $instance;

    public static function get_instance(){
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    private function __construct() {
        add_action('init', array($this, 'register_improvement_post_type'));
        add_action('init', array($this,'add_scripts'));
        add_action('add_meta_boxes', array($this, 'add_custom_meta_box'));
        add_action('save_post', array($this, 'save_from_admin'));
        add_shortcode('verbesserungsvorschlaege', array($this, 'to_frontend'));
        
    }

    public function add_scripts() {
        wp_enqueue_style('verbesserungsvorschlaege', plugins_url( 'css/verbesserungsvorschlaege.css', __FILE__ ));

        wp_enqueue_script('jQuery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js');

        wp_enqueue_script(
            'jsforwp_frontend-js',
            plugins_url('public/js/send_form.js', __FILE__)
        );
        wp_localize_script(
            'jsforwp-frontend-js',
            'jsforwp_globals',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'     => wp_create_nonce('nonce_name')
            ]
        );
        add_action('wp_ajax_jsforwp_add_like', array($this, 'jsforwp_add_like'));
    }

    public function jsforwp_add_like() {
        check_ajax_referer('nonce_name');
        $response['custom'] = "Do something custom";
        $response['success'] = true;

        $response = json_encode($response);
        echo $response;
        die();
    }



    # register custom post type "improvement"
    public function register_improvement_post_type() {
        $args = array(
    		'label'                 => 'Verbesserungen',
    		'public'                => true,
    		'publicly_queryable'    => true,
    		'exclude_from_search'   => false,
    		'show_ui'               => true,
    		'show_in_menu'          => true,
    		'has_archive'           => true,
    		'rewrite'               => true,
    		'query_var'             => true,
            'supports'              => array( 'title', 'editor', 'thumbnail'),
            'menu_icon'             =>  'dashicons-lightbulb',
    	);
    	register_post_type( 'improvement', $args );
    }


    # add meta box
    public function add_custom_meta_box() {
        add_meta_box(
            'improvement_infos',                # $id
            'Info',                             # $title 
            array($this,'print_info_meta_box'), # $callback
            'improvement',                      # $page
            'normal',                           # $context
            'high'                              # $priority
        ); 
    }


    # outputs the meta boxes in backend
    public function print_info_meta_box($post){

        $username = get_post_meta($post->ID,'post_username', true);
        echo "<label for='username'>Username: </label>";
        echo "<label id='username' name='username'>" . esc_attr($username) . "</label><br>";  

        $verified = get_post_meta($post->ID,'post_verified', true);
        echo "<label for='verified'>Geprüft: </label>";
        echo "<input type='checkbox' id='verified' name='verified' ". esc_attr($verified) . "></input><br>";
        
        $votes = get_post_meta($post->ID,'post_votes', true);
        echo "<label for='votes'>Anzahl Votes: </label>";
        echo "<label id='votes' name='votes'>" . esc_attr($votes) . "</label><br>";  
    }


    # saves changes from backend
    public function save_from_admin($post_id) {

        $verified = isset($_POST['verified']) ? "checked" : "";
        update_post_meta($post_id, 'verified', $verified);
    }

    public function to_frontend() {
        return '
            <form class="improvement">
                <input name="title" type="text"  placeholder="Titel" required></input>
                <textarea name="content" placeholder="Deine Nachricht..." required></textarea>
                <button id="submit" type="submit">Vorschlag einreichen!</button>
            </form>
            <div id="res"></div>
        ';
        
    }
}

$plugin = Verbesserungsvorschlaege::get_instance();


?>