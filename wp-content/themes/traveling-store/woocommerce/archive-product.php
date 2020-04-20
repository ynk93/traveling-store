<!DOCTYPE html>
<html lang="en">
<?php
	global $wp_query;

	/**
	 * Template Name: Tours page
	 */
	wp_head();

	$term = $wp_query->get_queried_object();

	var_dump($term);
?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>

    <section class="catalogSection">
        <div class="content">

            <div class="titleWrap innerPageTitleWrap">
                <div class="h2"><?php _e('Tours', 'traveling-store'); ?></div>
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

		                $args = array_merge( $args, wp_parse_args( $_SERVER['QUERY_STRING'] ) );

		                if ( ! count( wp_parse_args( $_SERVER['QUERY_STRING'] ) ) && ( is_product_category() || is_product_tag() || taxonomy_is_product_attribute($term->taxonomy) ) ) {
			                $param = get_queried_object();

			                echo '<script> var initQs={' . $param->taxonomy . ':"' . $param->slug . '"}</script>';
			                $args[ $param->taxonomy ] = $param->slug;
		                } else {
			                echo '<script> var initQs={"product_cat":"' . $args['product_cat'] . '", "product_tag":"' . $args['product_tag'] . '", "product_type":"' . $args['product_type'] . '", "pa_regions":"' . $args['pa_regions'] . '"}</script>';
		                }

		                if ( ! isset( $args['order'] ) ) {
			                $args = array_merge( $args, array(
				                'orderby' => 'menu_order+title',
				                'order'   => 'ASC',
			                ) );
		                } else {
			                $args['orderby'] = 'meta_value_num';
		                }

		                if ( ! isset( $args['tax_query'] ) ) {
			                $args['tax_query'] = array();
		                }

		                $args['tax_query'][] = array(
			                'taxonomy' => 'product_visibility',
			                'terms'    => array( 'exclude-from-catalog', 'hidden' ),
			                'field'    => 'slug',
			                'operator' => 'NOT IN',
		                );

		                var_dump($args);

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