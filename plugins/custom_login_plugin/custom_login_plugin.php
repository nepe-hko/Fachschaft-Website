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
    public function __construct(){
        add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
        add_shortcode( 'custom-login-form', array( $this, 'login_form' ) );
    }

    public function redirect_to_custom_login()
    {
        wp_redirect(home_url('login'));
    }

    private function login_form_html() {
    ?>
    <div class="login-form-container">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <form method = "post" action= "<?php echo wp_login_url( home_url() ); ?>"" style="margin:30px">

        <i class="material-icons" 
            style = "font-size:80px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    vertical-align: middle;">account_circle</i>

            <input type="text" name="log" id="user_login" placeholder=" Username" maxlength="40" 
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

            <input type="password" name="pwd" id="user_pass" maxlength="40" placeholder=" Password"  
                style = "font-size:20pt;
                        border-radius:10px;
                        width: 80%; 
                        float:right;" required><br><br>

        <p align="center">
        <input type="submit" value="Login"
            style = "width:100%;
                    border-radius:10px;
                    text-align:center"><br>
        
        <label style="margin:-15px;">
            <p  style="float:left;"><input type="checkbox" checked="checked"  name="remember" />Remember me</p>
            <span style="float: right;" >Forgot <a href="#">password?</a></span>
        </label>
    </form> 
    </div>
<?php }
    
    public function login_form() 
    {
        if ( is_user_logged_in() ) {
            return __( 'Sie sind bereits eingeloggt.', 'custom-login' );
        }
        if ( isset( $_REQUEST['redirect_to'] ) ) 
        {
            $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to']);
        }
         
        return $this->login_form_html();
    }
}

$custom_login_plugin = new Custom_Login();

