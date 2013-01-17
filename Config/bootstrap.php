<?php

//setup clog admin prefix url
Configure::write('Cloggy.url_prefix', 'cloggy');

//setup clog admin themes
Configure::write('Cloggy.theme_used', 'default');

//setup registered modules
Configure::write('Cloggy.modules', array(
    'CloggyBlog', 'CloggyUsers', 'CloggyDocs','ModuleTest'
));

//setup clog module path
define('CLOGGY_PATH_MODULE', APP . 'Plugin' . DS . 'Cloggy' . DS . 'Module' . DS);