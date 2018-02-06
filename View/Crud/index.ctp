<?php


if(!empty($title_for_layout)) echo '<h2>'.$title_for_layout.'</h2>';
echo $this->element('layout/actions', array(), array('plugin' => 'Cakeclient'));

$this->Html->scriptStart(array('inline' => false));
?>
$(document).ready(function() {
	$('#cakeclient-ancestry > .toggle').on('click', function() {
		if($(this).hasClass('off')) {
			$('#cakeclient-ancestrypane').show();
		}else{
			$('#cakeclient-ancestrypane').hide();
		}
		$(this).toggleClass('off');
		$('#cakeclient-ancestry > .toggle > span').toggleClass('glyphicon-menu-down');
		$('#cakeclient-ancestry > .toggle > span').toggleClass('glyphicon-menu-up');
	});
});
<?php
$this->Html->scriptEnd();
?>
<div id="cakeclient-ancestry">
	<h3 class="toggle off"><span class="glyphicon glyphicon-menu-down"></span> Ancestry</h3>
	<div id="cakeclient-ancestrypane" style="display:none">
		<?php
		echo $this->element('relations/parent_classes', array(), array('plugin' => 'Cakeclient'));
		echo $this->element('relations/child_classes', array(), array('plugin' => 'Cakeclient'));
		echo $this->element('relations/habtm_classes', array(), array('plugin' => 'Cakeclient'));
		?>
	</div>
</div>

<?php
echo $this->element('index/filter', array(), array('plugin' => 'Cakeclient'));

echo $this->element('index/bulkprocessor', array(), array('plugin' => 'Cakeclient'));
echo $this->element('index/pager', array(), array('plugin' => 'Cakeclient'));

// the actual listing
echo $this->element('crud/index', array(), array('plugin' => 'Cakeclient'));

echo $this->element('index/bulkprocessor', array(), array('plugin' => 'Cakeclient'));
echo $this->element('index/pager', array(), array('plugin' => 'Cakeclient'));
?>
