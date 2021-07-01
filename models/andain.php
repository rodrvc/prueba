<?php
class Andain extends AppModel
{
	var $name		= 'Andain';
	var $useTable	= false;

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo	= array(
		'Producto'			=> array('className' => 'Producto')
	);
}
?>
