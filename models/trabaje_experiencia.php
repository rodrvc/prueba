<?php
class TrabajeExperiencia extends AppModel
{
	var $name		= 'TrabajeExperiencia';
	var $useDbConfig = 'trabaje';
	var $useTable	= 'experiencias';

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
