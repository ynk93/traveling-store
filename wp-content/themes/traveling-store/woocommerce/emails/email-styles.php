<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load colors.
$bg        = get_option( 'woocommerce_email_background_color' );
$body      = get_option( 'woocommerce_email_body_background_color' );
$base      = get_option( 'woocommerce_email_base_color' );
$base_text = wc_light_or_dark( $base, '#202020', '#ffffff' );
$text      = get_option( 'woocommerce_email_text_color' );

// Pick a contrasting color for links.
$link_color = wc_hex_is_light( $base ) ? $base : $base_text;

if ( wc_hex_is_light( $body ) ) {
	$link_color = wc_hex_is_light( $base ) ? $base_text : $base;
}

$bg_darker_10    = wc_hex_darker( $bg, 10 );
$body_darker_10  = wc_hex_darker( $body, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$base_lighter_40 = wc_hex_lighter( $base, 40 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );
$text_lighter_40 = wc_hex_lighter( $text, 40 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
// body{padding: 0;} ensures proper scale/positioning of the email in the iOS native email app.
?>
    table.mainTable {
    width: 682px;
    margin: 0 auto;
    border: 1px solid #ebebeb;
    border-spacing: 0;
    font-size: 14px;
    font-family: Arial, sans-serif;
    }

    table.mainTable span {
    color: #7E7E7E;
    vertical-align: middle;
    }

    table.mainTable p {
    color: #7E7E7E;
    font-size: 14px;
    line-height: 20px;
    margin: 0;
    }

    table.mainTable p + p {
    margin-top: 8px;
    }

    .bottomTextWrap {
    padding: 24px 20px;
    display: block;
    border-top: 1px solid #ebebeb;
    }

    .bottomTextWrap .bold {
    margin-bottom: 8px;
    display: block;
    }

    table.mainTable span.bold {
    padding: 0;
    color: #000;
    font-weight: bold;
    }

    a.textLink {
    color: #148EFF;
    }

    table.mainTable .mailHead {
    display: block;
    line-height: 24px;
    padding: 26px 20px;
    }

    table.mainTable .logo {
    display: block;
    width: 155px;
    height: 55px;
    background: url('../images/mailTemplates/logo.png') no-repeat center/contain;
    }

    table.logoRowTable {
    padding: 10px 0;
    width: 100%;
    border-top: 1px solid #EBEBEB;
    }

    .ticketNumWrap {
    padding-right: 20px;
    display: block;
    text-align: right;
    margin-left: auto;
    }

    .ticketNumWrap span {
    display: block;
    padding: 0;
    }

    .ticketNumWrap .ticketNumber {
    color: #F87F27;
    margin-top: 4px;
    font-weight: bold;
    }

    .mainDataTable {
    border-spacing: 0;
    }

    table.mainDataTable span {
    display: block;
    padding: 0 20px;
    color: #000;
    }

    .mainDataTable tbody tr td {
    border-top: 1px solid #ebebeb;
    border-left: 1px solid #ebebeb;
    height: 56px;
    width: calc(100% / 4);
    }

    .mainDataTable tr td:first-child {
    border-left: 0;
    font-weight: bold;
    }

    .mainDataTable tr.mainRow td {
    border-top: 11px solid #ebebeb;
    }

    .mainDataTable tr.mainRow td td {
    border-top: 0;
    border-left: 0;
    font-size: 12px;
    line-height: 12px;
    height: 24px;
    border-bottom: 1px solid #ebebeb;
    font-weight: normal;
    color: #7E7E7E;

    }

    .mainDataTable tr.mainRow td tr:last-child td {
    border-bottom: 0;
    }

    .mainDataTable table {
    border-spacing: 0;
    }

    .mainDataTable tr.mainRow td td span span{
    display: inline-block;
    vertical-align: middle;
    padding: 0;
    }
    .mainDataTable tr.mainRow td td span {
    padding: 0 14px 0 20px;
    }

    .mainDataTable tr.mainRow td td .innerSpanDescription {
    color: #7e7e7e;
    }

    .bottomTableRow {
    border-top: 1px solid #ebebeb;
    padding: 24px 0;
    }

    .bottomTableRow td {
    padding: 0 20px;
    }
    .bottomTableRow td:last-child {
    text-align: right;
    width: 100%;
    }

    .bottomTableRow td a {
    color: #222;
    text-decoration: none;
    }

    .bottomTableRow .contactBlock {
    padding-left: 30px;
    }

<?php
