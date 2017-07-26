/**
 * Created by Tibi on 2015.11.25..
 */

app.controller("calendarCtrl", ['$scope', '$filter', '$http', '$q', 'MaterialCalendarData','$timeout', function ($scope, $filter, $http, $q, CalendarData,$timeout) {
    $scope.current_day = "Nincs kiválasztva";
    $scope.dateContent = [];
    $scope.database = [];
    //$scope.selectedmonth = parseInt(date.format('M'))
    $scope.dateMap = [];
    $scope.sum = [];
    $scope.summary = [];
    $scope.dataGet = false;
    var date = Date.now();
    $timeout(function () {
        $scope.year = date.getYear();
        var m =  date.getMonth()+1;
        $scope.month = (m<10?'0':'0')+m;
    });
    $scope.dayTypes = [];
    $http({
        method: 'GET',
        url: './calendarrest/gettypes'
    }).then(function (response) {
        $scope.$parent.isLoading = false;
        $scope.dayTypes = response.data;
    });
  

    $scope.projects = [];
    $http({
        method: 'POST',
        url: './calendarrest/getprojects',
        data: {'user_id': '0'}
    }).then(function (response) {
        $scope.$parent.isLoading = false;
        $scope.projects = response.data;
    }, function (err) {
        console.log('error', err)
    });

    //$scope.tasks = [];


    $scope.removeInstance = function (index) {
        $scope.dateContent.splice(index, 1);

    };

    $scope.dayFormat = "d";
    $scope.dayValues = {};
    // To select a single date, make sure the ngModel is not an array.
    $scope.selectedDate = new Date();

    //// If you want multi-date select, initialize it as an array.
    //$scope.selectedDate = Date.now();

    $scope.firstDayOfWeek = 1; // First day of the week, 0 for Sunday, 1 for Monday, etc.
    $scope.setDirection = function (direction) {
        $scope.direction = direction;
        $scope.dayFormat = direction === "vertical" ? "EEEE, MMMM d" : "d";
    };

    $scope.dayClick = function (date) {
        //$scope.$parent.isLoading = true;

        $scope.dateContent = [];
        if ($scope.selectedDate == null) {
            $scope.current_day = "Nincs kiválasztva";
            return false;
        }
        for (var i = 0; i < $scope.database.length; i++) {
            if (parseInt($scope.database[i].calendarDay) == parseInt($scope.selectedDate.getDate())) {
                var currentIndex = $scope.dateContent.length;
                $scope.dateContent.push($scope.database[i]);
                if ($scope.database[i].project != null) {
                    $scope.updateTasks($scope.dateContent[currentIndex], {projectId: $scope.database[i].project})
                }
            }
        }
        console.log($scope.dateContent);
        $scope.current_day = $filter("date")(date, "y-MM-d");
    };
    $scope.saveDay = function () {
        $scope.$parent.isLoading = true;
        $http({
            method: 'POST',
            url: './calendarrest/savetasks',
            data: {'userId': '1', 'values': $scope.dateContent, 'date': $filter("date")($scope.selectedDate, "y-MM-dd")}
        }).then(function (response) {
            console.log(response.data);
            if (!response.data.status)
                $scope.$parent.isLoading = false;
            else
                loadData($scope.selectedDate);
        });
    };
    $scope.updateTasks = function (parent, project) {
        //$scope.tasks = [];
        $scope.$parent.isLoading = true;
        $http({
            method: 'POST',
            url: './calendarrest/gettasks',
            data: {'userId': '1', 'projectId': project.projectId}
        }).then(function (response) {
            $scope.$parent.isLoading = false;
            console.log(response.data);
            parent.tasks = response.data;
        });
    };

    $scope.prevMonth = function (data) {
        $scope.dates = [];
        reloadData(data);
    };

    $scope.nextMonth = function (data) {
        $scope.dates = [];
        reloadData(data);
    };
    function reloadData(date) {
        $scope.$parent.isLoading = true;
        $scope.dataGet = false;
        loadData(new Date(date.year + '-' + date.month + "-01"))
    }

    loadData($scope.selectedDate);

    function loadData(date) {
        $scope.database = [];
        $scope.sum = [];
        clearTable();
        $scope.$parent.isLoading = false;
        $http({
            method: 'POST',
            url: './calendarrest/getdata',
            data: {
                date: $filter("date")(date, "y-MM-") + '01'
            }
        }).then(function (response) {
            console.log(response);
            $scope.$parent.isLoading = false;
            $scope.sumTime = 0;
            $scope.database = response.data;
            var map = {};
            var typeMap = {};
            for (var i = 0; i < response.data.length; i++) {
                if (typeMap[response.data[i].entryName] == null)
                    typeMap[response.data[i].entryName] = 0;
                typeMap[response.data[i].entryName] +=
                    (((response.data[i].end.hour == 0 ? 24 : response.data[i].end.hour) * 60 + response.data[i].end.min) -
                    (response.data[i].start.hour * 60 + response.data[i].start.min)) / 60;
                var day = $filter("date")(date, "y-MM-") + response.data[i].calendarDay;
                if (typeof map[day] == 'undefined') map[day] = $('<p/>').addClass('day-container');
                map[day].append(
                    $('<span/>')
                        .addClass('day-indicator')
                        .addClass('day-color-' + response.data[i].entryTypeId)
                );
            }
            for (var key in map) {
                CalendarData.setDayContent(new Date(key), map[key].prop('outerHTML'));
            }
            for (var key in typeMap) {
                $scope.sum.push({
                    type: key,
                    number: typeMap[key].toFixed(2)
                });
            }
            console.log(typeMap);
            loadGraph($filter("date")(date, "y-MM-") + '01');
        });
    }

    function loadGraph(date) {
        $scope.$parent.isLoading = true;
        $http({
            method: 'POST',
            url: './calendarrest/getsummary',
            data: {'date': date}
        }).then(function (response) {
            console.log('graph', date, response.data);
            $scope.$parent.isLoading = false;
            $scope.summary = response.data;
        });
    }

    function clearTable() {
        for (var i = 0; i < $scope.dateMap.length; i++)
            CalendarData.setDayContent($scope.dateMap[i], '<p class="day-container"></p>');
    }

    $scope.setDayContent = function (date) {
        $scope.dateMap.push(date);
        return '<p class="day-container"></p>';
    };

}]);