<?= $html->docType('xhtml-trans'); ?>
<?= $this->Facebook->html(); ?>
	<head>
		<?= $this->Html->charset(); ?>
		<?= $this->Html->tag('title', 'SKECHERS CHILE'); ?>
		<?= $this->Html->meta('icon'); ?>

		<!-- LIKE FACEBOOK -->
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
		
		<!-- HOJAS DE ESTILO -->
		<?= $this->Html->css('estructura'); ?>
		<!-- css lightbox -->
		<?= $this->Html->css('prettyPhoto'); ?>
		<?= $this->Html->css('fancybox'); ?>
		<!-- css slider -->
		<?= $this->Html->css('coin-slider-styles'); ?>
		<?= $this->Html->css('style'); ?>
		<?= ($_SERVER['REMOTE_ADDR'] == '::1')?"":"<link href='http://fonts.googleapis.com/css?family=Amatic+SC:400,700' rel='stylesheet' type='text/css'>" ?>
		<?= $this->Html->css('reset'); ?>
		<?= $this->Html->css('skechers-2014'); ?>

		<!-- ARCHIVOS JAVASCRIPT -->
		<?= $this->Html->scriptBlock("var webroot = '{$this->webroot}';"); ?>
		<?= $this->Html->script('www.andain.cl-jquery-1.7.min'); ?>
		<?= $this->Html->script('www.andain.cl-jquery.scrollTo-1.4.2-min'); ?>

		<!-- js formato moneda -->
		<?= $this->Html->script('jquery.formatCurrency-1.4.0'); ?>
		<?= $this->Html->script('jquery.formatCurrency.es-CL'); ?>
		<!-- js laibos fotito =D -->
		<?= $this->Html->script('www.andain.cl-jquery.prettyPhoto'); ?>
		<?= $this->Html->script('www.andain.cl-fancybox.pack'); ?>
		<!-- js slider -->
		<?= $this->Html->script('coin-slider'); ?>
		<?= $this->Html->script('www.andain.cl-jquery.scrollTo-1.4.2-min'); ?>
		<?= $this->Html->script('www.andain.cl-funciones'); ?>

		<!-- bootstrap-->
		<?= $this->Html->css('bootstrap'); ?>
		<?= $this->Html->css('skechers-bootstrap'); ?>
		<?= $this->Html->css('font-awesome.min'); ?>
		<!-- Fin bootstrap -->

		<? if (isset($serv_produccion) && $serv_produccion) : ?>
		<?= $this->element('coremetrics_head'); ?>
		<? endif; ?>
	</head>
	<body>
		<div class="skechers">
			<?= $this->element('new_top'); ?>
			<?= $this->element('new_menu'); ?>
			<?= $content_for_layout; ?>
		</div>
		<div class="col-md-12" style="margin-top:20px">
			<div class="row">
				<div class="footer">
					<div class="skechers">
						<?= $this->element('new_footer'); ?>
					</div>
				</div>
			</div>
		</div>


		<?= $this->Facebook->init(); ?>
		<? if (isset($serv_produccion) && $serv_produccion) : ?>
		<?= $this->element('ga'); ?>
		<?= $this->element('coremetrics_body'); ?>
		<? endif; ?>
		<?= $this->element('alertas'); ?>

		<script>
			$('ul.nav li.dropdown').hover(function() {
			  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
			}, function() {
			  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
			});
		</script>

		<script>
		  $(document).on('change', '.familiar', function(){
		  	var valor = $('.familiar').val();
		    if(valor == 1)
		    {
		    	$('.sitengo').removeClass('hidden');
		    	$('.sitengo').addClass('show');
		    }else{
		    	$('.sitengo').removeClass('show');
		    	$('.sitengo').addClass('hidden');
		    }
		  })
		</script>
	</body>
</html>
