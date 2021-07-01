<div class="col02">
	<?= $this->Form->create('Administrador', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Filtrar Log'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('fecha', array('type' => 'date')); ?></li>
		<li><?= $this->Form->input('tipo', array('type' => 'select',
												 'options' => array('admin' => 'admin',
																	'sitio' => 'sitio'),
												 'default' => 'admin')); ?></li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="buscar">Filtrar</span></a>
	</div>
	<?= $this->Form->end(); ?>
	<h1 class="titulo"><? __('Administracion');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th>Usuario</th>
			<!--<th>Controller</th>
			<th>Action</th>-->
			<th>Detalle</th>
			<!--<th>IP</th>
			<th>Fecha</th>-->
			<th class="actions"><? __('Acciones');?></th>
		</tr>
		<? if ($logs) : ?>
			<? foreach ($logs as $log) : ?>
				<tr>
					<?
					$nombre = $log['Usuario']['nombre'];
					if (isset($log['Usuario']['apellido_paterno']) && $log['Usuario']['apellido_paterno'])
						$nombre.= ' '.$log['Usuario']['apellido_paterno'];
					$tooltip = "
					<table>
						<tr style='padding: 0;'>
							<td style='padding: 0; text-align: left;'><b>usuario </b></td><td style='padding: 0; text-align: left;'>".$nombre."</td>
						</tr>
						<tr style='padding: 0;'>
							<td style='padding: 0; text-align: left;'><b>controller </b></td><td style='padding: 0; text-align: left;'>".$log['Log']['controlador']."</td>
						</tr>
						<tr style='padding: 0;'>
							<td style='padding: 0; text-align: left;'><b>action </b></td><td style='padding: 0; text-align: left;'>".$log['Log']['accion']."</td>
						</tr>
						<tr style='padding: 0;'>
							<td style='padding: 0; text-align: left;'><b>detalle </b></td><td style='padding: 0; text-align: left;'>".$log['Log']['detalle']."</td>
						</tr>
						<tr style='padding: 0;'>
							<td style='padding: 0; text-align: left;'><b>ip </b></td><td style='padding: 0; text-align: left;'>".$log['Log']['ip']."</td>
						</tr>
						<tr style='padding: 0;'>
							<td style='padding: 0; text-align: left;'><b>fecha </b></td><td style='padding: 0; text-align: left;'>".date('d-m-Y H:i',strtotime($log['Log']['created']))."</td>
						</tr>
					</table>
					";
					?>
					<td><?= $log['Usuario']['nombre']; ?></td>
					<td><?= $log['Log']['detalle']; ?></td>
					<td>
						<img src="<?= $this->Html->url('/img/iconos/search_16.png'); ?>" rel="tooltipLog" style="cursor: help;" title="<?= $tooltip; ?>" />
					</td>
				</tr>
			<? endforeach; ?>
		<? else : ?>
		<tr>
			<td colspan="7"></td>
		</tr>
		<? endif; ?>
	</table>
</div>
<script type="application/x-javascript">
$('img[rel="tooltipLog"]').tooltip();
</script>
