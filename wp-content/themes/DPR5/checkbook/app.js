/* global angular:false */
(function() {
	var app = angular.module('checkbook', ['mgcrea.ngStrap', 'ngAnimate', 'angularUtils.directives.dirPagination']);

	app.controller('RegisterController', function($scope, getTransDataService, $modal) {
		$scope.transactions = [];

		loadTransData();

		// TODO - when filtering with text search, add sum of showing transactions
		// TODO - do not filter student loan totals, get sum from other filters
		// TODO - load and parse all trackers from json
		// TODO - possibly reverse order so newest transaction is first?

		//--------------- Long term goals --------------//
		// - Add tags to list of tags, display in dropdown with typeahead filtering
		// - add button to create a tracker from current search filters
		// - monthly/yearly spending graphs
		//----------------------------------------------//

		// broadcast that event happened
		$scope.showCtrl1 = function () {
			$scope.$root.$broadcast('transEvent', {});
		};

		// Process the transactionForm form.
        $scope.addData = function(entries) {
            getTransDataService.addData( $scope.newTrans.check_number, $scope.newTrans.date, $scope.newTrans.desc, $scope.newTrans.transtags, $scope.newTrans.payment, $scope.newTrans.deposit)
                .then( loadTransData, function( errorMessage ) {
                    console.warn( errorMessage );
                }
            );
            // Reset the form once values have been consumed.
            $scope.newTrans.check_number = '';
            $scope.newTrans.date = '';
            $scope.newTrans.desc = null;
            $scope.newTrans.transtags = null;
            $scope.newTrans.payment = null;
            $scope.newTrans.deposit = null;
            $scope.transactionForm.$setPristine(true);
        };

        // Remove the given transaction from the current collection.
        $scope.removeData = function( trans, entries, thisModal ) {
			thisModal.$hide();

			getTransDataService.removeData( trans.id )
				.then( loadTransData, function( errorMessage ) {
					console.warn( errorMessage );
				});
		};

		// Edit the given transaction from the current collection.
        $scope.editData = function( trans, entries, thisModal ) {
			thisModal.$hide();

			getTransDataService.editData( trans.id, trans.check_number, trans.date, trans.description, trans.tags, trans.payment, trans.deposit)
                .then( loadTransData, function( errorMessage ) {
                    console.warn( errorMessage );
                }
            );
		};

		// Apply current year filter to transactions list
		var year_var = new Date().getFullYear();

		// Apply the remote data to the local scope.
        function applyRemoteData( newTrans ) {
			var i = 0;
			for (i = 0; i < newTrans.length; i++) {
				// Parse data
				if ( '0' === newTrans[i].check_number ) {
					newTrans[i].check_number = '';
				}
				if ( 0 === i ) {
					newTrans[i].balance = parseFloat(newTrans[i].deposit)-parseFloat(newTrans[i].payment);
				} else {
					newTrans[i].balance = parseFloat(newTrans[i - 1].balance)+parseFloat(newTrans[i].deposit)-parseFloat(newTrans[i].payment);
				}
			}

			var pageSize = '';
			var lastPage = '';
			var initialLoad = 0;
			var numChange = 0;
			var entriesNum = newTrans.length;

			if ( 0 === $scope.transactions.length ) {
				initialLoad = 1;
			}
			if ( $scope.transactions.length !== entriesNum ) {
				numChange = 1;
			}

			pageSize = $scope.pageSize;
			if ( undefined === pageSize ) {
				pageSize = 20;
			}
			lastPage = Math.ceil(entriesNum / pageSize);
			$scope.transactions = newTrans;
			$scope.transYear = year_var;
			$scope.pageSize = pageSize;

			if ( 0 === initialLoad && 0 === numChange ) {
				$scope.currentPage = $scope.currentPage;
			} else if ( 1 === initialLoad || 1 === numChange ) {
				$scope.currentPage = lastPage;
			}
		}

        // Load the remote data from the server.
        function loadTransData() {
            // The getTransDataService returns a promise.
            getTransDataService.getData()
                .then(function( trans ) {
					$scope.showCtrl1();
                    applyRemoteData( trans );
                });
        }

        $scope.highlightTrans = function(trans, entries) {
			var transIndex = entries.indexOf(trans);
			if ( '0' === entries[transIndex].highlight ) {
				entries[transIndex].highlight = 1;
			} else if ( '1' === entries[transIndex].highlight ) {
				entries[transIndex].highlight = 0;
			}
			getTransDataService.updateData( entries[transIndex].id, entries[transIndex].highlight )
						.then( loadTransData, function( errorMessage ) {
							console.warn( errorMessage );
						}
					);
		};

		$scope.showModal = function( trans ) {
			$scope.transaction = trans;

			// Show a basic modal from a controller
			var myModal = $modal({
				animation: 'am-fade-and-slide-bottom',
				title: 'Edit '+trans.description+'',
				scope: $scope,
				template: '../wp-content/themes/DPR5/checkbook/editModal.tpl.html',
				show: false
			});
				myModal.$promise.then(myModal.show);
		};
	});

	app.controller('pagiController', function($scope) {
		$scope.pageChangeHandler = function(num) {
		};
	});

	app.controller('loginController', function($scope, $modal) {
			// Show a basic modal from a controller
			var loginModal = $modal({
				animation: 'am-fade',
				title: 'Login',
				scope: $scope,
				container: 'section',
				template: '../wp-content/themes/DPR5/checkbook/loginModal.tpl.html',
				show: false
			});
			loginModal.$promise.then(loginModal.show);
	});

	app.controller('trackerController', function($scope, getTransDataService, $filter, $http) {
		$scope.transTrackers = [];

		$scope.$on("transEvent", function (event, args) {
			loadTransData();
		});

		// TODO - Figure this out
		function applyRemoteData2( trackers, newTrans ) {
			var count = 1;
			for (count = 1; count < trackers.length; count++) {
				// $scope[transFilter+count] = trackers[count-1]['Transaction Filter'];
				// $scope[loanAmount+count] = trackers[count-1]['Tracker Ammount'];
				// var loanPayments[count] = [];
				// var loanTransactions[count] = $filter('filter')(newTrans, $scope.transFilter[count]);

	   //          for (i = 0; i < loanTransactions[count].length; i++) {
				// 	loanPayments[count].push(parseFloat(loanTransactions[count][i].payment));
				// }
				// $scope.payments[count] = $scope.loanAmount[count];
				// if (0 < loanPayments[count].length) {
				// 	$scope.payments[count] = $scope.loanAmount[count]-loanPayments[count].reduce(function(prev, cur) {
				// 		return prev + cur;
				// 	});
				// }
				// $scope.loanPaymentSum[count] = $scope.payments[count]/$scope.loanAmount[count]*100;
			}
		}

        function applyRemoteData( trackers, newTrans ) {
			$scope.transFilter1 = trackers[0]['Transaction Filter'];
			$scope.loanAmount1 = trackers[0]['Tracker Ammount'];
			var loanPayments1 = [];
			var loanTransactions1 = $filter('filter')(newTrans, $scope.transFilter1);

            for (i = 0; i < loanTransactions1.length; i++) {
				loanPayments1.push(parseFloat(loanTransactions1[i].payment));
			}
			$scope.payments1 = $scope.loanAmount1;
			if (0 < loanPayments1.length) {
				$scope.payments1 = $scope.loanAmount1-loanPayments1.reduce(function(prev, cur) {
					return prev + cur;
				});
			}
			$scope.loanPaymentSum1 = $scope.payments1/$scope.loanAmount1*100;

			$scope.transFilter2 = trackers[1]['Transaction Filter'];
			$scope.loanAmount2 = trackers[1]['Tracker Ammount'];
			var loanPayments2 = [];
			var loanTransactions2 = $filter('filter')(newTrans, $scope.transFilter2);
			for (i = 0; i < loanTransactions2.length; i++) {
				loanPayments2.push(parseFloat(loanTransactions2[i].payment));
			}
			$scope.payments2 = $scope.loanAmount2;
			if (0 < loanPayments2.length) {
				$scope.payments2 = $scope.loanAmount2-loanPayments2.reduce(function(prev, cur) {
					return prev + cur;
				});
			} else {
				$scope.payments2 = $scope.loanAmount2;
			}
			$scope.loanPaymentSum2 = $scope.payments2/$scope.loanAmount2*100;

			$scope.transFilter3 = trackers[2]['Transaction Filter'];
			$scope.loanAmount3 = trackers[2]['Tracker Ammount'];
			var loanPayments3 = [];
			var loanTransactions3 = $filter('filter')(newTrans, $scope.transFilter3);
			for (i = 0; i < loanTransactions3.length; i++) {
				loanPayments3.push(parseFloat(loanTransactions3[i].payment));
			}
			$scope.payments3 = $scope.loanAmount3;
			if (0 < loanPayments3.length) {
				$scope.payments3 = $scope.loanAmount3-loanPayments3.reduce(function(prev, cur) {
					return prev + cur;
				});
			} else {
				$scope.payments3 = $scope.loanAmount3;
			}
			$scope.loanPaymentSum3 = $scope.payments3/$scope.loanAmount3*100;

			$scope.transFilterTotals = 'Student Loan Totals';
			$scope.loanAmountTotals = Math.round((parseFloat($scope.loanAmount1)+parseFloat($scope.loanAmount3)+parseFloat($scope.loanAmount2))*100)/100;
			var loanPaymentsTotals = [];
			var loanTransactionsTotals = $filter('filter')(newTrans, $scope.transFilterTotals);
			for (i = 0; i < loanTransactionsTotals.length; i++) {
				loanPaymentsTotals.push(parseFloat(loanTransactionsTotals[i].payment));
			}
			$scope.paymentsTotals = $scope.loanAmountTotals;
			if (0 < loanPaymentsTotals.length) {
				$scope.paymentsTotals = Math.round(($scope.loanAmountTotals-loanPaymentsTotals.reduce(function(prev, cur) {
					return prev + cur;
				}))*100)/100;
			} else {
				$scope.paymentsTotals = $scope.loanAmountTotals;
			}
			$scope.loanPaymentSumTotals = $scope.paymentsTotals/$scope.loanAmountTotals*100;
        }

        function loadFilters(trans) {
			$http.get('/wp-content/themes/DPR5/checkbook/trackers.json')
				.then(function( trackers ) {
					applyRemoteData(trackers.data['Trackers'], trans);
				});
        }

        function loadTransData() {
            getTransDataService.getData()
                .then(function( trans ) {
                    loadFilters(trans);
                    //applyRemoteData(trans);
                });
        }
	});

	app.directive('styleRepeater', function() {
		return function(scope, element, attr) {
			if ( undefined !== scope.transaction ) {
				if ( '1' === scope.transaction.highlight ) {
					angular.element(element).addClass('highlighted');
				}
			}
		};
	});

	app.service('getTransDataService', function($http, $q) {
		return({
			addData: addData,
			getData: getData,
			removeData: removeData,
			editData: editData,
			updateData: updateData
		});
		// Add data with the given name to the remote collection.
        function addData( check_number, date, desc, transtags, payment, deposit ) {
            var request = $http({
                method: 'post',
                url: '../wp-content/themes/DPR5/checkbook/insertTrans.php',
                params: {
					check_number: check_number,
                    date: date,
                    desc: desc,
                    transtags: transtags,
                    payment: payment,
                    deposit: deposit,
                },
                responseType: 'json'
            });
            return( request.then( handleSuccess, handleError ) );
        }
		// Get all of the data in the remote collection.
		function getData() {
			var request = $http({
				method: 'get',
				url: '../wp-content/themes/DPR5/checkbook/getTrans.php',
				params: {
					action: 'get'
				}
			});
			return( request.then( handleSuccess, handleError ) );
		}
		// Remove the data with the given id from the remote collection.
        function removeData( id ) {
            var request = $http({
                method: 'delete',
                url: '../wp-content/themes/DPR5/checkbook/removeTrans.php',
                params: {
                    action: 'delete',
                    id: id
                },
                data: {
                    id: id
                }
            });
            return( request.then( handleSuccess, handleError ) );
        }
        // Edit the data with the given id from the remote collection.
        function editData( id, check_number, date, desc, transtags, payment, deposit ) {
            var request = $http({
                method: 'update',
                url: '../wp-content/themes/DPR5/checkbook/editTrans.php',
                params: {
					action: 'set',
					id: id,
					check_number: check_number,
                    date: date,
                    desc: desc,
                    transtags: transtags,
                    payment: payment,
                    deposit: deposit
                },
                data: {
					id: id,
					check_number: check_number,
                    date: date,
                    desc: desc,
                    transtags: transtags,
                    payment: payment,
                    deposit: deposit
                }
            });
            return( request.then( handleSuccess, handleError ) );
        }
		// Update the data with the given id from the remote collection.
        function updateData( id, highlight ) {
            var request = $http({
                method: 'update',
                url: '../wp-content/themes/DPR5/checkbook/updateTrans.php',
                params: {
                    action: 'set',
                    id: id,
                    highlight: highlight
                },
                data: {
                    id: id,
                    highlight: highlight
                }
            });
            return( request.then( handleSuccess, handleError ) );
        }

		// Transform the error response, unwrapping the application dta from
        // the API response payload.
		function handleError( response ) {
            if ( ! angular.isObject( response.data ) || ! response.data.message ) {
                return( $q.reject( "An unknown error occurred." ) );
            }
            return( $q.reject( response.data.message ) );
        }
		// Transform the successful response, unwrapping the application data
        // from the API response payload.
        function handleSuccess( response ) {
            return( response.data );
        }
	});

	// display tags as pills
	app.filter('tagSplit', function() {
		return function(input) {
			var filtered = '';
			if ( null === input || '' === input || undefined === input ) {
				filtered += '';
			} else {
				var tags = input.split(', ');
				for (var i = 0; i < tags.length; i++) {
					var tag = tags[i];
					filtered += '<span>'+tag+'</span>';
				}
			}
			return filtered;
        };
    });

	angular.module('checkbook').config(function($datepickerProvider) {
		angular.extend($datepickerProvider.defaults, {
			animation: 'am-fade-and-slide-right',
			placement: 'top-left',
			autoclose: 'true',
			startWeek: 7,
			dateFormat: 'yyyy-MM-dd',
			dateType: 'string',
			iconLeft: '',
			iconRight: '',
		});
	});

	angular.module('checkbook').config(function($tooltipProvider) {
		angular.extend($tooltipProvider.defaults, {
			animation: 'am-fade-and-scale',
			trigger: 'hover'
		});
	});

})();