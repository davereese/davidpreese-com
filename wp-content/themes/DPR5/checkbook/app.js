(function() {
	var app = angular.module('checkbook', ['mgcrea.ngStrap', 'ngAnimate', 'angularUtils.directives.dirPagination']);

	app.controller('RegisterController', function($scope, getTransDataService, $modal) {
		$scope.transactions = [];

		loadTransData();

		// Process the transactionForm form.
        $scope.addData = function(entries) {
			// TODO - if new transaction adds a new page, go to the new page
			var prevBalance = null;
			var balance = '';
			if ( undefined === entries[entries.length - 1] ) {
				prevBalance = '0';
			} else {
				prevBalance = entries[entries.length - 1].balance;
			}
			if ( undefined !== $scope.newTrans.payment && null !== $scope.newTrans.payment ) {
				balance = parseFloat(prevBalance) - parseFloat($scope.newTrans.payment);
			} else if ( undefined !== $scope.newTrans.deposit && null !== $scope.newTrans.deposit ) {
				balance = parseFloat(prevBalance) + parseFloat($scope.newTrans.deposit);
			}
            getTransDataService.addData( $scope.newTrans.check_number, $scope.newTrans.date, $scope.newTrans.desc, $scope.newTrans.payment, $scope.newTrans.deposit, balance )
                .then( loadTransData, function( errorMessage ) {
                    console.warn( errorMessage );
                }
            );
            // Reset the form once values have been consumed.
            $scope.newTrans.check_number = '';
            $scope.newTrans.date = '';
            $scope.newTrans.desc = null;
            $scope.newTrans.payment = null;
            $scope.newTrans.deposit = null;
            $scope.newTrans.balance = null;
        };

        // Remove the given transaction from the current collection.
        $scope.removeData = function( trans, entries, thisModal ) {
			thisModal.$hide();

			getTransDataService.removeData( trans.id );
			// Remove selected frow from model
			var transIndex = entries.indexOf(trans);
			entries.splice(transIndex, 1);

			var i = 0;
			for (i = 0; i < entries.length; i++) {
				if ( undefined === entries[i - 1] ) {
					entries[i].balance = '0';
				} else {
					entries[i].balance = entries[i - 1].balance;
				}
				if ( '0.00' !== entries[i].payment ) {
					entries[i].balance = entries[i].balance - entries[i].payment;
					getTransDataService.updateData( entries[i].id, entries[i].balance, entries[i].highlight )
						.then( loadTransData, function( errorMessage ) {
							console.warn( errorMessage );
						}
					);
				}
				if ( '0.00' !== entries[i].deposit ) {
					entries[i].balance = parseFloat(entries[i].balance) + parseFloat(entries[i].deposit);
					getTransDataService.updateData( entries[i].id, entries[i].balance, entries[i].highlight )
						.then( loadTransData, function( errorMessage ) {
							console.warn( errorMessage );
						}
					);
				}
			}
		};

		// Apply the remote data to the local scope.
        function applyRemoteData( newTrans ) {
			var i = 0;
			for (i = 0; i < newTrans.length; i++) {
				if ( '0' === newTrans[i].check_number ) {
					newTrans[i].check_number = '';
				}
			}
            $scope.transactions = newTrans;

            var entriesNum = $scope.transactions.length;
            var pageSize = $scope.pageSize;
            if ( undefined === pageSize ) {
            	pageSize = 20;
            }
            var lastPage = $scope.currentPage;
            if ( undefined === lastPage ) {
            	lastPage = Math.ceil(entriesNum / pageSize);
            }
            $scope.pageSize = pageSize;
			$scope.currentPage = lastPage;
        }

        // Load the remote data from the server.
        function loadTransData() {
            // The getTransDataService returns a promise.
            getTransDataService.getData()
                .then(function( trans ) {
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
			getTransDataService.updateData( entries[transIndex].id, entries[transIndex].balance, entries[transIndex].highlight )
						.then( loadTransData, function( errorMessage ) {
							console.warn( errorMessage );
						}
					);
		};

		$scope.showModal = function( trans ) {
			$scope.transaction = trans;
			// Show a basic modal from a controller
			var myModal = $modal({
				title: ''+trans.description+'',
				content: 'Remove transaction?',
				scope: $scope,
				template: 'wp-content/themes/DPR5/checkbook/removeModal.tpl.html',
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
				title: 'Login',
				scope: $scope,
				template: 'wp-content/themes/DPR5/checkbook/loginModal.tpl.html',
				show: false
			});
			loginModal.$promise.then(loginModal.show);
	});

	app.controller('trackerController', function($scope, getTransDataService, $filter, $tooltip) {
		loadTransData();

        function applyRemoteData( newTrans ) {
			$scope.transFilter1 = 'SAF Student Loan';
			$scope.loanAmount1 = 11204.84;
			var loanPayments1 = [];
			var loanTransactions1 = $filter('filter')(newTrans, $scope.transFilter1);
            for (i = 0; i < loanTransactions1.length; i++) {
				loanPayments1.push(parseFloat(loanTransactions1[i].payment));
			}
			$scope.payments1 = $scope.loanAmount1-loanPayments1.reduce(function(prev, cur) {
				return prev + cur;
			});
			$scope.loanPaymentSum1 = $scope.payments1/$scope.loanAmount1*100;

			$scope.transFilter2 = 'GL Student Loan';
			$scope.loanAmount2 = 1889.47;
			var loanPayments2 = [];
			var loanTransactions2 = $filter('filter')(newTrans, $scope.transFilter2);
			for (i = 0; i < loanTransactions2.length; i++) {
				loanPayments2.push(parseFloat(loanTransactions2[i].payment));
			}
			$scope.payments2 = $scope.loanAmount2-loanPayments2.reduce(function(prev, cur) {
				return prev + cur;
			});
			$scope.loanPaymentSum2 = $scope.payments2/$scope.loanAmount2*100;

			$scope.transFilter3 = 'CP Student Loan';
			$scope.loanAmount3 = 4144.40;
			var loanPayments3 = [];
			var loanTransactions3 = $filter('filter')(newTrans, $scope.transFilter3);
			for (i = 0; i < loanTransactions3.length; i++) {
				loanPayments3.push(parseFloat(loanTransactions3[i].payment));
			}
			$scope.payments3 = $scope.loanAmount3-loanPayments3.reduce(function(prev, cur) {
				return prev + cur;
			});
			$scope.loanPaymentSum3 = $scope.payments3/$scope.loanAmount3*100;
        }

        function loadTransData() {
            getTransDataService.getData()
                .then(function( trans ) {
                    applyRemoteData( trans );
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

	function hilightClass(entries) {
		for (i = 0; i < entries.length; i++) {
			var selectedRow = document.getElementByid('row_'+i);
			if ( '1' === entries[i].highlight ) {
				selectedRow.className = selectedRow.className + ' highlighted';
			} else if ( '0' === entries[i].highlight ) {
				selectedRow.className = selectedRow.className = ' ng-scope';
			}
		}
	}


	app.service('getTransDataService', function($http, $q) {
		return({
			addData: addData,
			getData: getData,
			removeData: removeData,
			updateData: updateData,
		});
		// Add data with the given name to the remote collection.
        function addData( check_number, date, desc, payment, deposit, balance ) {
        	console.log(desc);
            var request = $http({
                method: 'post',
                url: 'wp-content/themes/DPR5/checkbook/insertTrans.php',
                params: {
					check_number: check_number,
                    date: date,
                    desc: desc,
                    payment: payment,
                    deposit: deposit,
                    balance: balance
                },
                responseType: 'json'
            });
            return( request.then( handleSuccess, handleError ) );
        }
		// Get all of the data in the remote collection.
		function getData() {
			var request = $http({
				method: 'get',
				url: 'wp-content/themes/DPR5/checkbook/getTrans.php',
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
                url: 'wp-content/themes/DPR5/checkbook/removeTrans.php',
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
		// Update the data with the given id from the remote collection.
        function updateData( id, balance, highlight ) {
            var request = $http({
                method: 'update',
                url: 'wp-content/themes/DPR5/checkbook/updateTrans.php',
                params: {
                    action: 'set',
                    id: id,
                    balance: balance,
                    highlight: highlight
                },
                data: {
                    id: id,
                    balance: balance,
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

	angular.module('checkbook').config(function($datepickerProvider) {
		angular.extend($datepickerProvider.defaults, {
			animation: 'am-fade',
			placement: 'top-left',
			autoclose: 'true',
			startWeek: 7,
			dateFormat: 'yyyy-MM-dd',
			dateType: 'string',
			iconLeft: '',
			iconRight: '',
		});
	})

	angular.module('checkbook').config(function($tooltipProvider) {
		angular.extend($tooltipProvider.defaults, {
			animation: 'am-fade',
			trigger: 'hover'
		});
	})

})();