/* global angular:false */
(function() {
	var app = angular.module('checkbook', ['mgcrea.ngStrap', 'ngAnimate', 'angularUtils.directives.dirPagination', 'ngSanitize', 'ngTagsInput']);

		//--------------- Long term goals --------------//
		// - add button to create a tracker from current search filters
		// - monthly/yearly spending graphs
		//----------------------------------------------//

	// ---------------------------------- RegisterController ---------------------------------- //

	app.controller('RegisterController', function($scope, getTransDataService, $modal, $filter, $http) {
		$scope.transactions = [];

		loadTransData();

		// broadcast that event happened
		$scope.showCtrl1 = function () {
			$scope.$root.$broadcast('transEvent', {});
		};

		// Process the transactionForm form.
		function listTags(tags) {
			var tagList = '';
        	for (i = 0; i < tags.length; i++) {
        		if ( i > 0 ) {
        			tagList += ', ';
        		}
        		tagList += tags[i].text;
        	}
        	return tagList
        }
        $scope.addData = function(entries) {
        	var transTags = $scope.newTrans.transtags;
        	var tagList = listTags(transTags);
            getTransDataService.addData( $scope.newTrans.check_number, $scope.newTrans.date, $scope.newTrans.desc, tagList, $scope.newTrans.payment, $scope.newTrans.deposit)
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
        $scope.editData = function( trans, tags, thisModal ) {
			thisModal.$hide();
        	var tagList = listTags(tags);
			getTransDataService.editData( trans.id, trans.check_number, trans.date, trans.description, tagList, trans.payment, trans.deposit)
                .then( loadTransData, function( errorMessage ) {
                    console.warn( errorMessage );
                }
            );
		};

		// Apply current year filter to transactions list
		var year_var = new Date().getFullYear();

		// Apply the remote data to the local scope.
        function applyRemoteData( newTrans ) {
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
                }
            );
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

		// open edit transaction modal
		$scope.showModal = function( trans ) {
			$scope.transaction = trans;
			var savedTags = trans.tags.split(", ");
			$scope.transTags = [];
			if( trans.tags.length ) {
				for (i = 0; i < savedTags.length; i++) {
					$scope.transTags.push(savedTags[i]);
				}
			} else {
				$scope.transTags = '';
			}
			var myModal = $modal({
				animation: 'am-fade-and-slide-bottom',
				title: 'Edit '+trans.description+'',
				scope: $scope,
				template: '../wp-content/themes/DPR5/checkbook/editModal.tpl.html',
				show: false
			});
			myModal.$promise.then(myModal.show);
		};

		// when typing in the search box, add up filtered payments and deposits
		$scope.change = function() {
			var transactions = $scope.transactions;
			var theFilter = $scope.q;
			var transFiltered = $filter('filter')(transactions, theFilter);
			$scope.paymentSum = 0;
			$scope.depositSum = 0;
			for (i = 0; i < transFiltered.length; i++) {
				$scope.paymentSum += parseFloat(transFiltered[i].payment);
				$scope.depositSum += parseFloat(transFiltered[i].deposit);
			}
			$scope.paymentSum = Math.round($scope.paymentSum*100)/100;
			$scope.depositSum = Math.round($scope.depositSum*100)/100;
		};

		// load tags for typeahead
		$scope.tagList = [];
		$scope.allTags = [];
		function loadTagData() {
			$scope.tagList = [];
			getTransDataService.getTags()
                .then(function( tags ) {
					for (i = 0; i < tags.length; i++) {
						$scope.tagList.push(tags[i].tag);
						$scope.allTags.push(tags[i].tag);
					}
					// $scope.tagList = tags;
					return $scope.tagList;
                }
            );
        };
		loadTagData();
		$scope.loadItems = function($query) {
			//return $scope.tagList;
			var tags = $scope.tagList;
			return tags.filter(function(tag) {
				return tag.toLowerCase().indexOf($query.toLowerCase()) !== -1;
			});
		};

		// loop through tags to see if any are new
		$scope.compareTags = function(currTags) {
			var transTags = '';
			if( undefined !== currTags ) {
				transTags = currTags;
			} else {
				transTags = $scope.newTrans.transtags;
			}
			var tagList = $scope.allTags;
			for (i = 0; i < transTags.length; i++) {
				var newTag = transTags[i].text;
				var check = 0;
				for (t = 0; t < tagList.length; t++) {
					var currentTag = tagList[t];
					if( newTag === currentTag ) {
						check++;
					}
				}
				if( 0 === check) {
					getTransDataService.addTag(newTag).then(function() {
						loadTagData();
					});
				}
			}
		};
	});

	// --------------------------------- / RegisterController --------------------------------- //
	// ---------------------------------------------------------------------------------------- //

	// ----------------------------------- TrackerController ---------------------------------- //

	app.controller('trackerController', function($scope, getTransDataService, $filter, $http) {
		$scope.transTrackers = [];

		$scope.$on("transEvent", function (event) {
			loadTransData();
		});

        function applyRemoteData( trackers, newTrans ) {
        	var i = 0;
			for (i = 0; i < trackers.length; i++) {
				var payments = [];
				var transactions = $filter('filter')(newTrans, trackers[i].Filter);

	            for (count = 0; count < transactions.length; count++) {
					payments.push(parseFloat(transactions[count].payment));
				}
				var filterPayments = trackers[i].Amount;
				if (0 < payments.length) {
					filterPayments = trackers[i].Amount-payments.reduce(function(prev, cur) {
						return prev + cur;
					});
				}
				var loanPaymentSum = filterPayments/trackers[i].Amount*100;

				trackers[i].Payments = Math.round(filterPayments*100)/100;
				trackers[i].Sum = loanPaymentSum;

			}
			$scope.transTrackers = trackers;
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
                });
        }
	});

	// ---------------------------------- / TrackerController --------------------------------- //
	// ---------------------------------------------------------------------------------------- //

	// --------------------------------------- Directives ------------------------------------- //

	app.directive('styleRepeater', function() {
		return function(scope, element, attr) {
			if ( undefined !== scope.transaction ) {
				if ( '1' === scope.transaction.highlight ) {
					angular.element(element).addClass('highlighted');
				}
			}
		};
	});

	app.directive('search', function() {
		return {
			restrict: 'E',
			templateUrl: '../wp-content/themes/DPR5/checkbook/search.html'
		};
	});

	app.directive('addform', function() {
		return {
			restrict: 'E',
			templateUrl: '../wp-content/themes/DPR5/checkbook/addform.html'
		};
	});

	app.directive('transrepeater', function() {
		return {
			restrict: 'E',
			templateUrl: '../wp-content/themes/DPR5/checkbook/transactions.html'
		};
	});

	app.directive('transaction', function() {
		return {
			restrict: 'E',
			templateUrl: '../wp-content/themes/DPR5/checkbook/transaction.html'
		};
	});

	app.directive('totals', function() {
		return {
			restrict: 'E',
			templateUrl: '../wp-content/themes/DPR5/checkbook/totals.html'
		};
	});

	app.directive('tracker', function() {
		return {
			restrict: 'E',
			templateUrl: '../wp-content/themes/DPR5/checkbook/tracker.html'
		};
	});

	// ------------------------------------- / Directives ------------------------------------- //
	// ---------------------------------------------------------------------------------------- //

	// ---------------------------------- getTransDataService --------------------------------- //

	app.service('getTransDataService', function($http, $q) {
		return({
			addData: addData,
			getData: getData,
			removeData: removeData,
			editData: editData,
			updateData: updateData,
			getTags: getTags,
			addTag: addTag
		});
		// Add data with the given name to the remote collection.
        function addData( check_number, date, desc, transtags, payment, deposit ) {
            var request = $http({
                method: 'post',
                url: '../wp-content/themes/DPR5/checkbook/php/insertTrans.php',
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
				url: '../wp-content/themes/DPR5/checkbook/php/getTrans.php',
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
                url: '../wp-content/themes/DPR5/checkbook/php/removeTrans.php',
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
                url: '../wp-content/themes/DPR5/checkbook/php/editTrans.php',
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
                url: '../wp-content/themes/DPR5/checkbook/php/updateTrans.php',
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
        // Get all of the tags from the database
		function getTags() {
			var request = $http({
				method: 'get',
				url: '../wp-content/themes/DPR5/checkbook/php/getTags.php',
				params: {
					action: 'get'
				}
			});
			return( request.then( handleSuccess, handleError ) );
		}
		// Add a new tag to the list
		function addTag( tag ) {
            var request = $http({
                method: 'post',
                url: '../wp-content/themes/DPR5/checkbook/php/insertTag.php',
                params: {
					tag: tag
                },
                responseType: 'json'
            });
            return( request.then( handleSuccess, handleError ) );
        }

		// Transform the error response, unwrapping the application dta from
        // the API response payload.
		function handleError( response ) {
            if ( ! angular.isObject( response.data ) || ! response.data.message ) {
                return( $q.reject( 'An unknown error occurred.' ) );
            }
            return( $q.reject( response.data.message ) );
        }
		// Transform the successful response, unwrapping the application data
        // from the API response payload.
        function handleSuccess( response ) {
            return( response.data );
        }
	});

	// --------------------------------- / getTransDataService -------------------------------- //
	// ---------------------------------------------------------------------------------------- //

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


	// display tags as pills
	app.filter('tagSplit', function() {
		return function(input) {
			var filtered = '';
			if ( undefined === input || null === input ) {
				filtered += '';
			} else if ( 0 === input.length ) {
				filtered += '';
			} else {
				var transTag = '';
				if ( 'object' === typeof(input) ) {
					for (var i = 0; i < input.length; i++) {
						transTag = input[i].text;
						filtered += '<span>'+transTag+'</span>';
					}
				} else {
					var transTags = input.split(', ');
					for (var c = 0; c < transTags.length; c++) {
						transTag = transTags[c];
						filtered += '<span>'+transTag+'</span>';
					}
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