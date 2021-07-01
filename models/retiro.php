<?php
class Retiro extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Retiro';
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


	// ASOCIACIONES
	var $belongsTo = array(
		'Tipo' => array(
			'className'				=> 'Tipo',
			'foreignKey'			=> 'tipo_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Tipo')
		),
		'Region' => array(
			'className'				=> 'Region',
			'foreignKey'			=> 'region_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Region')
		),
		'Comuna' => array(
			'className'				=> 'Comuna',
			'foreignKey'			=> 'comuna_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Comuna')
		),




		);

}
?>