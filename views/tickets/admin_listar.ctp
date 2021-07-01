<div class="col02">
	<?= $this->element('admin_tickets_exportar'); ?>
	<h1 class="titulo"><? __('Tickets');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
		
			<th><?= $this->Paginator->sort('codigo'); ?></th>
			<th><?= $this->Paginator->sort('Numero Guia'); ?></th>
			<th><?= $this->Paginator->sort('Fecha Guia'); ?></th>
			<th><?= $this->Paginator->sort('Producto'); ?></th>
			<th><?= $this->Paginator->sort('Color'); ?></th>
			<th><?= $this->Paginator->sort('Talla'); ?></th>
			<th><?= $this->Paginator->sort('Cobrado'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $tickets as $ticket ) : ?>
		<tr>
			<td><?= $ticket['Ticket']['codigo']; ?>&nbsp;</td>
			<td><?= $ticket['Ticket']['numero_guia']; ?>&nbsp;</td>
			<td><?= $ticket['Ticket']['fecha_guia']; ?>&nbsp;</td>
			<td><?= $ticket['Ticket']['codigo_producto']; ?>&nbsp;</td>
			<td><?= $ticket['Ticket']['color']; ?>&nbsp;</td>
			<td><?= $ticket['Ticket']['talla']; ?>&nbsp;</td>
			<td><? echo ($ticket['Ticket']['estado']==0)? 'no': 'si'; ?>&nbsp;</td>
					<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $ticket['Ticket']['id']), array('escape' => false)); ?>
				<?
					$permitir_acciones = false;
					if ($authUser['perfil'] == 3)
					{
						$permitir_acciones = true;
					}
					elseif ( $authUser['perfil'] == 2 && in_array($authUser['id'], array(1,2,3,5,6,37)) )
					{
						$permitir_acciones = true;
					}
				?>
				<? if ( $permitir_acciones ) : ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $ticket['Ticket']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $ticket['Ticket']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $ticket['Ticket']['id'])); ?>
				<? endif; ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>