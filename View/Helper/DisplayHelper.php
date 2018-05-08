<?php
App::uses('AppHelper', 'View/Helper');
class DisplayHelper extends AppHelper {
	
	var $helpers = array(
		'Html'
	);
	
	
	
	function dispatch($args = null, $value = null, $record = null, $fieldname = null) {
		if(empty($args)) return null;
		if(is_string($args)) {
			return $this->{$args}($value);
		}
		if(is_array($args)) {
			$method = false;
			if(!empty($args['method'])) {
				$method = $args['method'];
				unset($args['method']);
			}
			if($method == 'display' OR !method_exists($this, $method))
				return $this->display($value);
			// if $args is an array, $value and $record are optional
			if($method) return $this->{$method}($args, $value, $record, $fieldname);
		}
		return null;
	}
	
	
	function display($value = null) {
		if(is_array($value)) $value = 'array';
		if(is_object($value)) $value = 'object';
		if($value === null) $value = 'NULL';
		if(empty($value)) $value = '-';
		return $value;
	}
	
	
	function link($args = array(), $id = null) {
		$action = 'index';
		if(!empty($id)) $action = 'view';
		if(!empty($args['label']) AND !empty($args['url'])) {
			$args['url']['action'] = $action;
			if(!empty($id) AND !isset($args['url'][0])) $args['url'][0] = $id;
			return $this->Html->link($args['label'], $args['url']);
		}
	}
	
	function downloadLink($url = null) {
		return $this->Html->link('Download', $url);
	}
	
	function inlineForm($args = array(), $value = null, $record = null, $fieldname = null) {
		$vars = array(
			'record' => $record,
			'value' => $value,
			'fieldname' => $fieldname
		);
		if(isset($args['sortableOptions'])) $vars['fieldlistOptions'] = $args['sortableOptions'];
		return $this->_View->element('index/position_form', $vars); 
	}
	
	
	
	
	
	function hasContextActions($crudActions = array()) {
		$hasContext = false;
		if(!empty($crudActions)) {
			foreach($crudActions as $action) {
				$context = false;
				if(isset($action['contextual'])) {
					$context = (bool) $action['contextual'];
					if($context) {
						$hasContext = true;
						break;
					}
				}
			}
		}
		
		return $hasContext;
	}
}
?>