<?php
class Minisitio extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Minisitio';
	var $displayField	= 'nombre';

	// BEHAVIORS
	
	var $actsAs			= array(// SLUGS
								//'Sluggable'	=> array('label' => 'nombre', 'overwrite' => true, 'length' => 120, 'translation' => 'utf-8'),

								// IMAGE UPLOAD
								'Image'		=> array('fields' =>
													 array('imagen' => array('versions' => array(array('prefix'	=> 'mini',
																									   'width'	=> '60',
																									   'height'	=> '40',
																									   'crop'	=> true
																									   ),
																								 array('prefix'	=> 'sitio',
																									   'width'	=> '392',
																									   'height'	=> '195',
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
				'message'		=> 'Ingrese un nombre',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
	);
	
	// validacion personalizada
	function validaFoto($data)
	{
		return ! empty($data['imagen']['name']);
	}

	// ASOCIACIONES
	var $hasAndBelongsToMany = array(
		'Productos' => array(
			'className'				=> 'Productos',
			'joinTable'				=> 'productos_minisitios',
			'foreignKey'			=> 'minisitio_id',
			'associationForeignKey'	=> 'producto_id',
			'unique'				=> true,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'finderQuery'			=> '',
			'deleteQuery'			=> '',
			'insertQuery'			=> ''
		)
	);
}
?>