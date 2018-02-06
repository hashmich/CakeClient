<?php
class ConfigurableBehavior extends ModelBehavior {
	
	// a special Behavior class to apply Cakeclient settings to Cakeclient models
	
	function setup(Model $model, $settings = array()) {
		if($tableConfig = Configure::read('Cakeclient.tables')) {
			foreach($tableConfig as $tablename => $tabledefinition) {
				if($model->name == $tabledefinition['modelclass']) {
					if(!empty($tabledefinition['displayfield'])) {
						$model->displayField = $tabledefinition['displayfield'];
					}
					$model->useTable = $tablename;
					break;
				}
			}
		}
	}
}
?>