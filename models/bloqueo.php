<?php
class Bloqueo extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Bloqueo';
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
		'talla' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'producto_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			)
		)
	);

	// ASOCIACIONES
	var $belongsTo = array(
		'Producto' => array(
		'foreignKey'			=> 'producto_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Producto')
			)
	);

}
?>