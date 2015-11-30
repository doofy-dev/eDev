/**
 * Created by Tibi on 2015.11.26..
 */

app.directive('timeTracker', ['$compile', function ($compile) {
	return {
		restrict: 'E',
		require: 'ngModel',
		scope: {ngModel: '=', add: '&', save: '&'},
		link: function ($scope, $element, $attrs, ngModel) {
			$element.css({
				'padding-bottom': '70px'
			});
			var button = $compile(
				'<md-button ng-click="add()" style="margin-bottom: -10px" class="md-warn md-fab md-fab-bottom-right fab-add">+</md-button>')($scope);
			$element.append(button);
			$scope.$watch(function () {
				return ngModel.$modelValue;
			}, function (newValue, oldValue) {
				//console.log('change', newValue, oldValue);
			}, true);

			$scope.add = function(){
				ngModel.$modelValue.push({});
			};

			$scope.save = function () {

			}

		}
	}
}]);
