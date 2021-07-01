<h1 class="titulo"><? __('Datos Envio');?></h1>
<div class="previsualizar">
	<ul>
		<li><span><? __('Picking Number'); ?>:</span><p><?= (isset($this->data['Compra']['picking_number']) && $this->data['Compra']['picking_number']) ? $this->data['Compra']['picking_number'] : ''; ?></p></li>
		<li><span><? __('Boleta'); ?>:</span><p><?= $compra['Compra']['boleta']; ?>&nbsp;</p></li>
		<li><span><?= __('Codigo Despacho'); ?>:</span><p><a target="_blank" href="https://www.chilexpress.cl/Views/ChilexpressCL/Resultado.aspx?uq=<?php echo $compra['Compra']['cod_despacho']; ?>" ><?php echo $compra['Compra']['cod_despacho']; ?></a></p></li>
		<li><span><?= __('Rural'); ?>:</span><p><?= $compra['Compra']['rural']; ?></p></li>
		<li><span><?= __('Direccion'); ?>:</span><p><?= $compra['Compra']['direccion_rural']; ?></p></li>
		<li><span><?= __('Fecha de Envio'); ?>:</span><p><?= $compra['Compra']['fecha_enviado']; ?></p></li>
	</ul>
</div>
<div class="botones">
	<?= $this->Html->link('<span class="imprimir">Imprimir</span>', array('controller' => 'compras', 'action' => 'imprimir_despacho', $compra['Compra']['id']), array('escape' => false, 'target' => '_blank')); ?>
</div>