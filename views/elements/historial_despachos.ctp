<style>
    /* Resultado busqueda */

    #busqueda-content {
        float: right;
    }

    .resultado_busqueda {
        font-size: 14px;
        flex: 0 1 auto;
        font-family: sans-serif;
    }

    .resultado_busqueda .seccion {
        margin-bottom: 15px !important;
    }

    .resultado_busqueda .cabecera {

    }

    .resultado_busqueda .cabecera .titulo_resultado {
        font-size: 17px;
        margin-bottom: 20px;
    }

    .resultado_busqueda .cabecera .titulo_resultado .titulo_resultado_texto {
        margin-bottom: 25px;
    }

    .resultado_busqueda .cabecera .titulo_resultado .titulo_resultado_texto strong {
        letter-spacing: -1px;
    }

    .resultado_busqueda .cabecera .titulo_pagina {
        font-size: 30px;
        margin-bottom: 20px;
        font-style: italic;
        font-family: 'UniSansLightItalicItalic';
        font-weight: bold;
        line-height: 40px;
    }

    .resultado_busqueda .cabecera .numero_ot .numero_ot_texto {
        font-size: 0.8em;
        text-transform: uppercase;
        color: #8a8a8a;
    }

    .resultado_busqueda .cabecera .numero_ot .numero_ot_numero {
        color: #8a8a8a;
        font-size: 1.5em;
        letter-spacing: 0px;
        font-family: 'UniSansLightRegular';
        font-weight: bold;
    }

    .resultado_busqueda .seguimiento {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #d8d8d8;
        padding: 20px;
        color: #a4a4a4;
        font-weight: bold;
    }

    .resultado_busqueda .seguimiento .seguimiento_grupo .seguimiento_descripcion {
        text-align: center;
        font-size: 12px;
        width: 100px;
    }

    .resultado_busqueda .seguimiento .seguimiento_grupo .seguimiento_descripcion_incidencia {
        width: 1px !important;
    }

    .resultado_busqueda .seguimiento .seguimiento_grupo .seguimiento_descripcion_black {
        color: #000 !important;
    }

    .resultado_busqueda .seguimiento .seguimiento_grupo {
        position: relative;
        min-width: 2px;
    }

    .resultado_busqueda .seguimiento .seguimiento_grupo .seguimiento_punto_gris {
        display: none;
    }

    .resultado_busqueda .seguimiento .seguimiento_imagen {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0px 10px;
    }

    .resultado_busqueda .seguimiento .seguimiento_ticket {
        margin-left: -30px;
        position: relative;
        height: 20px;
        width: 20px;
    }

    .resultado_busqueda .seguimiento .seguimiento_grupo .seguimiento_imagen_flecha1 {
        position: absolute;
        right: 40px;
        top: -5px;
    }

    .resultado_busqueda .seguimiento .seguimiento_grupo .seguimiento_imagen_flecha2 {
        position: absolute;
        right: 40px;
        top: -15px;
    }

    .resultado_busqueda .seguimiento .seguimiento_alerta {
        width: 24px;
        height: 24px;
        margin-top: -20px;
        z-index: 1000;
    }

    .resultado_busqueda .seguimiento .seguimiento_grupo .seguimiento_alerta_imagen {
        width: 24px;
        height: 24px;
    }

    .resultado_busqueda .seguimiento .linea_punteada {
        flex: 1 0 auto;
        border: 1px dashed #d8d8d8;
        margin-top: -20px;
    }

    .resultado_busqueda .informacion {
    }

    .resultado_busqueda .informacion .datos_entrega {
        margin-bottom: 15px !important;
    }

    .resultado_busqueda .informacion .titulo_seccion {
        font-size: large;
        font-weight: bold;
        font-style: italic;
        margin-bottom: 15px !important;
    }

    .resultado_busqueda .informacion .datos_entrega .contenido_entrega, .resultado_busqueda .informacion .datos_pieza .contenido_pieza {
        border: 1px solid #d8d8d8;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        padding: 20px;
    }

    .resultado_busqueda .informacion .datos_entrega .contenido_entrega div, .resultado_busqueda .informacion .datos_pieza .contenido_pieza div {
        flex: 0 0 auto;
    }

    .datos_titulo {
        color: #A7A7A7;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        height: 15px;
    }

    .datos_textos {
        font-size: 13px;
    }


    .datos_informacion {
        font-size: 12px;
        /*font-weight: bold;*/
    }

    .datos_seguimiento {
        margin-top: 15px;
    }

    .datos_seguimiento_fila {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .datos_seguimiento_fila .link_seguimiento_internacional {
        font-weight: bold;
        font-size: 12px;
        text-transform: uppercase;
        margin-left: auto;
    }

    .datos_seguimiento .addresses thead th, .datos_seguimiento .addresses tbody tr td {
        padding: 5px 5px;
    }

    .datos_seguimiento .addresses thead th:nth-child(2), .datos_seguimiento .addresses tbody tr td:nth-child(2) {
        padding-right: 30px;
    }

    .datos_seguimiento .addresses thead th {
        background-color: transparent;
    }

    .datos_seguimiento .addresses tbody tr:first-child {
        font-weight: bold;
    }

    .datos_seguimiento_alerta {
        border: 1px solid #FAB82C;
        background: #F2EBDB;
        border-radius: 10px;
        padding: 10px;
        font-style: italic;
        display: flex;
    }

    .datos_seguimiento_alerta i {
        margin-right: 10px;
        color: #FAB82C;
        font-size: 20px;
    }

</style>
<?php foreach ($cod as $q) :?>

        <?php $busqueda = $q['busqueda'];?>
        <?php $trackeo = $q['trackeo'];?>

<?php if(isset($busqueda)): ?>
<div class="container">
    <div class="col-md-7 resultado_busqueda ">
        <div class="seccion informacion">
            <div class="datos_pieza seccion">
                <div class="titulo_seccion">Datos del envío</div>
                <div class="contenido_pieza">
                    <div class="grupo_datos">
                        <div class="pieza_producto datos_titulo">N° Despacho</div>
                        <div class="pieza_producto datos_informacion"><?php echo $busqueda['transportOrderNumber'];?></div>
                    </div>
                    <div class="grupo_datos">
                        <div class="pieza_servicio datos_titulo">Orden Compra</div>
                        <div class="pieza_producto datos_informacion"><?php echo $busqueda['reference'];?></div>
                    </div>
                    <div class="grupo_datos"><div class="pieza_admision datos_titulo">Fecha de Compra</div>
                        <div class="pieza_producto datos_informacion">27/04/2020</div>
                    </div>
                </div>
            </div>
            <div class="datos_entrega">
                <?php if(!empty($recepcion)&& $recepcion['nombre']!='') :?>
                    <div class="titulo_seccion">Datos de entrega</div>
                    <div class="contenido_entrega">
                        <div class="grupo_datos">
                            <div class="entrega_recibido datos_titulo">Recibido por</div>
                            <div class="entrega_recibido datos_informacion"><?php echo $recepcion['nombre'];?></div>
                        </div>
                        <div class="grupo_datos">
                            <div class="entrega_rut datos_titulo">RUT de receptor</div>
                            <div class="entrega_rut datos_informacion"><?php echo $recepcion['rut'];?></div>
                        </div>
                        <div class="grupo_datos">
                            <div class="entrega_fecha datos_titulo">Fecha de entrega</div>
                            <div class="entrega_fecha datos_informacion"><?php echo $recepcion['fecha'];?></div>
                        </div>
                        <div class="grupo_datos">
                            <div class="entrega_firma datos_titulo">Firma receptor</div>
                            <div class="entrega_firma datos_informacion"> &nbsp; </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="datos_seguimiento seccion">
                    <div class="datos_seguimiento_fila">
                        <div class="titulo_seccion">Seguimiento del envío</div>
                    </div>
                    <table class="addresses">
                        <thead>
                        <tr>
                            <th class="datos_titulo">Fecha</th>
                            <th class="datos_titulo">Hora</th>
                            <th class="datos_titulo">Actividad</th>
                        </tr>
                        </thead>
                        <tbody id="ListaTrackingOT">
                        <?php foreach ($trackeo as $movimiento) :?>
                            <tr>
                                <td class="datos_textos"><?php echo $movimiento['fecha'];?></td>
                                <td class="datos_textos"><?php echo $movimiento['hora'];?></td>
                                <td class="datos_textos"><?php echo $movimiento['estado'];?></td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
<?php endif; ?>
<?php endforeach; ?>
