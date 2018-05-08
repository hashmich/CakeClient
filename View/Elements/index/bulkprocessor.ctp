<?php
	$list = Inflector::variable($controllerName) . 'List';
	if(empty($bulkprocessing) AND !empty($$list) AND !empty($crudActions)) {
		$bulk_options = array();
		foreach($crudActions as $k => $action) {
			if(!empty($action['bulk_processing']) AND !empty($action['contextual'])) {
				// key is the string method name, if the action was defined via the default CRUD action list, numeric if from db
				$bulk_options[$action['name']] = $action['label'];
				$bulkprocessing = 1;
				unset($crudActions[$k]);
			}
		}
		$this->set(compact('crudActions', 'bulk_options'));
		
		if(!empty($bulkprocessing)) {
			// collect items - buffered in 'script' block
			$this->Html->scriptStart(array('inline' => false));
				?>
				function collectBulkItems(form) {
					var confirmation = confirm("Are you sure you want to perform the selected action?");
					if(confirmation) {
						var elements = document.getElementsByClassName('bulkprocessor_item');
						var i = elements.length;
						var collection = [];
						while(i--) {
							if(elements[i].checked) collection.push(elements[i].value);
						}
						form.BulkProcessorItems.value = JSON.stringify(collection);
					}
					return confirmation;
				}
				
				function toggleBulk() {
					var toggle = document.getElementById('check_all');
					var elements = document.getElementsByClassName('bulkprocessor_item');
					var i = elements.length;
					while(i--) {
						if(toggle.checked) elements[i].checked = true;
						else elements[i].checked = false;
					}
				}
				<?php
			$this->Html->scriptEnd();
		}
	}
	
	if(!empty($bulkprocessing)) {
		?>
		<noscript>
			You have to enable Javascript to collect items from the list for bulk processing. <br />
		</noscript>

		<?php
		echo $this->Form->create('BulkProcessor', array(
			'class' => 'bulk_processor',
			'id' => 'bulk_processor_' . $bulkprocessing,
			'onsubmit' => 'return collectBulkItems(this)'
		));
		echo $this->Form->input('items', array('type' => 'hidden'));
		echo $this->Form->input('action', array(
			'type' => 'select',
			'label' => 'Selected Items:',
			'options' => $bulk_options,
			'empty' => '-- choose action --'
		));
		echo $this->Form->end(array('label' => 'apply'));
		
		$this->set('bulkprocessing', ++$bulkprocessing);
	}
?>











