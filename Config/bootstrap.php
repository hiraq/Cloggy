<?php

//setup clog admin prefix url
Configure::write('Clog.url_prefix','clog');

//setup clog admin themes
Configure::write('Clog.theme_used','default');

//setup registered modules
Configure::write('Clog.modules',array(
	'ClogBlog','Users','ModuleTest'
));

//setup clog module path
define('CLOG_PATH_MODULE',APP.'Plugin'.DS.'Clog'.DS.'Module'.DS);