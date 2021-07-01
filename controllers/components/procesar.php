<?php
//App::import('Core', array('Router','Controller'));
App::import('Component', 'Controller');

Class ProcesarComponent extends Component
{
    //var $components		= array('Session');
    //var $Controller = null;

   /* function initialize(&$controller)
    {
        // CONTROLADOR DE DONDE ES LLAMADO
        //$this->Controller =& $controller;
      //  $this->Controller =& new Controller();

        // INICIALIZA EL OBJECTO FACEBOOK (ACCESO API)
    }*/
    function procesar_picks(){
        $basedir = 'C:/xampp/htdocs/';
        //$basedir = DS.'var'.DS.'www'.DS.'html'.DS.'webroot'.DS.'img'.DS;
        $archivo = $basedir.'pixpend_200825060003.csv';
        if(is_readable($archivo)) {
            var_dump('Archivo encontrado, procesando....');

            $start = 3;
            $separador = ',';
            $mapeo = array(
                'Grade',
                'Receipt', // num_id
                'Customer', // 10988 canal online
                'Ret Num', // num_id
                'Div',
                'Style',
                'Color',
                'Qty',
                'PIX Date'
            );

            $stats = array(
                'lineas' => 0,
                'actualizados' => 0,
                'omitidos' => 0,
                'error' => 0,
            );

            $registro = array();
            if (($handle = fopen($archivo, 'r')) !== FALSE) {
                while (($datos = fgetcsv($handle, 0, $separador)) !== FALSE) {
                    $stats['lineas']++;

                    if ($stats['lineas'] <= $start)
                        continue;

                    foreach ($datos as $index => $valor) {
                        if (!isset($mapeo[$index]))
                            continue;

                        $registro[$mapeo[$index]] = utf8_decode(trim($valor));
                    }
                    if ($registro['Receipt'] == ''){
                        $stats['error']++;
                        continue;
                    }

                    if($registro['Customer'] == '10998'){
                        $num_id = $registro['Receipt'];
                        $style = $registro['Style'];
                        $color = $registro['Color'];

                        //$respuesta = $this->consulta_compra($num_id,$style,$color);
                        if ($respuesta = $this->consulta_compra($num_id,$style,$color)){
                            var_dump($respuesta);
                            $stats['actualizados']++;
                        }else{
                            $stats['omitidos']++;
                        }

                    }else{
                        $stats['omitidos']++;
                        continue;
                    }

                }
                var_dump($stats['actualizados']);
                //var_dump($stats['omitidos']);
            }
            fclose($handle);

        }else{
            var_dump('El archivo no existe,');
        }
    }
    function consulta_compra($id_num,$style,$color){
        $codigo = '44966BBK'; //$style.$color;
        App::import('Model', 'Compra');
        $CompraOBJ = new Compra();
        App::import('Model', 'Devolucion');
        $DevolucionesOBJ = new Devolucion();

        $options = array(
            'conditions' => array(
                //'Compra.estado' => 1,
                'Compra.numId' => $id_num,
                'Compra.devolucion' => 1,
                'Devoluciones.producto' => $codigo,
                'Devoluciones.estado !=' =>1

                //'Compra.mail_compra' => 0,
                //'Compra.created >=' => $desde,
                //'Pago.tipoPago' => 'VD', //cambiar aqui
            ),
            'fields' => array(
                'Compra.id',
                'Compra.usuario_id',
                'Devoluciones.id',
                'Devoluciones.producto',
                //'ProductosCompra.valor',
            ),
            'joins' => array(
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devoluciones',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Devoluciones.compra_id = Compra.id'
                    )
                ),
                array(
                    'table' => 'sitio_pagos',
                    'alias' => 'Pago',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Pago.compra_id = Compra.id'
                    )
                ),
                array(
                    'table' => 'sitio_productos_compras',
                    'alias' => 'ProductosCompra',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'ProductosCompra.id = Devoluciones.productos_compra_id'
                    )
                ),

            ),
        );
        if ($compras = $CompraOBJ->find('all', $options)){

            $options = array(
              'conditions' => array(
                  'id' => $compras[0]['Devoluciones']['id']
              )
            );
            if ($datos = $DevolucionesOBJ->find('first',$options)){
                $DevolucionesOBJ->save(array('id' => $datos['Devolucion']['id'], 'estado' => 1));
                $datos['Devolucion']['estado'] = 1;
                $email = $this->email_devolucion($datos,$compras);
                return $compras;
            }

            //return $datos['Devolucion']['id'];
             return $compras;
        }

        //return $compras;
    }

    function email_devolucion($data,$datos)
    {
        //var_dump('aqui');
        App::import('Component', 'Email');
        $email =& new EmailComponent(null);
        $email->initialize($this->Controller);

        $email->smtpOptions = array(
            'port' => '2525',
            'timeout' => '30',
            'auth' => true,
            'host' => 'smtp.mailtrap.io',
            'username' => 'f50be2e01f78cd',
            'password' => 'dbc99f0c5b8768'
        );
        /*$this->Email->smtpOptions =array(
            'port' => '587',
                    'timeout' => '30',
                    'auth' => true,
                    'host' => 'mail.smtp2go.com',
                                'username' => 'noresponder@skechers-chile.cl',
                    'password' => 'eXV6M2k1cWp4Yzcw'
            );*/

       $this->set(compact('data'));
       // $mensaje = $this->mensaje($data);

        //$this->set(compact('data', $data));
        //$this->Controller->set('mensaje', $mensaje);

        $compra = $datos[0]['Compra']['id'];
        $personal = '8ff88ec03f-49a47a@inbox.mailtrap.io';
        // DATOS DESTINATARIO (CLIENTE)
        $copias = array(
            'ecom@skechers.com'
        );

        $template = 'devolucion_estado';
        //$this->Email->to = $compra['Usuario']['email'];
        $email->to = $personal;
        //$this->Email->bcc	= $copias;
        $email->subject = '[Skechers - Tienda en linea] Solicitud de devolución #' . $compra;
       // $email->from = 'Skechers <' . $this->email_skechers[2] . '>';
        // $this->Email->replyTo = $this->email_skechers[2];
        $email->sendAs = 'html';
        $email->layout = 'devoluciones';
        $email->template = 'mensaje';
        $email->delivery = 'smtp';
        if ($email->send()) {
            //$this->Compra->save(array('id' => $compra_id, 'mail_confirmacion' => 1));
            return true;
        }

        return true;
    }
    function mensaje($data){
        $mensaje = '
         <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;" bgcolor="#ffffff">
        <!-- Hero Image, Flush : BEGIN -->
            <tr>
                <td height="120" align="center" bgcolor="#ffffff" style="text-align: center!important; font-family: Lucida Grande, Lucida Sans Unicode, Lucida Sans, DejaVu Sans, Verdana,\' sans-serif\';">
                    <br>
                    <span style="color: #003985;  font-size: 24px; font-weight: bold; text-transform: uppercase;font-family:Century Gothic,Futura,san-serif;">Hola Sinda!</span>
                    <br>
                    <hr style="width: 50px; border: 1px solid #113d86">
                    <br>
                    <span style="color: #666666;  font-size: 16px; font-weight: 400;  height: 24px; font-family:Century Gothic,Futura,san-serif;">';

                        if($data['Devolucion']['estado'] == 1){
                            $estado = 'aprobado';
                        }elseif ($data['Devolucion']['estado'] == 2){
                            $estado = 'rechazado';
                        }else {
                            $estado = 'revisado';
                        }
        $mensaje .= 'Queremos informarte que la solicitud de devolución de tu pedido <strong>N°'; $data['Devolucion']['compra_id'];
        $mensaje .='</strong> ha sido <strong><?= $estado ?></strong>';
                       if($data['Devolucion']['estado'] == 1) {
                           $mensaje .='                  y en los próximos 15 días hábiles se reembolsará su dinero según su medio de pago .
                           <br >
                           <span style = "color: #666666;  font-size: 16px; font-weight: 400;  height: 24px; font-family:\'Century Gothic\',\'Futura\',san-serif;" > Esperamos verte pronto de vuelta en nuestra tienda y ante cualquier duda,
                           puedes contactarnos al correo:<a href = "mailto:ventas@skechers.com" > ventas@skechers . com </a >
                            </span >
                            ';
                       }

        $mensaje .='  </span>
                <br>
                <br>
            </td>

        </tr>
        <!-- Hero Image, Flush : END -->

        <!-- 1 Column Text + Button : BEGIN -->
        <tr>
            <td bgcolor="#ffffff" style="padding: 20px;">
                <table width="85%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td height="90">
                            <hr style="width: 80%; border-bottom: 1px solid #cccccc">
                        </td>
                    </tr>
                </table>

        ';

        return $mensaje;
    }

}
