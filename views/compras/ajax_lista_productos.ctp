<? foreach ( $productos as $id => $producto ) : ?>
<option value="<?= $id; ?>"><?= $producto['Producto']['codigo']; ?></option>
<? endforeach; ?>