<div class="col02">
	<h1 class="titulo"><? __('Administracion');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th>Proceso</th>
			<th>Descripcion</th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
		<tr>
			<td>Descargar excel</td>
			<td>Genera archivo excel a partir de xml disponibilizado en sitio USA, con detalles de productos.</td>
			<td>
				<a href="<?= $this->Html->url(array('action' => 'generar_excel_xml', 'admin' => false)); ?>">
					<img src="<?= $this->Html->url('/img/iconos/document_32.png'); ?>" />
				</a>
			</td>
		</tr>
		<tr>
			<td>Descargar excel (con ropa)</td>
			<td>Genera archivo excel a partir de xml disponibilizado en sitio USA, con detalles de productos.</td>
			<td>
				<a href="<?= $this->Html->url(array('action' => 'generar_excel_xml', 'inclusive', 'admin' => false)); ?>">
					<img src="<?= $this->Html->url('/img/iconos/document_32.png'); ?>" />
				</a>
			</td>
		</tr>
		<tr style="display: none;">
			<td>File manager</td>
			<td>Abre manager para imagenes de productos descargados desde el sitio USA, tomando el listado de productos del xml disponibilizado.</td>
			<td>
				<a href="#" rel="abrirManager">
					<img src="<?= $this->Html->url('/img/iconos/monitor_32.png'); ?>" />
				</a>
			</td>
		</tr>
		<tr style="display: none;">
			<td>Cargar imagenes</td>
			<td>Carga las imagenes disponibilizadas desde USA. Este proceso puede tardar unas horas.</td>
			<td>
				<a href="#">
					<img src="<?= $this->Html->url('/img/iconos/gear_32.png'); ?>" />
				</a>
			</td>
		</tr>
	</table>

	
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('a[rel="abrirManager"]').click(function(e) {
		e.preventDefault();
		// if ( confirm('¿Desea cargar el manager en la ventana actual?') ) {
			//$('.col02').append('<iframe rel="iframeManager" src="<?= $this->webroot.'webroot/file_manager/index.php'; ?>" width="680" height="450" frameborder="1"></iframe>');
		// } else {
		// 	window.open('<?= $this->webroot.'webroot/file_manager/index.php'; ?>', '_blank');
		// }
	});

});
</script>
