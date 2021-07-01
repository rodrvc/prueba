<?= $this->Form->create('Compra'); ?>
<table width="100%" border="1">
	<tr>
		<td width="50%" style="border: 1px solid #0080c0;color:#0080c0;font-weight:bold;text-transform:uppercase;">
			Productos comprados:
		</td>
		<td width="50%" style="border: 1px solid #0080c0;color:#0080c0;font-weight:bold;text-transform:uppercase;">
			Cambio:
		</td>
	</tr>
	<tr>
		<td style="border: 1px solid #0080c0;">
			<?
			echo $this->Form->input('ProductosCompra.id', array(
				'type' => 'select',
				'options' => $productos,
				'rel' => 'selectorProductoDevuelto',
				'empty' => '- seleccione producto devuelto',
				'div' => false,
				'label' => false,
				'class' => 'clase-input'
			));
			?>
		</td>
		<td rel="nuevo-producto" style="border: 1px solid #0080c0;">
			<?
			$options = array(
				'type' => 'select',
				'options' => array(),
				'rel' => 'selectorProductoCambio',
				'empty' => '- Producto cambio ...',
				'div' => false,
				'label' => false,
				'class' => 'clase-input'
			);
			echo $this->Form->input('Producto.cambio',$options); ?>
			<hr />
			<?
			$options = array(
				'type' => 'select',
				'options' => array(),
				'rel' => 'selectorProductoTalla',
				'empty' => '- Talla ...',
				'div' => false,
				'label' => false,
				'class' => 'clase-input'
			);
			echo $this->Form->input('Producto.talla',$options); ?>
		</td>
	</tr>
</table>
<?= $this->Form->end(); ?>