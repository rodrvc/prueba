<?= $this->element('menu'); ?>
<?= $this->element('detalle-buscador'); ?>
<div class="wrapper">
	<h1 class="titulo-categoria"><?= $titulo; ?></h1>
	<div class="cont-catalogo">
		<div class="seleccion">
			<div class="ver-resultado">
				Viendo <b><?= $cont; ?></b> Resultados
			</div>
			<div class="menu-outlet">
				<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'kids', 2)); ?>" class="categoria"<?= ($this->params['controller'] == 'productos' && $this->params['action'] == 'kids' && isset($this->params['pass'][0]) && $this->params['pass'][0] == 2) ? ' style="text-decoration:underline;"' : ''; ?>>Girls</a>
				<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'kids', 1)); ?>" class="categoria"<?= ($this->params['controller'] == 'productos' && $this->params['action'] == 'kids' && isset($this->params['pass'][0]) && $this->params['pass'][0] == 1) ? ' style="text-decoration:underline;"' : ''; ?>>Boys</a>
			</div>
		</div>
		<? $x = 1; $limite = 20; ?>
		<ul class="catalogo">
			<? foreach( $productos as $producto ) : ?>
				<?= $this->element('listado_productos/cuadro_producto', array('producto' => $producto, 'index_hide' => $x)); ?>
				<? $x++; ?>
			<? endforeach; ?>
		</ul>
		<!--DIVISOR-->
		<? if ( $otros ) : ?>
			<? if (isset($texto_otros) && $texto_otros) : ?>
			<div class="divisor"><h2><?= $texto_otros; ?></h2></div>
			<? else : ?>
			<div class="divisor"><h2>Otros modelos Skechers Performance</h2></div>
			<? endif; ?>
		<? endif; ?>
		<!---->
		<ul class="catalogo">
		<? if ($otros) : ?>
			<? foreach( $otros as $producto ) : ?>
				<?= $this->element('listado_productos/cuadro_producto', array('producto' => $producto, 'index_hide' => $x)); ?>
				<? $x++; ?>
			<? endforeach; ?>
		<? endif; ?>
		</ul>
	</div>
</div>
<?= $this->element('listado_productos/script_carga_productos'); ?>