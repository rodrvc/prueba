<?php
App::import('Core', array('Router','Controller'));
App::import('Component', 'Multivende');


class MultivendeVentaShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main() 
	{
		App::import('Component', 'Multivende');
		$multivende =& new MultivendeComponent(null);
		$multivende->initialize($this->Controller); 
		$multivende->authenticate();
		$multivende->procesarVentaUnica('fd4e77a7-3f2c-41c9-a841-fb03492030a4');
	}

	
}
function prx($data)
{
	pr($data); exit;
}