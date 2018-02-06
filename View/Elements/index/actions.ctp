<?php
	if(!empty($crudActions)) {
		if(empty($primaryKeyName)) {
			$primaryKeyName = 'id';
		}
		foreach($crudActions as $k => $action) {
			if(!empty($action['contextual'])) {
				if(!isset($record_id)) {
					if(!empty($record[$modelName][$primaryKeyName])) {
						$record_id = $record[$modelName][$primaryKeyName];
					}elseif(!empty($this->data[$modelName][$primaryKeyName])) {
						$record_id = $this->data[$modelName][$primaryKeyName];
					}
				}
				if(!empty($record_id)) {
					$action['url'] .= '/'.$record_id;
				}
				$options = array('class' => strtolower($action['label']));
				if(strtolower($action['name']) == 'delete') {
					$options['confirm']  = 'Are you sure to delete ' . $modelName . ' with ID ' . $record_id . '?';
				}
				echo $this->Html->link($action['label'], $action['url'], $options);
			}
		}
	}
?>