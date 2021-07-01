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
	width: 640px;
	padding: 10px 19px 10px 19px;
	border: 1px solid #818f9e;
	border-top: hidden;
	<?= ($this->params['controller'] == 'compras' && $this->params['action'] == 'admin_buscar') ? '' : 'display: none;' ; ?>
	background-color: #ececec;
	margin-bottom: 20px;
	border-bottom-left-radius: 5px;
	border-bottom-right-radius: 5px;
}
.linea-abajo {
	border-bottom: 1px solid #000;
}
.elemento-panel {
	float: left;
	width: 100%;
	border-bottom: 1px solid #818f9e;
}
.elemento-panel .bloque {
	float: left;
	width: 300px;
	padding: 10px;
}
.elemento-panel .bloque.divisor {
	border-left: 1px solid #818f9e;
	width: 299px;
}
.elemento-panel .label {
	float: left;
	color: #818f9e;
	padding: 5px 10px;
}
.elemento-panel .input {
	float: left;
	padding: 5px 10px;
}
.elemento-panel .btn {
	float: right;
	padding: 5px 10px;
	background-color: #067eff;
	color: #FFF;
	text-decoration: none;
	border-radius: 3px;
}
.elemento-panel .btn:hover {
	opacity: .7;
}
.elemento-panel .mega-bloque {
	float: left;
	width: 100%;
	padding: 10px 0;
}
.elemento-panel .mega-bloque .input {
	padding: 5px;
	width: 170px;
	margin-right: 10px;

}
</style>
<a href="#" class="desplegar-tools"></a>
<div class="tools-panel">
	<div class="elemento-panel">
		<div class="bloque">
			<? if ($this->params['controller'] == 'compras' && in_array($this->params['action'], array('admin_index','admin_pagadas', 'admin_buscar'))) : ?>
			<span class="label">Compras por pagina</span>
			<?
				$valor = 20;
				if ($this->Session->check('ComprasPorPagina'))
				{
					$valor = $this->Session->read('ComprasPorPagina');
					if (! $valor || ! is_numeric($valor))
						$valor = 20;
				}
				$options = array(
					'label'		=> false,
					'div' 			=> false,
					'type'			=> 'select',
					'class' 		=> 'input',
					'value' 		=> $valor,
					'options'		=> array(
						10 => 10,
						20 => 20,
						50 => 50,
						100 => 100
					)
				); 
				echo $this->Form->input('ComprasPorPagina.cantidad_por_pagina',$options);
			?>
			<? endif; ?>
		</div>
		<div class="bloque divisor">
			<a href="<?= $this->Html->url(array('controller' => 'compras', 'action' => 'cargas')); ?>" class="btn">cargas picking boleta despacho</a>
			<? if (in_array($authUser['id'], array(1,5))) : ?>
				<br>
				<br>
				<a href="<?= $this->Html->url(array('controller' => 'administradores', 'action' => 'file_manager')); ?>" class="btn">file manager</a>
				<? if ($authUser['id']==5) : ?>
					<br>
					<br>
					<a href="<?= $this->Html->url(array('controller' => 'administradores', 'action' => 'administracion')); ?>" class="btn">administrar sitio</a>
				<? endif; ?>
			<? endif; ?>
		</div>
	</div>
	<div class="elemento-panel">
		<?= $this->Form->create('Compra', array('action' => 'buscar', 'inputDefaults' => array(
			'label' => false,
			'div' => false,
			'class' => 'input',
			'autocomplete' => 'off'
		))); ?>
		<div class="mega-bloque">
			<?= $this->Form->input('Buscar.search',array('placeholder' => 'Buscar...')); ?>
			<?
				$options = array(
					'type' => 'select',
					// 'empty' => '- buscar por...',
					'options' => array(
						'Compra.id' => 'N° de compra',
						'Usuario.apellido_paterno' => 'Apellido',
						'Usuario.email' => 'Email',
						'Compra.boleta' => 'Boleta',
						// 'todo' => 'Todo',
					)
				); 
				echo $this->Form->input('Buscar.field',$options);
			?>
			<?
				$meses = array(1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiempre', 'Octubre', 'Noviembre', 'Diciembre');
				$limite = 12;
				$month = (int)(date('m'));
				$year = (int)(date('Y'));
				$key = $year.'-'.( ($month < 10) ? '0' : '' ).$month;
				$name = $meses[$month].' - '.$year;
				$periodos[$key] = $name;
				for ( $x=1; $x <= $limite; $x++ )
				{
					$month--;
					if ( $month < 1 )
					{
						$month = 12;
						$year--;
					}
					$key = $year.'-'.( ($month < 10) ? '0' : '' ).$month;
					$name = $meses[$month].' - '.$year;
					$periodos[$key] = $name;
				}
				$options = array(
					'type' => 'select',
					'empty' => '- mes...',
					'options' => $periodos
				); 
				echo $this->Form->input('Buscar.date',$options);
			?>
			<a href="#" class="btn" rel="buscarCompra">buscar</a>
		</div>
		<?= $this->Form->end(); ?>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('.desplegar-tools').click(function(e) {
		e.preventDefault();
		if ( $('.tools-panel').is(':visible') ) {
			$('.tools-panel').slideUp(700);
		} else {
			$('.tools-panel').slideDown(700);
		}
		return false;
	});
	$('#ComprasPorPaginaCantidadPorPagina').change(function() {
		var cantidad = $(this).val();
		if (cantidad) {
			$.ajax({
				type		: 'POST',
				url			: webroot + 'compras/ajax_cantidad_compras_por_pagina',
				data		: { cantidad : cantidad },
				success: function(respuesta) {
					if (respuesta == 'OK') {
						location.reload();
					}
				}
			});
		}
	});
	$('a[rel="buscarCompra"]').live('click', function() {
		var formulario = $(this).closest('form');
		var url = webroot + 'admin/compras/buscar';
		if (formulario.find('#BuscarSearch').val())
			url+='/search:'+formulario.find('#BuscarSearch').val();
		if (formulario.find('#BuscarField').val())
			url+='/field:'+formulario.find('#BuscarField').val();
		if (formulario.find('#BuscarDate').val())
			url+='/date:'+formulario.find('#BuscarDate').val();
		location.href = url;
	});
});
</script>
