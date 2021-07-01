<?php
App::import('Core', array('Router','Controller'));

class TraspasoShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main() {
	
		set_include_path(get_include_path() . DS . 'phpseclib');
		include('Net/SFTP.php');
		include('Crypt/RSA.php');
		$key = new Crypt_RSA();
		$key->loadKey(file_get_contents('D:/chl_andain.key.ppk'));
		$sftp = new Net_SFTP('mft.skechers.com');
		if(!$sftp->login('chl_andain', $key)) 
		{
    		exit('Login Failed');
		}
				echo 'Connected to SFTP.';

		$sftp->put( "hola.pdf", "D:/hola.pdf", NET_SFTP_LOCAL_FILE);
exit;




	/*	Configure::write('debug',1);
		set_time_limit(0);
		$inicio			= microtime(true);
		$guardar = true;
		$now = strtotime(date('d-m-Y H:i:s'));
		define("SERVER","andain.cl"); //IP o Nombre del Servidor
		define("PORT",21); //Puerto
		define("USER","amazon@skechers-chile.cl"); //Nombre de Usuario
		define("PASSWORD","Andain5546."); //Contraseña de acceso
		define("PASV",true); //Activa modo pasivo
		# FUNCIONES
		//Permite conectarse al Servidor FTP
		$id_ftp=ftp_connect(SERVER,PORT); //Obtiene un manejador del Servidor FTP
		ftp_login($id_ftp,USER,PASSWORD);
		ftp_pasv($id_ftp, true);
		$directorio=ftp_pwd($id_ftp);
		//print_r($directorio);
		$programacion = $now;
		$carpeta_ftp ='PROD';
		ftp_chdir($id_ftp,$carpeta_ftp);
		$archivos = (ftp_nlist ($id_ftp, ''));
		$archivos = array_diff($archivos, array('..', '.'));
		if(count($archivos) >0)
			$this->enviar_notificacion_email($archivos);

		die('fin');*/
	}
	private function enviar_notificacion_email($pasados = null)
	{
		$mensaje = 'Archivos Problemas FTP:<br>';
		foreach ($pasados as $pasado)
		{
			$mensaje .=$pasado.'<br>';
		}
		// CONFIGURACION DEL EMAIL
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
		$this->Controller->set('mensaje', $mensaje);
		// DATOS DESTINATARIO (CLIENTE)
		$email->to = 'sebastian@sancirilo.cl';
		$email->bcc	= array(
			
			//'solanger@skechers.com',
			//'xeniac@skechers.com'
		); 
		//$email->to = 'sdelvillar@andain.cl';

		$email->subject = '[Skechers] Error extraccion FTP '.date('d-m-Y H:i');
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
