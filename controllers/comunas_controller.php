<?php
class ComunasController extends AppController
{
	var $name = 'Comunas';

	function ajax_lista_region( $region_id = null )
	{
		if ( ! $region_id )
			return false;

		$data = $this->Comuna->find('list', array('conditions' => array('Comuna.region_id' => $region_id)));
		$this->set(compact('data'));
	}
	function index()
	{
		$regiones = $this->Comuna->Region->find('all', array('contain' => array('Comuna')));
		$this->set(compact('regiones'));

	}
	function admin_index($buscar = null)
	{
		if (! empty($this->data))
		{
			if (isset($this->data['Comuna']['buscar']) && $this->data['Comuna']['buscar'])
				$this->redirect(array('action' => 'index',$this->data['Comuna']['buscar']));
		}
		$options = array(
			'fields' => array(
				'Comuna.id',
				'Comuna.nombre',
				'Comuna.region_id',
				'Comuna.limite',
				'Comuna.despacho1',
				'Comuna.despacho2',

				'Region.id',
				'Region.codigo',
				'Region.nombre'
			),
			'recursive' => -1,
			'joins' => array(
				0 => array(
					'table' => 'sitio_regiones',
					'alias' => 'Region',
					'type' => 'INNER',
					'conditions' => array(
						'Region.id = Comuna.region_id'
					)
				)
			)
		);
		if ($buscar)
		{
			$options['conditions'] = array(
				'OR' => array(
					array('Comuna.id' => $buscar),
					array('Comuna.nombre LIKE' => '%'.$buscar.'%'),
					array('Region.codigo LIKE' => '%'.$buscar.'%'),
					array('Region.nombre LIKE' => '%'.$buscar.'%'),
				)
			);
		}

		$this->paginate = $options;
		$comunas = $this->paginate();
		$this->set(compact('comunas'));
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('comuna', $this->Comuna->read(null, $id));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Comuna->create();
			if ( $this->Comuna->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$regiones = $this->Comuna->Region->find('list');
		$this->set(compact('regiones'));
	}

	function admin_edit($id = null)
	{
		if ( ! $id && empty($this->data) )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( ! empty($this->data) )
		{
			if ( $this->Comuna->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		if ( empty($this->data) )
		{
			$this->data = $this->Comuna->read(null, $id);
		}
		$regiones = $this->Comuna->Region->find('list');
		$this->set(compact('regiones'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Comuna->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}

	function habilitadas()
	{
		$comunas_chilexpress = array();
		$url = 'https://testservices.wschilexpress.com/georeference/api/v1.0/coverage-areas?type=0&regionCode=99';
		$resultado = file_get_contents($url);
		$comunas = json_decode($resultado,true);
		foreach ($comunas['coverageAreas'] as $comuna)
		{
			$comunas_chilexpress[] = $comuna['coverageName'];
		}
		echo '<pre>';
		print_r($comunas_chilexpress);
		echo '</pre>';
		exit();

		$this->set(compact('comunas_chilexpress'));
	}
}
?>