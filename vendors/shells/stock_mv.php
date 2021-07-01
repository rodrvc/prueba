<?php
App::import('Core', array('Router','Controller'));

class StockMvShell extends Shell {
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
		//$multivende->setStock0();
		$multivende->setStockMasivo();
	}

	
}
function prx($data)
{
	pr($data); exit;
}