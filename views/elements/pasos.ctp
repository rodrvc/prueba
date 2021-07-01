<?

	switch ( $this->action ) {
		case "registro":
			$num_paso = "uno";
			break;
		case "despacho":
			$num_paso = "dos";
			break;
		case "confirmar":
			$num_paso = "tres";
			break;
		case "pago":
			$num_paso = "cuatro";
			break;
		case "felicidades":
			$num_paso = "cinco";
			break;
	}
?>
<div class="cont-pasos <?= $num_paso; ?>">
	<?= $this->Html->link(($this->action == 'registro')?'<span class="lugar current">Registro</span>':'<span class="lugar">Registro</span>', '#', array('escape' => false)); ?>
	<?= $this->Html->link(($this->action == 'despacho')?'<span class="lugar dos current">Despacho</span>':'<span class="lugar dos">Despacho</span>', '#', array('escape' => false)); ?>
	<?= $this->Html->link(($this->action == 'confirmar')?'<span class="lugar tres current">Confirmar</span>':'<span class="lugar tres">Confirmar</span>', '#', array('escape' => false)); ?>
	<?= $this->Html->link(($this->action == 'pago')?'<span class="lugar cuatro current">Pago</span>':'<span class="lugar cuatro">Pago</span>', '#', array('escape' => false)); ?>
	<?= $this->Html->link(($this->action == 'felicidades')?'<span class="lugar cinco current">Recibo</span>':'<span class="lugar cinco">Recibo</span>', '#', array('escape' => false)); ?>
</div>