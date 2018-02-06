<?php
// check for an override
if(file_exists(APP . 'Model' . DS . pathinfo(__FILE__, PATHINFO_BASENAME))) {
	require_once(APP . 'Model' . DS . pathinfo(__FILE__, PATHINFO_BASENAME));
	return;
}


/**
 * 
 */

class CcConfigAcosAro extends CakeclientAppModel {
	
	// there's an complex key on the combination (aro_key_name, aro_key_value)
	
	/* Virtually - yes. 
	public $belongsTo = array(
		'UserRole' => array(
			'className' => 'UserRole'
		)
	);
	*/
	
	public $belongsTo = array(
		'CcConfigMenu' => array(
			'className' => 'CcConfigMenu'
		)
		// AroModel
	);
	
	
	
	
	
	/**
	 * 
	 * @param array $aro		- the ARO object (the user array)
	 * @param unknown $keyNames
	 * @return the full menu tree of allowed ACOs for that ARO
	 */
	public function getAcoTree($aro = array(), $keyNames = null, $use = null, $action = array()) {
		return $this->getMergedAcosAro($aro, $keyNames, $use, $action);
	}
	
	
	public function getAcoList($aro = array(), $keyNames = null, $use = null, $action = array()) {
		$tree = $this->getMergedAcosAro($aro, $keyNames, $use, $action);
		$result = array();
		foreach($tree as $menu) {
			foreach($menu['CcConfigMenu']['CcConfigTable'] as $table) {
				foreach($table['CcConfigAction'] as $action) {
					$result[] = $action;
				}
			}
		}
		return $result;
	}
	
	
	public function getCurrentAcos(CakeRequest $request, $aro = array(), $keys = null) {
		$list = $this->getAcoList($aro, $keys);
		// there might be more than one matching ACO (eg full controller access, but many more menu entries
		$result = array();
		if(!empty($list)) foreach($list as $aco) {
			if(preg_match('<'.$aco['url_pattern'].'>', '/'.$request->url)) 
				$result[] = $aco;
		}
		
		return $result;
	}
	
	
	public function authorizeRequest(CakeRequest $request, $aro = array(), $keys = null) {
		$acos = $this->getCurrentAcos($request, $aro, $keys);
		return !empty($acos);
	}
	
	
	public function getMergedAcosAro($aro, $keys = null, $use = null, $action = array()) {
		if($keys == null) $keys = Configure::read('Cakeclient.aroKeyName');
		$result = array();
		
		if(is_string($keys)) {
			$result = $this->__getAcosAro($aro, $keys, $use, $action);
		}
		elseif(is_array($keys)) {
			$result = array();
			foreach($keys as $name) {
				$tmp = $this->__getAcosAro($aro, $name, $use, $action);
				$result = array_merge($result, $tmp);
			}
		}
		
		return $result;
	}
	
	private function __getAcosAro($aro, $keyName, $use = null, $action = array()) {
		$table_conditions = $conditions = array();
		
		switch($use) {
		case 'menu': 	$conditions = array('CcConfigAction.contextual' => false); break;
		case 'actions':
			if($action) {
				$conditions = array('CcConfigAction.name !=' => $action['viewName']);
				$table_conditions = array('CcConfigTable.name' => $action['tableName']);
			}
			break;
		}
		
		$results = $this->find('all', array(
			'conditions' => array(
				'CcConfigAcosAro.aro_key_name' => $keyName,
				'CcConfigAcosAro.aro_key_value' => (isset($aro[$keyName]) ? $aro[$keyName] : null)
			),
			'contain' => array(
				'CcConfigMenu' => array(
					'CcConfigTable' => array(
						'conditions' => $table_conditions,
						'CcConfigAction' => array(
								'conditions' => $conditions,
								'order' => 'CcConfigAction.position ASC'
						),
						'order' => 'CcConfigTable.position ASC'
					)	
				)
			)
		));
		if(!empty($results)) foreach($results as &$result) {
			unset($result['CcConfigAcosAro']);
			// adjust the action label based on context
			foreach($result['CcConfigMenu']['CcConfigTable'] as &$table) {
				foreach($table['CcConfigAction'] as &$action) {
					$action['label'] = $this->makeActionLabel(
							$action['name'],
							$table['label'], $use,
							$action['label'],
							$action['contextual']);
				}
			}
				
				
			return $results;
		}
		return $results;
	}
	
	
	
	
	
	
	
	
}
?>