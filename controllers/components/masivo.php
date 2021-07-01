<?php
class MasivoComponent extends Object
{
	/***
	 * Entrega el numero de lineas del archivo
	 * 
	 * @param	string		$archivo		Nombre del archivo
	 * @return	mixed						false si no es archivo o numero de lineas
	 */
	function numeroLineas($archivo = null)
	{
		if ( ! $archivo || ! is_file($archivo) )
			return false;

		$filas	= 0;
		if ( ( $handle = fopen($archivo, 'r') ) !== FALSE )
		{
			while ( fgetcsv($handle, 0, ';') !== FALSE )
			{
				$filas++;
			}
			fclose($handle);
		}
		return $filas;
	}


	/***
	 * Procesa el archivo y devuelve los registros mapeados
	 * 
	 * @param	string		$archivo		Ruta del archivo
	 * @param	array		$mapeo			Mapeo de campos
	 * @return	mixed						False en error o array con todos los registros
	 */
	function procesarArchivo($archivo = null, $mapeo = array(), $separador = ';')
	{
		if ( ! $archivo || ! is_file($archivo) || empty($mapeo) )
			return false;

		$registros	= $stats = array();
		$fila		= 0;
		if ( ( $handle = fopen($archivo, 'r') ) !== FALSE )
		{
			while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
			{
				//if ( ++$fila == 1 )
				//	continue;

				foreach ( $datos as $index => $valor )
				{
					if ( ! isset($mapeo[$index]) )
						continue;

					$registro[$mapeo[$index]]	= $valor;
				}
				array_push($registros, $registro);
			}
			fclose($handle);
		}
		return $registros;
	}
}