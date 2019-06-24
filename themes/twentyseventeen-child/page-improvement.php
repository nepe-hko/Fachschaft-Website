<?php
/*
Template Name: Verbesserungsvorschläge Page
*/
if(!defined('ABSPATH')) {
  die;
}

if(!is_user_logged_in()) {
    die;
};

function improvement_head() {
    echo '
        <style type="text/css">
            article.improvement .entry-header {
                margin-bottom: 1em !important
            }
        </style>
    ';
}
add_action('wp_head', 'improvement_head');

get_header(); ?>


<div class="wrap">
    <div id="primary" class="content-area">
        <button id="vbv_btn_show">Verbesserungsvorschlag einreichen</button>
        <!-- Einreichen -->
        <div class="vbv hide">
            <form id="vbv_container" class="vbv_form">
                <input id="vbv_title" name="title" type="text" placeholder="Titel" required></input>
                <input type="hidden" name="action" value="vbv_submit" />	
                <textarea id="vbv_content" name="content" placeholder="Deine Nachricht..." required></textarea>
                <button id="submit" type="submit">Vorschlag einreichen!</button>
            </form>
            <div id="vbv_response"></div>
        </div>

        <main id="main" class="site-main" role="main">

            <?php
            $args = array('post_type' => 'improvement');
            $query = new WP_Query($args);
            while ( $query->have_posts() ) :
                $query->the_post();

                get_template_part( 'template-parts/single/improvement', 'single' );


            endwhile; // End of the loop.
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
    <?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php
get_footer();
?>

