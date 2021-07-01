<?= $html->docType('xhtml-trans'); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?= $this->Html->charset(); ?>
		<?= $this->Html->tag('title', "Administración | {$title_for_layout}"); ?>
		<?= $this->Html->meta('icon'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/www.andain.cl-jquery-1.6.2.min.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/www.andain.cl-jquery.tmpl.min.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/jquery-ui-1.8.16.custom.min.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/www.andain.cl-corner.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/www.andain.cl-funciones-admin.js'); ?>
		<?= $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/admin-gestion.css'); ?>
		<?= $scripts_for_layout; ?>
	</head>
	<body>
		<!-- CONTENEDOR GENERAL -->
		<div id="admin">
			<div class="login">
				<h2 class="subtitulo">Administración</h2>
				<?= $content_for_layout; ?>
			</div>
		</div>
	</body>
</html>
