<?php
// a testcase view for the forms css class!
if(!empty($title_for_layout)) echo '<h2>'.$title_for_layout.'</h2>';

echo $this->Form->create();

echo $this->Form->input('name', array(
	'type' => 'text'
));
echo $this->Form->input('username', array(
	'type' => 'text'
));
echo $this->Form->input('drehen', array(
	'type' => 'checkbox'
));
echo $this->Form->input('wenden', array(
	'type' => 'checkbox'
));
echo $this->Form->input('verdrehen', array(
	'type' => 'checkbox',
	'label' => 'kldjfgklkj lksjdglhj ljdgkljf njk kjf odfigio ij jfgoi lsfdigh bn fgtb ldjfglb jjsdlgj kjfhglk j'
));
echo $this->Form->input('passwort', array(
	'type' => 'password'
));
echo $this->Form->input('Geschlecht', array(
	'type' => 'radio',
	'options' => array(
		'rot',
		'gelb',
		'kldjfgklkj lksjdglhj ljdgkljf njk kjf odfigio ij jfgoi lsfdigh bn fgtb ldjfglb jjsdlgj kjfhglk j',
		'lila'
	)
));
echo $this->Form->input('datum', array(
	'type' => 'date'
));
echo $this->Form->input('zeit', array(
	'type' => 'time'
));
echo $this->Form->input('Datum & Zeit', array(
	'type' => 'datetime'
));
echo $this->Form->input('fach', array(
	'type' => 'select',
	'options' => array(
		'Englisch',
		'Latein',
		'Mathe',
		'Philosophie'
	)
));
echo $this->Form->input('belag', array(
	'type' => 'select',
	'multiple' => 'checkbox',
	'div' => array(
		'class' => 'input select checkbox'
	),
	'options' => array(
		'Kaese',
		'Tomaten',
		'scharf!',
		'Oregano',
		'Knoblauch',
		'Sardellen',
		'kldjfgklkj lksjdglhj ljdgkljf njk kjf odfigio ij jfgoi lsfdigh bn fgtb ldjfglb jjsdlgj kjfhglk j',
		'Oliven',
		'Kapern'
	)
));
echo $this->Form->input('belag', array(
	'type' => 'select',
	'multiple' => 'checkbox',
	'options' => array(
		'Kaese',
		'Tomaten',
		'scharf!',
		'Oregano',
		'Knoblauch',
		'Sardellen',
		'kldjfgklkj lksjdglhj ljdgkljf njk kjf odfigio ij jfgoi lsfdigh bn fgtb ldjfglb jjsdlgj kjfhglk j',
		'Oliven',
		'Kapern'
	)
));
echo $this->Form->input('bemerkungen', array(
	'type' => 'textarea'
));
echo $this->Form->end('Senden');
?>