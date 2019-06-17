<?php

class symbol_widget extends WP_Widget //Widget wird nur dann angezeigt wenn man angemeldet ist
{
        // Instantiate the parent object
        public function __construct() 
        {
                $widget_options = array(
                        'classname' => 'symbol_widget',
                        'description' => __('Widget welches ein Kontaktformular anbietet', 'contactform'));

                parent::__construct( 'symbol_widget', 'Kontaktformular', $widget_options );   

                // Datenbankeintrag und Mailversand
                add_action('admin_post_do_function', array('kontaktformular', 'do_function'));

                // ajax funktion
                add_action('wp_ajax_do_function', array('kontaktformular', 'do_function'));
	}

        function widget( $args, $instance ) // Widget output
        {
                extract ($args);
                echo $before_widget;

                if (is_user_logged_in())
                {
                        $title = apply_filters('widget_title', $instance['title']);

                        if (!empty ($title))
                        {
                                echo $before_title . esc_html($title) . $after_title;
                        }
                        
                        // form ouput in frontend
                        echo '<p id="brief" style="font-size: 100%; vertical-align: top; text-align: left; margin: 2px 2px;"><b>&#9993; Schreibe eine Nachricht an uns!</b></p>';
                        ?>
                        <form id='form_logged_in' action='<?php echo esc_url(admin_url('admin-post.php'));?>' method='POST' class='ajax_logged_in' >  
                                <input type='text' name='subject_logged_in' id='subject_logged_in' maxlength='55' placeholder='Betreff*'/>			
                                <textarea id='message_logged_in' name='message_logged_in' maxlength='200' placeholder='Deine Nachricht...*'></textarea>
                                <div id='answer_logged_in'></div>
                                <button type='submit' id='submit_logged_in'>Absenden!</button>

                                <input name='action' type='hidden' value='do_function'/>
                        </form>                   
                        <?php           
                }
                echo $after_widget;
        }

	function update( $new_instance, $old_instance ) { 
                // Save widget options
                // wenn man im Backend was in der Form einträgt und speichert ist hier die funktion zuständig
                $instance = $old_instance;
                $instance['title'] = sanitize_text_field($new_instance['title']);

                return $instance;
	}

	function form( $instance ) {
        // admin für Schnittstelle zur Widget-Konfig / widget form in admin dashboard

        $args = array(
                'title' => 'Kontaktformular' //default Wert für den Titel 
        );
        $instance = wp_parse_args( (array) $instance, $args);
        $title = sanitize_text_field($instance['title']);

        //input felder
        ?>
        <p>Title:
        <input class='widefat' name='<?php echo $this->get_field_name('title'); ?>' type='text' value='<?php echo esc_attr($title); ?>' />
        </p>
        <?php    
	}
}

function kontaktformular_symbol_widget() {
	register_widget( 'symbol_widget' );
}



//hook in funktion
add_action( 'widgets_init', 'kontaktformular_symbol_widget' );



?>


