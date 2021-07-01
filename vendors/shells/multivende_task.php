<?php
App::import('Core', array('Router','Controller'));
App::import('Component', 'Multivende');


class MultivendeTaskShell extends Shell {
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
		$multivende->getCebraMasivo();
	}

	
}
function prx($data)
{
	pr($data); exit;
}