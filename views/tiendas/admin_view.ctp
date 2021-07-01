<div class="col02">
	<h1 class="titulo">Previsualización de <? __('tienda');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $tienda['Tienda']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Direccion'); ?>:</span><p><?= $tienda['Tienda']['direccion']; ?>&nbsp;</p></li>
			<li><span><? __('Telefono'); ?>:</span><p><?= $tienda['Tienda']['telefono']; ?>&nbsp;</p></li>
			<li><span><? __('Latitud'); ?>:</span><p><?= $tienda['Tienda']['latitud']; ?>&nbsp;</p></li>
			<li><span><? __('Longitud'); ?>:</span><p><?= $tienda['Tienda']['longitud']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $tienda['Tienda']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $tienda['Tienda']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $tienda['Tienda']['id'])); ?>
	</div>
</div>
