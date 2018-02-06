<?php

class CcConfigMenusController extends CakeclientAppController {
	
	public $components = array(
		'Cakeclient.AclMenu'
	);
	
	public $uses = array('CcConfigMenu');
	
	
	
	/**
	 * This method will save freshly created default menu trees to the database.
	 * These trees are not connected to any ARO in first place and will therefore not show up
	 * as menus in the application unless you connect the menu groups to any ARO. 
	 * Edit the menu tree structures as required for that ARO type beforehand. 
	 */
	public function create_default_trees() {
		// get the available prefixes to populate the form options
		$route_prefix = Configure::read('Cakeclient.prefixes');
		if(is_string($route_prefix)) {
			$route_prefix = array($route_prefix);
		}
		if(is_array($route_prefix) AND count($route_prefix) === 1) 
			$route_prefix = $route_prefix[0]; 
		
		if(!empty($this->request->data['CcConfigMenu'])) {
			if(is_array(Configure::read('Cakeclient.prefixes'))) {
				$index = $this->request->data['CcConfigMenu']['route_prefix'];
				$route_prefix = Configure::read('Cakeclient.prefixes')[$index];
			}else{
				$route_prefix = Configure::read('Cakeclient.prefixes');
			}
			
			// get the full action tree -> viewName = null
			$this->CcConfigMenu->createDefaultTrees($route_prefix, $this->AclMenu->defaultMenus, $viewName = null);
			$this->Flash->set("Menu trees have been created based on default configuration, 
					existing controller functions  and database structure");
			
			$this->redirect(array(
					'action' => 'index', 
					'controller' => 'cc_config_menus',
					'plugin' => $this->request->params['cakeclient.route']));
		}
		$this->set('route_prefixes', Configure::read('Cakeclient.prefixes'));
	}
	
	
	public function add_aco($id = null) {
		if(!empty($this->request->data['CcConfigAcosAro'])) {
			$this->CcConfigAcosAro->set($this->request->data);
			$this->CcConfigAcosAro->save();
		}
		$data = array(
				'cc_config_menu_id' => $id
		);
		$this->request->data = $data;
	}
	
	
}
?>