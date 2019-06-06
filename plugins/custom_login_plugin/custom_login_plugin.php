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

class Custom_Login
{
    public function __construct()
    {
        add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
        add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
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
     
                $login_url = home_url( 'login' );
                $login_url = add_query_arg( 'login', $error_codes, $login_url );
     
                wp_redirect( $login_url );
                exit;
            }
        }
        return $user;
    }
}

$custom_login_plugin = new Custom_Login();

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
            $errors [] = get_error_message($err_code);
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
    <form method = "post" action= "<?php echo wp_login_url( home_url() ); ?>" style="margin:30px">

        <i class="material-icons" 
            style = "font-size:80px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    vertical-align: middle;">account_circle</i>

            <input type="text" name="log"  placeholder=" Username" maxlength="40" 
                style = "font-size:20pt;
                        border-radius:10px;
                        width: 80%; 
                        float:right;" required><br><br>

        <i class="material-icons" 
            style = "font-size:80px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    vertical-align: middle;">lock</i>

            <input type="password" name="pwd" maxlength="40" placeholder=" Password" 
                style = "font-size:20pt;
                        border-radius:10px;
                        width: 80%; 
                        float:right;" required><br><br>
        
        <p>
        <input type="submit" name="sendIt" value="Login" class="btn btn-default"
            style = "width:100%;
                    border-radius:10px;
                    text-align:center;"><br>

        <label style="margin:-15px;">
            <p  style="float:left;"><input type="checkbox" checked="checked"  name="remember" />Remember me</p>
            <span style="float: right;" >Forgot <a href="<?php echo wp_lostpassword_url(); ?>">password?</a></span><br>
        </label>
        </p>
    </form> 
    <?php 

    $username = $_POST['log'];
    $passwort = $_POST['pwd'];
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

add_shortcode( 'custom-login-form', 'login_form_html' );

