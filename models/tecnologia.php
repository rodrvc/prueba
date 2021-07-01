<?php
class Tecnologia extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Tecnologia';
	var $displayField	= 'nombre';

	// BEHAVIORS
	
	var $actsAs			= array(// SLUGS
								//'Sluggable'	=> array('label' => 'nombre', 'overwrite' => true, 'length' => 120, 'translation' => 'utf-8'),

								// IMAGE UPLOAD
								'Image'		=> array('fields' =>
													 array('imagen' => array('versions' => array(array('prefix'	=> 'mini',
																									   'width'	=> '60',
																									   'height'	=> '60',
																									   'crop'	=> true
																									   ),
																								 array('prefix'	=> 'tecno',
																									   'width'	=> '102',
																									   'height'	=> '50',
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
		'imagen' => array(
			'notempty' => array(
				'rule'			=> array('validaImagen'),
				'last'			=> true,
				'message'		=> 'Agregue una imagen desde su equipo.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		)
	);
	
	//validacion personalizada
	function validaImagen($data)
	{
		return ! empty($data['imagen']['name']);
	}

	// ASOCIACIONES
	var $belongsTo = array(
		'ParentTecnologia' => array(
			'className'				=> 'Tecnologia',
			'foreignKey'			=> 'parent_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Tecnologia')
		)
	);
	var $hasMany = array(
		'ChildTecnologia' => array(
			'className'				=> 'Tecnologia',
			'foreignKey'			=> 'parent_id',
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
	var $hasAndBelongsToMany = array(
		'Producto' => array(
			'className'				=> 'Producto',
			'joinTable'				=> 'productos_tecnologias',
			'foreignKey'			=> 'tecnologia_id',
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