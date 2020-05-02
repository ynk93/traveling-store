<!DOCTYPE html>
<html lang="en">
<?php
	/**
	 * Template Name: Privacy policy page
	 */
	wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

	<?php get_header(); ?>
    <section class="innerPageSection faqPageSection">
        <div class="content">
            <div class="titleWrap innerPageTitleWrap">
                <div class="h2"><?php the_title(); ?></div>
            </div>

			<?php
				while ( have_posts() ) : the_post(); ?>

                    <div class="textPageContent privacyPolicyContent">
						<?php the_content(); ?>
                    </div>

				<?php
				endwhile;
				wp_reset_query();
			?>

        </div>
    </section>

	<?php get_footer(); ?>

</div>
</body>
<?php wp_footer(); ?>
</html>