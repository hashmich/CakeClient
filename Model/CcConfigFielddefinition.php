<?php
// check for an override
if(file_exists(APP . 'Model' . DS . pathinfo(__FILE__, PATHINFO_BASENAME))) {
	require_once(APP . 'Model' . DS . pathinfo(__FILE__, PATHINFO_BASENAME));
	return;
}

class CcConfigFielddefinition extends CakeclientAppModel {
	
	var $actsAs = array(
		'Utils.Sortable' => array(
			'parentId' => 'cc_config_table_id.cc_config_action_id.context'
		)
	);
	
	var $belongsTo = array(
		'CcConfigAction' => array(
			'className' => 'CcConfigAction',
			'foreignKey' => 'cc_config_action_id',
		),
		'CcConfigTable' => array(
			'className' => 'CcConfigTable',
			'foreignKey' => 'cc_config_table_id'
		)
	);
	
}
?>