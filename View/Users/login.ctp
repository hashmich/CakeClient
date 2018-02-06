<?php
	echo $this->Session->flash('auth');
	echo $this->Form->create('User');
	echo $this->Form->input('email', array(
		'autocomplete' => 'off'
	));
	echo $this->Form->input('password', array(
		'autocomplete' => 'off'
	));
	echo $this->Form->end(__('Login'));
?>