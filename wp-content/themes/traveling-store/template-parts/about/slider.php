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

				<?php foreach ( $about_item['slider_content'] as $slide ) :
					var_dump($slide);?>
				<a href="<?php echo $slide['link']['url']; ?>"  target="_self" class="swiper-slide">
					<div class="card">
						<span class="cardPic">
							<?php echo wp_get_attachment_image($slide['image']['ID'], array(240, 280), false); ?>
						</span>
						<span class="cardInfo">
							<span class="h5 cardName"><?php echo $slide['ttitle']; ?></span>
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