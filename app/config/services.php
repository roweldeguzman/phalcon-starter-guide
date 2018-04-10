<?php
/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */
use Phalcon\DI\FactoryDefault,
    Phalcon\Mvc\View,
    Phalcon\Mvc\Url as UrlResolver,
    Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter, 
    Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter,
    Phalcon\Session\Adapter\Files as SessionAdapter,
    Phalcon\Mvc\Router as Router;
use Phalcon\Dispatcher,
    Phalcon\Mvc\Dispatcher as MvcDispatcher,
    Phalcon\Events\Manager as EventsManager,
    Phalcon\Mvc\Dispatcher\Exception as DispatchException;
use \Phalcon\Mvc\Dispatcher as PhDispatcher;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});


$di->set('config', function() use ($config) {
	return $config;
}, true);

/**
 * Setting up the view component
 */
$di->setShared('view', function () use ($config) {

    $view = new View();
	$view->setViewsDir($config->application->viewsDir);
	$view->setLayoutsDir('layouts/');
	$view->setLayout('index');
	$view->registerEngines(array(
	'.phtml' => 'Phalcon\Mvc\View\Engine\Php'
	));
	return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($config) {
    $dbConfig = $config->database->toArray();
    $adapter = $dbConfig['adapter'];
    unset($dbConfig['adapter']);

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

    return new $class($dbConfig);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash(array(
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ));
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter([
        'lifetime'  =>  84600,
        'uniqueId'  =>  'ASSESTSMANAGEMENT_VL_051116'
    ]);
    $session->start();

    return $session;
});

/*
* Base URL of Root Domain for the application
*/
$di->set('baseurl', function(){
	$isSecure = false;
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
		$isSecure = true;
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
		$isSecure = true;
	}
	$REQUEST_PROTOCOL = $isSecure ? 'https' : 'http';
	return $REQUEST_PROTOCOL.'://'.$_SERVER['HTTP_HOST'];
}, true);

$di->set('dispatcher', function($di) {
 
    $eventsManager = new EventsManager(); 
    $eventsManager->attach("dispatch:beforeException", function($event, $dispatcher, $exception) { 
        if ($exception instanceof DispatchException) { 
            $dispatcher->forward(array(
                'controller' => 'error',
                'action' => 'index'
            ));
            return false;
        } 
        $dispatcher->forward(array(
            'controller' => 'error',
            'action' => 'index'
        )); 
        
        
        
        return false;
    }); 
    
   
         
    $dispatcher = new MvcDispatcher(); 
    $dispatcher->setEventsManager($eventsManager); 
    return $dispatcher;

}, true);
$di->set('cache',function() use ($config) {
    $frontCache = new \Phalcon\Cache\Frontend\Data(array('lifetime' => 172800));
    $cache = new Phalcon\Cache\Backend\File($frontCache, array(
        "cacheDir" => $config->application->cacheDir
    ));    
    return $cache;
});
$di->set('dispatcher',function() use ($di) {
    $evManager = $di->getShared('eventsManager');
    $evManager->attach("dispatch:beforeException",
        function($event, $dispatcher, $exception){
            switch ($exception->getCode()) {
                case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
                    $dispatcher->forward(array(
                        'controller' => 'error',
                        'action'     => 'index',
                    ));
                    return false;
                }
            }
        );
        $dispatcher = new PhDispatcher();
        $dispatcher->setEventsManager($evManager);
        return $dispatcher;
    },
    true
);

