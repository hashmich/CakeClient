<?php
class ConfigurationComponent{
	
	public function getConfig($config_name = null) {
		
		$user = $this->controller->Session->read('User');
		if($user AND !empty($user['is_admin']) AND !empty($user['debug_session'])) {
			Configure::write('debug', 2);
		}
		
		$configuration = Cache::read('App.config', 'default');
		if($configuration === false OR Configure::read('debug') > 0) {
			$this->controller->loadModel('CcConfigConfiguration');
			$configuration = $this->controller->CcConfigConfiguration->find('all', array());
			if(!empty($configuration['CcConfigConfiguration'])) {
				$configuration = $configuration['CcConfigConfiguration'];
				$configuration = Hash::combine($configuration, '{n}.key', '{n}.value');
			}
		}
		if($configuration) {
			foreach($configuration as $key => $value) {
				switch($key) {
				case 'debugging':
					$val = ($value AND $value !== '0') ? 2 : 0;
					Configure::write('debug', $val);
					break;
				case 'caching':
					if($value AND $value !== '0') {
						Configure::write('Cache.check', true);
						Configure::write('Cache.disable', false);
					}else{
						Configure::write('Cache.check', false);
						Configure::write('Cache.disable', true);
					}
					break;
				default:
					Configure::write($key, $value);
				}
			}
		}
		
		if(!empty($configuration) AND $configuration['caching']) {
			Cache::write('App.config', $configuration, 'default');
		}
	}
	
	
	
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$this->getConfig();
	}
	
	
	
	
}
?>