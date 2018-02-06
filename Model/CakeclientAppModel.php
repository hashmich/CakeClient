<?php
class CakeclientAppModel extends AppModel {
	
	var $recursive = 0;
	
	// CrudComponent sets this property to the name of the CRUD method in effect
	// to indicate that generic Crud code handles the request
	var $crud = false;
	
	var $actsAs = array(
		'Cakeclient.Configurable',
		'Containable'
	);
	
	public function afterSave($created, $options = array()) {
		// move this to affected models later on.
		Cache::clear($check_expiry = false, 'cakeclient');
	}
	
	
	public function makeTableLabel($tablename = null, $table_prefix = null) {
		$label = $tablename;
		if($table_prefix) $label = str_replace($table_prefix, '', $label);
		return $label = Inflector::camelize($label);
	}
	
	
	public function makeActionLabel($method, $tableLabel, $viewName = null, $label = null, $contextual = false) {
		if(empty($label)) {
			$label = Inflector::humanize($method);
			if($method == 'index') $label = 'List';
		}
		if(!empty($viewName) AND $viewName != 'menu') {
			if($method == 'index') $label = 'List '.$tableLabel;
			if($viewName != 'index' AND in_array($method, array('add','edit','view','delete')))
				$label = Inflector::humanize($method).' '.Inflector::singularize($tableLabel);
				if(!empty($viewName) AND $viewName == 'index' AND !$contextual)
					$label = Inflector::humanize($method).' '.Inflector::singularize($tableLabel);
		}
		
		return $label;
	}
	
	
	/*
	* If we are examining a plugin class, get the according App-class - if any
	*/
	public function getAppClass(&$className = null, $classType = null, &$virtual = false, &$plugin = false, &$pluginAppOverride = null, $method = null) {
		if(empty($className) OR empty($classType)) return null;
		
		App::uses($className, $classType);
		if(!class_exists($className, $autoLoad = true)) {
			$virtual = true;
			return $className;
		}
		$reflector = new ReflectionClass($className);
		$dir = dirname($reflector->getFileName());
		unset($reflector);
		if(strpos($dir, 'Plugin')) {
			$plugin = true;
			$expl = explode(DS, $dir);
			foreach($expl as $k => $d) if($d == 'Plugin') $plugin = $expl[$k+1];
			// test for an app-level override
			$_className = 'App'.$className;
			App::uses($_className, $classType);
			if(class_exists($_className, true)) {
				$pluginAppOverride = true;
				if(!empty($method) AND !method_exists($className, $method)) {
					$pluginAppOverride = false;
					$plugin = false;
				}
				$className = $_className;
			}
		}
		
		return $className;
	}
	
	
	public function getDisplayfield($model) {
		if(is_string($model)) $model = ClassRegistry::init($model);
		$displayField = $model->displayField;
		// if no displayField is provided, id will be the default
		if($displayField == 'id') {
			$schema = $model->schema();
			if(isset($schema['label'])) $displayField = 'label';
			elseif(isset($schema['name'])) $displayField = 'name';
			elseif(isset($schema['title'])) $displayField = 'title';
		}
		
		return $displayField;
	}
	
	
	public function getControllerMethods($tableName = null, &$plugin = false, &$pluginAppOverride = null, $defaultMethods = array(), &$controllerName = null) {
		$plugin = false;
		$pluginAppOverride = null;
		$controllerMethods = array();
		$controllerName = Inflector::camelize($tableName).'Controller';
		
		// plugins need to extend the App::paths() array in order to be detected
		// App::build(array('Controller' => App::path('Controller', 'Plugin')));
		
		// if a plugin controller, get the app-level override, if any
		$controllerName = $this->getAppClass($controllerName, 'Controller', $virtual, $plugin, $pluginAppOverride);
		
		if($controllerName AND !$virtual) {
			$reflector = new ReflectionClass($controllerName);
			$dir = dirname($reflector->getFileName());
			unset($reflector);
			if(strpos($dir, 'Plugin')) {
				$plugin = true;
				$expl = explode(DS, $dir);
				foreach($expl as $k => $d) if($d == 'Plugin') $plugin = $expl[$k+1];
				// test for an app-level override
				$_controllerName = Inflector::camelize('app_'.$tableName).'Controller';
				App::uses($_controllerName, 'Controller');
				if(class_exists($_controllerName, true)) {
					$pluginAppOverride = true;
					$controllerName = $_controllerName;
				}
			}
			
			// TODO: tidy up here
			$excludes = array('reset_order',);
			if($appExcludes = Configure::read('AclMenu.excludes'))
				$excludes = array_unique(array_merge($excludes, $appExcludes));
			Configure::write('AclMenu.excludes', $excludes);
					
				if($plugin) {
					if($pluginAppOverride) {
						$pluginController = get_parent_class($controllerName);
						$pluginAppController = get_parent_class($pluginController);
					}else{
						$pluginAppController = get_parent_class($controllerName);
					}
					$appController = get_parent_class($pluginAppController);
				}else{
					$appController = get_parent_class($controllerName);
				}
				$coreController = get_parent_class($appController);
					
				// we don't want the methods defined in Cake's core controller
				$coreControllerMethods = get_class_methods($coreController);
				$controllerMethods = get_class_methods($controllerName);
				foreach($controllerMethods as $i => $method) {
					if(	strpos($method, '_') === 0
							||	in_array($method, $excludes)
							||	in_array($method, $defaultMethods)		// cleaning against the default list
							||	(!empty($coreControllerMethods) AND in_array($method, $coreControllerMethods))
							) {
								unset($controllerMethods[$i]);
							}else{
								$reflector = new ReflectionMethod($controllerName, $method);
								if(!$reflector->isPublic()) unset($controllerMethods[$i]);
								unset($reflector);
							}
				}
		}
		
		
		return array_unique(array_merge($defaultMethods, $controllerMethods));
	}
	
}
?>