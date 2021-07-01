<?php
App::import('Core', array('Router','Controller'));

class StockMv0Shell extends Shell {
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
		$multivende->setStockMasivo(null,0);
	}

	
}
function prx($data)
{
	pr($data); exit;
}