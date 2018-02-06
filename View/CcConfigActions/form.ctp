<?php
	if(!empty($crudFieldlist)) {
		$model = null;
		if(isset($modelName)) {
			$model = $modelName;
		}
		
		echo $this->Form->create($model);
		
		foreach($crudFieldlist as $key => $value) {
			$options = array();
			if(is_string($value)) {
				$field = $value;
			}elseif(is_string($key)) {
				$field = $key;
			}
			if(is_array($value)) {
				// the field key is only set by CrudComponent's model inspector - it does not touch the fieldlist key
				if(isset($value['field'])) {
					$field = $value['field'];
				}
				// fieldlist may be manually defined in model: fieldName => array(formOptions), but set to form_options by CrudComponent
				if(isset($value['form_options'])) {
					$options = $value['form_options'];
					if(isset($options['options']) AND is_string($options['options'])) {
						// the options array is a set variable
						$options['options'] = $$options['options'];
					}
					$options['empty'] = '-';
				}
			}
			
			echo $this->Form->input($field, $options);
		}
		
		if($this->data['CcConfigAction']['has_view']) {
			echo $this->Form->input('CcConfigActionsViewsAction', array(
				'type' => 'select',
				'multiple' => 'checkbox',
				'label' => 'Link Actions'
			));
		}
		
		echo $this->Form->end('submit');
	}
?>