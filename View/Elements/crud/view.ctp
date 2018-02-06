<?php
	if(!empty($crudFieldlist)) {
		echo '<dl>';
			foreach($crudFieldlist as $key => $fieldDef) {
				$fieldname = $key;
				if(is_array($fieldDef)) {
					$fieldname = $fieldDef['fieldname'];
				}
				$fieldModelName = $modelName;
				fieldnameSplit($key, $fieldname, $fieldModelName);
				
				$value = (isset($record[$fieldModelName][$fieldname])) ? $record[$fieldModelName][$fieldname] : '';
				
				$title = (!empty($fieldDef['title'])) ? ' class="info" title="' . $fieldDef['title'] . '"' : '';
				
				$label = (isset($fieldDef['label'])) ? $fieldDef['label'] : ucfirst($fieldname);	// allow empty labels - empty string
				
				
				// check if the column is a foreign_key
				if(is_array($fieldDef) AND isset($fieldDef['displayField'])) {
					$fieldname = $fieldDef['displayField'];
					$fieldModelName = $modelName;
					fieldnameSplit($key, $fieldname, $fieldModelName);
					// the column shows data from a foreign model - make a link to index action
					if(	isset($record[$fieldModelName][$fieldname])
					AND	$fieldModelName != $modelName
					) {
						$foreignKeyValue = $value;
						// re-fetch the value, in case it is located under a different model key
						$value = $record[$fieldModelName][$fieldname];
						$label = $this->Html->link($label, array(
							'action' => 'index',
							'controller' => Inflector::tableize($fieldModelName),
							'plugin' => Configure::read('Cakeclient.prefix')
						));
						$value = $this->Html->link($value, array(
							'action' => 'view',
							'controller' => Inflector::tableize($fieldModelName),
							'plugin' => Configure::read('Cakeclient.prefix'),
							$foreignKeyValue
						));
						unset($foreignKeyValue);
					}
				}
				
				if(empty($value)) {
					$value = '&nbsp;';
				}
				
				echo '<dt' . $title . '>' . $label . '</dt>';
				echo '<dd>' . $value . '</dd>';
			}
		echo '</dl>';
	}
?>