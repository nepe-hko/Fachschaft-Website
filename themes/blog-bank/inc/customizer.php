<?php
/**
 * Blog Bank Theme Customizer
 *
 * @package Blog_Bank
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
//Sanitize sidebar options
if ( ! function_exists( 'bbank_sanitize_sidebar_post' ) ) :
function bbank_sanitize_sidebar_post($value){ 
    if(!in_array($value, array('no-sidebar','right-sidebar'))){
        $value = 'right-sidebar';
    }
    return $value;
}
endif;

function blog_bank_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

        // Add x-blog options section
    $wp_customize->add_section('bbank_general_settings', array(
        'title' => __('General settings', 'blog-bank'),
        'capability'     => 'edit_theme_options',
        'description'     => __('Blog bank theme general settings ', 'blog-bank'),

    ));
     $wp_customize->add_setting('bbank_site_layout', array(
        'default'        => 'right-sidebar',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'bbank_sanitize_sidebar_post',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('bbank_site_layout_control', array(
        'label'      => __('Site layout style', 'blog-bank'),
        'description'     => __('Select layout site layout style.', 'blog-bank'),
        'section'    => 'bbank_general_settings',
        'settings'   => 'bbank_site_layout',
        'type'       => 'select',
        'choices'    => array(
            'right-sidebar' => __('Right sidebar', 'blog-bank'),
            'no-sidebar' => __('No sidebar', 'blog-bank'),
        ),
    ));
		// Add x-blog options section
	$wp_customize->add_section('bbank_footer_options', array(
		'title' => __('Footer options', 'blog-bank'),
		'capability'     => 'edit_theme_options',
		'description'     => __('Blog bank footer options ', 'blog-bank'),

	));
    $wp_customize->add_setting('footer_copy_text', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'x_blog_sanitize_footer_text',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('footer_copy_text_control', array(
        'label'      => __('Footer copyright text', 'blog-bank'),
        'description'     => __('You can change copyright when you update with Blog Bank pro.', 'blog-bank').'<a target="_blank" href="https://wpthemespace.com/product/blog-bank/">'.__(' update pro','blog-bank').'</a>',
        'section'    => 'bbank_footer_options',
        'settings'   => 'footer_copy_text',
        'type'       => 'text',
    ));

     $wp_customize->add_setting('facebook_url', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('facebook_url_control', array(
        'label'      => __('Facebook url', 'blog-bank'),
        'description'     => __('Enter Facebook url for footer social link', 'blog-bank'),
        'section'    => 'bbank_footer_options',
        'settings'   => 'facebook_url',
        'type'       => 'url',
    ));
     $wp_customize->add_setting('twitter_url', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('twitter_url_control', array(
        'label'      => __('Twitter url', 'blog-bank'),
        'description'     => __('Enter twitter url for footer social link', 'blog-bank'),
        'section'    => 'bbank_footer_options',
        'settings'   => 'twitter_url',
        'type'       => 'url',
    ));
     $wp_customize->add_setting('instagram_url', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('instagram_url_control', array(
        'label'      => __('Instagram url', 'blog-bank'),
        'description'     => __('Enter instagram url for footer social link', 'blog-bank'),
        'section'    => 'bbank_footer_options',
        'settings'   => 'instagram_url',
        'type'       => 'url',
    ));
     $wp_customize->add_setting('linkedin_url', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('linkedin_control', array(
        'label'      => __('Linkedin url', 'blog-bank'),
        'description'     => __('Enter linkedin url for footer social link', 'blog-bank'),
        'section'    => 'bbank_footer_options',
        'settings'   => 'linkedin_url',
        'type'       => 'url',
    ));




	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'blog_bank_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'blog_bank_customize_partial_blogdescription',
		) );
	}
}
add_action( 'customize_register', 'blog_bank_customize_register' );

    // Footer social link






/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function blog_bank_customize_partial_blogname() {
	esc_html(bloginfo( 'name' ));
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function blog_bank_customize_partial_blogdescription() {
	esc_html(bloginfo( 'description' ));
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function blog_bank_customize_preview_js() {
	wp_enqueue_script( 'blog-bank-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'blog_bank_customize_preview_js' );
