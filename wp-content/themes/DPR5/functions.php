<?php
// edits the wp_head to remove unused items
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');

//edits the default image upoload settings to not add a link to the image
function mytheme_setup() {
	// Set default values for the upload media box
	update_option('image_default_align', 'none' );
	update_option('image_default_link_type', 'none' );
	update_option('image_default_size', 'large' );

}
add_action('after_setup_theme', 'mytheme_setup');

?>