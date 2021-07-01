te?<?php
App::import('Core', array('Router','Controller'));
App::import('Component', 'WsServer');


class dafitiShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main()
	{

		$orderId = $this->args[0];
		App::import('Component', 'Multivende');
		$multivende =& new MultivendeComponent(null);
		$multivende->initialize($this->Controller); 
		$multivende->authenticate();
		$multivende->setDespachoDafiti($orderId);
		
	}


}
function prx($data)
{
	pr($data); exit;
}
