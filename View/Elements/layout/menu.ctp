<?php
	if(!empty($menu)) {
		echo '<ul>';
		foreach($menu as $item) {
			echo '<li>';
			if(!empty($item['url'])) {
				echo $this->Html->link($item['label'], $item['url']);
			}else{
				echo $item['label'];
			}
			if(!empty($item['submenu'])) {
				echo '<ul>';
				foreach($item['submenu'] as $subItem) {
					echo '<li>' . $this->Html->link($subItem['label'], $subItem['url']) . '</li>';
				}
				echo '</ul>';
			}
			echo '</li>';
		}
		echo '</ul>';
	}
?>