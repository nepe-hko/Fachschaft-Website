

<article class="hk-post-container" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-date"><?php echo get_the_date(); ?></div>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content() ?>
	</div><!-- .entry-content -->
	<?php if ( !is_page() ) :
		comments_template();
	endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->

