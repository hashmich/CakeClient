<?php
	// set some defaults
	// layout: [bool]false (don't override app-layout), any other
	Configure::write('Cakeclient.layout', 'Cakeclient.cakeclient');	// Cakeclient default layout (naming conflict with 'default')
	// Disallow app-level view overrides by default. 
	// This is, to have this plugin fully functionally as a db-client on an alternative route-prefix.
	// To do overrides nevertheless, create a special route and controller action 
	// that sends requests to the application logic instead.
	Configure::write('Cakeclient.allowViewOverride',false);
	// show or hide the navbar
	Configure::write('Cakeclient.always_show_navbar', true);
	// may contain either string or array('element' => 'path/to/element','text' => 'footer string')
	Configure::write('Cakeclient.footer', '&copy; 2016 <a href="http://hendrikschmeer.de" target="_blank">Hendrik Schmeer</a>');
	
	Configure::write('Cakeclient.AclChecking', true);
	Configure::write('Cakeclient.AuthComponent', 'Auth');
	
	Configure::write('Cakeclient.aroKeyName', array('user_role_id'));
	
	
	
	// make the plugin controllers & models available to the application
	App::build(array('Controller' => App::path('Controller', 'Cakeclient')));
	App::uses('CakeclientAppController', 'Controller');
	App::build(array('Model' => App::path('Model', 'Cakeclient')));
	App::uses('CakeclientAppModel', 'Model');
	
	
	Cache::config('cakeclient', array(
		'engine' => 'File',
		'duration' => '+999 days',
		'prefix' => 'cakeclient_'
	));
	
	// read in app-specific configuration and overrides
	$filename = APP . 'Config' . DS . 'Cakeclient' . DS . 'bootstrap.php';
	if(file_exists($filename)) {
		include($filename);
	}
?>