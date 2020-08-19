<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Template Name: Home page
 */
wp_head(); ?>
<body>
<div class="wrapper">

    <?php get_header(); ?>

    <section class="mainSection">
        <div class="swiper-container mainSwiper">
            <div class="swiper-wrapper">

                <?php $slider = get_field('slider');
                foreach ($slider as $slide_item) : ?>

                    <div class="swiper-slide">
                        <div class="content">
                            <div class="slideContent">
                                <div class="h1 slideTitle">
                                    <?php echo $slide_item['slide']['title']; ?>
                                </div>
                                <div class="slideDescription">
                                    <?php echo $slide_item['slide']['text']; ?>
                                </div>
                            </div>
                            <div class="tourSlidePicBlock">
                                <img src="<?php echo $slide_item['slide']['background']['url']; ?>" alt="" class="src">
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>
            <div class="swiper-pagination"></div>

            <div class="swiper-buttons-wrap">
                <div class="swiper-button-left"></div>
                <div class="swiper-button-right"></div>
            </div>

        </div>
    </section>

    <?php $args = array(
        'post_type' => 'product',
        'posts_per_page' => 5,
        'post_status' => 'publish',
        'perm' => 'readable',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_tag',
                'field' => 'slug',
                'terms' => 'popular'
            )
        ),
    );

    $loop = new WP_Query($args);
    if ($loop->have_posts()) : ?>

        <section class="nthInRowSection popularProductsSection">
            <div class="content">
                <div class="titleWrap withButtonTitleWrap">
                    <div class="h3"><?php _e('Популярные туры', 'traveling-store'); ?></div>
                    <a href="<?php echo get_site_url() . '/tours/'; ?>"
                       class="withTitleButton"><?php _e('Все туры', 'traveling-store'); ?></a>
                </div>
                <div class="popularProductsSwiper swiper-container">
                    <div class="cardsRow swiper-wrapper">

                        <?php while ($loop->have_posts()) : $loop->the_post();
                            $post_id = get_the_ID();

                            get_template_part('template-parts/catalog-product');

                        endwhile; ?>

                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>

    <?php endif;
    wp_reset_postdata();
    ?>

    <section class="nthInRowSection partnersSection">
        <div class="content">

            <div class="titleWrap withButtonTitleWrap">
                <div class="h3"><?php _e('Реклама от партнеров', 'traveling-store'); ?></div>
                <a href="#partners" class="withTitleButton"><?php _e('стать партнером', 'traveling-store'); ?></a>
            </div>

            <div class="swiper-container partnersSwiper">
                <div class="cardsRow swiper-wrapper">

                    <?php $partners = get_field('partners');
                    foreach ($partners as $partners_item) : ?>

                        <a href="<?php echo $partners_item['link']['url']; ?>"
                           target="<?php echo $partners_item['link']['target']; ?>" class="card swiper-slide">
                        <span class="cardPic">
                            <?php echo wp_get_attachment_image($partners_item['image']['ID'], 'full', false); ?>
                        </span>
                            <span class="cardInfo">
                            <span class="cardName"><?php echo $partners_item['title']; ?></span>
                        </span>
                        </a>

                    <?php endforeach; ?>

                </div>
                <div class="swiper-pagination"></div>

            </div>

        </div>
    </section>

    <section class="nthInRowSection categoriesSection">
        <div class="content">
            <div class="titleWrap">
                <div class="h3"><?php _e('Категории', 'traveling-store'); ?></div>
            </div>

            <div class="swiper-container categoriesSwiper">
                <div class="cardsRow swiper-wrapper">
                    <?php
                    $category_taxonomy = 'product_cat';
                    $orderby = 'menu_order';

                    $show_count = 0;
                    $pad_counts = 0;
                    $title = '';

                    $args_cat = array(
                        'taxonomy' => $category_taxonomy,
                        'child_of' => 0,
                        'parent' => '',
                        'orderby' => $orderby,
                        'show_count' => $show_count,
                        'pad_counts' => $pad_counts,
                        'hierarchical' => 0,
                        'title_li' => $title,
                        'hide_empty' => 0,
                        'exclude' => 15
                    );

                    $categories = get_categories($args_cat);

                    foreach ($categories as $category) : ?>

                        <a href="<?php echo get_site_url() . '/tours/category/' . $category->slug . '/'; ?>"
                           class="card swiper-slide">
                        <span class="cardPic">
                                <?php $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                                echo wp_get_attachment_image($thumbnail_id, array(240, 280), false); ?>
                        </span>
                            <span class="cardInfo">
                            <span class="h5 cardName"><?php echo $category->name; ?></span>
                            <span class="p cardDescription">
                                <?php echo $category->category_description; ?>
                            </span>
                        </span>
                        </a>

                    <?php endforeach; ?>

                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section class="aboutUsSection">
        <div class="aboutUsSectionContent">
            <?php $about = get_field('about'); ?>

            <div class="orangeMark"><?php _e('о нас', 'traveling-store'); ?></div>
            <div class="h1"><?php echo $about['title']; ?></div>
            <div class="p">
                <?php echo $about['text']; ?>
            </div>
            <a href="<?php echo $about['button']['url']; ?>" target="_self" class="toAboutUsButton">
                <?php echo $about['button']['title']; ?>
            </a>
        </div>
        <div class="aboutUsBg img-parallax" data-speed="-1.25"></div>


    </section>

    <section class="nthInRowSection ourPreferencesSection">
        <div class="content">
            <div class="titleWrap centered">
                <div class="h3"><?php _e('Наши преимущества', 'traveling-store'); ?></div>
            </div>
            <div class="iconsRow">

                <?php $testimonials = get_field('testimonials');
                foreach ($testimonials as $testimonials_item) : ?>
                    <div class="iconBlock">
                        <div class="iconPic">
                            <img src="<?php echo $testimonials_item['image']['url']; ?>" alt="">
                        </div>
                        <div class="iconText"><?php echo $testimonials_item['title']; ?></div>
                    </div>

                <?php endforeach; ?>

            </div>

        </div>
    </section>

    <section class="reviewsSection">
        <?php $reviews = get_field('reviews'); ?>
        <div class="content">
            <div class="leftSide">
                <div class="reviewsTitle h3"><?php echo $reviews['left_block']['title']; ?></div>
                <div class="reviewsDescription p">
                    <?php echo $reviews['left_block']['text']; ?>
                </div>
            </div>
            <div class="rightSide">
                <div class="swiper-container reviewsSwiper">
                    <div class="swiper-wrapper">

                        <?php foreach ($reviews['reviews_slider'] as $review) : ?>

                            <div class="reviewBlock swiper-slide">
                                <div class="reviewHead">
                                    <div class="avatarPic">
                                        <?php echo wp_get_attachment_image($review['image'], array(72, 72), false); ?>
                                    </div>
                                    <div class="reviewTitle">
                                        <div class="reviewAuthorName">
                                            <?php echo $review['title']; ?>
                                        </div>
                                        <div class="reviewDate p">
                                            <?php echo $review['data']; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="reviewText p">
                                    <?php echo $review['text']; ?>
                                </div>
                            </div>

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