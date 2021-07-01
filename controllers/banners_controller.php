<?php
class BannersController extends AppController
{
	var $name = 'Banners';

	function admin_index()
	{

		$options = array(
			'conditions' => array('Banner.tipo' => 0),
			'fields' => array(
				'Banner.id',
				'Banner.imagen',
				'Banner.link'
				),
			'order' => array('Banner.orden' => 'ASC')
			);
		$banners = $this->Banner->find('all',$options);
		$options = array(
			'conditions' => array('Banner.tipo' => 1,
								  'Banner.activo' => 1),
			'fields' => array(
				'Banner.id',
				'Banner.imagen',
				'Banner.link'
				)
			);
		$izquierda = $this->Banner->find('first',$options);
		$options = array(
			'conditions' => array('Banner.tipo' => 2,
								  'Banner.activo' => 1),
			'fields' => array(
				'Banner.id',
				'Banner.imagen',
				'Banner.link'
				)
			);
		$derecha = $this->Banner->find('first',$options);
		$this->set(compact('banners','izquierda','derecha'));
	}
	
	function admin_carrusel()
	{

		if (! empty($this->data) )
		{
			foreach( $this->data['Banner'] as $index => $banner )
			{
				$this->Banner->save(array('Banner' => array('id' => $index,
															'orden' => $banner['orden'])));
			}
			$this->Session->setFlash(__('Nuevo Orden guardado con exito', true));
			$this->redirect(array('action' => 'index'));
		}
		$banners = $this->Banner->find('all', array('conditions' => array('Banner.tipo' => 0),
													'order' => array('Banner.orden' => 'ASC')));
		$this->set(compact('banners'));
	}

	function admin_izquierda()
	{
		$banners = $this->Banner->find('all', array('conditions' => array('Banner.tipo' => 1),
													'order' => array('Banner.orden' => 'ASC')));
		$this->set(compact('banners'));
	}
	
	function admin_derecha()
	{
		$banners = $this->Banner->find('all', array('conditions' => array('Banner.tipo' => 2),
													'order' => array('Banner.orden' => 'ASC')));
		$this->set(compact('banners'));
	}
	
	function admin_activar_izquierda($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'izquierda'));
		}
		$options = array('conditions' => array('Banner.id' => $id,
											   'Banner.tipo' => 1),
						 'fields' => array('Banner.id'));
		if (! $banner = $this->Banner->find('first',$options))
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'izquierda'));
		}
		if ($this->Banner->save(array('id' => $id,'activo' => 1)))
		{
			$this->Banner->updateAll(array('activo' => 0), array('tipo' => 1,
																 'NOT' => array(array('Banner.id' => $id))));
			$this->Session->setFlash(__('Caluga activada exitosamente.', true));
			$this->redirect(array('action' => 'izquierda'));
		}
		else
		{
			$this->Session->setFlash(__('Se produjo un problema al intentar guardar el registro.', true));
			$this->redirect(array('action' => 'izquierda'));
		}
	}
	
	function admin_activar_derecha($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'derecha'));
		}
		$options = array('conditions' => array('Banner.id' => $id,
											   'Banner.tipo' => 2),
						 'fields' => array('Banner.id'));
		if (! $banner = $this->Banner->find('first',$options))
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'derecha'));
		}
		if ($this->Banner->save(array('id' => $id,'activo' => 1)))
		{
			$this->Banner->updateAll(array('activo' => 0), array('tipo' => 2,
																 'NOT' => array(array('Banner.id' => $id))));
			$this->Session->setFlash(__('Caluga activada exitosamente.', true));
			$this->redirect(array('action' => 'derecha'));
		}
		else
		{
			$this->Session->setFlash(__('Se produjo un problema al intentar guardar el registro.', true));
			$this->redirect(array('action' => 'derecha'));
		}
	}

	//function admin_calugas()
	//{
	//	$banners = $this->Banner->find('all', array('conditions' => array('Banner.tipo' => 1),
	//												'order' => array('Banner.orden' => 'ASC')));
	//	$this->set(compact('banners'));
	//}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('banner', $this->Banner->read(null, $id));
	}

	function admin_add($tipo = null)
	{
		if ( ! empty($this->data) )
		{
			$this->Banner->create();
			if ( $this->Banner->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		else
		{
			if ($tipo == 'carrusel')
				$this->data['Banner']['tipo'] = 0;
			elseif ($tipo == 'izquierda')
				$this->data['Banner']['tipo'] = 1;
			elseif ($tipo == 'derecha')
				$this->data['Banner']['tipo'] = 2;
		}
	}

	function admin_edit($id = null)
	{
			Configure::write('debug', 2);

		if ( ! $id && empty($this->data) )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( ! empty($this->data) )
		{
			if (! $this->data['Banner']['imagen']['name'])
			{
				unset($this->data['Banner']['imagen']);
			}

			if ( $this->Banner->save($this->data) )
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
			$this->data = $this->Banner->read(null, $id);
		}
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Banner->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}

}
?>