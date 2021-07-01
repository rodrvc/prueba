<?php
App::import('Core', array('Router','Controller'));


class CreditoShell extends Shell
{
    var $Controller = null;

    function initialize()
    {
        $this->Controller =& new Controller();
    }

    function main()
    {
        $datos = $this->datos();

        if (is_array($datos)){
            $archivo = $this->archivo($datos);
        }else{
            var_dump('problemas al generar el archivo');
        }

        exit;
    }
    private function datos(){

        $time = (strtotime(date('Y-m-d H:i:s'))) - (3600 * 48); // hace dos hora atras
        $desde = date('Y-m-d H:i:s', $time);
        var_dump($desde);
        App::import('Model', 'Compra');
        $CompraOBJ = new Compra();

        var_dump($desde);
        $respuesta = ' == inicio busqueda de compras por notificar ==' . PHP_EOL;

        $options = array(
            'conditions' => array(
                //'Compra.estado' => 1,
                //'Compra.mail_compra' => 0,
                'Devoluciones.fecha_picks >=' => $desde,
                'Pago.tipoPago' => 'VC', //cambiar aqui
                'Devoluciones.estado' => 1,
                'Pago.marca' => 'webpay',
            ),
            'fields' => array(
                'Compra.id',
                'Compra.boleta',
                'Devoluciones.codigo',
                'ProductosCompra.valor',
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

        if (!$compras = $CompraOBJ->find('all', $options)) {
            $respuesta .= '- SIN COMPRAS' . PHP_EOL;
            return $respuesta;
        }else{
            return $compras;
        }

    }
    private function archivo($datos){

        //$basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'debito'.DS;
        $basedir = 'C:/xampp/htdocs/skechers/finanzas/debito/'.DS;

        if (! is_dir($basedir))
            @mkdir($basedir, 0755, true);

        $fecha = date('Y-m-d');
        //      GUARDAMOS EL ARCHIVO DE LOS PRODUCTOS
        $fecha = str_replace('-','',$fecha);
        $nombre = $fecha.'_credito.csv';//$fecha.'debito.csv';
        $fp = fopen($basedir.$nombre, 'w+');
        $contador =0;

        foreach($datos as $q)
        {
            if ($contador == 0){
                $linea = 'Monto a devolver;N compra;N RA;N Boleta'."\n";
                $linea.=  $q['ProductosCompra']['valor'] . ';' . $q['Compra']['id'] . ';' . $q['Devoluciones']['codigo'] . ';' . $q['Compra']['boleta'] . ';' . "\n";
            }else {
                $linea =  $q['ProductosCompra']['valor'] . ';' . $q['Compra']['id'] . ';' . $q['Devoluciones']['codigo'] . ';' . $q['Compra']['boleta'] . ';' . "\n";
            }
            if(count($q) > $contador++) {
                $linea.='';
            }
            fwrite($fp,$linea);
        }
        fclose($fp);

        $email = $this->enviar_correo($basedir,$nombre);
        return $email;
    }

    private function enviar_correo($basedir,$archivo){
        App::import('Component', 'Email');
        $email =& new EmailComponent(null);
        $email->initialize($this->Controller);

        /*$email->smtpOptions =array(

            'port' => '2525',
            'timeout' => '30',
            'auth' => true,
            'host' => 'smtp.mailtrap.io',
            'username' => 'f50be2e01f78cd',
            'password' => 'dbc99f0c5b8768'
        );
        */
        $email->smtpOptions = array(
            /*'port' => '587',
                        'timeout' => '30',
                        'auth' => true,
                        'host' => 'mail.smtp2go.com',
                        'username' => 'noresponder@skechers-chile.cl',
                        'password' => 'eXV6M2k1cWp4Yzcw'*/
            'transport' => 'Smtp',
            //'from' => array('noreply@skechers-chile.cl' => 'Testing'),
            'port' => '465',
            'timeout' => '30',
            'auth' => true,
            'host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
            'username' => 'AKIAZK3GISZV5N5QNTQB',
            'password' => 'BP66zMPbbcyLkCTQq4DuptpKUmF0j/7dcIIFyCJXAbe/'
        );
        $copias = array(
            //'sebastian@sancirilo.cl',
            'jumatamala@gmail.com',
            'miguela@skechers.com'
            //'jwalton@skechers.com'
            //'ecom@skechers.com'

        );

        $personal = '8ff88ec03f-49a47a@inbox.mailtrap.io';

        $template = 'pick_interno';
        $email->attachments =array($basedir.$archivo);
        //$this->Email->to = $compra['Usuario']['email'];
        $email->to = $copias;//$personal;
        //$this->Email->bcc	= $copias;
        $email->subject = 'Correo de devoluciones credito'.$archivo ;
        $email->from = 'noreply@skechers-chile.cl';
        //$this->Email->replyTo = $this->email_skechers[2];
        $email->sendAs = 'html';
        $email->layout = 'ventas';
        $email->template	= $template;//$despacho;
        $email->delivery = 'smtp';
        if ($email->send())
        {
            //$this->Compra->save(array('id' => $compra_id, 'mail_confirmacion' => 1));
            return true;
        }
        return false;
    }
}
