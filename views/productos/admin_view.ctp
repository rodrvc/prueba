<div class="col02">
	<h1 class="titulo">Previsualización de <? __('producto');?></h1>
	<div class="previsualizar">
		<ul>
			<?
				// colores, imagenes y otros
				$imagen = $this->Html->url('/img/sin_imagen.jpg');
				if ($producto['Producto']['foto'])
				{
					$imagen = $this->Html->url('/img/'.$producto['Producto']['foto']['path']);
				}
				$detalle_imagen = "<div style='width: 250px; border: 1px solid #999;'><img src='".$imagen."' width='100%' /></div>";
				$oferta = '<p style="background-color: #ff0000; font-weight: bold; color: #FFF; text-align: center;">NO</p>';
				$precio = '<b>'.$this->Shapeups->moneda($producto['Producto']['precio']).'</b>';
				if ($producto['Producto']['oferta'])
				{
					$oferta = '<p style="background-color: #008800; font-weight: bold; color: #FFF; text-align: center;">SI</p>';
					if ($producto['Producto']['precio_oferta'])
					{
						$precio = '<b style="text-decoration: line-through;">'.$this->Shapeups->moneda($producto['Producto']['precio']).'</b> - <b style="color: #ff0000;">'.$this->Shapeups->moneda($producto['Producto']['precio_oferta']).'</b>';
					}
				}
				$new = '<p style="background-color: #ff0000; font-weight: bold; color: #FFF; text-align: center;">NO</p>';
				if ($producto['Producto']['new'])
				{
					$new = '<p style="background-color: #008800; font-weight: bold; color: #FFF; text-align: center;">SI</p>';
				}
				$escolar = '<p style="background-color: #ff0000; font-weight: bold; color: #FFF; text-align: center;">NO</p>';
				if ($producto['Producto']['escolar'])
				{
					$escolar = '<p style="background-color: #008800; font-weight: bold; color: #FFF; text-align: center;">SI</p>';
				}
			?>
			<li class= "extendido"><span><? __('Nombre'); ?>:</span><p><?= $producto['Producto']['nombre']; ?>&nbsp;</p></li>
			<li class= "extendido"><span><? __('Categoria'); ?>:</span><p><?= $producto['Categoria']['nombre']; ?>&nbsp;</p></li>
			<li class= "extendido"><span><? __('Colección'); ?>:</span><p><?= $producto['Coleccion']['nombre']; ?>&nbsp;</p></li>
			<li class= "extendido">
				<span><? __('Foto'); ?>:</span>
				<a href="#" class="descripcion-tool" title="<?= $detalle_imagen; ?>">
					<?= (isset($producto['Producto']['foto']['path']) && $producto['Producto']['foto']['path']) ? basename($producto['Producto']['foto']['path']) : ''; ?>&nbsp;
				</a>
			</li>
			<li><span><? __('Color'); ?>:</span><p><?= $producto['Color']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Codigo'); ?>:</span><p><?= $producto['Producto']['codigo'].'-'.$producto['Color']['codigo']; ?>&nbsp;</p></li>
			
			<li><span><? __('Oferta'); ?>:</span><p><?= $oferta; ?>&nbsp;</p></li>
			<li><span><? __('Precio'); ?>:</span><?= $precio; ?>&nbsp;</li>
			
			<li><span><? __('New Arrival'); ?>:</span><p><?= $new; ?>&nbsp;</p></li>
			<li><span><? __('Escolar'); ?>:</span><p><?= $escolar; ?>&nbsp;</p></li>
			<li class= "extendido"><span><? __('Outlet'); ?>:</span><?= (isset($producto['Producto']['outlet']) && $producto['Producto']['outlet']) ? 'SI' : 'NO'; ?>&nbsp;</li>
			<li class= "extendido"><span><? __('Descripcion'); ?>:</span><?= $producto['Producto']['descripcion']; ?>&nbsp;</li>
			<li class= "extendido"><span><? __('Ficha'); ?>:</span><?= $producto['Producto']['ficha']; ?>&nbsp;</li>
			<li><span><? __('DIV'); ?>:</span><?= $producto['Producto']['division']; ?>&nbsp;</li>
			<li><span><? __('Tipo'); ?>:</span><?= $producto['Producto']['tipo']; ?>&nbsp;</li>
			<li class= "extendido"><span><? __('Grupo'); ?>:</span><?= $producto['Producto']['grupo']; ?>&nbsp;</li>
			<li class= "extendido"><span><? __('Slug'); ?>:</span><a href="http://store.skechers-chile.cl/detalle/<?= $producto['Producto']['slug']; ?>" target="_blank"><?= $producto['Producto']['slug']; ?></a>&nbsp;</li>
		</ul>
		<p>&nbsp;</p>
		<? if (false) : ?>
		<h1 class="titulo" style="font-size: 16px;">Stock por Tienda</h1>
		<ul>
			<style>
			.lista-tiendas {
				float: left;
				width: 12px;
				height: 12px;
				font-size: 10px;
				text-align: center;
				text-decoration: none !important;
				margin-right: 5px;
				background-color: #ccc;
				padding-bottom: 5px;
			}
			</style>
			<? foreach ( $tiendas as $tienda ) : ?>
			<li class="extendido">
				<div style="float: left; width: 200px;">
					<a href="#" class="lista-tiendas" data-id="<?= $tienda['Tienda']['id']; ?>">+</a>
					<b><?= $tienda['Tienda']['nombre']; ?></b>
				</div>
				<span style="margin-left: 100px; font-weight: normal;">Stock Total: <?= $tienda['Total']['stock']; ?></span>
				<? if ( isset($tienda['Stock']) ) : ?>
				<ul class="lista-stockXtienda tienda-<?= $tienda['Tienda']['id']; ?>" style="display: none;">
					<? foreach ( $tienda['Stock'] as $stock ) : ?>
					<li><span style="margin-left: 30px; font-weight: 100;">talla: <?= $stock['talla']; ?></span></li>
					<li>
						<span style="font-weight: 100;">cantidad: <?= $stock['cantidad']; ?></span>
						<span style="margin-left: 60px;">
						<?= $this->Html->link($this->Html->image('iconos/search_16.png', array('title' => 'Ver')), array('controller' => 'stocks', 'action' => 'view', $stock['id']), array('escape' => false)); ?>
						<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('controller' => 'stocks', 'action' => 'edit', $stock['id']), array('escape' => false)); ?>
						</span>
					</li>
					<? endforeach; ?>
				</ul>
				<? endif; ?>
			</li>
			<? endforeach; ?>
		</ul>
		<h1 class="titulo" style="font-size: 12px;">Stock Total: <?= $stox; ?></h1>
		<? endif; ?>
	</div>
	<div class="botones">
		<? if (in_array($authUser['id'], array(2,3))) : ?>
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $producto['Producto']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $producto['Producto']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $producto['Producto']['id'])); ?>
		<? endif; ?>
	</div>
</div>
<script>
$(document).ready(function()
{
	$('.lista-tiendas').click(function(evento)
	{
		evento.preventDefault();
		var id = $(this).data('id');
		if ($('.tienda-' + id).is(':visible'))
		{
			$('.tienda-' + id).hide();
		}
		else
		{
			$('.tienda-' + id).show();
		}
	});
});
</script>
