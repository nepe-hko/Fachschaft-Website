<?php
/**
 * @package CustomLoginPlugin
 */
/*
Plugin Name: Custom Login Plugin
Plugin URI: http://localhost:8080/wordpress/login
Description: Custom Login
Version 1.0
Author: Odile
*/

if( ! defined( 'ABSPATH' ) ) {
    die;
}
require_once(plugin_dir_path(__FILE__). '/wp_limit_login_attempts.php');
require_once(plugin_dir_path(__FILE__). '/widget_login.php');

class Custom_Login extends WP_Widget
{
    public function __construct() 
    {
        add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
        add_action('wp_logout',array($this,'redirect_logout'));

        add_action('wp_enqueue_scripts', array($this,'enqueue_style'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_style'));

        add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );

        add_shortcode( 'custom-login-form', array($this,'login_form_html') );
        //error_log('odile');
        
    }

    function redirect_logout() 
    {
        $login_url  = home_url();
        wp_redirect($login_url . "?login=false");
        exit;
    }
    

    public function redirect_to_custom_login()
    {
        wp_redirect(home_url('login'));
    }

    function maybe_redirect_at_authenticate( $user, $username, $password ) 
    {
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) 
        {
            if ( is_wp_error( $user ) ) 
            {
                $error_codes = join( ',', $user->get_error_codes() );
     
                $login_url = home_url('login');
                $login_url = add_query_arg( 'login', $error_codes, $login_url );
     
                wp_redirect( $login_url );
                exit;
            }
        }
        return $user;
    }

    function login_form_html() 
    {
        if ( is_user_logged_in() ) 
        {
            return '<h1>Sie sind bereits eingeloggt.</h1>';
        }

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
            echo '<div style="text-align:center;">';
            echo '<h3><strong>ERROR</strong>: ';
            echo $error . '</h3>';
            echo '</div>';
        }
?>

        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <form method = "post" action= "<?php echo wp_login_url( home_url() ); ?>" id="login_form">
        <div>
            <i id="login_icon" class="material-icons">account_circle</i>
            <input id="input_login" type="text" name="log"  placeholder=" Username" maxlength="40" required><br><br>
    
            <i id="login_icon" class="material-icons" >lock</i>
            <input id="input_login" type="password" name="pwd" maxlength="40" placeholder=" Password" required><br><br>
             
            <p>
            <input id="submit" type="submit" name="sendIt" value="Login" class="btn btn-default"><br>
            
            <label style="margin:-15px;">
                <p style="float: left;" ><a href="<?php echo wp_registration_url(); ?>">Sign Up!</a></p>
                <span style="float: right;" >Forgot <a href="<?php echo wp_lostpassword_url(); ?>">password?</a></span><br>
            </label>
            </p>
        </form> 
<?php 
    }


    function get_error_message($err)
    {
        switch($err)
        {
            case 'invalid_username':
                return 'Ungültiger Username';
            case 'incorrect_password':
                return 'Ungültiges Passwort';
            default:
                break;
        }
        return 'Unbekannter Error';
    }
    function enqueue_style()
    {
        wp_register_style('style_login', plugins_url(). '/custom_login_plugin/css/style_login.css');
        wp_enqueue_style('style_login');
    }


}

$custom_login_plugin = new Custom_Login();




