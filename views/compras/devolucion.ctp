<!--MODAL CAMBIO PRODUCTO-->

<div class="modal fade" id="modalCambioProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Devolución de producto</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-warning" rel="alertaFormulario" style="display:none;"></div>
				<?= $this->Form->create('Compra',array('class' => 'form-horizontal','inputDefaults' => array('class' => 'form-control','div' => false,'label' => false))); ?>
				<table class="table">
					<thead>
						<tr>
							<th colspan="2" rel="nombre"></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Código:</td>
							<td rel="codigo" id="codigo_id"></td>
						</tr>
						<tr>
							<td>Color:</td>
							<td rel="color"></td>
						</tr>
						<tr>
							<td>Talla:</td>
							<td rel="talla"></td>
						</tr>
						<tr>
							<td>Valor <small style="color: #999;">(pagado)</small>:</td>
							<td rel="valor"></td>
						</tr>
						<tr>
							<td>Tipo:</td>
							<td rel="tallas">
								<div class="form-group">
									<?= $this->Form->hidden('id'); ?>
									<?= $this->Form->hidden('compra'); ?>
									<?= $this->Form->hidden('producto'); ?>
                                    <?= $this->Form->hidden('tipoPago'); ?>
									<?
									$options = array(
										'type' => 'select',
										'options' => $tipos_devolucion,
										'empty' => '- seleccione tipo de devolución'
									);
									echo $this->Form->input('tipo',$options);
									?>
								</div>
							</td>
						</tr>
                        <? if($tipoPago == 'VD'): ?>
                            <thead>
                                <tr class="pagos" style="display: none;">
                                    <th colspan="2"> Ingrese los datos de su cuenta para realizar el deposito:</th>
                                </tr>
                            </thead>
                            <tr class="pagos" style="display: none;">
                                <td>Nombre titular:</td>
                                <td rel="tallas">
                                    <?= $this->Form->input('nombre_titular'); ?>
                                </td>
                            </tr>
                            <tr class="pagos" style="display: none;">
                                <td>Apellido titular:</td>
                                <td rel="tallas">
                                    <?= $this->Form->input('apellido_titular'); ?>
                                </td>
                            </tr>
                            <tr class="pagos" style="display: none;">
                                <td>Run titular:<br> (Sin puntos y con Guion)</td>
                                <td rel="tallas">
                                    <?= $this->Form->input('run_titular'); ?>
                                    <span rel="run" class="error text-danger" style="display: none;">El run es incorrecto!</span>
                                </td>
                            </tr>
                            <tr class="pagos" style="display: none;">
                                <td>Tipo de cuenta:</td>
                                <td rel="option">
                                    <?
                                    $tipo_cuenta = array(
                                            'Cuenta Corriente' => 'Cuenta Corriente',
                                            'Cuenta Vista' => 'Cuenta Vista'
                                    );
                                    $options = array(
                                    'type' => 'select',
                                    'options' => $tipo_cuenta,
                                    'empty' => '- seleccione tipo de cuenta'
                                    ); ?>
                                    <?= $this->Form->input('tcuenta_titular',$options); ?>
                                </td>
                            </tr>
                            <tr class="pagos" style="display: none;">
                                <td>Banco:</td>
                                <td rel="tallas">
                                    <?
                                    $tipo_banco = array(
                                        'Banco Estado' => 'Banco Estado',
                                        'Banco de Chile' => 'Banco de Chile / Edwards',
                                        'Banco de Crédito e Inversiones (BCI)' => 'Banco de Crédito e Inversiones (BCI)',
                                        'Banco Bice' => 'Banco Bice',
                                        'HSBC Bank' =>  'HSBC Bank',
                                        'Banco Santander' => 'Banco Santander',
                                        'Itaú Corpbanca' => 'Itaú / Corpbanca',
                                        'Banco Security' => 'Banco Security',
                                        'Scotiabank' => 'Scotiabank',
                                        'Scotiabank Azul' => 'Scotiabank Azul(Ex BBVA)',
                                        'Banco Falabella' => 'Banco Falabella',
                                        'Banco Ripley' => 'Banco Ripley',
                                        'Banco Consorcio' => 'Banco Consorcio'

                                    );
                                    $options = array(
                                        'type' => 'select',
                                        'options' => $tipo_banco,
                                        'empty' => '- seleccione tipo de banco'
                                    ); ?>
                                    <?= $this->Form->input('banco_titular',$options); ?>
                                </td>
                            </tr>
                            <tr class="pagos" style="display: none;">
                                <td>N° de cuenta:</td>
                                <td rel="tallas">
                                    <?= $this->Form->input('ncuenta_titular'); ?>
                                </td>
                            </tr>
                        <? endif ?>

						
							<tr id="producto" style="display: none;">
							<td>Seleccione Producto:</td>
							<td rel="tallas">
								<div class="form-group">
									<?
									$options = array(
										'type' => 'select',
										'options' => array(
											),
										'placeholder' => 'Ingrese Codigo Producto'
									);
									echo $this->Form->input('codigo_producto',$options);
									?>
								</div>
							</td>
						</tr>
						<tr id="talla" style="display: none;">
							<td>Seleccione Talla:</td>
							<td rel="tallas">
								<div class="form-group">
									<?
									$options = array(
										'type' => 'select',
										'options' => array(
										),
										'empty' => '- seleccione Talla a Cambiar'
									);
									echo $this->Form->input('talla',$options);
									?>
								</div>
							</td>
						</tr>
						<tr id="talla2" style="display: none;">
							<td>Seleccione Talla:</td>
							<td rel="tallas">
								<div class="form-group">
									<?
									$options = array(
										'type' => 'text',
										'options' => array(
										),
										'placeholder' => 'Ingrese Talla'
									);
									echo $this->Form->input('talla2',$options);
									?>
								</div>
							</td>
						</tr>
						<tr class="area" style="display: none">

							<td class="motivo">Motivo de devolución:</td>
							<td  rel="tallas">
								<div class="form-group">
									<?= $this->Form->input('razon',array('type' => 'textarea')); ?>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<?= $this->Form->end(); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="guardarCambioProducto">Solicitar Devolucion</button>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="panel panel-default">
		  <div class="panel-body nopadding-top">
		  	<div class="volver-top">
		  		<div class="row">
			  		<div class="col-md-12">
						<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'inicio')); ?>" class="btn btn-info btn-xs" title="Seguir comprando"><span>Volver a catálogo</span></a>
			  		</div>
			  	</div>
		  	</div>
		  	<div class="row">
		  		<div class="col-md-12">
					<h3>Estimado <b><?= ( isset($authUser) )? $authUser['nombre'].' '.$authUser['apellido_paterno'].' '.$authUser['apellido_materno']:'Sr(a)'; ?></b>.</h3>
							<p>
					Para realizar una devolución de tu pedido tienes 30 días desde la fecha de recepción de tu compra. <br />
Al iniciar el proceso desplegará un formulario para ingresar el motivo de tu devolución. Una vez completado, podrás descargarlo e imprimirlo. <br />
Pega el formulario en la caja de cartón café del despacho (no en la caja del producto) y entrégala en cualquier sucursal de Correos Chile. Ellos realizaran el envío a nuestro Centro de Distribución (CD) sin costo para ti. Una vez que nuestro CD confirme que el/los producto(s) está(n) en perfectas condiciones y sin uso, con etiquetas y empaque original (a excepción de garantías), procederemos con el reembolso. <br /><br />

El valor del producto será devuelto de acuerdo al medio de pago original, en un plazo máximo de 15 días hábiles.<br />
Si la devolución no cumple con los requisitos será rechazada y el /los producto(s) será(n) retornado(s) sin costo a la dirección original de la compra.
						
					</p>
		  		</div>
		  	</div>
		  	<div class="row">
		  		<div class="col-md-12">
		  			<div class="table-responsive">
						<table id="devoluciones" class="table table-skechers table-condensed">
							<thead>
								<tr>
									<th colspan="7">
										<h3>
											Datos de la compra
										</h3>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr class="plomo">
									<td class="col-xs-1">Foto</td>
									<td class="text-left col-xs-4">Nombre</td>
									<td class="text-center col-xs-1">Color</td>
									<td class="text-center col-xs-1">Código</td>
									<td class="text-center col-xs-1">Talla</td>
									<td class="text-center col-xs-2">Valor</td>
									<td class="text-center col-xs-2"></td>
								</tr>
								<? if ($productos) : ?>
									<? foreach ($productos as $producto ) : ?>
										<tr>
											<td>
												<img src="<?= $this->Shapeups->imagen('Producto/'.$producto['Producto']['id'].'/full_'.$producto['Producto']['foto']); ?>" width="100%" />
											</td>
											<td class="text-left">
												<h4 class="nomargin text-info">
													<b><?= $producto['Producto']['nombre']; ?></b>
												</h4>
											</td>
											<td class="text-center">
												<b><?= $producto['Color']['nombre']; ?></b>
											</td>
											<td class="text-center">
												<b><?= $producto['Producto']['codigo_completo']; ?></b>
											</td>
											<td class="text-center">
												<b>
													<?= $this->Shapeups->talla_ropa($producto['ProductosCompra']['talla']); ?>
												</b>
											</td>
											<td class="text-center" style="color: #a3a3a3;">
												<p>
													<b class="text-info">
														<?= $this->Shapeups->moneda($producto['ProductosCompra']['valor']); ?>
													</b>
												</p>
											</td>
											<td class="text-center">
												<?
												if ($producto['ProductosCompra']['devolucion'] == 1 && $producto['Devolucion']['id'])
												{
													//1 => 'Devolución del producto',
													//2 => 'Cambio de producto',
													//3 => 'Cambio por talla',
													$estado = 'Devolución Solicitada.';
													$contenido = 'Su solicitud de devolucion esta siendo analizada. Por favor espere hasta que sea aceptada.';
													if ($producto['Devolucion']['estado'] == 1)
													{
														if ($producto['Devolucion']['tipo'] == 1)
														{
															$estado = 'Hemos recibido tu solicitud';
															$contenido = 'Código de devolución: '.$producto['Devolucion']['codigo'].'<br>';
															$contenido.='<b>La devolucion ha sido ingresada con exito.</b><br>';
															$contenido.="
															Para hacer efectiva tu devolución:<br>
															<ul>
																<li>Descargue el formulario de devoluciones que recibio junto a su prodyucto en la caja del producto. Si no lo encuentra puede descargarlo haciendo click en el boton de descarga.</li>
																<li>Escriba el código de devolucón (generado arriba) en el formulario de devoluciones</li>
																<li>Entregue el producto junto con el formulario de devoluciones...</li>
																<li>La devolución será activada, una vez que hayamos recibido el producto original en nuestra bodega y corroborado el buen estado del mismo. En ese momento enviaremos un correo electrónico con el código del cupón a la dirección registrada en tu cuenta.</li>
															</ul>
															<div class='alert alert-warning small'>Recordamos que la devolución se realizara el los datos asociados a la compra realizada.</div>
															";
														}
														elseif ($producto['Devolucion']['tipo'] == 2)
														{
															$estado = 'Hemos recibido tu solicitud';
															$contenido = 'Código de devolución: '.$producto['Devolucion']['codigo'].'<br>';
															$contenido.='<b>La devolucion ha sido ingresada con exito.</b><br>';
															$contenido.="
															Para activar el cupón debe proceder a:<br>
															<ul>
																<li>Descargue el formulario de devoluciones que recibio junto a su prodyucto en la caja del producto. Si no lo encuentra puede descargarlo haciendo click en el boton de descarga.</li>
																<li>Escriba el código de devolucón (generado arriba) en el formulario de devoluciones</li>
																<li>Entregue el producto junto con el formulario de devoluciones...</li>
																<li>El cupón será activado, una vez que hayamos recibido el producto original en nuestra bodega y corroborado el buen estado del mismo. En ese momento enviaremos un correo electrónico notificando el estado de la devolución a la dirección registrada en tu cuenta.</li>
															</ul>
															<div class='alert alert-warning small'>Recordamos que el cupón solamente puedes ser utilizado por el mismo usuario que hizo la compra original y dentro de un plazo maximo de 15 días.</div>
															";
														}
														elseif ($producto['Devolucion']['tipo'] == 3)
														{
															$estado = 'Hemos recibido tu solicitud';
															$contenido = 'Código de devolución: '.$producto['Devolucion']['codigo'].'<br>';
															$contenido.='<b>La devolucion ha sido ingresada con exito.</b><br>';
															$contenido.="
															Para activar el cupón debe proceder a:<br>
															<ul>
																<li>Descargue el formulario de devoluciones que recibio junto a su prodyucto en la caja del producto. Si no lo encuentra puede descargarlo haciendo click en el boton de descarga.</li>
																<li>Escriba el código de devolucón (generado arriba) en el formulario de devoluciones</li>
																<li>Entregue el producto junto con el formulario de devoluciones...</li>
																<li>El cupón será activado, una vez que hayamos recibido el producto original en nuestra bodega y corroborado el buen estado del mismo. En ese momento enviaremos un correo electrónico con el código del cupón a la dirección registrada en tu cuenta.</li>
															</ul>
															<div class='alert alert-warning small'>Recordamos que el cupón solamente puedes ser utilizado por el mismo usuario que hizo la compra original y dentro de un plazo maximo de 15 días.</div>
															";
														}
													}
													elseif ($producto['Devolucion']['estado'] == 2)
													{
														$estado = 'Hemos recibido tu solicitud';
														$contenido = 'Código de devolución: '.$producto['Devolucion']['codigo'].'<br>';
														$contenido.='<b>La devolución no ha sido aceptada.</b><br>';
													}
													echo 'Devolucion Solicitada<br><a href="'.$this->Html->url(array("controller" => "compras", "action" => "imprimir_devolucion",$compra['Compra']['id'])).'">Re Imprimir</a>';
												}
												else
												{
													if ($producto['ProductosCompra']['estado'] == 0)
													{
														echo '
														<div class="btn-group-vertical" role="group" aria-label="...">';
														echo '
															<a href="#"
																class="btn btn-primary btn-sm"
																rel="btnCambioProducto"
																data-compra="'.$compra['Compra']['id'].'"
																data-producto="'.$producto['Producto']['id'].'"
																data-id="'.$producto['ProductosCompra']['id'].'"
																data-nombre="'.$producto['Producto']['nombre'].'"
																data-codigo="'.$producto['Producto']['codigo_completo'].'"
																data-color="'.$producto['Color']['nombre'].'"
																data-talla="'.$this->Shapeups->talla_ropa($producto['ProductosCompra']['talla']).'"
																data-valor="'.$producto['ProductosCompra']['valor'].'"
																>
																<i class="fa fa-refresh"></i> Solicitar Devolución
															</a>';
														echo '</div>';
													}
													else
													{
														echo '<button class="btn btn-warning disabled">...</button>';
													}
												}
												?>
											</td>
										</tr>
									<? endforeach; ?>
								<? endif; ?>
								<tr class="info">
									<td colspan="6" class="text-right">Subtotal</td>
									<td class="text-center"><b class="text-info" rel="totalSubtotal"><?= $this->Shapeups->moneda($compra['Compra']['subtotal']); ?></b></td>
								</tr>
								<tr class="info">
									<td colspan="6" class="text-right noborder">Despacho a domicilio</td>
									<td class="text-center noborder"><?= $this->Shapeups->moneda($compra['Compra']['valor_despacho']); ?></td>
								</tr>
								<? if (isset($compra['Compra']['descuento']) && $compra['Compra']['descuento']) : ?>
								<tr class="info">
									<td colspan="6" class="text-right noborder">Descuento</td>
									<td class="text-center noborder">- <?= $this->Shapeups->moneda($compra['Compra']['descuento']); ?></td>
								</tr>
								<? endif; ?>
								<tr class="info">
									<td colspan="6" class="text-right noborder">Precio Neto</td>
									<td class="text-center noborder"><?= $this->Shapeups->moneda($compra['Compra']['neto']); ?></td>
								</tr>
								<tr class="info">
									<td colspan="6" class="text-right noborder">IVA(19%):</td>
									<td class="text-center noborder"><?= $this->Shapeups->moneda($compra['Compra']['iva']); ?></td>
								</tr>
								<tr class="info">
									<td colspan="6" class="text-right divider-total">Total</td>
									<td class="text-center divider-total">
										<h3>
											<b class="text-info">
												<?= $this->Shapeups->moneda($compra['Compra']['total']); ?>
											</b>
										</h3>
									</td>
								</tr>
							</tbody>
						</table>
		  			</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="datosCuenta" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" rel="descargar">Descargar Formulario</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="application/x-javascript">
var productos = [];
		var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];



$(document).ready(function() 
{
    var Fn = {
        // Valida el rut con su cadena completa "XXXXXXXX-X"
        validaRut : function (rutCompleto) {
            rutCompleto = rutCompleto.replace("‐","-");
            if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test( rutCompleto ))
                return false;
            var tmp 	= rutCompleto.split('-');
            var digv	= tmp[1];
            var rut 	= tmp[0];
            if ( digv == 'K' ) digv = 'k' ;

            return (Fn.dv(rut) == digv );
        },
        dv : function(T){
            var M=0,S=1;
            for(;T;T=Math.floor(T/10))
                S=(S+T%10*(9-M++%6))%11;
            return S?S-1:'k';
        }
    }

    if (window.name == "reloader")
    {
        window.name = "no";
        location.reload();
    }
    window.onbeforeunload = function() {
        window.name = "reloader";
    }

	jQuery.curCSS = function(element, prop, val)
	{
    	return jQuery(element).css(prop, val);
	};
	

	$('#guardarCambioProducto').click(function(){
	    var run = $("#CompraRunTitular").val();
        if (run != ''){
            if (Fn.validaRut( run)){
                $('span[rel="run"]').hide();

                alert('Para Devolver su producto, debe imprimir el formulario que se mostrara');
                $('#CompraDevolucionForm').submit();
            } else {
                $('span[rel="run"]').show();
            }
        }

	})
	$('button[rel="infoDevolucion"]').click(function() {
		var target = $('#modalDevolucion'),
			titulo = $(this).data('titulo'),
			contenido = $(this).data('contenido');
		if (! target.length) {
			return false;
		}
		target.find('.modal-title').html(titulo);
		target.find('.modal-body').html(contenido);
	});
	$('button[rel="descargar"]').click(function() {
		alert('Descargar archivo pdf con formulario de devoluciones. !!!');
	});
	$('#CompraTipo').change(function(){
		$('.pagos').hide();
        $('#talla').hide();
		$('#talla2').hide();
        $('.area').hide();
		$('#producto').hide();
		seleccionado = $(this).val();
		id = $("#CompraProducto").val();
	
		 $("#CompraTalla option").remove();
		if(seleccionado == 3)
		{
            $('.motivo').text('Motivo de devolución:');
            $('.area').show();
			$('#talla').show();
            $('.motivo').show();
			$.ajax(
			{
				async	: false,
				type		: 'GET',
				url		: webroot + 'compras/ajax_cambio_talla/'+id,
				success: function( respuesta ) 
				{
					respuesta= JSON.parse(respuesta);
					$("#CompraTalla").append('<option value=0>--Seleccione Talla--</option>');
					$.each(respuesta,function(key, registro) {
   					 $("#CompraTalla").append('<option value='+registro+'>'+registro+'</option>');                                          
					}); 
				}
			});
		}else if(seleccionado == 2)
		{
            $('.motivo').text('Motivo de devolución:');
			$('#talla').show();
            $('.area').show();
            $('.motivo').show();
			$('#producto').show();
			$.ajax(
			{
				async	: false,
				type		: 'GET',
				url		: webroot + 'compras/ajax_cambio_producto/',
				success: function( respuesta ) 
				{
					respuesta= JSON.parse(respuesta);
					$("#CompraCodigoProducto").append('<option value=0>--Seleccione Producto--</option>');
					$.each(respuesta,function(key, registro) {
   					 $("#CompraCodigoProducto").append('<option value='+key+'>'+registro+'</option>');                                          
					}); 
					$("#CompraCodigoProducto").chosen();
					
				}
			});

		}else if(seleccionado == 1 || seleccionado == 4){
            if (seleccionado == 4){
                $('.motivo').text('Descripción de la Falla:')
            }else{
                $('.motivo').text('Motivo de devolución:')
            }
            $('.pagos').show();
            $('.area').show();
        }
		
	})

	$('#CompraCodigoProducto').change(function(){
			id = $("#CompraCodigoProducto").val();
			$('#CompraTalla').find('option').remove().end();
			$.ajax(
			{
				async	: false,
				type		: 'GET',
				url		: webroot + 'compras/ajax_cambio_talla/'+id,
				success: function( respuesta ) 
				{
					respuesta= JSON.parse(respuesta);
					$("#CompraTalla").append('<option value=0>--Seleccione Talla--</option>');
					$.each(respuesta,function(key, registro) {
   					 $("#CompraTalla").append('<option value='+registro+'>'+registro+'</option>');                                          
					}); 
				}
			});

	})
});
</script>