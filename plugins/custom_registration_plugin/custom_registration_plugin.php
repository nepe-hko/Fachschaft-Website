<?php
/**
 * @package CustomRegistrationPlugin
 */
/*
Plugin Name: Custom Registration Plugin
Plugin URI: http://localhost:8080/wordpress/wp-admin/plugins.php
Description: Custom Registration
Version 1.0
Author: Odile
*/

if( ! defined( 'ABSPATH' ) ) {
    die;
}

require_once(plugin_dir_path(__FILE__). '/cpt_register.php');

class Custom_Registration
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'my_enqueue'));
        add_action('admin_enqueue_scripts', array($this, 'my_enqueue')); 	

        add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );

        add_action('admin_post_test_ajax', array($this, 'test_ajax'));
        add_action('admin_post_nopriv_test_ajax', array($this, 'test_ajax'));

        add_action( 'wp_ajax_test_ajax', array($this,'test_ajax') );
        add_action('wp_ajax_nopriv_test_ajax', array($this, 'test_ajax'));
        
        add_shortcode('registration',array( $this, 'registration' ) );
    }

    public function redirect_to_custom_register() 
    {
        wp_redirect( home_url( 'registrierung' ) );
    }

    public function test_ajax()
    {
        if( $_SERVER['REQUEST_METHOD'] == 'POST'  )
        {
            $vorname = sanitize_text_field($_POST['vorname']);
            $nachname = sanitize_text_field($_POST['nachname']);
            $username = sanitize_text_field($_POST['username']);
            $email = sanitize_email($_POST['email']);
            $passwort = $_POST['passwort'];
            $pass_again =$_POST['pass_again'];
            $role = $_POST['role'];

            if(username_exists($username))
            {
                $message = 'Der Username "'.$username.'" existiert bereits.';
            }
            elseif(email_exists($email))
            {
                $message = 'Die Email "'.$email.'" wird bereits verwendet.';
            }
            elseif(trim($passwort) != trim($pass_again))
            {
                $message = 'Passwörter müssen übereinstimmen';
            }
            else
            {
                $userdata = array
                (
                    'first_name' => $vorname,
                    'last_name' => $nachname,
                    'user_login' => $username,
                    'user_email' => $email,
                    'user_pass' => $passwort,
                    'role' => $role
                );
            
                $args = array(
                    'post_type' => 'reg',
                    'post_status'   => 'publish',
                    'post_author'  => get_current_user_id(),
                    'meta_input' => array(
                        '_vorname_value_key' => $vorname,
                        '_nachname_value_key' => $nachname,
                        '_email_value_key' => $email,
                        '_username_value_key' => $username
                    )
                );
                
                $post_id = wp_insert_post($args);
                $user_id = wp_insert_user($userdata);

                $message = 'Sie haben sich erfolgreich registriert!';
            }

            echo $message;
        }

        wp_die();
    }

    public function registration()
    {
        if(is_user_logged_in())
        {
            return '<h1>Sie sind bereits registriert!</h1>';
        }
?>          
        <strong><div id="msg" class="msg"></div></strong><br><br>

        <form  id="reg_ajax_id" action="<?php echo admin_url('admin-post.php'); ?>" method="post" class="reg_ajax_class" > 
                
            <h3><strong>ACCOUNT ERSTELLEN</strong></h3>

            <strong>Vorname:</strong>
            <input type="text" name="vorname" id ="vorname" required><br>

            <strong>Nachname:</strong>
            <input type="text" name="nachname" id ="nachname" required><br> 

            <strong>Username:</strong>
            <input type="text" name="username" id ="username" required><br>

            <strong>Email:</strong>
            <input type="email" name="email" id ="email" required><br>

            <strong>Passwort</strong>
            <input type="password" name="passwort" id="passwort" required>

            <span id="password-strength"></span>

            <meter max="4" id="password-strength-meter" ></meter>
            <strong>Passwort bitte nochmal eingeben</strong>
            <input type="password" name="pass_again" id="pass_again" required><br>

            <strong>Rolle</strong>
            <select name="role" id="role">
			
                <option selected='selected' value='subscriber'>Abonnent</option>
                <option value='contributor'>Mitarbeiter</option>
                <option value='author'>Autor</option>
                <option value='editor'>Redakteur</option>
                <!-- <option value='administrator'>Administrator</option>			 -->
            
            </select>

            <a href=" " style="vertical-align: top;" title="Hier ist ein grober Überblick über die verschiedenen Benutzerrollen und die jeweils damit verknüpften Berechtigungen:

&bull;Abonnenten können nur Kommentare lesen und abgeben, aber keine eigenen Inhalte erstellen.
&bull;Mitarbeiter können eigene Beiträge schreiben und bearbeiten, sie jedoch nicht veröffentlichen. Auch dürfen sie keine Dateien hochladen.
&bull;Autoren können ihre eigenen Beiträge veröffentlichen und verwalten, und auch Dateien hochladen.
&bull;Redakteure können Beiträge und Seiten veröffentlichen und verwalten, und auch die Beiträge, Seiten, etc. von anderen Benutzern verwalten.
&bull;Administratoren haben vollen Zugriff auf alle administrativen Funktionen.">&#63;</a><br><br>
            <input type='hidden' name='action' value='test_ajax' />   
            <input type="submit"  name = "submit" value="Registrieren"  /><br>
        </form>

        <script type="text/javascript">
            var password = document.getElementById('passwort'); 
            var meter = document.getElementById('password-strength-meter');

            password.addEventListener('input', function() 
            {
                var val = password.value;
                var result = zxcvbn(val);

                // Update the password strength meter
                meter.value = result.score;
                },false);
        </script>
<?php
    }


    
    function my_enqueue()
    {

        wp_register_script('js_meter','https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js');
        wp_enqueue_script('js_meter');

        wp_register_script('ajax', plugins_url(). '/custom_registration_plugin/js/pass_error.js',array('jquery'));
        wp_enqueue_script('ajax');
        wp_localize_script( 'ajax', 'reg_ajax_data', 
        array('ajaxurl' => admin_url( 'admin-ajax.php' )));

        wp_register_style('style_register', plugins_url(). '/custom_registration_plugin/css/style.css');
        wp_enqueue_style('style_register');
    }
}
$reg = new Custom_Registration();

