<div class="col02">
	<?= $this->element('admin_buscar_clientes'); ?>
	<h1 class="titulo"><? __('Usuarios');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('Apellido','apellido_paterno'); ?></th>
			<th><?= $this->Paginator->sort('email'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $usuarios as $usuario ) : ?>
		<tr>
			<td><?= $usuario['Usuario']['nombre']; ?>&nbsp;</td>
			<td><?= $usuario['Usuario']['apellido_paterno'].''.$usuario['Usuario']['apellido_materno']; ?>&nbsp;</td>
			<td><?= $this->Text->truncate($usuario['Usuario']['email'], 30); ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $usuario['Usuario']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $usuario['Usuario']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $usuario['Usuario']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $usuario['Usuario']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>
	</table>
	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>