<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

<?php switch($wp_query->post->post_title) {
	case 'Home': ?>
    
    <div id="content">
	
    <div class="arrow-left"></div>
	<div class="arrow-right"></div>
	<?php query_posts( array ( 'category_name' => 'work', 'posts_per_page' => -1 ) ); ?>
	<?php /* The loop */ ?>
    <?php $count = 1; ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="project_wrap<?php if($wp_query->found_posts == $count) { echo ' last'; } ?>" id="<?php echo $count; ?>">
        	<?php if ( has_post_format( 'video' )) {
				echo '<div class="featured"><div class="videoWrapper">';
 					the_content();
				echo '</div></div><div class="content_wrap"><h2>';
						the_title();
				echo '</h2>';
				the_excerpt();
				echo '</div><div class="tags_wrap">';
				echo strip_tags(get_the_tag_list('',',&nbsp;&nbsp;',''));
				echo '</div>';
			} else {
				echo '<div class="featured">';
				echo '<a href="';
				echo get_post_meta($post->ID, 'Link', true);
				echo '" target="_blank">';
					the_post_thumbnail( 'full' ); 
				echo '</a></div><div class="content_wrap"><h2>';
					the_title();
				echo '</h2>';
					the_content();
				echo '</div><div class="tags_wrap">';
				echo strip_tags(get_the_tag_list('',',&nbsp;&nbsp;',''));
				echo '</div>';
			}?>
            
            <span class="current_post<?php if($wp_query->found_posts == $count) { echo ' last_post'; } ?>" id="post<?php echo $count; ?>"><?php echo $wp_query->current_post+1; ?></span>
		</div><!-- .project_wrap -->
	<?php $count++; ?>
	<?php endwhile; ?>
    
    </div><!-- #content -->
	<div class="count">
    	<span class="out_of">/</span>
    	<span class="total">
    	<?php
		$postsInCat = get_term_by('name','Work','category');
		$postsInCat = $postsInCat->count;
		echo $postsInCat;
		?>
        </span> <!-- .total -->
    </div> <!-- .count -->

	<?php break;
	case 'About': ?>
    
    	<div class="project_wrap_about">
        <?php /* The loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
        	<?php echo '<div class="float_right"><div class="featured"></div>'; ?>
        		<script>
        		var picUrl = 'https://graph.facebook.com/23508050/picture?width=300';
$('.featured').html('<div class="center"><img src="' + picUrl + '" class="attachment-full wp-post-image" /></div>');
				</script>
				<?php //$call = new WP_Query("page_id=27"); while($call->have_posts()) : $call->the_post(); the_post_thumbnail( 'full' ); endwhile; wp_reset_query();
			echo '<!--</div><div class="resume"><a href="';
			//echo esc_url( home_url( "/" ) );
			echo 'wp-content/uploads/2013/11/David_Reese_Resume.pdf" class="resume_link" target="_blank">RÉSUMÉ</a><div class="arrow"><img src="'.get_stylesheet_directory_uri().'/images/resume_arrow.png" class="attachment-full wp-post-image"></div></div>--></div>'; ?>
    		<?php $call = new WP_Query("page_id=27"); while($call->have_posts()) : $call->the_post(); the_content(); endwhile; wp_reset_query(); ?>
        <?php endwhile; ?>
        </div><!-- .project_wrap -->
    
    <?php break;
} ?>

<?php get_footer(); ?>