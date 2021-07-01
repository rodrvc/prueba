<div class="col02">
	<h1 class="titulo">Previsualización de <? __('despacho');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Usuario'); ?>:</span><p><?= $this->Html->link($despacho['Usuario']['email'], array('controller' => 'usuarios', 'action' => 'view', $despacho['Usuario']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Lugar'); ?>:</span><p><?= $despacho['Despacho']['lugar']; ?>&nbsp;</p></li>
			<li><span><? __('Calle'); ?>:</span><p><?= $despacho['Despacho']['calle']; ?>&nbsp;</p></li>
			<li><span><? __('Numero'); ?>:</span><p><?= $despacho['Despacho']['numero']; ?>&nbsp;</p></li>
			<li><span><? __('Departamento'); ?>:</span><p><?= $despacho['Despacho']['departamento']; ?>&nbsp;</p></li>
			<li><span><? __('Region'); ?>:</span><p><?= $this->Html->link($despacho['Region']['nombre'], array('controller' => 'regiones', 'action' => 'view', $despacho['Region']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Ciudad'); ?>:</span><p><?= $despacho['Despacho']['ciudad']; ?>&nbsp;</p></li>
			<li class= "extendido"><span><? __('Rural'); ?>:</span><p><?= ( $despacho['Despacho']['rural'] == 1 )? 'Es rural':'No es rural'; ?>&nbsp;</p></li>
			<li><span><? __('Telefono'); ?>:</span><p><?= $despacho['Despacho']['telefono']; ?>&nbsp;</p></li>
			<li><span><? __('Celular'); ?>:</span><p><?= $despacho['Despacho']['celular']; ?>&nbsp;</p></li>
			<li><span><? __('Postal'); ?>:</span><p><?= $despacho['Despacho']['postal']; ?>&nbsp;</p></li>
			<li><span><? __('Fecha de despacho'); ?>:</span><p><?= $despacho['Despacho']['fecha_despacho']; ?>&nbsp;</p></li>
			<li class= "extendido"><span><? __('Despachado'); ?>:</span><p><?= ( $despacho['Despacho']['despachado'] == 1 )? 'Esta despachado':'No esta despachado'; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $despacho['Despacho']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $despacho['Despacho']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $despacho['Despacho']['id'])); ?>
	</div>
</div>
