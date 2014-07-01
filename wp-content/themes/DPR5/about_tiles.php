<?php
/**
 * Template Name: About Tiles
**/

get_header(); ?>

<div id="red" class="main gradient">

</div><!--/#red-->

<script type="text/javascript">
	$(document).ready(function(){
		function randomFrom(array) {
			return array[Math.floor(Math.random() * array.length)];
		}
		var color = randomFrom(['red', 'orange', 'yellow', 'green', 'blue', 'purple', 'brown'])
		$('.main').attr('id', color);
	});
</script>

<?php get_footer(); ?>