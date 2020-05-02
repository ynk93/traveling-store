<!DOCTYPE html>
<html lang="en">
<?php
	/**
	 * Template Name: About page
	 */

	$about = get_field( 'About' );

	wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

	<?php get_header(); ?>
    <section class="innerPageSection aboutUsPageSection">
        <div class="content">
            <div class="titleWrap innerPageTitleWrap">
                <div class="h2"> <?php _e( 'О нас', 'traveling-store' ); ?></div>
            </div>

			<?php foreach ( $about as $about_item ) :

				$about_item_layout = $about_item['acf_fc_layout'];

				switch ( $about_item_layout ) {
                    case 'image_and_text':
	                    include( locate_template( 'template-parts/about/img_with_text.php' ) );
	                    break;
                    case 'slider':
	                    include( locate_template( 'template-parts/about/slider.php' ) );
                        break;

				}

			endforeach; ?>

        </div>
    </section>

	<?php get_footer(); ?>

</div>
</body>
<?php wp_footer(); ?>
</html>