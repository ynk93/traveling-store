<!DOCTYPE html>
<html lang="en">
<?php
	/**
	 * Template Name: Checkout page template
	 * Template Post Type: page
	 *
	 * @package WordPress
	 * @subpackage Traveling Store
	 * @since Traveling Store 1.0
	 */

$checkout = WC()->checkout();

wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>

	<?php if (is_wc_endpoint_url('order-received')) {

		wc_get_template('checkout/thankyou.php');

	} ?>

    <section class="innerPageSection checkoutPageSection">
        <div class="content">
            <div class="titleWrap innerPageTitleWrap">-->
                <div class="h2"><?php _e('Checkout', 'traveling-store'); ?></div>
            </div>

	        <?php wc_get_template('checkout/form-checkout.php', array('checkout' => $checkout)); ?>

        </div>
    </section>

    <?php get_footer(); ?>

</div>
</body>
<?php wp_footer(); ?>
</html>
