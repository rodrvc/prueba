<?php
class Color extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Color';
	var $displayField	= 'nombre_codigo';
	var $virtualFields	= array('nombre_codigo' => 'CONCAT(Color.nombre, " (", Color.codigo, ")")');

	// BEHAVIORS
	
	var $actsAs			= array(// SLUGS
								'Sluggable'	=> array('label' => 'nombre', 'overwrite' => true, 'length' => 120, 'translation' => 'utf-8'),

								// IMAGE UPLOAD
								//'Image'		=> array('fields' =>
								//					 array('imagen' => array('versions' => array(array('prefix'	=> 'mini',
								//																	   'width'	=> '200',
								//																	   'height'	=> '200',
								//																	   'crop'	=> true
								//																	   )
								//																 ),
								//											 'image_types'	=> array('jpg', 'jpeg', 'gif', 'png')
								//											 )
								//						   )
								//					 )
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
		'codigo' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
			'isUnique' =>array(
				'rule'			=> array('isUnique'),
				'last'			=> true,
				'message'		=> 'Color registrado.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
	);

	// ASOCIACIONES
	var $belongsTo = array(
		'Primario' => array(
			'className'				=> 'Primario',
			'foreignKey'			=> 'primario_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Primario')
		)
	);
	var $hasMany = array(
		'Producto' => array(
			'className'				=> 'Producto',
			'foreignKey'			=> 'color_id',
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