<!DOCTYPE html>
<html lang="en">
<?php
	/**
	 * Template Name: FAQ page template
	 * Template Post Type: page
	 *
	 * @package WordPress
	 * @subpackage Traveling Store
	 * @since Traveling Store 1.0
	 */
$qa = get_field('qa');
wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>

    <section class="innerPageSection faqPageSection">
        <div class="content">
            <div class="titleWrap innerPageTitleWrap">
                <div class="h2"><?php the_content(); ?></div>
            </div>
            <div class="textPageContent">

                <?php foreach ( $qa as $qa_item ) : ?>

                    <div class="faqBlock">
                    <div class="faqTitle h4">
                        <?php echo $qa_item['question']; ?>
                    </div>
                    <div class="faqText p">
	                    <?php echo $qa_item['answer']; ?>
                    </div>
                </div>

                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <?php get_footer(); ?>

</div>
</body>
<?php wp_footer(); ?>
</html>