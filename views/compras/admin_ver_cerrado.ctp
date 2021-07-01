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
    <h1 class="titulo">Datos de devolución <?= $devolucion['Devolucion']['codigo']; ?></h1>
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
                                <?= $usuario['Usuario']['nombre'].' '.$usuario['Usuario']['apellido_paterno'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td><b>rut:</b></td>
                        </tr>
                        <tr>
                            <td class="linea">
                                <?= $usuario['Usuario']['rut']; ?>&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td><b>email cliente:</b></td>
                        </tr>
                        <tr>
                            <td class="linea">
                                <?= $usuario['Usuario']['email']; ?>&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td><b>despacho:</b></td>
                        </tr>
                        <tr>
                            <td class="linea">
                                <?= $direccion['Direccion']['calle'].' #'.$direccion['Direccion']['numero']; ?><?= ($direccion['Direccion']['depto'])?', depto '.$direccion['Direccion']['depto']:''; ?> - <?= $direccion['Direccion']['nombre']; ?>&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td><b>telefono:</b></td>
                        </tr>
                        <tr>
                            <td class="linea">
                                <?= $direccion['Direccion']['telefono']; ?>&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td><b>celular:</b></td>
                        </tr>
                        <tr>
                            <td>
                                <?= $direccion['Direccion']['celular']; ?>&nbsp;
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="derecha">
                    <table class="datosCompra">
                        <tr>
                            <td class="linea"><b>estado compra:</b></td>
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
                                elseif ($compra['Compra']['estado'] == 12)
                                    $estado = 'En evaluacion';
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
                                <?= $pago['Pago']['marca']; ?>&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td class=""><b>Tipo:</b></td>
                            <td  class=" text-right">
                                <?= $pago['Pago']['estado']; ?>&nbsp;
                            </td>
                        </tr>
                    </table>
                </div>
            </li>
        </ul>
    </div>
    <h2 class="subtitulo">Producto</h2>

        <div class="previsualizar">
            <ul>
                <li class="extendido">
                    <?php
                    $precio_venta = $producto['ProductosCompra']['valor'];
                    /*if ($producto['Descuento']['id'])
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
                    }*/

                    ?>
                    <span><img src="<?= $this->Shapeups->imagen($producto_datos['Producto']['foto']['mini']) ?>" /></span>
                    <b><?= $producto_datos['Producto']['nombre']; ?></b> (<?= $producto_datos['Producto']['codigo_completo']; ?>)
                    <br /><b>talla:</b> <?= $this->Shapeups->talla_ropa($producto['ProductosCompra']['talla']); ?>
                    <br /><b>valor pagado:</b> <?= $this->Shapeups->moneda($precio_venta); ?>


                </li>

                        <li><span>Estilo</span><?php echo (isset($devolucion['Devolucion']['producto']) && $devolucion['Devolucion']['producto']!='')? $devolucion['Devolucion']['producto']:$devolucion['Producto']['codigo_completo']; ?></li>
                        <li> <span>Razon</span><?= $devolucion['Devolucion']['razon']; ?></li>

                        <?
                            if ($devolucion['Devolucion']['estado'] == 1){
                                $estado_devolucion = 'Aprobada';
                            }elseif($devolucion['Devolucion']['estado'] == 2){
                                $estado_devolucion = 'Rechazada';
                            }else{
                                $estado_devolucion = 'Rechazada';
                            }
                        ?>
                        <li>
                            <span>solicitud</span>
                            <?= $estado_devolucion; ?>
                        </li>
                        <li >
                            <span>fecha:</span>
                            <?= $devolucion['Devolucion']['fecha']; ?></li>
                        <li >
                            <span>hora:</span>
                            <?= $devolucion['Devolucion']['hora']; ?>
                        </li>
                        <li >
                            <span>observaciones:</span>
                            <?= $devolucion['Devolucion']['observaciones']; ?>
                        </li>
                <? if( $devolucion['Devolucion']['run_titular']) :?>
                    <br>
                    <h3> Datos de transferencia:</h3>
                    <li>
                        <span>Nombre:</span>
                        <?= $devolucion['Devolucion']['nombre_titular']; ?>
                    </li>
                    <li>
                        <span>Apellido:</span>
                        <?= $devolucion['Devolucion']['apellido_titular']; ?>
                    </li>
                    <li>
                        <span>Run:</span>
                        <?= $devolucion['Devolucion']['run_titular']; ?>
                    </li>
                    <li>
                        <span>Tipo cuenta:</span>
                        <?= $devolucion['Devolucion']['tcuenta_titular']; ?>
                    </li>
                    <li>
                        <span>N° cuenta:</span>
                        <?= $devolucion['Devolucion']['ncuenta_titular']; ?>
                    </li>
                    <li>
                        <span>Banco:</span>
                        <?= $devolucion['Devolucion']['banco_titular']; ?>
                    </li>
                <? else :?>
                    <h3> Datos de transferencia: Desconocidos </h3>
                <? endif?>
            </ul>
        </div>
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