<?php
if(!empty($crudActions)) {
	$menu_actions = array();
	// #ToDo: check if we need to filter contextual actions for this view
	if(!empty($currentViewIsNotContextual)) {
		// list views
		foreach($crudActions as $k => $action) {
			if(empty($action['contextual'])) {
				$menu_actions[] = $action;
				unset($crudActions[$k]);
			}
		}
		// set the contextual actions for further processing in the index table
		$this->set('crudActions', $crudActions);
	}else{
		// contextual views
		$menu_actions = $crudActions;
	}
	
	
	if(!empty($menu_actions)) {
		if(empty($primaryKeyName)) $primaryKeyName = 'id';
		echo '<div class="actions">';
			echo '<ul>';
				foreach($menu_actions as $action) {
					if(!empty($action['contextual'])) {
						if(!isset($record_id)) {
							$record_id = null;
							if(!empty($record[$modelName][$primaryKeyName])) {
								$record_id = $record[$modelName][$primaryKeyName];
							}elseif(!empty($this->data[$modelName][$primaryKeyName])) {
								$record_id = $this->data[$modelName][$primaryKeyName];
							}
						}
						if(!empty($record_id)) {
							$action['url'] .= '/'.$record_id;
						}
					}
					$options = array('class' => strtolower($action['label']));
					if(strtolower($action['name']) == 'delete') {
						$options['confirm']  = 'Are you sure to delete ' . $modelName . ' with ID ' . $record_id . '?';
					}
					echo '<li>';
						echo $this->Html->link($action['label'], $action['url'], $options);
					echo '</li>';
				}
			echo '</ul>';
		echo '</div>';
	}
}
?>