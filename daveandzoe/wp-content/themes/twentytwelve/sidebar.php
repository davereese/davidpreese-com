<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
			<ul>
				<li><a href="http://davidpreese.com/daveandzoe/">Dave and Zoë</a></li>
				<li><a href="http://davidpreese.com/daveandzoe/camp-paxson/">Camp Paxson</a></li>
				<li><a href="http://davidpreese.com/daveandzoe/accommodations/">Accommodations</a></li>
				<li><a href="http://davidpreese.com/daveandzoe/registry/">Registry</a></li>
				<li><a href="http://davidpreese.com/daveandzoe/guestbook/">Guestbook</a></li>
			</ul>
		</div><!-- #secondary -->
	<?php endif; ?>