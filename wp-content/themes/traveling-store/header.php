<?php

	$cart_total = WC()->cart->get_cart_contents_count();
	$cart_total_class = ($cart_total == 0) ? '' : 'active';

?>

<header class="header">
    <div class="content">
        <div class="headerInnerWrap">

            <a href="#" class="hamburgerWrap">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </a>

            <div class="leftSide">
                <a href="<?php echo get_home_url(); ?>" class="logoBlock">Traveling store</a>
                <a href="tel:80670102013" class="cellPhoneBlock">
                    <span class="cellPhoneIcon"></span>
                    <span class="cellPhoneInfo">
                        <span class="cellPhoneLabel">
                            <?php _e('Call center', 'traveling-store'); ?>
                        </span>
                        <span class="cellPhoneNumber"><?php _e('8 067 01 02 013', 'traveling-store'); ?></span>
                    </span>
                </a>
            </div>

            <div class="rightSide">

	            <?php $header_navigation_menu = wp_nav_menu( [
		            'menu'        => 'header-navigation',
		            'container'   => false,
		            'menu_class'  => 'menu',
		            'menu_id'     => '',
		            'echo'        => false,
		            'fallback_cb' => 'wp_page_menu',
		            'items_wrap'  => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		            'depth'       => 0,
	            ] );

	            if ( $header_navigation_menu ) {
	                echo $header_navigation_menu;
                } ?>

                <div class="selectWrapper" style="display: none;">
                    <select name="langSelector">
                        <option value="ru">Ru</option>
                        <option value="en">En</option>
                    </select>
                </div>

                <a href="/cart/" class="basketIconWrapper" target="_self">
                    <span class="basketIcon">
                        <span class="productsInBasketTag <?php echo $cart_total_class; ?>">
                            <?php echo $cart_total; ?>
                        </span>
                    </span>
                </a>

            </div>

            <div class="headerBottomLine"></div>

        </div>
    </div>
</header>