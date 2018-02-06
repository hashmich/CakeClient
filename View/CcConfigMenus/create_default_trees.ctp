

<p>
	After chosing the route prefix, the default menu trees will be generated persistently 
	in the database for further editing. 
	You will have to assign one or many ACOs after that, in order to make these menus show 
	up for anybody. 
</p>

<?php
echo $this->Form->create('CcConfigMenu');
echo $this->Form->input('route_prefix', array('options' => $route_prefixes));
echo $this->Form->end("submit")
?>