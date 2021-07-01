<?
/** ============== PERFIL FULL COMPRAS ============== */
//Mostramos los links de cada vista
echo "<ul>";
if ( $controlador == 'compras' )
{
	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_index' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Compras por Despachar', true),array('controller' => $controlador,'action' => 'index'), array('style' => $style));
	echo "</li>";
	
	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_pagadas' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Compras Pagadas', true),array('controller' => $controlador,'action' => 'pagadas'), array('style' => $style));
	echo "</li>";
	
	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_devuelto' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Compras Devueltas', true),array('controller' => $controlador,'action' => 'devuelto'), array('style' => $style));
	echo "</li>";
	
	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_listar' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Listar',true).' '.$nombre,array('controller' => $controlador,'action' => 'listar'), array('style' => $style));
	echo "</li>";
}
elseif ( $controlador == 'descuentos' )
{
	echo "<li>";
	echo $html->link(__('Buscar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
}
echo "</ul></li>";


?>