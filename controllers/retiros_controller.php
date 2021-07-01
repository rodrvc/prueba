<?php
class RetirosController extends AppController {

	var $name = 'Retiros';
	function beforeRender()
	{
		parent::beforeRender();
	
	}

	public function ajax_retiros($comuna_id, $tipo)
	{
		$options = array(
			'conditions' => array(
				'Retiro.comuna_id' => $comuna_id,
				'Retiro.tipo_id' => $tipo
			),
			'fields' => array(
				'Retiro.id',
				'Retiro.nombre'
			)
		);
		die(json_encode($this->Retiro->find('list',$options)));
	}
		public function ajax_retirosRegion($region_id, $tipo)
	{
		$options = array(
			'conditions' => array(
				'Retiro.region_id' => $region_id,
				'Retiro.tipo_id' => $tipo,
				'Retiro.activo' => 1
			),
			'fields' => array(
				'Retiro.id',
				'Retiro.nombre'
			)
		);
		die(json_encode($this->Retiro->find('list',$options)));
	}
	public function ajax_comunas($region_id)
	{
		$options = array(
			'conditions' => array(
				'Comuna.region_id' => $region_id
			),
			'fields' => array(
				'Comuna.id',
				'Comuna.nombre'
			)
		);
		die(json_encode($this->Retiro->Comuna->find('list',$options)));
	}

	public function ajax_retiro_info($id)
	{
		$options = array(
			'conditions' => array(
				'Retiro.id' => $id
			),
			'contain' =>array(
				'Comuna',
				'Region'
			)
		);
		die(json_encode($this->Retiro->find('first',$options)));
	}

	public function test($id)
	{
		$options = array(
			'conditions' => array(
				'Retiro.id' => $id
			),
			'contain' =>array(
				'Comuna',
				'Region'
			)
		);
		die(json_encode($this->Retiro->find('first',$options)));

	}
	public function actualizar()
	{
		$this->Chilexpress->actualizarOficinas();
	}
		public function oficinas()
	{
		$this->Chilexpress->getOficinas(6);
	}

}
?>