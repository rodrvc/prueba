<html lang="es">
	<head>
		 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Tecnobuy</title>

    <!-- Bootstrap -->
   <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous"> -->
    <link rel="shortcut icon" href="https://www.tecnobuy.cl/tb_ico.ico">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://d1d6stsa4vf3p.cloudfront.net/js/www.andain.cl-jquery-ui.min.js"></script>	

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">-->
  <?= $this->Html->css('bootstrap.css'); ?>
    <?= $this->Html->css('style.css'); ?> 
   <!-- <?= $this->Html->css('style_old.css'); ?>  --> 
    <?= $this->Html->css('choosen.css'); ?>
    <?= $this->Html->script('funciones.js'); ?>

  <!--  <link rel="stylesheet" href="http://localhost/tb_remote/css/style.css">
     <link rel="stylesheet" href="http://localhost/tb_remote/css/style_old.css"> 
     <link rel="stylesheet" href="http://localhost/tb_remote/css/bootstrap.css">  -->

    <link rel="stylesheet" type="text/css" href="https://d1d6stsa4vf3p.cloudfront.net/css/font-awesome.min.css" />
   <!--swipe--> <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css'>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script> 

 <!-- font-awesome--><script src="https://kit.fontawesome.com/d0448ed65c.js" crossorigin="anonymous"></script>

<!-- swipe js--><script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/js/swiper.min.js'></script>
		<?= $this->Html->scriptBlock("var webroot = '{$this->webroot}';"); ?>


<!--*--->
		<?
		$texto_usuario ='';
		if(isset($_SESSION['Auth']['Usuario']))
			$texto_usuario = "{'userId' : '".$_SESSION['Auth']['Usuario']['id']."'}";
		?>
		<script>
		dataLayer = [<?=$texto_usuario;?>];
		</script>
	</head>
	<body class="bg-white">			
<!--	<div class="hidden-xs"><a data-toggle="modal" data-target=".bd-example-modal-lg" href="#" target="_blank"><img src="https://s3.amazonaws.com/andain-sckechers/img/header_promo2.jpg" width="100%"></a></div>
	<div class="hidden-sm hidden-md hidden-lg"><a data-toggle="modal" data-target=".bd-example-modal-sm" href="#" target="_blank"><img src="https://s3.amazonaws.com/andain-sckechers/img/header_promo_mb.jpg" width="100%"></a></div> -->

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
		<div class="container-fluid hidden-xs hidden-sm ">


					<cake:nocache>
					<?= $this->element('new_top',array('cache' => true)); ?>
				</cake:nocache>
				</div>
		

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
			
					<?= $this->element('new_footer'); ?>
		
		</div>
<div class="modal fade bd-example-modal-lg" id="modal_ciber_chica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<br>
				<!--<h4 class="modal-title" id="myModalLabel" rel="titulo">Modal title</h4>-->
			</div>
			<div class="modal-body">
				<div class="col-12">
					<img src="https://s3.amazonaws.com/andain-sckechers/img/proceso-de-compra_desktop.jpg" width="100%" alt="">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
<!--FIN MODAL ESCRITORIO-->
<!--MODAL MOBILE-->
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<br>
				<!--<h4 class="modal-title" id="myModalLabel" rel="titulo">Modal title</h4>-->
			</div>
			<div class="modal-body">
				<div class="col-12">
					<img src="https://s3.amazonaws.com/andain-sckechers/img/proceso-de-compra_mobile.jpg" width="100%" alt="">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
    </div>
  </div>
</div>
	</body>

 <script type="text/javascript">
jQuery(document).ready(function(){
    $(".dropdown").hover(
        function() { $('.dropdown-menu', this).stop().fadeIn("fast");
        },
        function() { $('.dropdown-menu', this).stop().fadeOut("fast");
    });
});
 </script>

<!--swipe-->
 <script>
var galleryTop = new Swiper('.gallery-top', {
         slidesPerView: 1,  
      loop: true,
      loopedSlides: 50,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
    var galleryThumbs = new Swiper('.gallery-thumbs', {
      direction: 'vertical',
      slidesPerView: 4,
      slideToClickedSlide: true,
      spaceBetween: 10,
      loopedSlides: 50,
      loop: true,
    });
    galleryTop.controller.control = galleryThumbs;
    galleryThumbs.controller.control = galleryTop;
 </script>
	<script>
	$(document).ready(function(){
		$.ajax(
			{
				url: webroot + 'usuarios/ajax_info',
				success: function(data)
				{
					$('#carro').html(data);
				}
			});

		$.ajax(
			{
				url: webroot + 'usuarios/ajax_usuario',
				success: function(data)
				{
					$('#usuario').html(data);
				}
			});


	})
	</script>
</html>
