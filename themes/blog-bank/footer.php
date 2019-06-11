<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Blog_Bank
 */
$facebook_url = get_theme_mod('facebook_url','#');
$twitter_url = get_theme_mod('twitter_url','#');
$instagram_url = get_theme_mod('instagram_url','#');
$linkedin_url = get_theme_mod('linkedin_url','#');
?>

	</div><!-- #content -->

        <footer class="footer-section">
            <div class="container"> 
                <div class="footer-top footer-social"> 
                    <ul>
                        <?php if ($facebook_url): ?>
                        <li><a href="<?php echo esc_url($facebook_url); ?>"><i class="fa fa-facebook"></i><?php esc_html_e('Facebook','blog-bank'); ?></a></li>
                        <?php
                        endif;
                         if ($twitter_url): ?>
                        <li><a href="<?php echo esc_url($twitter_url); ?>"><i class="fa fa-twitter"></i><?php esc_html_e('Twitter','blog-bank'); ?></a></li>
                        <?php
                        endif;
                         if ($instagram_url): ?>
                        <li><a href="<?php echo esc_url($instagram_url); ?>"><i class="fa fa-instagram"></i><?php esc_html_e('Instagram','blog-bank'); ?></a></li>
                        <?php
                        endif;
                         if ($linkedin_url): ?>
                        <li><a href="<?php echo esc_url($linkedin_url); ?>"><i class="fa fa-linkedin"></i><?php esc_html_e('Linkedin','blog-bank'); ?></a></li>
                        <?php
                        endif;
                        ?>
                         
                    </ul>
                </div>
                <div class="footer-bottom"> 
                    <div class="copy-text">
                    	<p><a href="<?php echo esc_url( __( 'https://wordpress.org/', 'blog-bank' ) ); ?>"><?php
                    /* translators: %s: CMS name, i.e. WordPress. */
                    printf( esc_html__( 'Proudly powered by %s', 'blog-bank' ), 'WordPress' );
                ?></a>
                <span class="sep"> | </span>
                <?php
                    /* translators: 1: Theme name, 2: Theme author. */
                    printf( esc_html__( 'Theme: %1$s by %2$s', 'blog-bank' ), 'blog-bank', '<a href="'.esc_url('https://wpthemespace.com').'">wpthemespace.com</a>' );
                ?></p>

                    </div>
                </div>
            </div>
        </footer>
        


</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
