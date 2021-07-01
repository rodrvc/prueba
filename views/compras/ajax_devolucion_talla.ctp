<? if ($tallas) : ?>
	<option value="">seleccione talla</option>
	<? foreach ($tallas as $talla) : ?>
	<option value="<?= $talla; ?>"><?= $talla; ?></option>
	<? endforeach; ?>
<? else : ?>
	<option value="">sin stock</option>
<? endif; ?>