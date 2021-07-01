<?php
class ComentariosController extends AppController
{
	var $name = 'Comentarios';
	
	function agregar()
	{
		// ----- VERIFICA LOGUEO
		if(! $this->Auth->user('') )
			$this->redirect(array('action' => 'login'));
		
		if( $this->data['Comentario']['producto_id'] && $this->data['Comentario']['asunto'] && $this->data['Comentario']['talla'] && $this->data['Comentario']['como'] && $this->data['Comentario']['comentario'])
		{
			$this->data['Comentario']['usuario_id'] = $this->Auth->user('id');
			$this->Comentario->create();
			if ( $this->Comentario->save($this->data) )
			{
				$this->Session->setFlash(__('<h4>¡Muchas Gracias!</h4><br /><p>Tu mensaje será revisado por un moderador para ser publicado.</p>', true));
				$this->redirect($this->referer());
			}
			else
			{
				$this->Session->setFlash(__('<h4>¡Lo sentimos!</h4><br /><p>Tus comentario no fue guardado. Intentalo nuevamente.</p>', true));
				$this->redirect($this->referer());
			}
		}
		else
		{
			$this->Session->setFlash(__('<h4>¡Lo sentimos!</h4><br /><p>Debes llenar todos los campos. Intentalo nuevamente.</p>', true));
			$this->redirect($this->referer());
		}
	}
	
	function validar_comentario()
	{
		if( $this->params['form']['id'] )
		{
			$this->data['Comentario']['id'] = $this->params['form']['id'];
			$this->data['Comentario']['estado'] = 1;
			if ( $this->Comentario->save($this->data) )
			{
				die('VALIDAR_OK');
			}
		}
		die('VALIDAR_FAIL');
	}

	function admin_index()
	{
		$this->Comentario->recursive = 1;
		$this->set('comentarios', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Comentario->recursive = 1;
		$this->set('comentario', $this->Comentario->read(null, $id));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Comentario->create();
			if ( $this->Comentario->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$this->Comentario->recursive = 1;
		$usuarios = $this->Comentario->Usuario->find('list');
		$this->set(compact('usuarios'));
	}

	function admin_edit($id = null)
	{
		if ( ! $id && empty($this->data) )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}

		$comentario = $this->Comentario->find('first', array('conditions' => array('Comentario.id' => $id),
															 'fields' => array('Comentario.id',
																			   'Comentario.nombre',
																			   'Comentario.comentario',
																			   'Comentario.compra_id')));
		if ( ! $comentario )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}

		if ( ! empty($this->data) )
		{
			if ( $this->Comentario->save($this->data) )
			{
				
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				if ( isset($comentario['Comentario']['compra_id']) && $comentario['Comentario']['compra_id'] )
				{
					$this->redirect(array('controller' => 'compras', 'action' => 'si', $comentario['Comentario']['compra_id']));
				}
				else
				{
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		if ( empty($this->data) )
		{
			$this->data = $comentario;
		}
		//$this->Comentario->recursive = 1;
		//$usuarios = $this->Comentario->Usuario->find('list');
		//$this->set(compact('usuarios'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Comentario->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function admin_eliminar($id = null)
	{
		if (! $id)
			die('INVALIDO(1)');
		if (! $this->Auth->user())
			die('NO_PERMISO(2)');

		if ( in_array($this->Auth->user('perfil'), array(3)) )
		{
			$comentario = $this->Comentario->find('first', array('conditions' => array('Comentario.id' => $id),
																 'fields' => array('Comentario.id'),
																 'recursive' => -1));
			if (! $comentario)
				die('INVALIDO(3)');
			if ( $this->Comentario->delete($id) )
				die('READY');
		}
		else
		{
			die('NO_PERMISO(4)');
		}
		die('ERROR(5)');
	}
}
?>