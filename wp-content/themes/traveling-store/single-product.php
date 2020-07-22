<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Template Name: About page
 */

wp_head(); ?>
<body>
<div class="wrapper innerPageHeader product-page">

    <?php get_header(); ?>

    <section class="productCardSection">
        <div class="content">

	        <?php woocommerce_content(); ?>

        </div>

        <div class="productCardSettingsTriggerWrapper">
            <div class="cardName"><?php echo $product->get_title(); ?></div>
            <a href="#" class="productCardSettingsTrigger">
                <span><?php _e('расчет стоимости', 'traveling-store'); ?></span>
            </a>
        </div>

    </section>

	<?php $args = array(
		'post_type'      => 'product',
		'posts_per_page' => 5,
		'post_status'    => 'publish',
		'perm'           => 'readable',
		'tax_query'      => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_tag',
				'field'    => 'slug',
				'terms'    => 'popular'
			)
		),
	);

		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) : ?>

            <section class="nthInRowSection popularProductsSection">
                <div class="content">
                    <div class="titleWrap withButtonTitleWrap">
                        <div class="h3"><?php _e( 'Популярные туры', 'traveling-store' ); ?></div>
                        <a href="<?php echo get_site_url() . '/tours/'; ?>" class="withTitleButton"><?php _e( 'Все туры', 'traveling-store' ); ?></a>
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