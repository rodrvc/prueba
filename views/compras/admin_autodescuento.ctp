<div class="col02">
	<!-- DATOS COMPRA -->
	<h1 class="titulo">Anulacion de compra #<?= $respuesta['compra']; ?></h1>
	<div class="previsualizar">
		<ul>
			<li class="extendido"><span>Compra:</span><p>#<?= $respuesta['compra']; ?>&nbsp;</p></li>
			<li class="extendido"><span>Estado Anulacion:</span><p><?= $respuesta['anulacion']; ?>&nbsp;</p></li>
			<li class="extendido"><span>Descuento:</span><p>#<?= $respuesta['descuento']; ?>&nbsp;</p></li>
			<li class="extendido"><span>Cod.Descuento:</span><p><?= $respuesta['codigo']; ?>&nbsp;</p></li>
			<li class="extendido"><span>Monto Descuento:</span><p><?= $this->Shapeups->moneda($respuesta['total']); ?>&nbsp;</p></li>
			<li class="extendido"><span>Estado e-mail:</span><p><?= $respuesta['envio_mail']; ?>&nbsp;</p></li>
		</ul>
	</div>
</div>