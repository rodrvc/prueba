<!DOCTYPE html>
<html lang="es">
	<head>
		<?= $this->Html->charset(); ?>
		<?= $this->Html->tag('title', 'SKECHERS CHILE'); ?>
		<?= $this->Html->meta('icon'); ?>

		<meta name="viewport" content="width=device-width, initial-scale=1">


		<? if (isset($producto) && $producto) : ?>
		<link rel="image_src" href="http://www.skechers.cl/img/<?= $producto['Producto']['foto']['path']; ?>">
		<meta name="twitter:image:src" content="http://www.skechers.cl/img/<?= $producto['Producto']['foto']['path']; ?>" property="og:image">
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="@skecherschile">
		<meta name="twitter:creator" content="@skecherschile">
		<meta name="twitter:domain" content="Skechers.cl">
		<meta name="twitter:title" content="<?= $producto['Producto']['nombre']; ?>" property="og:title">
		<meta name="twitter:description" content="<?= $producto['Producto']['descripcion']; ?>" property="og:description">
		<meta name="twitter:url" content="http://www.skechers.cl/detalle/<?= $producto['Producto']['slug']; ?>" property="og:url">
	<!--	<meta property="og:type" content="website">
		<meta property="og:title" content="Skechers Chile - <?= $producto['Producto']['nombre']; ?>" />
		<meta property="og:type" content="company" />
		<meta property="og:url" content="http://store.skechers-chile.cl/detalle/<?= $producto['Producto']['slug']; ?>" />
		<meta property="og:image" content="http://store.skechers-chile.cl/img/<?= $producto['Producto']['foto']['ith']; ?>" />
		<meta property="og:site_name" content="Skechers Chile" />
		<meta property="fb:admins" content="1062091508" />
		<meta property="og:description" content="Skechers Chile - <?= $producto['Producto']['nombre']; ?>" /> -->
		<? else : ?>
		<meta property="og:title" content="Skechers Chile" />
		<meta property="og:type" content="game" />
		<meta property="og:url" content="http://store.skechers-chile.cl/" />
		<meta property="og:image" content="http://store.skechers-chile.cl/img/logo-skechers.png" />
		<meta property="og:site_name" content="Skechers Chile" />
		<meta property="fb:admins" content="1062091508" />
		<meta property="og:description" content="Skechers Chile" />
		<? endif; ?>

		<!--ESTILOS-->
		<?= $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/bootstrap.min.css'); ?>
		<?= $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/skechers-bootstrap-cybermonday.css'); ?>
		<?= $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/font-awesome.min.css'); ?>
		<?= $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/fancybox.css'); ?>
		<style type="text/css" media="all">
		.container {
			max-width: 1024px;
		}
		</style>
		<!--JS-->
		<?= $this->Html->scriptBlock("var webroot = '{$this->webroot}';"); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/jquery-1.11.1.min.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/www.andain.cl-jquery-ui.min.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/bootstrap.min.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/www.andain.cl-fancybox.pack.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/www.andain.cl-funciones-1.11.js'); ?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<?
		$texto_usuario ='';
		if(isset($_SESSION['Auth']['Usuario']))
			$texto_usuario = "{'userId' : '".$_SESSION['Auth']['Usuario']['id']."'}";
		?>
		<script>
		dataLayer = [<?=$texto_usuario;?>];
		</script>


	</head>
	<body>
	<script src="https://cdn1.mingadigital.com/px/2322_ldr.js" async></script>
		<?= $this->element('promo'); ?>
		<?= $this->element('ga'); ?>
		<?= $this->element('pixel'); ?>

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
		<?= $this->element('alertas'); ?>
		<div class="row">
			<div class="col-md-12">
			<img src="<?= $this->Shapeups->imagen('cybermonday2016/banner_cyberday.jpg'); ?>" width="100%">
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
	</body>
</html>
