<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}


/*
Show Pending Post Count in Admin area
kopiert von: www.wprudder.com/show-pending-post-count-wordpress-admin-area/
*/
function hk_show_pending_number($menu) {
   $types = array("post", "improvement");
   $status = "pending";
   foreach($types as $type) {
       $num_posts = wp_count_posts($type, 'readable');
       $pending_count = 0;
       if (!empty($num_posts->$status)) $pending_count = $num_posts->$status;

       if ($type == 'post') {
           $menu_str = 'edit.php';
       } else {
           $menu_str = 'edit.php?post_type=' . $type;
       }

       foreach( $menu as $menu_key => $menu_data ) {
           if( $menu_str != $menu_data[2] )
               continue;
           $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>"
               . number_format_i18n($pending_count)
               . '</span></span>';
       }
   }
   return $menu;
}
add_filter('add_menu_classes', 'hk_show_pending_number');

/*
Customize Appearance Options
*/

function hk_customize_register($wp_customize) {

    # sections
    $wp_customize->add_section('hk_standard_colors', array(
        'title' => __('Standard Farben','twentyseventeen-child'),
        'priority' => 100,
    ));

    # settings
    $wp_customize->add_setting('hk_link_color', array(
        'default' => 'red',
        'transport' => 'refresh'
    ));
    $wp_customize->add_setting('hk_widget_title_color', array(
        'default' => '#222',
        'transport' => 'refresh'
    ));

    #controls
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hk_link_color_control', array(
        'label' => __('Links', 'twentyseventeen-child'),
        'section' => 'hk_standard_colors',
        'settings' => 'hk_link_color'
    )));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hk_widget_title_control', array(
        'label' => __('Widget Überschrift', 'twentyseventeen-child'),
        'section' => 'hk_standard_colors',
        'settings' => 'hk_widget_title_color'
    )));

}
add_action('customize_register', 'hk_customize_register');

// Output Customize CSS
function hk_customize_css() { ?>
    <style type="text/css">
        a:link, a:visited {
            color: <?php echo get_theme_mod('hk_link_color');?>
        }

        h2.widget-title {
            color: <?php echo get_theme_mod('hk_widget_title_color');?>
        }
    </style>
<?php
}


add_action('wp_head', 'hk_customize_css');