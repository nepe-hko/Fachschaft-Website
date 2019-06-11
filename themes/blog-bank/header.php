<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Blog_Bank
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'blog-bank' ); ?></a>
	  
	      <!-- Header section start -->
        <header id="site-head"> 
            <!-- Navigation -->
            <nav id="mainNav" class="navbar navbar-default navbar-custom navbar-fixed-top gold-nav">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header page-scroll">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main_menu">
                            <span class="sr-only"><?php esc_html_e('Toggle navigation','blog-bank'); ?></span> <?php esc_html_e('Menu','blog-bank'); ?> <i class="fa fa-bars"></i>
                        </button>
                        <?php if(has_custom_logo()): ?>
                        <a class="navbar-brand page-scroll" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			                <?php
				                the_custom_logo();
				            ?>
			            </a>
			            <?php else: ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php
            endif;
			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
			<?php
			endif; ?>
                        
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                 <?php
						wp_nav_menu( array(
						'theme_location'  => 'menu-1',
						'depth'	          => 2, // 1 = no dropdowns, 2 = with dropdowns.
						'container'       => 'div',
						'container_class' => 'collapse navbar-collapse',
						'container_id'    => 'main_menu',
						'menu_class'        => 'nav navbar-nav navbar-right',
						'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
						'walker'          => new WP_Bootstrap_Navwalker(),
					) );
					?>
                 
                </div>
                <!-- /.container-fluid -->
            </nav>
            <?php if(is_home()): ?>
            <div class="top-slider"> 
            	<div class="item active">
					<?php the_header_image_tag(); ?>
           		</div>
            </div>
           <?php endif; ?>
        </header>

	<div id="content" class="site-content">
