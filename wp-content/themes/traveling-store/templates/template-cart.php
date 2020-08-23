<!DOCTYPE html>
<html lang="en">
<?php
	/**
	 * Template Name: Cart page template
	 * Template Post Type: page
	 *
	 * @package WordPress
	 * @subpackage Traveling Store
	 * @since Traveling Store 1.0
	 */
wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>

    <section class="innerPageSection basketPageSection">

        <div class="content">

            <div class="titleWrap innerPageTitleWrap">
                <div class="h2">
                    <?php _e('Моя корзина', 'traveling-store'); ?>
                </div>
            </div>

            <div class="basketPageContent">

	            <?php get_template_part('woocommerce/cart/cart')?>

            </div>

        </div>

    </section>

    <?php get_footer(); ?>

</div>
</body>
<?php wp_footer(); ?>
</html>
