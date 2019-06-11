<?php
/**
 * About setup
 *
 * @package blog-bank
 */

if ( ! function_exists( 'blog_bank_about_setup' ) ) :

	/**
	 * About setup.
	 *
	 * @since 1.0.0
	 */
	function blog_bank_about_setup() {
		$theme = wp_get_theme();
		$config = array(
		// Menu name under Appearance.
		'menu_name'               => esc_html__( 'blog bank Info', 'blog-bank' ),
		// Page title.
		'page_name'               => esc_html__( 'blog bank Info', 'blog-bank' ),
		/* translators: Main welcome title */
		'welcome_title'         => sprintf( esc_html__( 'Welcome to %s! - Version ', 'blog-bank' ), $theme['Name'] ),
		// Main welcome content
			// Welcome content.
			'welcome_content' => sprintf( esc_html__( '%1$s is now installed and ready to use. We want to make sure you have the best experience using the theme and that is why we gathered here all the necessary information for you. Thanks for using our theme!', 'blog-bank' ), $theme['Name'] ),

			// Tabs.
			'tabs' => array(
				'getting_started' => esc_html__( 'Getting Started', 'blog-bank' ),
				'recommended_actions' => esc_html__( 'Recommended Actions', 'blog-bank' ),
				'useful_plugins'  => esc_html__( 'Useful Plugins', 'blog-bank' ),
				'free_pro'  => esc_html__( 'Free Vs Pro', 'blog-bank' ),
				),

			// Quick links.
			'quick_links' => array(
                'theme_url' => array(
                    'text' => esc_html__( 'Theme Details', 'blog-bank' ),
                    'url'  => 'https://wpthemespace.com/product/blog-bank/',
                ),
                'demo_url' => array(
                    'text' => esc_html__( 'View Demo', 'blog-bank' ),
                    'url'  => 'http://wpthemium.com/blog-bank/bb-preview/',
                ),
                'video_url' => array(
                    'text'   => esc_html__( 'Show Quick Video', 'blog-bank' ),
                    'url'    => 'https://www.youtube.com/watch?v=8ldj27EHS8s',
                    'button' => 'primary',
                ),
                'documentation_url' => array(
                    'text'   => esc_html__( 'View Documentation', 'blog-bank' ),
                    'url'    => 'http://wpthemium.com/blog-bank/document-instruction/',
                    'button' => 'primary',
                ),
            ),

			// Getting started.
			'getting_started' => array(
				'one' => array(
					'title'       => esc_html__( 'Theme Documentation', 'blog-bank' ),
					'icon'        => 'dashicons dashicons-format-aside',
					'description' => esc_html__( 'Please check our full documentation for detailed information on how to setup and customize the theme.', 'blog-bank' ),
					'button_text' => esc_html__( 'View Documentation', 'blog-bank' ),
					'button_url'  => 'http://wpthemium.com/blog-bank/document-instruction/',
					'button_type' => 'primary',
					'is_new_tab'  => true,
					),
				'two' => array(
					'title'       => esc_html__( 'Theme Options', 'blog-bank' ),
					'icon'        => 'dashicons dashicons-admin-customizer',
					'description' => esc_html__( 'Theme uses Customizer API for theme options. Using the Customizer you can easily customize different aspects of the theme.', 'blog-bank' ),
					'button_text' => esc_html__( 'Customize', 'blog-bank' ),
					'button_url'  => wp_customize_url(),
					'button_type' => 'primary',
					),
				'three' => array(
					'title'       => esc_html__( 'Demo Content', 'blog-bank' ),
					'icon'        => 'dashicons dashicons-layout',
					'description' => sprintf( esc_html__( 'Demo content is pro feature. To import sample demo content, %1$s plugin should be installed and activated. After plugin is activated, visit Import Demo Data menu under Appearance.', 'blog-bank' ), esc_html__( 'One Click Demo Import', 'blog-bank' ) ),
					),
				'four' => array(
				    'title'       => esc_html__( 'Set Widgets', 'blog-bank' ),
				    'icon'        => 'dashicons dashicons-tagcloud',
				    'description' => esc_html__( 'Set widgets in your sidebar, Offcanvas as well as footer.', 'blog-bank' ),
				    'button_text' => esc_html__( 'Add Widgets', 'blog-bank' ),
				    'button_url'  => admin_url().'/widgets.php',
				    'button_type' => 'link',
				    'is_new_tab'  => true,
				),
				'five' => array(
					'title'       => esc_html__( 'Theme Preview', 'blog-bank' ),
					'icon'        => 'dashicons dashicons-welcome-view-site',
					'description' => esc_html__( 'You can check out the theme demos for reference to find out what you can achieve using the theme and how it can be customized. Theme demo only work in pro theme', 'blog-bank' ),
					'button_text' => esc_html__( 'View Demo', 'blog-bank' ),
					'button_url'  => 'http://wpthemium.com/blog-bank/bb-preview/',
					'button_type' => 'link',
					'is_new_tab'  => true,
					),
                'six' => array(
                    'title'       => esc_html__( 'Contact Support', 'blog-bank' ),
                    'icon'        => 'dashicons dashicons-sos',
                    'description' => esc_html__( 'Got theme support question or found bug or got some feedbacks? Best place to ask your query is the dedicated Support forum for the theme.', 'blog-bank' ),
                    'button_text' => esc_html__( 'Contact Support', 'blog-bank' ),
                    'button_url'  => 'https://wpthemespace.com/support/',
                    'button_type' => 'link',
                    'is_new_tab'  => true,
                ),
				),

					'useful_plugins'        => array(
						'description' => esc_html__( 'Theme supports some helpful WordPress plugins to enhance your site. But, please enable only those plugins which you need in your site. For example, enable WooCommerce only if you are using e-commerce.', 'blog-bank' ),
						'already_activated_message' => esc_html__( 'Already activated', 'blog-bank' ),
						'version_label' => esc_html__( 'Version: ', 'blog-bank' ),
						'install_label' => esc_html__( 'Install and Activate', 'blog-bank' ),
						'activate_label' => esc_html__( 'Activate', 'blog-bank' ),
						'deactivate_label' => esc_html__( 'Deactivate', 'blog-bank' ),
						'content'                   => array(
							array(
								'slug' => 'x-instafeed',
								'icon' => 'svg',
							),
							array(
								'slug' => 'gallery-box',
								'icon' => 'svg',
							),
							array(
								'slug' => 'contact-form-7',
								'icon' => 'svg',
							)
						),
					),
					// Required actions array.
					'recommended_actions'        => array(
						'install_label' => esc_html__( 'Install and Activate', 'blog-bank' ),
						'activate_label' => esc_html__( 'Activate', 'blog-bank' ),
						'deactivate_label' => esc_html__( 'Deactivate', 'blog-bank' ),
						'content'            => array(
							'gallery-box' => array(
								'title'       => __('Gallery Box', 'blog-bank' ),
								'description' => __( 'These recommended plugin need to install and active for better user experience.', 'blog-bank' ),
								'plugin_slug' => 'gallery-box',
								'id' => 'gallery-box'
							),
							'go-pro' => array(
								'title'       => '<a target="_blank" class="activate-now button button-primary" href="https://wpthemespace.com/product/blog-bank/">'.__('UPGRADE TO PRO','blog-bank').'</a>',
								'description' => __( 'You will get more frequent updates and quicker support with the Pro version.', 'blog-bank' ),
								//'plugin_slug' => 'x-instafeed',
								'id' => 'go-pro'
							),
						),
					),
			// Free vs pro array.
			'free_pro'                => array(
				'free_theme_name'     => __('Blog Bank','blog-bank'),
				'pro_theme_name'      => __('Blog Bank Pro','blog-bank'),
				'pro_theme_link'      => 'https://wpthemespace.com/product/blog-bank-pro/',
				/* translators: View link */
				'get_pro_theme_label' => sprintf( __( 'Get %s', 'blog-bank' ), 'Blog Bank Pro' ),
				'features'            => array(
					array(
						'title'       => esc_html__( 'Daring Design for Devoted Readers', 'blog-bank' ),
						'description' => esc_html__( 'blog-bank\'s design helps you stand out from the crowd and create an experience that your readers will love and talk about. With a flexible home page you have the chance to easily showcase appealing content with ease.', 'blog-bank' ),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Mobile-Ready For All Devices', 'blog-bank' ),
						'description' => esc_html__( 'blog-bank makes room for your readers to enjoy your articles on the go, no matter the device their using. We shaped everything to look amazing to your audience.', 'blog-bank' ),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Home slider', 'blog-bank' ),
						'description' => esc_html__( 'Blog Bank gives you extra post slider feature. You can create awesome home slider in this theme.', 'blog-bank' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Widgetized Sidebars To Keep Attention', 'blog-bank' ),
						'description' => esc_html__( 'Blog Bank comes with a widget-based flexible system which allows you to add your favorite widgets over the Sidebar as well as on offcanvas too.', 'blog-bank' ),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Multiple Header Layout', 'blog-bank' ),
						'description' => esc_html__( 'Blog Bank gives you extra ways to showcase your header with miltiple layout option you can change it on the basis of your requirement', 'blog-bank' ),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Banner Slider Options', 'blog-bank' ),
						'description' => esc_html__( 'blog-bank\'s PRO version comes with more Slider options to display and filter posts. For instance, you can have far more control on setting the source of the posts or how they are displayed, everything to push the content to the right people and promote it by the blink of an eye.', 'blog-bank' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Flexible Home Page Design', 'blog-bank' ),
						'description' => esc_html__( 'blog-bank\'s PRO version has more controll available to enable you to place widgets on Footer or Below the Post at the end of your articles.', 'blog-bank' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Multiple home layout', 'blog-bank' ),
						'description' => esc_html__( 'blog-bank PRO verison has multiple home layout so you can show your blog with awesome masonry grid layout with all devices supporte.', 'blog-bank' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Advance Customizer Options', 'blog-bank' ),
						'description' => esc_html__( 'Advance control for each element gives you different way of customization and maintained you site as you like and makes you feel different.', 'blog-bank' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Advance Pagination', 'blog-bank' ),
						'description' => esc_html__( 'Multiple Option of pagination via customizer can be obtained on your site like Infinite scroll, Ajax Button On Click, Number as well as classical option are available.','blog-bank' ),
						'is_in_lite'  => 'ture',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Flexible Color Scheme', 'blog-bank' ),
						'description' => esc_html__( 'Match your unique style in an easy and smart way by using an intuitive interface that you can fine-tune it until it fully represents you and matches your particular blogging needs.','blog-bank' ),
						'is_in_lite'  => 'ture',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Popular fonts', 'blog-bank' ),
						'description' => esc_html__( 'Blog Bank supports 6 popular fonts. So you can select your text and heading fonts differently.','blog-bank' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Premium Support and Assistance', 'blog-bank' ),
						'description' => esc_html__( 'We offer ongoing customer support to help you get things done in due time. This way, you save energy and time, and focus on what brings you happiness. We know our products inside-out and we can lend a hand to help you save resources of all kinds.','blog-bank' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'No Credit Footer Link', 'blog-bank' ),
						'description' => esc_html__( 'You can easily remove the Theme: blog-bank by blog-bank copyright from the footer area and make the theme yours from start to finish.', 'blog-bank' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
				),
			),

			);

		blog_bank_About::init( $config );
	}

endif;

add_action( 'after_setup_theme', 'blog_bank_about_setup' );


//Admin notice 

function blog_bank_admin_notice__error() {
    if(get_option('bb_notice1')){
        return;
    }
	$class = 'bb-notice notice notice-warning is-dismissible';
	$message = __( '<strong><span style="color:green;">Limite Time Offer For You:</span>  <span style="color:red">Blog Bank PRO Now 30% Discount.</span> You will get pro License with pro features and great support.<strong><span style="color:red;"> Pro version more powerful more secure and more SEO friendly. </span>', 'blog-bank' );
    $url1 = esc_url('https://wpthemespace.com/product/blog-bank/');
    $url2 =esc_url('https://wpthemespace.com/product/blog-bank/?add-to-cart=533');
	$url3 =esc_url('https://www.youtube.com/watch?v=8ldj27EHS8s');

	printf( '<div class="%1$s" style="padding:10px 15px 20px;text-transform:uppercase"><p>%2$s</p><a target="_blank" class="button button-danger" href="%3$s" style="margin-right:10px">'.esc_html('View details').'</a><a target="_blank" class="button button-primary" href="%4$s">'.esc_html('Buy now').'</a><a style="margin:0 10px" target="_blank" class="button button-primary" href="%5$s">'.esc_html('Show video').'</a><a class="bbhide" href="#">'.esc_html('Dismiss the notice').'</a></div>', esc_attr( $class ), wp_kses_post( $message ),$url1,$url2,$url3 ); 
}
add_action( 'admin_notices', 'blog_bank_admin_notice__error' );

function blog_bank_admin_notice_option(){
    if(isset($_GET['hnotice']) && $_GET['hnotice'] == 1 ){
        update_option( 'bb_notice1', 1);
    }
}
add_action('init','blog_bank_admin_notice_option');
