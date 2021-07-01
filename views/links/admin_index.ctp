<div class="col02">
	<h1 class="titulo"><? __('Links');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th><?= $this->Paginator->sort('ruta'); ?></th>
			<th>url</th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $links as $link ) : ?>
		<?
		$url = array(
			'controller' => $link['Link']['controlador'],
			'action' => $link['Link']['action'],
			'admin' => false
		);
		if (isset($link['Link']['parametro']) && $link['Link']['parametro'])
		{
			array_push($url, $link['Link']['parametro']);
		}
		if (isset($link['LinkParametro']) && $link['LinkParametro'])
		{
			foreach ($link['LinkParametro'] as $parametro)
			{
				$url['?'] = array($parametro['parametro'] => $parametro['valor']);
			}
		}
		?>
		<tr>
			<td><?= $link['Link']['ruta']; ?>&nbsp;</td>
			<td><?= $this->Html->url($url); ?>&nbsp;</td>
			<td class="actions">

				<a href="<?= ($link['Link']['activo']) ? $this->Html->url('/'.$link['Link']['ruta']) : $this->Html->url($url); ?>" target="_blank" title="ver"><img src="<?= $this->Html->url('/img/iconos/clipboard_16.png'); ?>" /></a>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $link['Link']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $link['Link']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $link['Link']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>

	</table>

	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>