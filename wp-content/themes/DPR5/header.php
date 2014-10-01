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
    <link href='http://fonts.googleapis.com/css?family=Allura|Open+Sans:400italic,700italic,800italic,300italic,600italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/easing.js" type="text/javascript"></script>
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->
</head>

<body>
<?php switch($wp_query->post->ID) {
case '6':
case '99': 
case '27':
case '113':?>
	<div id="red"> <!-- the color div -->
    <div id="body_container"></div><!-- #body_container -->
    <header class="dpr_header">
        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>">D<span class="expand">P</span>R</a></h1>
        <nav>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>/work-1/">WORK</a> / <a href="<?php echo esc_url( home_url( '/' ) ); ?>/about-1/">ABOUT</a>
        </nav>
    </header><!-- .dpr_header -->
<?php break;
case '101':
case '116': ?>
    <div id="orange"> <!-- the color div -->
    <div id="body_container"></div><!-- #body_container -->
    <header class="dpr_header">
        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>">D<span class="expand">P</span>R</a></h1>
        <nav>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>/work-2/">WORK</a> / <a href="<?php echo esc_url( home_url( '/' ) ); ?>/about-2/">ABOUT</a>
        </nav>
    </header><!-- .dpr_header -->
<?php break;
case '103':
case '118': ?>
    <div id="yellow"> <!-- the color div -->
    <div id="body_container"></div><!-- #body_container -->
    <header class="dpr_header">
        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>">D<span class="expand">P</span>R</a></h1>
        <nav>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>/work-3/">WORK</a> / <a href="<?php echo esc_url( home_url( '/' ) ); ?>/about-3/">ABOUT</a>
        </nav>
    </header><!-- .dpr_header -->
<?php break;
case '105':
case '120': ?>
    <div id="green"> <!-- the color div -->
    <div id="body_container"></div><!-- #body_container -->
    <header class="dpr_header">
        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>">D<span class="expand">P</span>R</a></h1>
        <nav>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>/work-4/">WORK</a> / <a href="<?php echo esc_url( home_url( '/' ) ); ?>/about-4/">ABOUT</a>
        </nav>
    </header><!-- .dpr_header -->
<?php break;
case '107':
case '122': ?>
    <div id="blue"> <!-- the color div -->
    <div id="body_container"></div><!-- #body_container -->
    <header class="dpr_header">
        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>">D<span class="expand">P</span>R</a></h1>
        <nav>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>/work-5/">WORK</a> / <a href="<?php echo esc_url( home_url( '/' ) ); ?>/about-5/">ABOUT</a>
        </nav>
    </header><!-- .dpr_header -->
<?php break;
case '109':
case '124': ?>
    <div id="purple"> <!-- the color div -->
    <div id="body_container"></div><!-- #body_container -->
    <header class="dpr_header">
        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>">D<span class="expand">P</span>R</a></h1>
        <nav>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>/work-6/">WORK</a> / <a href="<?php echo esc_url( home_url( '/' ) ); ?>/about-6/">ABOUT</a>
        </nav>
    </header><!-- .dpr_header -->
<?php break;
case '111':
case '126': ?>
    <div id="brown"> <!-- the color div -->
    <div id="body_container"></div><!-- #body_container -->
    <header class="dpr_header">
        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>">D<span class="expand">P</span>R</a></h1>
        <nav>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>/work-7/">WORK</a> / <a href="<?php echo esc_url( home_url( '/' ) ); ?>/about-7/">ABOUT</a>
        </nav>
    </header><!-- .dpr_header -->
<?php break;
} ?>