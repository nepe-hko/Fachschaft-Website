<?php

/**
*  @package FachschaftCalendarPlugin
*/
/*
Plugin Name: Veranstaltungskalender
Plugin URI:
Description: Dies ist ein Veranstaltungskalender bestehend aus einer Kalenderübersich und einer Veranstaltungsliste. Um Icons anzeigen zu können, ist die Aktivierung des Font Awesome Plugins nötig.
Version: 1.0.0
Author: Lena Kallenbach
Author URI:
License: GPLv2 or later
Text Domain: fachschaft-calendar-plugin
*/


if(!defined('ABSPATH'))
{
  die;
}


//CalendarPlugin class
require plugin_dir_path( __FILE__ ) . 'calendar-plugin-fachschaft-calendar-plugin.php';

if(class_exists('CalendarPlugin'))
{
  $fachschaftCalendarPlugin = CalendarPlugin::get_instance(); // Create instance

}

//activation
register_activation_hook(__FILE__, array($fachschaftCalendarPlugin, 'activate'));

//deactivation
register_deactivation_hook(__FILE__, array($fachschaftCalendarPlugin, 'deactivate'));


add_action( 'wp_enqueue_scripts', 'fachschaft_calendar_add_stylesheet' );

function fachschaft_calendar_add_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'fachschaft-calendar-plugin-styles-css', plugins_url('/css/fachschaft_calendar_plugin_styles.css', __FILE__));
    wp_enqueue_style('fachschaft-calendar-plugin-styles-css');
    //fontawesome.min.css
    wp_register_style( 'font-awesome-css', plugins_url('/css/fontawesome.min.css', __FILE__));
    wp_enqueue_style('font-awesome-css');

}

function add_date_picker(){
  //jQuery UI datepicker file
  wp_enqueue_script('jquery-ui-datepicker');
  //custom datepicker js
  wp_enqueue_script('custom-datepicker', plugins_url().'/fachschaft-calendar-plugin/js/datepicker.js', array('jquery'));
  //jQuery UI theme css file
  wp_enqueue_style('e2b-admin-ui-css','http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css');

}
//frontend: wp_enqueue_scripts
//backend: admin_enqueue_scripts
add_action('admin_enqueue_scripts', 'add_date_picker');
add_action('wp_enqueue_scripts', 'add_date_picker');



// calendar meta box

function calendar_add_meta_box(){
  // activate custom metabox
  add_meta_box('calendar_event', 'Veranstaltung', 'calendar_event_callback', 'calendar_post_type', 'advanced', 'high');
}
function calendar_event_callback($post){
  //nonce for security
  wp_nonce_field('save_calendar_event_data', 'calendar_event_meta_box_nonce');
  // collect value
  //treu if single value, else false
  $value = get_post_meta($post->ID,'_calendar_event_value_key', true);
  echo '<label for="calendar_event_field">Datum: </lable>';
  echo "<input type='text' id='datepicker' name='calendar_event_field' value='". esc_attr($value) ."' size='25'/>";

}

function save_calendar_event_data($post_id){
    //security test
    if(!isset( $_POST['calendar_event_meta_box_nonce'])){
      return;
    }
    if(! wp_verify_nonce($_POST['calendar_event_meta_box_nonce'], 'save_calendar_event_data')){
      return;
    }
    //permission?
    if(!current_user_can('edit_post',$post_id)){
      return;
    }
    //have post value?
    if(!isset($_POST['calendar_event_field'])){
      return;
    }
    $calendar_event_data = sanitize_text_field($_POST['calendar_event_field']);
    update_post_meta($post_id, '_calendar_event_value_key', $calendar_event_data);
}


//CreateCalendar class
require plugin_dir_path( __FILE__ ) . 'create-calendar-fachschaft-calendar-plugin.php';

function printCalendar(){

  $events =array();

  $all_post_ids = get_posts(array(
    'fields'          => 'ids',
    'posts_per_page'  => -1,
    'post_type' => 'calendar_post_type'
  ));
  $event_dates = array();
  $post_ids = array_unique($all_post_ids);
  foreach ($post_ids as $value) {
    array_push($event_dates,get_post_meta($value,'_calendar_event_value_key'));
  }

  //get timestajmp
  for ($i=0; $i < sizeof($event_dates); $i++) {
    array_push($events, array($event_dates[$i][0], strtotime($event_dates[$i][0])));
  }
  foreach ($events as $key => $node) {
   $eventsort[$key]    = $node[1];
  }
  //sort date events
  array_multisort($eventsort, SORT_ASC, $events);

  //get current date
  $time = current_time( 'mysql' );
  list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $time );
  // echo $today_month;

  $calendar = new CreateCalendar($today_month,$today_year);


  return $calendar->markeDay($events);
}

add_shortcode('calendar', 'printCalendar');

function printEvents() {
  setlocale(LC_TIME, "de_DE");
  global $wpdb;
  $query = $wpdb->get_results("SELECT post_id,meta_value, post_title, post_content, wp_terms.name as category,  wp_terms.slug as slug FROM `wp_postmeta` JOIN `wp_posts` ON wp_postmeta.post_id=wp_posts.ID
    JOIN `wp_term_relationships` ON wp_postmeta.post_id=wp_term_relationships.object_id
    JOIN `wp_terms` ON wp_term_relationships.term_taxonomy_id = wp_terms.term_id
    WHERE meta_key = '_calendar_event_value_key' ORDER BY meta_value;");
  $events = array();
  foreach ($query as $value) {
    $string= $value->meta_value;
    $res = str_replace(".", "", $string);
    array_push($events, array(strtotime($value->meta_value),$value->meta_value, $value->post_title,$value->post_content,$res, $value->post_id, $value->category,$value->slug));
  }


  foreach ($events as $key => $node) {
   $eventsort[$key]    = $node[0];
  }
  //sort date events
  array_multisort($eventsort, SORT_ASC, $events);


  $time = current_time( 'mysql' );
  list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $time );

  //split .$events[$i][1]
  $event_month = array();
  for ($i=0; $i <sizeof($events) ; $i++) {
    $str_arr = explode (".", $events[$i][1]);
    array_push($event_month, $str_arr[1]);
  }
  //generate $output
  $output = '<div class="fachschaft_calendar_plugin_event_list">';

  $month_name = date_i18n( 'F', false, false);
  $output .= '<h1>'.$month_name.'</h1>';

  // check if font awesome plugin is activated
  include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

  $activated = False;
  if ( is_plugin_active( 'font-awesome/font-awesome.php' ) ) {
    $activated = True;
  }

  for ($i=0; $i < sizeof($events); $i++) {
    if ($events[$i][0] >= $current_timestamp = time()) {

      if ($event_month[$i] != $event_month[$i-1] && $event_month[$i-1] != NULL)  {
        setlocale(LC_TIME, "de_DE");
        $month_name = strftime("%B", $events[$i][0]);
        $output .= '<h1>'.$month_name.'</h1>';
      }
      $output .= '<div id="' .$events[$i][4].'_scrollPos">';
      $output .= '<h2>'.$events[$i][2];

      //add Icon for Category if Font Awesome Plugin is activated
      if ($activated) {
        if ($events[$i][7] == "vortrag") {
          $output .= '<i class="fas fa-comments"></i></h2>';

        }
        elseif ($events[$i][7] == "spiel-spass") {
            $output .= '<i class="fas fa-dice"></i></h2>';
        }
        elseif ($events[$i][7] == "party") {
            $output .= '<i class="fas fa-beer"></i></h2>';
        }
        else {
            $output .= '<i class="fas fa-calendar-alt"></i></h2>';
        }
      }
      else {
          $output .= '</h2>';
      }

      $output .= '<h3>'.' am '.$events[$i][1].'</h3>';

      //category
      $output .= '<text>'.$events[$i][6] .'</br> </br></text>';

      $output .= '<text>'.$events[$i][3] .'</text>';
      $output .= '</div>';
      $output .= '</br></br>';


    }


  }
  $output .= '</div>';

  return $output;
}

add_shortcode('events', 'printEvents');

// Register and load the widget
function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

// Creating the widget
class wpb_widget extends WP_Widget {

function __construct() {
parent::__construct(

// Base ID of your widget
'wpb_widget',

// Widget name will appear in UI
__('Veranstaltungskalender', 'wp_widget_domain'),

// Widget description
array( 'description' => __( 'Fachschaft Veranstaltungskalender Widget', 'wp_widget_domain' ), )
);
}

//widget front-end

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );

echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
echo "<div class='fachschaft_calendar_plugin_widget' href='http://localhost/wordpress/veranstaltungen/'>";

?>
<script>
  jQuery(document).ready(function() {
    jQuery( ".fachschaft_calendar_plugin_widget" ).bind('click', function() {

      jQuery(location).attr('href','http://localhost/wordpress/veranstaltungen/');
      jQuery(".fachschaft_calendar_plugin_widget").css("cursor", "pointer");
    });
  });

</script>
<?php
echo __( 'Veranstaltungen in diesem Monat </br> </br> ', 'wp_widget_domain' );
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
  $title = __( 'New title', 'wp_widget_domain' );
  }
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title );
?>" />
</p>
<?php

}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here


  function theme_customize_register( $wp_customize ) {

    // Calendar Widget background
    $wp_customize->add_setting( 'event_background_color_widget', array(
      'default'   => '#2F9B92',
      'transport' => 'refresh',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'event_background_color_widget', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Hintergrundfarbe Veranstaltung Kalender-Widget', 'theme' ),
    ) ) );
    // Calendar background
    $wp_customize->add_setting( 'event_background_color', array(
      'default'   => '#2F9B92',
      'transport' => 'refresh',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'event_background_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Hintergrundfarbe Veranstaltung Kalender', 'theme' ),
    ) ) );
    // Event List Date color
    $wp_customize->add_setting( 'event_date_color', array(
      'default'   => '#2F9B92',
      'transport' => 'refresh',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'event_date_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Datum Schriftfarbe in Veranstaltungsliste', 'theme' ),
    ) ) );
    // Event List Icon color
    $wp_customize->add_setting( 'event_icon_color', array(
      'default'   => '#2F9B92',
      'transport' => 'refresh',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'event_icon_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Iconfarbe in Veranstaltungsliste', 'theme' ),
    ) ) );

  }

  add_action( 'customize_register', 'theme_customize_register' );

  function mytheme_customize_css()
{
    ?>
         <style type="text/css">
             .fachschaft_calendar_plugin_calendar_table .event { background: <?php echo get_theme_mod('event_background_color', '#2F9B92'); ?>;}
             .fachschaft_calendar_plugin_widget .event { background: <?php echo get_theme_mod('event_background_color_widget', '#2F9B92'); ?>;}
             .fachschaft_calendar_plugin_event_list h3 { color: <?php echo get_theme_mod('event_date_color', '#2F9B92'); ?>;}
             .fachschaft_calendar_plugin_event_list .fas:before { color: <?php echo get_theme_mod('event_icon_color', '#2F9B92'); ?>;}
         </style>
    <?php
}
add_action( 'wp_head', 'mytheme_customize_css');
