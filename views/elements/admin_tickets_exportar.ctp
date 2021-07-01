<? if (in_array($authUser['perfil'], array(3,2,1,4))) : ?>
	<style>
	.desplegar-tools {
		background-image: url(<?= $this->Html->url('/img/iconos/btn-tools.png'); ?>);
		float: right;
		width: 30px;
		height: 30px;
		margin-bottom: 3px;
		margin-top: 0;
		background-position: left top;
	}
	.desplegar-tools:hover {
		background-position: left -30px;
	}
	.tools-panel {
		float:left;
		width: 420px;
		padding: 10px 19px 10px 19px;
		margin-left: 220px;
		border: 1px solid #000;
		border-top: hidden;
		display: none;
		background-color: #f8f8f8;
		margin-bottom: 20px;
		border-bottom-left-radius: 5px;
		border-bottom-right-radius: 5px;
	}
	.tools-panel .btn-excel {
		background-color: #000000;
		color: #FFFFFF;
		background-image: url("<?= $this->Html->url('/img/admin/bg-boton.png'); ?>");
		background-position: center center;
		background-repeat: repeat-x;
		display: block;
		float: right;
		font-size: 13px;
		font-weight: normal;
		margin-left: 10px;
		padding: 10px 15px;
		text-decoration: none;
		text-transform: capitalize;
		border-radius: 5px;
		height: 14px;
		padding-top: 5px;
	}
	.tools-panel .btn-excel .excel {
		background-image: url("<?= $this->Html->url('/img/iconos/excel_16.png'); ?>");
		background-position: left center;
		background-repeat: no-repeat;
		padding-bottom: 5px;
		padding-left: 30px;
		padding-top: 5px;
	}
	.tools-panel .btn-excel .foto {
		background-image: url("<?= $this->Html->url('/img/iconos/reload_16.png'); ?>");
		background-position: left center;
		background-repeat: no-repeat;
		padding-bottom: 5px;
		padding-left: 30px;
		padding-top: 5px;
	}
	.tools-panel .texto-btn {
		float: right;
		width: auto;
		padding-top: 5px;
		color: #999999;
	}
	.tools-panel input {
		background-color: #FFF;
	}
	/*.tools-panel input:focus {
		box-shadow: 1px 1px 1px 1px #000;
	}*/
	</style>
	<a href="#" class="desplegar-tools"></a>
	<div class="tools-panel">
		<div class="botones buscar" style="float: right; margin-bottom: 5px; margin-top: 0; width: 100%; border-bottom: 1px solid #000; padding-bottom: 15px;">
			<div class="conte-buscador">
				<?= $this->Form->create('Tickets', array('action' => 'buscador')); ?>
					<?= $this->Form->input('buscar', array('id' => 'input-buscarProducto',
														   'class' => 'clase-input',
														   'div' => false,
														   'label' => false,
														   'type' => 'text',
														   'placeholder' => '- ingrese nombre o codigo del producto aquí')); ?>
				<a href="#" id="btn-buscarDescuento" class="submit" style="height: 14px; padding-top: 5px;"><span class="buscar">Buscar</span></a>
				<?= $this->Form->end(); ?>
			</div>
			<div class="texto-btn">
				<u><b>Buscador de descuentos:</b></u> codigo o nombre del descuento
			</div>
		</div>
		<div class="botones" style="float: right; margin-bottom: 5px; margin-top: 0; width: 100%; padding-bottom: 15px; border-bottom: 1px solid #000;">
			<a href="<?= $this->Html->url(array('controller' => 'tickets', 'action' => 'exportar_tickets_validados')); ?>" class="btn-excel"><span class="excel">Exportar</span></a>
			<div class="texto-btn">
				Exportar codigos validados &raquo;
			</div>
		</div>
		<? if ($authUser['perfil']) : ?>
		<div class="botones" style="float: right; margin-bottom: 5px; margin-top: 0; width: 100%;">
			<div class="texto-btn">
				<u><b>Carga masiva de tickets a traves de csv</u></b>
			</div>
		</div>
		<div class="botones" style="float: right; margin-bottom: 5px; margin-top: 0; width: 100%; padding-bottom: 15px;">
			<a href="<?= $this->Html->url(array('controller' => 'tickets', 'action' => 'masivo_add')); ?>" class="btn-excel"><span class="excel" style="background-image: url('<?= $this->Html->url('/img/iconos/up_16.png'); ?>');">Cargar</span></a>
			<!--<a href="<?= $this->Html->url(array('controller' => 'tickets', 'action' => 'masivo_listar')); ?>" class="btn-excel"><span class="excel" style="background-image: url('<?= $this->Html->url('/img/iconos/document_16.png'); ?>');">Listar</span></a> -->
		</div>
		<? endif; ?>
	</div>
	<script>
	$('.desplegar-tools').click(function(e) {
		e.preventDefault();
		if ( $('.tools-panel').is(':visible') ) {
			$('.tools-panel').slideUp(700);
		} else {
			$('.tools-panel').slideDown(700);
		}
		return false;
	});
	</script>
<? endif; ?>