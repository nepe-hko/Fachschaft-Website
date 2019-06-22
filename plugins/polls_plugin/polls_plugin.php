<?php
/**
 * @package PollsPlugin
 */
/*
Plugin Name: Polls
Plugin URI: http://localhost:8080/wordpress/wp-admin/plugins.php
Description: Polls
Version 1.0
Author: Odile
*/

if( ! defined( 'ABSPATH' ) ) {
    die;
}

require_once( plugin_dir_path( __FILE__). '/poll_cpt.php');

if(! class_exists('Poll_Widget'))
{
    class Poll_Widget extends WP_Widget
    {
        public function __construct()
        {
            $widget_options = array( 
                'classname' => 'Poll_Widget',
                'description' => 'Widget for Poll',
              );

            parent::__construct( 'Poll_Widget', 'Poll', $widget_options );

            add_action('test',array($this, 'test') );
            add_action('admin_post_nopriv_test', array($this, 'test'));
            add_action('admin_post_test', array($this, 'test'));
        }

        public function widget($args, $instance)
        {

            $title = apply_filters( 'widget_title', $instance[ 'title' ] );

            echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; 

            ?>
                    <form method="post" action=" ">
                        <input type="text" id = "qst" name ="qst">
                        <input type="radio" id="yes" name = "yes">Yes</input><br>
                        <input type="radio" id="no" name = "no">No</input><br>
                        <input type="submit"  name = "submit" value="Abstimmen"><br>
                    </form>
            <?php
        }

        public function test()
        {
            $qst = $_POST['qst'];
            $vote_yes = $_POST['yes'];
            $vote_no = $_POST['no'];

            $args = array(
                'post_content'  => $vote_yes,
                'post_content'  => $vote_no,
                'post_type' => 'poll',
                'post_status'   => 'publish',
				'meta_input' => array(
				    '_poll_value_key' => $qst
				)
            );
            
            $postID = wp_insert_post($args);

            echo 'Vielen Dank für Ihre Abstimmung';
        }

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

function register_poll_widget() 
{ 
    register_widget( 'Poll_Widget' );
}

add_action( 'widgets_init', 'register_poll_widget' );