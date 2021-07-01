<?php
class Descuento extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Descuento';
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
		'cantidad' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				'message'		=> 'Ingrese la cantidad de descuentos',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'fecha_caducidad' => array(
			'date' => array(
				'rule'			=> array('date'),
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
				'message'		=> 'Ingrese un codigo al para el descuento',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'contador' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'tipo' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'descuento' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				'message'		=> 'Ingrese Valor (numero entero) para Descuento',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
        'super' => array(
            'numeric' => array(
                'rule'			=> array('numeric'),
                'last'			=> true,
                //'message'		=> 'Ingrese la cantidad de descuentos',
                //'allowEmpty'	=> true,
                //'required'		=> false,
                //'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
            ),
        ),
	);

	// ASOCIACIONES
	var $hasAndBelongsToMany = array(
		'Categoria' => array(
			'className'				=> 'Categoria',
			'joinTable'				=> 'categorias_descuentos',
			'foreignKey'			=> 'descuento_id',
			'associationForeignKey'	=> 'categoria_id',
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
	// ASOCIACIONES
	var $hasMany = array(
		'ClientesTienda' => array(
			'className'				=> 'ClientesTienda',
			'foreignKey'			=> 'descuento_id',
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