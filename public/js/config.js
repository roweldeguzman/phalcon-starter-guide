app.config(['$stateProvider', '$urlRouterProvider', '$httpProvider',
	function ($stateProvider, $urlRouterProvider, $httpProvider) {
		$urlRouterProvider.otherwise('/');
		$stateProvider
		.state('home',{
			url: '/',
			templateUrl: '/index/home',
		})
        .state('singlePage',{
            url: '/single-page',
            templateUrl: '/index/singlePage',
        })
        .state('home.about', {
            url: 'about',
            templateUrl: '/index/about',
        })
        .state('home.contact', {
            url: 'contact',
            templateUrl: '/index/contact',
        })
        .state('home.career', {
            url: 'career',
            templateUrl: '/index/career',
        })
	}
]);