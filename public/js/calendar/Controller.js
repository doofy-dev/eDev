/**
 * Created by Tibi on 2015.11.25..
 */

app.controller("calendarCtrl", function($scope, $filter, $http, $q) {

	$scope.dayFormat = "d";
	$scope.dayValues = {};
	// To select a single date, make sure the ngModel is not an array.
	$scope.selectedDate = Date.now();

	//// If you want multi-date select, initialize it as an array.
	//$scope.selectedDate = Date.now();

	$scope.firstDayOfWeek = 1; // First day of the week, 0 for Sunday, 1 for Monday, etc.
	$scope.setDirection = function(direction) {
		$scope.direction = direction;
		$scope.dayFormat = direction === "vertical" ? "EEEE, MMMM d" : "d";
	};

	$scope.dayClick = function(date) {
		$scope.$parent.isLoading = true;
		setTimeout(function(){
			$scope.$parent.isLoading = false;
		},3000);
		$scope.msg = "You clicked " + $filter("date")(date, "MMM d, y h:mm:ss a Z");
		$scope.dayValues = {'day':$scope.selectedDate};
	};

	$scope.prevMonth = function(data) {
		$scope.msg = "You clicked (prev) month " + data.month + ", " + data.year;
	};

	$scope.nextMonth = function(data) {
		$scope.msg = "You clicked (next) month " + data.month + ", " + data.year;
	};
	$scope.tooltips = true;
	$scope.setDayContent = function(date) {

		// You would inject any HTML you wanted for
		// that particular date here.
		return "<p></p>";

		//// You could also use an $http function directly.
		//return $http.get("/some/external/api");
		//
		//// You could also use a promise.
		//var deferred = $q.defer();
		//$timeout(function() {
		//	deferred.resolve("<p></p>");
		//}, 1000);
		//return deferred.promise;

	};

});