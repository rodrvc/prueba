<?php
class Tienda extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Tienda';
	var $displayField	= 'nombre';

	// BEHAVIORS
	var $actsAs			= array(// SLUGS
								//'Sluggable'	=> array('label' => 'nombre', 'overwrite' => true, 'length' => 120, 'translation' => 'utf-8'),

								// IMAGE UPLOAD
								'Image'		=> array('fields' =>
													 array('imagen' => array('versions' => array(array('prefix'	=> 'mini',
																									   'width'	=> '135',
																									   'height'	=> '137',
																									   'crop'	=> true
																									   )
																								 ),
																			 'image_types'	=> array('jpg', 'jpeg', 'gif', 'png')
																			 )
														   )
													 )
								);

	// VALIDACIONES
	var $validate = array(
		'nombre' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'telefono' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
	);
	
	var $belongsTo = array(
	'Region' => array(
					 'className'			=> 'Region',
					 'foreignKey'		=> 'region_id',
					 'conditions'		=> '',
					 'fields'			=> '',
					 'order'				=> '',
					 'counterCache'		=> false,
					 'counterScope'		=> array('Asociado.modelo' => 'Region')
					 ),
	'Comuna' => array(
					 'className'			=> 'Comuna',
					 'foreignKey'		=> 'comuna_id',
					 'conditions'		=> '',
					 'fields'			=> '',
					 'order'				=> '',
					 'counterCache'		=> false,
					 'counterScope'		=> array('Asociado.modelo' => 'Comuna')
					 ),
	'Zona' => array(
					 'className'			=> 'Zona',
					 'foreignKey'		=> 'zona_id',
					 'conditions'		=> '',
					 'fields'			=> '',
					 'order'				=> '',
					 'counterCache'		=> false,
					 'counterScope'		=> array('Asociado.modelo' => 'Zona')
					 ),
	);
	var $hasMany = array(
		'Stock' => array(
			'className'				=> 'Stock',
			'foreignKey'			=> 'tienda_id',
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