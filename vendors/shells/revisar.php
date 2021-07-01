<?php
App::import('Core', array('Router','Controller'));

class RevisarShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main() 
	{
		Configure::write('debug',1);
		error_reporting(8);	
		set_time_limit(0);
		$directorio= '/home/skechersftp/ftp/files/boleta_pdf/';
		$datos = array();
		$datos=array();
		$directorio=opendir($directorio);
		while ($archivo = readdir($directorio)) 
		{ 
		  if(($archivo != '.')&&($archivo != '..'))
		  {
		     $datos[]=$archivo; 
		  } 
		}
		print_r($datos);
		if(empty($datos))
			$this->enviar_notificacion_email('error');
		else
			$this->enviar_notificacion_email(count($datos));
	}
	function enviar_notificacion_email($mensaje)
	{
		App::import('Component', 'Email');
		$email =& new EmailComponent(null);
		$email->initialize($this->Controller);    
		$email->smtpOptions = array(
		'port' => '587',
					'timeout' => '30',
					'auth' => true,
					'host' => 'mail.smtp2go.com',
					'username' => 'noresponder@skechers-chile.cl',
					'password' => 'eXV6M2k1cWp4Yzcw'
			);
		if($mensaje == 'error')
			$this->Controller->set('mensaje', 'No se subieron Boletas');
		else
			$this->Controller->set('mensaje', 'Se cargaron '.$mensaje.' Boletas en el FTP');
		$email->to = 'sebastian@sancirilo.cl';
		$email->cc	= array('danielb@skechers.com'); 
		//$email->to = 'sdelvillar@andain.cl';

		$email->subject = '[Skechers] Carga Boletas en FTP '.date('d-m-Y H:i');
		$email->from = 'Skechers <noreply@skechers-chile.cl>';
		$email->replyTo = 'noreply@skechers-chile.cl';
		$email->sendAs = 'html';
		$email->template	= 'mensaje';
		$email->delivery = 'smtp';
		if ( $email->send() )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

}
function prx($data)
{
	pr($data); exit;
}
