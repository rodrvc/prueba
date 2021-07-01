<div class="col02">
	<? if (isset($resultado) && $resultado) : ?>
	<h1 class="titulo">
		<? __('Resultado de carga');?>
	</h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<td>Estado</td>
			<td><?= ( $resultado['estado'] ? 'OK' : 'ERROR'); ?></td>
		</tr>
		<tr>
			<td></td>
			<td><?= $resultado['mensaje']; ?></td>
		</tr>
		<tr>
			<td>Estado del respaldo</td>
			<td><?= ( $resultado['bkp'] ? 'OK' : 'ERROR'); ?></td>
		</tr>
		<tr>
			<td>Archivo de respaldo</td>
			<td><a href="<?= $this->Html->url('/img/cargas_andain/productos/'.basename($resultado['bkp_name'])); ?>" target="_blank"><?= basename($resultado['bkp_name']); ?></a></td>
		</tr>
		<tr>
			<td>Archivo Log</td>
			<td><a href="<?= $this->Html->url('/img/cargas_andain/productos/'.basename($resultado['log_name'])); ?>" target="_blank"><?= basename($resultado['log_name']); ?></a></td>
		</tr>
		<? if (isset($resultado['resumen'])) : ?>
			<? foreach ($resultado['resumen'] as $dato => $valor) : ?>
				<? if (is_array($valor)) : ?>
					<? foreach ($valor as $sub_dato => $sub_valor) : ?>
						<tr>
							<td><?= $dato.' por '.$sub_dato; ?></td>
							<td><?= $sub_valor['count'].' ('.implode(', ', $sub_valor['list']).')'; ?></td>
						</tr>
					<? endforeach; ?>
				<? else : ?>
					<tr>
						<td><?= $dato; ?></td>
						<td><?= $valor; ?></td>
					</tr>
				<? endif; ?>
			<? endforeach; ?>
		<? endif; ?>
	</table>
	<div class="botones">
		<a href="<?= $this->Html->url(array('action' => 'carga_productos')); ?>" class=""><span class="generar">Nueva carga</span></a>
	</div>
	<? else : ?>
	<?= $this->Form->create('Producto', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Carga de productos'); ?></h1>
	<ul class="edit">
		<li>
			<?= $this->Form->input('tipo', array(
				'label' => array(
					'text' => 'tipo de carga',
					'class' => 'texto'
				),
				'type' => 'select',
				'options' => $tipos_carga,
				//'empty' => '- seleccione tipo de carga'
			)); ?>
		</li>
		<li>
			<?= $this->Form->input('coleccion_id', array(
				'label' => array(
					'text' => 'Coleccion',
					'class' => 'texto'
				),
				'type' => 'select',
				'options' => $colecciones,
			)); ?>
		</li>
		<li>
			<?= $this->Form->input('archivo', array(
				'type' => 'file'
			)); ?>
		</li>
	</ul>

	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Cargar</span></a>
	</div>
	<?= $this->Form->end(); ?>
	<? endif; ?>
</div>
