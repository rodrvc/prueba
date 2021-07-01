<div class="col02">
	<?= $this->Form->create('Administrador', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Agregar Etiquetas'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('etiqueta'); ?></li>
		<li><?= $this->Form->input('listado', array('type' => 'textarea',
													'label' => array('class' => 'texto',
																	 'text' => 'Codigos de productos'),
													'placeholder' => 'Escriba codigos completos de los productos a los cuales desea agregar la etiqueta, separados por coma (,). EJ: 1034ASC,1212CVB,2452GGWP...')); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="editar">Agregar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
<style type="text/css" media="all">
.filtro li {
	width: 100% !important;
}
.filtro li label {
	float: left;
	font-weight: bold;
	text-transform: uppercase;
	margin-right: 15px;
	width: 200px !important;
}
#ProductoListado {
	width: 100% !important;
}
.filtro button {
	float: right;
}
</style>
<div class="col02">
	<h1 class="titulo">Buscar productos</h1>
	<?= $this->Form->create('Producto', array('action' => 'generarListado','type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
	<div class="previsualizar filtro">
		<ul>
			<li>
				<?= $this->Form->input('nombre'); ?>
			</li>
			<li>
				<?= $this->Form->input('grupo', array('type' => 'text')); ?>
			</li>
			<li>
				<?= $this->Form->input('categoria_id',array('empty' => '- categoria')); ?>
			</li>
			<li>
				<?= $this->Form->input('coleccion_id',array('empty' => '- coleccion')); ?>
			</li>
			<li>
				<?= $this->Form->input('showroom'); ?>
			</li>
			<li>
				<?
				$options = array(
					'type' => 'select',
					'options' => array(
						1 => 'si',
						0 => 'no'
					),
					'empty' => 'todos'
				);
				echo $this->Form->input('activo',$options); ?>
			</li>
			<li>
				<button type="button" class="btn" rel="generarListado">Buscar</button>
			</li>
		</ul>
	</div>
	<?= $this->Form->end(); ?>
	<div class="target">
		<?
		$options = array(
			'type' => 'textarea',
			'readonly' => 'readonly',
			'div' => false,
			'label' => false,
			'placeholder' => 'listado de productos...'
		);
		echo $this->Form->input('Producto.listado',$options); ?>
	</div>
</div>
<script type="application/x-javascript">
$('button[rel="generarListado"]').click(function() {
	var target = $('.target #ProductoListado'),
		formulario = $(this).parents('form');
	if (! target.length)
		return false;
	if (! formulario.length)
		return false;
	$.ajax({
		async	: true,
		dataType: "json",
		type	: 'POST',
		url		: webroot + 'administradores/ajax_filtrotag',
		data	: formulario.serialize(),
		success	: function( respuesta ) {
			target.val(respuesta);
		}
	});
});
</script>
