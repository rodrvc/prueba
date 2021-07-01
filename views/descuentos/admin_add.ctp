<div class="col02">
	<style type="text/css" media="all">
		.ayudita {
			position: absolute;
			right: 30px;
			width: 25px;
			height: 25px;
		}
	</style>
	<script type="application/x-javascript">
	$(document).ready(function()
	{
		$('.ayudita').tooltip();

        $('label[for="DescuentoSuper"]').hide();
        $('label[for="DescuentoCiclico"]').hide();

		// SELECT CANALES
		$('#DescuentoTodos').change(function(evento) {
			var contenedor = $(this).parents('.edit');
			if ( $(this).is(':checked') ) {
				contenedor.find('.check-categoria').attr('checked',true);
			}
			else {
				contenedor.find('.check-categoria').attr('checked',false);
			}
		});
		$('#Showroom0Valor').change(function(evento) {
			var contenedor = $(this).parents('.edit');
			console.log($(this));
			if ($(this).attr('value') != 'todos') {
				return false;
			}
			if ( $(this).is(':checked') ) {
				contenedor.find('.check-showroom').attr('checked',true);
			} else {
				contenedor.find('.check-showroom').attr('checked',false);
			}

		});
	});
	</script>
	<?= $this->Form->create('Descuento', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Agregar Descuento'); ?></h1>

	<ul class="edit">
		<li>
			<?= $this->Form->input('nombre'); ?>
			<a class="ayudita" href="#" title="Nombre del descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('cantidad'); ?>
			<a class="ayudita" href="#" title="Cantidad de veces que se puede utilizar un código de descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('fecha_caducidad'); ?>
			<a class="ayudita" href="#" title="Fecha hasta la cual es válido el descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('codigo'); ?>
			<a class="ayudita" href="#" title="Código del descuento (ej: COD-001-SK001)."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
			<li>
			<?= $this->Form->input('correlativo'); ?>
			<a class="ayudita" href="#" title="Código interno del descuento"><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('responsable'); ?>
			<a class="ayudita" href="#" title="Nombre de la persona que entrego el descuento"><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li class="radio-descuento">
			<span class="texto">Tipo de Descuento</span><?= $this->Form->radio('tipo', $opciones_radio, $attr_radio); ?>
			<a class="ayudita" href="#" title="Diferencia si el descuento es segun un porcentaje o si se aplicara un monto establecido sobre el valor del producto."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('descuento'); ?>
			<a class="ayudita" href="#" title="Valor por el cual se aplicara el descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('web_tienda', array('type' => 'select',
													   'options' => array(0 => 'web',
																		  1 => 'tienda',
																		  2 => 'ambos'),
													   'label' => array('text' => 'Web / Tienda',
																		'class' => 'texto'))); ?>
			<a class="ayudita" href="#" title="Establece si el código de descuento puede ser utilizado en tiendas, en el sitio web o en ambos."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('maximo', array('type' => 'select',
												   'options' => array(1 => 1,2,3,4,5,6,7,8,9,10),
												   'label' => array('text' => 'Maximo por Usuario',
																	'class' => 'texto'))); ?>
			<a class="ayudita" href="#" title="Cantidad de veces que un usuario puede utilizar el mismo código de descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('minimo'); ?>
			<a class="ayudita" href="#" title="Valor sobre el cual se aplica el descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
		<li>
			<?= $this->Form->input('comentario'); ?>
			<a class="ayudita" href="#" title="Agrega comentarios al descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
        <li>
            <label class='texto'>INCLUIR EXCLUIDOS</label>
            <?= $this->Form->input('super', array('type'=>'checkbox')); ?>
            <a class="ayudita" href="#" title="Establece si el descuento puede incluir excluidos."><?= $this->Html->image('iconos/help_25.png'); ?></a>
        </li>
        <li>
            <label class='texto'>DESCUENTO CICLICO</label>
            <?= $this->Form->input('ciclico', array('type'=>'checkbox')); ?>
            <a class="ayudita" href="#" title="Establece si el descuento se renueva mes a mes."><?= $this->Html->image('iconos/help_25.png'); ?></a>
        </li>
		<li>
			<?= $this->Form->input('escolar'); ?>
			<a class="ayudita" href="#" title="Establece si el descuento puede ser aplicado sobre productos escolares."><?= $this->Html->image('iconos/help_25.png'); ?></a>
		</li>
	</ul>
	<!--categorias-->
	<h2 class="subtitulo">
		<? __('Categorias'); ?>
		<a class="ayudita" href="#" title="Categorias sobre las cuales sera valido el descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
	</h2>
	<ul class="edit">
		<li>
			<label class="texto"><b>Todas las Categorias</b></label>
			<?= $this->Form->checkbox('Descuento.todos'); ?>
		</li>
		<? foreach( $categorias as $index => $categoria ) : ?>
		<li><label class="texto"><?= $categoria; ?></label><?= $this->Form->checkbox('Categoria.' .$index. '.categoria_id', array('class' => 'check-categoria')); ?></li>
		<? endforeach; ?>
	</ul>
	<!--showroom-->
	<h2 class="subtitulo">
		<? __('SHOWROOM'); ?>
		<a class="ayudita" href="#" title="Showroom del producto al cual se le aplica el descuento."><?= $this->Html->image('iconos/help_25.png'); ?></a>
	</h2>
	<ul class="edit">
		<li>
			<label class="texto"><b>Todas los Showroom</b></label>
			<?= $this->Form->checkbox('Showroom.0.valor',array('value' => 'todos')); ?>
		</li>
		<?
		$index=1;
		foreach( $showrooms as $showroom )
		{
			echo '<li><label class="texto">'.$showroom.'</label>'.$this->Form->checkbox('Showroom.' .$index. '.valor', array('class' => 'check-showroom','value' => $showroom)).'</li>';
			$index++;
		}
		?>
	</ul>

	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
