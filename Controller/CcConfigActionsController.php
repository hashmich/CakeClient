<?php
class CcConfigActionsController extends CakeclientAppController {
	
	
	
	function edit($id = null) {
		if(empty($id)) {
			$this->redirect('index');
		}
		$record = $this->CcConfigAction->find('first', array(
			'contain' => array('CcConfigActionsView'),
			'conditions' => array('CcConfigAction.id' => $id)
		));
		
		if(empty($this->data)) {
			$this->request->data = $record;
			// push the relational data into the right format for the checkboxes
			if(!empty($this->request->data['CcConfigActionsView'])) {
				foreach($this->request->data['CcConfigActionsView'] as $assoc) {
					$this->request->data['CcConfigAction']['CcConfigActionsView'][] = $assoc['child_action_id'];
				}
				unset($this->request->data['CcConfigActionsView']);
			}
			
		}else{
			$data = $deletable = array();
			$existant = $record['CcConfigActionsView'];
			if(!empty($this->request->data['CcConfigAction']['CcConfigActionsView'])) {
				$data = array();
				foreach($this->request->data['CcConfigAction']['CcConfigActionsView'] as $child_action_id) {
					$count = $this->CcConfigAction->CcConfigActionsView->find('count', array(
						'conditions' => array(
							'parent_action_id' => $id,
							'child_action_id' => $child_action_id
						)
					));
					if(!$count) {
						// save a new record if it not already exists
						$data[] = array(
							'parent_action_id' => $id,
							'child_action_id' => $child_action_id
						);
					}
				}
				$this->request->data['CcConfigActionsView'] = $data;
			}
			if(!empty($existant)) {
				foreach($existant as $record) {
					if(!in_array($record['child_action_id'], $this->data['CcConfigAction']['CcConfigActionsView'])) {
						$deletable[] = $record['id'];
					}
				}
			}
			if(!empty($deletable)) {
				$this->CcConfigAction->CcConfigActionsView->deleteAll(array('CcConfigActionsView.id' => $deletable), $cascade = true, $callbacks = true);
			}
			// unset the checkboxes before saving (doesn't actually cause problems, but who knows?)
			$checkboxes = $this->request->data['CcConfigAction']['CcConfigActionsView'];
			unset($this->request->data['CcConfigAction']['CcConfigActionsView']);
			
			// save data
			$this->request->data['CcConfigAction']['id'] = $id;
			$this->CcConfigAction->saveAll($this->request->data);
			// repopulate the checkboxes
			$this->request->data['CcConfigAction']['CcConfigActionsView'] = $checkboxes;
		}
		
		$this->render('form');
	}
	
}
?>