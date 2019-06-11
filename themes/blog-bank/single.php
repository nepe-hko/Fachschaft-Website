<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Blog_Bank
 */
$bbank_site_layout = get_theme_mod('bbank_site_layout','right-sidebar');

if($bbank_site_layout == 'no-sidebar'){
	$bbank_layout_class = '12';
}else{
	$bbank_layout_class = '9';
}
get_header();
?>

	<section class="entry-content section-padding">
        <div class="container"> 
        	<div class="row"> 
            	<div class="col-sm-<?php echo esc_attr($bbank_layout_class); ?>">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

			the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

				</div>
				<?php if($bbank_site_layout == 'right-sidebar'): ?>
				<div class="col-sm-3"> 
				<?php get_sidebar(); ?>
				</div><!-- #main -->
				<?php endif; ?>
			</div><!-- #main -->
		</div><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();

