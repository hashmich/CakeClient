<?php
echo '<tr>';
	if(!empty($bulkprocessing)) {
		echo '<th class="select">';
			echo $this->Form->input('check_all', array(
				'type' => 'checkbox',
				'hiddenField' => false,
				'label' => false,
				'onchange' => 'toggleBulk()'
			));
		echo '</th>';
	}
	if(!empty($crudActions) AND $this->Display->hasContextActions($crudActions)) {
		echo '<th class="actions">Actions</th>';
	}
	if(	isset($crudRelations)
	AND	(!empty($crudRelations['hasMany']) OR !empty($crudRelations['hasAndBelongsToMany']))
	) {
		//echo '<th class="relations">Relations</th>';
	}
	if(!empty($crudFieldlist)) foreach($crudFieldlist as $key => $fieldDef) {
		$fieldname = $key;
		$fieldModelName = $modelName;
		fieldnameSplit($key, $fieldname, $fieldModelName);
		if(!is_array($fieldDef)) $fieldDef = array('label' => $fieldDef);
		if(empty($fieldDef['label'])) $fieldDef['label'] = Inflector::camelize($fieldname);
		
		$title = (!empty($fieldDef['title'])) ? ' class="info" title="' . $fieldDef['title'] . '"' : '';
		echo '<th' . $title . '>';
			if(!empty($this->request->params['paging'][$modelName])) {
				$named = array();
				if(!empty($this->request->params['named'])) $named = $this->request->params['named'];
				if(	!empty($named['sort']) AND $named['sort'] == $fieldModelName . '.' . $fieldname
				AND	!empty($named['direction']) AND strtolower($named['direction']) == 'desc'
				) {
					// build a link to reset sorting
					unset($named['sort']);
					unset($named['direction']);
					$url = array(
						'action' => $this->request->params['action'],
						'controller' => $this->request->params['controller']
					);
					$url = array_merge($url, $this->request->params['pass'], $named);
					echo $this->Html->link($fieldDef['label'], $url, array('class' => 'desc'));
					
				}else{
					echo $this->Paginator->sort($fieldModelName . '.' . $fieldname, $fieldDef['label']);
				}
			}else{
				echo $fieldDef['label'];
			}
		echo '</th>';
	}
echo '</tr>';
?>