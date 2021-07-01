<?php
class Marca extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Marca';
	var $displayField	= 'nombre';

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
	

	// ASOCIACIONES

	var $hasMany = array(
	'Producto' => array(
			'className'				=> 'Producto',
			'foreignKey'			=> 'marca_id',
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