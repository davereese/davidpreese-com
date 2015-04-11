<?php
/**
 * Template Name: Checkbook
 *
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
get_header();
if(is_user_logged_in()) { ?>
<a href="<?php echo wp_logout_url( get_permalink() ); ?>" class="logout-btn">Logout</a>
<div class="checkbook container">
	<div ng-controller="RegisterController as register" repeater-directive class="checkbook-content">
		<search></search>
		<div class="row details headers">
			<div class="col-sm-4 col-sm-push-3 hidden-xs">
				<div class="row">
					<div class="col-xs-12">Transaction Description</div>
				</div>
			</div>
			<div class="col-sm-3 col-xs-5 col-sm-pull-4">
				<div class="row">
					<div class="col-sm-1 hidden-xs"></div>
					<div class="col-sm-4 col-xs-4">Check</div>
					<div class="col-sm-7 col-xs-8">Date</div>
				</div>
			</div>
			<div class="col-sm-5 col-xs-7">
				<div class="row">
					<div class="col-sm-11 col-xs-12">
						<div class="col-xs-4">Pay<span class="hidden-xs">ment</span> -</div>
						<div class="col-xs-4">Dep<span class="hidden-xs">osit</span> +</div>
						<div class="col-xs-4" ng-class="{ 'hidden': q }">Bal<span class="hidden-xs">ance</span></div>
					</div>
					<div class="col-sm-1 hidden-xs">
						<div class="col-sm-12"></div>
					</div>
				</div>
			</div>
		</div>
		<transrepeater></transrepeater>
		<totals></totals>
		<addform></addform>
	</div>
	<div ng-controller="pagiController" class="other-controller">
		<div class="text-center">
			<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="../wp-content/themes/DPR5/checkbook/dirPagination.tpl.html"></dir-pagination-controls>
			<p><a href="https://secure.ally.com/allyWebClient/login.do" target="_blank">Ally Bank Login</a></p>
		</div>
	</div>
	<div ng-controller="trackerController" class="tracker-controller">
		<div class="row tracker">
			<div ng-repeat="tracker in transTrackers"><tracker></tracker></div>
		</div>
	</div>
</div>

<?php
} else { ?>
	<section ng-controller="loginController" class="login-controller <?php 
	if ( isset($_GET['login']) ) {
		if ( 'failed' == $_GET['login'] ) {
			echo 'failed';
		} 
	} ?>">
		<div bs-modal="loginModal"></div>
	</section>
<?php } 
get_footer();
?>
