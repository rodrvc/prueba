<style xmlns="http://www.w3.org/1999/html">
    /* Resultado busqueda */

    #busqueda-content {
        float: right;
    }
    /* inicio embed */
    .embed-container {
        position: relative;
        height: 0;
        overflow: hidden;
    }
    .mi-iframe {
        width: 100px;
        height: 50px;
    }

    /* CSS pantallas de 320px o superior */
    @media (min-width: 320px) {

        .mi-iframe {
            width: 200px;
            height: 150px;
        }

    }

    /* CSS pantalla 768px o superior */
    @media (min-width: 768px) {

        .mi-iframe {
            width: 500px;
            height: 350px;
        }

    }
    .embed-container iframe {
        position: absolute;
        top:0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    /* fin embed */

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
    .contenido_boleta{
        display: inline-block;
        text-align: center;
        font-size: 16px;
        color: inherit;

    }

</style>

<div class="container">
    <h1 class="titulo-categoria">Detalle Boleta</h1>
    <div class="panel panel-skechers">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-5">
                    <div class="panel panel-info panel-skechers2">
                        <div class="panel-heading">
                            <h3 class="panel-title">Consulta el detalle de tu boleta</h3>
                        </div>
                        <div class="panel-body">
                            <?= $this->Form->create('Compras', array('action' => 'detalle_boleta', 'class' => 'form-horizontal')); ?>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-4 control-label">N° de Compra:</label>
                                <div class="col-sm-8">
                                    <?= $this->Form->input('id', array('type' => 'text',
                                        'div' => false,
                                        'label' => false,
                                        'class' => 'form-control',
                                        'placeholder' => 'Ingresa tu numero de Orden')); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-12">
                                    <button type="submit" class="btn btn-primary">Ver detalle boleta</button>
                                </div>
                            </div>
                            <?= $this->Form->end(); ?>

                        </div>
                    </div>
                </div>
                <?php if(isset($path)): ?>
                    <div class="col-md-7">
                        <span class=" col-md-12 alert alert-success contenido_boleta">Hola, descarga aquí tu boleta:

                            <?= $html->link('Ver boleta', $path ,array('target'=>'_blank')); ?>
                        </span>

                    </div>
                <?else:?>
                    <?php if(isset($error)): ?>
                        <div class=" col-md-7" role="alert">
                            <span class="col-md-12 alert alert-danger contenido_boleta">
                                <?php echo $error; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
