<?php
class EstilosController extends AppController
{
	var $name = 'Estilos';

	function admin_index()
	{
		$settings = array(
			'recursive' => 0
		);
		if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
		{
			$settings['conditions'] = array(
				'Estilo.categoria_id' => $this->params['url']['categoria']
			);
			$this->data['Filtro']['categoria_id'] = $this->params['url']['categoria'];
		}
		$this->paginate = $settings;
		$estilos = $this->paginate();

		$categorias = array();
		$this->loadModel('Categoria');
		$options = array(
			'conditions' => array(
				'Categoria.publico' => 1,
				'Categoria.parent_id' => null
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.nombre'
			),
			'order' => array('Categoria.nombre' => 'ASC')
		);
		if ($padres = $this->Categoria->find('list',$options))
		{
			foreach ($padres as $categoriaId => $categoriaName)
			{
				$options = array(
					'conditions' => array(
						'Categoria.publico' => 1,
						'Categoria.parent_id' => $categoriaId
					),
					'fields' => array(
						'Categoria.id',
						'Categoria.nombre'
					),
					'order' => array('Categoria.nombre' => 'ASC')
				);
				if ($hijos = $this->Categoria->find('list',$options))
				{
					$categorias[$categoriaName] = $hijos;
				}
				else
				{
					$categorias[$categoriaId] = $categoriaName;
				}
			}
		}
		$this->set(compact('estilos', 'categorias'));
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('estilo', $this->Estilo->read(null, $id));
	}

	function admin_add()
	{

		if ( ! empty($this->data) )
		{
			$this->Estilo->create();
			if ( $this->Estilo->save($this->data) )
			{

				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$this->loadModel('Categoria');
		$categorias = array();
		$options = array(
			'conditions' => array(
				'Categoria.publico' => 1,
				'OR' => array(
					array('Categoria.parent_id' => null),
					array('Categoria.parent_id' => 0)
				)
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.nombre'
			),
			'contain' => array(
				'ChildCategoria' => array(
					'fields' => array(
						'ChildCategoria.id',
						'ChildCategoria.nombre',
						'ChildCategoria.parent_id'
					),
				   'order' => array(
						'ChildCategoria.nombre' => 'ASC'
					)
				)
			),
			'order' => array(
				'Categoria.nombre' => 'ASC'
			)
		);
		$lista_categorias = $this->Categoria->find('all',$options);
		if ($lista_categorias)
		{
			foreach ($lista_categorias as $categoria)
			{
				if ($categoria['ChildCategoria'])
				{
					$subcategorias = array();
					foreach ($categoria['ChildCategoria'] as $subcategoria)
					{
						$subcategorias[$subcategoria['id']] = ' - ' . $subcategoria['nombre'];
					}
					$categorias[$categoria['Categoria']['id']] = $categoria['Categoria']['nombre'];
					$categorias = array_merge($categorias, $subcategorias);
				}
				else
				{
					$categorias[$categoria['Categoria']['id']] = $categoria['Categoria']['nombre'];
				}
			}
		}
		$this->set(compact('categorias'));
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
			if ( $this->Estilo->save($this->data) )
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
			$this->data = $this->Estilo->read(null, $id);
		}
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Estilo->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>