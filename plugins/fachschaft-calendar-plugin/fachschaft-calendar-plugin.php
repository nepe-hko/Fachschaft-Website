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




//
