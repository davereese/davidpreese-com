<?php
// edits the wp_head to remove unused items
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');

function scripts() {
	wp_register_script('easing', get_stylesheet_directory_uri() . '/js/easing.js', array(), null, true);
	wp_register_script('main', get_stylesheet_directory_uri() . '/js/main.js', array(), null, true);
	wp_register_script('angular', get_stylesheet_directory_uri() . '/js/angular.min.js', array(), null, true);
	wp_register_script('angularsanitize', 'http://ajax.googleapis.com/ajax/libs/angularjs/1.2.18/angular-sanitize.js', array(), null, true);
	wp_register_script('select', get_stylesheet_directory_uri() . '/js/select.js', array(), null, true);
	wp_register_script('angularPagination', get_stylesheet_directory_uri() . '/js/angular-pagination/dirPagination.js', array(), null, true);
	wp_register_script('app', get_stylesheet_directory_uri() . '/checkbook/app.js', array(), null, true);
	wp_register_script('angularanimatejs', 'http://code.angularjs.org/1.2.23/angular-animate.min.js', array(), null, true);
	wp_register_script('angularstrapjs', '//cdnjs.cloudflare.com/ajax/libs/angular-strap/2.1.2/angular-strap.min.js', array(), null, true);
	wp_register_script('angularstraptpljs', '//cdnjs.cloudflare.com/ajax/libs/angular-strap/2.1.2/angular-strap.tpl.min.js', array(), null, true);
	wp_register_script('tags', get_stylesheet_directory_uri() . '/bower_components/ng-tags-input/ng-tags-input.js', array(), null, true);

	wp_enqueue_script('easing');
	if( !is_page('checkbook') ) {
		wp_enqueue_script('main');
	}
	if( is_page('checkbook') ) {
		wp_enqueue_script('angular');
		wp_enqueue_script('angularsanitize');
		wp_enqueue_script('select');
		wp_enqueue_script('angularPagination');
		wp_enqueue_script('angularanimatejs');
		wp_enqueue_script('angularstrapjs');
		wp_enqueue_script('angularstraptpljs');
		wp_enqueue_script('tags');
		wp_enqueue_script('app');
		wp_enqueue_style('angularMotion', get_stylesheet_directory_uri() . '/css/angular-motion.min.css', false, null);
		wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css', false, null);
		wp_enqueue_style('tags-css', get_stylesheet_directory_uri() . '/bower_components/ng-tags-input/ng-tags-input.min.css', false, null);
		wp_enqueue_style('lato', 'http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic', false, null);
	}
}
add_action('wp_enqueue_scripts', 'scripts', 100);

//edits the default image upoload settings to not add a link to the image
function mytheme_setup() {
	// Set default values for the upload media box
	update_option('image_default_align', 'none' );
	update_option('image_default_link_type', 'none' );
	update_option('image_default_size', 'large' );

}
add_action('after_setup_theme', 'mytheme_setup');

add_action( 'wp_login_failed', 'my_front_end_login_fail' );  // hook failed login

function my_front_end_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
   // if there's a valid referrer, and it's not the default log-in screen
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
      wp_redirect( add_query_arg('login', 'failed', $referrer) );
      exit;
   }
}

?>