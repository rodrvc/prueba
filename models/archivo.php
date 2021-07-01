<?php
class Archivo extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Archivo';

	// BEHAVIORS
	var $actsAs			= array(// SLUGS
								//'Sluggable'	=> array('label' => 'nombre', 'overwrite' => true, 'length' => 120, 'translation' => 'utf-8'),

								// IMAGE UPLOAD
								'Image'		=> array('fields' => array('nombre' => array('image_types'	=> array('csv'))))
								);

	// VALIDACIONES
	var $validate = array(
		'administrador_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'nombre' => array(
			'archivoCsv' => array(
				'rule'			=> array('archivoCsv'),
				'last'			=> true,
				'message'		=> 'El archivo debe estar en formato CSV',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
	);

	function archivoCsv($data)
	{
		$tipo	= $data[array_shift(array_keys($data))]['type'];
		$ext	= array_pop(explode('.', $data[array_shift(array_keys($data))]['name']));
		
		return ( ( $tipo == 'application/vnd.ms-excel' && $ext == 'csv' ) || ( $tipo == 'text/comma-separated-values' && $ext == 'csv' ) || ( $tipo == 'text/csv' && $ext == 'csv' ) );
	}

	// ASOCIACIONES
	var $belongsTo = array(
		'Administrador' => array(
			'className'				=> 'Administrador',
			'foreignKey'			=> 'administrador_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Administrador')
		)
	);
}
?>