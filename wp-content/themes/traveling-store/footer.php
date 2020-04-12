<footer class="footer">
    <div class="content">

        <div class="footerInnerRow">

			<?php $footer_navigation_menu = wp_nav_menu( [
				'theme_location' => 'footer-navigation',
				'container'      => false,
				'menu_class'     => 'menu',
				'menu_id'        => '',
				'echo'           => false,
				'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'depth'          => 0,
			] );

				if ( $footer_navigation_menu ) {
					echo $footer_navigation_menu;
				}

				$contacts_footer_navigation_menu = wp_nav_menu( [
					'theme_location' => 'contacts-footer-nav',
					'container'      => false,
					'menu_class'     => 'menu',
					'menu_id'        => '',
					'echo'           => false,
					'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'depth'          => 0,
				] );

				if ( $contacts_footer_navigation_menu ) : ?>

                    <div class="contactLinks">

						<?php echo $contacts_footer_navigation_menu ?>

                    </div>

				<?php endif; ?>

        </div>

        <div class="footerInnerRow">

            <span class="copyRight p">
                <?php _e( 'Traveling Store 2020 ©. All rights reserved.', 'traveling-store' ); ?>
            </span>

			<?php $social_footer_navigation_menu = wp_nav_menu( [
				'theme_location' => 'social-footer-nav',
				'container'      => false,
				'menu_id'        => '',
				'echo'           => false,
				'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'depth'          => 0,
			] );

				if ( $social_footer_navigation_menu ) : ?>

                    <div class="socialLinksWrap">

						<?php echo $social_footer_navigation_menu; ?>

                    </div>

				<?php endif; ?>

        </div>

    </div>
</footer>