<?php
if(isset($crudRelations) AND !empty($crudRelations['hasAndBelongsToMany'])) {
	echo '<div class="associations">';
	echo '<p title="An item of this class &quot;has and belongs to many&quot; items of the other class.">n:m Relations</p>';
	echo '<ul>';
	$parent_id = false;
	if(empty($primaryKeyName)) {
		$primaryKeyName = 'id';
	}
	if(isset($modelName) AND isset($record[$modelName]) AND isset($record[$modelName][$primaryKeyName])) {
		$parent_id = $record[$modelName][$primaryKeyName];
	}
	foreach($crudRelations['hasAndBelongsToMany'] as $assoc) {
		$link_url = array(
			'action' => 'index',
			'controller' => $assoc['tablename'],
			$modelName . '.' . $assoc['primary_key'] => $parent_id
		);
		if(!$parent_id) unset($link_url[$assoc['primary_key']]);
		echo '<li>';
		echo $this->Html->link($assoc['label'], $link_url);
		echo '</li>';
	}
	echo '</ul>';
	echo '</div>';
}
?>