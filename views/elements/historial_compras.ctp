<style>
.tooltip.in {
	opacity: 1;
}
.tooltip.top .tooltip-arrow {
	left: 25%;
}
.tooltip.default .tooltip-inner {

}
.tooltip.success .tooltip-inner {
	background-color: #d9ecd8;
	color: #078e00;
	font-weight: 600;
}
.tooltip.success.top .tooltip-arrow {
	border-top-color: #d9ecd8;	
}
.tooltip.info .tooltip-inner {
	background-color: #d1f4ff;
	color: #1d94b7;
	font-weight: 600;
}
.tooltip.info.top .tooltip-arrow {
	border-top-color: #d1f4ff;	
}
</style>

<legend class="text-info">Listado de compras realizadas</legend>
<? foreach( $compras as $index => $compra ) : ?>
	<?
		// INICIA SWITCH ACTIVAR CAMBIO (desactivado)
		$activar_cambio = false;
		$dias_caducidad = 90; // 95 dias
		$caducidad = date('Y-m-d H:i:s', strtotime($compra['Compra']['modified']) + (60 * 60 * 24 * $dias_caducidad));
		$estado = array(
			'estado' => 'No pagada',
			'class' => 'danger',
			'tooltip' => ''
		);

		if ( $compra['Compra']['estado'] == 1 )
		{
			$estado = array_merge($estado, array(
				'estado' => 'En Revision',
				'class' => 'default',
				'tooltip' => 'Este proceso contempla la revisión del pago, y preparación del producto previo al despacho.'
			));
			if ( $compra['Compra']['picking_number'] )
			{
				$estado = array_merge($estado, array(
					'estado' => 'Procesando despacho',
					'class' => 'info',
					'tooltip' => 'El o los productos ya han sido localizados, se encuentran en proceso de embalaje y etiquetado. Este proceso puede tomar 1 a 2 días hábiles.'
				));
				if ( $compra['Compra']['boleta'] && $compra['Compra']['cod_despacho'] )
				{
					$estado = array_merge($estado, array(
						'estado' => 'Despachada',
						'class' => 'success',
						'tooltip' => 'La orden ya se encuentra en poder de la empresa de despacho, camino a ser entregada en la dirección registrada. Recibirá un email con el número de seguimiento.'
					));
				}
			}
		}
		elseif ( $compra['Compra']['estado'] == 2 )
		{
			$estado = array_merge($estado, array(
				'estado' => 'Anulada'
			));
		}
		elseif ( $compra['Compra']['estado'] == 3 )
		{
			$estado = array_merge($estado, array(
				'estado' => 'En devolución',
				'class' => 'warning'
			));
		}
		elseif ( $compra['Compra']['estado'] == 4 )
		{
			$estado = array_merge($estado, array(
				'estado' => 'Devuelto',
				'class' => 'warning'
			));
		}
        elseif ( $compra['Compra']['estado'] == 11 )
        {
            $estado = array_merge($estado, array(
                'estado' => 'Anulada',
                
            ));
        }
        elseif ( $compra['Compra']['estado'] == 12 )
        {
            $estado = array_merge($estado, array(
                'estado' => 'En Evaluación',

            ));
        }
	?>
	<table class="table table-bordered table-hover">
		<tbody>
			<tr>
				<td class="col-md-6">Compra número (Nº):</td>
				<td><b><?= $compra['Compra']['id']; ?></b></td>
			</tr>
			<tr>
				<td>Número de seguimiento (Nº):</td>
				<td><b><?= ( isset($compra['Compra']['cod_despacho']) && $compra['Compra']['cod_despacho'] ) ? $compra['Compra']['cod_despacho'] : 'XXXXXXXXXX'; ?></b></td>
			</tr>
			<tr>
				<td>Número de transacción (Nº):</td>
				<td><b><?= ( isset($compra['Pago'][0]['codigo']) && $compra['Pago'][0]['codigo'] ) ? $compra['Pago'][0]['codigo'] : 'xx-xxx-xxx-x'; ?></b></td>
			</tr>
			<tr>
				<td>Número de tarjeta de pago (Nº):</td>
				<td><b>xx-xxx-<?= ( isset($compra['Pago'][0]['numeroTarjeta']) && $compra['Pago'][0]['numeroTarjeta'] ) ? $compra['Pago'][0]['numeroTarjeta'] : 'xxx'; ?></b></td>
			</tr>
			<tr>
				<td>Número de boleta (Nº):</td>
				<td><b><?= ( isset($compra['Compra']['boleta']) && $compra['Compra']['boleta'] ) ? $compra['Compra']['boleta'] : 'XXX.XXX.XXX'; ?></b></td>
			</tr>
			<tr>
				<td>Fecha:</td>
				<td><b><?= date('d-m-Y', strtotime($compra['Compra']['created'])); ?></b></td>
			</tr>
			<tr>
				<td>Cantidad de productos:</td>
				<td><b><?= count($compra['Producto']); ?></b></td>
			</tr>
			<tr>
				<td>Estado:</td>
				<td>
					<div class="col-xs-12">
						<div class="row">
							<h4 class="nomargin">
								<? if (isset($estado['tooltip']) && $estado['tooltip']) : ?>
								<span class="label label-<?= $estado['class']; ?>" data-toggle="tooltip" title="<?= $estado['tooltip']; ?>" data-template='<div class="tooltip <?= $estado['class']; ?>" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'>
									<?= $estado['estado']; ?>
								</span>
								<? else : ?>
								<span class="label label-<?= $estado['class']; ?>">
									<?= $estado['estado']; ?>
								</span>
								<? endif; ?>
								
							</h4>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>Valor del despacho:</td>
				<td><b><?= $this->Shapeups->moneda($compra['Compra']['valor_despacho']); ?></b></td>
			</tr>
			<tr>
				<td>Precio <b>TOTAL</b>:</td>
				<td><b><?= $this->Shapeups->moneda($compra['Compra']['total']); ?></b></td>
			</tr>
			<tr>
				<td colspan="2" class="text-center info">
					<a href="#" class="btn btn-primary ver-detalle" data-id="<?= $compra['Compra']['id']; ?>">Ver detalles</a>
                    <?php if($compra['Compra']['cod_despacho']) :?>
                    <?php  $token = bin2hex($compra['Compra']['id'].'/'.$email) ?>
                    <a href="compras/estado_despacho/<?= $token ?>" class="btn btn-success">Ver estado de despacho</a>
                    <?php endif; ?>

				<?php if($caducidad > date('Y-m-d H:i;s') && $compra['Compra']['cod_despacho'] && $estado['estado'] != 'Procesando despacho') :?>
					<a href="<?= $this->Html->url(array('controller' => 'compras', 'action' => 'devolucion', $compra['Compra']['id'])); ?>" class="btn btn-warning btn-devolucion">Devolucion</a>
				<?php endif; ?>

				<?php if($compra['Compra']['boleta_pdf'] !='') :?>
					<a  class="btn btn-success" href="https://www.skechers.cl/boletas_skechers/<?=$compra['Compra']['boleta_pdf'];?>" target="_blank" title="Boleta">Boleta</a>

				<!--	<a href="https://www.skechers.cl/boletas_skechers/'.$compra['Compra']['boleta_pdf'].'" target="_blank" title="Boleta"> -->
				<?php endif; ?>
				</td>


			</tr>
		</tbody>
		</tbody>
	</table>

	<!--Tabla detalles (oculta)-->
	<div class="tab-detal<?= $compra['Compra']['id']; ?>" style="display: none;">
		<table class="table table-bordered">
			<tr class="warning">
				<td colspan="6">
					<h3 class="nomargin text-center"><b>Detalle de la compra realizada</b></h3>
				</td>
			</tr>
			<? foreach( $compra['Producto'] as $producto ) : ?>
			<tr class="warning">
				<td class="col-md-1">
					<img src="<?= $this->Shapeups->imagen('Producto/'.$producto['id'].'/mini_'.$producto['foto']); ?>" class="thumbnail" width="100%">
				</td>
				<td>
					<p class="nomargin"><b><?= $producto['nombre']; ?></b></p>
					<p class="nomargin">Color: <b><?= $producto['Color']['nombre']; ?></b></p>
					<p class="nomargin">COD: <b><?= $producto['codigo']; ?> <?= $producto['Color']['codigo']; ?></b></p>
				</td>
				<td>
					<h4 class="text-center">Talla: <b><?= $producto['ProductosCompra']['talla']; ?></b></h4>
				</td>
				<td>
					<h4 class="text-center">
						<b><?= $this->Shapeups->moneda($producto['ProductosCompra']['valor']); ?></b>
					</h4>
				</td>
                <!--
                <td>
                    <h4 class="text-center">
                        <a href="solicitar_devolucion" class="btn btn-warning">Socilitar devolucion</a>
                    </h4>
                </td>
                -->
			</tr>
			<? endforeach; ?>
		</table>
	</div>
	<!---->
<? endforeach; ?>
<script type="text/javascript">
$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip({
		container: false,
		placement: 'top'
	});
});
</script>