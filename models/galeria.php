<?php
class Galeria extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Galeria';

	// BEHAVIORS
	
	var $actsAs			= array(// SLUGS
								//'Sluggable'	=> array('label' => 'nombre', 'overwrite' => true, 'length' => 120, 'translation' => 'utf-8'),

								// IMAGE UPLOAD
								'Image'		=> array('fields' =>
												array('imagen' => array('versions' => array(array('prefix'	=> 'full',
																								'width'	=> '428',
																								'height'	=> '378',
																								'crop'	=> true
																								),
																							array('prefix'	=> 'ith',
																								'width'	=> '180',
																								'height'	=> '159',
																								'crop'	=> true
																								),
																							array('prefix'	=> 'mini',
																								'width'	=> '71',
																								'height'	=> '63',
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
		'imagen' => array(
			'validaFoto' => array(
				'rule'			=> array('validaFoto'),
				'last'			=> true,
				'message'		=> 'Escoja una imagen de su equipo',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		
	);
	
	// VALIDACION PERSONALIZADA
	function validaFoto($data)
	{
		return ! empty($data['imagen']['name']);
	}

	// ASOCIACIONES
	var $belongsTo = array(
		'Producto' => array(
			'className'				=> 'Producto',
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