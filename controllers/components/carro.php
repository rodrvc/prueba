<?php
class CarroComponent extends Object
{
	var $components		= array('Session');
		function initialize(&$controller)
	{
		// CONTROLADOR DE DONDE ES LLAMADO
		$this->Controller	=& $controller;

		// INICIALIZA EL OBJECTO FACEBOOK (ACCESO API)
	}

	function verificarRut($rut = null)
	{
		if (! $rut)
			return false;
		if(! strpos($rut,'-'))
			return false;
		$rut = explode('-',$rut);

		$x = 2;
		$sumatorio=0;
		for ( $i = strlen($rut[0])-1; $i>=0; $i--)
		{
			if ( $x > 7 )
				$x = 2;
			$sumatorio = $sumatorio + ( $rut[0][$i] * $x );
			$x++;
		}
		$digito = bcmod( $sumatorio,11 );
		$digito = 11 - $digito;

		if ($digito==10)
			$digito='K';
		elseif ($digito==11)
			$digito='0';

		if (strtoupper($rut[1])==$digito)
			return true;
		return false;
	}

	function agregar($id = null)
	{
		if ( ! $id )
			return false;

		if ( $this->Session->read("Carro.{$id}") )
		{
			$cantidad		= $this->Session->read("Carro.{$id}.cantidad") + 1;
			$this->Session->write("Carro.{$id}.cantidad", $cantidad);
		}
		else
			$this->Session->write("Carro.{$id}.cantidad", 1);
	}

	function eliminar($id = null)
	{
		if ( ! $id )
			return false;

		return $this->Session->delete("Carro.{$id}");
	}

	function productos()
	{
		$productos		= array();

		if ( $this->Session->read('Carro') )
			foreach ( $this->Session->read('Carro') as $id => $producto )
			{
				$productos[$id]	= $producto['cantidad'];
			}

		return $productos;
	}

	function actualiza($id = null, $cantidad = 1)
	{
		$cantidad		= ($cantidad == 0 ? 1 : $cantidad);

		if ( ! $id || ! is_numeric($id) || ! $this->Session->read("Carro.{$id}") )
			return array('Tipo' => 'ERROR');
		else
		{
			$this->Session->write("Carro.{$id}.cantidad", $cantidad);
			return array('Tipo' => 'OK', 'Cantidad' => $cantidad);
		}
	}

	function vaciar()
	{
		$this->Session->delete('Carro');
	}
	
	function dias_habiles($dias = 3)
	{
		$hoy = strtotime(date('Y-m-d H:i:s'));
		// $hoy = strtotime('2016-09-20 00:00:00');
		$hasta = date('Y-m-d H:i:s', $hoy);
		if ($dias)
		{
			$feriados = array();
			$feriadosModel = ClassRegistry::init('Feriado');
			$fijos = $feriadosModel->find('list', array(
				'conditions' => array(
					'Feriado.repetir' => 1
				),
				'fields' => array(
					'Feriado.fecha',
					'Feriado.fecha'
				)
			));
			if ($fijos)
			{
				foreach ($fijos as $feriado)
				{
					array_push($feriados, date('m-d', strtotime($feriado)));
				}
			}
			$anuales = $feriadosModel->find('list', array(
				'conditions' => array(
					'Feriado.repetir' => 0,
					'Feriado.fecha >=' => date('Y-m-d', ($hoy - (86400 * 60))),
					'Feriado.fecha <=' => date('Y-m-d', ($hoy + (86400 * 60))),
				),
				'fields' => array(
					'Feriado.fecha',
					'Feriado.fecha'
				)
			));
			if ($anuales)
			{
				foreach ($anuales as $feriado)
				{
					array_push($feriados, date('m-d', strtotime($feriado)));
				}
			}

			$diasHabiles = array(1,2,3,4,5);// lunes martes miercoles jueves viernes
			$cont = $x = 1;
			while($cont <= $dias)
			{
				// $hasta = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) - (60 * 60 * 24 * $x));
				$hasta = date('Y-m-d', ($hoy - (86400 * $x))).' 00:00:00';
				$dia = date('N',strtotime($hasta));
				if (in_array((int)$dia,$diasHabiles))
				{
					$dia = date('m-d', strtotime($hasta));
					if ($feriados)
					{
						if (! in_array($dia, $feriados))
							$cont++;
					}
					else
					{
						$cont++;
					}
				}

				$x++;
				if ($x > 30)
					break;
			}
		}
		return $hasta;
	}

	function imagen($imagen = null)
	{
		
			$respuesta = 'https://s3.amazonaws.com/andain-sckechers/img/'.$imagen;
			//$respuesta = 'http://store.skechers-chile.cl/img/'.$imagen;
		
		return $respuesta;
	}
	function talla($talla)
	{
		if ($talla == 1)
			$talla = 'XS';
		elseif ($talla == 2)
			$talla = 'S';
		elseif ($talla == 3)
			$talla = 'M';
		elseif ($talla == 4)
			$talla = 'L';
		elseif ($talla == 5)
			$talla = 'XL';
		elseif ($talla == 6)
			$talla = 'XXL';
		elseif ($talla == 100)
			$talla = 'One';
		return $talla;

	}
}