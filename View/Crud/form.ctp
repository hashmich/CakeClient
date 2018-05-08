<?php
if(!empty($title_for_layout)) echo '<h2>'.$title_for_layout.'</h2>';
echo $this->element('layout/actions', array(), array('plugin' => 'Cakeclient'));

echo $this->element('crud/form', array(), array('plugin' => 'Cakeclient'));
?>