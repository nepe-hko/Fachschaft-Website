<?php
class symbol_widget extends WP_Widget 
{
        // Instanziiert das Eltern Object
        public function __construct() 
        {
                $widget_options = array(
                        'classname' => 'symbol_widget',                                                                         //selber Name wie die Klasse
                        'description' => __( 'Widget welches ein Kontaktformular anbietet', 'kontaktformular' ) );

                parent::__construct( 'symbol_widget', __( 'Kontaktformular', 'kontaktformular' ), $widget_options );   

                // Datenbankeintrag und Mailversand
                add_action( 'admin_post_do_function', array( 'kontaktformular', 'do_function' ) );

                // Ajax Funktion
                add_action( 'wp_ajax_do_function', array( 'kontaktformular', 'do_function' ) );
	}

        function widget( $args, $instance )                                                                                     // Widget Output in Frontend 
        {
                extract ( $args );
                echo $before_widget;

                if ( is_user_logged_in() )                                                                                      
                {
                        $title = apply_filters( 'widget_title', $instance['title'] );

                        if ( !empty ( $title ) )
                        {
                                echo $before_title . esc_html_e( $title ) . $after_title;
                        }
                        
                        
                        ?><p id="brief"><b>&#9993; <?php _e( 'Schreibe eine Nachricht an uns!', 'kontaktformular' ); ?></b></p>
                        <form id='form_logged_in' action='<?php echo esc_url( admin_url( 'admin-post.php' ) );?>' method='POST' class='ajax_logged_in' >  
                                <input type='text' name='subject_logged_in' id='subject_logged_in' maxlength='55' placeholder='<?php esc_html_e( 'Betreff *', 'kontaktformular' ); ?>'/>			
                                <textarea id='message_logged_in' name='message_logged_in' maxlength='200' placeholder='<?php esc_html_e( 'Deine Nachricht... *','kontaktformular' ); ?>'></textarea>
                                <div id='answer_logged_in'></div>
                                <button type='submit' id='submit_logged_in'><?php _e( 'Absenden!', 'kontaktformular' ); ?></button>
                                <input name='action' type='hidden' value='do_function'/>
                        </form><?php           
                }
                if ( !is_user_logged_in() )                                                                                     // Beschreibung mit Link zur einer Seite  
                {       
                        ?><a href='http://localhost/wordpress/kontaktformular/' >
                                <p id='brief_logged_in'><b>&#9993; <?php _e( 'Schreibe eine Nachricht an uns!', 'kontaktformular' ); ?><b></p>
                        </a><?php
                }
                echo $after_widget;
        }

        function update( $new_instance, $old_instance )                                                                         // Aktualisiert Titel im Widget-Backend
        { 
                $instance = $old_instance;
                $instance['title'] = sanitize_text_field( $new_instance['title'] );

                return $instance;
	}

        function form( $instance )                                                                                              // Struktur in der Widget-Konfig
        {                                                                                   
                $args = array(
                        'title' => __( 'Kontaktformular', 'kontaktformular' )                                                   //default Wert für den Titel 
                );
                $instance = wp_parse_args( (array) $instance, $args );
                $title = sanitize_text_field( $instance['title'] );

                ?><p><?php _e( 'Titel:', 'kontaktformular' );
                        ?><input class='widefat' name='<?php echo $this->get_field_name( 'title' ); ?>' type='text' value='<?php esc_attr_e( $title, 'kontaktformular' ); ?>' />
                </p><?php    
	}
}

function kontaktformular_symbol_widget() {
	register_widget( 'symbol_widget' );
}

add_action( 'widgets_init', 'kontaktformular_symbol_widget' );
?>