<?php
/**
 * @package CustomRegistrationPlugin
 */
/*
Plugin Name: Custom Registration Plugin
Plugin URI: http://localhost:8080/wordpress/wp-admin/plugins.php
Description: Custom Registration
Version 1.0
Text Domain: custom_registration_plugin
Domain Path: /languages
Author: Odile
*/

if( ! defined( 'ABSPATH' ) ) {
    die;
}

require_once(plugin_dir_path(__FILE__). '/cpt_register.php');

function registration_load_textdomain() 
{
    load_plugin_textdomain( 'custom_registration_plugin', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}
  
add_action( 'plugins_loaded', 'registration_load_textdomain' );

if( ! class_exists('Custom_Registration'))
{
    class Custom_Registration
    {
        public function __construct()
        {
            add_action('wp_enqueue_scripts', array($this, 'my_enqueue')); //proper hook to use when enqueuing items that are meant to appear on the front end
            add_action('admin_enqueue_scripts', array($this, 'my_enqueue')); //first action hooked into the admin scripts actions 	

            add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) ); //Fires before a specified login form action.

            add_action('admin_post_register_user', array($this, 'register_user'));      //logged in
            add_action('admin_post_nopriv_register_user', array($this, 'register_user')); //logged out

            add_action( 'wp_ajax_register_user', array($this,'register_user') ); //logged in
            add_action('wp_ajax_nopriv_register_user', array($this, 'register_user')); //logged out
        
            add_shortcode('registration',array( $this, 'registration' ) ); //Handler for Shortcode
        }

        public function redirect_to_custom_register() 
        {
            wp_redirect( home_url( 'registrierung' ) ); //redirect to '/registrierung'
        }

        public function register_user()
        {
            if( $_SERVER['REQUEST_METHOD'] == 'POST'  )
            {
                //sanitize input and store in variable
                $vorname = sanitize_text_field($_POST['vorname']);
                $nachname = sanitize_text_field($_POST['nachname']);
                $username = sanitize_text_field($_POST['username']);
                $email = sanitize_email($_POST['email']);
                $passwort = $_POST['passwort'];
                $pass_again =$_POST['pass_again'];
                $role = $_POST['role'];

                if(username_exists($username)) //checks if Usernme is already in use
                {
                    $message = __('<strong>ERROR: </strong>Der Username "', 'custom_registration_plugin') .$username. __('" existiert bereits.', 'custom_registration_plugin' );
                }
                elseif(email_exists($email)) //Does Email already exist in database
                {
                    $message = __('<strong>ERROR: </strong>Die Email "', 'custom_registration_plugin') .$email. __('" wird bereits verwendet.', 'custom_registration_plugin' );
                }
                elseif(trim($passwort) != trim($pass_again)) //trims Passwords and checks if they are the same
                {
                    $message = __('<strong>ERROR: </strong>Passwörter müssen übereinstimmen', 'custom_registration_plugin' );
                }
                else
                {
                    //stores data in variable
                    $userdata = array
                    (
                        'first_name' => $vorname,
                        'last_name' => $nachname,
                        'user_login' => $username,
                        'user_email' => $email,
                        'user_pass' => $passwort,
                        'role' => $role
                    );
            
                    //stores data in variable
                    $args = array(
                        'post_type' => 'register-cpt',
                        'post_status'   => 'publish',
                        'meta_input' => array(
                            '_vorname_value_key' => $vorname,
                            '_nachname_value_key' => $nachname,
                            '_email_value_key' => $email,
                            '_username_value_key' => $username,
                            '_role_value_key' => $role
                        )
                    );
                
                    $post_id = wp_insert_post($args); //inserts User in Post
                    $user_id = wp_insert_user($userdata); //inserts User in database

                    $message = __('Sie haben sich erfolgreich registriert!', 'custom_registration_plugin' );
                }

                echo $message;
            }

            wp_die(); //end function
        }

        public function registration()
        {
            if(is_user_logged_in()) //if User is logged in return message
            {
                $loggedin = __('<h1>Sie sind bereits registriert!</h1>', 'custom_registration_plugin' );
                return $loggedin;
            }

            //return HTML form
            return '          
            <strong><div id="msg" class="msg"></div></strong><br><br>

            <form  id="reg_ajax_id" action="' . esc_url(admin_url('admin-post.php')) . ' " method="post" class="reg_ajax_class" > 
                
                <h3><strong>ACCOUNT ERSTELLEN</strong></h3>

                <strong>' . __('Vorname:', 'custom_registration_plugin' ) . '</strong>
                <input type="text" name="vorname" id ="vorname" required><br>

                <strong>' . __('Nachname:', 'custom_registration_plugin' ) . '</strong>
                <input type="text" name="nachname" id ="nachname" required><br> 

                <strong>' . __('Username:', 'custom_registration_plugin' ) . '</strong>
                <input type="text" name="username" id ="username" required><br>

                <strong>' . __('Email:', 'custom_registration_plugin' ) .'</strong>
                <input type="email" name="email" id ="email" required><br>

                <strong>' . __('Passwort:', 'custom_registration_plugin' ) . '</strong>
                <input type="password" name="passwort" id="passwort" required>

                <span id="password-strength"></span>

                <meter max="4" id="password-strength-meter" ></meter>
                <strong>' . __('Passwort bitte nochmal eingeben:', 'custom_registration_plugin' ) . '</strong>
                <input type="password" name="pass_again" id="pass_again" required><br>

                <input type="hidden" name="action" value="register_user" />   
                <input type="submit" name = "submit" value="' . __('Registrieren', 'custom-registration-plugin') .'"  /><br>

                <input type="hidden" name="role" value="subscriber" >
             </form>

            ';
        }

        function my_enqueue()
        {
            wp_register_script('js_meter','/wp-includes/js/zxcvbn-async.js', array('zxcvbn-async'));
            wp_enqueue_script('js_meter');

            wp_enqueue_script('ajax', plugins_url(). '/custom_registration_plugin/js/pass_error.js',array('jquery'));
            wp_localize_script( 'ajax', 'reg_ajax_data', 
            array('ajaxurl' => admin_url( 'admin-ajax.php' ),
            ));

            wp_enqueue_style('style_register', plugins_url(). '/custom_registration_plugin/css/style.css'); //enqueues CSS file  
        }
    }
}
$reg = new Custom_Registration();

