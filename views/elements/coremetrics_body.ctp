<? if ( $_SERVER['REMOTE_ADDR'] != '::1' ) : ?>
	<? if ( $this->params['controller'] == 'productos' && $this->params['action'] == 'inicio' ) : ?>
	<script type="text/javascript">
		/* == COREMETRIC HOME == */
		cmCreatePageviewTag("Inicio", "Paginas");
	</script>
	<? elseif ( $this->params['controller'] == 'productos' && $this->params['action'] == 'view' ) : ?>
	<script type="text/javascript">
		/* == COREMETRIC DETALLE PRODUCTO == */
		cmCreatePageviewTag("PRODUCTO: <?= $producto['Producto']['nombre']; ?>", "Paginas"); 
		cmCreateProductviewTag("<?= $producto['Producto']['codigo'].$producto['Color']['codigo']; ?>", "<?= $producto['Producto']['nombre']; ?>");
	</script>
	<? elseif ( $this->params['controller'] == 'tiendas' && $this->params['action'] == 'index' ) : ?>
	<script type="text/javascript">
		/* == COREMETRIC TIENDAS == */
		cmCreatePageviewTag("Tiendas", "Paginas");
	</script>
	<? elseif ( $this->params['controller'] == 'productos' && $this->params['action'] == 'carro' && isset($productos) && $productos) : ?>
	<script type="text/javascript">
		/* == COREMETRIC CARRO == */
		cmCreatePageviewTag("Carro", "Paginas");
		<? if (isset($productos) && $productos) : ?>
			<? foreach ($productos as $producto) : ?>
				cmCreateShopAction5Tag("<?= $producto['Producto']['codigo'].$producto['Producto']['Color']['codigo']; ?>", "<?= $producto['Producto']['nombre']; ?>", "<?= $producto['cantidad']; ?>");
			<? endforeach; ?>
			cmDisplayShop5s();
		<? endif; ?>
	</script>
	<? elseif ( $this->params['controller'] == 'productos' && $this->params['action'] == 'exito' ) : ?>
	<script type="text/javascript">
		/* == COREMETRIC EXITO == */
		cmCreatePageviewTag("Exito", "Paginas");
		<? if (isset($productos) && $productos) : ?>
			<? foreach ($productos as $producto) : ?>
				cmCreateShopAction9Tag("<?= $producto['Producto']['codigo'].$producto['Producto']['Color']['codigo']; ?>",
									   "<?= $producto['Producto']['nombre']; ?>",
									   "<?= $producto['cantidad']; ?>",
									   "<?= ($producto['Producto']['oferta']) ? $producto['Producto']['precio_oferta'] : $producto['Producto']['precio'] ; ?>",
									   "<?= $authUser['id']; ?>",
									   "<?= $pago['Pago']['compra_id']; ?>",
									   "<?= $subtotal; ?>");
			<? endforeach; ?>
			cmDisplayShop9s();
			cmCreateOrderTag("<?= $pago['Pago']['compra_id']; ?>", "<?= $subtotal; ?>", "0", "<?= $authUser['id']; ?>");
			cmCreateRegistrationTag("<?= $authUser['id']; ?>", "<?= $authUser['email']; ?>");
		<? endif; ?>
	</script>
	<? endif; ?>
<? endif; ?>