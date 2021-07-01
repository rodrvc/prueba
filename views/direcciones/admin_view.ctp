<div class="col02">
	<h1 class="titulo">Previsualización de <? __('direccion');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Usuario'); ?>:</span><p><?= $this->Html->link($direccion['Usuario']['email'], array('controller' => 'usuarios', 'action' => 'view', $direccion['Usuario']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Guardado como...'); ?>:</span><p><?= $direccion['Direccion']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Calle'); ?>:</span><p><?= $direccion['Direccion']['calle']; ?>&nbsp;</p></li>
			<li><span><? __('Numero'); ?>:</span><p><?= $direccion['Direccion']['numero']; ?>&nbsp;</p></li>
			<li><span><? __('Depto'); ?>:</span><p><?= $direccion['Direccion']['depto']; ?>&nbsp;</p></li>
			<li><span><? __('Otras Indicaciones'); ?>:</span><p><?= $direccion['Direccion']['otras_indicaciones']; ?>&nbsp;</p></li>
			<li><span><? __('Comuna'); ?>:</span><p><?= $this->Html->link($direccion['Comuna']['nombre'], array('controller' => 'comunas', 'action' => 'view', $direccion['Comuna']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Region'); ?>:</span><p><?= $this->Html->link($direccion['Region']['nombre'], array('controller' => 'regiones', 'action' => 'view', $direccion['Region']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Codigo Postal'); ?>:</span><p><?= $direccion['Direccion']['codigo_postal']; ?>&nbsp;</p></li>
			<li><span><? __('Telefono'); ?>:</span><p><?= $direccion['Direccion']['telefono']; ?>&nbsp;</p></li>
			<li><span><? __('Celular'); ?>:</span><p><?= $direccion['Direccion']['celular']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $direccion['Direccion']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $direccion['Direccion']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $direccion['Direccion']['id'])); ?>
	</div>
</div>
