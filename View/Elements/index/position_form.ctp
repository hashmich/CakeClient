<?php
	/**
	* Expected variables:
	* $vars['record'] = $record,
	* $vars['value'] = $value (of record)
	* ($vars['fieldlistOptions']) - optional: an alternative array of select-options
	*/
	
	// pretend it's an index view
	$options = $record['SortableOptions']['editOptions'];
	if(isset($fieldlistOptions)) {
		if($fieldlistOptions === 'add') {
			$options = $record['SortableOptions']['addOptions'];
		}elseif(is_array($fieldlistOptions)) {
			$options = $fieldlistOptions;
		}
	}
	$opts = array(
		'label' => false,
		'type' => 'select',
		'options' => $options,
		'selected' => $value,
		'error' => false,
		'onchange' => 'this.form.submit()'
	);
	if(!empty($record['SortableOptions']) AND $record['SortableOptions']['allowNull'] === true) {
		$opts['empty'] = 'null';
	}
	if(empty($value) AND !empty($record['SortableOptions'])) {
		$opts['options'] = $record['SortableOptions']['addOptions'];
	}
	$id = null;
	if(empty($primaryKeyName)) $primaryKeyName = 'id';
	if(isset($record[$primaryKeyName])) {
		$id = $record[$primaryKeyName];
	}elseif(isset($record[$modelName][$primaryKeyName])) {
		$id = $record[$modelName][$primaryKeyName];
	}
	
	echo $this->Form->create($modelName, array(	
		'class' => 'position_form',
		'id' => 'PositionEditForm_' . $id,
		'url' => array(
			'action' => 'edit',
			'plugin' => Configure::read('Cakeclient.prefix'),
			$id
		)
	));
	
	echo $this->Form->input($fieldname, $opts);
	
	echo $this->Form->end(array(
		'label' => '>',
		'id' => 'PositionEditSubmit_' . $id,
		'class' => 'positioning_submit'
	));
	
	
	// remove the submit buttons, as long the form will be submitted by js
	if(empty($positon_form)) {
		$this->Html->scriptBlock(array('inline' => false));
		?>
		$(document).ready(function() {
			var elements = document.getElementsByClassName('positioning_submit');
			i = elements.length;
			while(i--) {
				elements[i].style.display = "none";
				elements[i].form.style.minWidth = "40px";
			}
		});
		<?php
		$this->Html->scriptEnd();
		$this->set('position_form', true);
	}
	?>