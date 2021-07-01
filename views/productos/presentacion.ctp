<?= $this->element('menu'); ?>
<?= $this->element('detalle-buscador'); ?>
<div class="wrapper">
	<?
	$titulo = '';
	if (isset($this->params['pass'][0]) && $this->params['pass'][0])
	{
		if ($this->params['pass'][0] == 'hombre')
			$titulo = "Men's Shoes";
		elseif ($this->params['pass'][0] == 'mujer')
			$titulo = "Women's Shoes";
		elseif ($this->params['pass'][0] == 'nino')
			$titulo = "Boy's Shoes";
		elseif ($this->params['pass'][0] == 'nina')
			$titulo = "Girl's Shoes";
	}
	?>
	<h1 class="titulo-categoria"><?= $titulo; ?></h1>
	<div class="cont-catalogo">
		<div class="seleccion">
			<div class="ver-resultado">
				Viendo <b><?= $cont; ?></b> Resultados
			</div>
			<? if (isset($ordenar) && $ordenar) : ?>
			<?= $this->element('listado_productos/catalogo_ordenar', array('ordenar' => $ordenar)); ?>
			<? endif; ?>
		</div>
		<? $x = 1; $limite = 20; ?>
		<ul class="catalogo">
			<? foreach( $productos as $producto ) : ?>
				<?= $this->element('listado_productos/cuadro_producto', array('producto' => $producto, 'index_hide' => $x)); ?>
				<? $x++; ?>
			<? endforeach; ?>
		</ul>
	</div>
</div>
<?= $this->element('listado_productos/script_carga_productos'); ?>