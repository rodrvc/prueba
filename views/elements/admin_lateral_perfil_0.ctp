<?
/** ============== PERFIL VER COMPRAS ============== */
//Mostramos los links de cada vista
echo "<ul>";
	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_index' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Compras por Despachar', true),array('controller' => 'compras','action' => 'index'), array('style' => $style));
	echo "</li>";
	
	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_pagadas' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Compras Pagadas', true),array('controller' => 'compras','action' => 'pagadas'), array('style' => $style));
	echo "</li>";
	
	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_no_pagado' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Compras No Pagadas', true),array('controller' => 'compras','action' => 'no_pagado'), array('style' => $style));
	echo "</li>";
	
	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_listar' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Listar Compras',true),array('controller' => 'compras','action' => 'listar'), array('style' => $style));
	echo "</li>";
	echo "<li>";
		echo $html->link(__('Generar Excel Retail', true),array('controller' => $controlador,'action' => 'generarexcel4'), array('style' => 'background-color: #008000; color: #fff; font-weight: bold;'));
	echo "</li>";
		echo "<li>";
		echo $html->link(__('Generar Excel productos', true),array('controller' => 'productos','action' => 'excel_productos'), array('style' => 'background-color: #008000; color: #fff; font-weight: bold;'));
	echo "</li>";

echo "</ul></li>";


?>