<?php
class Menu extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Menu';
	var $displayField	= 'categoria_id';

	// BEHAVIORS
	
	/*var $actsAs			= array(// SLUGS
								//'Sluggable'	=> array('label' => 'nombre', 'overwrite' => true, 'length' => 120, 'translation' => 'utf-8'),

								// IMAGE UPLOAD
								'Image'		=> array('fields' =>
													 array('imagen' => array('versions' => array(array('prefix'	=> 'mini',
																									   'width'	=> '80',
																									   'height'	=> '80',
																									   'crop'	=> true
																									   ),
																								 array('prefix'	=> 'grande',
																									   'width'	=> '960',
																									   'height'	=> '419',
																									   'crop'	=> true
																									   ),
																								 array('prefix'	=> 'normal',
																									   'width'	=> '312',
																									   'height'	=> '171',
																									   'crop'	=> true
																									   ),
																								 ),
																			 'image_types'	=> array('jpg', 'jpeg', 'gif', 'png')
																			 )
														   )
													 )
								);
	*/
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
}
?>