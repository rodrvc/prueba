<?php
class Ticket extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Ticket';
	var $displayField	= 'nombre';

	// BEHAVIORS
	/*
	var $actsAs			= array(// SLUGS
								'Sluggable'	=> array('label' => 'nombre', 'overwrite' => true, 'length' => 120, 'translation' => 'utf-8'),

								// IMAGE UPLOAD
								'Image'		=> array('fields' =>
													 array('imagen' => array('versions' => array(array('prefix'	=> 'mini',
																									   'width'	=> '200',
																									   'height'	=> '200',
																									   'crop'	=> true
																									   )
																								 ),
																			 'image_types'	=> array('jpg', 'jpeg', 'gif', 'png')
																			 )
														   )
													 )
								);
	*/

	// VALIDACIONES
	var $validate = array(
		'codigo' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				'message'		=> 'Ingrese Codigo',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'numero_guia' => array(
			'numeric' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				'message'		=> 'Ingrese Numero de Guia',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'fecha_guia' => array(
			'date' => array(
				'rule'			=> array('date'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		)
	);

	// ASOCIACIONES
var $belongsTo = array(
	'Administrador' => array(
					 'className'		=> 'Administrador',
					 'foreignKey'		=> 'administrador_id',
					 'conditions'		=> '',
					 'fields'			=> '',
					 'order'				=> '',
					 'counterCache'		=> false,
					 'counterScope'		=> array('Asociado.modelo' => 'Administrador')
					 )
	);
}
?>