<?php
App::import('Core', array('Router','Controller'));

class PruebaShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main() 
	{
		
		App::import('Component', 'Email');
		$email =& new EmailComponent(null);
		$email->initialize($this->Controller);    
		$email->smtpOptions = array(
					'port' => '587',
					'timeout' => '30',
					'auth' => true,
					'host' => 'email-smtp.us-east-1.amazonaws.com',
					'username' => 'AKIAZK3GISZV46Z4HSSL',
					'password' => 'BG+9TWMWjKJOxC55PLl9ozWiXx/TAjvo98l7C0u/p5On',
					'tls' => true
			);
			$this->Controller->set('mensaje', 'No se subieron Boletas');
	
		$email->to = 'sebastian@sancirilo.cl';
		//$email->cc	= array('danielb@skechers.com'); 
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
				echo'<pre>';
				print_r($email);
				echo'</pre>';
				die();
		
			return false;
		}
	}

}
function prx($data)
{
	pr($data); exit;
}
