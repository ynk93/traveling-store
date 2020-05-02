<div class="aboutUsPageRow">

	<div class="leftSide">
		<div class="picSide">
			<?php echo wp_get_attachment_image($about_item['image']['ID'], array(580, 360), false); ?>
		</div>
	</div>
	<div class="rightSide">
		<div class="textSide">
			<div class="aboutUsSideTitle">
				<?php echo $about_item['title']; ?>
			</div>
			<div class="p">
				<?php echo $about_item['text']; ?>
			</div>
		</div>
	</div>
</div>