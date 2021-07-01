<style type="text/css" media="all">
.clase-input {
	background-color: #fff !important;
}
button.btn {
	padding: 10px 5px;
	background-color: #0080ff;
	color: #fff;
	font-weight: bold;
}
button.btn:hover {
	background-color: #409fff;
}
button.btn.btn-block {
	width: 100%;
}
.izquierda {
	float: left;
	width: 400px;
}
.derecha {
	float: left;
	width: 250px;
	margin-left: 10px;
}
.datosCompra {
	width:100%;
	background: #fff;
	padding: 5px 10px;
	border: 1px solid #0080c0;
	border-radius: 5px;
	margin-bottom: 10px;
}
.datosCompra td {
	padding: 0;
	text-align: left;
}
.datosCompra td b {
	color: #0080c0;
}
.datosCompra td.linea {
	border-bottom: 1px solid #0080c0;
}
.datosCompra td.text-right {
	text-align: right;
}
.btn-devolucion {
	float: right;
	padding: 5px;
	background: #0080ff;
	color: #fff !important;
	font-weight: bold;
	text-decoration: none !important;
	border-radius: 3px;
	border: 1px solid #0080c0;
}
.btn-devolucion:hover {
	opacity: 0.7;
}
.btn-devolucion.btn-aceptar {
	background: #008000;
	border: 1px solid #004000;
}
.btn-devolucion.btn-cancelar {
	background: #800000;
	border: 1px solid #400000;
	margin-right: 10px;
}
.btn-devolucion.disabled {
	opacity: 0.4;
	cursor: no-drop;
}
li.formulario {
	display: none;
}
.ui-dialog-content p {
	font-weight: bold;
}
.ui-dialog-content ul {
	margin-top: 10px;
	list-style: inside;
}
</style>
<div class="col02">
	<h1 class="titulo">Paso a Devolución</h1>
	<div class="previsualizar">
		<ul>
			<li class="extendido">
				<h2>Datos de la compra</h2>
			</li>
			<li class="extendido">
				<div class="izquierda">
					<table class="datosCompra">
						<tr>
							<td><b>nombre cliente:</b></td>
						</tr>
						<tr>
							<td class="linea">
								<?= $compra['Usuario']['nombre'].' '.$compra['Usuario']['apellido_paterno']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td><b>rut:</b></td>
						</tr>
						<tr>
							<td class="linea">
								<?= $compra['Usuario']['rut']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td><b>email cliente:</b></td>
						</tr>
						<tr>
							<td class="linea">
								<?= $compra['Usuario']['email']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td><b>despacho:</b></td>
						</tr>
						<tr>
							<td class="linea">
								<?= $compra['Direccion']['calle'].' #'.$compra['Direccion']['numero']; ?><?= ($compra['Direccion']['depto'])?', depto '.$compra['Direccion']['depto']:''; ?> - <?= $compra['Comuna']['nombre']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td><b>telefono:</b></td>
						</tr>
						<tr>
							<td class="linea">
								<?= $compra['Direccion']['telefono']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td><b>celular:</b></td>
						</tr>
						<tr>
							<td>
								<?= $compra['Direccion']['celular']; ?>&nbsp;
							</td>
						</tr>
					</table>
				</div>
				<div class="derecha">
					<table class="datosCompra">
						<tr>
							<td class="linea"><b>estado:</b></td>
							<td class="linea text-right">
								<?
								$estado = 'no pagado';
								if ($compra['Compra']['estado'] == 1)
									$estado = 'pagado';
								elseif ($compra['Compra']['estado'] == 2)
									$estado = 'anulado';
								elseif ($compra['Compra']['estado'] == 3)
									$estado = 'en devolución';
								elseif ($compra['Compra']['estado'] == 4)
									$estado = 'devuelto';
								elseif ($compra['Compra']['estado'] == 5)
									$estado = 'pendiente';
                                elseif ($compra['Compra']['estado'] == 11)
                                    $estado = 'anulado por stock';
								?>
								<?= $estado; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="linea"><b>n° boleta:</b></td>
							<td class="linea text-right">
								<?= $compra['Compra']['boleta']; ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="linea"><b>id (invoice):</b></td>
							<td class="linea text-right">
								<?= $compra['Compra']['numId']; ?>&nbsp;
							</td>
						</tr>
						<tr>
                            <td class="linea"><b>cod. despacho:</b></td>
                            <td class="linea text-right">
                                <?= $compra['Compra']['cod_despacho']; ?>&nbsp;
                            </td>
                        </tr>
						 <tr>
                            <td class="linea"><b>Marca:</b></td>
                            <td  class="linea text-right">
                                <?= $compra['Pago']['marca']; ?>&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td class=""><b>Tipo:</b></td>
                            <td  class=" text-right">
                                <?= $compra['Pago']['estado']; ?>&nbsp;
                            </td>
                        </tr>
					</table>
					<table class="datosCompra">
						<tr>
							<td class="linea"><b>subtotal:</b></td>
							<td class="linea text-right">
								<?= $this->Shapeups->moneda($compra['Compra']['subtotal']); ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="linea"><b>iva:</b></td>
							<td class="linea text-right">
								<?= $this->Shapeups->moneda($compra['Compra']['iva']); ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="linea"><b>neto:</b></td>
							<td class="linea text-right">
								<?= $this->Shapeups->moneda($compra['Compra']['neto']); ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="linea"><b>descuento:</b></td>
							<td class="linea text-right">
								- <?= $this->Shapeups->moneda($compra['Compra']['descuento']); ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="linea"><b>valor despacho:</b></td>
							<td class="linea text-right">
								<?= $this->Shapeups->moneda($compra['Compra']['valor_despacho']); ?>&nbsp;
							</td>
						</tr>
						<tr>
							<td><b>total:</b></td>
							<td class="text-right">
								<?= $this->Shapeups->moneda($compra['Compra']['total']); ?>&nbsp;
							</td>
						</tr>
					</table>
				</div>
			</li>
		</ul>
	</div>
	<h2 class="subtitulo">Productos</h2>
	<? foreach ($productos as $producto) : ?>

	
		<div class="previsualizar">
			<ul>
				<li class="extendido">

        <?php
				$precio_venta = $producto['ProductosCompra']['valor'];
				if ($producto['Descuento']['id'])
				{
					$descontar = 0;
					if ($producto['Descuento']['tipo'] == 'POR')
					{
						if ($producto['Descuento']['descuento'])
						{
							$descontar = ($producto['ProductosCompra']['valor'] * $producto['Descuento']['descuento']) / 100;
							if ( ($descontar % 10) > 0 )// redondea descuento
								$descontar = (((int)($descontar/10))*10)+10;
							else
								$descontar = ((int)($descontar/10))*10;
						}
					}
					elseif ($producto['Descuento']['descuento'])
					{
						$descontar = $producto['Descuento']['descuento'];
					}
					$precio_venta = $precio_venta-$descontar;
					if ($precio_venta <= 0)
						$precio_venta = 0;
				}
				?>
					<span><img src="<?= $this->Shapeups->imagen('Producto/'.$producto['Producto']['id'].'/mini_'.$producto['Producto']['foto']); ?>" /></span>
					<b><?= $producto['Producto']['nombre']; ?></b> (<?= $producto['Producto']['codigo_completo']; ?>)
					<br /><b>talla:</b> <?= $this->Shapeups->talla_ropa($producto['ProductosCompra']['talla']); ?>
					<br /><b>valor pagado:</b> <?= $this->Shapeups->moneda($precio_venta); ?>
                    <? if($producto['Devolucion']['codigo']):?>
                        <br /><b>Codigo de devolucion:</b> <?= $producto['Devolucion']['codigo']; ?>
                    <? else:?>
                        <br /><b>Codigo de devolucion: No aplica</b>
                    <?endif;?>

				</li>
				<? if ($producto['ProductosCompra']['devolucion']) : ?>
					<?= $this->Form->create('Compra', array('action' => 'admin_procesar_devolucion','inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => false))); ?>
						<li>
							<span>Devolución:</span>si
							<?= $this->Form->hidden('Devolucion.id', array('value' => $producto['Devolucion']['id'])); ?>
							<?= $this->Form->hidden('Devolucion.compra_id', array('value' => $producto['Devolucion']['compra_id'])); ?>
							<?= $this->Form->hidden('Devolucion.productos_compra_id', array('value' => $producto['Devolucion']['productos_compra_id'])); ?>
						</li>
						<li>
							<span>Tipo Cambio:</span>
							<?
							//1 => 'Devolución del producto',
							//2 => 'Cambio de producto',
							//3 => 'Cambio por talla',
							if ($producto['Devolucion']['tipo'] == 1)
								echo 'Devolución del producto';
							elseif($producto['Devolucion']['tipo'] == 2)
								echo 'Cambio de producto';
							elseif($producto['Devolucion']['tipo'] == 3)
								echo 'Cambio por talla';
							?>
						</li>

						<? if ($producto['Devolucion']['estado'] == 0) : ?>
							<li><span>estado</span>solicitud pendiente</li><li><span>Producto Nueva:</span><?= $producto['Devolucion']['producto']; ?></li>
							<li><span>razón</span><?= $producto['Devolucion']['razon']; ?></li>
                            <? if($producto['Devolucion']['tipo'] != 3) :?>
                            <li><span>Talla Nueva:</span>No aplica</li>
                            <?else :?>
                            <li><span>Talla Nueva:</span><?= $producto['Devolucion']['talla']; ?></li>
                            <?endif;?>
							<li class="extendido"><span>cambiar estado:</span>
								<?
								$options = array(
									'type' => 'select',
									'options' => array(
										0 => 'pendiente',
										1 => 'aprobada/Agregada',
										2 => 'rechazada'
									),
                                    'class'=>'DevolucionEstado clase-input'
								);

							if ($producto['Devolucion']['estado'])
									$options['value'] = $producto['Devolucion']['estado'];
								echo $this->Form->input('Devolucion.estado',$options); ?>
                            </li>
                                <? if(isset($producto) && $tipoPago == 'VD') :?>
                                <h3> Datos de transferencia:</h3>
                                <li>
                                    <span>Nombre:</span>
                                    <?= $producto['Devolucion']['nombre_titular']; ?>
                                </li>
                                <li>
                                    <span>Apellido:</span>
                                    <?= $producto['Devolucion']['apellido_titular']; ?>
                                </li>
                                <li>
                                    <span>Run:</span>
                                    <?= $producto['Devolucion']['run_titular']; ?>
                                </li>
                                <li>
                                    <span>Tipo cuenta:</span>
                                    <?= $producto['Devolucion']['tcuenta_titular']; ?>
                                </li>
                                <li>
                                    <span>N° cuenta:</span>
                                    <?= $producto['Devolucion']['ncuenta_titular']; ?>
                                </li>
                                <li>
                                    <span>Banco:</span>
                                    <?= $producto['Devolucion']['banco_titular']; ?>
                                </li>
                                <li rel="observacion" style="display: none">
                                    <span> Ingrese motivo:</span>
                                    <p><?= $this->Form->input('Devolucion.observaciones',array('type' => 'textarea')); ?></p>

                                </li>

                                <? else :?>
                                <h3> Datos de transferencia: Desconocidos</h3>
                                <? endif?>

							<li class="extendido">
								<button type="submit" class="btn btn-block" rel="guardarDevolucion">guardar</button>
							</li>
						<?= $this->Form->end(); ?>
					<? elseif ($producto['Devolucion']['estado'] == 1) : ?>
						<li ><span>Estado</span>Producto Aceptado</li><li><span>Estilo</span><?php echo (isset($producto['Devolucion']['producto']) && $producto['Devolucion']['producto']!='')? $producto['Devolucion']['producto']:$producto['Producto']['codigo_completo']; ?></li>
						<li> <span>Razon</span><?= $producto['Devolucion']['razon']; ?></li>

                        <? if($producto['Devolucion']['tipo'] != 3) :?>
                            <li><span>Talla Nueva:</span>No aplica</li>
                        <?else :?>
                            <li><span>Talla Nueva:</span><?= $this->Shapeups->talla_ropa($producto['Devolucion']['talla']); ?></li>
                        <?endif;?>
                        <? if(isset($producto) && $tipoPago == 'VD') :?>
                            <h3> Datos de transferencia:</h3>
                            <li>
                                <span>Nombre:</span>
                                <?= $producto['Devolucion']['nombre_titular']; ?>
                            </li>
                            <li>
                                <span>Apellido:</span>
                                <?= $producto['Devolucion']['apellido_titular']; ?>
                            </li>
                            <li>
                                <span>Run:</span>
                                <?= $producto['Devolucion']['run_titular']; ?>
                            </li>
                            <li>
                                <span>Tipo cuenta:</span>
                                <?= $producto['Devolucion']['tcuenta_titular']; ?>
                            </li>
                            <li>
                                <span>N° cuenta:</span>
                                <?= $producto['Devolucion']['ncuenta_titular']; ?>
                            </li>
                            <li>
                                <span>Banco:</span>
                                <?= $producto['Devolucion']['banco_titular']; ?>
                            </li>
                        <? else :?>
                            <h3> Datos de transferencia: Desconocidos </h3>
                        <? endif?>
					

						<!--<li class="extendido">
							<button type="button" class="btn btn-block" rel="enviarNotificacion" data-id="<?= $producto['Devolucion']['id']; ?>">Enviar notificación</button>
						</li> -->
					<? else : ?>
						<li class="extendido"><span>estado</span>solicitud rechazada</li>
						<li class="extendido"><span>razón</span><?= $producto['Devolucion']['razon']; ?></li>
						<li class="extendido">
							<span>codigo:</span>
							<?= $producto['Devolucion']['codigo']; ?>
							</li>
						<li class="extendido">
							<span>fecha:</span>
							<?= $producto['Devolucion']['fecha']; ?></li>
						<li class="extendido">
							<span>hora:</span>
							<?= $producto['Devolucion']['hora']; ?>
						</li>
						<li class="extendido">
							<span>observaciones:</span>
							<?= $producto['Devolucion']['observaciones']; ?>
						</li>
						<!--<li class="extendido">
							<a href="button" class="btn btn-block" rel="enviarNotificacion" data-id="<?= $producto['Devolucion']['id']; ?>">Enviar notificación</button>
						</li>-->
					<? endif; ?>
				<? else: ?>
					<li class="extendido">
						<span>devolución:</span>no
					<!--	<a class="btn-devolucion" rel="btnCambioProducto" href="#">iniciar devolución</a> -->
					</li>
					<li class="extendido formulario">
						<?= $this->Form->create('Compra', array('inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => false))); ?>
							<table>
								<tr>
									<td><b>Tipo de cambio:</b></td>
									<td rel="tallas">
										<?= $this->Form->hidden('id', array('value' => $compra['Compra']['id'])); ?>
										<?= $this->Form->hidden('productos_compra_id', array('value' => $producto['ProductosCompra']['id'])); ?>
										<?= $this->Form->hidden('producto_id', array('value' => $producto['Producto']['id'])); ?>
										<?
										$options = array(
											'type' => 'select',
											'options' => array(
												1 => 'Devolución del producto',
												2 => 'Cambio de producto',
												3 => 'Cambio por talla',
											),
											'empty' => '- seleccione tipo de cambio'
										);
										echo $this->Form->input('tipo',$options);
										?>
									</td>
								</tr>
								<tr>
									<td><b>Motivo:</b></td>
									<td rel="tallas">
										<?= $this->Form->input('razon',array('type' => 'textarea')); ?>
									</td>
								</tr>
							</table>
							<a class="btn-devolucion btn-aceptar" rel="guardarCambioProducto" href="#">pasar a devolución</a>
							<a class="btn-devolucion btn-cancelar" rel="cancelarCambioProducto" href="#">cancelar devolución</a>
						<?= $this->Form->end(); ?>
					</li>
				<? endif; ?>
			</ul>
		</div>
	<? endforeach; ?>
</div>
<script>
    $('.DevolucionEstado').change(function () {
       //select[rel="observacion"]
        if($( this ).val() == 2){
            $(this).parent().siblings('li[rel="observacion"]').show()
        }else{
            $(this).parent().siblings('li[rel="observacion"]').hide()
        }
    })
$('a[rel="btnCambioProducto"]').live('click',function(e) {
	e.preventDefault();
	var boton = $(this),
		target = $(this).parent().siblings('li.formulario');
	if (! target.length) {
		return false;
	}
	if (boton.hasClass('disabled')) {
		return false;
	}
	target.slideDown(800,function() {
		boton.addClass('disabled');
	});
});
$('a[rel="btnrechazado"]').live('click',function(e) {
    e.preventDefault();
    var boton = $(this),
        target = $(this).parent().siblings('li.formulario');
    if (! target.length) {
        return false;
    }
    if (boton.hasClass('disabled')) {
        return false;
    }
    target.slideDown(800,function() {
        boton.addClass('disabled');
    });
});

$('a[rel="cancelarCambioProducto"]').live('click',function(e) {
	e.preventDefault();
	var	formulario = $(this).parents('form#CompraAdminDevolucionForm'),
		target = $(this).parents('li.formulario'),
		boton = target.parent().find('a[rel="btnCambioProducto"]');
	if (! formulario.length) {
		return false;
	}
	if (! target.length) {
		return false;
	}
	if (! boton.hasClass('disabled')) {
		return false;
	}
	target.slideUp(800,function() {
		formulario[0].reset();
		boton.removeClass('disabled');
	});
});

$('a[rel="guardarCambioProducto"]').live('click',function() {
	var formulario = $(this).parents('form#CompraAdminDevolucionForm');
	formulario.find('.has-error').removeClass('has-error');

	if (! formulario.find('#CompraId').val()) {
		return false;
	}
	if (! formulario.find('#CompraProductosCompraId').val()) {
		return false;
	}
	if (! formulario.find('#CompraProductoId').val()) {
		return false;
	}

	var txtAlert = '<p>Lo sentimos. Para continuar: </p><ul>';
	if (! formulario.find('#CompraTipo').val()) {
		formulario.find('#CompraTipo').parent().addClass('has-error');
		txtAlert+= '<li>seleccione el tipo de cambio</li>';
	}
	if (! formulario.find('#CompraRazon').val()) {
		formulario.find('#CompraRazon').parent().addClass('has-error');
		txtAlert+= '<li>ingrese una razón</li>';
	}
	if (formulario.find('.has-error').length) {
		alerta(txtAlert+'</ul>');
		return false;
	}

	txtAlert = 'Lo sentimos. Se produjo un problema al intentar guardar los datos de su cambio. Por favor intentelo nuevamente.';

	$.ajax({
		async	: true,
		dataType: 'json',
		type	: 'POST',
		url		: webroot + 'compras/ajax_devolucion_producto',
		data	: formulario.serialize(),
		success: function(respuesta) {
			if (respuesta.estado) {
				if (respuesta.estado == 'OK') {
					if (respuesta.mensaje) {
						alerta(respuesta.mensaje);
					} else {
						alerta('La solicitud de cambio ha sido enviada exitosamente.');
					}
					location.reload();
				} else if (respuesta.mensaje) {
					alerta(respuesta.mensaje);
				} else {
					alerta(txtAlert);
				}
			} else {
				alerta(txtAlert);
			}
		},
		error: function() {
			alerta(txtAlert);
		}
	});
	return false;
});
</script>