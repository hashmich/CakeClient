<?php
// we need to create this, to make the plugin's AppModel
// methods available without loading any other model
class Dummy extends CakeclientAppModel {
	
	public $useTable = false;
	
}
?>