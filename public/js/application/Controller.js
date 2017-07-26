/**
 * Created by Tibi on 2015.11.25..
 */
app.controller('Application',function($mdSidenav,$scope,$timeout){
	$scope.toggleSidenav = function(menuId) {
		$mdSidenav(menuId).toggle();
	};
	$scope.isLoading = false;
	$scope.menu = [
		{
			title:'Folyam',
			url:'/dashboard',
			icon:'dashboard'
		},
		{
			title: 'Értesítések',
			url:'/notifications',
			icon: 'access_alarm'
		},
		{
			title: 'Jelenlét',
			url:'/calendar',
			icon: 'event'
		},
		{
			inset:true,
			title:'Előnézet',
			url:'/calendar/preview',
			icon: 'visibility'

		},
		{
			inset:true,
			title:'Exportálás',
			url:'/calendar/export',
			icon: 'get_app'
		},
		{
			inset:true,
			title:'Beállítások',
			url:'/calendar/settings',
			icon: 'settings'
		},
		{
			title:'Projektek',
			url:'/projects',
			icon:'bookmark_outline'
		},
		{
			inset:true,
			title:'Lista',
			url:'/projects/list',
			icon: 'list'
		},
		{
			inset:true,
			title:'Feladatok',
			url:'/projects/tasks',
			icon: 'assignment_turned_in'
		},
		{
			inset:true,
			title:'Hibakövetés',
			url:'/projects/bug',
			icon: 'bug_report'
		},
		{
			inset:true,
			title:'Csatolmányok',
			url:'/projects/dashboard',
			icon: 'attachment'
		},
		{
			title:'Fiók',
			url:'/account',
			icon:'account_circle'
		},
		{
			title:'Kijelentkezés',
			url:'/logout',
			icon:'close'
		}
	];
    var date = new Date();
    $timeout(function () {
        $scope.year = date.getFullYear();
        var m =  date.getMonth()+1;
        $scope.month = (m<10?'0':'0')+m;
    });
	$scope.toRoute = function(entity){
		location.href = entity.url;
		//history.pushState({route:entity.url},entity.title,entity.url);
		//var url = history.state.route.replace(/\/$/,'')+'.txt';
		////console.log(url);
		//$.get(url).done(function(response){
		//	//console.log(response)
		//	 console.log(url);
		//	$scope.site_content = response;
		//});

		//console.log(history.state);
	}
});