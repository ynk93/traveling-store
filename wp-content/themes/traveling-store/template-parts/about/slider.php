<div class="aboutUsPageRow swiperRow">
	<div class="leftSide">
		<div class="textSide">
			<div class="aboutUsSideTitle">
				<?php echo $about_item['title']; ?>
			</div>
			<div class="p">
				<?php echo $about_item['text']; ?>
			</div>
		</div>
	</div>
	<div class="rightSide swiperSide">
		<div class="swiper-container aboutUsSwiper">

			<div class="swiper-wrapper">

				<?php
					$category_taxonomy = 'product_cat';
					$orderby           = 'menu_order';

					$show_count = 0;
					$pad_counts = 0;
					$title      = '';

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

					foreach ( $categories as $category ) : ?>

                        <a href="<?php echo get_site_url() . '/tours/category/' . $category->slug . '/'; ?>" target="_self"
                           class="swiper-slide">
                            <div class="card">
                            <span class="cardPic">
                                <?php $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
	                                echo wp_get_attachment_image( $thumbnail_id, array( 240, 280 ), false ); ?>
                            </span>
                                <span class="cardInfo">
                                <span class="h5 cardName"><?php echo $category->name; ?></span>
                            </span>
                            </div>
                        </a>

					<?php endforeach; ?>

			</div>

			<div class="swiper-bottom-panel">
				<div class="swiper-buttons-wrap">
					<div class="swiper-button-prev"></div>
					<div class="swiper-button-next"></div>
				</div>
				<div class="swiper-pagination"></div>
			</div>

		</div>
	</div>
</div>