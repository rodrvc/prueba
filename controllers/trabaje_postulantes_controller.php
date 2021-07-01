<?php
class TrabajePostulantesController extends AppController
{
	var $name = 'TrabajePostulantes';

	function admin_index()
	{
		$this->TrabajePostulante->recursive = 0;
		$this->set('postulantes', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$options = array(
			'conditions' => array(
				'TrabajePostulante.id' => $id
			),
			'contain' => array(
				'TrabajeCarga',
				'TrabajeExperiencia',
				'TrabajePariente',
				'TrabajeReferencia'
			)
		);
		$postulante = $this->TrabajePostulante->find('first',$options);
		$this->set(compact('postulante'));
	}
}
?>