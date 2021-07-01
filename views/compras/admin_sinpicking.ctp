<style>
td.actions a {
	margin-right: 7px;
}
.col02 {
	background-color: #fff;
}
.boton-exportar {
	border-radius: 5px;
	height: 14px;
	padding-top: 5px;
	background-image: url("<?= $this->Html->url('/img/admin/bg-boton.png'); ?>");
	background-position: center center;
	background-repeat: repeat-x;
	display: block;
	float: left;
	font-size: 13px;
	font-weight: normal;
	margin-left: 10px;
	padding: 10px 15px;
	text-decoration: none;
	text-transform: capitalize;
	background-color: #000000;
	color: #FFFFFF;
}
.boton-exportar:hover {
	background-color: #222;
	color: #DDD;
}
.boton-exportar span {
	background-position: left center;
	background-repeat: no-repeat;
	padding-bottom: 5px;
	padding-left: 30px;
	padding-top: 5px;
	background-image: url("<?= $this->Html->url('/img/iconos/excel_16.png'); ?>");
	color: #fff;
}
.btn-seleccionar-todos {
	float: right;
	/*border: 1px solid rgba(255, 255, 255, 0.7);*/
	background-color: rgba(255, 255, 255, 0.5);
	border-radius: 3px;
	padding: 2px;
}
.btn-seleccionar-todos:hover {
	background-color: rgba(255, 255, 255, 0.9);
}
</style>
<div class="col02">
	<?= $this->element('admin_buscar_compra'); ?>
	<h1 class="titulo"><? __('Compras Pagadas');?></h1>
	<?= $this->Form->create('Compra', array('action' => 'exportar_compras_seleccionadas')); ?>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th>Estado Despacho</th>
			<th><?= $this->Paginator->sort('Nº Compra', 'id'); ?></th>
			<th><?= $this->Paginator->sort('Nombre', 'usuario_id'); ?></th>
			<th><?= $this->Paginator->sort('Fecha', 'created'); ?></th>
			<th><?= $this->Paginator->sort('Total', 'total'); ?></th>
			<th style="font-size: 9px;">Picking Number</th>
			<th class="actions" style="width: 170px;">
				Acciones
				<a href="#" title="seleccionar todas" class="btn-seleccionar-todos" rel="seleccionarTodas">
					<img src="<?= $this->Html->url('/img/iconos/tick_16.png'); ?>" alt="selecciona todas"/>
				</a>
			</th>
		</tr>
		<? foreach ( $compras as $compra ) : ?>
		<tr>
			<td class="estado-<?= $compra['Compra']['id']; ?>">
				<?
				$boton = array(
					'link' => '#',
					'imagen' => $this->Html->url('/img/iconos/estados/rojo.png'),
					'title' => 'Tarde'
				);
				if ($compra['Compra']['despachado'])
				{
					if ($compra['Compra']['enviado'])
					{
						$boton = array_merge($boton, array(
							'imagen' => $this->Html->url('/img/iconos/estados/accept.png'),
							'title' => 'Enviado'
						));
					}
					else
					{
						$boton = array_merge($boton, array(
							'imagen' => $this->Html->url('/img/iconos/estados/azul.png'),
							'title' => 'Listo'
						));
					}
				}
				else
				{
					if ($compra['Compra']['created'] < $cinco_dias)
					{
						$boton = array_merge($boton, array(
							'imagen' => $this->Html->url('/img/iconos/estados/rojo.png'),
							'title' => 'Tarde'
						));
					}
					elseif ($compra['Compra']['created'] < $tres_dias)
					{
						$boton = array_merge($boton, array(
							'imagen' => $this->Html->url('/img/iconos/estados/amarillo.png'),
							'title' => '+ 3 dias'
						));
					}
					elseif ($compra['Compra']['created'] >= $tres_dias)
					{
						$boton = array_merge($boton, array(
							'imagen' => $this->Html->url('/img/iconos/estados/verde.png'),
							'title' => 'En plazo'
						));
					}
				}

				if (in_array($authUser['perfil'],array(1,2,3)))
				{
					$boton = array_merge($boton, array(
						'link' => $this->Html->url(array('action' => 'si', $compra['Compra']['id']))
					));
				}
				?>
				<a href="<?= $boton['link']; ?>" title="<?= $boton['title']; ?>"><img src="<?= $boton['imagen']; ?>" /></a>
			</td>
			<td><?= $compra['Compra']['id']; ?>&nbsp;</td>
			<td><?= $compra['Usuario']['nombre'] . ' ' . $compra['Usuario']['apellido_paterno']; ?></td>
			<td><?= date('d-m-Y', strtotime($compra['Compra']['created'])); ?></td>
			<td><?= $compra['Compra']['total']; ?>&nbsp;</td>
			<td><?= (isset($compra['Compra']['picking_number']) && $compra['Compra']['picking_number']) ? '<img src="'.$this->Html->url('/img/iconos/tick_16.png').'" style="opacity: 0.5;"/>':'<img src="'.$this->Html->url('/img/iconos/delete_16.png').'" style="opacity: 0.6;"/>'; ?>&nbsp;</td>
			<td class="actions acciones-compras-<?= $compra['Compra']['id']; ?>">
				<?
				$botones = array(
					'view' => false,
					'email' => false,
					'devolver' => false,
					'devolucion' => false,
					'anular' => false,
					'direccion' => false,
					'editar' => false,
					'check' => false
				);
				if ($authUser['perfil'] == 1)
					$botones['devolucion'] = true;
				elseif ($authUser['perfil'] == 2)
					$botones['devolucion'] = $botones['anular'] = $botones['editar'] = $botones['check'] = true;
				elseif ($authUser['perfil'] == 3)
					$botones['email'] = $botones['devolucion'] = $botones['anular'] = $botones['direccion'] = $botones['editar'] = $botones['check'] = true;

				// ACCIONES 
				if ($botones['view'])
					echo '<a href="'.$this->Html->url(array('action'=>'view',$compra['Compra']['id'])).'" title="Ver"><img src="'.$this->Html->url('/img/iconos/clipboard_16.png').'" /></a>';
				if ($botones['email'])
					echo '<a href="'.$this->Html->url(array('controller'=>'productos','action'=>'emailcompra',$compra['Compra']['id'])).'" title="Enviar Correo"><img src="'.$this->Html->url('/img/iconos/letter_16.png').'" /></a>';
				if ($botones['devolver'])
					echo '<a href="'.$this->Html->url(array('action'=>'devolver',$compra['Compra']['id'])).'" title="En devolucion"><img src="'.$this->Html->url('/img/iconos/left_16.png').'" /></a>';
				if ($botones['devolucion'])
					echo '<a href="'.$this->Html->url(array('action'=>'devolucion',$compra['Compra']['id'])).'" title="Devolucion"><img src="'.$this->Html->url('/img/iconos/reload_16.png').'" /></a>';
				if ($botones['anular'])
					echo '<a href="'.$this->Html->url(array('action'=>'anular',$compra['Compra']['id'])).'" title="Anular Compra"><img src="'.$this->Html->url('/img/iconos/stop_16.png').'" /></a>';
				if ($botones['direccion'])
					echo '<a href="'.$this->Html->url(array('action'=>'cambio_direccion',$compra['Compra']['id'])).'" title="Cambiar dirección"><img src="'.$this->Html->url('/img/iconos/home_16.png').'" /></a>';
				if ($botones['editar'])
					echo '<a href="'.$this->Html->url(array('action'=>'edit',$compra['Compra']['id'])).'" title="Editar"><img src="'.$this->Html->url('/img/iconos/pencil_16.png').'" /></a>';
				if ($botones['check'])
				{
					$options = array(
						'type' => 'checkbox',
						'div'	=> false,
						'label' => false
					);
					echo $this->Form->input('Compra.'.$compra['Compra']['id'].'.compra_id',$options);
				}
				?>
			</td>
		</tr>
		<? endforeach; ?>
	</table>
	<? if (in_array($authUser['perfil'], array(2,3))) : ?>
	<div style="float: left; width: 100%;">
		<a href="#" class="boton-exportar submit"><span>Exportar</span></a>
	</div>
	<? endif; ?>
	<?= $this->Form->end(); ?>
	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>
<script>
$(document).ready(function() {
	$('a[rel="seleccionarTodas"]').live('click', function(e) {
		e.preventDefault();
		var checkbox = $('#CompraExportarComprasSeleccionadasForm input[type="checkbox"]');
		if (! checkbox.length) {
			return false;
		}
		var marcados = $('#CompraExportarComprasSeleccionadasForm input[type="checkbox"]:checked');
		if (marcados.length < checkbox.length) {
			checkbox.attr('checked', true);
		} else {
			checkbox.attr('checked', false);
		}
	});
});
</script>