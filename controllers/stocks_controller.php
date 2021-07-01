<?php
class StocksController extends AppController
{
	var $name = 'Stocks';

	public function codigo_unico()
	{
		$stocks = $this->Stock->find('all', array('limit' => 100));
		prx($stocks);
	}

	function admin_index()
	{
		//$this->Stock->recursive = 0;
		$this->paginate = array('contain' => array('Tienda', 'Producto' => array('Color')));
		$this->set('stocks', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('stock', $this->Stock->read(null, $id));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Stock->create();
			if ( $this->Stock->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$tiendas = $this->Stock->Tienda->find('list', array('order' => 'Tienda.nombre'));
		$productos_aux = $this->Stock->Producto->find('all', array('order' => 'Producto.codigo',
																   'contain' => array('Color' => array('fields' => array('Color.codigo'))),
																   'fields' => array('Producto.id', 'Producto.codigo')));
		$productos = array();
		foreach( $productos_aux as $producto )
		{
			$productos[$producto['Producto']['id']] = $producto['Producto']['codigo'] . '' . $producto['Color']['codigo'];
		}
		$this->set(compact('tiendas', 'productos'));
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
			if ( $this->Stock->save($this->data) )
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
			$this->data = $this->Stock->read(null, $id);
		}
		$tiendas = $this->Stock->Tienda->find('list');
		$productos_aux = $this->Stock->Producto->find('all', array('order' => 'Producto.codigo',
																   'contain' => array('Color' => array('fields' => array('Color.codigo'))),
																   'fields' => array('Producto.id', 'Producto.codigo')));
		$productos = array();
		foreach( $productos_aux as $producto )
		{
			$productos[$producto['Producto']['id']] = $producto['Producto']['codigo'] . '' . $producto['Color']['codigo'];
		}
		$this->set(compact('tiendas', 'productos'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Stock->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>