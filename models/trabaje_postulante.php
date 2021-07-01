<?php
class TrabajePostulante extends AppModel
{
	var $name		= 'TrabajePostulante';
	var $useDbConfig = 'trabaje';
	var $useTable	= 'postulantes';

	var $hasMany = array(
		'TrabajeCarga' => array(
			'className'				=> 'TrabajeCarga',
			'foreignKey'			=> 'postulante_id',
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
		'TrabajeExperiencia' => array(
			'className'				=> 'TrabajeExperiencia',
			'foreignKey'			=> 'postulante_id',
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
		'TrabajePariente' => array(
			'className'				=> 'TrabajePariente',
			'foreignKey'			=> 'postulante_id',
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
		'TrabajeReferencia' => array(
			'className'				=> 'TrabajeReferencia',
			'foreignKey'			=> 'postulante_id',
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
	);
}
?>
