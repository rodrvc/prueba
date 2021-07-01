<?php
class HydeeController extends AppController
{
	var $name = 'Hydee';

	function admin_index()
	{
		$this->Hydee->recursive = 0;
		$this->set('hydee', $this->paginate());
	}
	function test()
	{
		$this->Hydee->buscar();
		$this->Hydee->procesarFile('d:/xampp/htdocs/skechers/hydee.csv');
	}


}
?>