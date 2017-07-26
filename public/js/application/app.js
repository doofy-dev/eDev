/**
 * Created by Tibi on 2015.11.25..
 */
var app = angular.module('eDev',
	[
		'ngMaterial',
		'ngMdIcons',
		'ngSanitize',
		'materialCalendar',
])
	;
app.config(function($httpProvider){
	//$httpProvider.defaults.useXDomain = true;
	//delete $httpProvider.defaults.headers.common['X-Requested-With'];
});