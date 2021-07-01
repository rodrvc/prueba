<div class="col02">
	<h1 class="titulo"><? __('Comentarios');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('usuario_id'); ?></th>
			<th><?= $this->Paginator->sort('producto_id'); ?></th>
			<th><?= $this->Paginator->sort('asunto'); ?></th>
			<th><?= $this->Paginator->sort('comentario'); ?></th>
			<th><?= $this->Paginator->sort('estado'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $comentarios as $comentario ) : ?>
		<tr>
			
			<td><?= $this->Html->link($comentario['Usuario']['nombre'] . ' ' . $comentario['Usuario']['apellido_paterno'], array('controller' => 'usuarios', 'action' => 'view', $comentario['Usuario']['id'])); ?></td>
			<td><?= $this->Html->link($comentario['Producto']['nombre'], array('controller' => 'productos', 'action' => 'view', $comentario['Producto']['id'])); ?></td>
			<td><?= $this->Html->link($this->Text->truncate($comentario['Comentario']['asunto'],20), array('action' => 'view', $comentario['Comentario']['id'])); ?></td>
			<td><?= $this->Html->link($this->Text->truncate($comentario['Comentario']['comentario'],30), array('action' => 'view', $comentario['Comentario']['id'])); ?></td>
			<td>
				<? if( $comentario['Comentario']['estado'] == 0 ) : ?>
				<?= $this->Html->link($this->Html->image('iconos/stop_16.png', array('title' => 'En espera')), '#', array('escape' => false, 'class' => 'validar', 'data-id' => $comentario['Comentario']['id'])); ?>
				<? elseif( $comentario['Comentario']['estado'] == 1 ) : ?>
				<?= $this->Html->image('iconos/tick_16.png', array('title' => 'Aprobado')); ?>
				<? endif; ?>
			</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $comentario['Comentario']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $comentario['Comentario']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $comentario['Comentario']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $comentario['Comentario']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$(".validar").click(function(evento)
		{
			evento.preventDefault();
			var	id = $(this).data('id'),
				a = $(this);
			
			$.ajax(
			{
				async	: false,
				type		: 'POST',
				url		: webroot + 'comentarios/validar_comentario',
				data		: {	id : id },
				success: function( respuesta ) 
				{
					if ( respuesta == 'VALIDAR_OK' )
					{
						a.parent().html('<img alt="" title="Aprobado" src="<?= $this->Html->url('/img/iconos/tick_16.png'); ?>">');
					}
					else
					{
						alert('No se pudo validar este registro');
					}
				}
			});
			return false;
			
			
			
			
		});
	});
</script>