<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Template Name: Contact page
 */

wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>
    <section class="innerPageSection contactUsPageSection">
        <div class="content">
            <?php $text = get_field('text_content'); ?>

            <div class="titleWrap innerPageTitleWrap">
                <div class="h2"> <?php _e('Контакты', 'traveling-store'); ?></div>
            </div>
            <div class="textPageContent">
                <div class="contactUsContent">
                    <div class="p">
                        <?php echo $text; ?>
                    </div>

                    <div class="iconsRow">
                        <?php $contacts = get_field('contact_items');
                        foreach ($contacts as $contact_items):
                            print_r($contacts);
                            ?>
                            <a href="<?php echo $contact_items['info']['link']; ?>" class="iconBlock">
                                <div class="iconPic mailIcon">
                                    <img src="<?php echo $contact_items['info']['image']; ?>" alt="">
                                </div>
                                <div class="iconText"><?php echo $contact_items['info']['title']; ?></div>
                            </a>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php get_footer(); ?>

</div>
</body>
<?php wp_footer(); ?>
</html>