<div class="col02">
	<h1 class="titulo"><? __('Estilos');?></h1>
	<div style="float:left; width: 100%; padding: 0 0 10px 0;">
		<?= $this->Form->input('Filtro.categoria_id', array(
			'type' => 'select',
			'div' => false,
			'label' => false,
			'empty' => '- seleccione categoria',
			'options' => $categorias,
			'class' => 'clase-input',
			'style' => 'float: right;'
		)); ?>
	</div>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('alias'); ?></th>
			<th><?= $this->Paginator->sort('activo'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $estilos as $estilo ) : ?>
		<tr>
			<td><?= $estilo['Estilo']['nombre']; ?>&nbsp;</td>
			<td><?= $estilo['Estilo']['alias']; ?>&nbsp;</td>
			<td><?= $estilo['Estilo']['activo']; ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $estilo['Estilo']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $estilo['Estilo']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $estilo['Estilo']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $estilo['Estilo']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>
<script>
$('#FiltroCategoriaId').change(function() {
	var categoria = $(this).val();
	var url = '<?= $this->Html->url(array('controller' => 'estilos', 'action' => 'index')); ?>?categoria='+categoria;
	location.href = url;
});
</script>