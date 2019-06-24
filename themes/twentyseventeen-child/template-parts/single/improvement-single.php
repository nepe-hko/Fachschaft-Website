<article class="hk-post-container" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php echo '<h1 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h1>'; ?>
		<div class="entry-date"><?php echo get_the_date(); ?></div>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php the_content(); ?>
		<a href="<?php comments_link(); ?>"> Kommentieren (
			<?php comments_number("0","1","%"); ?> )</a>
	</div><!-- .entry-content -->
	<?php if ( !is_page() ) :
		comments_template();
	endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->