/**
 * Created by Tibi on 2015.11.25..
 */

app.controller("calendarCtrl", function($scope, $filter, $http, $q) {
	$scope.current_day = "Nincs kiválasztva";
	$scope.dateContent = [
		//{
		//	name:'VMI',
		//	start:{
		//		hour:8,
		//		min:30
		//	},
		//	end:{
		//		hour:16,
		//		min:30
		//	},
		//	type: 1,
		//	project: 1,
		//	task:1,
		//	comment: 'ASD'
		//}
	];
	$scope.database = [];
	var date = Date.now();
	$scope.$parent.isLoading = true;
	$http({
		method: 'POST',
		url: './calendarrest/getdata',
		data:{
			date:$filter("date")(date, "y-MM-")+'00'
		}
	}).then(function(response){
		$scope.$parent.isLoading = false;
		$scope.database = response.data;
		console.log(response.data);
	});
	$scope.dayTypes = [];
	$http({
		method: 'GET',
		url: './calendarrest/gettypes'
	}).then(function(response){
		$scope.$parent.isLoading = false;
		$scope.dayTypes = response.data;
		console.log(response.data);
	});
	$scope.projects = [

		{
			id:1,
			label:'SmartCity'
		},
		{
			id:2,
			label:'ICF'
		}
	];
	$scope.tasks = [

		{
			id:1,
			label:'Webservice'
		},
		{
			id:2,
			label:'Sitebuild'
		}
	];


	$scope.removeInstance =function(index){
		$scope.dateContent.splice(index,1);

	};

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
		//$scope.$parent.isLoading = true;

		$scope.dateContent = [];
		if($scope.selectedDate==null){
			$scope.current_day = "Nincs kiválasztva";
			return false;
		}
		for(var i=0;i<$scope.database.length;i++){
			if(parseInt($scope.database[i].calendarDay) == parseInt($scope.selectedDate.getDate())){
				$scope.dateContent.push($scope.database[i])
			}
		}
		$scope.current_day = $filter("date")(date, "y-MM-d");
	};

	$scope.prevMonth = function(data) {
		reloadData(data);
	};

	$scope.nextMonth = function(data) {
		reloadData(data);
	};
	function reloadData(date){
		console.log(date);
		$scope.$parent.isLoading = true;
		$http({
			method: 'POST',
			url: './calendarrest/getdata',
			data:{
				date:date.year+'-'+date.month+'-00'
			}
		}).then(function(response){
			$scope.$parent.isLoading = false;
			$scope.database = response.data;
			console.log(response.data);
		});
	}
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