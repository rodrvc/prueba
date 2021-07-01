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
	<?= ($this->params['controller'] == 'administradores' && $this->params['action'] == 'admin_index') ? '' : 'display: none;' ; ?>
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
		<?= $this->Form->create('Administrador', array('inputDefaults' => array(
			'label' => false,
			'div' => false,
			'class' => 'input',
			'autocomplete' => 'off'
		))); ?>
		<div class="bloque">
			<?= $this->Form->input('Buscar.search',array('placeholder' => 'Buscar por nombre...')); ?>
			<a href="#" class="btn" rel="buscarCompra">buscar</a>
		</div>
		<?= $this->Form->end(); ?>

		<? if (in_array($authUser['id'], array(1,5))) : ?>
		<div class="bloque divisor">
			<a href="<?= $this->Html->url(array('controller' => 'administradores', 'action' => 'file_manager')); ?>" class="btn">file manager</a>
			<? if ($authUser['id']==5) : ?>
				<br>
				<br>
				<a href="<?= $this->Html->url(array('controller' => 'administradores', 'action' => 'administracion')); ?>" class="btn">administrar sitio</a>
			<? endif; ?>
		</div>
		<? endif; ?>
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
	$('a[rel="buscarCompra"]').live('click', function() {
		var formulario = $(this).closest('form');
		var url = webroot + 'admin/administradores/index';
		if (formulario.find('#BuscarSearch').val())
			url+='/search:'+formulario.find('#BuscarSearch').val();
		location.href = url;
	});
});
</script>
