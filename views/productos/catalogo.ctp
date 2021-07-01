<div class="container-fluid pr-0 pl-0">
    <!--*-->
        <div class="my-container">
          <? if (isset($titulo) && $titulo) : ?>
           <h1 ><?= $titulo; ?></h1>
          <? endif; ?>
            <hr class="divider">
        </div>
    <!--*-->
</div>
<!--fin jumbotron-->


<!--breadcrumb--->
<div class="container mt-3">
    <div class="row">
       <div class="col-md-12 pt-3 0 border-0 mb-1">
              <ul class="breadcrumb">
                <li><a href="<?php echo $this->Html->url(array('controller' => 'productos', 'action' => 'inicio'));?>"><u>Inicio</u></a></li>
                  <?php if(isset($categoria['CategoriaPadre']) && !empty($categoria['CategoriaPadre'] && $categoria['CategoriaPadre']['id'])) :
                ?>  
                 <li><a href="<?php echo $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo',$categoria['CategoriaPadre']['slug']));?>"><u><?php echo $categoria['CategoriaPadre']['nombre'];?></u></a></li>
                <?php endif; ?>
                <li><?php echo $categoria['Categoria']['nombre'];?></li>
              </ul>
        </div>
  </div>
</div>
<!--fin breadcrumb-->

<!-- destacados-->
<div class="container-fluid mt-0">
  <div class="container">
    <div class="row">
       <div class="col-md-3 pt-3 pb-2 border-0 mb-4">
	<!--filtro mobile-->
	<div class="hidden-md hidden-lg">
			<div class="panel-group" id="accordion">

  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
          Ordenar por:
        </a>
      </h4>
    </div>

    <div id="collapseOne" class="panel-collapse collapse in">
      <div class="panel-body">
      	
	 <div class="select">
                  <select name="slct" id="xd">
                    <option selected disabled>menor precio</option>
                    <option value="2">mayor precio</option>
                    <option value="3">s</option>
                  </select>
                </div>

	</div>
    </div>
  </div>

<?php foreach($filtros as $filtro) :?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
          Resolución
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body">
   	
		<div class="box">
            <form action="#">
              <?php foreach($buscador['Rango'] as $rango) :?>
              <p class="mb-2">
                <input type="checkbox" id="test1" >
                <label for="test1"> <?php $rango['nombre']; ?> </label>
              </p>
           <?php endforeach; ?>

          </form>
        </div>


		
     </div>
    </div>
  </div>
<?php endforeach; ?>


</div>
	
       </div>
	<!--fin filtro mobile-->
        <div class="sidebar_productos hidden-xs">
            <div>              
              <label class="ml-3 mt-2" for="xd">Ordenar por</label>
                <div class="select">
                  <select name="slct" id="xd">
                    <option selected disabled>menor precio</option>
                    <option value="2">mayor precio</option>
                    <option value="3">s</option>
                  </select>
                </div>
            </div>
        <?php echo $this->Form->create('Producto',array('id' => 'form','url' => '/catalogo/'.$categoria['Categoria']['slug'])); ?>

            <?php foreach($buscador as $filtro) :?>

          <div class="box">
                <?php foreach($filtro['Rango'] as $rango) :?>

              <p class="mb-2">
               <?php echo $this->Form->input( array( 'type' => 'checkbox', 'value' =>  $rango["id"], 'label' => false )); ?>

                <label for="test1"><?php echo $rango['nombre']; ?></label>
              </p>
           <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
<div><input class="btn btn-primary" type="submit" value="Submit"></div>

       <?php echo $this->Form->end(); ?>







      </div>

       </div>

       <div class="col-md-9 pt-3 pb-2 mb-5">
          <?php if (isset($imagen) && $imagen) : ?>

        <div class="hidden-xs hidden-sm ">
            <img src=" <?= $this->Shapeups->imagen('huincha_promo.jpg'); ?>" width="100%">
            <?= $this->Shapeups->imagen('huincha_promo.jpg'); ?>
        </div>
      <?php endif; ?>
          <div class="lista">
            <ul>
               <!--*-->
          <? $i=0; ?>
          <? foreach( $productos as $producto ) : ?>
            <?
              $i++;

            $link = $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug'])); // LINK AL DETALLE DE PRODUCTO
            // CLASE Y TEXTO DEL BOTON
            $class = 'new-arrival'; // CLASE INICIAL
            $texto = 'Comprar'; // TEXTO INICIAL
            /** verifica cuando un producto esta en oferta y calcula el porcentaje de ahorro del producto */
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
                    <a href="<?= $link; ?>"><img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['ith']); ?>"></a>
                  </div>
                  <div class="det mt-2">
                    <p class="text-center"><a href="<?= $link; ?>" class="text-center text-dark"><?= strtoupper($producto['Producto']['nombre']); ?></a></p>
                  </div>
                  <? if ($producto['Producto']['oferta'] && $producto['Producto']['precio'] > $producto['Producto']['precio_oferta']) : ?>

                   <div class="precio">
                    <h6><?= $this->Shapeups->moneda($producto['Producto']['precio_oferta']); ?></h6>
                    <small>Antes</small>
                    <p><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></p>
                  </div> 
                  <?else:?>
             <div class="precio_sin_antes">
                    <h6><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></h6>
                   
                  </div> 

                  <?php endif;?>                
                  <p class="btn_comprar_ahora text-center">
                     <a href="<?= $link; ?>">VER PRODUCTO</a>
                  </p>
                </li>
              <?php endforeach; ?>
                   

            </ul>
          </div>
            <nav aria-label="Page navigation example">
  <!--<ul class="pagination" style="float: right;">
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul> -->
</nav>
       </div>
     </div>
  </div>
</div>