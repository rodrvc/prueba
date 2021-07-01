<style>
.ui-effects-transfer {
	background-image: url('<?= $this->Shapeups->imagen($producto['Producto']['foto']['path']); ?>');
	background-position: center; background-size: contain;
	background-repeat: no-repeat;
	z-index:999999;
}
/**/
.tabs-left {
  border-bottom: none;
  padding-top: 2px;
  border-right: 1px solid #ddd;
}
.tabs-left>li {
  float: none;
  margin-bottom: 2px;
  margin-right: -1px;
}
.tabs-left>li.active>a,
.tabs-left>li.active>a:hover,
.tabs-left>li.active>a:focus {
  border-bottom-color: #ddd;
  border-right-color: transparent;
}
.tabs-left>li>a {
  border-radius: 4px 0 0 4px;
  margin-right: 0;
  display:block;
}
</style>
<?
	$precio = $producto['Producto']['precio'];
 if ($producto['Producto']['oferta'] == 1 && $producto['Producto']['precio'] > $producto['Producto']['precio_oferta'])
 		$precio = $producto['Producto']['precio_oferta']; ?>
<script>
$(document).ready(function(){


	dataLayer.push({
		'event': 'impressions',
	'ecommerce': {'currencyCode': 'CLP',
	 'impressions': [{'name': "<?= $producto['Producto']['nombre']; ?>",
	 'id': '<?=$producto["Producto"]["codigo_completo"];?>',
	 'price': '<?=$precio;?>',
	 'brand': 'Skechers',
	 'category': '<?=$producto["Categoria"]["nombre"];?>',
	 'variant': '<?=$producto["Color"]["nombre"];?>',
	 'list': 'Vista Producto',
	 'position': 1}]
	 }
	});


});
</script>

<div class="container-fluid pr-0 pl-0 bg-white">
    <!--*-->
        <div class="my-container">
            <h1><?php echo $producto['Categoria']['nombre'];?></h1>
            <hr class="divider">
        </div>
    <!--*-->
</div>
<!--fin jumbotron-->

<!--breadcrumb--->
<div class="container mt-3 bg-white">
    <div class="row">
       <div class="col-md-12 pt-3 0 border-0 mb-1">
              <ul class="breadcrumb">
                <li><a href="<?php echo $this->Html->url(array('controller' => 'productos', 'action' => 'inicio'));?>"><u>Inicio</u></a></li>
                <?php 
                if(isset($producto['CategoriaPadre']) && !empty($producto['CategoriaPadre'])) :
                ?>	
                 <li><a href="<?php echo $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo',$producto['CategoriaPadre']['slug']));?>"><u><?php echo $producto['CategoriaPadre']['nombre'];?></u></a></li>
                <?php endif; ?>
                 <?php 
                if(isset($producto['Categoria']) && !empty($producto['Categoria'])) :
                ?>	
                 <li><a href="<?php echo $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo',$producto['Categoria']['slug']));?>"><u><?php echo $producto['Categoria']['nombre'];?></u></a></li>
                <?php endif; ?>
                <li><?php echo strtoupper($producto['Producto']['nombre']); ?></li>
              </ul>
        </div>
  </div>
</div>
<!--fin breadcrumb-->



<!-- destacados-->
<div class="container-fluid bg-white mt-3">
  <div class="container">
    <div class="row">
      <?= $this->Form->create('Producto', array('action' => 'carro')); ?>

      <div class="col-md-6 pt-3 pb-2 mb-5">
	<div style="display:block; height: 320px; display: block">

		<div class="swiper-container gallery-thumbs">
		  <div class="swiper-wrapper">
		     <div class="swiper-slide">
		         <img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['ith']); ?>" width="100%">
		     </div>
		     <? foreach ($producto['Galeria'] as $key => $galeria) : ?>
			       <div class="swiper-slide">
			         <img src="<?= $this->Shapeups->imagen('Galeria/'.$galeria['id'].'/ith_'.$galeria['imagen']); ?>" width="100%">
			     </div>
		 	<?php endforeach; ?>
		  </div>
		</div>
		<div class="swiper-container gallery-top">
		  <div class="swiper-wrapper">
		   <div class="swiper-slide"><img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['full']); ?>" width="100%"></div>
		    <? foreach ($producto['Galeria'] as $key => $galeria) : ?>
		     	<div class="swiper-slide"><img src="<?= $this->Shapeups->imagen('Galeria/'.$galeria['id'].'/full_'.$galeria['imagen']); ?>" width="100%"></div>
		     <?php endforeach; ?>
		     
		  </div>
		  <!-- Add Arrows -->
		  <div class="swiper-button-next"></div>
		  <div class="swiper-button-prev"></div>
		</div>
	   </div>
       </div>
        <div class="col-md-1 pt-3 pb-2 mb-5 hidden">
            
        </div>
       <div class="col-md-5 pt-3 pb-2 mb-5">
         <!--  <div class="discountHighLight">
                <span>18%</span>
                <span>OFF</span>
            </div>
            <!--detalle--->
            <div class="detalle">
              <h2 class="mb-3"><?= strtoupper($producto['Producto']['nombre']); ?></h2>
               <small> Marca : <?= $producto['Marca']['nombre']; ?></small><br>
              <small class="mb-2">Codigo: <?= $producto['Producto']['codigo_completo']; ?></small>
              <? if ($producto['Producto']['oferta'] == 1 && $producto['Producto']['precio'] > $producto['Producto']['precio_oferta']) : ?>
              <p class="ant mb-1"><?php echo $this->Shapeups->moneda($producto['Producto']['precio']);?></p>
              <h3 class="mb-3"><?= $this->Shapeups->moneda($producto['Producto']['precio_oferta']); ?></h3>
              <?php else: ?>
 				<h3 class="mb-3"><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></h3>
              <?php endif; ?>

              <p class="frase mb-2">
                Desde
                <span class="special"><?= $this->Shapeups->cuotas($precio); ?>
               </span>
              </p>
              <!--*--->
              <!--
                  <div style="display: block; width: 100%; height:150px;">
                          <div class="section-atributes">
                              <div class="atribute-name"><p>Color</p></div>
                              <div class="selector-atribute-general atribute-Color">
				<div class="self-Blanco"><a href="#">Blanco</a></div>
                                <div class="self-Verde" data-color="Verde" data-property="Color"><a href="#">Verde</a></div>
                                <a href="#"><div class="self-Azul-Marino selected-ficha" data-color="Azul Marino" data-property="Color"><a href="#">Azul Marino</a></div>
                                <a href="#"><div class="self-Lavanda" data-color="Lavanda" data-property="Color"><a href="#">Lavanda</a></div>
                                <a href="#"><div class="self-Rojo" data-color="Rojo" data-property="Color"><a href="#">Rojo</a></div>
                                <a href="#"><div class="self-Naranjo" data-color="Naranjo" data-property="Color"><a href="#">Naranjo</a></div>
                            </div>
                          </div>
                  </div>

              <!--*--->

              <!--comprar--->
              <div class="mt-2" style="display: block; width: 100%;">
              	<?php if($estado_venta):?>
              		<?php if(!empty($producto['Stock'])): ?>
					   <a  class="btn btn_comprar_carro" href="#" rel="agregar-al-carro" data-id="<?php echo $producto['Stock'][0]['id'];?>" data-tipo="normal">
	                    Comprar Ahora<i class="fa fa-shopping-cart amarillo_tb fa-1x ml-2"></i>
	                  </a>
                  	<?php else: ?>
                  		<a  class="btn btn_comprar_carro" href="#breadcrumb"  >Sin Stock</a>
                  	<?php endif; ?>
                  <?php else: ?>
  					<a  class="btn btn_comprar_carro" href="#breadcrumb"  >Pronto</a>

                  <?php endif; ?>
              

                               <div class="mt-2" style="display: block; width: 100%; display: inline; float: left">
               <a href="" data-toggle="modal" data-target="#myModal2" style="float: left;"><img src="http://tecnobuy.cl/img/despacho.PNG"></a>
               <a href="" data-toggle="modal" data-target="#myModal1" style="float: left;" style="float: left;" class="ml-5 f-l-s"><img src="http://tecnobuy.cl/img/retiro.PNG"></a>
             </div>

                                </div>
              <!--fin boton comprar-->

            </div>

            <!--fin detalle--->
       </div>
<?= $this->Form->end(); ?>

     </div>
  </div>
</div>
<!--fin destacados-->


<!--iframe--->
<div class="container mt-3 mb-4">
    <div class="row">
       <div class="col-md-12 pt-3 0 border-0 mb-5">
            	<?php if(isset($producto["Producto"]["ficha"]) && $producto["Producto"]["ficha"] =='flix'): ?>
            <div id="flix-inpage"></div>
<script type="text/javascript" src="https://media.flixfacts.com/js/loader.js" data-flix-distributor="4800" data-flix-language="cl" data-flix-brand="Samsung" data-flix-mpn="<?= strtoupper($producto['Producto']['codigo']); ?>" data-flix-ean="" data-flix-sku="" data-flix-button="flix-minisite" data-flix-inpage="flix-inpage" data-flix-button-image="false" data-flix-price="" data-flix-fallback-language="" async></script>
	<?php endif; ?>

        </div>
  </div>
</div>
<!--fin iframe-->

<!--iframe
<div class="container-fluid bg-white" style="background: #ffffff">
    <div class="container mt-3 mb-4">
        <div class="row">
           <div class="col-md-12 pt-3 0 border-0 mb-5">
            <h2 style="font-size: 36px; text-align: center; color: #000000; font-weight: 600;">Productos similares</h2>
            </div>
      </div>
    </div>
</div>-->

<!--MODAL LARGO-->

  <!-- Modal -->
  <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModal2Label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
       <div class="modal-body">
		<!--*--->
			 <div class="row">
	              <div class="col-md-12 mb-5">
	                <p>Region Metropolitana 48 hrs habiles </p>
	                <p>Resto del pais 48 a 72 hrs habiles</p>
	              </div>         
	            </div>
			 <div class="row">
              <div class="col-md-12">
                <table class="table table-striped border px-3 py-3">
                	<thead>
                    <tr>
                		  <td class="bg_tb">REGIÓN</td>
                		  <td class="bg_tb">COSTO</td>
                		</tr>
                	</thead>
                  <tbody>
                 
                    <tr>
                      <td>Antofagasta</td>
                      <td>$12.500</td>
                    </tr>
                    <tr>
                      <td>Atacama</td>
                      <td>$12.500</td>
                    </tr>
                    <tr>
                      <td>Coquimbo</td>
                      <td>$12.500</td>
                    </tr>
                    <tr>
                      <td>Valparaíso</td>
                      <td>$12.500</td>
                    </tr>
                    <tr>
                      <td>Metropolitana</td>
                      <td>$5.990</td>
                    </tr>
                    <tr>
                      <td>O´Higgins</td>
                      <td>$12.500</td>
                    </tr>
                    <tr>
                      <td>Maule</td>
                      <td>$12.500</td>
                    </tr>
                    <tr>
                      <td>Ñuble</td>
                      <td>$12.500</td>
                    </tr>
                    <tr>
                      <td>Bío Bío</td>
                      <td>$12.500</td>
                    </tr>
                    <tr>
                      <td>La Araucanía</td>
                      <td>$12.500</td>
                    </tr>
                    <tr>
                      <td>Los Ríos</td>
                      <td>$12.500</td>
                    </tr>
                	<tr>
                      <td>Los Lagos</td>
                      <td>$12.500</td>
                    </tr>
                	
                  </tbody>
                </table>
              </div>
            </div>
		<!--*--->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
        </div>
      </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
<!-- FIN MODAL LARGO-->

<!--MODAL LOCALES-->
<!-- Central Modal Large -->
    <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <!--Content-->
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Locales Disponibles</h4>
          </div>
          <!--Body-->
          <div class="modal-body">
            <!--*-->
              <div class="row" id="box-tab">
              <div class="col-12">
                <div class="col-md-3 col-xs-12">
                        <ul class="nav nav-tabs tabs-left">
                              <li class="active text-left">
                              	<a href="#ClientInfo" data-toggle="tab">
                               		<span><b>Mall Barrio Independencia</b> </span>
                                		<br>
                                		<span class="small">Independencia</span>
                                 		<small class="hidden">Stock Disponible</small>
                              	</a>
                              </li>

                              <li class="text-left">
              	                <a href="#tab2" data-toggle="tab">
                               		<span><b>Mall Parque Arauco</b><span>
                                		<br>
                                		<span class="small">Las Condes</span>
                                		<br>
                                		<small class="hidden">Stock Disponible</small>
              	                </a>
                              </li>

                              <li class="text-left">
                              	<a href="#tab3" data-toggle="tab">
                               		<span><b>Mall Alto las Condes</b><span>
                                		<br>
                                		<span class="small">Las Condes</span>
                                		<br>
                                		<small class="hidden">Stock Disponible</small>
                              	</a>
                              </li>

                              <li class="text-left">
                              	<a href="#tab4" data-toggle="tab">
                               		<span><b>Mall Arauco Estacion</b><span>
                                		<br>
                                		<span class="small">Estacion Central</span>
                                		<br>
                                		<small class="hidden">Stock Disponible</small>
                              	</a>
                              </li>
                          </ul>
                      </div>
                      <div class="col-md-9 col-xs-12">
                          <!-- Tab panes -->
                          <div class="tab-content">
                              <div class="tab-pane active" id="ClientInfo">
                           		<!--*-->
                           		<div class="mapa_local">
                           			<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13320.805563101107!2d-70.6063901!3d-33.4179935!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x9a87ef2fefaad0df!2sCostanera%20Center!5e0!3m2!1ses!2scl!4v1623019556982!5m2!1ses!2scl"  style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                           		</div>
                              <div class="mt-5 text-left">
                                <p><b>Dirección:</b> Av.Independencia 565 Local TM-140B   </p>
                                <p><b>Horario:</b> Lunes a viernes de 09:00 a 17:00 hrs </p>
                                <p><b>Teléfono:</b> 72 5254525365</p>
                              </div>
                           		<!--*-->
                           	 </div>

                              <div class="tab-pane" id="tab2">
                              	<!--*-->
                              	<div class="mapa_local">
                           			<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13320.805563101107!2d-70.6063901!3d-33.4179935!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x9a87ef2fefaad0df!2sCostanera%20Center!5e0!3m2!1ses!2scl!4v1623019556982!5m2!1ses!2scl"  style="border:0; width="100%; height: 100px" allowfullscreen="" loading="lazy"></iframe>
                           		</div>
                               <div class="mt-5 text-left">
                                <p><b>Dirección:</b> Av.Independencia 565 Local TM-140B   </p>
                                <p><b>Horario:</b> Lunes a viernes de 09:00 a 17:00 hrs </p>
                                <p><b>Teléfono:</b> 72 5254525365</p>
                              </div>
                           		<!--*-->
                              </div>

                              <div class="tab-pane" id="tab3">
                              <!--*-->
                             		<div class="mapa_local">
                           			<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13320.805563101107!2d-70.6063901!3d-33.4179935!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x9a87ef2fefaad0df!2sCostanera%20Center!5e0!3m2!1ses!2scl!4v1623019556982!5m2!1ses!2scl"  style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                           		</div>
                               <div class="mt-5 text-left">
                                <p><b>Dirección:</b> Av.Independencia 565 Local TM-140B   </p>
                                <p><b>Horario:</b> Lunes a viernes de 09:00 a 17:00 hrs </p>
                                <p><b>Teléfono:</b> 72 5254525365</p>
                              </div>
                           		<!--*-->
                              </div>
                              <div class="tab-pane" id="tab4">
                              <!--*-->
                              	<div class="mapa_local">
                           			<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13320.805563101107!2d-70.6063901!3d-33.4179935!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x9a87ef2fefaad0df!2sCostanera%20Center!5e0!3m2!1ses!2scl!4v1623019556982!5m2!1ses!2scl"  style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                           		</div>
                                <div class="mt-5 text-left">
                                <p><b>Dirección:</b> Av.Independencia 565 Local TM-140B   </p>
                                <p><b>Horario:</b> Lunes a viernes de 09:00 a 17:00 hrs </p>
                                <p><b>Teléfono:</b> 72 5254525365</p>
                              </div>
                           	<!--*-->
                              </div>
                          </div>
                      </div>
                      </div>
              </div>
             <!--*-->
          </div>
          <!--Footer-->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
          </div>
        </div>
        <!--/.Content-->
     
    </div>
    <!-- Central Modal Large -->
<!--FIN MODAL LOCALES-->





<style>
#modalTablaTalla > .modal-dialog {
	width: 60% !important;
}
#modalTablaTallaRopa > .modal-dialog {
	width: 60% !important;
}
#modalTablaTallaMobile > .modal-dialog {
	width: 90% !important;
}
#modalTablaTallaRopaMobile > .modal-dialog {
	width: 90% !important;
}
#modalTablaTalla .modal-dialog > .modal-content {
	background-color: rgba(33,33,33,0.3);
}
#modalTablaTalla .modal-dialog > .modal-content > .modal-body {
	padding: 20px !important;
}
#modalTablaTalla .modal-dialog > .modal-content > .modal-body > .close {
	position: absolute;
	right: 0px;
	top: 0px;
	/*color: #FFF;*/
}
</style>
<script>
$(document).ready(function() {
	if($('.tallas').length ==2)
	{
		$('.tallas')[0].click();
	}

	$(document).on('click', 'a[rel="verTallas"]', function(e) {
		e.preventDefault();
		$('#modalTablaTalla').modal('show');
	});
		$(document).on('click', 'a[rel="verTallasRopa"]', function(e) {
		e.preventDefault();
		$('#modalTablaTallaRopa').modal('show');
	});
	$(document).on('click', 'a[rel="verTallasMobile"]', function(e) {
		e.preventDefault();
		$('#modalTablaTallaMobile').modal('show');
	});
	$(document).on('click', 'a[rel="verTallasRopaMobile"]', function(e) {
		e.preventDefault();
		$('#modalTablaTallaRopaMobile').modal('show');
	});
});
</script>

<script>
function setModalMaxHeight(element) {
  this.$element     = $(element);  
  this.$content     = this.$element.find('.modal-content');
  var borderWidth   = this.$content.outerHeight() - this.$content.innerHeight();
  var dialogMargin  = $(window).width() < 768 ? 20 : 60;
  var contentHeight = $(window).height() - (dialogMargin + borderWidth);
  var headerHeight  = this.$element.find('.modal-header').outerHeight() || 0;
  var footerHeight  = this.$element.find('.modal-footer').outerHeight() || 0;
  var maxHeight     = contentHeight - (headerHeight + footerHeight);

  this.$content.css({
      'overflow': 'hidden'
  });
  
  this.$element
    .find('.modal-body').css({
      'max-height': maxHeight,
      'overflow-y': 'auto'
    });
}

$('.modal').on('show.bs.modal', function() {
  $(this).show(); 
  setModalMaxHeight(this);
});

$(window).resize(function() {
  if ($('.modal.in').length == 1) {
    setModalMaxHeight($('.modal.in'));
  }
});

/* CodeMirror */
$('.code').each(function() {
  var $this = $(this),
      $code = $this.text(),
      $mode = $this.data('language');

  $this.empty();
  $this.addClass('cm-s-bootstrap');
  CodeMirror.runMode($code, $mode, this);
});
</script>


<div id="modalTablaTalla" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times-circle-o"></i></button>
				<?
					echo '<img src="'.$this->Html->url('/img/tabla-skechers.jpg').'" width="100%" />';
				?>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="modalTablaTallaRopa" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times-circle-o"></i></button>
				<?
					echo '<img src="'.$this->Html->url('/img/tabla-skechers-ropa.jpeg').'" width="100%" />';
				?>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modalTablaTallaMobile" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times-circle-o"></i></button>
				<?
					echo '<img src="'.$this->Html->url('/img/tabla-skechers.jpg').'" width="100%" />';
				?>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="modalTablaTallaRopaMobile" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times-circle-o"></i></button>
				<?
					echo '<img src="'.$this->Html->url('/img/tabla-skechers-ropa.jpeg').'" width="100%" />';
				?>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
