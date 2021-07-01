<div class="col02">
	<div class="botones buscar" style="float: right; margin-bottom: 5px; margin-top: 0; width: 430px;">
		<?= $this->Form->create('Comuna'); ?>
			<?= $this->Form->input('buscar', array('id' => 'input-buscarProducto',
												   'class' => 'clase-input descripcion-tool',
												   'div' => false,
												   'label' => false,
												   'type' => 'text',
												   'title' => 'Para realizar busqueda de comunas, usted puede ingresar:<br />- ID de la comuna<br />- Nombre de la comuna<br />- Codigo de la region<br />- Nombre de la region')); ?>
		<a href="#" id="btn-buscarProducto" class="submit" style="height: 14px; padding-top: 5px;"><span class="buscar">Buscar</span></a>
		<?= $this->Form->end(); ?>
	</div>
	<h1 class="titulo"><? __('Comunas');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('region_id'); ?></th>
			<th><?= $this->Paginator->sort('limite'); ?></th>
			<th><?= $this->Paginator->sort('despacho1'); ?></th>
			<th><?= $this->Paginator->sort('despacho2'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $comunas as $comuna ) : ?>
		<tr>
			<td><?= $comuna['Comuna']['nombre']; ?>&nbsp;</td>
			<td><?= $this->Html->link($comuna['Region']['codigo'], array('controller' => 'regiones', 'action' => 'view', $comuna['Region']['id'])); ?></td>
			<td><?= $comuna['Comuna']['limite']; ?>&nbsp;</td>
			<td><?= $comuna['Comuna']['despacho1']; ?>&nbsp;</td>
			<td><?= $comuna['Comuna']['despacho2']; ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $comuna['Comuna']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $comuna['Comuna']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $comuna['Comuna']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $comuna['Comuna']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>