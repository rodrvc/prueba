<?= $this->Html->script('jquery-1.11.1.min'); ?>
<style type="text/css" media="all">
ul li {
	padding: 5px;
	border: 1px solid #ccc;
	list-style:none;
}
ul li span {
	float: left;
	width: 300px;
}
</style>
<form name="formulario" method="post" id="formulariotbk" action="<?= $this->Html->url(array('action' => 'felicidades')); ?>">
<ul>
	<li><span>TBK_ORDEN_COMPRA: </span><input name="TBK_ORDEN_COMPRA" value="<?= $compra['Compra']['id']; ?>" type="text" readonly="readonly"></li>
	<li><span>TBK_TIPO_TRANSACCION: </span><input name="TBK_TIPO_TRANSACCION" value="" type="text" readonly="readonly"></li>
	<li><span>TBK_RESPUESTA:</span><input name="TBK_RESPUESTA" value="0" type="text" readonly="readonly"></li>
	<li><span>TBK_MONTO: </span><input name="TBK_MONTO" value="<?= ($compra['Compra']['total']*100); ?>" type="text" readonly="readonly"></li>
	<li><span>TBK_CODIGO_AUTORIZACION: </span><input name="TBK_CODIGO_AUTORIZACION" value="123456" type="text" readonly="readonly"></li>
	<li><span>TBK_FINAL_NUMERO_TARJETA: </span><input name="TBK_FINAL_NUMERO_TARJETA" value="1234" type="text" readonly="readonly"></li>
	<li><span>TBK_FECHA_CONTABLE: </span><input name="TBK_FECHA_CONTABLE" value="<?= str_replace('-','',date('m-d')); ?>" type="text" readonly="readonly"></li>
	<li><span>TBK_FECHA_EXPIRACION: </span><input name="TBK_FECHA_EXPIRACION" value="<?= str_replace('-','',date('m-d')); ?>" type="text" readonly="readonly"></li>
	<li><span>TBK_FECHA_TRANSACCION: </span><input name="TBK_FECHA_TRANSACCION" value="<?= str_replace('-','',date('m-d')); ?>" type="text" readonly="readonly"></li>
	<li><span>TBK_HORA_TRANSACCION: </span><input name="TBK_HORA_TRANSACCION" value="<?= str_replace(':','',date('H:i:s')); ?>" type="text" readonly="readonly"></li>
	<li><span>TBK_ID_SESION: </span><input name="TBK_ID_SESION" value="1" type="text" readonly="readonly"></li>
	<li><span>TBK_ID_TRANSACCION: </span><input name="TBK_ID_TRANSACCION" value="1234567890" type="text" readonly="readonly"></li>
	<li><span>TBK_TIPO_PAGO:</span><input name="TBK_TIPO_PAGO" value="VN" type="text" readonly="readonly"></li>
	<li><span>TBK_NUMERO_CUOTAS: </span><input name="TBK_NUMERO_CUOTAS" value="0" type="text" readonly="readonly"></li>
	<li><span>TBK_NUMERO_CUOTAS: </span><input name="TBK_TASA_INTERES_MAX" value="0" type="text" readonly="readonly"></li>
	<li><span>TBK_MAC: </span><textarea name="TBK_MAC" readonly="readonly" cols="30" rows="5"><?= md5('Andain').md5($compra['Compra']['id']).md5($authUser['id']); ?></textarea></li>
</ul>
<button type="button" rel="enviarTBK">ENVIAR</button>
</form>
<script>
$(document).ready(function() {
	$('button[rel="enviarTBK"]').click(function() {
		var formulario = $('#formulariotbk');
		$.ajax({
			async : true,
			type : 'POST',
			url : '<?= $this->Html->url(array('action' => 'cierre')); ?>',
			data : formulario.serialize(),
			success: function(respuesta) {
				if (respuesta == 'ACEPTADO') {
					formulario.submit();
				} else {
					if (confirm('Sera redirigido a otra pagina. ¿Desea continuar?')) {
						location.href="<?= $this->Html->url(array('action' => 'fallo')); ?>";
					}
				}
			},
			error: function() {
				if (confirm('Sera redirigido a otra pagina. ¿Desea continuar?')) {
					location.href="<?= $this->Html->url(array('action' => 'fallo')); ?>";
				}
			}
		});
	});
});
</script>