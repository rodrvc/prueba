<?php
App::import('Core', array('Router','Controller'));
App::import('Component', 'Procesar');
App::import('Component', 'Email');

class ProcesarPicksShell extends Shell
{
    var $Controller = null;

    function initialize()
    {
        $this->Controller =& new Controller();

    }

    function main()
    {

        /*App::import('Component', 'Procesar');
        $procesar =& new ProcesarComponent(null);

        $procesar->procesar_picks();*/

        App::import('Controller','Compras');
        $compras =& new ComprasController(null);
        // parametro 1 indica al controlador que viene desde shell
        $datos = $compras->procesar_picks(1);
        if (isset($datos['compras'])){
           $actualizados = $datos['stats']['actualizados'];
           $omitidos = $datos['stats']['omitidos'];
           $log_a = $datos['stats']['log_actualizados'];

            $email = $this->enviar_email($datos['compras']);
            $email = $this->enviar_email_interno($datos['stats']);
        }
        exit;
    }
    function enviar_email($data){
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
        $personal = '8ff88ec03f-49a47a@inbox.mailtrap.io';

        /*$this->Email->smtpOptions =array(
            'port' => '587',
                    'timeout' => '30',
                    'auth' => true,
                    'host' => 'mail.smtp2go.com',
                                'username' => 'noresponder@skechers-chile.cl',
                    'password' => 'eXV6M2k1cWp4Yzcw'
            );*/
        // DATOS DESTINATARIO (CLIENTE)
        $copias = array(
            'ecom@skechers.com'
        );

        foreach ($data as $datos) {

           // var_dump($datos);
            $datos['Devoluciones']['estado'] = 1;
            //$mensaje = $this->mensaje($datos);

            $this->Controller->set('datos',$datos);
            $template = 'devolucion_shell';
            //$this->Email->to = $compra['Usuario']['email'];
            $email->to = $personal;
            //$this->Email->bcc	= $copias;
            $email->subject = '[Skechers - Tienda en linea] Solicitud de devolución #' . $datos['Compra']['id'];
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
    function enviar_email_interno($data)
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
        $personal = '8ff88ec03f-49a47a@inbox.mailtrap.io';

        /*$this->Email->smtpOptions =array(
            'port' => '587',
                    'timeout' => '30',
                    'auth' => true,
                    'host' => 'mail.smtp2go.com',
                                'username' => 'noresponder@skechers-chile.cl',
                    'password' => 'eXV6M2k1cWp4Yzcw'
            );*/
        // DATOS DESTINATARIO (CLIENTE)
        $copias = array(
            'ecom@skechers.com'
        );


            //var_dump($data);
            //die();

            //var_dump($data['log_a']);
            $datos['Devoluciones']['estado'] = 1;
            //$mensaje = $this->mensaje($datos['stats]);

            var_dump($data);
            $this->Controller->set('datos', $data);
            $template = 'devolucion_shell_interno';
            //$this->Email->to = $compra['Usuario']['email'];
            $email->to = $personal;
            //$this->Email->bcc	= $copias;
            $email->subject = 'Correo con status de picks';
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