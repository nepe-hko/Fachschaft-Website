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
        add_action('add_meta_boxes', array($this, 'add_custom_meta_box'));
        add_action('save_post', array($this, 'save_from_admin'));
        add_shortcode('verbesserungsvorschlaege', array($this, 'to_frontend'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
        add_action( 'wp_ajax_request', array($this, 'request') );
        add_action( 'wp_ajax_nopriv_request', array($this, 'request') );
    }

    public function enqueue() {

        // enqueue javascript on the frontend.
        wp_enqueue_script(
            'send_form_script',
            plugins_url() . '/verbesserungsvorschlaege/public/js/send_form.js',
            array('jquery')
        );

        // add ajax url to script
        wp_localize_script(
            'send_form_script',
            'obj',
            array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
        );
    }

    public function request() {
 
        // The $_REQUEST contains all the data sent via ajax
        if ( isset($_POST['title']) && isset($_POST['content']) ) {

            # get data from frontend
            $title = sanitize_text_field($_POST['title']);
            $content = sanitize_textarea_field($_POST['content']);
                        
            # create new improvement-post
            $new_post = array(
                'post_title' => $title,
                'post_content' => $content,
                'post_status' => 'pending',
                'post_votes' => 0,
                'post_type' => 'improvement',

            );
            $post_id = wp_insert_post($new_post);
            echo "vielen Dank. <br>Dein Vorschlag wurde eingereicht und wird nach einer Prüfung veröffentlicht";            
         
        }
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
            'supports'              => array( 'title', 'editor', 'thumbnail', 'comments'),
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

        $author = get_post_meta($post->ID,'post_author', true);
        echo "<label for='author'>Username: </label>";
        echo "<label id='author' name='author'>" . esc_attr($author) . "</label><br>";  

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
        

        $args = array('post_type' => 'improvement', 'posts_per_page' => '3');
        $query = new WP_Query($args);
        if ($query->have_posts()) { ?>
            <h2>Eingereichte Verbesserungsvorschläge</h2><?php
            while ($query->have_posts()){
                $query->the_post();?>
                <h4><?php the_title();?></h1>
                <?php the_content();?>
                <h4><?php the_author();?></h4><hr>
                <?php
            } 
        }

        

        echo '
            <div id="vbv_headline"><h2>Verbesserungsvorschlag einreichen</h2></div>
            <form id="vbv_container">
                <input id="vbv_title" name="title" type="text" placeholder="Titel" required></input>
                <textarea id="vbv_content" name="content" placeholder="Deine Nachricht..." required></textarea>
                <button id="submit" type="submit">Vorschlag einreichen!</button>
            </form>
            <div id="vbv_response"></div>
        ';
        
    }
}

$plugin = Verbesserungsvorschlaege::get_instance();


?>