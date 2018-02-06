<?php
if(!empty($crudFieldlist)) {
	$this->Html->scriptStart(array('inline' => false));
	?>
	$(document).ready(function() {
		$('#cakeclient-filter > .toggle').on('click', function() {
			if($(this).hasClass('off')) {
				$('#cakeclient-filterpane').show();
			}else{
				$('#cakeclient-filterpane').hide();
			}
			$(this).toggleClass('off');
			$('#cakeclient-filter > .toggle > span').toggleClass('glyphicon-menu-down');
			$('#cakeclient-filter > .toggle > span').toggleClass('glyphicon-menu-up');
		});
	});
	<?php $this->Html->scriptEnd(); ?>
	<div id="cakeclient-filter">
		<h3 class="toggle off"><span class="glyphicon glyphicon-menu-down"></span> Filter</h3>
		<div id="cakeclient-filterpane" style="display:none">
			<?php
			echo $this->Form->create('Filter', array('novalidate' => 'novalidate'));
			
			$options = array();
			foreach($crudFieldlist as $k => $field) {
				$options[$field['fieldname']] = $field['label'];
			}
			
			echo $this->Form->input('field', array(
					'label' => 'Field',
					'options' => $options,
					'type' => 'select',
					'empty' => '-- filter by field --'
			));
			
			$options = array(
					'contains' => 'contains (%LIKE%)',
					'starts with' => 'starts with (LIKE%)',
					'ends with' => 'ends with (%LIKE)',
					'is' => 'is (=)',
					'is not' => 'is not (!=)',
					'greater than' => 'greater than (>)',
					'less than' => 'less than (<)'
			);
			echo $this->Form->input('operator', array('options' => $options, 'default' => 'contains'));
			
			echo $this->Form->input('value');
			
			echo $this->Form->end('apply');
			
			
			
			if(!empty($filter)) {
				echo '<p>Active Filters: (' . $this->Html->link('reset', 'index') . ')</p>';
				echo '<ul>';
				foreach($filter as $fieldname => $value) {
					$fieldname = explode('.', $fieldname);
					if(!empty($fieldname[1])) $fieldname = $fieldname[1];
					echo '<li><span>' . $fieldname . ' </span>' . $value . '</li>';
				}
				echo '</ul>';
			}
			
			?>
		</div>
	</div>
	<?php
}	
?>
