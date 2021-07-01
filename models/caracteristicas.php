<?php
class Caracteristica extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Caracteristica';
	var $displayField	= 'nombre';

	// BEHAVIORS
	


	
	var $hasMany = array(
		'Filtro' => array(
			'className'				=> 'Filtro',
			'foreignKey'			=> 'caracteristica_id',
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
			'className'				=> 'Producto',
			'joinTable'				=> 'productos_cateristica',
			'foreignKey'			=> 'caracteristica_id',
			'associationForeignKey'	=> 'producto_id',
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