<style>
.col02 {
	background-color: #fff;
}
h3.titulo {
	color: #0080c0;
	border-bottom: 1px solid #0080c0;
	padding-bottom: 5px;
	margin-bottom: 15px;
	font-size: large;
	font-weight: bold;
}
ul.listado {
	width: 100%;
	display: table;
	margin-bottom: 30px;
	background-color: #fff;
}
ul.listado > li {
	float: left;
	width: 20%;
	border: 1px solid #0080c0;
	background-color: #d5f1ff;
	padding: 3px;
	margin: 5px;
	text-align: center;
	border-radius: 4px;
}
ul.listado > li.invalido {
	border: 1px solid #800000;
	background-color: #ffd9d9;
}
ul.listado > li.por_cargar {
	background-color: #d5f1ff;
}
ul.listado > li span.linea {
	color: #777;
}
.boton {
	width: 100%;
	margin-bottom: 30px;
	text-align: right;
	padding-right: 30px;
}
.boton a {
	width: auto;
	padding: 10px 15px;
	border: 1px solid #008000;
	text-decoration: none;
	background-color: #00a600;
	color: #fff;
	font-weight: bold;
}
.boton a:hover {
	background-color: #00d700;
}
.boton a.blanco {
	color: #0080c0;
	border: 1px solid #e1e1e1;
	border-bottom: hidden;
	background-color: #fff;
	opacity: 0.5;
}
.boton a.blanco.activo {
	opacity: 1;
}
.boton a.blanco:hover {
	opacity: 1;
	text-decoration: underline;
}
.tab-cargados {
	display: none;
}
.text-left {
	text-align: left;
}
</style>
<div class="col02">
	<div class="boton text-left">
		<a href="#tab-cargados" class="blanco" rel="tab">cargados</a>
		<a href="#tab-no-cargados" class="blanco activo" rel="tab">no cargados</a>
	</div>
	<div class="tab tab-no-cargados" rel="#tab-no-cargados">
		<h1 class="titulo">Productos no cargados</h1>
		<? if (isset($resultado['no_cargados']) && $resultado['no_cargados']) : ?>
		<h3 class="titulo">Productos no cargados en catalogo: <?= count($resultado['no_cargados']); ?></h3>
		<?= $this->Form->create('Producto'); ?>
		<ul class="listado">
			<? foreach ($resultado['no_cargados'] as $linea => $codigo) : ?>
			<li class="<?= (isset($this->data['Producto'][$linea])) ? 'por_cargar':'invalido'; ?>">
				<span class="linea"><?= $linea; ?></span> &raquo; <?= $codigo; ?>
				<? if (isset($this->data['Producto'][$linea])) : ?>
					<?= $this->Form->hidden('Producto.'.$linea.'.nombre'); ?>
					<?= $this->Form->hidden('Producto.'.$linea.'.categoria_id'); ?>
					<?= $this->Form->hidden('Producto.'.$linea.'.coleccion_id'); ?>
					<?= $this->Form->hidden('Producto.'.$linea.'.color_id'); ?>
					<?= $this->Form->hidden('Producto.'.$linea.'.codigo'); ?>
					<?= $this->Form->hidden('Producto.'.$linea.'.codigo_completo'); ?>
					<?= $this->Form->hidden('Producto.'.$linea.'.precio'); ?>
					<?= $this->Form->hidden('Producto.'.$linea.'.oferta'); ?>
					<?= $this->Form->hidden('Producto.'.$linea.'.new'); ?>
					<?= $this->Form->hidden('Producto.'.$linea.'.excluir_descuento'); ?>
					<? if (isset($this->data['Producto'][$linea]['division'])) : ?>
					<?= $this->Form->hidden('Producto.'.$linea.'.division'); ?>
					<? endif; ?>
				<? endif; ?>
			</li>
			<? endforeach; ?>
		</ul>
		<div class="boton">
			<a href="#" rel="cargar-productos">cargar</a>
		</div>
		<?= $this->Form->end(); ?>
		<? endif; ?>
		<? if (isset($resultado['colores_faltantes']) && $resultado['colores_faltantes']) : ?>
		<h3 class="titulo">Colores faltantes: <?= count($resultado['colores_faltantes']); ?></h3>
		<ul class="listado">
			<? foreach ($resultado['colores_faltantes'] as $codigo) : ?>
			<li><span class="linea"><?= $codigo; ?></li>
			<? endforeach; ?>
		</ul>
		<? endif; ?>
	</div>
	<div class="tab tab-cargados" rel="#tab-cargados">
		<h1 class="titulo">Productos cargados</h1>
		
		<? if (isset($resultado['cargados']) && $resultado['cargados']) : ?>
		<h3 class="titulo">Productos cargados en catalogo: <?= count($resultado['cargados']); ?></h3>
		<?= $this->Form->create('Producto'); ?>
		<ul class="listado">
			<? foreach ($resultado['cargados'] as $id => $codigo) : ?>
			<li class="<?= (isset($this->data['Actualizar'][$id])) ? 'por_cargar':'invalido'; ?>">
				<span class="linea"><?= $id; ?></span> &raquo; <?= $codigo; ?>
				<? if (isset($this->data['Actualizar'][$id])) : ?>
					<?= $this->Form->hidden('Actualizar.'.$id.'.id'); ?>
					<?= $this->Form->hidden('Actualizar.'.$id.'.nombre'); ?>
					<?= $this->Form->hidden('Actualizar.'.$id.'.categoria_id'); ?>
					<?= $this->Form->hidden('Actualizar.'.$id.'.coleccion_id'); ?>
					<?= $this->Form->hidden('Actualizar.'.$id.'.precio'); ?>
					<?= $this->Form->hidden('Actualizar.'.$id.'.oferta'); ?>
					<? if (isset($this->data['Actualizar'][$id]['division'])) : ?>
					<?= $this->Form->hidden('Actualizar.'.$id.'.division'); ?>
					<? endif; ?>
				<? endif; ?>
			</li>
			<? endforeach; ?>
		</ul>
		<div class="boton">
			<a href="#" rel="actualizar-productos">Actualizar</a>
		</div>
		<?= $this->Form->end(); ?>
		<? endif; ?>
		
		
		<? if (isset($resultado['sin_foto']) && $resultado['sin_foto']) : ?>
		<h3 class="titulo">Productos sin foto en catalogo: <?= count($resultado['sin_foto']); ?></h3>
		<ul class="listado">
			<? foreach ($resultado['sin_foto'] as $id => $codigo) : ?>
			<li><a href="<?= $this->Html->url(array('action' => 'edit',$id)); ?>"><span class="linea"><?= $id; ?></span> &raquo; <?= $codigo; ?></a></li>
			<? endforeach; ?>
		</ul>
		<? endif; ?>
		<? if (isset($resultado['sin_descripcion']) && $resultado['sin_descripcion']) : ?>
		<h3 class="titulo">Productos sin descripcion en catalogo: <?= count($resultado['sin_descripcion']); ?></h3>
		<ul class="listado">
			<? foreach ($resultado['sin_descripcion'] as $id => $codigo) : ?>
			<li><span class="linea"><?= $id; ?></span> &raquo; <?= $codigo; ?></li>
			<? endforeach; ?>
		</ul>
		<? endif; ?>
	</div>
</div>
<script>
$('a[rel="tab"]').click(function(e) {
	e.preventDefault();
	var btn = $(this),
		tab = $(this).attr('href');
	$('a[rel="tab"]').removeClass('activo');
	btn.addClass('activo');
	$('.col02 > .tab').hide();
	$('.tab[rel="'+tab+'"]').fadeIn(300);
});
$('a[rel="actualizar-productos"]').click(function(e) {
	e.preventDefault();
	if (! confirm('¿Desea actualizar los productos del catalogo?'))
		return false;
	$(this).parents('form').submit();
});
$('a[rel="cargar-productos"]').click(function(e) {
	e.preventDefault();
	if (! confirm('¿Desea cargar los productos que no se encuentran en el catalogo?'))
		return false;
	$(this).parents('form').submit();
});
</script>
