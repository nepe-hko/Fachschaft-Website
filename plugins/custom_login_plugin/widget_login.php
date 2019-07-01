<?php
if(! class_exists('Widget_Login'))
{
    class Widget_Login extends WP_Widget
    {
        //Register widget
        public function __construct() 
        {
            $widget_options = array( 
                'classname' => 'login_widget',
                'description' => 'Widget for Login',
            );

            parent::__construct( 'login_widget',        //Base ID
                                 'Login',               //Name
                                  $widget_options );    //Args
        }
        
        //Front - end display of widget
        // $args    Widget arguments
        //$instance Saved values from database
        public function widget( $args, $instance ) 
        {
            $title = apply_filters( 'widget_title', $instance[ 'title' ] );

            echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; 

            // If User is logged in
            if ( is_user_logged_in() ) 
            {
                ?>
                <form method="post" action = "<?php echo wp_logout_url( home_url() ); ?>">
                    <input id="submit_widget_logout" type="submit" name="sendIt" value="<?php _e('Logout', 'custom_login_plugin') ?>" class="btn btn-default">
                </form>
                <?php
            }
            else{ //if User is logged out
?>
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <form method = "post" action= "<?php echo wp_login_url( home_url() ); ?>" id="widget_form">

                <i id="widget_icon" class="material-icons">account_circle</i>
                <input id="log_widget" class="input_widg"type="text" name="log"  placeholder="<?php _e(' Username', 'custom_login_plugin') ?>" maxlength="40" required><br>
                    
                <i id="widget_icon" class="material-icons">lock</i>
                <input id="pwd_widget"  class="input_widg" type="password" name="pwd" maxlength="40" placeholder="<?php _e(' Passwort', 'custom_login_plugin') ?>" required><br>
                <p>
                    <input id="submit_widget" type="submit" name="sendIt" value="<?php _e('Login', 'custom_login_plugin') ?>" class="btn btn-default"><br>
           
                    <label id="label_margin">
                        <p id="reg_url_id"><a href="<?php echo wp_registration_url(); ?>"><?php _e('Registrieren!', 'custom_login_plugin') ?></a></p>
                        <span id="lost_pwd_url_id"><a href="<?php echo wp_lostpassword_url(); ?>"><?php _e('Passwort vergessen?', 'custom_login_plugin') ?></a></span><br>
                    </label>
                </p>
            </form> 
            <?php }
            echo $args['after_widget'];
        }

        //Backend widget form
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
}

//Register Widget_Login
function register_login_widget() 
{ 
    register_widget( 'Widget_Login' );
}

add_action( 'widgets_init', 'register_login_widget' );