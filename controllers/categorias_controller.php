<?php
class CategoriasController extends AppController
{
	var $name = 'Categorias';

	function admin_index()
	{
		$this->Categoria->recursive = 0;
		$this->set('categorias', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$categoria = $this->Categoria->find('first', array('conditions' => array('Categoria.id' => $id),
														   'fields' => array('Categoria.id',
																			 'Categoria.nombre',
																			 'Categoria.parent_id',
																			 'Categoria.sexo_id',
																			 'Categoria.desde',
																			 'Categoria.hasta',
																			 'Categoria.medios',
																			 'Categoria.publico',
																			 'Categoria.created'),
														   'contain' => array('ParentCategoria' => array('fields' => array('ParentCategoria.id',
																														   'ParentCategoria.nombre')),
																			  'ChildCategoria' => array('fields' => array('ChildCategoria.id',
																														  'ChildCategoria.nombre')),
																			  'Sexo' => array('fields' => array('Sexo.id',
																												'Sexo.nombre')),
																			  'Producto' => array('fields' => array('Producto.id',
																													'Producto.nombre',
																													'Producto.codigo',
																													'Producto.codigo_completo'))
																			  )));
		if ( ! $categoria )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set(compact('categoria'));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Categoria->create();
			if ( $this->Categoria->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$this->Categoria->recursive = 1;
		$categorias = $this->Categoria->find('list', array('conditions' => array('Categoria.parent_id' => NULL),
														   'order' => array('Categoria.nombre' => 'ASC')));
		$sexos = $this->Categoria->Sexo->find('list');
		$this->set(compact('categorias', 'sexos'));
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
			if ( $this->Categoria->save($this->data) )
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
			$this->data = $this->Categoria->read(null, $id);
		}
		$this->Categoria->recursive = 1;
		$categorias = $this->Categoria->find('list', array('conditions' => array('Categoria.parent_id' => NULL),
														   'order' => array('Categoria.nombre' => 'ASC')));
		$sexos = $this->Categoria->Sexo->find('list');
		$this->set(compact('categorias', 'sexos'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$categoria = $this->Categoria->find('first', array('conditions' => array('Categoria.id'),
														   'fields' => array('Categoria.id'),
														   'recursive' => -1));
		if ($categoria)
		{
			if ( $this->Categoria->delete($id) )
			{
				$this->Session->setFlash(__('Registro eliminado', true));
				$this->redirect($this->referer());
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
				$this->redirect(array('action' => 'index'));
			}
		}
		else
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
	}
	
	function admin_limpiar_categorias()
	{
		$lista = array();
		$consulta =	'SELECT '.
						'Categoria.id,'.
						'Categoria.nombre,'.
						'Categoria.parent_id,'.
						'Categoria.publico,'.
						'(COUNT(Producto.categoria_id)) AS count '.
					'FROM '.
						'sitio_categorias AS Categoria '.
						'LEFT JOIN sitio_productos AS Producto ON (Producto.categoria_id = Categoria.id) '.
					'WHERE '.
						'Categoria.parent_id IS NULL OR Categoria.parent_id = 0 '.
					'GROUP BY Categoria.id ORDER BY Categoria.nombre ASC';
		$categorias = $this->Categoria->query($consulta);
		if ($categorias)
		{
			foreach ($categorias as $index => $categoria)
			{
				$lista[$categoria['Categoria']['id']] = $categoria['Categoria']['id'];
				$hijos = array();
				$cantidad = $categoria[0]['count'];
				if (! $categoria['Categoria']['parent_id'])
				{
					$consulta =	'SELECT '.
									'Categoria.id,'.
									'Categoria.nombre,'.
									'Categoria.parent_id,'.
									'Categoria.publico,'.
									'(COUNT(Producto.categoria_id)) AS count '.
								'FROM '.
									'sitio_categorias AS Categoria '.
									'LEFT JOIN sitio_productos AS Producto ON (Producto.categoria_id = Categoria.id) '.
								'WHERE '.
										'Categoria.parent_id = '.$categoria['Categoria']['id'].' '.
								'GROUP BY Categoria.id ORDER BY Categoria.nombre ASC';
					$hijos = $this->Categoria->query($consulta);
					if ($hijos)
					{
						
						foreach ($hijos as $hijo)
						{
							$lista[$hijo['Categoria']['id']] = $hijo['Categoria']['id'];
							$cantidad = $cantidad+$hijo[0]['count'];
						}
					}
				}
				$categorias[$index][0]['count'] = $cantidad;
				$categorias[$index]['Categoria']['hijos'] = $hijos;
			}
		}
		if ($lista)
		{
			$lista = implode(',',$lista);
			$consulta =	'SELECT '.
							'Categoria.id,'.
							'Categoria.nombre,'.
							'Categoria.parent_id,'.
							'Categoria.publico,'.
							'(COUNT(Producto.categoria_id)) AS count '.
						'FROM '.
							'sitio_categorias AS Categoria '.
							'LEFT JOIN sitio_productos AS Producto ON (Producto.categoria_id = Categoria.id) '.
						'WHERE '.
								'Categoria.id NOT IN ('.$lista.') '.
						'GROUP BY Categoria.id ORDER BY Categoria.nombre ASC';
			$categorias_error = $this->Categoria->query($consulta);
		}
		$this->set(compact('categorias', 'categorias_error'));
	}
}
?>