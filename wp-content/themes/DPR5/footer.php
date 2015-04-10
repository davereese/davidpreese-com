<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

 <?php if( !is_page('checkbook') ) { ?>
 <footer class="dpr_footer">
	
</footer><!-- .dpr_footer -->
</div> <!-- end the color div -->
<?php } ?>
		
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-5107661-4");
pageTracker._trackPageview();
} catch(err) {}</script>

<?php wp_footer(); ?>
</body>
</html>