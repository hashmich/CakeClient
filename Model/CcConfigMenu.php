<?php
// check for an override
if(file_exists(APP . 'Model' . DS . pathinfo(__FILE__, PATHINFO_BASENAME))) {
	require_once(APP . 'Model' . DS . pathinfo(__FILE__, PATHINFO_BASENAME));
	return;
}

class CcConfigMenu extends CakeclientAppModel {
	
	public $displayField = 'label';
	
	public $hasMany = array(
		'CcConfigTable' => array(
			'className' => 'CcConfigTable'
		),
		'CcConfigAcosAro' => array(
			'className' => 'CcConfigAcosAro'
		)
	);
	
	/* virtually:
	public $hasAndBelongsToMany = array(
		AroModel (User or UserRole via CcConfigAcosAro)
	);
	*/
	
	
	
	/**
	 * 
	 * @param string $routePrefix
	 * @param bool   $isAdmin		- make sure the default menus are only available to admins!
	 * @param array  $menuGroups
	 * @return array
	 */
	public function getDefaultMenuTree($routePrefix = null, $isAdmin = false, $menuGroups = array(), $view = null) {
		$menu = array();
		if(!empty($menuGroups)) {
			$tablePrefixes = Hash::extract($menuGroups, '{n}.table_prefix');
			foreach($menuGroups as $k => $group) {
				if(!$isAdmin AND !empty($group['require_super_user']))
					continue;
				$menu[$k]['CcConfigMenu'] = $this->getDefaultMenu($group, $k + 1);
				$source = (!empty($group['data_source'])) ? $group['data_source'] : 'default';
				$tableTree = $this->CcConfigTable->getDefaultMenuTableTree($routePrefix, $group, $tablePrefixes, $source, $view);
				$menu[$k]['CcConfigMenu']['CcConfigTable'] = $tableTree;
			}
		}
		return $menu;
	}
	
	
	protected  function getDefaultMenu($group = array(), $k = null) {
		
		$source = (!empty($group['data_source'])) ? $group['data_source'] : 'default';
		$prefix = (!empty($group['table_prefix'])) ? $group['table_prefix'] : null;
		$name = (!empty($group['name'])) ? $group['name'] : 'Menu '.$k;
		
		return array(
			//'id',
			'label' => $name,
			'position' => $k,
			'layout_block' => 'cakeclient_navbar',
			'comment' => 'auto-created menu tree'
		);
	}
	
	
	public function createDefaultTrees($routePrefix = null, $menuGroups = array()) {
		$menus = $this->getDefaultMenuTree($routePrefix, true, $menuGroups, null);
		$this->saveAll($menus, array('deep' => true));
	}
	
	
	
}
?>