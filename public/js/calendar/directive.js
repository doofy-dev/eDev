/**
 * Created by Tibi on 2015.11.26..
 */
app.directive('timeTrackerValue', ['$compile',function ($compile) {
	return {
		restrict: 'E',
		scope:{
			ngModel : '='
		},
		controller: function ($scope, $element) {
			$element.css({
				display: 'block',
				position: 'relative',
				'z-index': '10',
				border: '1px solid rgba(0,0,0,.12)',
				backgroundColor: 'white'
			});
			//var
			//function getInput($label, $icon, $model){
			//	return '<md-input-container class="md-icon-float md-block">'+
			//	'<label>'+$label+'</label>'+
			//	'<ng-md-icon size="30" style="fill: rgb(63,81,181);" icon="'+$icon+"></ng-md-icon>'+
			//	'<input ng-model="'+$model+'" type="text">'+
			//	'</md-input-container>';
			//}
		}
	}
}]);
app.directive('timeTracker', ['$compile', function ($compile) {
	return {
		restrict: 'E',
		require: 'ngModel',
		scope: {ngModel: '=',add:'&',save:'&'},
		link: function ($scope, $element,$attrs,ngModel) {
			$element.css({
				'padding-bottom':'70px'
			});
			var button = $compile(
					'<md-button ng-click="add()" style="margin-bottom: -10px" class="md-accent md-fab md-fab-bottom-right fab-add">+</md-button>')($scope);
			$element.append(button);
			$scope.$watch(function(){
				return ngModel.$modelValue;
			},function(newValue, oldValue){
				console.log('change', newValue, oldValue);
			},true);
			$scope.add = function () {
				var el = $compile("<time-tracker-value layout='row' layout-padding flex ng-model='ngModel'/>")($scope);
				$element.append(el);
			};
			$scope.save = function(){

			}

		}
	}
}]);
