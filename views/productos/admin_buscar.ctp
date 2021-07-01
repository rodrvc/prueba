<div class="col02">
	<?= $this->element('admin_buscar'); ?>
	<h1 class="titulo"><? __('Productos');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('nombre'); ?></th>
			<th><?= $this->Paginator->sort('categoria_id'); ?></th>
			<th><?= $this->Paginator->sort('foto'); ?></th>
			<th><?= $this->Paginator->sort('codigo'); ?></th>
			<th><?= $this->Paginator->sort('precio'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $productos as $producto ) : ?>
		<tr>
			<td><?= $producto['Producto']['nombre']; ?>&nbsp;</td>
			<td><?= $producto['Categoria']['nombre']; ?></td>
			<td>
				<?
					$imagen = 'sin_imagen.jpg';
					if ( isset($producto['Producto']['foto']['mini']) && $producto['Producto']['foto']['mini'] )
					{
						$imagen = $producto['Producto']['foto']['mini'];
					}
					$precio = $producto['Producto']['precio'];
					$style = "";
					if ($producto['Producto']['oferta'])
					{
						$precio = $producto['Producto']['precio_oferta'];
						$style = "color: Red;";
					}
				?>
				<?= $this->Html->image($imagen); ?>&nbsp;
			</td>
			<td><?= $producto['Producto']['codigo'] . '' . $producto['Color']['codigo']; ?></td>
			<td style="<?= $style; ?>"><?= $precio; ?>&nbsp;</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $producto['Producto']['id']), array('escape' => false)); ?>
				<? if (in_array($authUser['id'], array(2,3,5,37))) : ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $producto['Producto']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $producto['Producto']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $producto['Producto']['id'])); ?>
				<? endif; ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>