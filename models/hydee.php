<?php
class Hydee extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Hydee';
	// BEHAVIORS
	
	// ASOCIACIONES
	var $belongsTo = array(
		'Compra' => array(
		'foreignKey'			=> 'compra_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Compra')
			)
	);
	public function procesarFile($archivo)
	{
		if (($handle = fopen($archivo, 'r') ) !== FALSE)
		{
			$stats['lineas'] = 0;
			$separador = ';';
			$mapeo =array('id');
			$registros = array();
			while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
			{
				//prx($datos);
				$stats['lineas']++;

				if ($stats['lineas']<=1)
					continue;

				$registro = array();

				foreach ( $datos as $index => $valor )
				{
					if ( ! isset($mapeo[$index]) )
						continue;
	
					if (trim($valor) == '---------- END OF REPORT ---------')// finaliza lectura
						break 2;

				}
				$registros[]= trim($valor);
			}
			foreach ($registros as $registro)
			{
				if(!$this->find('first', array('conditions' => array('compra_id' => $registro))))
				{
					$this->create();
					$this->save(array('compra_id' => $registro));
				}
			}

		}
	}
	public function buscar()
	{
		$hasta = date('Y-m-d 23:59:59' , time()-172800);
		$ordenes_hydee = $this->find('list', array('fields' => array('compra_id','compra_id')));
		$ordenes_ecomm = $this->Compra->find('list',	array('conditions' => array( 
																	  'created >=' => '2020-09-01 00:00:00',
																	  'created <' => $hasta,
																	  'local' => 0,
																	  'estado' => 1),
																'fields' => array('id','id')));
		pr(count($ordenes_ecomm));
		foreach($ordenes_ecomm as $orden)
		{
			if(isset($ordenes_hydee[$orden]))
			{
				unset($ordenes_ecomm[$orden]);
			}
		}
		pr(count($ordenes_ecomm));

		prx($ordenes_ecomm);
	}
}
?>