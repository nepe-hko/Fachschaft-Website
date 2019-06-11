<?php
/**
 * Blog Bank functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Blog_Bank
 */

if ( ! function_exists( 'blog_bank_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function blog_bank_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Blog Bank, use a find and replace
		 * to change 'blog-bank' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'blog-bank', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'blog-bank' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'blog_bank_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 100,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		// Add support for Block Styles.
		  add_theme_support( 'wp-block-styles' );
		  
		  // Add support for full and wide align images.
		  add_theme_support( 'align-wide' );



add_editor_style( array(blog_bank_fonts_url(), get_stylesheet_uri(),'assets/css/font-awesome.css' ) );
	}
endif;
add_action( 'after_setup_theme', 'blog_bank_setup' );

if ( ! function_exists( 'wp_body_open' ) ) {
    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support WordPress versions prior to 5.2.0.
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action( 'wp_body_open' );
    }
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function blog_bank_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'blog_bank_content_width', 1170 );
}
add_action( 'after_setup_theme', 'blog_bank_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function blog_bank_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'blog-bank' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'blog-bank' ),
		'before_widget' => '<section id="%1$s" class="widget bfade widget-item %2$s">',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'blog_bank_widgets_init' );
/**
 * Register custom fonts.
 */
function blog_bank_fonts_url() {
	$fonts_url = '';

		$font_families = array();

		$font_families[] = 'Montserrat:400,700';
		$font_families[] = 'Poppins:300,400';
		$font_families[] = 'family=Slabo+27px:300,400,700';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );


	return esc_url_raw( $fonts_url );
}

/**
 * Enqueue scripts and styles.
 */
function blog_bank_scripts() {
	    wp_enqueue_style( 'blog-bank-font', blog_bank_fonts_url(), array(), null );

	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(),'4.7.0 ','all' );
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/assets/css/animate.css', array(),'4.7.0 ','all' );
	wp_enqueue_style( 'owl.carousel', get_template_directory_uri() . '/assets/css/owl.carousel.min.css', array(),'4.7.0 ','all' );
	wp_enqueue_style( 'material-design-iconic-font', get_template_directory_uri() . '/assets/css/material-design-iconic-font.min.css', array(),'4.7.0 ','all' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(),'4.7.0 ','all' );
	wp_enqueue_style( 'blog-bank-default', get_template_directory_uri() . '/assets/css/default.css', array(),'1.0.6 ','all' );
	wp_enqueue_style( 'blog-bank-style', get_stylesheet_uri() );
	 wp_enqueue_style( 'block-style', get_template_directory_uri() . '/assets/css/block.css', array(), '1.0' );
	wp_enqueue_style( 'blog-bank-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array(),'4.7.0 ','all' );


	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.8.3.min.js', array(), '20151215', false );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '20151215', true );
	wp_enqueue_script( 'blog-bank-plugins', get_template_directory_uri() . '/assets/js/plugins.js', array('jquery'), '20151215', true );
	wp_enqueue_script( 'scrollreveal', get_template_directory_uri() . '/assets/js/scrollreveal.min.js', array('jquery'), '20151215', true );
	wp_enqueue_script( 'owl.carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array('jquery'), '20151215', true );
	wp_enqueue_script( 'blog-bank-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '20151215', true );

	wp_enqueue_script( 'blog-bank-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'blog_bank_scripts' );


function blog_bank_gb_block_style() {

   wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ), false, '4.2.0', 'all' );
   wp_enqueue_style( 'gb-block', get_theme_file_uri( '/assets/css/block-editor.css' ), false, '1.0', 'all' );

}
add_action( 'enqueue_block_editor_assets', 'blog_bank_gb_block_style' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * tgm plugin class
 */
require get_template_directory() . '/inc/class-tgm-plugin-activation.php';
/**
 * tgm plugin recomend
 */
require get_template_directory() . '/inc/plugin-recomend.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load theme about section.
 */
if ( is_admin() ) {
    require_once trailingslashit( get_template_directory() ) . 'inc/about/class.about.php';
    require_once trailingslashit( get_template_directory() ) . 'inc/about/about.php';
}
/**
 * Customizer pro info .
 */
require get_template_directory() . '/inc/info/class-customize.php';
