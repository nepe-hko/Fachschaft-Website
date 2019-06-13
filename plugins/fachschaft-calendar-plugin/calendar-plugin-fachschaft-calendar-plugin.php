<?php
class CalendarPlugin{
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
    		'label' => 'Veranstaltungen',
    		'public' => true,
    		'publicly_queryable' => true,
    		'exclude_from_search' => false,
    		'show_ui' => true,
    		'show_in_menu' => true,
    		'has_archive' => true,
    		'rewrite' => true,
    		'query_var' => true,
        'taxonomies' => array('category'),
        'supports' => array( 'title', 'editor', 'thumbnail'),
        'menu_icon' =>  'dashicons-calendar-alt',
    	);
    	register_post_type( 'calendar_post_type', $public_pt_args );


    }
}
