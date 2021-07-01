<div class="col02">
	<?= $this->element('admin_categorias_tools'); ?>
	<h1 class="titulo"><? __('Categorias');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('Categoría padre', 'parent_id'); ?></th>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('slug'); ?></th>
			<th><?= $this->Paginator->sort('sexo_id'); ?></th>
			<th>Tallas</th>
			<th><?= $this->Paginator->sort('publico'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $categorias as $categoria ) : ?>
		<?
		// asigna color de fondo segun estado publico
		$bgcolor = '#ff8080;';
		if (isset($categoria['Categoria']['publico']) && $categoria['Categoria']['publico'])
		{
			$bgcolor = '#80ff80;';
		}
		?>
		<tr style="background-color: <?= $bgcolor; ?>;">
			<td><?= $this->Html->link($categoria['ParentCategoria']['nombre'], array('controller' => 'categorias', 'action' => 'view', $categoria['ParentCategoria']['id'])); ?></td>
			<td><?= $categoria['Categoria']['nombre']; ?>&nbsp;</td>
			<td><?= $categoria['Categoria']['slug']; ?>&nbsp;</td>
			<td><?= $this->Html->link($categoria['Sexo']['nombre'], array('controller' => 'sexos', 'action' => 'view', $categoria['Sexo']['id'])); ?></td>
			<td><?= $categoria['Categoria']['desde']; ?> - <?= $categoria['Categoria']['hasta']; ?>&nbsp;</td>
			<td><?= (isset($categoria['Categoria']['publico']) && $categoria['Categoria']['publico']) ? 'si' : 'no'; ?></td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $categoria['Categoria']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $categoria['Categoria']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $categoria['Categoria']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $categoria['Categoria']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>