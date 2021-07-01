<?php
App::import('Core', array('Router','Controller'));
App::import('Component', 'Multivende');


class TaskShell extends Shell {
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
		$multivende->getCebraMasivoAtrasado();

	}

	
}
function prx($data)
{
	pr($data); exit;
}