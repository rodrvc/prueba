<?php
class TrabajePariente extends AppModel
{
	var $name		= 'TrabajePariente';
	var $useDbConfig = 'trabaje';
	var $useTable	= 'parientes';

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
