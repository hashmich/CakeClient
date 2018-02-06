<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php
		if(!empty($page_title)) echo $page_title;
		// we're using title_for_layout as a subtitle
		if(!empty($title_for_layout)) echo ' - ' . $title_for_layout;
		?>
	</title>
	<?php
	if(!Configure::read('Cakeclient.robots') OR Configure::read('debug') > 0) {
		echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex'));
	}
	
	$meta_description = Configure::read('Cakeclient.description');
	$meta_keywords = Configure::read('Cakeclient.keywords');
	if($meta_keywords) {
		echo $this->Html->meta('keywords', $meta_keywords);
	}
	if($meta_description) {
		echo $this->Html->meta('description', $meta_description);
	}
	echo $this->fetch('meta');
	
	echo $this->Html->meta('icon');
	
	echo $this->Html->css('Cakeclient.styles.css');
	if(Configure::read('debug') > 0) {
		echo $this->Html->css('Cakeclient.cake_debugging.css');
	}
	
	// #ToDo: streamline the styles with bootstrap
	/*echo $this->Html->css('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', array(
		'integrity' => 'sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7',
		'crossorigin' => 'anonymous'));*/
	
	// custom CSS
	echo $this->fetch('css');
	?>
	
</head>
<body>
	<div id="container">
		
		<?php echo $this->element('layout/header'); ?>
		
		<div class="columns">
			<div id="menu">
				<?php echo $this->element('layout/menu'); ?>
			</div>
			
			<div id="content">
				<h2><?php echo $title_for_layout; ?></h2>
				<?php
				echo $this->Session->flash();
				echo $this->fetch('content');
				?>
			</div>
		</div>
		
		<div id="footer">
			<?php
			if($footer = Configure::read('Cakeclient.footer')) {
				if(is_string($footer)) {
					echo $footer;
				}
				elseif(is_array($footer)) {
					if(!empty($footer['element'])) {
						echo $this->element($footer['element']);
					}
					if(!empty($footer['text'])) {
						echo $footer['text'];
					}
				}
			}
			?>
		</div>
	</div>
	
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"
		integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
		crossorigin="anonymous">
	</script>
	<script type="text/javascript">
		window.jQuery || document.write('<script type="text/javascript" src="<?php echo $this->Html->url('/js/jquery-1.12.4.min.js', true); ?>"><\/script>')
	</script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" 
		integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" 
		crossorigin="anonymous">
	</script>
	<script type="text/javascript">
		(typeof $().modal == 'function') || document.write('<script type="text/javascript" src="<?php echo $this->Html->url('/js/bootstrap.min.js', true); ?>"><\/script>')
	</script>
	
	<?php echo $this->fetch('script'); ?>
	
</body>
</html>




