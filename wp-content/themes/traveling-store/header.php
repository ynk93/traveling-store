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
                <a href="tel: " class="cellPhoneBlock">
                    <span class="cellPhoneIcon"></span>
                    <span class="cellPhoneInfo">
                        <span class="cellPhoneLabel">Контактный центр</span>
                        <span class="cellPhoneNumber">8 067 01 02 013</span>
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

                <div class="selectWrapper">
                    <select name="langSelector">
                        <option value="ru">Ru</option>
                        <option value="en">En</option>
                    </select>
                </div>

                <a href="/cart/" class="basketIconWrapper" target="_self">
                    <span class="basketIcon">
                        <span class="productsInBasketTag">2</span>
                    </span>
                </a>

            </div>

            <div class="headerBottomLine"></div>

        </div>
    </div>
</header>