<? $this->element('menu'); ?>
<? $this->element('detalle-buscador'); ?>
<? $this->element('inicio_facebook'); ?>

<div class="wrapper">
	<div class="cont-catalogo">
			<div class="seleccion">
				<span style="float: left; text-transform: capitalize; font-size: 16px; margin-top: 8px; margin-left: 35px; margin-right: 20px;"><b style="color: #EEEEEE;"><?= $categoria['Categoria']['nombre']; ?></b></span> 
				<label>
					<?= $this->Form->create('Producto', array('inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => false))); ?>
					<?= $this->Form->select('genero', array(1 => 'Hombre', 2 => 'Mujer'), null, array('escape' => false, 'class' => 'listas', 'empty' => '-- Seleccione Sexo')); ?>
					<?= $this->Form->end(); ?>
				</label>
				<div class="ver-resultado">Viendo <b><?= $cont; ?></b> Resultados</div>
			</div>
			<ul class="catalogo">
				<? foreach( $productos as $producto ) : ?>
				<li>
					<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug'])); ?>">
						<?= $this->Html->image($producto['Producto']['foto_categoria']['path'], array('width' => 176, 'height' => 148)); ?>
						<? if( isset($producto['Producto']['escolar']) && $producto['Producto']['escolar'] == 1 ) : ?>
						<span class="escolar"></span>
						<? elseif( isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 ) : ?>
						<!-- destacado new -->
						<span class="new-arrival"></span>
						<? elseif ( isset($producto['Producto']['oferta']) && $producto['Producto']['oferta'] == 1 ) : ?>
						<!-- destacado sale -->
						<span class="sale"></span>
						<? endif; ?>
					</a>
					<div class="boton home">
						<? if ( isset($producto['disponible']) && $producto['disponible']) : ?>
							<? if ( isset($producto['Producto']['escolar']) && $producto['Producto']['escolar'] == 1 ) : ?>
							<?= $this->Html->link('<span>Comprar</span>', array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']), array('escape' => false, 'class' => 'naranja')); ?>
							<? elseif ( isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 ) : ?>
							<?= $this->Html->link('<span>Comprar</span>', array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']), array('escape' => false, 'class' => 'new-arrival')); ?>
							<? elseif ( isset($producto['Producto']['oferta']) && $producto['Producto']['oferta'] == 1 ) : ?>
							<?= $this->Html->link('<span>Comprar</span>', array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']), array('escape' => false, 'class' => 'rojo')); ?>
							<? else : ?>
							<?= $this->Html->link('<span>Comprar</span>', array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']), array('escape' => false, 'class' => 'naranja')); ?>
							<? endif; ?>
						<? else : ?>
							<? if ( isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 ) : ?>
							<?= $this->Html->link('Coming Soon', array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']), array('escape' => false, 'class' => 'coming-soon')); ?>
							<? else : ?>
							<?= $this->Html->link('Sold Out', array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']), array('escape' => false, 'class' => 'sold-out')); ?>
							<? endif; ?>
						<? endif; ?>
					</div>
					<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug'])); ?>" class="nombre"><?= $producto['Producto']['nombre']; ?></a>
					<span class="codigo">Código #<?= "{$producto['Producto']['codigo']}{$producto['Color']['codigo']}"; ?></span>
					<div class="precios">
						<? if ( $producto['Producto']['oferta'] ) : ?>
						<s><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></s>
						<b class="oferta"><?= $this->Shapeups->moneda($producto['Producto']['precio_oferta']); ?></b>
						<? else : ?>
						<b><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></b>
						<? endif; ?>
					</div>
					<span class="disponibilidad">Disponible en <?= $producto['Producto']['colores']; ?> color<?= ($producto['Producto']['colores'] > 1 ? 'es' : ''); ?></span>
				</li>
				<? endforeach; ?>
			</ul>
			<!--DIVISOR-->
			<? if ( $otros ) : ?>
			<div class="divisor"><h2>Otros modelos de Skechers Fitness</h2></div>
			<? endif; ?>
			<!---->
			<ul class="catalogo">
				<? foreach( $otros as $producto ) : ?>
				<li>
					<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug'])); ?>">
						<?= $this->Html->image($producto['Producto']['foto_categoria']['path'], array('width' => 176, 'height' => 148)); ?>
						<? if( isset($producto['Producto']['escolar']) && $producto['Producto']['escolar'] == 1 ) : ?>
						<span class="escolar"></span>
						<? elseif( isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 ) : ?>
						<!-- destacado new -->
						<span class="new-arrival"></span>
						<? elseif ( isset($producto['Producto']['oferta']) && $producto['Producto']['oferta'] == 1 ) : ?>
						<!-- destacado sale -->
						<span class="sale"></span>
						<? endif; ?>
					</a>
					<div class="boton home">
						<? if ( isset($producto['disponible']) && $producto['disponible']) : ?>
							<? if ( isset($producto['Producto']['escolar']) && $producto['Producto']['escolar'] == 1 ) : ?>
							<?= $this->Html->link('<span>Comprar</span>', array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']), array('escape' => false, 'class' => 'naranja')); ?>
							<? elseif ( isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 ) : ?>
							<?= $this->Html->link('<span>Comprar</span>', array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']), array('escape' => false, 'class' => 'new-arrival')); ?>
							<? elseif ( isset($producto['Producto']['oferta']) && $producto['Producto']['oferta'] == 1 ) : ?>
							<?= $this->Html->link('<span>Comprar</span>', array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']), array('escape' => false, 'class' => 'rojo')); ?>
							<? else : ?>
							<?= $this->Html->link('<span>Comprar</span>', array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']), array('escape' => false, 'class' => 'naranja')); ?>
							<? endif; ?>
						<? else : ?>
							<? if ( isset($producto['Producto']['new']) && $producto['Producto']['new'] == 1 ) : ?>
							<?= $this->Html->link('Coming Soon', array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']), array('escape' => false, 'class' => 'coming-soon')); ?>
							<? else : ?>
							<?= $this->Html->link('Sold Out', array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug']), array('escape' => false, 'class' => 'sold-out')); ?>
							<? endif; ?>
						<? endif; ?>
					</div>
					<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'view', $producto['Producto']['slug'])); ?>" class="nombre"><?= $producto['Producto']['nombre']; ?></a>
					<span class="codigo">Código #<?= "{$producto['Producto']['codigo']}{$producto['Color']['codigo']}"; ?></span>
					<div class="precios">
						<? if ( $producto['Producto']['oferta'] ) : ?>
						<s><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></s>
						<b class="oferta"><?= $this->Shapeups->moneda($producto['Producto']['precio_oferta']); ?></b>
						<? else : ?>
						<b><?= $this->Shapeups->moneda($producto['Producto']['precio']); ?></b>
						<? endif; ?>
					</div>
					<span class="disponibilidad">Disponible en <?= $producto['Producto']['colores']; ?> color<?= ($producto['Producto']['colores'] > 1 ? 'es' : ''); ?></span>
				</li>
				<? endforeach; ?>
			</ul>
		</div>	
</div>