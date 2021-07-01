<style>
.btn-dropdown {
	text-decoration: none !important;
	border: 1px solid #1a75ff;
	padding: 0 3px 0 3px;
	text-align: center;
	border-radius: 9px;
	font-size: 10px;
	background-color: #4691ff;
	font-family: monospace;
}
.publico {
	position: absolute;
	width: 10px;
	height: 10px;
	border-radius: 5px;
	background-color: #008000;
}
.privado {
	position: absolute;
	width: 10px;
	height: 10px;
	border-radius: 5px;
	background-color: #b90000;
}
.nombre {
	font-weight: bold;
}
.dropdown .nombre {
	font-weight: normal;
}
.dropdown {
	display: none;
}
.padding-20 {
	padding-left: 20px;
}
.text-left {
	text-align: left;
}
table.tabla tr.padre {
	background-color: #eee;
}
table.tabla tr.padre td {
	border-bottom: 1px solid #ddd;
}
table.tabla tr:hover {
	background-color: #ddd;
}
</style>
<div class="col02">
	<h1 class="titulo">Limpiar Categorias</h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th></th>
			<th style="width: 240px;">Nombre</th>
			<th style="font-size: 10px;">Cantidad</th>
			<th style="width: 180px;">Estado</th>
			<th style="font-size: 10px;">Publico/Privado</th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $categorias as $categoria ) : ?>
		<?
		// asigna color de fondo segun cantidad
		$publico = false;
		if ($categoria['Categoria']['publico'])
			$publico = true;
		$estado = $bgcolor = '';
		if (isset($categoria[0]['count']) && $categoria[0]['count'])
		{
			$bgcolor = 'none repeat scroll 0 0 rgba(0, 255, 0, 0.5)';
			$estado = '<b>categoria - activo</b>';
		}
		else
		{
			$bgcolor = 'none repeat scroll 0 0 rgba(255, 0, 0, 0.5)';
			$estado = '<b>categoria - vacio</b>';
		}
		?>
		<tr class="padre">
			<td><?= (isset($categoria['Categoria']['hijos']) && $categoria['Categoria']['hijos'])? '<a href="#" class="btn-dropdown" data-id="'.$categoria['Categoria']['id'].'">+</a>' : '' ; ?></td>
			<td class="nombre text-left"><?= $categoria['Categoria']['nombre']; ?>&nbsp;</td>
			<td><?= (isset($categoria[0]['count']) && $categoria[0]['count']) ? $categoria[0]['count'] : 0; ?></td>
			<td style="background: <?= $bgcolor; ?>; text-align: left;"><?= $estado; ?></td>
			<td style="text-align: center; ?>;"><span class="<?= ($publico) ? 'publico' : 'privado'; ?>">&nbsp;</span></td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $categoria['Categoria']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $categoria['Categoria']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $categoria['Categoria']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $categoria['Categoria']['id'])); ?>
			</td>
		</tr>
			<? if (isset($categoria['Categoria']['hijos']) && $categoria['Categoria']['hijos']) : ?>
				<? foreach ($categoria['Categoria']['hijos'] as $subcategoria) : ?>
				<?
				// asigna color de fondo segun cantidad
				$publico = false;
				if ($subcategoria['Categoria']['publico'])
					$publico = true;
				$estado = $bgcolor = '';
				if (isset($subcategoria[0]['count']) && $subcategoria[0]['count'])
				{
					$bgcolor = 'none repeat scroll 0 0 rgba(0, 255, 0, 0.5)';
					$estado = '<b>subcategoria - activo</b>';
				}
				else
				{
					$bgcolor = 'none repeat scroll 0 0 rgba(255, 0, 0, 0.5)';
					$estado = '<b>subcategoria - vacio</b>';
				}
				?>
				<tr class="dropdown padre-<?= $categoria['Categoria']['id']; ?>">
					<td><?= (isset($subcategoria['Categoria']['hijos']) && $subcategoria['Categoria']['hijos'])? '<a href="#" class="btn-dropdown">+</a>' : '' ; ?></td>
					<td class="nombre text-left padding-20"><?= $subcategoria['Categoria']['nombre']; ?>&nbsp;</td>
					<td><?= (isset($subcategoria[0]['count']) && $subcategoria[0]['count']) ? $subcategoria[0]['count'] : 0; ?></td>
					<td style="background: <?= $bgcolor; ?>; text-align: left;"><?= $estado; ?></td>
					<td style="text-align: center; ?>;"><span class="<?= ($publico) ? 'publico' : 'privado'; ?>">&nbsp;</span></td>
					<td class="actions">
						<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $subcategoria['Categoria']['id']), array('escape' => false)); ?>
						<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $subcategoria['Categoria']['id']), array('escape' => false)); ?>
						<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $subcategoria['Categoria']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $subcategoria['Categoria']['id'])); ?>
					</td>
				</tr>
				<? endforeach; ?>
			<? endif; ?>
		<? endforeach; ?>
	</table>
	
	
	
	<? if (isset($categorias_error) && $categorias_error) : ?>
	<h2 class="subtitulo">Categorias desasociadas</h2>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th></th>
			<th style="width: 240px;">Nombre</th>
			<th style="font-size: 10px;">Cantidad</th>
			<th style="width: 180px;">Estado</th>
			<th style="font-size: 10px;">Publico/Privado</th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
	
		<? foreach ( $categorias_error as $categoria ) : ?>
		<?
		// asigna color de fondo segun cantidad
		$publico = false;
		if ($categoria['Categoria']['publico'])
			$publico = true;
		$estado = $bgcolor = '';
		if (isset($categoria[0]['count']) && $categoria[0]['count'])
		{
			$bgcolor = 'none repeat scroll 0 0 rgba(0, 255, 0, 0.5)';
			$estado = '<b>categoria - activo</b>';
		}
		else
		{
			$bgcolor = 'none repeat scroll 0 0 rgba(255, 0, 0, 0.5)';
			$estado = '<b>categoria - vacio</b>';
		}
		?>
		<tr>
			<td><?= (isset($categoria['Categoria']['hijos']) && $categoria['Categoria']['hijos'])? '<a href="#" class="btn-dropdown" data-id="'.$categoria['Categoria']['id'].'">+</a>' : '' ; ?></td>
			<td class="nombre text-left"><?= $categoria['Categoria']['nombre']; ?>&nbsp;</td>
			<td><?= (isset($categoria[0]['count']) && $categoria[0]['count']) ? $categoria[0]['count'] : 0; ?></td>
			<td style="background: <?= $bgcolor; ?>; text-align: left;"><?= $estado; ?></td>
			<td style="text-align: center; ?>;"><span class="<?= ($publico) ? 'publico' : 'privado'; ?>">&nbsp;</span></td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $categoria['Categoria']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $categoria['Categoria']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $categoria['Categoria']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $categoria['Categoria']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>
	</table>
	<? endif; ?>
	
	
	
</div>
<script>
$('.btn-dropdown').click(function(e) {
	e.preventDefault();
	var	boton = $(this),
		padre = $(this).data('id'),
		dropdown = $('.dropdown.padre-'+padre);
	if (dropdown.is(':visible')) {
		boton.html('+');
		dropdown.hide();
	} else {
		boton.html('-');
		dropdown.show();
	}
});
</script>