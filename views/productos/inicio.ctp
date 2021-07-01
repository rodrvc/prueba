<div id="carousel-example" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
  	<? foreach($banners as $index => $banner): ?>
			<? 
				$class = "";
				if($banner === reset($banners))
					$class = 'active';
			?>
			<li data-target="#carousel-example" data-slide-to="<?= $index; ?>" class="<?= $class; ?>"></li>
			<? endforeach; ?>
  </ol>

  <div class="carousel-inner">
<? foreach($banners as $index => $banner): ?>
	<? 
		$class = "";
		if($banner === reset($banners))
			$class = 'active';
	?>
      <div class="item <?php echo $class;?>" >
     	<? if($banner['Banner']['link']): ?>
			<a href="<?= $banner['Banner']['link']; ?>">
				<img src="<?= $this->Shapeups->imagen($banner['Banner']['imagen']['grande']); ?>" width="100%">
			</a>
		<? else: ?>
			<img src="<?= $this->Shapeups->imagen($banner['Banner']['imagen']['grande']); ?>" width="100%">
		<? endif; ?>

      </div>
	<? endforeach; ?>
  </div>

  <a class="left carousel-control" href="#carousel-example" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel-example" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
</div>
<div class="container-fluid mt-3">
  <div class="container">
    <div class="row">
      <div class="col-md-4 items">
        <h4>Elige tu mobile</h4>
        <a href="#"><img src="img/1.png"></a>
      </div>
        <div class="col-md-4 items">
        <h4>La mejor tecnología</h4>
        <a href="#"><img src="img/2.png"></a>
      </div>
        <div class="col-md-4 items">
        <h4>Los mejores Smartwatch</h4>
        <a href="#"><img src="img/3.png"></a>
      </div>
     
      
    </div>
  </div>
</div>

<!-- fin 3 banners-->

<!-- destacados-->
	<?php if(isset($productos_destacados) && !empty($productos_destacados)) :?>

<div class="container-fluid home_destacados" style="background: black; margin-top: 20px;">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="tit">destacados</h2>
      </div>
    </div> 
  </div>
</div>
<div class="container-fluid home_destacados mt-3">
  <div class="container">
    <div class="row tabla">
            <ul>
               <?php $i=0; ?>
              <?php foreach ($productos_destacados as $producto) :?>
				<?php $i++;
					$link = $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug'])); // LINK AL DETALLE DE PRODUCTO
					$ahorro = 0;
					if ( isset($producto['Producto']['oferta']) && $producto['Producto']['oferta'] == 1 )
					{
						if ($producto['Producto']['precio'] > $producto['Producto']['precio_oferta'])
						{
							$ahorro = ($producto['Producto']['precio_oferta']*100)/$producto['Producto']['precio'];
							$ahorro = 100-$ahorro;
							if ($ahorro)
							{
								$ahorro = (round(($ahorro/10),0)*10).'%';
							}
						}
					}
				?>
                <li>
                  <div class="img">
                    <a href=<?= $link; ?>><img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['ith']); ?>"></a>
                  </div>
                  <div class="det mt-2">
                    <p><a href="<?= $link; ?>" class="text-center text-dark"><?= strtoupper($producto['Producto']['nombre']); ?></a></p>
                  </div>
                   <div class="precio">
                    <h6><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></h6>
                  </div>                  
                  <p class="btn_comprar_ahora">
                    <a href="<?= $link; ?>">
                    Comprar Ahora<i class="fa fa-shopping-cart amarillo_tb fa-1x ml-2"></i>
                  </a>
                  </p>
                </li>
             <?php endforeach; ?>
            
                <!--*-->
            </ul>
          </div>
  </div>
</div>
<?php endif; ?>
<!--fin destacados-->

<!-- destacados-->
<div class="container-fluid" style="margin-top: 60px;">
  <div class="container mt-0 pt-0 mb-0 pb-0">
    <div class="row">
      <div class="col-md-12">
        <div class="title_lines">PROMOCIÓN</div>
      </div>
    </div> 
  </div>
</div>
<div class="container home_destacados mt-3 mb-5">
  
    <div class="row">
        <!--#include virtual="productos_home.shtml"-->
    </div>
  
</div>
