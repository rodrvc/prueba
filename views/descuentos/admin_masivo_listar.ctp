<div class="col02">
	<?= $this->element('admin_descuento_exportar'); ?>
	<h1 class="titulo"><? __('Cargas Masivas');?></h1>
	<div class="contenido">
		<div id="cargaDescuentos">
			<table cellpadding="0" cellspacing="0" class="tabla">
				<tr>
					<th>Subido por</th>
					<th>Archivo</th>
					<th>Tamaño</th>
					<th>Fecha</th>
					<th>Procesado ?</th>
					<th class="actions"><? __('Acciones');?></th>
				</tr>
				<? foreach ( $archivos as $archivo ) : ?>
				<tr>
					<td><?= $this->Html->link($archivo['Administrador']['nombre'], array('controller' => 'administradores', 'action' => 'view', $archivo['Archivo']['administrador_id'])); ?>&nbsp;</td>
					<td><?= $this->Html->link(basename($archivo['Archivo']['nombre']['path']), "/img/{$archivo['Archivo']['nombre']['path']}"); ?>&nbsp;</td>
					<td><?= $archivo['Archivo']['size']; ?> MB&nbsp;</td>
					<td><?= $archivo['Archivo']['created']; ?>&nbsp;</td>
					<td><?= (isset($archivo['Archivo']['flag']) && $archivo['Archivo']['flag'] == 1) ? 'Si' : 'No'; ?></td>
					<td class="actions">
						<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('controller' => 'archivos', 'action' => 'delete', $archivo['Archivo']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el archivo %s?', true), basename($archivo['Archivo']['nombre']['path']))); ?>
					</td>
				</tr>
				<? endforeach; ?>
			</table>
		</div>
	</div>
</div>