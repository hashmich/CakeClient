<?php
// check for an override
if(file_exists(APP . 'Model' . DS . pathinfo(__FILE__, PATHINFO_BASENAME))) {
	require_once(APP . 'Model' . DS . pathinfo(__FILE__, PATHINFO_BASENAME));
	return;
}

class CcConfigTable extends CakeclientAppModel {
	
	
	
	
	var $actsAs = array(
		'Utils.Sortable' => array(
			'parentId' => 'cc_config_menu_id'
		)
	);
	
	
	var $belongsTo = array(
		'CcConfigMenu' => array(
			'className' => 'CcConfigMenu',
			'foreignKey' => 'cc_config_menu_id',
		)
	);
	
	var $hasMany = array(
		'CcConfigAction' => array(
			'className' => 'CcConfigAction',
			'foreignKey' => 'cc_config_table_id'
		),
		'CcConfigFielddefinition' => array(
			'className' => 'CcConfigFielddefinition',
			'foreignKey' => 'cc_config_table_id'
		),
		'CcConfigDisplayedrelation' => array(
			'className' => 'CcConfigDisplayedrelation',
			'foreignKey' => 'cc_config_table_id'
		)
	);
	
	
	protected function getTablesFromSource($source = null) {
		if(empty($source)) $source = 'default';
		App::uses('ConnectionManager', 'Model');
		$db = ConnectionManager::getDataSource($source);
		return $db->listSources();
	}
	
	/**
	 * 
	 * @param String $tableName		required
	 * @param String $table_label	optional, defaults to table name
	 * @param Integer $position		optional, provide null if no position required
	 * @return Array				an array resembling a database entry
	 */
	protected function getDefaultTable($tableName, $table_label = null, $position = null) {
		$table_label = ($table_label == null) ? $tableName : $table_label;
		
		$controllerName = Inflector::classify($tableName);
		$modelName = $this->getAppClass($controllerName, 'Model');
		$displayField = $this->getDisplayfield($modelName);
		return array(
			//'id' => '1',
			//'cc_config_menu_id' => 1,
			'position' => $position,
			'name' => $tableName,
			'label' => $table_label,
			'model' => $modelName,
			'controller' => $tableName,
			'displayfield' => $displayField,
			'displayfield_label' => $table_label,
			'show_associations' => true
		);
	}
	
	
	protected function getDefaultGroupTables($source = null, $menuGroup = array(), $tablePrefixes = array()) {
		$table_prefix = (!empty($menuGroup['table_prefix'])) ? $menuGroup['table_prefix'] : null;
		$source = (!empty($menuGroup['dataSource'])) ? $menuGroup['dataSource'] : $source;
		$_tables = $this->getTablesFromSource($source);
		$tables = array();
		if(!empty($_tables)) foreach($_tables as $i => $tableName) {
			$hit = false;
			if(empty($table_prefix)) {
				// get only those tables that don't match any prefix
				foreach($tablePrefixes as $pr) {
					if(strpos($tableName, $pr) === 0) {
						$hit = true;
						break;
					}
				}
				if($hit) continue;
			}else{
				if(strpos($tableName, $table_prefix) === false) continue;
			}
			$table_label = $this->makeTableLabel($tableName, $table_prefix);
			$tables[$i] = $this->getDefaultTable($tableName, $table_label, $i+1);
			if(!empty($menuGroup['id'])) $tables[$i]['cc_config_menu_id'] = $menuGroup['id'];
		}
		return $tables;
	}
	
	
	public function getDefaultMenuTableTree($routePrefix = null, $group = array(), $tablePrefixes = array(), $source = null, $view = null) {
		$tables = $this->getDefaultGroupTables($source, $group, $tablePrefixes);
		$tablePrefix = (!empty($group['table_prefix'])) ? $group['table_prefix'] : null;
		
		if(!empty($tables)) foreach($tables as $i => &$table) {
			$actions = $this->CcConfigAction->getDefaultActions($table['name'], $tablePrefix, $view, $routePrefix);
			$table['CcConfigAction'] = $actions;
		}
		return $tables;
	}
	
	
	
	
	
	
	
	/**
	* Takes either a table ID or a table name as argument, 
	* returns the table ID, sets the table name by reference.
	*/
	/**
	 * 
	 * @param mixed $table			either a table ID or a table name, reference will 
	 * @return mixed table_id		either table_id or Boolean false if no record found
	 */
	function getTable(&$table) {
		$table_id = false;
		if(ctype_digit($table) AND $table > 0) {
			$table_id = $table;
			$stored = $this->find('first', array(
				'conditions' => array(
					'id' => $table_id
				),
				'recursive' => -1
			));
			$table = $stored['CcConfigTable']['name'];
			
		}elseif(!empty($table) AND is_string($table)) {
			$stored = $this->find('first', array(
				'conditions' => array(
					'name' => $table
				),
				'recursive' => -1
			));
			if($stored) $table_id = $stored['CcConfigTable']['id'];
		}
		return $table_id;
	}
	
	/*
	function update($config_id = null) {
		$this->tidy($config_id);
		$this->store($config_id);
	}
	*/
	/**
	* Create table definitions in configuration from all db-tables 
	* that cannot be found in configuration.
	*/
	/*
	function store($menu_id = null) {
		if(empty($config_id)) {
			// use the current config id
			$config_id = Configure::read('Cakeclient.config_id');
		}
		
		if(!isset($this->tables) OR empty($this->tables)) {
			$this->__getDbTables();
		}
		$tables = $this->tables;
		
		$storedTables = $this->find('all', array(
			'conditions' => array(
				'cc_config_configuration_id' => $config_id
			),
			'recursive' => -1
		));
		$update = false;
		foreach($tables as $i => $tablename) {
			$stored = false;
			foreach($storedTables as $k => $storedTable) {
				if($storedTable['CcConfigTable']['name'] == $tablename) {
					$stored = true;
					break;
				}
			}
			if(!$stored) {
				$this->create();
				$this->save(array(
					'position' => $i + 1,
					'cc_config_configuration_id' => $config_id,
					'name' => $tablename,
					'label' => Inflector::humanize($tablename),
					'modelclass' => Inflector::singularize(Inflector::camelize($tablename)),
					'displayfield' => null, // stick to the cake default "id", "name" or "title"
					'displayfield_label' => Inflector::humanize(Inflector::singularize($tablename))
				), false);
				$this->CcConfigConfiguration->save(array(
					'id' => $config_id,
					'modified' => date("Y-m-d H:i:s")
				), false);
				$update = true;
			}
		}
	}
	
	
	*/
	/**
	* Remove all table definitions from configuration
	* that cannot be found in the db anymore.
	*/
	/*
	function tidy($config_id = null) {
		if(empty($config_id)) {
			// use the current config id
			$config_id = Configure::read('Cakeclient.config_id');
		}
		if(!isset($this->tables) OR empty($this->tables)) {
			$this->__getDbTables();
		}
		$tables = $this->tables;
		
		$storedTables = $this->find('all', array(
			'conditions' => array(
				'cc_config_configuration_id' => $config_id
			),
			'recursive' => -1
		));
		
		$removable = array();
		foreach($storedTables as $k => $storedTable) {
			$existant = false;
			foreach($tables as $i => $tablename) {
				if($storedTable['CcConfigTable']['name'] == $tablename) {
					$existant = true;
					break;
				}
			}
			if(!$existant) {
				$removable[] = $storedTable['CcConfigTable']['id'];
			}
		}
		if(!empty($removable)) {
			$this->deleteAll(array('CcConfigTable.id' => $removable), $cascade = true);
			// saving action will refresh the configuration cache
			$this->CcConfigConfiguration->save(array(
				'id' => $config_id,
				'modified' => date("Y-m-d H:i:s")
			), false);
		}
	}
	*/
}
?>