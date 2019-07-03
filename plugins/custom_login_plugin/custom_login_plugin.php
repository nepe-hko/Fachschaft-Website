<?php
/**
 * @package CustomLoginPlugin
 */
/*
Plugin Name: Custom Login Plugin
Plugin URI: http://localhost:8080/wordpress/login
Description: Custom Login
Version 1.0
Text Domain: custom_login_plugin
Domain Path: /languages
Author: Odile
*/

if( ! defined( 'ABSPATH' ) ) 
{
    die;
}

require_once(plugin_dir_path(__FILE__). '/widget_login.php');

//loads textdomain
function login_load_textdomain() 
{
    load_plugin_textdomain( 'custom_login_plugin', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'login_load_textdomain' );

if( ! class_exists('Custom_Login'))
{
    class Custom_Login extends WP_Widget
    {
        public function __construct() 
        {
            add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );   //Fires before a specified login form action
            add_action('wp_logout',array($this,'redirect_logout' ) );   //triggered when a user logs out using the wp_logout() function

            add_action('wp_enqueue_scripts', array($this,'enqueue_style' ) ); //Frontend
            add_action('admin_enqueue_scripts', array($this, 'enqueue_style' ) ); //Backend

            add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 ); //Filters whether a set of user login credentials are valid

            add_shortcode( 'custom-login-form', array($this,'login_form_html' ) ); //Shortcode
        }

        public function redirect_to_custom_login()
        {
            wp_redirect(home_url('login'));
        }

        function redirect_logout() 
        {
            $login_url  = home_url();
            wp_redirect($login_url . "?loggedout=true");
            exit;
        }

        function maybe_redirect_at_authenticate( $user, $username, $password ) //Redirect the user after authentication if there were any errors
        {
            //collect error codes and attach them to redirect URL
            if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) 
            {
                if ( is_wp_error( $user ) ) 
                {
                    $error_codes = join( ',', $user->get_error_codes() ); 
     
                    $login_url = home_url( 'login' );
                    $login_url = add_query_arg( 'login', $error_codes, $login_url );
     
                    wp_redirect( $login_url );
                    exit;
                }
            }
            return $user;
        }


        function login_form_html() 
        {
            if ( is_user_logged_in() ) //If User is logged in display message
            {
                $html = __('<h1>Sie sind bereits eingeloggt.</h1>', 'custom_login_plugin');
                return $html;
            }

            //Error messages
            $errors = array();
            if ( isset( $_REQUEST['login'] ) ) 
            {
                $err = explode( ',', $_REQUEST['login'] );
     
                foreach ( $err as $err_code ) 
                {
                    $errors [] = $this->get_error_message($err_code);
                }
            }

            foreach($errors as $error)
            {
                $error_html = '<div id="style_error"> 
                 <h3><strong>ERROR</strong>: 
                 '. $error .' </h3>
                 </div>';
            }

            return ''. $error_html .'
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <form method = "post" action= "' . esc_url(wp_login_url( home_url() )) . '" id="login_form">
            
                <i id="login_icon" class="material-icons">account_circle</i>
                <input id="log_login" type="text" name="log"  placeholder="' . __(' Username', 'custom_login_plugin') . '" maxlength="40" required><br><br>
    
                <i id="login_icon" class="material-icons" >lock</i>
                <input id="pwd_login" type="password" name="pwd" maxlength="40" placeholder="' . __(' Passwort', 'custom_login_plugin') . '" required><br><br>
             
                <p>
                    <input id="submit_login" type="submit" name="sendIt" value="' . __('Login', 'custom_login_plugin') . '" class="btn btn-default"><br>
            
                    <label id="label_margin">
                        <p id="reg_url_id"><a href="' . wp_registration_url() . '">' . __('Registrieren!', 'custom_login_plugin') . '</a></p>
                        <span id="lost_pwd_url_id"><a href="' . wp_lostpassword_url() . '">' . __('Passwort vergessen?', 'custom_login_plugin') . '</a></span><br>
                    </label>
                </p>
            </form> ';
        }

        function get_error_message($err) //Finds and returns a matching error message for the given error code
        {
            switch($err)
            {
                case 'invalid_username':
                    return __('Ungültiger Username', 'custom_login_plugin');
                case 'incorrect_password':
                    return __('Ungültiges Passwort',  'custom_login_plugin');
                default:
                    break;
            }
            return __('Unbekannter Error', 'custom_login_plugin');
        }

        function enqueue_style()
        {
            wp_enqueue_style('style_login', plugins_url(). '/custom_login_plugin/css/style_login.css');
        }
    }
}
$custom_login_plugin = new Custom_Login();




