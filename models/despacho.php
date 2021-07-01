<?php
class Despacho extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Despacho';
	var $displayField	= 'id';

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
		'calle' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				'message'		=> 'Ingrese la calle de su domicilio.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'numero' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				'message'		=> 'Ingrese el número de su domicilio.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'ciudad' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				'message'		=> 'Ingrese la ciudad de su domicilio.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'telefono' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				'message'		=> 'Ingrese la calle de su domicilio.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'region_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
	);

	// ASOCIACIONES
	var $belongsTo = array(
		'Usuario' => array(
			'className'				=> 'Usuario',
			'foreignKey'			=> 'usuario_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Usuario')
		),
		'Direccion' => array(
			'className'				=> 'Direccion',
			'foreignKey'			=> 'direccion_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Direccion')
		)
	);
	var $hasMany = array(
		'Compra' => array(
			'className'				=> 'Compra',
			'foreignKey'			=> 'despacho_id',
			'dependent'				=> false,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'exclusive'				=> '',
			'finderQuery'			=> '',
			'counterQuery'			=> ''
		)
	);
}
?>