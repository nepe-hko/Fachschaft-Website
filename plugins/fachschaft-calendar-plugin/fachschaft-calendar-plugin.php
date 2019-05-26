<?php

/**
*  @package FachschaftCalendarPlugin
*/
/*
Plugin Name: Fachschaft Calendar FachschaftCalendarPlugin
Plugin URI:
Description: This is a Calandar Plugin for our Fachschaft-Website
Version: 1.0.0
Author: Lena Kallenbach
Author URI:
License: GPLv2 or later
Text Domain: fachschaft-calendar-plugin
*/

// namespace fachschaftCalendarPlugin;

if(!defined('ABSPATH'))
{
  die;
}

class CalendarPlugin
{
    protected static $_instance = null; // Singleton instance
    public static function get_instance() {
      if (null === self::$_instance) {
      self::$_instance = new self;
      }
    return self::$_instance;
    }
    protected function __clone() {} // Prevent singleton cloning
    protected function __construct() {
      //add custom post type calendar
      add_action('init', array($this, 'calendar_post_type'));
      //metabox for custom post type
      add_action('add_meta_boxes', 'calendar_add_meta_box');
      add_action('save_post','save_calendar_event_data');
    }

    function activate(){
      //generate custom post types

      $this->calendar_post_type();
      //new rewrite rules
      flush_rewrite_rules();
    }
    function deactivate(){
      flush_rewrite_rules();
    }

    function calendar_post_type(){
      //specify custom post type
      $public_pt_args = array(
    		'label' => 'Kalender',
    		'public' => true,
    		'publicly_queryable' => true,
    		'exclude_from_search' => false,
    		'show_ui' => true,
    		'show_in_menu' => true,
    		'has_archive' => true,
    		'rewrite' => true,
    		'query_var' => true,
        'supports' => array( 'title', 'editor', 'thumbnail'),
        'menu_icon' =>  'dashicons-calendar-alt',
    	);
    	register_post_type( 'calendar_post_type', $public_pt_args );
    }
}

if(class_exists('CalendarPlugin'))
{
  $fachschaftCalendarPlugin = CalendarPlugin::get_instance(); // Create instance

}

//activation
register_activation_hook(__FILE__, array($fachschaftCalendarPlugin, 'activate'));

//deactivation
register_deactivation_hook(__FILE__, array($fachschaftCalendarPlugin, 'deactivate'));

function add_date_picker(){
  //jQuery UI datepicker file
  wp_enqueue_script('jquery-ui-datepicker');
  //custom datepicker js
  wp_enqueue_script('custom-datepicker', get_stylesheet_directory_uri().'/js/datepicker.js', array('jquery'));
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

  // echo "Test Date Picker: </br><div><p>Date: <input type='text' id='datepicker'></p> </div>";
  echo '<label for="calendar_event_field">Datum: </lable>';
  echo "<input type='text' id='datepicker' name='calendar_event_field' value='". esc_attr($value) ."' size='25'/>";
?>
        <script>
          jQuery(document).ready(function() {
              jQuery( "#datepicker" ).datepicker({ dateFormat: 'dd.mm.yy' }).val();
          });

        </script>
        <?php
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


class Calendar{
  private $month;
  private $year;
  private $day_of_week;
  private $num_days;
  private $date_info;
  private $days_of_week;

  public function __construct($month, $year, $days_of_week= array('Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'))
    {
      $this->month = $month;
      $this->year = $year;
      $this->days_of_week = $days_of_week;
      $this->num_days = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
      $this->date_info = getdate(strtotime('first day of', mktime(0,0,0, $this->month, 1, $this->year)));
      $this->day_of_week = $this->date_info['wday']-1;
    }

  public function show(){
    $output = '<table class="calendar">';
    $output .='<caption>'.$this->date_info['month'].' '.$this->year.'</caption>';
    $output .='<tr>';

    // Display dayes of the week header
    foreach ($this->days_of_week as $day) {
      $output .= '<th class="header">' .$day . '</th>';
    }

    $output .= '</tr><tr>';

    if ($this->day_of_week >0) {
      $output .= '<td colspan="' .$this->day_of_week .'"></td>';
    }
    $current_day = 1;

    while ($current_day <= $this->num_days) {
      if ($this->day_of_week == 7) {
        $this->day_of_week = 0;
        $output .= '</tr><tr>';
      }

      $output .= '<td class="day">' .$current_day .'</td>';

      $current_day++;
      $this->day_of_week++;
    }

    if ($this->day_of_week != 7) {
      $remaining_days = 7 - $this->day_of_week;
      $output .= '<td colspan="' .$remaining_days .'"></td>';
    }

    $output .= '</tr>';
    $output .= '</table>';

    //print calendar table
    echo $output;

  }

  public function markeDay($event_days, $event_months, $event_years){

    $output = '<table class="calendar">';
    $output .='<caption>'.$this->date_info['month'].' '.$this->year.'</caption>';
    $output .='<tr>';

    // Display dayes of the week header
    foreach ($this->days_of_week as $day) {
      $output .= '<th class="header">' .$day . '</th>';
    }

    $output .= '</tr><tr>';

    if ($this->day_of_week >0) {
      $output .= '<td colspan="' .$this->day_of_week .'"></td>';
    }
    $current_day = 1;
    $counter =0;

    while ($current_day <= $this->num_days) {
      if ($this->day_of_week == 7) {
        $this->day_of_week = 0;
        $output .= '</tr><tr>';
      }

      if ($current_day == $event_days[$counter]) {
        $output .= '<td class="day event" style="background: #f970f7;">' .$current_day .'</td>';
        $counter++;
      }
      else {
        $output .= '<td class="day">' .$current_day .'</td>';
      }


      $current_day++;
      $this->day_of_week++;
    }

    if ($this->day_of_week != 7) {
      $remaining_days = 7 - $this->day_of_week;
      $output .= '<td colspan="' .$remaining_days .'"></td>';
    }

    $output .= '</tr>';
    $output .= '</table>';

    //print calendar table
    return $output;
}
}

function printCalendar(){

  // $calendar->markeDay(12,5,2019);
  //get Event dates from db -> meta_value of _calendar_event_value_key

  $days =array();
  $months =array();
  $years =array();
  //test Data

  $events = array('1.5.2019', '21.5.2019', '6.6.2019', '31.5.2019', '11.5.2019');
  function date_sort($a, $b) {
      return strtotime($a) - strtotime($b);
  }
  usort($events, "date_sort");

  //// TODO: get post meta
  // array_push($events,get_post_meta(get_the_ID(),'_calendar_event_value_key'));


  //split date
  foreach ($events as $date) {
    $parts = explode('.', $date);
    array_push($days, $parts[0]);
    array_push($months, $parts[1]);
    array_push($years, $parts[2]);
  }
  $months_with_events = array_unique($months);
  $years_with_events = array_unique($years);

  $calendar = new Calendar(5,2019);

  return $calendar->markeDay($days,$months,$years);
}

add_shortcode('calendar', 'printCalendar');
