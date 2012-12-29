<?php

/*
 * custom router class
 * for modules
 */
App::uses('ModuleRoute','CustomRouter');
$base = '/'.Configure::read('Cloggy.url_prefix');

/*
 * main /cloggy
 */
Router::redirect($base.'/',$base,array('status' => '302'));
Router::connect(
	$base,
	array(
		'controller' => 'home',
		'action' => 'index',
		'plugin' => 'cloggy'
	)
);

Router::redirect($base.'/login/',$base.'/login',array('status' => '302'));
Router::connect(
	$base.'/login',
	array(
		'controller' => 'home',
		'action' => 'login',
		'plugin' => 'cloggy'
	)
);

Router::redirect($base.'/logout/',$base.'/logout',array('status' => '302'));
Router::connect(
	$base.'/logout',
	array(
		'controller' => 'home',
		'action' => 'logout',
		'plugin' => 'cloggy'
	)
);

/*
 * module routers
 */
Router::redirect($base.'/module/:name/',$base.'/module/:name',array('status' => '302'));
Router::connect(
	$base.'/module/:name',
	array(
		/*pass*/
	),
	array('routeClass' => 'ModuleRoute')
);

Router::redirect(
	$base.'/module/:name/:controller/:action/*/',
	$base.'/module/:name/:controller/:action/*',
	array('status' => '302'
));
Router::connect(
	$base.'/module/:name/:controller/:action/*',
	array(
		/*pass*/			
	),
	array('routeClass' => 'ModuleRoute')
);

Router::redirect(
	$base.'/module/:name/:controller/:action/',
	$base.'/module/:name/:controller/:action',
	array('status' => '302'
));
Router::connect(
	$base.'/module/:name/:controller/:action',
	array(
		/*pass*/
	),
	array('routeClass' => 'ModuleRoute')
);


Router::connect(
	$base.'/module/:name/:controller',
	array(
		/*pass*/
	),
	array('routeClass' => 'ModuleRoute')
);
Router::redirect(
	$base.'/module/:name/:controller/',
	$base.'/module/:name/:controller',
	array('status' => '302'
));