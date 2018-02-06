<?php
// check for an override
if(file_exists(APP . 'Model' . DS . pathinfo(__FILE__, PATHINFO_BASENAME))) {
	require_once(APP . 'Model' . DS . pathinfo(__FILE__, PATHINFO_BASENAME));
	return;
}

class UserRole extends CakeclientAppModel {
	
	/**
	*  The reason for the User class is located in the application 
	*  and not in this plugin, is the manyfold involvement of the user model in applicational logic. 
	*/
	
	var $hasMany = array(
		'User' => array(
			'className' => 'User'
		),
		/* - virtually - yes.
		'CcConfigMenu' => array(
			'className' => 'CcConfigMenu'
		)
		*/
	);
	
	
	
}
?>