<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Blog_Bank
 */

get_header();
?>
        <section class="entry-content section-padding">
            <div class="container"> 
               <div class="error-page"> 
                    <header class="page-header">
					<h1 class="page-title"><?php esc_html_e( '404', 'blog-bank' ); ?></h1>
				</header><!-- .page-header -->
                    <h2><?php esc_html_e('Page not  found','blog-bank'); ?></h2>
                    <p><?php esc_html_e('Oops! The page you were looking for was not found. Perhaps searching can help.','blog-bank'); ?></p>
                    <div class="bfade search"> 
                        <?php get_search_form(); ?>
                    </div>
               </div>
            </div>
            
        </section>

<?php
get_footer();
