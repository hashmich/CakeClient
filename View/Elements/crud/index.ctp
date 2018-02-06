<?php
// set the listname like "naughtyUsersList" for controller NaughtyUsers
$list = Inflector::variable($controllerName) . 'List';
echo '<div class="scroll_wrapper">';
	echo '<table>';
		
		echo $this->element('index/table_head');
		
		if(!empty($$list)) {
			foreach($$list as $k => $record) {
				if(isset($record[$modelName])) {
					echo '<tr>';
						if(!empty($bulkprocessing)) {
							echo '<td class="select">';
								echo $this->Form->input('Bulkprocessor.' . $record[$modelName]['id'], array(
									'type' => 'checkbox',
									'hiddenField' => false,
									'label' => false,
									'value' => $record[$modelName]['id'],
									'class' => 'bulkprocessor_item'
								));http://localhost/DH/userhttp://localhost/DH/ushttp://localhost/DH/users/approve/234865ers/approve/234865s/approve/234865
							echo '</td>';
						}
						if(!empty($crudActions) AND $this->Display->hasContextActions($crudActions)) {
							echo '<td class="actions">';
								echo $this->element('index/actions', array(
									'record_id' => $record[$modelName]['id']
								));
							echo '</td>';
						}
						if(	isset($crudRelations)
						AND	(!empty($crudRelations['hasMany']) OR !empty($crudRelations['hasAndBelongsToMany']))
						) {
							/*/echo '<td class="children">';
								echo $this->element('relations/child_classes', array('record' => $record));
								echo $this->element('relations/habtm_classes', array('record' => $record));
							echo '</td>';*/
						}
						foreach($crudFieldlist as $key => $fieldDef) {
							$foreignKeyValue = null;
							$fieldname = $key;
							$fieldModelName = $modelName;
							fieldnameSplit($key, $fieldname, $fieldModelName);
							
							$_fieldname = $fieldname;
							// be aware of NULL values!
							if(isset($record[$fieldModelName]) AND array_key_exists($fieldname, $record[$fieldModelName])) {
								$value = $record[$fieldModelName][$fieldname];
								//if(strlen($value) > 153)
								//	$value = substr($value, 0, 150) . '...';
								// check whether we have a foreign model field to display
								if(isset($fieldDef['displayField'])) {
									$key = $fieldname = $fieldDef['displayField'];
									$fieldModelName = $modelName;
									fieldnameSplit($key, $fieldname, $fieldModelName);
									if(isset($record[$fieldModelName]) AND isset($record[$fieldModelName][$fieldname]))
										$foreignKeyValue = $record[$fieldModelName][$fieldname];
								}
								if(!empty($fieldDef['display'])) {
									if(!empty($foreignKeyValue)) {
										$pre = null;
										if(!empty($record[$fieldModelName]['id']))
											$pre = $record[$fieldModelName]['id'].': ';
										$fieldDef['display']['label'] = $pre.$foreignKeyValue;
									}else{
										// if the foreignKey field has no value (NULL), make no links or other fancy stuff
										$fieldDef['display'] = array('method' => 'display');
									}
									$value = $this->Display->dispatch($fieldDef['display'], $value, $record[$modelName], $_fieldname);
								}
								unset($foreignKeyValue);
								
								echo '<td>' . $value . '</td>';
							}
						}
					echo '</tr>';
				}
			}
		}
	echo '</table>';
echo '</div>';
?>