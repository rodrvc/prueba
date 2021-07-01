<div class="col02">
	<?= $this->element('admin_descuento_exportar'); ?>
	<h1 class="titulo"><? __('Descuentos');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('fecha_caducidad'); ?></th>
			<? if ( $authUser['perfil'] == 3 ) : ?>
			<th><?= $this->Paginator->sort('codigo'); ?></th>
			<? endif; ?>
			<th><?= $this->Paginator->sort('descuento'); ?></th>
			<th><?= $this->Paginator->sort('Max. Usuario','maximo'); ?></th>
			<th><?= $this->Paginator->sort('Web/Tienda','web_tienda'); ?></th>
			<th><?= $this->Paginator->sort('Cantidad','contador'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $descuentos as $descuento ) : ?>
		<tr>
			<td><?= $descuento['Descuento']['nombre']; ?>&nbsp;</td>
			<td><?= date('d-m-y', strtotime($descuento['Descuento']['fecha_caducidad'])); ?>&nbsp;</td>
			<? if ( $authUser['perfil'] == 3 ) : ?>
			<td><?= $descuento['Descuento']['codigo']; ?>&nbsp;</td>
			<? endif; ?>
			<td>
				<?
					$valor_descuento = '';
					if (isset($descuento['Descuento']['tipo']) && in_array($descuento['Descuento']['tipo'], array('POR', 'DIN')))
					{
						if ($descuento['Descuento']['tipo'] == 'DIN')
							$valor_descuento = $this->Shapeups->moneda($descuento['Descuento']['descuento']);
						else
							$valor_descuento = $descuento['Descuento']['descuento'].'%';
					}
				?>
				<?= $valor_descuento; ?>
				&nbsp;
			</td>
			<td>
				<?= $descuento['Descuento']['maximo']; ?>
			</td>
			<td>
				<?
					$web_tienda = 'web';
					if ($descuento['Descuento']['web_tienda'])
					{
						$web_tienda = 'tienda';
						if ($descuento['Descuento']['web_tienda'] == 2)
						{
							$web_tienda = 'ambos';
						}
					}
				?>
				<?= $web_tienda; ?>
			</td>
			<? if ($descuento['Descuento']['cantidad'] <= $descuento['Descuento']['contador']) : ?>
			<td style="background-color: #004080; font-size: 10px; font-weight: bold; color: #fff;"><?= $descuento['Descuento']['contador'].'/'.$descuento['Descuento']['cantidad']; ?></td>
			<? else : ?>
			<td style="background-color: #64ca3e; font-size: 10px; font-weight: bold; color: #fff;"><?= $descuento['Descuento']['contador'].'/'.$descuento['Descuento']['cantidad']; ?></td>
			<? endif; ?>
			
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $descuento['Descuento']['id']), array('escape' => false)); ?>
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
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $descuento['Descuento']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $descuento['Descuento']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $descuento['Descuento']['id'])); ?>
				<? endif; ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>