<cake:nocache>
<style>
	.usuario, .usuario a {
		color: #000000 !important;
	}
	.usuario p {
		margin-bottom: 10px;
		display: inline-block;
	}
	.gratis .despacho {
		color: #CC0000;
		font-weight: 900;
	}
	.gratis .tuser, .gratis .tuser a {
		color: #000000;
		font-weight: 900;
	}
	.gratis .tuser {
		display: block;
		margin-bottom: 5px;
		margin-top:-10px;
	}
	.gratis .tuser a {
		text-decoration: underline;
	}
	.carrito {
		padding-top: 10px;
		padding-bottom: 10px;
		padding-left: 20px;
		padding-right: 20px;
		text-align: center;
		background-color: #FFFFFF;
		color: #808080;
		float: right;
		font-size: 14px;
		border-bottom: #ccc 1px solid;
		border-left: #ccc 1px solid;
		box-shadow: 0 0 0 #000;
		-webkit-box-shadow: 0 0 0 rgba(0,0,0,0);
		-moz-box-shadow: 0 0 0 rgba(0,0,0,0);
		font-weight: 900 !important;
	}
	.carrito a {
		color: #808080;
		font-weight: 900;
	}
</style>
<div class="header">
	<?= $this->Html->link('<div class="logo"></div>', array('controller' => 'productos', 'action' => 'inicio'), array('escape' => false)); ?>
	<div class="gratis">
		<p class="tuser">
			<span>¡Bienvenid@!</span>

			<? if ( isset ( $authUser ) ) : ?>
				<a href="<?= $this->Html->url(array('controller' => 'usuarios', 'action' => 'perfil_datos')); ?>">
					<?= $authUser['nombre'].' '.$authUser['apellido_paterno'].' '.$authUser['apellido_materno']; ?>
				</a>
				<?= $this->Html->link('Desconectarse', '#', array('id' => 'logout', 'class' => 'btn-logout')); ?>
			<? else : ?>
				<a href="<?= $this->Html->url(array('controller' => 'usuarios', 'action' => 'add')); ?>">
					Inicia Sesión
				</a>
				<a href="<?= $this->Html->url(array('controller' => 'usuarios', 'action' => 'add')); ?>" title="Registrate aquí">
					Regístrate aquí
				</a>
			<? endif; ?>
		</p>
		<p class="despacho">
			DESPACHO GRATIS en todo Chile continental
		</p>

	</div>
	<div class="carrito">
		<? if( $productos_carro = $this->Session->read('Carro') ) : ?>
		<?
			$contador = 0;
			if ($productos_carro)
			{
				foreach ($productos_carro as $producto_carro)
				{
					if ($producto_carro['cantidad'])
					{
						$contador = $contador + $producto_carro['cantidad'];
					}
				}
			}
		?>
			<p>Revisa tu carro de compras (<?= $contador; ?>)</p>
		<? else: ?>
			<p>Tu carro de compra está vacío</p>
		<? endif; ?>
			<?= $this->Html->link('Compra Online', array('controller' => 'productos', 'action' => 'carro'), array('title' => 'comprar online', 'class' => 'online-buy')); ?>
	</div>
</div>
</cake:nocache>
