<?php
class TrabajeCarga extends AppModel
{
	var $name		= 'TrabajeCarga';
	var $useDbConfig = 'trabaje';
	var $useTable	= 'cargas';

	var $belongsTo	= array(
		'TrabajePostulante' => array(
			'className'				=> 'TrabajePostulante',
			'foreignKey'			=> 'postulante_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'TrabajePostulante')
		),
	);
}
?>
