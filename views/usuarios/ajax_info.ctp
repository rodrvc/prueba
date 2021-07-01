<!--

	
			<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'carro')); ?>" class="carrito btn-block">

				<h5 class="nomargin text-center" rel="carroHeaderText">
					<?
                    $texto_carro = 'Tu carro está vacío';
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
		
        -->

      
   <div >
                   <p class="tucarro"><a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'carro')); ?>" class="registrarse"><u><i class="fa fa-shopping-cart amarillo_tb fa-1x mr-2 " style="font-size: 15px;"></i><?php echo $texto_carro;?></u></a></p>
                     </div>
