<?
/** ============== PERFIL TIENDAS ADMIN ============== */
//Mostramos los links de cada vista
echo "<ul>";
if ( $controlador == 'banners' )
{
	//echo "<li>";
	//echo $html->link(__('Agregar',true).' '.$nombre,array('controller' => $controlador,'action' => 'add'));
	//echo "</li>";
	echo "<li>";
	echo $html->link(__('Listar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
	//echo "<li>";
	//echo $html->link(__('Listar Calugas',true),array('controller' => $controlador,'action' => 'calugas'));
	//echo "</li>";
}
elseif ( $controlador == 'productos' )
{
	echo "<li>";
	echo $html->link(__('Listar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
}
elseif ( $controlador == 'email_blasts' )
{
	echo "<li>";
	echo $html->link(__('Agregar',true).' '.$nombre,array('controller' => $controlador,'action' => 'add'));
	echo "</li>";
	echo "<li>";
	echo $html->link(__('Listar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
}
echo "</ul></li>";


?>