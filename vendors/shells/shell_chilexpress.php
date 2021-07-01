<?php
App::import('Core', array('Router','Controller'));
App::import('Component', 'Procesar');
App::import('Component', 'Email');

class ShellChilexpressShell extends Shell
{
    var $Controller = null;

    function initialize()
    {
        $this->Controller =& new Controller();

    }

    function main()
    {

        $datos = $this->compras();

        exit;
    }
    function compras(){

        //$basedir = 'C:/xampp/htdocs/pick/';
        //$basedir = DS.'var'.DS.'www'.DS.'html'.DS.'webroot'.DS.'img'.DS;
        //$basedir = DS.'var'.DS.'www'.DS;

        //$archivo = $basedir.'TGO-150920.csv';


        $directorio_base = 'C:/xampp/htdocs/pick' ;
        $dir_handle = opendir($directorio_base);

        while(($archivo = readdir($dir_handle)) !== false) {
            $ruta = $directorio_base . '/' . $archivo;
            //echo $ruta . PHP_EOL;
            if(is_file($ruta)) {
                $ext = pathinfo($ruta, PATHINFO_EXTENSION);
                if($ext === 'xlsx') {
                    //hacer lo que se tiene que hacer con el archivo
                }
            }
        }
        closedir($dir_handle);
        if (!isset($ext) )
            return false;


        $guardar = true;

        App::import('Model', 'Compra');
        $Compras = new Compra();

        if (! file_exists($ruta))
            return false;

        $empresa_despacho=1;
        $start = 2;
        $separador =';';
        $mapeo = array(
            'N° Orden Skechers', // N° Orden Skechers;Numero despacho TGO
            'Numero despacho ChileX',
        );


        $stats = array(
            'lineas' => 0,
            'actualizados' => 0,
            'log_actualizados' => '',
            'parametro_invalido' => 0,
            'log_parametro_invalido' => '',
            'compra_invalida' => 0,
            'log_compra_invalida' => '',
            'omitidos' => 0,
            'log_omitidos' => '',
            'errores' => 0,
            'log_errores' => '',
        );


        if(is_readable($ruta)) {

            if (($handle = fopen($ruta, 'r')) !== FALSE) {
                while (($datos = fgetcsv($handle, 0, $separador)) !== FALSE) {
                    $stats['lineas']++;

                    if ($stats['lineas'] <= $start)
                        continue;

                    $registro = array();
                    foreach ($datos as $index => $valor) {
                        if (!isset($mapeo[$index]))
                            continue;

                        $registro[$mapeo[$index]] = trim($valor);
                    }

                    if (!isset($registro['N° Orden Skechers'])) {
                        $stats['parametro_invalido']++;

                        if ($stats['log_parametro_invalido'])
                            $stats['log_parametro_invalido'] .= ', ';

                        $stats['log_parametro_invalido'] .= $stats['lineas'];
                        continue;
                    }
                    if (!$registro['N° Orden Skechers']) {
                        $stats['parametro_invalido']++;

                        if ($stats['log_parametro_invalido'])
                            $stats['log_parametro_invalido'] .= ', ';

                        $stats['log_parametro_invalido'] .= $stats['lineas'];
                        continue;
                    }
                    if (!isset($registro['Numero despacho ChileX'])) {
                        $stats['parametro_invalido']++;

                        if ($stats['log_parametro_invalido'])
                            $stats['log_parametro_invalido'] .= ', ';

                        $stats['log_parametro_invalido'] .= $stats['lineas'];
                        continue;
                    }
                    if (!$registro['Numero despacho ChileX']) {
                        $stats['parametro_invalido']++;

                        if ($stats['log_parametro_invalido'])
                            $stats['log_parametro_invalido'] .= ', ';

                        $stats['log_parametro_invalido'] .= $stats['lineas'];
                        continue;
                    }
                    $id = ereg_replace("[^0-9]", "", $registro['N° Orden Skechers']);

                    $options = array(
                        'conditions' => array(
                            'Compra.id' => $id,
                            'Compra.estado' => 1
                        ),
                        'fields' => array(
                            'Compra.id',
                            'Compra.usuario_id',
                            'Compra.picking_number',
                            'Compra.cod_despacho',
                            'Compra.boleta'
                        )
                    );
                    //var_dump('aqui');
                    // $compras = $Compras->find('first', $options);
                    //var_dump($compras);


                    if (!$compra = $Compras->find('first', $options)) {
                        $stats['compra_invalida']++;

                        if ($stats['log_compra_invalida'])
                            $stats['log_compra_invalida'] .= ', ';

                        $stats['log_compra_invalida'] .= $registro['N° Orden Skechers'];

                        continue;
                    }
                    if (!$compra['Compra']['picking_number']) {
                        $stats['omitidos']++;

                        if ($stats['log_omitidos'])
                            $stats['log_omitidos'] .= ', ';

                        $stats['log_omitidos'] .= $compra['Compra']['id'];
                        continue;
                    }

                    if ($compra['Compra']['cod_despacho'] == $registro['Numero despacho ChileX']) {
                        $stats['omitidos']++;

                        if ($stats['log_omitidos'])
                            $stats['log_omitidos'] .= ', ';

                        $stats['log_omitidos'] .= $compra['Compra']['id'];
                        continue;
                    }

                    $save = array(
                        'id' => $compra['Compra']['id'],
                        'cod_despacho' => trim($registro['Numero despacho ChileX']),
                        'fecha_enviado' => date('Y-m-d H:m:s'),
                        'despachado' => 1,
                        'empresa_despacho_id' => $empresa_despacho
                    );

                    if ($guardar) {

                        if ($Compras->save($save)) {
                            $stats['actualizados']++;

                            if ($stats['log_actualizados'])
                                $stats['log_actualizados'] .= ', ';

                            $stats['log_actualizados'] .= $compra['Compra']['id'];

                            if ($compra['Compra']['cod_despacho']) {
                                $save = array(
                                    'compra_id' => $compra['Compra']['id'],
                                    'cod_despacho' => $compra['Compra']['cod_despacho']
                                );

                                $Compras->Boleta->create();
                                $Compras->Boleta->save($save);
                            }

                            $externo = $this->enviar_email($compra);
                            var_dump($externo);
                        } else {
                            $stats['errores']++;

                            if ($stats['log_errores'])
                                $stats['log_errores'] .= ', ';

                            $stats['log_errores'] .= $compra['Compra']['id'];
                        }
                    } else {
                        $stats['actualizados']++;

                        if ($stats['log_actualizados'])
                            $stats['log_actualizados'] .= ', ';

                        $stats['log_actualizados'] .= $compra['Compra']['id'];
                    }
                }
            }
            fclose($handle);

            $interno = $this->enviar_email_interno($stats);

            //aqui comienza respaldo de archivo
            if ($guardar)
            {
                $respaldo = 'C:/xampp/htdocs/respaldos_pick/';

                //$basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'archivos'.DS.'DESPACHO'.DS;
                //$basedir = 'C:\xampp\public_html\andain\skechers\catalogo\sitio\webroot\archivos'.DS;
                //$basedir = 'C:\xampp\htdocs\skechers\archivos'.DS;
                if (! is_dir($respaldo))
                {
                    @mkdir($respaldo, 0777, true);

                }
                $time = (strtotime(date('Y-m-d H:i:s')));
                $fecha = Inflector::slug(date('Y-m-d H:i:s'));

                //$basedir = 'C:/xampp/htdocs/pick/';
                //$archivo = 'TGO-150920.csv';

                //$basedir = DS.'var'.DS.'www'.DS.'html'.DS.'webroot'.DS.'img'.DS;
                //$basedir = DS.'var'.DS.'www'.DS;
                $name = $fecha.'_chilexpress.csv';

                rename($ruta,$respaldo.$name);
            }
        }else{
            return false;
        }

        return($stats);
    }

    function enviar_email($compra){

        App::import('Component', 'Email');
        $email =& new EmailComponent(null);
        $email->initialize($this->Controller);

        App::import('Model', 'Compra');
        $Compras = new Compra();

        $options = array(
            'conditions' => array(
                'Compra.id' => $compra['Compra']['id'],
                'Compra.estado' => 1,
                'Compra.despachado' => 1
            ),
            'fields' => array(
                'Compra.id',
                'Compra.boleta',
                'Compra.picking_number',
                'Compra.cod_despacho',
                'Compra.local',
                'Compra.rural',
                'Compra.direccion_rural',
                'Compra.despacho_id',
                'Compra.usuario_id',
                'Compra.subtotal',
                'Compra.iva',
                'Compra.neto',
                'Compra.descuento',
                'Compra.total',
                'Compra.valor_despacho',
                'Compra.pago_id',
                'Compra.estado',
                'Compra.despachado',
                'Compra.fecha_enviado',
                'Compra.mail_confirmacion',
                'Compra.empresa_despacho_id',
                'Compra.boleta_pdf',
            ),
            'contain' => array(
                'Despacho' => array(
                    'fields' => array(
                        'Despacho.id',
                        'Despacho.direccion_id',
                    ),
                    'Direccion' => array(
                        'fields' => array(
                            'Direccion.id',
                            'Direccion.calle',
                            'Direccion.numero',
                            'Direccion.depto',
                            'Direccion.comuna_id',
                            'Direccion.region_id',
                        ),
                        'Comuna.nombre',
                        'Region.nombre'
                    )
                ),
                'Usuario' => array(
                    'fields' => array(
                        'Usuario.id',
                        'Usuario.nombre',
                        'Usuario.apellido_paterno',
                        'Usuario.apellido_materno',
                        'Usuario.email',
                        'Usuario.sexo_id'
                    ),
                    'Sexo.nombre'
                ),
                'Pago' => array(
                    'fields' => array(
                        'Pago.id',
                        'Pago.compra_id',
                        'Pago.numeroOrden',
                        'Pago.monto',
                        'Pago.numeroTarjeta',
                        'Pago.fecha',
                        'Pago.hora',
                        'Pago.estado',
                        'Pago.mac',
                        'Pago.codAutorizacion',
                        'Pago.expiracion',
                        'Pago.fechaContable',
                        'Pago.tipoPago',
                        'Pago.codigo'
                    )
                )
            )
        );
        if (! $compra = $Compras->find('first',$options))
            return false;


        if (! $compra['Compra']['picking_number'])
            return false;

        if (! $compra['Compra']['boleta'])
        {
            //return false;
        }
        if (!$compra['Compra']['cod_despacho'])
            return false;

        if ($compra['Compra']['local']==1)
            return false;

        if ($compra['Compra']['mail_confirmacion'])
            return false;

        $email->smtpOptions = array(
            'port' => '2525',
            'timeout' => '30',
            'auth' => true,
            'host' => 'smtp.mailtrap.io',
            'username' => 'f50be2e01f78cd',
            'password' => 'dbc99f0c5b8768'
        );
        $personal = '8ff88ec03f-49a47a@inbox.mailtrap.io';

        /*$this->Email->smtpOptions =array(
            'port' => '587',
                    'timeout' => '30',
                    'auth' => true,
                    'host' => 'mail.smtp2go.com',
                                'username' => 'noresponder@skechers-chile.cl',
                    'password' => 'eXV6M2k1cWp4Yzcw'
            );
        $copias = array(
            'ecom@skechers.com'
        );*/


        //$archivo= $compra['Compra']['boleta_pdf'];
        //$basedir = DS.'var'.DS.'www'.DS.'html'.DS.'webroot'.DS.'boletas_skechers'.DS;

        $basedir = 'C:/xampp/htdocs/skechers/webroot/boletas_skechers/';
        $archivo= $compra['Compra']['boleta_pdf'].'.pdf';

        //$basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'boletas_skechers'.DS;
        //$basedir = 'C:/xampp/htdocs/skechers/webroot/boletas_skechers/boleta_pdf'.DS;

        $this->Controller->set('compra',$compra);

        $template = 'despacho';
        $email->attachments =array($basedir.$archivo);
        //$this->Email->to = $compra['Usuario']['email'];
        $email->to = $personal;
        //$this->Email->bcc	= $copias;
        $email->subject = '[Skechers - Tienda en linea] Solicitud de devolución #' . $compra['Usuario']['nombre'];
        // $email->from = 'Skechers <' . $this->email_skechers[2] . '>';
        // $this->Email->replyTo = $this->email_skechers[2];
        $email->sendAs = 'html';
        $email->layout = 'ventas';
        $email->template = $template;
        $email->delivery = 'smtp';
        if ($email->send()) {
            $Compras->save(array('id' => $compra['Compra']['id'], 'mail_confirmacion' => 1));
            return true;
        }
        return false;
    }
    function enviar_email_interno($datos)
    {
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
        $personal = '8ff88ec03f-49a47a@inbox.mailtrap.io';

        /*$this->Email->smtpOptions =array(
            'port' => '587',
                    'timeout' => '30',
                    'auth' => true,
                    'host' => 'mail.smtp2go.com',
                                'username' => 'noresponder@skechers-chile.cl',
                    'password' => 'eXV6M2k1cWp4Yzcw'
            );
        DATOS DESTINATARIO (CLIENTE)
        $copias = array(
            'ecom@skechers.com'
        ); */


        $this->Controller->set('datos', $datos);

        $template = 'logs_shell_interno';
        //$this->Email->to = $compra['Usuario']['email'];
        $email->to = $personal;
        //$this->Email->bcc	= $copias;
        $email->subject = 'Correo con status de chilexpress';
        // $email->from = 'Skechers <' . $this->email_skechers[2] . '>';
        // $this->Email->replyTo = $this->email_skechers[2];
        $email->sendAs = 'html';
        $email->layout = 'devoluciones';
        $email->template = $template;
        $email->delivery = 'smtp';
        if ($email->send()) {
            //$this->Compra->save(array('id' => $compra_id, 'mail_confirmacion' => 1));
            // return true;
        }

        // return true;
    }

}