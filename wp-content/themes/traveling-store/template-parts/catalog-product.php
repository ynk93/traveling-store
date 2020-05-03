<?php
	$product_id = get_the_ID();
	$product_url = get_the_permalink();
	$product_name = get_the_title();
	$product_img = get_the_post_thumbnail( $product_id, array(232, 160), array('alt' => get_the_title(), 'titlle' => get_the_title() . ' - Traveling Store') );

	$product = wc_get_product( $product_id );
	$product_regular_price = $product->get_price();
	$product_attributes = $product->get_attributes();
?>

<a href="<?php echo $product_url; ?>" class="card" target="_self" id="product-<?php echo $product_id; ?>" data-id="<?php echo $product_id; ?>">

	<span class="cardHead">
		<span class="cardPic">
			<?php echo $product_img; ?>
		</span>
		<span class="cardName h5"><?php echo $product_name; ?></span>
	</span>

	<span class="cardInfo">

        <?php if ( $product_attributes ) : ?>

            <span class="productInfoRows">

                <?php foreach ( $product_attributes as $product_attribute ) : ?>

                    <span class="productInfoRow">
                        <span class="productInfoRowLabel">
                            <?php echo wc_attribute_label($product_attribute->get_name(), $product); ?>
                        </span>
                        <span class="productInfoRowValue">

                            <?php $options = $product_attribute->get_terms();

                            $options_count = count($options);
                            $i = 0;

                            foreach ( $options as $option ) {

                                $i++;
                                $separator = '';

                                if ( $options_count !== $i ) {
                                    $separator = ', ';
                                }

                                echo $option->name . $separator;

                            } ?>
                        </span>
                    </span>

                <?php endforeach; ?>

            </span>

        <?php endif; ?>

		<span class="priceRow">
			<span class="priceBlock">
				<span class="priceLabel"><?php _e('Цена от:', 'traveling-store'); ?></span>
				<span class="priceValue"><?php echo $product_regular_price . get_woocommerce_currency_symbol(); ?></span>
			</span>
			<span class="opencardButton"><?php _e('Подробнее', 'traveling-store'); ?></span>
		</span>

	</span>

</a>