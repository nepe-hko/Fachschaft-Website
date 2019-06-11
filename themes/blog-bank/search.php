<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h3 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'blog-bank' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h3>
			</header><!-- .page-header -->
			 <div class="row"> 
            	<div class="col-sm-<?php echo esc_attr($bbank_layout_class); ?>">

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'search' );

			endwhile;

			the_posts_navigation();

		else : ?>
			<div class="row"> 
            	<div class="col-sm-<?php echo esc_attr($bbank_layout_class); ?>">
		<?php 
			get_template_part( 'template-parts/content', 'none' );

		endif;
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
