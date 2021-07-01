<style type="text/css">
  /*div.green { margin: 0px; width: 100px; height: 80px; background: green; border: 1px solid black; position: relative; }*/
  /*div.red { margin-top: 10px; width: 50px; height: 30px; background: red; border: 1px solid black; position: relative; }*/
	.ui-effects-transfer {
		background-image: url('<?= $this->Html->url('/img/' . $producto['Producto']['foto']['path']); ?>');
		background-position: center; background-size: contain;
		background-repeat: no-repeat;
	}
	.ui-effects-campana {
		background-image: url('<?= $this->Html->url('/img/' . $producto['Producto']['imagen_campana']['path']); ?>');
		background-position: center; background-size: contain;
		background-repeat: no-repeat;
		z-index: 300;
	}
</style>