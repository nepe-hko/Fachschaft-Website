<?php
/**
 * @package CustomRegistrationPlugin
 */
/*
Plugin Name: Custom Registration Plugin
Plugin URI: http://localhost:8080/wordpress
Description: Custom Registration
Version 1.0
Author: Odile
*/

if( ! defined( 'ABSPATH' ) ) {
    die;
}
class Custom_Registration
{
    public function __construct()
    {
        add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );
    }
    /**
    * Redirects the user to the custom registration page instead
    * of wp-login.php?action=register.
    */
    public function redirect_to_custom_register() 
    {
        wp_redirect( home_url( 'registrierung' ) );
        exit;
    }

}
$custom_registration_plugin = new Custom_Registration();
function registration()
{
    if(is_user_logged_in()){
        return __('<h1>Sie sind bereits eingeloggt!</h1>','personalize-login');
    }
    echo '<h3><strong>ACCOUNT ERSTELLEN</strong></h3>

    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post"> 

        <strong>Vorname:</strong>
        <input type="text" name="vorname" required><br>

        <strong>Nachname:</strong>
        <input type="text" name="nachname" required><br> 

        <strong>Username:</strong>
        <input type="text" name="username" required><br>

        <strong>Email:</strong>
        <input type="email" name="email" required><br>

        <strong>Passwort</strong>
        <input type="password" name="passwort" required><br>

        <strong>Passwort bitte nochmal eingeben</strong>
        <input type="password" name="pass_again" required><br>

        <input type="submit" name = "submit" value="Registrieren"><br>
    </form>';

    $vorname = $_POST['vorname'];
    $nachname = $_POST['nachname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];
    $pass_again =$_POST['pass_again'];

    register_user($email, $username, $vorname, $nachname, $passwort, $pass_again);
    // if(isset($_POST['submit'])){
    //     echo'<meta http-equiv="Refresh" content="0; url=http://localhost:8080/wordpress/login/">';
    // }
}


function register_user($email, $username, $vorname, $nachname, $passwort, $pass_again)
{
    if(isset($_POST['submit']))
    {
        $errors = new WP_Error();
        //see if email already exists
        if(email_exists($email))
        {
            $errors->add('email', 'Die Email existiert bereits');
        }
        //See if Username already exists
        if(username_exists($username))
        {
            $errors->add('username','Der Username wird bereits verwendet');
        }
        //See if the password is the same length
	    if(trim($passwort) != trim($pass_again))
        {
            $errors->add('password', 'Die Passwörter müssen übereinstimmen');
        }
        //Length of password
        if(strlen(trim($passwort)) < 5 )
        {
            $errors->add('password', 'Länge stimmt nicht überein');
        }
        //Maximum Length of 50 for surname and name
        if(strlen($vorname) > 50 || strlen($nachname) > 50)
        {
            $errors->add('name', 'Zu lange');
        }
        //Minimum length of Username
        if(strlen($username) > 15)
        {    
            $errors->add('username', 'Zu lang');
        }
        if(is_wp_error($errors))
        {
            foreach($errors->get_error_messages() as $error)
            {
                echo '<div>';
                echo '<strong>ERROR</strong>:';
                echo $error . '<br/>';
                echo '</div>';
            }
        }
        
        $userdata = array(
            'first_name' => $vorname,
            'last_name' => $nachname,
            'user_login' => $username,
            'user_email' => $email,
            'user_pass' => $passwort,
            'user_pass' => $pass_again,);
        $user_id = wp_insert_user($userdata);
        echo 'Sie haben sich erfolgreich registriert';
        return $user_id;
    }
}

add_shortcode('registration','registration');


