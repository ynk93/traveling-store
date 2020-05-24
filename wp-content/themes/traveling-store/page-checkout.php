<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Template Name: Checkout page
 */

$checkout = WC()->checkout();

wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>

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
