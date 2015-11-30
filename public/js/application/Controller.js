/**
 * Created by Tibi on 2015.11.25..
 */
app.controller('Application',function($mdSidenav,$scope){
	$scope.toggleSidenav = function(menuId) {
		$mdSidenav(menuId).toggle();
	};
	$scope.isLoading = false;
	$scope.menu = [
		{
			title:'Áttekintés',
			url:'/dashboard',
			icon:'dashboard'
		},
		{
			title: 'Jelenlét',
			url:'/calendar',
			icon: 'access_time'
		},
		{
			inset:true,
			title:'Megtekintés',
			url:'/calendar',
			icon: 'visibility'

		},
		{
			inset:true,
			title:'Export',
			url:'/calendar/export',
			icon: 'get_app'
		},
		{
			title:'Projektek',
			url:'/projects',
			icon:'bookmark_outline'
		},
		{
			title:'Kijelentkezés',
			url:'/logout',
			icon:'close'
		}
	];

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