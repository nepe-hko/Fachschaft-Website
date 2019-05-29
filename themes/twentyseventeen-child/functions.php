<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}


/*
Show Pending Post Count in Admin area
kopiert von: www.wprudder.com/show-pending-post-count-wordpress-admin-area/
*/
function show_pending_number($menu) {
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
add_filter('add_menu_classes', 'show_pending_number');