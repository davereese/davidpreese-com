/* global angular:false */
(function() {
	var app = angular.module('checkbook2', ['mgcrea.ngStrap', 'ngAnimate', 'angularUtils.directives.dirPagination']);

	app.controller('RegisterController', function($scope, $modal, $http) {
		$http.get('../wp-content/themes/DPR5/checkbook2/trans_data.json')
			.then(function(results){
				$scope.transactions = results.data;
				applyRemoteData( $scope.transactions );
				console.log($scope.transactions);
			}, function(results) {
				//error
			});

		// Process the transactionForm form.
        $scope.addData = function(entries) {
			// TODO - if new transaction adds a new page, go to the new page
			var prevBalance = null;
			var bal = '';
			if ( undefined === entries[entries.length - 1] ) {
				prevBalance = '0';
			} else {
				prevBalance = entries[entries.length - 1].balance;
			}
			if ( undefined !== $scope.newTrans.payment && null !== $scope.newTrans.payment ) {
				bal = parseFloat(prevBalance) - parseFloat($scope.newTrans.payment);
			} else if ( undefined !== $scope.newTrans.deposit && null !== $scope.newTrans.deposit ) {
				bal = parseFloat(prevBalance) + parseFloat($scope.newTrans.deposit);
			}
			var newTransaction = {
			check_number: $scope.newTrans.check_number,
			date: $scope.newTrans.date,
			description: $scope.newTrans.desc,
			payment: $scope.newTrans.payment,
			deposit: $scope.newTrans.deposit,
			balance: bal
			}

			$scope.transactions.push(newTransaction);
			$http.post('../wp-content/themes/DPR5/checkbook2/trans_data.json', $scope.transactions)
				.success(function(results){
					console.log($scope.transactions);
				}).error(function(results) {
					//error
					console.log('error');
				});
            // getTransDataService.addData( $scope.newTrans.check_number, $scope.newTrans.date, $scope.newTrans.desc, $scope.newTrans.payment, $scope.newTrans.deposit, balance )
            //     .then( loadTransData, function( errorMessage ) {
            //         console.warn( errorMessage );
            //     }
            // );
            // Reset the form once values have been consumed.
            $scope.newTrans.check_number = '';
            $scope.newTrans.date = '';
            $scope.newTrans.desc = null;
            $scope.newTrans.payment = null;
            $scope.newTrans.deposit = null;
            $scope.newTrans.balance = null;

            //document.getElementById('trackerController').scope().loadTransData();
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
				template: '../wp-content/themes/DPR5/checkbook2/removeModal.tpl.html',
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
				container: 'section',
				template: '../wp-content/themes/DPR5/checkbook2/loginModal.tpl.html',
				show: false
			});
			loginModal.$promise.then(loginModal.show);
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

	angular.module('checkbook2').config(function($datepickerProvider) {
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

	angular.module('checkbook2').config(function($tooltipProvider) {
		angular.extend($tooltipProvider.defaults, {
			animation: 'am-fade',
			trigger: 'hover'
		});
	})

})();