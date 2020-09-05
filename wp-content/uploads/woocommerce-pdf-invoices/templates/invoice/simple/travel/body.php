<?php
$theme_color            = $this->template_options['bewpi_color_theme'];
$is_theme_text_black    = $this->template_options['bewpi_theme_text_black'];
$columns_count          = 4;
echo $this->outlining_columns_html( count( $this->order->get_taxes() ) );

do_action( 'woocommerce_email_order_details', $this->order );

?>
