<div class="col02">
	<style>
	.btn-add {
		float: right;font-size:12px;text-decoration:none !important;
	}
	.btn-add:hover {
		text-decoration:underline !important;
	}
	</style>
	<h1 class="titulo">
		<? __('Caluga Izquierda');?>
		<a href="<?= $this->Html->url(array('action' => 'add','izquierda')); ?>" class="btn-add">&raquo; agregar caluga</a>
	</h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th>Imagen</th>
			<th>Link</th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
		<? foreach ( $banners as $banner ) : ?>
		<tr style="<?= ($banner['Banner']['activo'])?'background-color:#b3ffb3;':''; ?>">
			<td><?= $this->Html->image($banner['Banner']['imagen']['mini']); ?>&nbsp;</td>
			<td><?= $this->Html->link($this->Text->truncate($banner['Banner']['link'],20) , array('action' => 'view', $banner['Banner']['id']), array('title' => $banner['Banner']['link'], 'style' => 'text-decoration: none')); ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/tick_16.png', array('title' => 'Activar')), array('action' => 'activar_izquierda', $banner['Banner']['id']), array('escape' => false), sprintf(__('¿Estas seguro de activar la caluga # %s?', true), $banner['Banner']['id'])); ?>
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $banner['Banner']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $banner['Banner']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $banner['Banner']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $banner['Banner']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>
	</table>
</div>