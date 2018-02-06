
<div id="cakeclient_navbar">
<nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
	
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" 
				class="navbar-toggle collapsed"
				data-toggle="collapse"
				data-target="#cakeclient_navbar_collapse"
				aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
	</div>
	
	<div class="collapse navbar-collapse" id="cakeclient_navbar_collapse">
		<ul class="nav navbar-nav">
			<?php
			if(!empty($cakeclientMenu)) foreach($cakeclientMenu as $m => $group) {
				if($group['CcConfigMenu']['layout_block'] != 'cakeclient_navbar') continue;
				
				$class = ' class="dropdown';
				$sr = null;
				if($active_link = false) {
					$class .= ' active';
					$sr = ' <span class="sr-only">(current)</span>';
				}
				$class .= '"';
				$dropdownOptions = array(
					'class' => 'dropdown-toggle',
					'data-toggle' => 'dropdown',
					'role' => 'button',
					'aria-haspopup' => 'true',
					'aria-expanded' => false,
					'escape' => false
				);
				$caret = ' <span class="caret"></span>';
				
				echo '<li'.$class.'>';
					echo $this->Html->link($group['CcConfigMenu']['label'].$sr.$caret, '#', $dropdownOptions);
					if(!empty($group['CcConfigMenu']['CcConfigTable'])) {
						echo '<ul class="dropdown-menu">';
							foreach($group['CcConfigMenu']['CcConfigTable'] as $t => $table) {
								echo '<li role="group_label" class="group_label">'.$table['label'].'</li>';
								if(!empty($table['CcConfigAction'])) foreach($table['CcConfigAction'] as $a => $action) {
									$linkOptions = (!empty($action['html_options'])) ? $action['html_options'] : array();
									echo '<li class="group">';
										echo $this->Html->link($action['label'], $action['url'], $linkOptions);
									echo '</li>';
								}
							}
						echo '</ul>';
					}
				echo '</li>';
			}
			?>
			
			
		</ul>
	</div>
	
</nav>
</div>
	
	