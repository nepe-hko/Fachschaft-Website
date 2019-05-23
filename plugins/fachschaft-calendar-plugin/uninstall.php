<?php

/*
* file for uninstall
*
* @package fachschaftCalendarPlugin
*/

//security check
if( !defined('WP_UNINSTALL_PLUGIN' )){
  die;
}

//clear data from database
//delete custom post type data

//here:  calendar_post_type'
$calendar_post_types = get_posts( array('post_type' => 'calendar_post_type', 'numberposts' => -1 ));

foreach ($calendar_post_types as $data) {
  wp_delete_posts($data->ID, true);
}
