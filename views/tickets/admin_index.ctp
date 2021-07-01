<div class="col02">
	<?= $this->element('admin_descuento_exportar'); ?>
	<?= $this->Form->create('Ticket', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Buscar Ticket'); ?></h1>
	<ul class="edit">
		<li style="border-bottom: none;"><?= $this->Form->input('codigo'); ?></li>
		<li style="font-size: 10px; color: #999; text-align: center;">Debe ingresar el codigo del ticket.</li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="buscar">Buscar</span></a>
	</div>
	<?= $this->Form->end(); ?>
	<? if ( isset($ticket) ) : ?>
		<? if ($ticket) : ?>
					<div class="previsualizar" style="margin-top: 30px;">
				<ul>
					<li class="extendido"><span><? __('Codigo'); ?>:</span><p><?= $ticket['Ticket']['codigo']; ?>&nbsp;</p></li>
					<li><span><? __('Numero Guia'); ?>:</span><p><?= $ticket['Ticket']['numero_guia']; ?>&nbsp;</p></li>
					<li><span><? __('Fecha Guia'); ?>:</span><p><?= date('d-m-Y', strtotime($ticket['Ticket']['fecha_guia'])); ?>&nbsp;</p></li>
					<li><span><? __('Codigo Producto'); ?>:</span><p><?= $ticket['Ticket']['codigo_producto']; ?>&nbsp;</p></li>
					<li><span><? __('Color'); ?>:</span><p><?= $ticket['Ticket']['color']; ?>&nbsp;</p></li>
					<li><span><? __('Talla'); ?>:</span><p><?= $ticket['Ticket']['talla']; ?>&nbsp;</p></li>
					<li>
						<span><? __('Utilizado'); ?>:</span>
						<p><?= ($ticket['Ticket']['estado'] == 1) ? 'Si' : 'No'; ?>&nbsp;</p>
					</li>
				</ul>
			</div>
				<? if ($ticket['Ticket']['estado']) : ?>
					<div class="previsualizar">
						<ul>
							<li><span>Tipo</span><p>Tienda&nbsp;</p></li>
							<li><span>Tienda</span><p><?= $ticket['Administrador']['nombre']; ?>&nbsp;</p></li>
							<li ><span>Cliente</span><p><?= $ticket['Ticket']['nombre']; ?>&nbsp;</p></li>
							<li ><span>Cliente</span><p><?= $ticket['Ticket']['rut']; ?>&nbsp;</p></li>
							<li ><span>Fecha</span><p><?= $ticket['Ticket']['fecha_cobro']; ?>&nbsp;</p></li>
						</ul>
					</div>
				<? endif; ?>
			<? endif; ?>
			<? if (!$ticket['Ticket']['estado']) : ?>
				<?= $this->Form->create('Ticket', array('action' => 'usar_ticket', 'type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
					<h1 class="titulo"><? __('Datos Cliente'); ?></h1>
					<ul class="edit">
						<?= $this->Form->hidden('Ticket.id', array('value' => $ticket['Ticket']['id']));?>
						<?= $this->Form->hidden('Ticket.codigo', array('value' => $ticket['Ticket']['codigo']));?>
						<li><?= $this->Form->input('Ticket.nombre'); ?></li>
						<li><?= $this->Form->input('Ticket.rut'); ?></li>
						<li><?= $this->Form->input('Ticket.codigo_producto_nuevo'); ?></li>
						<li><?= $this->Form->input('Ticket.color_nuevo'); ?></li>
						<li><?= $this->Form->input('Ticket.talla_nuevo'); ?></li>
					</ul>
					<div class="botones">
						<a href="#" class="submit"><span class="generar">Utilizar</span></a>
					</div>
				<?= $this->Form->end(); ?>
			<? else : ?>
			<script type="text/javascript">
			$(document).ready(function()
			{
				alert('Este Ticket ya fue utilizado');
			});
			</script>
			<? endif; ?>
		<? else : ?>
			<div class="previsualizar" style="margin-top: 30px;">
				<ul>
					<li class="extendido" style="text-align: center; font-weight: bold;">No se encontro Ticket.</li>
				</ul>
			</div>
		<? endif; ?>
	
</div>