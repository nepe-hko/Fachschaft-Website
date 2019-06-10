<?php
class Widget_Login extends WP_Widget
{
    public function __construct() 
    {
        $widget_options = array( 
          'classname' => 'login_widget',
          'description' => 'Widget for Login',
        );
        parent::__construct( 'login_widget', 'Login', $widget_options );
      
        add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
        add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
    }
    public function widget( $args, $instance ) 
    {
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );

        echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; 
      
        if ( is_user_logged_in() ) 
        {
            return '';
        }
?>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <form method = "post" action= "<?php echo wp_login_url( home_url() ); ?>" style="margin:15px;">

            <i class="material-icons" 
                style = "font-size:60px;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        vertical-align: middle;">account_circle</i>

                <input type="text" name="log"  placeholder=" Username" maxlength="40" 
                    style = "font-size:15pt;
                            border-radius:8px;
                            width: 75%; 
                            float:right;" required><br><br>

            <i class="material-icons" 
                style = "font-size:60px;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        vertical-align: middle;">lock</i>

                <input type="password" name="pwd" maxlength="40" placeholder=" Password" 
                    style = "font-size:15pt;
                            border-radius: 8px;
                            width: 75%; 
                            float:right;" required><br><br>
        
            <p>
            <input type="submit" name="sendIt" value="Login" class="btn btn-default"
                style = "width:100%;
                        border-radius:10px;
                        text-align:center;"><br>
           
            <label style="margin:-15px;">
                <p style="float: left;" ><a href="<?php echo wp_registration_url(); ?>">Sign Up!</a></p>
                <span style="float: right;" >Forgot <a href="<?php echo wp_lostpassword_url(); ?>">password?</a></span><br>
            </label>
            </p>
        </form> 
<?php 

        $username = $_POST['log'];
        $passwort = $_POST['pwd'];
      
        echo $args['after_widget'];
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


    public function form( $instance ) 
    {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : ''; 
?>
        <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
          <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
        </p>
<?php 
    }

    public function update( $new_instance, $old_instance ) 
    {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        return $instance;
    }


}

function register_login_widget() 
{ 
    register_widget( 'Widget_Login' );
}

add_action( 'widgets_init', 'register_login_widget' );