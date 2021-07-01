<div class="col02">
	<h1 class="titulo">Previsualización de <? __('descuento');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Codigo'); ?>:</span><p><?= $ticket['Ticket']['codigo']; ?>&nbsp;</p></li>
			<li><span><? __('Guia'); ?>:</span><p><?= $ticket['Ticket']['numero_guia']; ?>&nbsp;</p></li>
			<li><span><? __('Fecha Caducidad'); ?>:</span><p><?= date('Y-m-d', strtotime($ticket['Ticket']['fecha_guia'])); ?>&nbsp;</p></li>
			<li><span><? __('Estilo'); ?>:</span><p><?= $ticket['Ticket']['codigo_producto']; ?>&nbsp;</p></li>
			<li><span><? __('Color'); ?>:</span><p><?= $ticket['Ticket']['color']; ?>&nbsp;</p></li>
			<li><span><? __('Talla'); ?>:</span><p><?= $ticket['Ticket']['talla']; ?>&nbsp;</p></li>
			<li><span><? __('Fecha Creacion'); ?>:</span><p><?= $ticket['Ticket']['created']; ?>&nbsp;</p></li>
			<li><span><? __('Estado'); ?>:</span><p><?= ($ticket['Ticket']['estado'] == 1) ? 'Si' : 'No'; ?>&nbsp;</p></li>


		</ul>
	</div>
	<?php if($ticket['Ticket']['estado']) :?>
	<h2 class="subtitulo">Cobrado Por:</h2>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $ticket['Ticket']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Rut'); ?>:</span><p><?= $ticket['Ticket']['rut']; ?>&nbsp;</p></li>
			<li><span><? __('Fecha Cobro'); ?>:</span><p><?= date('Y-m-d', strtotime($ticket['Ticket']['fecha_cobro'])); ?>&nbsp;</p></li>
		</ul>
	</div>

<? endif; ?>
</div>
