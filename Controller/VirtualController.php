<?php
class VirtualController extends CakeclientAppController {
	
	/**	We need to set up at least one ControllerClass, to catch incoming requests, 
	*	according to router settings. 
	*/
	
	var $crudController = true;
	
}
?>