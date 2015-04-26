<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<?php if( is_page('checkbook') ) { ?>
    <html <?php language_attributes(); ?> ng-app="checkbook">
<?php } else { ?>
    <html <?php language_attributes(); ?>>
<?php } ?>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title>David P. Reese | Onward to Awesome</title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.ico">
    <link href='http://fonts.googleapis.com/css?family=Gochi+Hand' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
    <?php if( is_page('checkbook') ) { ?>
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <?php } else { ?>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.js" type="text/javascript"></script>
    <?php } ?>
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->
</head>

<body>
    <?php if( !is_page('checkbook') ) { ?>
	<div id="red"> <!-- the color div -->
        <div id="body_container"></div><!-- #body_container -->
        <header class="dpr_header">
            <h1>DPR.</h1>
            <nav>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">WORK</a> / <a href="<?php echo esc_url( home_url( '/' ) ); ?>/about/">ABOUT</a>
            </nav>
        </header><!-- .dpr_header -->
<?php } ?>