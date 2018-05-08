<?php
	if($this->Paginator->hasNext() OR $this->Paginator->hasPrev()) {
		// unset page - otherwise we would stay on the same page (paging variables are stored within key "paging" in params array)
		unset($this->request->params['named']['page']);
		
		// paginator last & first return an empty string if there is no prev or next!
		$text = $this->Paginator->first('|<', array('url' => $this->params['named'])) . ' ';
		if(!$this->Paginator->hasPrev()) {
			$text .= $this->Html->tag('span', '|<', array('class' => 'disabled'));
		}
		$text .= $this->Paginator->prev('<<', null, null, array('class' => 'prev disabled'));
		$text .= $this->Paginator->counter();
		$text .= $this->Paginator->next('>>', null, null, array('class' => 'next disabled'));
		$text .= $this->Paginator->last(' >|', array('url' => $this->params['named']));
		if(!$this->Paginator->hasNext()) {
			$text .= $this->Html->tag('span', '>|', array('class' => 'disabled'));
		}
		
		echo $this->Html->tag('div', $text, array('class' => 'paging'));
	}
	
	$limit_options = array(5,10,20,40,80,160);
	$params = $this->Paginator->params();
	if(!empty($params['count']) AND $params['count'] > $limit_options[0]) {
		if(empty($paging_form_count)) $paging_form_count = 1;
		echo $this->Form->create('Pager', array('class' => 'paging_limit', 'id' => 'paging_form' . $paging_form_count));
		echo $this->Form->input('limit', array(
			'label' => 'results per page',
			'onchange' => 'this.form.submit()',
			'options' => array_combine($limit_options, $limit_options),
			'default' => 10,
			'selected' => $params['limit']
		));
		$id = 'submit_paging_form' . $paging_form_count;
		echo $this->Form->end(array(
			'label' => 'apply',
			'div' => array('id' => $id)
		));
		
		$this->Html->scriptStart(array('inline' => false));
			?>
			$(document).ready(function() {
				var element = document.getElementById('<?php echo $id; ?>');
				element.style.display = "none";
			});
			<?php
		$this->Html->scriptEnd();
		
		$this->set('paging_form_count', ++$paging_form_count);
	}
?>