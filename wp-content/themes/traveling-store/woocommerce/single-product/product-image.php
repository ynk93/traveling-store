<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$main_img_id     = $product->get_image_id();
$gallery_img_ids = $product->get_gallery_image_ids();

if ( $main_img_id || $gallery_img_ids ) : ?>

	<div class="swiper-container productCardPicsSwiper">

		<div class="swiper-wrapper">

			<?php if ( $main_img_id ) : ?>
				<div class="swiper-slide">
					<?php echo wp_get_attachment_image($main_img_id, array(856, 400), false); ?>
				</div>
			<?php endif; ?>

			<?php if ( $gallery_img_ids ) :
				foreach ( $gallery_img_ids as $gallery_img_id ) : ?>
				<div class="swiper-slide">
					<?php echo wp_get_attachment_image($gallery_img_id, array(856, 400), false); ?>
				</div>
			<?php endforeach;
			endif; ?>

		</div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

        <div class="shareRow">
            <div class="row">

                <a class="button fb" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode (get_post_permalink()); ?>"
                   onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                   target="_blank">
                    <span class="icon"></span>
                </a>

            </div>
        </div>

	</div>

<?php endif; ?>


