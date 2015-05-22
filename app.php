<?php
require_once __DIR__.'/vendor/autoload.php'; 

$app = new Silex\Application(); 

const APIKEY = 'some-secret-key';

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/db/app.sqlite',
    ),
));

$app->get('/set/{latitude}/{longitude}/{apiKey}', function($latitude, $longitude, $apiKey) use($app) {
	
	if ($apiKey == APIKEY) {
		$app['db']->insert('parking', array(
		    'latitude'   => $app->escape($latitude),
		    'longitude'   => $app->escape($longitude),
		    'datetime' => new \DateTime(),
			), array (
	    		PDO::PARAM_STR,
	    		PDO::PARAM_STR,
	    		'datetime',
			));
		 
	    return 'angular.callbacks._0(1);'; 
	}
}); 

$app->get('/get/{apiKey}', function($apiKey) use($app) {

	if ($apiKey == APIKEY) {
		$sql = "SELECT * FROM parking ORDER BY id DESC LIMIT 0,1";
	    $parking = $app['db']->fetchAssoc($sql, array());
	
	    return 'angular.callbacks._0('.json_encode($parking).');';
	}
}); 

$app->get('/clear/{apiKey}', function($apiKey) use($app) {

	if ($apiKey == APIKEY) {
	    $app['db']->executeQuery('DELETE FROM parking');
		
		return 'angular.callbacks._0(1);'; 
	}
}); 

$app->run(); 