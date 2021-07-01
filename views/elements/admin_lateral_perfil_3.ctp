<?
/** ============== PERFIL SUPER ADMIN ============== */
//Mostramos los links de cada vista
echo "<ul>";
if (($this->action == 'admin_view') && ($this->params['controller']==$controlador)){
	if (! in_array($controlador, array('compras','btn1','descuentos','trabaje_postulantes')))
	{
		echo "<li>";
		echo $html->link(__('Editar '.Inflector::singularize($nombre),true),array('action' => 'edit', $this->params['pass'][0]));
		echo "</li>";
		echo "<li>";
		echo $html->link(__('Eliminar '.Inflector::singularize($nombre), true),array('action' => 'delete', $this->params['pass'][0]),
								  null, sprintf(__('Deseas eliminar # %s?', true), $this->params['pass'][0]));
		echo "</li>";
	}
}
if (($this->action == 'admin_edit')  && ($this->params['controller']==$controlador))
{
	if (! in_array($controlador, array('compras','btn1','descuentos','trabaje_postulantes')))
	{
		echo "<li>";
		echo $html->link(__('Eliminar '.Inflector::singularize($nombre), true),array('action' => 'delete',  $this->params['pass'][0]),
								  null, sprintf(__('Deseas eliminar # %s?', true),  $this->params['pass'][0]));
		echo "</li>";
	}
}

if ( $controlador == 'archivos' )
{
	//echo "<li>";
	//echo $html->link(__('Cargar Stock',true),array('controller' => $controlador,'action' => 'cargar_excel'));
	//echo "</li>";
	echo "<li>";
	echo $html->link(__('Listar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
}
elseif ( $controlador == 'banners' )
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
elseif ( $controlador == 'compras' )
{
	// MENU COMPRAS
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
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_anulado' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Compras Anuladas', true),array('controller' => $controlador,'action' => 'anulado'), array('style' => $style));
	echo "</li>";

    $style = '';
    if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_anulado_stock' )
    {
        $style = 'background-color: #AAAFFF;';
    }
    echo "<li>";
    echo $html->link(__('Compras Anuladas por stock', true),array('controller' => $controlador,'action' => 'anulado_stock'), array('style' => $style));
    echo "</li>";

    $style = '';
    if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_devolucion_activa' )
    {
        $style = 'background-color: #AAAFFF;';
    }
    echo "<li>";
    echo $html->link(__('Compras con devolucion activa', true),array('controller' => $controlador,'action' => 'devolucion_activa'), array('style' => $style));
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
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'multivende' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Multivende',true).' '.$nombre,array('controller' => $controlador,'action' => 'multivende'), array('style' => $style));
	echo "</li>";


		$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_multivende' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Multivende',true).' '.$nombre,array('controller' => $controlador,'action' => 'multivende'), array('style' => $style));
	echo "</li>";


	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_sinpicking' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Compras Sin Picking', true),array('controller' => $controlador,'action' => 'sinpicking'), array('style' => $style));
	echo "</li>";


	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_devuelto3' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Devoluciones cerradas', true),array('controller' => $controlador,'action' => 'devuelto3'), array('style' => 'background-color: blue; color: #fff; font-weight: bold;'));
	echo "</li>";

 /*
	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_devuelto2' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Devoluciones Aprobadas', true),array('controller' => $controlador,'action' => 'devuelto2'), array('style' => 'background-color: blue; color: #fff; font-weight: bold;'));
	echo "</li>"; */

	$style = '';
    if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_devuelto2' )
    {
        $style = 'background-color: #AAAFFF;';
    }
    echo "<li>";
    echo $html->link(__('Devoluciones Rechazadas', true),array('controller' => $controlador,'action' => 'devuelto4'), array('style' => 'background-color: blue; color: #fff; font-weight: bold;'));
    echo "</li>";


	$style = '';
	if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'devoluciones' )
	{
		$style = 'background-color: #AAAFFF;';
	}
	echo "<li>";
	echo $html->link(__('Devoluciones Abiertas Webpay',true),array('controller' => $controlador,'action' => 'devoluciones'), array('style' => 'background-color: blue; color: #fff; font-weight: bold;'));
	echo "</li>";

    $style = '';
    if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'devoluciones_mp' )
    {
        $style = 'background-color: #AAAFFF;';
    }
    echo "<li>";
    echo $html->link(__('Devoluciones Abiertas Mercadopago',true),array('controller' => $controlador,'action' => 'devoluciones_mp'), array('style' => 'background-color: blue; color: #fff; font-weight: bold;'));
    echo "</li>";
     echo "<li>";
    echo $html->link(__('Reporte Estado Devoluciones',true),array('controller' => $controlador,'action' => 'excel_devoluciones'), array('style' => 'background-color: blue; color: #fff; font-weight: bold;'));
    echo "</li>";
      echo "<li>";
    echo $html->link(__('Reporte Anulaciones Aceptadas',true),array('controller' => $controlador,'action' => 'picks'), array('style' => 'background-color: blue; color: #fff; font-weight: bold;'));
    echo "</li>";

	/*
    $style = '';
    if ( $this->params['controller'] == 'compras' && $this->params['action'] == 'admin_devuelto' )
    {
        $style = 'background-color: #AAAFFF;';
    }
    echo "<li>";
    echo $html->link(__('Compras Devueltas Procesar', true),array('controller' => $controlador,'action' => 'devuelto'), array('style' => 'background-color: blue; color: #fff; font-weight: bold;'));
    echo "</li>";
	*/
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
		echo $html->link(__('Generar Excel Retail', true),array('controller' => $controlador,'action' => 'generarexcel4'), array('style' => 'background-color: #008000; color: #fff; font-weight: bold;'));
	echo "</li>";
	echo "<li>";
		echo $html->link(__('Anular Webpay', true),array('controller' => $controlador,'action' => 'admin_webpay_anular'), array('style' => 'background-color: #333333; color: #fff; font-weight: bold;'));
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
	echo "<li>";
	echo $html->link(__('Buscar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
}
elseif ( $controlador == 'tickets' )
{
	echo "<li>";
	echo $html->link(__('Agregar',true).' '.$nombre,array('controller' => $controlador,'action' => 'add'));
	echo "</li>";
	echo "<li>";
	echo $html->link(__('Listar',true).' '.$nombre,array('controller' => $controlador,'action' => 'listar'));
	echo "</li>";
	echo "<li>";
	echo $html->link(__('Buscar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
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
	if ( in_array($authUser['id'], array(2,3,5)) )
	{
		echo "<li>";
		echo $html->link(__('New',true).' '.$nombre,array('controller' => $controlador,'action' => 'nuevos'));
		echo "</li>";
		echo "<li>";
		echo $html->link(__('New (Arreglos)',true),array('controller' => $controlador,'action' => 'admin_nuevos_arreglar'));
		echo "</li>";
		echo "<li>";
		echo $html->link(__('Old',true).' '.$nombre,array('controller' => $controlador,'action' => 'antiguos'));
		echo "</li>";

	echo "<li>";
	echo $html->link(__('No Publicados',true).' '.$nombre,array('controller' => $controlador,'action' => 'no_publicados'), array('style' => $style));
	echo "</li>";
	echo "<li>";
		echo $html->link(__('Generar Excel productos', true),array('controller' => $controlador,'action' => 'excel_productos'), array('style' => 'background-color: #008000; color: #fff; font-weight: bold;'));
	echo "</li>";
	
		echo "<li>";
		echo $html->link(__('Listar Todo',true),array('controller' => $controlador,'action' => 'todos'));
		echo "</li>";
	}
}
elseif ( $controlador == 'tipomaletas' )
{
	echo "<li>";
	echo $html->link(__('Listar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
}
elseif ( $controlador == 'btn1' )
{
	echo "<li>";
	echo $html->link(__('Estadisticas',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
	echo "<li>";
	echo $html->link(__('Ranking',true).' '.$nombre,array('controller' => $controlador,'action' => 'ranking'));
	echo "</li>";
	echo "<li>";
	echo $html->link(__('Premios',true).' '.$nombre,array('controller' => $controlador,'action' => 'premios'));
	echo "</li>";
}
elseif ( $controlador == 'fl' )
{
	echo "<li>";
	echo $html->link(__('Agregar Fanlook',true),array('controller' => $controlador,'action' => 'add'));
	echo "</li>";
	echo "<li>";
	echo $html->link(__('Listar Fanlooks',true),array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
	echo "<li>";
	echo $html->link(__('Estadisticas Fanlook',true),array('controller' => $controlador,'action' => 'estadisticas'));
	echo "</li>";
	echo "<li>";
	echo $html->link(__('Ganadores',true),array('controller' => $controlador,'action' => 'ganadores'));
	echo "</li>";
	echo "<li>";
	echo $html->link(__('Excel Campaña', true),array('controller' => $controlador,'action' => 'excel_concurso'), array('style' => 'background-color: #008000; color: #fff; font-weight: bold;'));
	echo "</li>";
}
elseif ( $controlador == 'trabaje_postulantes' )
{
	echo "<li>";
	echo $html->link(__('Listar',true).' '.$nombre,array('controller' => $controlador,'action' => 'index'));
	echo "</li>";
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