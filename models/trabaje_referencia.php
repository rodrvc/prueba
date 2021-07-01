<?php
class TrabajeReferencia extends AppModel
{
	var $name		= 'TrabajeReferencia';
	var $useDbConfig = 'trabaje';
	var $useTable	= 'referencias';

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
