<div class="col02">
	<div class="previsualizar" style="margin-top: 30px;">
		<h2 class="subtitulo">Reporte de Anulaciones</h2>
		<ul>

			<li><b><? __('Ordenes Ingresadas'); ?></b></li>
			<li><b><?= $resultados['total']; ?></b></li>
			<li><b><? __('Ordenes Anuladas'); ?></b></li>
			<li><b><?= $resultados['ok']; ?></b></li>
			<li><b><? __('Ordenes No Anuladas'); ?></b></li>
			<li><b><?= $resultados['error']; ?></b></li>
		</ul>
	</div>
		<?php if(isset($errores) && is_array($errores) && count($errores) > 0)?>
	<div class="previsualizar" style="margin-top: 30px;">
		<h2 class="subtitulo">Ordenes fallidas</h2>
		<ul>
			<?php foreach ($errores as $orden => $error) :?> 
				<li><b><?php echo $orden; ?></b></li>
				<li><b><?php echo $error; ?></b></li>
			<?php endforeach; ?>
		</ul>
	</div>
			
	
		<div class="botones">
			<a href="<?= $this->Html->url(array('action' => 'webpay_anular')); ?>"><span class="guardar" style="background-image: url('<?= $this->Html->url('/img/iconos/left_16.png'); ?>')";>Volver</span></a>
		</div>
	</div>
	
</div>
