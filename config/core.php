<?php
	if($_SERVER['REMOTE_ADDR'] == '181.160.6.116'){
		Configure::write('debug', 2);
	}else{
		Configure::write('debug', 0);
	}

		Configure::write('debug', 2);




	Configure::write('log', false);
	Configure::write('App.encoding', 'UTF-8');
	//Configure::write('App.baseUrl', env('SCRIPT_NAME'));
	Configure::write('Routing.prefixes', array('admin'));
	Configure::write('Cache.disable', true);// desactivar cache
	Configure::write('Cache.check', false);// activar cache
	define('LOG_ERROR', 2);

	Configure::write('Session.save', 'php');
	//Configure::write('Session.model', 'Session');
	//Configure::write('Session.table', 'cake_sessions');
	//Configure::write('Session.database', 'default');
	Configure::write('Session.cookie', 'ANDAIN');
	Configure::write('Session.timeout', '10800');
	Configure::write('Session.start', true);
	Configure::write('Session.checkAgent', true);

	Configure::write('Security.level', 'medium');
	Configure::write('Security.salt', '74e34b08823106e419113f8192dadf974ca78f61');
	Configure::write('Security.cipherSeed', '316166336265633938643836326132');

	//Configure::write('Asset.timestamp', true);
	//Configure::write('Asset.filter.css', 'css.php');
	//Configure::write('Asset.filter.js', 'custom_javascript_output_filter.php');

	Configure::write('Acl.classname', 'DbAcl');
	Configure::write('Acl.database', 'default');
	date_default_timezone_set('America/Santo_Domingo');

	//date_default_timezone_set('UTC');
	Cache::config('default', array('engine' => 'File'));
    Cache::config('carro', array(
        'engine' => 'File',
        'path' => CACHE.'carro',
        //'duration'=> '+1 hours',
        'duration' => '+10 minutes',
        'prefix' => 'cake_'
    ));
	Configure::write('Error', array(
    'handler' => 'ErrorHandler::handleError',
    'level' => E_ALL & ~E_STRICT & ~E_DEPRECATED,
    'trace' => true
));

	error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

