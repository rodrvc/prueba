<?php
class Producto extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Producto';
	var $displayField	= 'nombre';

	// BEHAVIORS
	
	var $actsAs			= array(// SLUGS
								'Sluggable'	=> array('label' => 'nombre', 'overwrite' => true, 'length' => 120, 'translation' => 'utf-8'),

								// IMAGE UPLOAD
								'Image'		=> array('fields' =>
													 array('foto' => array('versions' => array(array('prefix'	=> 'full',
																									   'width'	=> '428',
																									   'height'	=> '378',
																									   'crop'	=> false
																									   ),
																								 array('prefix'	=> 'mini',
																									   'width'	=> '71',
																									   'height'	=> '63',
																									   'crop'	=> false
																									   ),
																								 array('prefix'	=> 'ith',
																									   'width'	=> '180',
																									   'height'	=> '159',
																									   'crop'	=> false
																									   ),
																								 ),
																			 'image_types'	=> array('jpg', 'jpeg', 'gif', 'png')
																			 ),
														   'foto_categoria' => array('versions' => array(array('prefix'	=> 'mini',
																											   'width'	=> '75',
																											   'height'	=> '63',
																											   'crop'	=> true
																											   )
																										 ),
																					 'image_types'	=> array('jpg', 'jpeg', 'gif', 'png')
																					 ),
														   'imagen_campana' => array('versions' => array(array('prefix'	=> 'mini',
																											   'width'	=> '50',
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
				//'required'	=> false,
				'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'categoria_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'foto' => array(
			'validaFoto' => array(
				'rule'			=> array('validaFoto'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'color_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'codigo' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'precio' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'oferta' => array(
			'boolean' => array(
				'rule'			=> array('boolean'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		//'descripcion_id' => array(
		//	'numeric' => array(
		//		'rule'			=> array('numeric'),
		//		'last'			=> true,
		//		//'message'		=> 'Mensaje de validación personalizado',
		//		//'allowEmpty'	=> true,
		//		//'required'		=> false,
		//		'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
		//	),
		//),
	);

	// ASOCIACIONES
	var $belongsTo = array(
		'Categoria' => array(
			'foreignKey'			=> 'categoria_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Categoria')
		),
		'Marca' => array(
			'foreignKey'			=> 'marca_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Marca')
		),
		'Color' => array(
			'className'				=> 'Color',
			'foreignKey'			=> 'color_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Color')
		),
		'Descripcion' => array(
			'className'				=> 'Descripcion',
			'foreignKey'			=> 'descripcion_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Descripcion')
		),
		'Coleccion' => array(
			'className'				=> 'Coleccion',
			'foreignKey'			=> 'coleccion_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Coleccion')
		),
	);
	
	function validaFoto($data)
	{
		return ! empty($data['foto']['name']);
	}
	var $hasMany = array(
		'Galeria' => array(
			'className'				=> 'Galeria',
			'foreignKey'			=> 'producto_id',
			'dependent'				=> false,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'exclusive'				=> '',
			'finderQuery'			=> '',
			'counterQuery'			=> ''
		),
		'Comentario' => array(
			'className'				=> 'Comentario',
			'foreignKey'			=> 'producto_id',
			'dependent'				=> true,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'exclusive'				=> '',
			'finderQuery'			=> '',
			'counterQuery'			=> ''
		),
		'Talla' => array(
			'className'				=> 'Talla',
			'foreignKey'			=> 'producto_id',
			'dependent'				=> true,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'exclusive'				=> '',
			'finderQuery'			=> '',
			'counterQuery'			=> ''
		),
		'Stock' => array(
			'className'				=> 'Stock',
			'foreignKey'			=> 'producto_id',
			'dependent'				=> true,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'exclusive'				=> '',
			'finderQuery'			=> '',
			'counterQuery'			=> ''
		),
	);
	var $hasAndBelongsToMany = array(
		'Tecnologia' => array(
			'className'				=> 'Tecnologia',
			'joinTable'				=> 'productos_tecnologias',
			'foreignKey'			=> 'producto_id',
			'associationForeignKey'	=> 'tecnologia_id',
			'unique'				=> true,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'finderQuery'			=> '',
			'deleteQuery'			=> '',
			'insertQuery'			=> ''
		),
		'Compra' => array(
			'className'				=> 'Compra',
			'joinTable'				=> 'productos_compras',
			'foreignKey'			=> 'producto_id',
			'associationForeignKey'	=> 'compra_id',
			'unique'				=> true,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'finderQuery'			=> '',
			'deleteQuery'			=> '',
			'insertQuery'			=> ''
		),
		'Minisitio' => array(
			'className'				=> 'Minisitio',
			'joinTable'				=> 'productos_minisitios',
			'foreignKey'			=> 'producto_id',
			'associationForeignKey'	=> 'minisitio_id',
			'unique'				=> true,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'finderQuery'			=> '',
			'deleteQuery'			=> '',
			'insertQuery'			=> ''
		),
	);
}
?>