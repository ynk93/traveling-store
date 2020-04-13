<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Template Name: Shop page
 */
wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>

    <section class="catalogSection">
        <div class="content">

            <div class="titleWrap innerPageTitleWrap">
                <div class="h2"><?php _e('Туры', 'traveling-store'); ?></div>
            </div>

            <div class="catalogSectionRow">

	            <?php get_template_part( 'template-parts/catalog-sidebar' ); ?>

                <div class="cardsRow">

	                <?php

		                $args = array(
			                'post_type'      => 'product',
			                'posts_per_page' => 12,
			                'post_status'    => 'publish',
			                'perm'           => 'readable',
			                'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
			                'product_tag'    => get_query_var( 'product_tag' ) ? get_query_var( 'product_tag' ) : '',
			                'tax_query'      => array()
		                );

		                $loop = new WP_Query( $args );

		                if ( $loop->have_posts() ) {
			                while ( $loop->have_posts() ) : $loop->the_post();
				                $post_id = get_the_ID();

				                get_template_part( 'template-parts/catalog-product' );

			                endwhile;
		                } else {
			                echo __( '<div class="empty-product">Not found</div>' );
		                }
		                wp_reset_postdata();

	                ?>

                </div>

            </div>

        </div>
    </section>

    <?php get_footer(); ?>

</div>
</body>
<?php wp_footer(); ?>
</html>