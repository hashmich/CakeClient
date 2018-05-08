<?php
class AclMenuComponent extends Component {
	
	public $menuModelName = 'CcConfigMenu';
	
	public $tableModelName = 'CcConfigTable';
	
	public $actionModelName = 'CcConfigAction';
	

	public $aclLookupModelName = 'CcConfigAcosAro';	// this is not yet the actual ARO table, but the connection table
	
	public $aroKeyName = null;
	
	public $aroKeyValue = null;
	
	public $aro = array();
	
	
	// the menu currently loaded for the user - needs to be stored, as we will have to do ACL checking against this structure - or second db query?
	protected $currentMenu = array();
	
	// the model where the permissions are bound against 
	//public $aro_model = 'UserRole';		// 'User'
	
	// acf - the access controling field
	//public $acf = 'user_role_id';		// 'id'
	
	// the field value that enables admin function (allow all for admin group UserRole.id = 1)
	//public $acf_adminValue = 1;			// 42 (id of the admin account)
	
	// the currently requesting object ID (null = anonymous) - aka a role, or user.
	//public $aro_id = null;
	
	
	/**
	 * The menus we want to generate if no other menu is available for the current ARO.
	 * If require_super_user is not set or false, the menu will be public to all, 
	 * who can access the application level showing this menu!!!
	 */
	public $defaultMenus = array(
		array(
			'name' => 'Config',
			'table_prefix' => 'cc_config_',		// table name prefix
			'data_source' => 'default',
			'require_super_user' => true,			// to be checked against DefaultAuth.isAdmin
			'layout_block' => 'cakeclient_navbar'
			// #ToDo: implement this (default false):
			//'classPrefix' => false
			// what about class prefixes for different sources?
			// what about prefixing during ACO generation?
		),
		array(
			'name' => 'Tables',
			'dataSource' => 'default',
			'require_super_user' => true,
			'layout_block' => 'cakeclient_navbar'
			// no table name prefix - gather all tables without prefix
		)
		// extend with further prefixed (plugin) table groups);
	);
	
	
	public $defaultRoute = 'db-webclient';
	
	public $settings = array();
	
	public $controller = null;
	
	public $request = null;
	
	
	
	
	
	
	public function getModel($modelName = null) {
		if(!isset($this->controller->{$modelName}))
			$this->controller->loadModel($modelName);
		return $this->controller->{$modelName};
	}
	
	
	
	
	// require Auth
	// optional DefaultAuth (admin check)
	
	
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
		$this->settings = $settings;
		
		$this->aroKeyName = Configure::read('Cakeclient.aroKeyName');
	}
	
	
	public function isAdmin() {
		if(	(method_exists($this->controller, 'isAdmin')
			AND $this->controller->isAdmin())
		OR	(isset($this->controller->DefaultAuth)
			AND $this->controller->DefaultAuth->isAdmin())) return true;
		return false;
	}
	
	
	/*
	* don't do anything about authorisation at this stage, 
	* the component might be used to generate a menu alone
	*/
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		
		foreach($this->settings as $key => $value)
			$this->{$key} = $value;
		
		$this->request = $controller->request;
		
		
		if(isset($this->controller->Auth))
			$this->aro = $this->controller->Auth->user();
	}
	
	
	/**
	 * The passed ARO will be used to lookup matching menu trees. 
	 * Permissions granted to an individual object will always extend the inherited 
	 * permissions  bound to any parent object (eg UserRole).
	 * 
	 * Thus, the algorithm will first check for matches of the aroKeyName pattern(s), 
	 * as specified in the Configure settings: 
	 * Cakeclient.aroKeyName
	 * 
	 * @param array $arObject, optional
	 * @return boolean
	 */
	public function authorize() {
		
		$aroAcoModel = $this->getModel($this->aclLookupModelName);
		$result = $aroAcoModel->authorizeRequest($this->request, $this->aro, $this->aroKeyName);
		
		if($this->isAdmin())
			return true;
		
		return $result;
	}
	
	
	
	/**
	 * 
	 * @param String $routePrefix	the current (or only prefix) to create default menus
	 */
	public function setMenu() {
		$cakeclientMenu = $this->getMenu();
		// only run menu creation on non-AJAX requests
		if(!$this->request->is('requested')) {
			// load the AssetHelper which appends the top_nav Menu to whichever layout
			if(	!in_array('Cakeclient.Asset', $this->controller->helpers)
			AND	!isset($this->controller->helpers['Cakeclient.Asset']))
				$this->controller->helpers[] = 'Cakeclient.Asset';
		}
		
		$this->controller->set(compact('cakeclientMenu'));
	}
	
	
	
	public function getMenu() {
		$user = $this->controller->Auth->user();
		$aroAcoModel = $this->getModel($this->aclLookupModelName);
		$menu = $aroAcoModel->getAcoTree($this->aro, $this->aroKeyName, 'menu');
		
		// only if demanded or admin: get defaults if no menu available
		if(empty($menu)) {
			// build the default menu either using the current route or the default route from settings
			$routePrefix = (Configure::read('Cakeclient.current_route'))
				? Configure::read('Cakeclient.current_route') : $this->defaultRoute;
			
			$menuModel = $this->getModel($this->menuModelName);
			$menu = $menuModel->getDefaultMenuTree($routePrefix, $this->isAdmin(), $this->defaultMenus, 'menu');
		}
		
		return $menu;
	}
	
	public function getDefaultActions($args = array()) {
		if(empty($args)) $args = $this->__getMenuActionArguments();
		foreach($args as $key => $value) $$key = $value;
		
		$actionModel = $this->getModel($this->actionModelName);
		return $actionModel->getDefaultActions($tableName, $tablePrefix, $viewName, $urlPrefix);
	}
	
	public function getActions() {
		$args = $this->__getMenuActionArguments();
		$actions = array();
		
		$aroAcoModel = $this->getModel($this->aclLookupModelName);
		$actions = $aroAcoModel->getAcoList($this->aro, $this->aroKeyName, 'actions', $args);
		
		if(empty($actions)) {
			// only if demanded or admin: get defaults if no menu available
			if(empty($actions) AND $this->isAdmin()) {
				$actions = $this->getDefaultActions($args);
			}
		}
		
		return $actions;
	}
	
	private function __getMenuActionArguments() {
		$tableName = $this->request->params['controller'];
		$viewName = $this->request->params['action'];
		$urlPrefix = (Configure::read('Cakeclient.current_route')) 
			? Configure::read('Cakeclient.current_route') : $this->defaultRoute;
		// determine the table prefix
		$tablePrefixes = Hash::extract($this->defaultMenus, '{n}.prefix');
		$tablePrefix = null;
		foreach($tablePrefixes as $prefix) if(strpos($prefix, $tableName) === 0) $tablePrefix = $prefix;
		return array(
				'tableName' => $this->request->params['controller'],
				'viewName' => $this->request->params['action'],
				'urlPrefix' => $urlPrefix,
				'tablePrefix' => $tablePrefix
		);
	}
	
	
	
	function setActions() {
		$actions = $this->getActions();
		$this->controller->set('crudActions', $actions);
		return $actions;
	}
	
	
	
	
	
}
?>