<?php
if(isset($crudRelations) AND !empty($crudRelations['belongsTo'])) {
	echo '<div class="associations">';
	echo '<p>Parent Classes</p>';
	echo '<ul>';
	
	foreach($crudRelations['belongsTo'] as $assoc) {
		$parent_id = false;
		if(isset($modelName) AND isset($record[$modelName]) AND isset($record[$modelName][$assoc['foreign_key']])) {
			$parent_id = $record[$modelName][$assoc['foreign_key']];
		}
		$link_url = array(
			'action' => 'index',
			'controller' => $assoc['tablename']
		);
		if($actionName == 'view' AND $parent_id) {
			$link_url['action'] = 'view';
			$link_url[0] = $record[$modelName][$assoc['foreign_key']];
		}
		echo '<li>';
		echo $this->Html->link($assoc['label'], $link_url);
		echo '</li>';
	}
	echo '</ul>';
	echo '</div>';
}
?>