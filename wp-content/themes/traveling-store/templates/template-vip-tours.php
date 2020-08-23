<!DOCTYPE html>
<html lang="en">
<?php
	/**
	 * Template Name: Vip tours page template
	 * Template Post Type: page
	 *
	 * @package WordPress
	 * @subpackage Traveling Store
	 * @since Traveling Store 1.0
	 */

	$vip = get_field( "vip_content" );

	wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

	<?php get_header(); ?>


    <section class="innerPageSection textPageSection">
        <div class="content">

            <div class="textPageContent">

				<?php foreach ( $vip as $vip_item ) :

					$vip_item_layout = $vip_item["acf_fc_layout"];
					switch ( $vip_item_layout ) {
						case "text_and_image":
							include( locate_template( 'template-parts/vip/text_and_image.php' ) );
							break;
						case "image_and_text":
							include( locate_template( 'template-parts/vip/image_and_text.php' ) );
							break;
					}

				endforeach; ?>

            </div>

        </div>
    </section>

	<?php $args = array(
		'post_type'      => 'product',
		'posts_per_page' => 5,
		'post_status'    => 'publish',
		'perm'           => 'readable',
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => 'vip'
			)
		),
	);

		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) : ?>

    <section class="nthInRowSection popularProductsSection">
        <div class="content">
            <div class="titleWrap withButtonTitleWrap">
                <div class="h3"><?php _e( 'VIP туры', 'traveling-store' ); ?></div>
                <a href="<?php echo get_site_url() . '/torus/category/vip/'; ?>" target="_self" class="withTitleButton">
					<?php _e( 'Все VIP туры', 'traveling-store' ); ?></a>
            </div>
            <div class="popularProductsSwiper swiper-container">
                <div class="cardsRow swiper-wrapper">

	                <?php while ( $loop->have_posts() ) : $loop->the_post();
		                $post_id = get_the_ID();

		                get_template_part( 'template-parts/catalog-product' );

	                endwhile; ?>

                </div>
            </div>
        </div>
    </section>

		<?php endif;
		wp_reset_postdata();
	?>

	<?php get_footer(); ?>

</div>
</body>
<?php wp_footer(); ?>
</html>