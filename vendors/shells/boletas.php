<?php
App::import('Core', array('Router','Controller'));


class BoletasShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main() 
	{
		error_reporting(8);
		App::import('Model','Compra');
		$CompraOBJ = new Compra();
		$ruta_inicial ='/home/skechersftp/ftp/files/boleta_pdf';
		$rutal_final ='/var/www/boletas';
		$compras = $CompraOBJ->boletas_pdf($ruta_inicial, $rutal_final);
	}

	
}
function prx($data)
{
	pr($data); exit;
}