/**
 * Created by Tibi on 2015.11.26..
 */

app.directive("summaryGraph",[function(){
	function makePlot($container, data) {
		var plot = $.plot($container + ' .canvas', data, {
			xaxis: {
				mode: "time",
				thickLength: 5
			}, legend: {
				show: true,

			}, lines: {
				show: true
			},
			points: {
				show: true
			}, grid: {
				hoverable: true,
				clickable: true
			}
		});
		$("._tooltip").remove();
		$("<span class='_tooltip'></span>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #fdd",
			padding: "2px",
			"background-color": "#fee",
			opacity: 0.80,
			left: 0,
			top: 0,
			"z-index": 100
		}).appendTo($('body'));
		$($container + " .canvas").bind('plothover', function (event, pos, item) {
			if (item) {
				var x = item.datapoint[0],
					y = item.datapoint[1].toFixed(2);
				$("._tooltip").html((parseFloat(y)===0?'Egész nap': (y + " óra") ))
					.css({'top': item.pageY - 20, 'left': item.pageX + 5})
					.show(200);
			} else {
				$("._tooltip").hide();
			}
		});
	}

	return {
		template:'<div class="canvas" style="height: 100px; width: 100%;"></div>',
		restrict: 'AE',
		scope: {ngModel: '='},
		link:function($scope, $element, $attrs, ngModel){
			$scope.$watch(function () {
				return $scope.ngModel;
			}, function (newValue, oldValue) {
				//$element.children('.canvas').css('margin-top', (newValue.length*50)+"px" )
				$element.children('.canvas').html('');
				makePlot(".summary_page",newValue);
				//console.log('change', newValue, oldValue);
			}, true);
			makePlot('.summary_page', []);
		}
	}

}]);

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
app.directive('timeHandler',function(){
	return{
		restrict: 'A',
		link: function ($scope, $elem, $attrs) {
			var type = $attrs.timeHandler=='hour'?24:59;
			$elem.bind('keyup',function(e){
				if(parseInt($elem.val())>type){
					$elem.val(type);
				}else{
					if(parseInt($elem.val())!==$elem.val()){
						$elem.val(parseInt($elem.val()));
					}
				}
				console.log($elem);
				//if(!$scope.$last) {
				//	$elem[0].parentNode.nextElementSibling.children[1].focus();
				//}
			});
		}
	}
});