<style type="text/css" media="all">
	td.actions a {
		margin-right: 7px;
	}
</style>
<div class="col02">
	<?= $this->element('admin_buscar_compra'); ?>
	<h1 class="titulo"><? __('Compras');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('estado'); ?></th>
			<th><?= $this->Paginator->sort('Nº Compra', 'id'); ?></th>
			<th><?= $this->Paginator->sort('Nombre', 'usuario_id'); ?></th>
			<th><?= $this->Paginator->sort('Fecha', 'created'); ?></th>
			<th><?= $this->Paginator->sort('Total', 'total'); ?></th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
		<? foreach ( $compras as $compra ) : ?>
		<tr>
			<td class="estado-<?= $compra['Compra']['id']; ?>">
				<?
				$boton = array(
					'link' => '#',
					'imagen' => $this->Html->url('/img/iconos/estados/no_pagado.png'),
					'title' => '<br />No Pagado'
				);
				if (in_array($authUser['perfil'],array(0,2,3)))
					$boton['link'] = $this->Html->url(array('controller'=>'compras','action'=>'view',$compra['Compra']['id']));
	
				if ($compra['Compra']['estado'] == 1)
				{
					$boton['imagen'] = $this->Html->url('/img/iconos/estados/pagado.png');
					$boton['title'] = '<br />Pagado';
					if (in_array($authUser['perfil'],array(0,1,2,3)))
						$boton['link'] = $this->Html->url(array('controller' => 'compras', 'action' => 'si', $compra['Compra']['id']));
				}
				elseif ($compra['Compra']['estado'] == 2)
				{
					$boton['imagen'] = $this->Html->url('/img/iconos/estados/anulado.png');
					$boton['title'] = '<br />Anulado';
					if (in_array($authUser['perfil'],array(2,3)))
						$boton['link'] = $this->Html->url(array('controller'=>'compras','action'=>'view',$compra['Compra']['id']));
				}
				elseif ($compra['Compra']['estado'] == 3)
				{
					$boton['imagen'] = $this->Html->url('/img/iconos/estados/devuelto2.png');
					$boton['title'] = '<br />En Devolucion';
					if (in_array($authUser['perfil'],array(1,2,3)))
						$boton['link'] = $this->Html->url(array('controller'=>'compras','action'=>'view',$compra['Compra']['id']));
				}
				elseif ($compra['Compra']['estado'] == 4)
				{
					$boton['imagen'] = $this->Html->url('/img/iconos/estados/devuelto.png');
					$boton['title'] = '<br />Devuelto';
					if (in_array($authUser['perfil'],array(1,2,3)))
						$boton['link'] = $this->Html->url(array('controller'=>'compras','action'=>'view',$compra['Compra']['id']));
				}
				elseif ($compra['Compra']['estado'] == 5)
				{
					$boton['imagen'] = $this->Html->url('/img/iconos/estados/pendiente.png');
					$boton['title'] = '<br />Pendiente';
					$boton['link'] = '#';
				}
				elseif ($compra['Compra']['estado'] == 6)
				{
					$boton['imagen'] = $this->Html->url('/img/iconos/estados/devuelto2.png');
					$boton['title'] = '<br />Devolución en curso';
					$boton['link'] = '#';
				}
                elseif ($compra['Compra']['estado'] == 11)
                {
                    $boton['imagen'] = $this->Html->url('/img/iconos/estados/anulado.png');
                    $boton['title'] = '<br />anulado por stock';
                    $boton['link'] = '#';
                }
                elseif ($compra['Compra']['estado'] == 12)
                {
                    $boton['imagen'] = $this->Html->url('/img/iconos/estados/pendiente.png');
                    $boton['title'] = '<br />devolucion activa';
                    $boton['link'] = '#';
                }
				?>
				<a href="<?= $boton['link']; ?>" title="<?= strip_tags($boton['title']); ?>" style="text-decoration: none; font-size: 10px;"><img src="<?= $boton['imagen']; ?>" /><?= $boton['title']; ?></a>
			<!-- FIN BOTON ESTADO -->
			</td>
			<td><?= $compra['Compra']['id']; ?>&nbsp;</td>
			<td><?= $compra['Usuario']['nombre'] . ' ' . $compra['Usuario']['apellido_paterno']; ?></td>
			<td><?= date('d-m-Y', strtotime($compra['Compra']['created'])); ?></td>
			<td><?= $compra['Compra']['total']; ?>&nbsp;</td>
			<td class="actions acciones-compras-<?= $compra['Compra']['id']; ?>">
				<?
				$botones = array(
					'view' => false,
					'email' => false,
					'devolver' => false,
					'devolucion' => false,
					'cambiar' => false,
					'anular' => false,
					'direccion' => false,
					'editar' => false,
					'reenviar' => false,
				);
				if ($compra['Compra']['estado']==0)
				{
					if (in_array($authUser['perfil'],array(2,3)))
						$botones['editar'] = true;
				}
				elseif ($compra['Compra']['estado']==1)
				{
					if (in_array($authUser['perfil'],array(1,2,3)))
					{
						$botones['devolucion'] = true;
						if (in_array($authUser['perfil'],array(2,3)))
						{
							$botones['anular'] = $botones['editar'] = true;
							if ($authUser['perfil']==3)
								$botones['email'] = true;
						}
					}
				}
				elseif ($compra['Compra']['estado']==2)
				{
					if (in_array($authUser['perfil'],array(2,3)))
						$botones['editar'] = true;
				}
				elseif ($compra['Compra']['estado']==3)
				{
					if (in_array($authUser['perfil'],array(1,2,3)))
					{
						$botones['cambiar'] = true;
						if (in_array($authUser['perfil'],array(2,3)))
						{
							$botones['anular'] = $botones['editar'] = true;
							if ($authUser['perfil'] == 3)
								$botones['devolucion'] = true;
						}
					}
				}
				elseif ($compra['Compra']['estado']==4)
				{
					if (in_array($authUser['perfil'],array(1,2,3)))
					{
						$botones['reenviar'] = true;
						if (in_array($authUser['perfil'],array(2,3)))
							$botones['anular'] = $botones['editar'] = true;
					}
				}
				elseif ($compra['Compra']['estado']==5)
				{
					if (in_array($authUser['perfil'],array(2,3)))
					{
						$botones['editar'] = true;
						if ($this->params['action'] == 'admin_pendiente')
						{
							$botones['view'] = true;
						}
					}
						
				}
				elseif ($compra['Compra']['estado']==6)
				{
					if (in_array($authUser['perfil'],array(2,3)))
						$botones['editar'] = true;
				}
                elseif ($compra['Compra']['estado']==11)
                {
                    if (in_array($authUser['perfil'],array(2,3)))
                        $botones['editar'] = true;
                }

				// ACCIONES 
				if ($botones['view'])
					echo '<a href="'.$this->Html->url(array('action'=>'view',$compra['Compra']['id'])).'" title="Ver"><img src="'.$this->Html->url('/img/iconos/clipboard_16.png').'" /></a>';
				if ($botones['devolucion'])
					echo '<a href="'.$this->Html->url(array('action'=>'devolucion',$compra['Compra']['id'])).'" title="Devolucion"><img src="'.$this->Html->url('/img/iconos/reload_16.png').'" /></a>';
				if ($botones['reenviar'])
					echo '<a href="'.$this->Html->url(array('action'=>'reenviar',$compra['Compra']['id'])).'"title="Reenviar Productos"><img src="'.$this->Html->url('/img/iconos/gear2_16.png').'" /></a>';
				if ($botones['cambiar'])
					echo '<a href="'.$this->Html->url(array('action'=>'cambiar',$compra['Compra']['id'])).'"title="Cambiar Productos"><img src="'.$this->Html->url('/img/iconos/gear_16.png').'" /></a>';
				if ($botones['email'])
					echo '<a href="'.$this->Html->url(array('controller'=>'productos','action'=>'emailcompra',$compra['Compra']['id'])).'" title="Enviar Correo"><img src="'.$this->Html->url('/img/iconos/letter_16.png').'" /></a>';
				if ($botones['devolver'])
					echo '<a href="'.$this->Html->url(array('action'=>'devolver',$compra['Compra']['id'])).'" title="En devolucion"><img src="'.$this->Html->url('/img/iconos/left_16.png').'" /></a>';
				if ($botones['anular'])
					echo '<a href="'.$this->Html->url(array('action'=>'anular',$compra['Compra']['id'])).'" title="Anular Compra"><img src="'.$this->Html->url('/img/iconos/stop_16.png').'" /></a>';
				if ($botones['direccion'])
					echo '<a href="'.$this->Html->url(array('action'=>'cambio_direccion',$compra['Compra']['id'])).'" title="Cambiar dirección"><img src="'.$this->Html->url('/img/iconos/home_16.png').'" /></a>';
				if ($botones['editar'])
					echo '<a href="'.$this->Html->url(array('action'=>'edit',$compra['Compra']['id'])).'" title="Editar"><img src="'.$this->Html->url('/img/iconos/pencil_16.png').'" /></a>';
				?>
			</td>
		</tr>
		<? endforeach; ?>
	</table>
	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>