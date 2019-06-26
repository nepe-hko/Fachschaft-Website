<article class="hk-post-container" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php echo '<h1 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h1>'; ?>
		<div class="entry-author">von <?php the_author_meta('first_name') ?> <?php the_author_meta('last_name') ?></div>
		<div class="entry-date"><?php echo get_the_date(); ?></div>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php the_content(); 
		$admin_comment = get_post_meta($post->ID,'admin_comment', true);
		if($admin_comment) : ?>
			<div class="admin-comment">Kommentar der Fachschaft:</div><?php echo $admin_comment;
		endif; ?>
		<br><br>
		<a href="<?php comments_link(); ?>"> Kommentieren (
			<?php comments_number("0","1","%"); ?> )</a>
			
	</div><!-- .entry-content -->
	<?php if ( !is_page() ) :
		comments_template();
	endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->