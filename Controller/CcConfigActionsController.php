<?php
class CcConfigActionsController extends CakeclientAppController {
	
	
	public $uses = array('CcConfigAction');
	
	
	function edit($id = null) {
		if(empty($id)) {
			$this->redirect('index');
		}
		$record = $this->CcConfigAction->find('first', array(
			'contain' => array(),
			'conditions' => array('CcConfigAction.id' => $id)
		));
		
		if(empty($this->request->data['CcConfigAction'])) {
			$this->request->data = $record;
			
		}else{
			$data['CcConfigAction'] = $this->request->data['CcConfigAction'];
			
			$this->CcConfigAction->save($data);
		}
		
		$this->render('form');
	}
	
}
?>