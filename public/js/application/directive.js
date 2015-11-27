/**
 * Created by Tibi on 2015.11.27..
 */
app.directive('evalAsync', ['$compile',
	function () {
		return {
			restrict: 'E',
			controller: function($scope,$element) {
				$element.css({'display':'none'});
				$scope.$evalAsync( function() {
					$element.css({'display':'block'});
				});
			}
		};
	}
]);