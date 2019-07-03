<?php
class wpb_widget extends WP_Widget {

function __construct() {
  parent::__construct(

  // Widget Id
  'wpb_widget',

  // Widget Name
  __('Veranstaltungskalender', 'wp_widget_domain'),

  // Widget Beschreibung
  array( 'description' => __( 'Fachschaft Veranstaltungskalender Widget', 'wp_widget_domain' ), )
  );
}

// Widget front-end

public function widget( $args, $instance ) {
  $title = apply_filters( 'widget_title', $instance['title'] );

  echo $args['before_widget'];
  if ( ! empty( $title ) )
  echo $args['before_title'] . $title . $args['after_title'];

  echo "<div class='fachschaft_calendar_plugin_widget'>";
  echo printCalendar();
  echo "</div>";
  echo $args['after_widget'];
}

// Widget Backend
public function form( $instance ) {
  if ( isset( $instance[ 'title' ] ) ) {
  $title = $instance[ 'title' ];
  }
  else {
  $title = __( 'Veranstaltungskalender', 'wp_widget_domain' );
  }
// Variabler Titel
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title );
?>" />
</p>
<?php

}

// Updating Widget Titel
public function update( $new_instance, $old_instance ) {
  $instance = array();
  $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
  return $instance;
  }
}
