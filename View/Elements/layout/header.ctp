<div id="header">
	<?php
	if($logo = Configure::read('Cakeclient.logo_image')) {
		$options = array(
			'alt' => 'Logo',
			'class' => 'left'
		);
		if($url = Configure::read('Cakeclient.logo_url')) $options['url'] = $url;
		else $options['url'] = '/';
		echo $this->Html->image($logo_left, $options);
	}
	?>
	
	<h1><?php if(!empty($page_title)) echo $page_title; ?></h1>
	
	<?php
	if(!empty($login_info)) {
		echo '<div class="login_info">';
		if(is_array($login_info)) {
			$link_title = $login_info['title'];
			unset($login_info['title']);
			echo '<p>' . $this->Html->link($link_title, $login_info) . '</p>';
		}
		echo '</div>';
	}
	?>
	
	<?php
	if($logo_2 = Configure::read('Cakeclient.logo_right_image')) {
		$options = array(
			'alt' => 'Logo',
			'class' => 'right'
		);
		if($url = Configure::read('Cakeclient.logo_right_url')) $options['url'] = $url;
		else $options['url'] = '/';
		echo $this->Html->image($logo_2, $options);
	}
	?>
</div>