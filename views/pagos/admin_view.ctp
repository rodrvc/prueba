<div class="col02">
	<h1 class="titulo">Previsualización de <? __('pago');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Forma'); ?>:</span><p><?= $pago['Pago']['forma']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $pago['Pago']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $pago['Pago']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $pago['Pago']['id'])); ?>
	</div>
</div>
