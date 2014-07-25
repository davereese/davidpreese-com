<?php
// edits the wp_head to remove unused items
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');

function scripts() {
	wp_register_script('main', get_stylesheet_directory_uri() . '/js/main.js', array(), null, true);
	wp_enqueue_script('main');
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

?>