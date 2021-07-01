<div class="col02">
	<h1 class="titulo"><? __('Postulantes');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('rut'); ?></th>
			<th><?= $this->Paginator->sort('email'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
		<? foreach ( $postulantes as $postulante ) : ?>
		<tr>
			<td><?= $postulante['TrabajePostulante']['nombre'].' '.$postulante['TrabajePostulante']['apellido_paterno']; ?>&nbsp;</td>
			<td><?= $postulante['TrabajePostulante']['rut']; ?>&nbsp;</td>
			<td><?= $postulante['TrabajePostulante']['email']; ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $postulante['TrabajePostulante']['id']), array('escape' => false)); ?>
			</td>
		</tr>
		<? endforeach; ?>
	</table>
	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>