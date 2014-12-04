<?php
return array(
	'_root_'  => 'toppage/index',  // The default route
	//'_root_'  => 'admin',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route
	
	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
	'Pastsinglebattle/(:num)' => '/pastsinglebattle',
	//'confirmlogin(/:name)?' => array('confirmlogin', 'name' => 'confirmlogin'),
);

