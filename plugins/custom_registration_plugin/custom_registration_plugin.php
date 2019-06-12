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
        add_action('wp_enqueue_scripts', array($this, 'my_enqueue'));
        add_action('admin_enqueue_scripts', array($this, 'my_enqueue')); 	

        add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );

        add_shortcode('registration',array( $this, 'registration' ) );
    }

    public function redirect_to_custom_register() 
    {
        wp_redirect( home_url( 'registrierung' ) );

        if ( $_SERVER['REQUEST_METHOD'] == 'POST'  ) 
        {
            $redirect_url = home_url( 'registrierung' );
            if ( ! get_option( 'users_can_register' ) ) 
            {
                // Registration closed, display error
                $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
            } 
            else 
            {
                $vorname = sanitize_text_field($_POST['vorname']);
                $nachname = sanitize_text_field($_POST['nachname']);
                $username = sanitize_text_field($_POST['username']);
                $email = $_POST['email'];
                $passwort = $_POST['passwort'];
                $pass_again =$_POST['pass_again'];

                $result = $this->register_user($email, $username, $vorname, $nachname, $passwort, $pass_again);

                if(is_wp_error($result))
                {
                    $error_codes = join( ',', $result->get_error_codes() );
                    $redirect_url = add_query_arg( 'register-errors', $error_codes, $redirect_url );
                }

                else
                {
                    $redirect_url = home_url( 'registrierung' );
                    $redirect_url = add_query_arg( array(
                        'registered' => $username,
                    ), $redirect_url );
                }
            }
            wp_redirect( $redirect_url );
           
            exit;
        }
    }

    public function registration()
    {
        if(is_user_logged_in())
        {
            return '<h1>Sie sind bereits registriert!</h1>';
        }
        else
        {
            if ( isset( $_REQUEST['register-errors'] ) ) 
            {
                $err = explode( ',', $_REQUEST['register-errors'] );
         
                foreach ( $err as $err_code ) 
                {
                    $errors = $this->get_error_message($err_code);
                    echo '<div style="text-align:center;">';
                    echo '<h3><strong>ERROR</strong>: ';
                    echo $errors . '</h3>';
                    echo '</div>';
                }
            }


?>          
        <h3><strong>ACCOUNT ERSTELLEN</strong></h3>

        <form action="<?php echo wp_registration_url(); ?>" method="post" id="test" class="test"> 

            <strong>Vorname:</strong>
            <input type="text" name="vorname" required><br>

            <strong>Nachname:</strong>
            <input type="text" name="nachname" required><br> 

            <strong>Username:</strong>
            <input type="text" name="username" required><br>

            <strong>Email:</strong>
            <input type="email" name="email" required><br>

            <strong>Passwort</strong>
            <input type="password" name="passwort" id="passwort" onkeyup="myFunction()" required>

            <span id="password-strength"></span>

            <meter max="4" id="password-strength-meter" ></meter>
            <strong>Passwort bitte nochmal eingeben</strong>
            <input type="password" name="pass_again" id="pass_again" required><br>

            <input type="submit"  name = "submit" value="Registrieren"  /><br>
        </form>
<?php
                $vorname = $_POST['vorname'];
                $nachname = $_POST['nachname'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $passwort = $_POST['passwort'];
               $pass_again =$_POST['pass_again'];
        }
    }


    public function register_user($email, $username, $vorname, $nachname, $passwort, $pass_again)
    {
        $userdata = array(
            'first_name' => $vorname,
            'last_name' => $nachname,
            'user_login' => $username,
            'user_email' => $email,
            'user_pass' => $passwort,
            'user_pass' => $pass_again,);
        $user_id = wp_insert_user($userdata);

        //wp_new_user_notification( $user_id, $password );

        return $user_id;
       
    }

    public function get_error_message($err)
    {
        switch($err)
        {
            case 'existing_user_email':
                return 'Diese Email existiert bereits';
            case 'existing_user_login':
                return 'Dieser Username wird bereits verwendet';
            case 'empty_user_login':
                return '
                Es kann kein Benutzer mit einem leeren Anmeldenamen erstellt werden. Stellen Sie sicher, dass Sie nur die erlaubten Zeichen verwendet haben.';
            default:
                break;
        }
        return 'Unbekannter Fehler';
    }
    function add_process_ajax()
    {
        echo 'Test';
        die();
    }
    function my_enqueue()
    {
        wp_register_script('js_snippet', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js');
        wp_enqueue_script('js_snippet');
        wp_register_script('js_meter','https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js');
        wp_enqueue_script('js_meter');

        wp_register_style('style_register', plugins_url(). '/custom_registration_plugin/css/style.css');
        wp_enqueue_style('style_register');

        wp_enqueue_script('pass_error', plugins_url(). '/custom_registration_plugin/js/pass_error.js');

        wp_localize_script( 'ajax_script', 'reg_ajax_data', array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        ));
    }
}
$reg = new Custom_Registration();

