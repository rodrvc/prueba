<!DOCTYPE html>
<html lang="es">
	<head>
		<?= $this->Html->charset(); ?>
		<?= $this->Html->tag('title', 'SKECHERS CHILE'); ?>
		<?= $this->Html->meta('icon'); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<? if (isset($producto) && $producto) : ?>
		<meta property="og:title" content="Skechers Chile - <?= $producto['Producto']['nombre']; ?>" />
		<meta property="og:type" content="company" />
		<meta property="og:url" content="http://store.skechers-chile.cl/detalle/<?= $producto['Producto']['slug']; ?>" />
		<meta property="og:image" content="http://store.skechers-chile.cl/img/<?= $producto['Producto']['foto']['ith']; ?>" />
		<meta property="og:site_name" content="Skechers Chile" />
		<meta property="fb:admins" content="1062091508" />
		<meta property="og:description" content="Skechers Chile - <?= $producto['Producto']['nombre']; ?>" />
		<? else : ?>
		<meta property="og:title" content="Skechers Chile" />
		<meta property="og:type" content="game" />
		<meta property="og:url" content="http://store.skechers-chile.cl/" />
		<meta property="og:image" content="http://store.skechers-chile.cl/img/logo-skechers.png" />
		<meta property="og:site_name" content="Skechers Chile" />
		<meta property="fb:admins" content="1062091508" />
		<meta property="og:description" content="Skechers Chile" />
		<? endif; ?>

		<?= $this->Html->css('bootstrap.min'); ?>
		<?= $this->Html->css('skechers-bootstrap'); ?>
		<?= $this->Html->css('font-awesome.min'); ?>
		<?= $this->Html->scriptBlock("var webroot = '{$this->webroot}';"); ?>
		<?= $this->Html->script('jquery-1.11.1.min'); ?>
		<?= $this->Html->script('bootstrap.min'); ?>
		<?= $this->Html->script('www.andain.cl-funciones-1.11'); ?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="trabaje">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?= $this->element('new_top'); ?>
				</div>
				<div class="col-md-12">
					<?= $this->element('new_menu'); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?= $content_for_layout; ?>
			</div>
		</div>
		<div class="row">
			<div class="footer">
				<div class="container">
					<?= $this->element('new_footer'); ?>
				</div>
			</div>
		</div>
		<?= $this->element('alertas'); ?>
		<script>
			$('ul.nav li.dropdown').hover(function() {
				$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
			}, function() {
				$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
			});
			$(document).on('change', '.familiar', function() {
				var valor = $('.familiar').val();
				if(valor == 1) {
					$('.sitengo').removeClass('hidden');
					$('.sitengo').addClass('show');
				} else {
					$('.sitengo').removeClass('show');
					$('.sitengo').addClass('hidden');
				}
			});
		</script>
	</body>
</html>