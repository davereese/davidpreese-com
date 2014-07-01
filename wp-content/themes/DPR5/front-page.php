<?php
/**
 * Template Name: Front Page Template
 */
?>
<?php get_header(); ?>

<div id="white"></div><!--/#white-->
<div id="red" class="main gradient">
	<div class="onward_to_awesome">
		<div class="dpr">
			<span class="cap">D</span>avi<span class="expand">d</span> <span class="cap contract">P</span>. <span class="cap">R</span>eese
		</div><!--/.dpr-->
		<div class="onward">
			On<span class="contract">w</span><span class="expand">a</span><span class="expand">r</span>d
		</div><!--/.onward-->
		<div class="awesome">
			<div class="to">
				to
			</div><!--/.to-->
			<span class="contract">A</span>w<span class="expand">e</span>some
		</div><!--/.awesome-->
	</div><!--/.onward-->

</div><!--/#red-->

<script type="text/javascript">
	$(document).ready(function(){
		$('#body_container').css('display', 'none');
		
		function randomFrom(array) {
			return array[Math.floor(Math.random() * array.length)];
		}
		var color = randomFrom(['red', 'orange', 'yellow', 'green', 'blue', 'purple', 'brown'])
		$('.main').attr('id', color);
		
		$('.onward, .onward .expand').css('letter-spacing','.1em');
		$('.onward .contract').css('letter-spacing','.05em');
		$('.awesome, .awesome .expand').css('letter-spacing','.085em');
		$('.awesome .contract').css('letter-spacing','.04em');
		$('.dpr').css('margin-top','-15px');

		var toMargin = parseInt($('.to').css('margin-left'));
		var toMarginPlus = toMargin-30+'px';

		$('.to').css('margin-left',''+toMarginPlus+'');

		$('.to').delay(75).animate({ marginLeft:''+toMargin+'' }, 1250, 'easeInOutExpo', function() { $('.to').removeAttr("style") });
		$('.dpr').delay(75).animate({ marginTop:'0px' }, 1250, 'easeInOutExpo');		
		$('.onward, .awesome').delay(75).animate({ letterSpacing:'-.055em' }, 1250, 'easeInOutExpo');
		$('.onward .expand, .awesome .expand').delay(75).animate({ letterSpacing:'-.03em' }, 1250, 'easeInOutExpo');
		$('.onward .contract, .awesome .contract').delay(75).animate({ letterSpacing:'-0.1em' }, 1250, 'easeInOutExpo');
		
		$('#white').delay(100).fadeOut(1200, 'easeInOutExpo');
		
		$('.main').delay(2400).fadeOut(700, 'easeInOutExpo', function() {
			if(color == 'red') {
				window.location.replace("<?php echo esc_url( home_url( '/' ) ); ?>work-1/");
			} else if(color == 'orange') {
				window.location.replace("<?php echo esc_url( home_url( '/' ) ); ?>work-2/");
			} else if(color == 'yellow') {
				window.location.replace("<?php echo esc_url( home_url( '/' ) ); ?>work-3/");
			} else if(color == 'green') {
				window.location.replace("<?php echo esc_url( home_url( '/' ) ); ?>work-4/");
			} else if(color == 'blue') {
				window.location.replace("<?php echo esc_url( home_url( '/' ) ); ?>work-5/");
			} else if(color == 'purple') {
				window.location.replace("<?php echo esc_url( home_url( '/' ) ); ?>work-6/");
			} else if(color == 'brown') {
				window.location.replace("<?php echo esc_url( home_url( '/' ) ); ?>work-7/");
			}
		});
	});
</script>

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