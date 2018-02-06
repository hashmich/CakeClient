<?php
	if(empty($hash)) {
		echo '<p>For manually creating user accounts, type in your password to compute the hash value!</p>';
	}else{
		echo '<p>Hash: ' . $hash . '</p>';
	}
	echo $this->Form->create('User');
	echo $this->Form->input('password', array(
		'autocomplete' => 'off'
	));
	echo $this->Form->end(__('Go!'));
?>