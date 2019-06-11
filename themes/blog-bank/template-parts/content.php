<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blog_Bank
 */
$categories_list = get_the_category_list( esc_html__( ', ', 'blog-bank' ) );
$bbank_tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'blog-bank' ) );
?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('single-content post-text scroll'); ?>> 
                            <div class="<?php if(!is_single()): ?>bfade<?php endif; ?> content-media"> 
                            	<?php if(has_post_thumbnail()): ?>
                                <?php blog_bank_post_thumbnail(); ?>
                                <div class="content-social"> 
                                    <ul>
                                        <li><a target="_blank" href="https://facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>&display=popup"><i class="fa fa-facebook"></i></a></li>
                                        <li><a target="_blank" href="https://twitter.com/intent/tweet?text=<?php the_title(); ?>&amp;url=<?php the_permalink(); ?>"><i class="fa fa-twitter"></i></a></li>
                                        <li><a target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;description=<?php the_title(); ?>"><i class="fa fa-pinterest"></i></a></li>
                                        <li><a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" > <i class="fa fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            </div>
                            <header class="<?php if(!is_single()): ?>bfade<?php endif; ?> text-header"> 
                                <?php if ( is_singular() ) :
			the_title( '<h2>', '</h2>' );
		else :
			the_title( '<h2><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif; ?>
                            <?php if ( 'post' === get_post_type() ) : ?>
                                <div class="post-meta"> 
                                   <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><span class="post-author"><i class="zmdi zmdi-account-circle"> <?php echo esc_html(get_the_author_meta( 'display_name')); ?></i></span></a>
                                   <?php if($categories_list): ?>
                                    <span class="post-cat"><i class="fa fa-book"></i> <?php echo wp_kses_post($categories_list); ?></span>
                                <?php endif; ?>
                                  <?php blog_bank_comment_post(); ?> 
                                </div>
                            <?php endif; ?>
                            </header>
                            <div class="<?php if(!is_single()): ?>bfade<?php endif; ?> ufade post-content"> 
                               	<div class="entry-content">
									<?php
                                    if(is_single()){
									the_content( sprintf(
										wp_kses(
											/* translators: %s: Name of current post. Only visible to screen readers */
											__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'blog-bank' ),
											array(
												'span' => array(
													'class' => array(),
												),
											)
										),
										get_the_title()
									) );
                                }else{
                                    the_excerpt();?>
                         <a href="<?php the_permalink(); ?>" class="redmore"><?php esc_html_e('Continue Reading...','blog-bank'); ?></a>
                            <?php
                                }

                                    if (is_single() && $bbank_tags_list ) {
                printf( '<div class="tags-links bfade">' . esc_html__( 'Tagged: %1$s', 'blog-bank' ) . '</div>', $bbank_tags_list ); 
            }

									wp_link_pages( array(
										'before' => '<div class="page-links bfade">' . esc_html__( 'Pages:', 'blog-bank' ),
										'after'  => '</div>',
									) );


									?>
								</div><!-- .entry-content -->
                            </div>
        </article><!-- single article -->


