<!DOCTYPE html>
<html lang="es">
	<head>
		<?= $this->Html->charset(); ?>
		<?= $this->Html->tag('title', 'SKECHERS CHILE'); ?>
		<?= $this->Html->meta('icon'); ?>

		<meta name="viewport" content="width=device-width, initial-scale=1">

		<? if (isset($producto) && $producto) : ?>
		<link property="og:image" href="https://s3.amazonaws.com/andain-sckechers/img/<?= $producto['Producto']['foto']['ith']; ?>">
		<meta property="og:image:secure_url" content="https://s3.amazonaws.com/andain-sckechers/img/<?= $producto['Producto']['foto']['ith']; ?>">
		<meta property="og:description" content="Skechers Chile - <?= $producto['Producto']['nombre']; ?>" /> 

		<? else : ?>
		<meta property="og:title" content="Skechers Chile" />
		<meta property="og:type" content="game" />
		<meta property="og:url" content="http://www.skechers.cl/" />
		<meta property="og:image" content="http://store.skechers-chile.cl/img/logo-skechers.png" />
		<meta property="og:site_name" content="Skechers Chile" />
		<meta property="fb:admins" content="1062091508" />
		<meta property="og:description" content="Skechers Chile" />
		<? endif; ?>

		<?
		// parametros verificacion de evento
		$ahora = date('Y-m-d H:i:s');
		$ahora = strtotime($ahora);
		// considerar desface de tiempo del servidor...
		if ( isset($desface['time']) && $desface['time'] )
		{
			if (  isset($desface['mas']) && $desface['mas']  )
				$ahora += $desface['time'];
			else
				$ahora -= $desface['time'];
		}
		$_evento = $this->Shapeups->eventos(
			array(
				'menu_categorias' => $menu_categorias,
			)
		);
		?>
		<!--ESTILOS-->
		<?= $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/bootstrap.min.css'); ?>
		<?
		if ($estado_sitio == 1)
		{
			echo $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/skechers-bootstrap-cybermonday.css');
		}
		else
		{
			echo $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/skechers-bootstrap.css');
			//echo $this->Html->css('http://www.skechers.cl/css/skechers-bootstrap.css');
		
		}
		?>
		<?= $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/font-awesome.min.css'); ?>
		<?= $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/fancybox.css'); ?>
		<?= $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/chosen.css'); ?>
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
		<?= $this->Html->script('http://localhost/skechers/ecomm/js/www.andain.cl-funciones-1.11.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/chosen.jquery.js'); ?>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.min.js"></script>
		<script src="https://wchat.freshchat.com/js/widget.js"></script>


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
	<div class="hidden-xs"><a href="https://s3.amazonaws.com/andain-sckechers/plazos_entrega.pdf" target="_blank"><img src="https://s3.amazonaws.com/andain-sckechers/img/header_promo.jpg" width="100%"></a></div>
		<div class="hidden-sm hidden-md hidden-lg"><a href="https://s3.amazonaws.com/andain-sckechers/plazos_entrega.pdf" target="_blank"><img src="https://s3.amazonaws.com/andain-sckechers/img/header_promo_mb.jpg" width="100%"></a></div>

		<?
		if ($estado_sitio == 1)
		{
			echo $this->element('promo', array(
				'_promo' => $_evento,
				'activarPromo' => true
			));
		}
		?>
		<?= $this->element('ga'); ?>
		<?= $this->element('pixel'); ?>
		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<cake:nocache>
					<?= $this->element('new_top',array('cache' => true)); ?>
				</cake:nocache>
				</div>
				<div class="col-md-12">

					<?
			
		if ($estado_sitio == 1)
					{
						$parametros['_evento'] = $_evento;
											echo $this->element('new_menu', $parametros);

					}else{
						echo $this->element('new_menu');
					}
					?>
				</div>
			</div>
		</div>
		<?= $this->element('alertas'); ?>
		<div class="row">
			<div class="col-md-12">
				<? if (false && isset($_evento['banner']) && $_evento['banner']) : ?>
				<div class="container">
					 <img src="https://s3.amazonaws.com/andain-sckechers/img/BlackFriday-Skecher.jpg" width="100%"> 
				</div>
				<? endif; ?>
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
	<script>
	$(document).ready(function(){
		$.ajax(
			{
				url: webroot + 'usuarios/ajax_info',
				success: function(data)
				{
					$('#usuario').html(data);
				}
			});

		 window.fcWidget.init({
    token: "fb7b8be6-de4c-431d-87da-60deaf92cf2a",
    host: "https://wchat.freshchat.com"
  });
	})
	</script>
</html>
