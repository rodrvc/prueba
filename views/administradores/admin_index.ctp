<style>
.col02 > h1.titulo > a.btn-admin {
	float: right;
	font-size: small;
	text-decoration: none;
	border: 1px solid #a7a7a7;
	padding: 3px;
	border-radius: 4px;
}
.col02 > h1.titulo > a.btn-admin:hover {
	/*text-decoration: underline;*/
	background-color: #eee;
}
</style>
<div class="col02">
	<?= $this->element('admin_buscar_administradores'); ?>
	<h1 class="titulo">
		<? __('Administradores');?>
	</h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('usuario'); ?></th>

			<th><?= $this->Paginator->sort('perfil'); ?></th>
			<th><?= $this->Paginator->sort('Fecha creación','created'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $administradores as $administrador ) : ?>
		<tr>
			<td><?= $administrador['Administrador']['nombre']; ?>&nbsp;</td>
			<td><?= $administrador['Administrador']['usuario']; ?>&nbsp;</td>
			<td><?= ( (isset($perfiles[$administrador['Administrador']['perfil']])) ? $perfiles[$administrador['Administrador']['perfil']] : $administrador['Administrador']['perfil'] ); ?>&nbsp;</td>
			<td><?= date('d-m-Y H:i', strtotime($administrador['Administrador']['created'])); ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $administrador['Administrador']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $administrador['Administrador']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $administrador['Administrador']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $administrador['Administrador']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>