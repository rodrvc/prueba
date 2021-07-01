<?
/** ============== PERFIL FULL PRODUCTOS ============== */
//Mostramos los links de cada vista
echo "<ul>";

if ( $controlador == 'archivos' )
{
	echo "<li>";
	echo $html->link(__('Cargar Stock',true),array('controller' => $controlador,'action' => 'cargar_excel'));
	echo "</li>";
	echo "<li>";
	echo $html->link(__('Listar',true).' '.$nombre . ' Stock',array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
}
elseif ( $controlador == 'compras' )
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
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_no_pagado' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Compras No Pagadas', true),array('controller' => $controlador,'action' => 'no_pagado'), array('style' => $style));
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
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_anulado' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Compras Anuladas', true),array('controller' => $controlador,'action' => 'anulado'), array('style' => $style));
	echo "</li>";
	
	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_pendiente' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Compras Pendientes', true),array('controller' => $controlador,'action' => 'pendiente'), array('style' => $style));
	echo "</li>";
	
	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_listar' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Listar',true).' '.$nombre,array('controller' => $controlador,'action' => 'listar'), array('style' => $style));
	echo "</li>";
		$style = '';

	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_no_publicados' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('No Publicados',true).' '.$nombre,array('controller' => $controlador,'action' => 'no_publicados'), array('style' => $style));
	echo "</li>";
	
echo "<li>";
	echo $html->link(__('Generar Excel', true),array('controller' => $controlador,'action' => 'generarexcel'), array('style' => 'background-color: #008000; color: #fff; font-weight: bold;'));
	echo "</li>"; 

	echo "<li>";
	echo $html->link(__('Generar Excel Compacto', true),array('controller' => $controlador,'action' => 'generarexcel2'), array('style' => 'background-color: #008000; color: #fff; font-weight: bold;'));
	echo "</li>"; 

	echo "<li>";
	echo $html->link(__('Generar Excel MultiVende', true),array('controller' => $controlador,'action' => 'generarexcel3'), array('style' => 'background-color: #008000; color: #fff; font-weight: bold;'));
	echo "</li>";
    echo "<li>";
    echo $html->link(__('Generar Excel Picks', true),array('controller' => $controlador,'action' => 'picks'), array('style' => 'background-color: #008000; color: #fff; font-weight: bold;'));
    echo "</li>";
}
elseif ( $controlador == 'productos' )
{
	echo "<li>";
	echo $html->link(__('Agregar',true).' '.$nombre,array('controller' => $controlador,'action' => 'add'));
	echo "</li>";
	echo "<li>";
	echo $html->link(__('Listar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
}
elseif ( $controlador == 'descuentos' )
{
	echo "<li>";
	echo $html->link(__('Agregar',true).' '.$nombre,array('controller' => $controlador,'action' => 'add'));
	echo "</li>";
	echo "<li>";
	echo $html->link(__('Listar',true).' '.$nombre,array('controller' => $controlador,'action' => 'listar'));
	echo "</li>";

	if ( in_array($authUser['id'], array(6,37)) )
	{
		echo "<li>";
		echo $html->link(__('Buscar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
		echo "</li>";
	}
}
else
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