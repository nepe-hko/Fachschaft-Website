<?php
class ThSpeiseplanWidget extends WP_Widget {

    public function __construct()
    {

    }

    public function widget( $args, $instance)
    {

    }

    public function form( $instance)
    {

    }

    public function update ( $new_instance, $old_instance)
    {

    }

    add_action('widgets_init', function() {
        register_widget( 'My_Widget');
    })
}