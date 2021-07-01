<?php
class Filtro extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Filtro';
	var $displayField	= 'nombre';

	// BEHAVIORS
	var $belongsTo = array(
		'Caracteristica' => array(
			'className'				=> 'Caracteristica',
			'foreignKey'			=> 'categoria_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Caracteristica')
		),
		'Categoria' => array(
			'className'				=> 'Categoria',
			'foreignKey'			=> 'categoria_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Categoria')
		)
	);
	var $hasMany = array(
		'Rango' => array(
			'className'				=> 'Rango',
			'foreignKey'			=> 'filtro_id',
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