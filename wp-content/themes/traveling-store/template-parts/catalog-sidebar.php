<?php

	global $wp_query;

	$category_taxonomy = 'product_cat';
	$orderby      = 'menu_order';

	$show_count   = 0;
	$pad_counts   = 0;
	$title        = '';

	$args_cat = array(
		'taxonomy'     => $category_taxonomy,
		'child_of'     => 0,
		'parent'       => '',
		'orderby'      => $orderby,
		'show_count'   => $show_count,
		'pad_counts'   => $pad_counts,
		'hierarchical' => 0,
		'title_li'     => $title,
		'hide_empty'   => 0,
		'exclude'      => 15
	);

	$categories = get_categories( $args_cat );

	$regions = get_terms( 'pa_regions',
		[
			'orderby'    => $orderby,
			'hide_empty' => 0
		] );
?>

<div class="catalogSideBarWrap">

	<div class="sideBarBlock" data-group="category">
		<div class="sideBarHead">
			<div class="h5"><?php _e('Category','traveling-store'); ?></div>
		</div>
		<div class="sideBarBody">

            <?php foreach ( $categories as $cat ) : ?>

			<div class="chbWrap">
				<label class="container">
					<input type="checkbox" class="ajax-input" data-term="product_cat"
                           data-id="<?php echo $cat->slug; ?>"
                           data-name="<?php echo $cat->name; ?>">
					<span class="checkmark"></span>
					<span class="chbLabel"><?php echo $cat->name; ?></span>
				</label>
			</div>

            <?php endforeach; ?>

		</div>
	</div>

	<div class="sideBarBlock" data-group="filter">
		<div class="sideBarHead">
			<div class="h5"><?php _e('Regions', 'traveling-store'); ?></div>
		</div>
		<div class="sideBarBody">

			<?php foreach ( $regions as $region ) : ?>

                <div class="chbWrap">
                    <label class="container">
                        <input type="checkbox" class="ajax-input" data-term="pa_regions"
                               data-id="<?php echo $region->slug; ?>"
                               data-name="<?php echo $region->name; ?>"
                               data-slug="regions">
                        <span class="checkmark"></span>
                        <span class="chbLabel"><?php echo $region->name; ?></span>
                    </label>
                </div>

			<?php endforeach; ?>

		</div>
	</div>

</div>