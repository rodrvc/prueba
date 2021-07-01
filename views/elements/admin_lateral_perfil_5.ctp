<?
/** ============== PERFIL TIENDAS ============== */
//Mostramos los links de cada vista
if ( $controlador == 'descuentos' )
{

	echo "<li>";
	echo $html->link(__('Buscar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
}
elseif ( $controlador == 'tickets' )
{
	echo "<li>";
	echo $html->link(__('Buscar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
}


?>