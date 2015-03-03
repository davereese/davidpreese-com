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
		<div class="row filters">
			<div class="col-sm-4 col-sm-offset-2 col-xs-6">
	        	<label for="search">Search:</label>
	        	<input type="text" autocomplete="off" ng-model="q" id="search" class="form-control" placeholder="Check, desc, tag etc.">
	        </div>
	        <div class="col-sm-2 col-xs-3">
				<label for="search">Year:</label>
				<input type="text" id="yearpicker" class="form-control" placeholder="Date" ng-model="transYear" bs-datepicker data-placement="bottom" data-date-format="yyyy" data-min-view="2">
			</div>
			<div class="col-sm-2 col-xs-3">
	        	<label for="search"> Per page:</label>
	        	<input type="number" min="1" max="100" class="form-control" ng-model="pageSize">
			</div>
		</div>
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
						<div class="col-xs-4">Bal<span class="hidden-xs">ance</span></div>
					</div>
					<div class="col-sm-1 hidden-xs">
						<div class="col-sm-12"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row details trans" dir-paginate="transaction in transactions | filter:q | filter:transYear | itemsPerPage: pageSize" current-page="currentPage" id="row_{{transaction.transnum}}" style-repeater>
			<div class="col-sm-4 col-xs-12 col-sm-push-3">
				<div class="row">
					<div class="col-xs-1 visible-xs"><a ng-click="showModal(transaction)" data-animation="am-fade-and-scale" data-placement="center"><i class="fa fa-times"></i></a></div>
					<div class="col-xs-9 col-sm-11 description">{{transaction.description}}</div>
					<div class="col-xs-1 tags"><i class="fa fa-tag" data-placement="top" data-title="{{transaction.tags}}" bs-tooltip ng-class="{ 'has-tags': transaction.tags }"></i></div>
					<div class="col-xs-1 visible-xs hilighter"><a ng-click="highlightTrans(transaction, transactions)"><i class="fa fa-check"></i></a></div>
				</div>
			</div>
			<div class="col-sm-3 col-xs-5 col-sm-pull-4">
				<div class="row">
					<div class="col-xs-1 hidden-xs"><a ng-click="showModal(transaction)" data-animation="am-fade-and-scale" data-placement="center"><i class="fa fa-times"></i></a></div>
					<div class="col-sm-4 col-xs-4">{{transaction.check_number}}</div>
					<div class="col-sm-7 col-xs-8">{{transaction.date}}</div>
				</div>
			</div>
			<div class="col-sm-5 col-xs-7">
				<div class="row">
					<div class="col-sm-11 col-xs-12">
						<div class="col-xs-4">{{transaction.payment | currency}}</div>
						<div class="col-xs-4">{{transaction.deposit | currency}}</div>
						<div class="col-xs-4">{{transaction.balance | currency}}</div>
					</div>
					<div class="col-xs-1 hidden-xs hilighter">
						<div class="col-xs-12"><a ng-click="highlightTrans(transaction, transactions)"><i class="fa fa-check"></i></a></div>
					</div>
				</div>
			</div>
		</div>
		<form name="transactionForm" ng-submit="transactionForm.$valid && addData(transactions)" novalidate>
			<div class="row details draft">
				<div class="col-sm-4 col-xs-12 col-sm-push-3">
					<div class="row">
						<div class="col-xs-11">{{newTrans.desc}}</div>
						<div class="col-xs-1 tags" ng-class="{ 'has-tags': newTrans.transtags }"><i class="fa fa-tag" data-placement="top" data-title="{{newTrans.transtags}}" bs-tooltip></i></div>
					</div>
				</div>
				<div class="col-sm-3 col-xs-5 col-sm-pull-4">
					<div class="row">
						<div class="col-xs-1"></div>
						<div class="col-sm-4 col-xs-4">{{newTrans.check_number}}</div>
						<div class="col-sm-7 col-xs-7">{{newTrans.date}}</div>
					</div>
				</div>
				<div class="col-sm-5 col-xs-7">
					<div class="row">
						<div class="col-xs-11">
							<div class="col-xs-4">{{newTrans.payment | currency}}</div>
							<div class="col-xs-4">{{newTrans.deposit | currency}}</div>
							<div class="col-xs-4">{{newTrans.balance | currency}}</div>
						</div>
						<div class="col-xs-1">
							<div class="col-xs-12"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="row details inputs">
				<div class="col-sm-4 col-xs-12 col-sm-push-3">
					<div class="row">
						<div class="col-xs-11"><input type="text" class="form-control description_input" placeholder="Transaction Description" ng-model="newTrans.desc" required tabindex=3><input type="text" class="form-control tags_input" placeholder="Tags (separate by comma)" ng-model="newTrans.transtags" tabindex=3></div>
						<div class="col-xs-1 tags"><i class="fa fa-tags" title="Tags"></i></div>
					</div>
				</div>
				<div class="col-sm-3 col-xs-6 col-sm-pull-4">
					<div class="row">
						<div class="col-sm-5 col-xs-5"><input type="text" class="form-control" placeholder="Check" ng-model="newTrans.check_number" tabindex=1></div>
						<div class="col-sm-7 col-xs-7"><input type="text" id="datepicker" class="form-control" placeholder="Date" ng-model="newTrans.date" bs-datepicker required tabindex=2></div>
					</div>
				</div>
				<div class="col-sm-5 col-xs-6">
					<div class="row">
						<div class="col-xs-12">
							<div class="col-sm-4 col-xs-6"><input type="text" class="form-control" placeholder="Payment" ng-model="newTrans.payment" tabindex=4></div>
							<div class="col-sm-4 col-xs-6"><input type="text" class="form-control" placeholder="Deposit" ng-model="newTrans.deposit" tabindex=5></div>
							<div class="col-sm-4 col-xs-12"><input type="submit" class="form-control" value="Submit" tabindex=6></div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div ng-controller="pagiController" class="other-controller">
		<div class="text-center">
			<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="../wp-content/themes/DPR5/checkbook/dirPagination.tpl.html"></dir-pagination-controls>
			<p><a href="https://secure.ally.com/allyWebClient/login.do" target="_blank">Ally Bank Login</a></p>
		</div>
	</div>
	<!-- <div ng-controller="trackerController" class="tracker-controller">
		<div class="row tracker" ng-repeat="tracker in transTrackers">
			<div class="col-sm-4">
				<h4><i class="fa fa-align-left"></i> {{tracker.transFilter}} - ${{tracker.loanAmount}}</h4>
				<div class="tracker-bg">
					<div class="tracker-fill" style="width:{{tracker.loanPaymentSum}}%;" data-placement="bottom" data-title="${{tracker.payments}}" bs-tooltip></div>
				</div>
			</div>
		</div>
	</div> -->
	<div ng-controller="trackerController" class="tracker-controller">
		<div class="row tracker">
			<div class="col-lg-3 col-md-6 col-sm-6">
				<h4><i class="fa fa-bar-chart"></i> {{transFilter1}} - ${{loanAmount1}}</h4>
				<div class="tracker-bg">
					<div class="tracker-fill" style="width:{{loanPaymentSum1}}%;" data-placement="bottom" data-title="${{payments1}}" bs-tooltip></div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6">
				<h4><i class="fa fa-area-chart"></i> {{transFilter2}} - ${{loanAmount2}}</h4>
				<div class="tracker-bg">
					<div class="tracker-fill" style="width:{{loanPaymentSum2}}%;" data-placement="bottom" data-title="${{payments2}}" bs-tooltip></div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6">
				<h4><i class="fa fa-pie-chart"></i> {{transFilter3}} - ${{loanAmount3}}</h4>
				<div class="tracker-bg">
					<div class="tracker-fill" style="width:{{loanPaymentSum3}}%;" data-placement="bottom" data-title="${{payments3}}" bs-tooltip></div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6">
				<h4><i class="fa fa-line-chart"></i> {{transFilterTotals}} - ${{loanAmountTotals}}</h4>
				<div class="tracker-bg">
					<div class="tracker-fill" style="width:{{loanPaymentSumTotals}}%;" data-placement="bottom" data-title="${{paymentsTotals}}" bs-tooltip></div>
				</div>
			</div>
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
