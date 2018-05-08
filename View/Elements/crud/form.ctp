<?php
	if(!empty($crudFieldlist)) {
		if(!isset($modelName))
			$modelName = null;
		
		echo $this->Form->create($modelName, array('novalidate' => 'novalidate'));
		
		foreach($crudFieldlist as $key => $fieldDef) {
			$options = array();
			if(is_string($fieldDef)) {
				$fieldname = $fieldDef;
			}elseif(is_string($key)) {
				$fieldname = $key;
			}
			if(is_array($fieldDef)) {
				// the field key is only set by CrudComponent's model inspector - it does not touch the fieldlist key
				if(isset($fieldDef['field'])) {
					$fieldname = $fieldDef['field'];
				}
				
				// fieldlist may be manually defined in model: fieldName => array(formOptions), but set to form_options by CrudComponent
				if(isset($fieldDef['formoptions'])) {
					$options = $fieldDef['formoptions'];
					if(!empty($fieldDef['title']) AND !array_key_exists('title', $options)) {
						$options['title'] = $fieldDef['title'];
						if(empty($options['class'])) {
							$options['class'] = 'info';
						}else{
							$options['class'] .= ' info';
						}
					}
					// select options
					if(isset($options['options']) AND is_string($options['options'])) {
						// the options array is a set variable
						$options['options'] = $$options['options'];
					}
					if(!array_key_exists('empty', $options)) $options['empty'] = '-';
				}else{
					if(empty($fieldDef['default'])) $options['empty'] = ' - none - ';
				}
			}
			echo $this->Form->input($fieldname, $options);
		}
		
		echo $this->Form->end('submit');
	}
?>