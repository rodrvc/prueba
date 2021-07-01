

		<div class="col-md-5 col-xs-12">
			<? //print_r($authUser );
			//die('aca'); ?>


			<p class="nomargin text-center bienvenido">
				<cake:nocache>
				¡Bienvenid@!
			</cake:nocache>
				<? $authUser = $this->Session->read('Auth.Usuario');
					?>
				<? if (isset($authUser)) : ?>
				<a href="<?= $this->Html->url(array('controller' => 'usuarios', 'action' => 'perfil_datos')); ?>" title="Perfil">
					<?= $authUser['nombre'].' '.$authUser['apellido_paterno']; ?>
				</a> -
				<a href="<?= $this->Html->url(array('controller' => 'usuarios', 'action' => 'logout')); ?>" class="btn-logout text-danger" id="logout">
					Salir
				</a>
				<? else : ?>
				<a href="<?= $this->Html->url(array('controller' => 'usuarios', 'action' => 'add')); ?>">
					Inicia Sesión
				</a>
				<a href="<?= $this->Html->url(array('controller' => 'usuarios', 'action' => 'add')); ?>">
					Registrate aquí
				</a>
				<? endif; ?>
			</p>
			<p class="text-danger text-despacho text-center nomargin">DESPACHO GRATIS en todo Chile continental</p>
			<p class="hidden-md hidden-sm hidden-lg"></p>

		</div>
		<div class="col-md-4 col-xs-12" style="padding-top: 8px;">
		<p style="text-align: center"><a href="" style="text-decoration: underline">Revisa tu despacho</a>&nbsp;&nbsp;&nbsp;<a href="" style="text-decoration: underline">Devolución</a></p>
			<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'carro')); ?>" class="carrito btn-block">

				<h5 class="nomargin text-center" rel="carroHeaderText">
					<?
                    $texto_carro = 'Tu carrito de compra está vacío';
                    if(Cache::read('carro_id'.$authUser['id'],'carro')){

                        $contador = 0;
                        $productos_carro = Cache::read('carro_id'.$authUser['id'],'carro');
                        foreach ($productos_carro as $producto_carro)
                        {
                            if ($producto_carro['cantidad'])
                                $contador = $contador + $producto_carro['cantidad'];
                        }
                        if ($contador)
                        {
                            $texto_carro = 'Tu carro tiene '.$contador.' producto';
                            if ($contador > 1)
                                $texto_carro.='s';
                        }
                    }
                    elseif ($this->Session->check('Carro'))
                    {
                        $contador = 0;
                        $productos_carro = $this->Session->read('Carro');
                        foreach ($productos_carro as $producto_carro)
                        {
                            if ($producto_carro['cantidad'])
                                $contador = $contador + $producto_carro['cantidad'];
                        }
                        if ($contador)
                        {
                            $texto_carro = 'Tu carro tiene '.$contador.' producto';
                            if ($contador > 1)
                                $texto_carro.='s';
                        }
                    }

                    ?>
                    <?= $texto_carro; ?> <i class="fa fa-shopping-cart text-primary fa-2x"></i>
				</h5>
			</a>
		</div>
	
