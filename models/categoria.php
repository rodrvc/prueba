<?php
class Categoria extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Categoria';
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
		'sexo_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
	);

	// ASOCIACIONES
	var $belongsTo = array(
		'ParentCategoria' => array(
			'className'				=> 'Categoria',
			'foreignKey'			=> 'parent_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Categoria')
		),
		'Sexo' => array(
			'className'				=> 'Sexo',
			'foreignKey'			=> 'sexo_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Sexo')
		)
	);
	var $hasMany = array(
		'ChildCategoria' => array(
			'className'				=> 'Categoria',
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
		),
		'Producto' => array(
			'className'				=> 'Producto',
			'foreignKey'			=> 'categoria_id',
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
		'Descuento' => array(
			'className'				=> 'Descuento',
			'joinTable'				=> 'categorias_descuentos',
			'foreignKey'			=> 'categoria_id',
			'associationForeignKey'	=> 'descuento_id',
			'unique'				=> false,
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