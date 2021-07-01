<?php
class ShapeupsHelper extends AppHelper
{
	var $helpers	= array('Number', 'Html');


	/***
	 * Formatea numeros a tipo moneda
	 *
	 * @param	double		$valor		Numero a formatear
	 * @param	string		$before		Texto a mostrar antes del numero (opcional)
	 * @return	string					Numero formateado
	 * @access	public
	 */

	function talla_ropa($talla)
	{
		if ($talla == 1)
				$talla_ropa = 'XS';
		elseif ($talla == 2)
			$talla_ropa = 'S';
		elseif ($talla == 3)
			$talla_ropa = 'M';
		elseif ($talla == 4)
			$talla_ropa = 'L';
		elseif ($talla == 5)
			$talla_ropa = 'XL';
		elseif ($talla == 6)
			$talla_ropa = 'XXL';
		elseif ($talla == 100)
			$talla_ropa = 'One';
		else
			$talla_ropa = $talla;
		return $talla_ropa;

	}
	function moneda($valor, $before = null)
	{
		$opciones		= array('places' => 0, 'decimals' => ',', 'thousands' => '.', 'before' => (! is_null($before) ? $before : '$'));
		return $this->Number->format($valor, $opciones);
	}
	function cuotas($valor)
	{
		$cuotas = 12;
		$valor_cuota = (int)($valor/$cuotas);
		return $cuotas.' cuotas sin interés de
                  <br>
                   '.$this->moneda($valor_cuota);
	}

	function imagen($imagen = null)
	{
		//$respuesta = $this->webroot.'img/'.$imagen;
		//if (! file_exists($_SERVER['DOCUMENT_ROOT'].$this->webroot.'webroot/img/'.$imagen))
		//{
		//	$respuesta = 'http://www.skechers.cl/img/'.$imagen;
		//	//$respuesta = 'http://store.skechers-chile.cl/img/'.$imagen;
		//}
		//return $respuesta;
		//$respuesta = 'https://s3.amazonaws.com/andain-sckechers/img/'.$imagen;
		//$respuesta = 'http://localhost/tecno/img/'.$imagen;
		$respuesta = 'https://www.tecnobuy.cl/img/'.$imagen;
		return $respuesta;
	}

	function eventos($parametros = array())
	{
		$ahora = date('Y-m-d H:i:s');
		$ahora = strtotime($ahora);
		$desface = array(
			//'time' => 3600,
			//'mas' => false
		);
		// ejemplo:
		$_evento = array(
			'lb' => array(
				'imagen' => false,
				'url' => false,
				'open' => false,
				'style' => false
			),
			'header' => array(
				'imagen' => false,
				'url' => false
			),
			'css' => false,
			'banner' => false,
			'inicio' => false,
			'fin' => false,
			'menu' => false
		);

		/* CYBERDAY2016 */
		$_evento = array(
			'header' => array(
				'imagen' => $this->imagen('cybermonday2016/header_cybermonday.jpg'),
				'url' => false
			),
			'inicio' => '2016-11-06 23:30:00',
			'fin' => '2016-11-09 23:59:59',
			'css' => 'skechers-bootstrap-cybermonday',
			'banner' => false,
		);
		// considerar desface de tiempo del servidor...
		if ( isset($desface['time']) && $desface['time'] )
		{
			if (  isset($desface['mas']) && $desface['mas']  )
				$ahora += $desface['time'];
			else
				$ahora -= $desface['time'];
		} 

		// validar evento
		$activarEvento = true;


		if (! $activarEvento)
		{
			return false;
		}

		if (isset($parametros['menu_categorias']))
		{
			$menu_categorias = $parametros['menu_categorias'];
			$_evento['menu'] = array(

				-3 => array(
					'activo'		=> true,
					'titulo'		=> '<i class="fa fa-question-circle fa-2" aria-hidden="true"></i>&nbsp;Preguntas Frecuentes',
					'url'			=> $this->Html->url(array('controller' => 'pages', 'action' => 'display','faq')),
					'class'			=> 'hidden-xs hidden-sm hidden-md hidden-lg nuevo_item  yamm-fw',
				),
				-2 => array(
					'activo'		=> true,
					'url'			=> 'https://s3.amazonaws.com/andain-sckechers/politicas_skechers.pdf',
					'titulo'		=> '<i class="fa fa-file-text fa-6" aria-hidden="true"></i>&nbsp;Políticas de despacho ',
					'class'			=> 'hidden-xs hidden-sm hidden-md hidden-lg nuevo_item yamm-fw',
				),
				-1 => array(
					'activo'		=> true,
					'url'			=> $this->Html->url(array('controller' => 'compras', 'action' => 'estado_despacho')),
					'titulo'		=> '<i class="fa fa-truck" aria-hidden="true"></i>&nbsp;Estado de despacho',
					'class'			=> 'hidden-xs hidden-sm hidden-md hidden-lg nuevo_item yamm-fw',
				),
				0 => array(
					'activo'		=> true,
					'url'			=> $this->Html->url(array('controller' => 'compras', 'action' => 'detalle_boleta')),
					'titulo'		=> '<i class="fa fa-ticket" aria-hidden="true"></i>&nbsp;Mi boleta',
					'class'			=> 'hidden-xs hidden-sm hidden-md hidden-lg nuevo_item yamm-fw',
				),
				1 => array(
					'activo'		=> true,
					'titulo'		=> 'WOMEN',
					'subtitulo'		=> 'MUJERES',
					'class'			=> 'dropdown yamm-fw',
					'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', $menu_categorias[1])),
					'tallas'		=> true,
					'colores'		=> true,
					'styles'		=> true
				),
				2 => array(
					'activo'		=> true,
					'titulo'		=> 'MEN',
					'subtitulo'		=> 'HOMBRES',
					'class'			=> 'dropdown yamm-fw',
					'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', $menu_categorias[2])),
					'tallas'		=> true,
					'colores'		=> true,
					'styles'		=> true
				),
				3 => array(
					'activo'		=> true,
					'titulo'		=> 'BOYS',
					'subtitulo'		=> 'NIÑOS',
					'class'			=> 'dropdown yamm-fw',
					'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', $menu_categorias[3])),
					'tallas'		=> true,
					'colores'		=> true,
					'styles'		=> true
				),
				4 => array(
					'activo'		=> true,
					'titulo'		=> 'GIRLS',
					'subtitulo'		=> 'NIÑAS',
					'class'			=> 'dropdown yamm-fw',
					'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', $menu_categorias[4])),
					'tallas'		=> true,
					'colores'		=> true,
					'styles'		=> true
				),
				5 => array(
					'activo'		=> false,
					'titulo'		=> 'PERFORMANCE',
					'subtitulo'		=> 'PERFORMANCE',
					'class'			=> '',
					'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo','performance')),
					'tallas'		=> false,
					'colores'		=> false
				),
				'cyber2016' => array(
					'activo'		=> true,
					'evento'		=> true,
					'titulo'		=> 'CYBERDAY',
					'subtitulo'		=> 'CYBERDAY',
					'imagen'		=> 'https://s3.amazonaws.com/andain-sckechers/img/cybermonday2016/boton-azul.png',
					'css' 			=> 'skechers-bootstrap-cybermonday',
					'class'			=> 'cybermonday2016',
					'url'			=> $this->Html->url(array('controller' => 'cyber')),
					'tallas'		=> false,
					'colores'		=> false
				),
				7 => array(
			'activo'		=> true,
			'titulo'		=> 'ROPA',
			'subtitulo'		=> 'ROPA',
			'class'			=> '',
			'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'ropa')),
			'tallas'		=> false,
			'colores'		=> false
		),
		8 => array(
			'activo'		=> true,
			'titulo'		=> 'RELOJES',
			'subtitulo'		=> 'RELOJES',
			'class'			=> 'dropdown yamm-fw',
			'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'reloj')),
			'tallas'		=> false,
			'colores'		=> false
		),
				9 => array(
					'activo'		=> true,
					'titulo'		=> 'TIENDAS',
					'subtitulo'		=> 'TIENDAS',
					'class'			=> '',
					'url'			=> $this->Html->url(array('controller' => 'tiendas', 'action' => 'index')),
					'tallas'		=> false,
					'colores'		=> false
				),
			);
		}
		if ($this->params['controller'] == 'productos')
		{
			if ($this->params['action'] == 'inicio')
			{
				if (isset($_evento['lb']))
				{
					$_evento['lb']['open'] = true;
				}
			}
			if ($this->params['action'] == 'cybermonday2016')
			{
				$_evento['banner'] = $this->imagen('cybermonday2016/cyberday_opt.jpg');
				//$_evento['banner'] = $this->imagen('cybermonday2016/cyberday_opt.jpg'),
			}
		}
		return $_evento;
	}
}
?>
