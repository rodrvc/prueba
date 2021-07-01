<?php
class AndainController extends AppController
{
	var $name = 'Andain';

	//http://localhost/andain/skechers/catalogo/sitio2/productos/remover_grupo/memory_foam
	function index()
	{
		$options = array(
			'fields' => array(
				'Producto.id'
			),
			'limit' => 20
		);
		$productos = $this->Andain->Producto->find('all', $options);
		prx($productos);
	}

	/*  =========================================================================================================
	 *	============================= FUNCIONES LOCALES === NO COMITEAR NI SUBIR =============================
	 *	=========================================================================================================
	 */
	function cargar_productos_csv()
	{
		Configure::write('debug',1);
		set_time_limit(0);

		$archivo = 'C:\xampp\public_html\andain\skechers\catalogo\sitio2\webroot\img\cargas_andain\carga2016_2.csv';
		$separador=';';
		$mapeo = array(
			//0 => false,	// estado foto
			1 => false,	//codigo completo
			2 => 'codigo',
			3 => 'color',
			4 => 'nombre',
			//5 => false,		// coleccion
			6 => 'precio',
			7 => 'division',
			//8 => false,			// descripcion
			9 => 'showroom',	
			//10 => false,	// genero
			11 => 'genero',	// subgenero
			12 => 'tipo',	
			//13 => false,	// tipo calzado
			//14 => false,	// tipo cierre
			//15 => false,	// tipo season
			//16 => false,	// fecha
			17 => 'memory_foam',
			18 => 'relaxed_fit',
			19 => 'sandalias',
			20 => 'slip_on',
			21 => 'gowalk',
			22 => 'running',
			23 => 'puntillas',
			24 => 'alpargatas',
			25 => 'sport',
			26 => 'burst',
			27 => 'retro',
			28 => 'skech_air',
			//29 => false,
			//30 => false,
			31 => 'fecha_activo'
		);
		$registros = array();
		// iniciar lectura del archivo
		if (false &&( $handle = fopen($archivo, 'r') ) !== FALSE )
		{
			$linea = 0;
			while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
			{
				$linea++;
				if ($linea <= 3)
					continue;
				//if ($linea >= 5)
				//	break;
				//pr($datos);

				$registro = array();
				foreach ( $datos as $index => $valor )
				{
					if ( ! isset($mapeo[$index]) )
						continue;
					if ( $mapeo[$index] == false )
						continue;
					$registro[$mapeo[$index]] = trim($valor);
				}
				array_push($registros, $registro);
			}
		}

		$stats = array(
			'total' => count($registros),
			'procesados' => 0,
			'nuevos' => 0,
			'actualizados' => 0,
			'invalido' => array(
				'color' => array(
					'list' => array(),
					'count' => 0
				),
				'categoria' => array(
					'list' => array(),
					'count' => 0
				)
			),
			'error' => array(
				'producto' => array(
					'list' => array(),
					'count' => 0
				)
			)
		);

		if ($registros)
		{
			$generos = array(
				'women' => 1,
				'men' => 2,
				'boy' => 3,
				'boys' => 3,
				'infant-boys' => 3,
				'girl' => 4,
				'girls' => 4,
				'infant-girls' => 4
			);

			$colores = $this->Andain->Producto->Color->find('list', array(
				'fields' => array(
					'Color.codigo',
					'Color.id'
				)
			));
			$this->loadModel('Estilo');
			$estilos = $this->Estilo->find('list', array(
				'fields' => array(
					'Estilo.alias',
					'Estilo.alias'
				)
			));
			$productos = $this->Andain->Producto->find('all', array(
				'fields' => array(
					'Producto.id',
					'Producto.codigo'
				),
				'contain' => array(
					'Color.codigo'
				)
			));
			$codigos = array();
			if ($productos)
			{
				foreach ($productos as $producto)
				{
					$codigos[$producto['Producto']['codigo'].$producto['Color']['codigo']] = $producto['Producto']['id'];
				}
			}

			foreach ($registros as $registro)
			{
				if (! $registro['codigo'])
					continue;
				if (! $registro['color'])
					continue;
				if (! isset($colores[$registro['color']]))
				{
					if (! in_array($registro['color'], $stats['invalido']['color']['list']) )
					{
						array_push($stats['invalido']['color']['list'], $registro['color']);
					}
					$stats['invalido']['color']['count']++;
					continue;
				}
				if (! isset($generos[strtolower($registro['genero'])]))
				{
					if (! in_array($registro['genero'], $stats['invalido']['categoria']['list']) )
					{
						array_push($stats['invalido']['categoria']['list'], $registro['genero']);
					}
					$stats['invalido']['categoria']['count']++;
					continue;
				}
				$stats['procesados']++;
				// limpiar precio
				$precio = 0;
				if ($registro['precio'])
				{
					for ($x = 0; $x < strlen($registro['precio']); $x++)
					{
						if (is_numeric($registro['precio'][$x]))
						{
							if ($precio)
								$precio.= $registro['precio'][$x];
							else
								$precio = $registro['precio'][$x];
						}
					}
				}

				$save = array(
					'codigo_completo' => $registro['codigo'].$registro['color'],
					'codigo' => $registro['codigo'],
					'nombre' => $registro['nombre'],
					'precio' => $precio,
					'oferta' => 0,
					'precio_oferta' => 0,
					'coleccion_id' => 8,
					'outlet' => 0,
					'escolar' => 0,
					'categoria_id' => $generos[strtolower($registro['genero'])],
					'color_id' => $colores[$registro['color']],
					'excluir_descuento' => 0,
					'division' => $registro['division'],
					'showroom' => $registro['showroom'],
					'grupo' => '',
					'tipo' => $registro['tipo'],
					'activo' => ( $registro['fecha_activo'] ? 0 : 1 )
				);
				if (strtolower($save['showroom']) == 'performance')
				{
					$save['grupo'] .= '[performance]';
				}
				if ($estilos)
				{
					foreach ($estilos as $estilo)
					{
						if (! isset($registro[$estilo]))
							continue;
						if (! $registro[$estilo])
							continue;
						$save['grupo'] .= '['.$estilo.']';
					}
				}
				if (isset($codigos[$save['codigo_completo']]))
				{
					$save['id'] = $codigos[$save['codigo_completo']];
					if ( $this->Andain->Producto->save($save) )
					{
						$stats['actualizados']++;
					}
					else
					{
						$stats['error']['producto']['count']++;
						array_push($stats['error']['producto']['list'], $save['codigo_completo']);
					}
				}
				else
				{
					$this->Andain->Producto->create();
					if ( $this->Andain->Producto->save($save) )
					{
						$stats['nuevos']++;
					}
					else
					{
						$stats['error']['producto']['count']++;
						array_push($stats['error']['producto']['list'], $save['codigo_completo']);
					}
				}
			}
		}
	}

	function reporte_status()
	{
		// REPORTE DE PRODUCTOS + ESTADO DE FOTOS FOTOS
		$options = array(
			 'conditions' => array(
			 	'Producto.coleccion_id' => 8
			 ),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.codigo',
				'Producto.codigo_completo',
				'Producto.precio',
				'Producto.oferta',
				'Producto.precio_oferta',
				'Producto.categoria_id',
				// 'Producto.ficha',
				// 'Producto.foto',
				'Producto.outlet',
				'Producto.escolar',
				'Producto.activo',
				'if (length(Producto.foto) > 5,1,0) AS n_foto',
				'COUNT(DISTINCT Galeria.id) galerias',
				'if (length(Producto.ficha) > 10,1,0) AS n_ficha',
				'if (length(Producto.descripcion) > 10,1,0) AS n_descripcion',
				'COUNT(DISTINCT Stock.id) stock',
			),
			'joins' => array(
				array(
					'table' => 'sitio_stocks',
					'alias' => 'Stock',
					'type' => 'LEFT',
					'conditions' => array(
						'Stock.producto_id = Producto.id'
					)
				),
				array(
					'table' => 'sitio_galerias',
					'alias' => 'Galeria',
					'type' => 'LEFT',
					'conditions' => array(
						'Galeria.producto_id = Producto.id'
					)
				)
			),
			'contain' => array(
				'Categoria.alias',
				'Coleccion.nombre',
				'Color.codigo',
			),
			'group' => array(
				'Producto.id'
			),
			// 'limit' => 10
		);
		$productos = $this->Andain->Producto->find('all',$options);
		$categorias = $this->Andain->Producto->Categoria->find('list', array(
			'conditions' => array(
				'Categoria.publico'
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.id'
			)
		));

		$excel = '<table>
		<tr>
			<th>ID</th>
			<th>Nombre</th>
			<th>Codigo</th>
			<th>Style</th>
			<th>Color</th>
			<th>Precio</th>
			<th>Categoria</th>
			<th>Coleccion</th>
			<th>Ropa</th>
			<th>Outlet</th>
			<th>Escolar</th>
			<th>Foto</th>
			<th>Galeria</th>
			<th>Descripcion</th>
			<th>Ficha</th>
			<th>Stock</th>
			<th>Activo</th>
		</tr>';
			foreach ($productos as $producto)
			{
				$precio = 0;
				if ($producto['Producto']['precio'])
				{
					$precio = $producto['Producto']['precio'];
					if ($producto['Producto']['oferta'] && ($producto['Producto']['precio'] > $producto['Producto']['precio_oferta']))
					{
						$precio = $producto['Producto']['precio_oferta'];
					}
				}
				$activo = 'si';
				if (! $producto['Producto']['activo'])
					$activo = 'no';
				elseif (! in_array($producto['Producto']['categoria_id'], $categorias))
					$activo = 'no';
				elseif (! $producto[0]['n_foto'])
					$activo = 'no';
				$excel.='<tr>
					<td>'.$producto['Producto']['id'].'</td>
					<td>'.$producto['Producto']['nombre'].'</td>
					<td>'.$producto['Producto']['codigo_completo'].'</td>
					<td>'.$producto['Producto']['codigo'].'</td>
					<td>'.$producto['Color']['codigo'].'</td>
					<td>'.$precio.'</td>
					<td>'.strtoupper($producto['Categoria']['alias']).'</td>
					<td>'.$producto['Coleccion']['nombre'].'</td>
					<td>'.( (in_array($producto['Producto']['categoria_id'], array(58,59,60))) ? 'si' : 'no' ).'</td>
					<td>'.( ($producto['Producto']['outlet']) ? 'si' : 'no' ).'</td>
					<td>'.( ($producto['Producto']['escolar']) ? 'si' : 'no' ).'</td>
					<td>'.( ($producto[0]['n_foto']) ? 'si' : 'no' ).'</td>
					<td>'.$producto[0]['galerias'].'</td>
					<td>'.( ($producto[0]['n_descripcion']) ? 'si' : 'no' ).'</td>
					<td>'.( ($producto[0]['n_ficha']) ? 'si' : 'no' ).'</td>
					<td>'.( ($producto[0]['stock']) ? 'si' : 'no' ).'</td>
					<td>'.$activo.'</td>
				</tr>';
			}
			$excel.= '</table>';
			$fileName = 'status_productos_'.date('d-m-Y').'_'.date('H.i.s').'.xls';
			header('Content-Type: application/force-download');
			header('Content-Disposition: attachment; filename="'.$fileName.'"');
			header('Content-Transfer-Encoding: binary');
			$excel.='</table>';
			die(utf8_decode($excel));
		//prx($productos);
	}

	function archivo_oferta()
	{
		Configure::write('debug',0);
		$options = array(
			'conditions' => array(
				'Producto.activo' => 1,
				'Producto.outlet' => 0,
				'Producto.escolar' => 0,
				'Producto.categoria_id' => array(3,4,16)
			),
			'fields' => array(
				'Producto.id',
				'Producto.precio',
				'Producto.codigo_completo'
			),
		);
		$productos = $this->Andain->Producto->find('all', $options);
		$documento = '';

		$delimitador = ',';
		$documento.="CODIGO{$delimitador}PRECIO{$delimitador}OFERTA_PORCENTAJE{$delimitador}OFERTA_VALOR";
		$documento.=PHP_EOL;

		foreach ($productos as $producto)
		{
			if (! $producto['Producto']['codigo_completo'])
				continue;
			$documento.=$producto['Producto']['codigo_completo'];
			$documento.=$delimitador;

			if ($producto['Producto']['precio'])
				$documento.=$producto['Producto']['precio'];
			$documento.=$delimitador;

			$porcentaje = 20;
			if ($porcentaje)
				$documento.=$porcentaje;
			$documento.=$delimitador;

			$precio_oferta = '';
			if ($precio_oferta)
				$documento.=$precio_oferta;
			$documento.=PHP_EOL;
		}

		header('Content-Type: application/force-download');
		header('Content-Disposition: attachment; filename="ofertas.csv"');
		header('Content-Transfer-Encoding: binary');
		die($documento);
	}

	function update()
	{
		$precios = array(
			"14010HPK" => 44990
		);
		$cont = 0;
		foreach ($precios as $codigo => $precio)
		{
			$options = array(
				'conditions' => array(
					'Producto.codigo_completo'
				),
				'fields' => array(
					'Producto.id'
				)
			);
			if ($producto = $this->Andain->Producto->find('first', $options))
			{
				$update = array(
					'id' => $producto['Producto']['id'],
					'precio' => $precio
				);
				if (! $precio)
					continue;
				if ($precio <= 2000)
					continue;
				//if ($this->Producto->save($update))
				//{
				//	$cont++;
				//}
			}
		}
		pr(count($precios));
		prx($cont);
	}

	function verificar_precios()
	{
		$precios = array(
			"12004NVPK" => 37990,
			"11906CCPR" => 34990,
			"12004CCAQ" => 37990,
			"14010HPK" => 44990
		);
		$cont = 0;
		foreach ($precios as $codigo => $precio)
		{
			$options = array(
				'conditions' => array(
					'codigo_completo' => $codigo
				),
				'fields' => array(
					'id',
					'precio'
				)
			);
			if ($producto = $this->Andain->Producto->find('first', $options))
			{
				if ($producto['Producto']['precio'] == $precio)
				{
					//pr('++ '.$codigo.' - precio '.$producto['Producto']['precio']);
				}
				else
				{
					pr('-- '.$codigo.' - precio '.$producto['Producto']['precio'].' '.$precio);
					$update = array(
						'id' => $producto['Producto']['id'],
						'precio' => $precio
					);
					if ($this->Andain->Producto->save($update))
						$cont++;
				}
			}
			else
			{
				pr('Producto no encontrado '.$codigo);
			}
		}
		prx(count($precios).' / '.$cont);
	}

	function estilos($tipo = 1)
	{
		if ($tipo == 1)
		{
			
		}
		else
		{
			$options = array(
				'conditions' => array(
					//'Producto.nombre LIKE' => '%go flex%',
					'NOT' => array(
						array('Producto.grupo LIKE' => '%[go_fit]%')
					),
					'Producto.codigo_completo' => array(
						'14086GYHP', '14086PUR', '14088BKW', '14091BKMT', '14087BKW', '14091BLMT'
					)
				),
				'fields' => array(
					'id',
					'nombre',
					'codigo_completo',
					'coleccion_id',
					'grupo',
					'activo'
				)
			);
			$productos = $this->Andain->Producto->find('all', $options);
			$list = '';
			foreach ($productos as $producto)
			{
				$list.=($list?', ':'').$producto['Producto']['id'];
			}
			pr($list);
			prx($productos);
		}
	}

	function fotos($coleccion_id = 8)
	{
		$cortes = array(
			'mini',
			'ith',
			'full',
			//'path'
		);
		$options = array(
			'conditions' => array(
				'Producto.coleccion_id' => $coleccion_id
			),
			'fields' => array(
				'id',
				'codigo_completo',
				'foto'
			),
			'limit' => 20
		);
		$productos = $this->Andain->Producto->find('all', $options);
		$this->layout = false;
		$this->set(compact('productos', 'cortes'));
	}

	function activar_productos()
	{
		$options = array(
			'conditions' => array(
				'Producto.codigo_completo' => array("12004NVPK", "11906CCPR", "12004CCAQ", "11906BKHP", "48930BRN", "48930BLK", "12448BKMT", "51234CCBK", "12004BBK", "49041BLK", "50661SAGE", "22724BKW", "12055NVPK", "51508BKW", "49041LTBR", "12055GYNP", "51234BLK", "11906LGGR", "22724BKMT", "51361BKW", "51361NVY", "49023CHOC", "51508GYOR", "22740NVAQ", "51521BKW", "81878LBKMT", "12055CCAQ", "22497GRY", "12131CCPK", "12732BBLP", "22532BLK", "12058BKW", "12437CCPK", "81878LAQMT", "14046BKW", "14086GYHP", "22469CCL", "12406BKW", "12131BKW", "81799LNVHP", "48962CHOC", "81799LNPMT", "50140TPBK", "51521CCRD", "81855NGYMT", "22672BBK", "22724AQMT", "81905LBKHP", "12437BLHP", "22497NVY", "12182NVY", "50140CCBK", "64538CHOC", "81499LPRNP", "48962BLK", "22532TPE", "22722CCCL", "49023BLK", "22713HPBK", "14060NVPK", "51508NVLM", "51529CCBK", "12184BBK", "81844LGYAQ", "49170CHOC", "22740GYNP", "12004BMLT", "95519LBKBL", "22532NVY", "51529NVOR", "64222DSCH", "22468GYPK", "12182BKW", "64440DKBR", "11870NVTQ", "22532GRY", "12058GYTQ", "81072LPRNP", "64538TAN", "22551CCLB", "22913CCL", "49186DKTP", "49170DKTP", "12700NVMT", "64716LTBR", "14055CCPK", "22913BLK", "14061HPLM", "14061HPBK", "81905LTQHP", "51361GRY", "12184CCPK", "11965CCAQ", "22713BKAQ", "81650LGYMT", "64226DSCH", "996258LBKPK", "22583NVY", "12404NVHP", "81075LBKMT", "51530CCBK", "14010BKHP", "81908LBBLP", "14068BKHP", "11965BLK", "14046NVW", "81069LGYHP", "64716CHAR", "51530NVLM", "81650LBKMT", "14061NVW", "22827BKW", "12433NVAQ", "49061BLK", "14056NVW", "12730GRY", "49229BLK", "22827TPE", "49229NVY", "14058BLU", "14047PUR", "14086PUR", "62895TPE", "10504NPKMT", "97300LNVOR", "14058NVMT", "12448HPMT", "12452BKPR", "64278DSCH", "14060BKW", "14010NVCL", "12448AQMT", "64109KHK", "12745PRMT", "12453TQMT", "49109DKBR", "996258LPRMT", "51521NVY", "121PRPK", "49023LTBR", "14060CCBL", "49109BLK", "81861LNPMT", "49186CHOC", "95532LGYOR", "48979BBK", "14202NVMT", "81852NTQMT", "48761BLK", "14055NVBL", "14010BKLM", "48930SAGE", "14055CCBL", "11863NVY", "14068MTMT", "14066GYHP", "49229DSCH", "81853NPKAQ", "81852LMLT", "11863GRY", "12406NVBL", "48661BLK", "14088BKW", "51531BKGY", "22740BKCC", "22512BLK", "80344LBKMT", "12734MULT", "81852LTQMT", "81909LBBLP", "14047TURQ", "95527LCCYL", "51531LGBK", "53980NVGY", "54047GRY", "52107BMLT", "22484BLK", "12447HPMT", "22512GRY", "81702LBKNP", "22517TPE", "95390LBNVL", "12447BKMT", "12732PRPK", "22517BLK", "12058LGCL", "95513LBKLM", "64529DSCH", "14058PNK", "14047NVW", "49170BLK", "22722PRPK", "14068MULT", "11967BBK", "12433BKW", "22752BKWP", "95170NBBLM", "64506CHAR", "12753BKW", "12404GYMT", "95242LCCRD", "14058BKMT", "48962GRY", "22482BBK", "64222CCGY", "95170NCCRD", "81074LBLPK", "81905LHPCR", "64441CHAR", "80344LPPAQ", "22724HPMT", "81074LCCNV", "95095NRYYL", "998091NRYYL", "64109BLK", "54047NVGY", "11965NVLB", "121BKPR", "81555LBLPK", "51560TPBR", "12753GYCL", "14068HPOR", "22583BLK", "95092NBLYL", "22752NVLB", "64630LTBR", "12735GYCL", "81908LNVHP", "64680CDB", "54047BKGY", "95242LNVYL", "81852NMLT", "81075LPRMT", "12437HPMT", "22482NAT", "22722NPYL", "12433PKPR", "14011GYCL", "51530GYBL", "14011GYHP", "80344LBLAQ", "64639GRY", "64663CHAR", "12406GYHP", "53755CHAR", "54350BKW", "14047HPK", "81555LNPCL", "12184LBNV", "10621NPKMT", "49229TPE", "64666DKBR", "81908LNPTQ", "22497NAT", "80594NLPMT", "81857LBKPK", "22722NVGR", "51531NVOR", "49145BLK", "14202WMLT", "12447AQMT", "14221BKW", "22672CRL", "12133BKMT", "22532BLU", "81068NGYMT", "52107BKW", "81650LNVMT", "53755WTN", "51531BLLM", "95533LNVOR", "97301LRYYL", "64506TAN", "49061TPE", "12741BLK", "12754BKMT", "51504CCBK", "51509BKW", "51509CCOR", "49229GRY", "80594NBLPK", "52107CCOR", "22583DKNT", "64666TPE", "91476LCCOR", "49145CCL", "22752TPCL", "81498LCCNP", "64441BLK", "64630CHAR", "54047BLU", "12182LTBL", "64650CDB", "48979GRY", "22511NVY", "95390LBKYO", "52107BLLM", "64582BRN", "22497BLK", "22826BBK", "48979NVY", "14011BKGY", "64630NVY", "14221NVW", "12454BKLB", "64667GRY", "81072LTQOR", "51561BROR", "81851NMLT", "14221TPE", "14200BKW", "95092NNVRD", "14091BKMT", "90481LBKRD", "22583CCL", "48662CHOC", "49038CCL", "95533LCCRD", "996257NNPOR", "95092NCCOR", "94161LCCBL", "84201LPRMT", "64111CDB", "81498NCCNP", "51423NTGY", "14221CHAR", "22497WHT", "81075LBLMT", "53755DSCH", "14800WHT", "22826NAT", "10621NDEN", "80035LCCMT", "82293LBBK", "81068NNPPK", "22532WHT", "53988BBK", "81701LBLHP", "64582TAN", "81857LPRNP", "81853LPKAQ", "22583LTGY", "12740GYCL", "95532LNVLM", "97022LSLBL", "93412LSAGE", "12032NVY", "85609LMLT", "52108BKBL", "95096NBKLM", "23400BLK", "53738STN", "81217LHPK", "22511TPE", "22484TPE", "81842LNPMT", "22849BLK", "22849CCL", "23400TPE", "996259LPKPR", "81499LCCNP", "94161LGYRD", "97303LBKMT", "51560GYBK", "95316LCCOR", "64833CDB", "81217LBKMT", "97301LLMBK", "49038BLK", "80033LGYPK", "64667BLK", "90520LSLBK", "22826GRY", "81498LCCMT", "54040BKGY", "10562LBLMT", "14205GRY", "22511BLK", "53738CHAR", "96350LBRN", "93871LCHAR", "51483CCLM", "81908LCCPK", "54050NVW", "141BKHP", "11863BLK", "14205BLPK", "52108CCBK", "12182PNK", "10621NBKPK", "90461LCCBK", "84201LBLMT", "64663CDB", "81853NSMLT", "91476LBKLB", "998091NCCOR", "95316LRYOR", "10612LMLT", "96350LBLU", "97300LCCBK", "57003GYMT", "998091NBKRD", "22832GRY", "22469TPE", "85609LBKWP", "48662BLK", "52180BKW", "57003BKSL", "51138TPE", "51423BKGY", "57003NVLB", "12433AQUA", "85610LDMLT", "12032PNK", "12032PERI", "81853LSMLT", "95482LNVOR", "13781NVW", "13836NVW", "13841NVW", "54050BKW", "51483NVRD", "97300LBKRD", "51561CCLM", "12032CHAR", "12113NVHP", "90520LRYYL", "141NVAQ", "93871LBRN", "12732HPMT", "22602BKW", "22844GYMN", "97301LBKW", "64652CHAR", "64732BRN", "95519LCCOR", "81498LBKNP", "48760BLK", "85608LPRBL", "48761DKBR", "64582BLK", "95096NNVRD", "51423NVGY", "90461NCCBK", "64652DSCH", "52108NVGY", "53738BKW", "22832TPE", "10620LWMLT", "176CCL", "84201LHPMT", "57000BKW", "68167DSRT", "48944BLK", "12753NVPK", "12404TURQ", "12740BKLB", "95140LCCOR", "97370LCCOR", "95525LBLLM", "22752GYMN", "95488LBLK", "81702NNPPR", "64529BLK", "97020LBKRD", "81861LNVHP", "22517LTGY", "12750BKMT", "13836TEAL", "13841TURQ", "12404BBK", "12735SLT", "14346NVTL", "22709NVMT", "53737KHK", "51138CCBK", "14080CCHP", "95314LCCBK", "142BURG", "90293LRDBK", "81217LBLU", "12113BLHP", "48760DKBR", "95525LBKCC", "97006LBKRD", "95170LCCRD", "10504NBLMT", "64740BLK", "51138BKGY", "90481LSLBL", "998090LBKRD", "54080BKLM", "90293LBKLM", "95527LBKBL", "10623LMLT", "95170LBKSL", "49221TAN", "80593LNPMT", "52102BKW", "52102CCLM", "52108BKW", "97043LBKRD", "97038NBKMT", "52107NVOR", "81245LBKHP", "81245LGYMT", "95488LCCBK", "90550NBBLM", "51551CCBL", "97006LWBK", "80591NMLT", "14080CCBL", "141GYMN", "81068NBKHP", "22512TPE", "80591LMLT", "52300BURG", "54350CCOR", "54080NVOR", "49064BLK", "49139LTGY", "52310BKW", "14087BKW", "10629LPRMT", "54350NVGY", "54351BBK", "54351CCNV", "81494NPKLV", "90550NBCOR", "11870PRCL", "95341NBKYB", "996258LBLMT", "68160SAND", "53737NVGY", "81498NCCMT", "998090LBLLM", "95482LCCLB", "22551BKCC", "95314LRYBK", "52300BKW", "14050BKW", "22525BLK", "48935BLK", "14056KHK", "34096NVY", "95170LBBLM", "34096BLK", "52310NVGY", "64740LTBR", "64815DKTP", "64822GRY", "64810BLK", "64810NVY", "12113AQLM", "90491LBSRY", "123GYLV", "97022NSLBL", "123TPCL", "34103BLK", "34103NVY", "34268BBK", "34268NAT", "64677DKGY", "22551TPCL", "12452GYMN", "13996BKHP", "22468BLK", "22534TPE", "53997CCLM", "22517CCL", "12454HPGR", "53996GRNV", "14230BKHP", "97014NBLU", "13997CCBL", "123BKLV", "14200BLPK", "14230CCPR", "64224CDB", "13997BKPR", "53997ORBK", "996257NBLPR", "52107LGLM", "49110BBK", "14005BKHP", "998090LNVBL", "12454TQHP", "33756NTMT", "52310OLBR", "12759PKMT", "49085BRN", "49226BLK", "176NVY", "97002LBKW", "14050NVBL", "54230BKW", "97054LBKRD", "10659NGYPK", "52300NVNT", "10651NMLT", "84201LGYMT", "13997PROR", "50856SAGE", "52310NTBL", "14011CCTQ", "49085CHOC", "14091BLMT", "145NVY", "14005PROR", "81854NSPPR", "91536LGUMT", "10901LBKMT", "49069BLK", "49272BLK", "125NVPK", "143BKGD", "13996PRTL", "80032LBPPR", "22534BLU", "95527LNVRD", "93412LCHAR", "13997BLLM", "81493LBLAQ", "95955LRYBK", "14115PKPR", "49324BLK", "49324MUSH", "84200LBLMT", "51480NVYL", "54005LMBK", "64533LTBR", "10539LMLT", "97057LWBK", "84200LHPMT", "84200LPRMT", "10539LLBPK", "10573NSIL", "54105BKLM", "54105CCRD", "54115CCBL", "54115BKW", "14115NVCL", "14105PRAQ", "95513LCCRD", "54110CCOR", "95956LBKYL", "54110BLGR", "14105NVHP", "97003LBKMT", "49085GRY", "97000NBKRD", "68160CHAR", "12449HPMT", "53530CCNV", "53980CCOR", "97000NWBRY", "93871LBLK", "53530WNVR", "81498NSMLT", "97000LBKRD", "53546NVW", "53530BKW", "53529BBK", "53546CCBL", "53529WGBL", "53529CCBL", "53530BRN", "57002LGPK", "49085RAS", "14100BKPK", "14100PRCC", "97020LRDBK", "54100ORG", "54100BURG", "54100GROR", "93890LBLK", "93890LBRN", "14010HPK"),
				'Producto.activo' => 0
			),
			'fields' => array(
				'Producto.id',
				'Producto.activo'
			)
		);
		$productos = $this->Andain->Producto->find('all', $options);
		foreach ($productos as $producto)
		{
			if (reset($productos) != $producto)
				echo ', ';
			echo $producto['Producto']['id'];
		}
		prx('fin');
	}

	function remover_grupo()
	{
		Configure::write('debug',1);
		$limpiar = array(
			//'PMMFT',
			//'WMMFR'
		);
		$tags = array(
			'memory_foam' => array("12004NVPK", "12004CCAQ", "48930BRN", "48930BLK", "12448BKMT", "51234CCBK", "12004BBK", "49041BLK", "22724BKW", "12055NVPK", "51508BKW", "49041LTBR", "12055GYNP", "51234BLK", "22724BKMT", "51361BKW", "51361NVY", "49023CHOC", "51508GYOR", "22740NVAQ", "51521BKW", "81878LBKMT", "12055CCAQ", "38465BBK", "22497GRY", "12131CCPK", "12732BBLP", "22532BLK", "12058BKW", "12437CCPK", "81878LAQMT", "22469CCL", "12406BKW", "12131BKW", "81799LNVHP", "48950DSCH", "48962CHOC", "81799LNPMT", "50140TPBK", "51521CCRD", "22672BBK", "22724AQMT", "81905LBKHP", "12437BLHP", "22497NVY", "12182NVY", "50140CCBK", "64538CHOC", "38472BBK", "48962BLK", "38478BLK", "22532TPE", "22722CCCL", "49023BLK", "22713HPBK", "51508NVLM", "51529CCBK", "12184BBK", "81844LGYAQ", "49170CHOC", "64641BRN", "22740GYNP", "12004BMLT", "95519LBKBL", "22532NVY", "51529NVOR", "64222DSCH", "22468GYPK", "12182BKW", "64440DKBR", "22532GRY", "12058GYTQ", "81072LPRNP", "64538TAN", "22551CCLB", "49186DKTP", "49170DKTP", "12700NVMT", "64716LTBR", "81905LTQHP", "51361GRY", "12184CCPK", "11965CCAQ", "22713BKAQ", "81650LGYMT", "64226DSCH", "22583NVY", "12404NVHP", "51530CCBK", "81908LBBLP", "11965BLK", "64716CHAR", "51530NVLM", "81650LBKMT", "22827BKW", "12433NVAQ", "49061BLK", "12730GRY", "38582CCL", "49229BLK", "22827TPE", "49229NVY", "62895TPE", "97300LNVOR", "12448HPMT", "12452BKPR", "64278DSCH", "12448AQMT", "64109KHK", "12745PRMT", "12453TQMT", "49109DKBR", "48776DSCH", "51521NVY", "121PRPK", "49023LTBR", "49109BLK", "81861LNPMT", "49186CHOC", "95532LGYOR", "48979BBK", "81852NTQMT", "48761BLK", "48930SAGE", "11863NVY", "49229DSCH", "81853NPKAQ", "81852LMLT", "11863GRY", "12406NVBL", "51531BKGY", "22740BKCC", "22512BLK", "80344LBKMT", "12734MULT", "81852LTQMT", "81909LBBLP", "95527LCCYL", "51531LGBK", "52107BMLT", "22484BLK", "12447HPMT", "38478TPE", "22512GRY", "22517TPE", "12447BKMT", "12732PRPK", "22517BLK", "12058LGCL", "95513LBKLM", "64529DSCH", "38409BBK", "49170BLK", "22722PRPK", "64641OLV", "11967BBK", "12433BKW", "22752BKWP", "64506CHAR", "12753BKW", "12404GYMT", "48962GRY", "22482BBK", "64222CCGY", "81905LHPCR", "64441CHAR", "80344LPPAQ", "22724HPMT", "95095NRYYL", "64109BLK", "11965NVLB", "121BKPR", "81555LBLPK", "51560TPBR", "12753GYCL", "22583BLK", "95092NBLYL", "22752NVLB", "64630LTBR", "12735GYCL", "81908LNVHP", "64680CDB", "38409TPE", "81852NMLT", "12437HPMT", "22482NAT", "22722NPYL", "12433PKPR", "51530GYBL", "48776TPE", "80344LBLAQ", "64639GRY", "64663CHAR", "12406GYHP", "81555LNPCL", "12184LBNV", "49229TPE", "64666DKBR", "81908LNPTQ", "22497NAT", "81857LBKPK", "22722NVGR", "51531NVOR", "38461CCL", "49145BLK", "12447AQMT", "22672CRL", "12133BKMT", "22532BLU", "52107BKW", "38992BKMT", "81650LNVMT", "51531BLLM", "95533LNVOR", "64506TAN", "49061TPE", "12754BKMT", "51509BKW", "51509CCOR", "49229GRY", "38992BRMT", "52107CCOR", "22583DKNT", "64666TPE", "38478WHT", "49145CCL", "22752TPCL", "64441BLK", "64630CHAR", "12182LTBL", "64650CDB", "48979GRY", "22511NVY", "52107BLLM", "64582BRN", "38992TAN", "22497BLK", "22826BBK", "48979NVY", "64630NVY", "12454BKLB", "64667GRY", "81072LTQOR", "51561BROR", "81851NMLT", "38979BLK", "38582TAN", "95092NNVRD", "22583CCL", "48662CHOC", "49038CCL", "38458BBK", "39083BRZ", "95533LCCRD", "95092NCCOR", "84201LPRMT", "64111CDB", "51423NTGY", "22497WHT", "22826NAT", "80035LCCMT", "22532WHT", "64582TAN", "81857LPRNP", "81853LPKAQ", "22583LTGY", "12740GYCL", "95532LNVLM", "12032NVY", "85609LMLT", "39082BLK", "52108BKBL", "95096NBKLM", "23400BLK", "81217LHPK", "22511TPE", "22484TPE", "23400TPE", "97303LBKMT", "51560GYBK", "95316LCCOR", "64833CDB", "81217LBKMT", "49038BLK", "80033LGYPK", "64667BLK", "22826GRY", "10562LBLMT", "64712BRN", "22511BLK", "96350LBRN", "93871LCHAR", "38979BRN", "51483CCLM", "81908LCCPK", "141BKHP", "11863BLK", "52108CCBK", "12182PNK", "64712BLK", "38454BKMT", "38472WHT", "84201LBLMT", "64663CDB", "81853NSMLT", "95316LRYOR", "96350LBLU", "97300LCCBK", "57003GYMT", "39083SIL", "22832GRY", "22469TPE", "85609LBKWP", "48662BLK", "57003BKSL", "51138TPE", "51423BKGY", "57003NVLB", "12433AQUA", "85610LDMLT", "12032PNK", "12032PERI", "81853LSMLT", "95482LNVOR", "51483NVRD", "97300LBKRD", "51561CCLM", "12032CHAR", "12113NVHP", "141NVAQ", "93871LBRN", "12732HPMT", "22602BKW", "22844GYMN", "64652CHAR", "95519LCCOR", "48760BLK", "85608LPRBL", "48761DKBR", "64582BLK", "95096NNVRD", "51423NVGY", "64652DSCH", "52108NVGY", "22832TPE", "10620LWMLT", "176CCL", "84201LHPMT", "57000BKW", "68167DSRT", "12753NVPK", "12404TURQ", "12740BKLB", "95140LCCOR", "95525LBLLM", "22752GYMN", "39082TPE", "64529BLK", "97020LBKRD", "81861LNVHP", "22517LTGY", "12404BBK", "12735SLT", "22709NVMT", "51138CCBK", "38979NAT", "142BURG", "81217LBLU", "12113BLHP", "64712GRY", "48760DKBR", "95525LBKCC", "51138BKGY", "95527LBKBL", "49221TAN", "52108BKW", "97043LBKRD", "52107NVOR", "51551CCBL", "141GYMN", "22512TPE", "52300BURG", "49064BLK", "49139LTGY", "52310BKW", "95341NBKYB", "68160SAND", "95482LCCLB", "22551BKCC", "52300BKW", "22525BLK", "48935BLK", "34096NVY", "34096BLK", "52310NVGY", "12113AQLM", "38577GRY", "38577RAS", "123GYLV", "123TPCL", "34103BLK", "34103NVY", "34268BBK", "34268NAT", "64677DKGY", "22551TPCL", "12452GYMN", "22468BLK", "22534TPE", "22517CCL", "12454HPGR", "97014NBLU", "38946BLK", "123BKLV", "64224CDB", "52107LGLM", "49110BBK", "12454TQHP", "33756NTMT", "52310OLBR", "12759PKMT", "49085BRN", "176NVY", "97002LBKW", "52300NVNT", "10651NMLT", "84201LGYMT", "50856SAGE", "52310NTBL", "49085CHOC", "145NVY", "81854NSPPR", "49069BLK", "49272BLK", "125NVPK", "143BKGD", "22534BLU", "95527LNVRD", "95955LRYBK", "49324BLK", "49324MUSH", "84200LBLMT", "51480NVYL", "64533LTBR", "84200LHPMT", "84200LPRMT", "95513LCCRD", "95956LBKYL", "97003LBKMT", "49085GRY", "68160CHAR", "12449HPMT", "93871LBLK", "57002LGPK", "49085RAS", "97020LRDBK"),
			'relaxed_fit' => array("48930BRN", "48930BLK", "49041BLK", "49041LTBR", "49023CHOC", "51521BKW", "22497GRY", "12131CCPK", "22532BLK", "22469CCL", "12406BKW", "12131BKW", "48950DSCH", "48962CHOC", "50140TPBK", "51521CCRD", "22497NVY", "50140CCBK", "64538CHOC", "81499LPRNP", "48962BLK", "22532TPE", "49023BLK", "51529CCBK", "49170CHOC", "64641BRN", "22532NVY", "51529NVOR", "64222DSCH", "22468GYPK", "64440DKBR", "22532GRY", "64538TAN", "22551CCLB", "49186DKTP", "49170DKTP", "64716LTBR", "81650LGYMT", "64226DSCH", "22583NVY", "12404NVHP", "51530CCBK", "64716CHAR", "51530NVLM", "81650LBKMT", "49061BLK", "49229BLK", "49229NVY", "64278DSCH", "64109KHK", "49109DKBR", "48776DSCH", "51521NVY", "49023LTBR", "49109BLK", "49186CHOC", "48979BBK", "48761BLK", "48930SAGE", "38688BLK", "49229DSCH", "12406NVBL", "51531BKGY", "22512BLK", "51531LGBK", "22484BLK", "22512GRY", "22517TPE", "22517BLK", "64529DSCH", "49170BLK", "64641OLV", "64506CHAR", "12404GYMT", "48962GRY", "22482BBK", "64222CCGY", "64441CHAR", "64109BLK", "81555LBLPK", "22583BLK", "64630LTBR", "22482NAT", "51530GYBL", "48776TPE", "64639GRY", "64663CHAR", "12406GYHP", "81555LNPCL", "49229TPE", "64666DKBR", "22497NAT", "51531NVOR", "49145BLK", "12133BKMT", "22532BLU", "81650LNVMT", "51531BLLM", "64506TAN", "49061TPE", "49229GRY", "22583DKNT", "64666TPE", "49145CCL", "64441BLK", "64630CHAR", "64650CDB", "48979GRY", "22511NVY", "64582BRN", "22497BLK", "48979NVY", "64630NVY", "64667GRY", "22583CCL", "49038CCL", "64111CDB", "51423NTGY", "22497WHT", "22532WHT", "64582TAN", "22583LTGY", "22511TPE", "22484TPE", "81499LCCNP", "49038BLK", "64667BLK", "64712BRN", "22511BLK", "96350LBRN", "93871LCHAR", "64712BLK", "64663CDB", "96350LBLU", "22469TPE", "51138TPE", "51423BKGY", "12113NVHP", "93871LBRN", "64652CHAR", "48760BLK", "48761DKBR", "64582BLK", "51423NVGY", "64652DSCH", "48944BLK", "12404TURQ", "97370LCCOR", "64529BLK", "22517LTGY", "12404BBK", "51138CCBK", "12113BLHP", "64712GRY", "48760DKBR", "51138BKGY", "49221TAN", "51551CCBL", "22512TPE", "49064BLK", "49139LTGY", "22551BKCC", "22525BLK", "12113AQLM", "64677DKGY", "22551TPCL", "22468BLK", "22534TPE", "22517CCL", "64224CDB", "49110BBK", "49085BRN", "49085CHOC", "49069BLK", "49272BLK", "22534BLU", "49324BLK", "49324MUSH", "64533LTBR", "49085GRY", "93871LBLK", "49085RAS"),
			'sandalias' => array("38465BBK", "38500BLK", "48950DSCH", "63647SAND", "38357BLK", "63647BRN", "38472BBK", "38478BLK", "38640BBK", "64641BRN", "48228NAT", "86623LBKHP", "38642BLK", "86623LDNPK", "38582CCL", "86532LNPNK", "48776DSCH", "48228BLK", "38688BLK", "38500WHT", "86614LLVMT", "38640BKMT", "38664BLK", "38345ZBA", "38345LPD", "38478TPE", "38498BLK", "38409BBK", "86218LBLK", "64641OLV", "86705LBKHP", "38537BLK", "38409TPE", "48776TPE", "92187LCHTP", "40790BLK", "38642TPE", "39232BLK", "86631LBKHP", "38537TPE", "38461CCL", "38992BKMT", "40838DSCH", "38992BRMT", "38478WHT", "92187LCCBL", "38992TAN", "39232NAT", "38979BLK", "38582TAN", "86218LHPK", "86705LSLPR", "38458BBK", "39083BRZ", "92187LBKLM", "39082BLK", "54250CHOC", "40790CRL", "64712BRN", "38979BRN", "64712BLK", "38454BKMT", "38472WHT", "40790TPE", "39083SIL", "86631LMLT", "38528BLK", "54250BKW", "86656NBKMT", "92187NCCBL", "10592LPKTQ", "86656LHPTQ", "86656LBKMT", "86656NHPTQ", "92187NBKLM", "39082TPE", "10592LBKHP", "38979NAT", "38528BRZ", "64712GRY", "10592NPKTQ", "14250TURQ", "38577GRY", "38577RAS", "54250CHAR", "40838BLK", "14250BKW", "14250TPE", "14250HPK", "40798CHOC", "38946BLK", "81063LGYHP", "38615GRY"),
			'slip_on' => array("49041BLK", "22724BKW", "49041LTBR", "22724BKMT", "51361BKW", "51361NVY", "49023CHOC", "22740NVAQ", "51521BKW", "22497GRY", "14046BKW", "51521CCRD", "22672BBK", "22724AQMT", "22497NVY", "12182NVY", "22722CCCL", "49023BLK", "22713HPBK", "14060NVPK", "49170CHOC", "22740GYNP", "12182BKW", "64440DKBR", "22913CCL", "49170DKTP", "64716LTBR", "14055CCPK", "22913BLK", "14061HPLM", "14061HPBK", "51361GRY", "22713BKAQ", "14010BKHP", "14046NVW", "81069LGYHP", "64716CHAR", "14061NVW", "22827BKW", "49229BLK", "22827TPE", "49229NVY", "14047PUR", "62895TPE", "14179CLGYPK", "14060BKW", "14010NVCL", "51521NVY", "49023LTBR", "14060CCBL", "48979BBK", "48761BLK", "14055NVBL", "14010BKLM", "14055CCBL", "49229DSCH", "22740BKCC", "14047TURQ", "54047GRY", "22517TPE", "22517BLK", "14047NVW", "86218LBLK", "49170BLK", "22722PRPK", "64441CHAR", "22724HPMT", "54047NVGY", "64680CDB", "54047BKGY", "22722NPYL", "14047HPK", "49229TPE", "22497NAT", "22722NVGR", "14221BKW", "22672CRL", "12741BLK", "51504CCBK", "51509BKW", "51509CCOR", "49229GRY", "64441BLK", "54047BLU", "12182LTBL", "64650CDB", "48979GRY", "22497BLK", "22826BBK", "48979NVY", "14221NVW", "14221TPE", "86218LHPK", "14221CHAR", "22497WHT", "22826NAT", "53988BBK", "12032NVY", "85609LMLT", "23400BLK", "81217LHPK", "23400TPE", "81217LBKMT", "22826GRY", "14205GRY", "96350LBRN", "14205BLPK", "12182PNK", "96350LBLU", "22832GRY", "85609LBKWP", "85610LDMLT", "12032PNK", "12032PERI", "12032CHAR", "22844GYMN", "85608LPRBL", "48761DKBR", "22832TPE", "48944BLK", "95488LBLK", "22517LTGY", "53737KHK", "81217LBLU", "64740BLK", "49221TAN", "95488LCCBK", "53737NVGY", "14050BKW", "22525BLK", "48935BLK", "34096NVY", "34096BLK", "64740LTBR", "64815DKTP", "34103BLK", "34103NVY", "34268BBK", "34268NAT", "22517CCL", "33756NTMT", "49226BLK", "14050NVBL", "49069BLK", "49272BLK", "97057LWBK", "14010HPK"),
			'gowalk' => array("14046BKW", "14060NVPK", "81072LPRNP", "14055CCPK", "14061HPLM", "14061HPBK", "81075LBKMT", "14010BKHP", "14068BKHP", "14046NVW", "81069LGYHP", "14061NVW", "14056NVW", "14058BLU", "14047PUR", "14058NVMT", "14179CLGYPK", "14060BKW", "14010NVCL", "14060CCBL", "14055NVBL", "14010BKLM", "14055CCBL", "14068MTMT", "14066GYHP", "14047TURQ", "53980NVGY", "54047GRY", "14058PNK", "14047NVW", "14068MULT", "14058BKMT", "81074LBLPK", "81074LCCNV", "54047NVGY", "14068HPOR", "54047BKGY", "81075LPRMT", "14011GYCL", "14011GYHP", "14047HPK", "54047BLU", "14011BKGY", "81072LTQOR", "81075LBLMT", "53988BBK", "54040BKGY", "54050NVW", "54050BKW", "14050BKW", "14056KHK", "14050NVBL", "14011CCTQ", "53980CCOR", "14010HPK"),
			'running' => array("14180CLBKPK", "54350BKW", "14800WHT", "14080CCHP", "54080BKLM", "14080CCBL", "54350CCOR", "54080NVOR", "54350NVGY", "54351BBK", "54351CCNV", "13996BKHP", "53997CCLM", "53996GRNV", "13997CCBL", "13997BKPR", "53997ORBK", "14005BKHP", "13997PROR", "14005PROR", "13996PRTL", "13997BLLM", "14115PKPR", "54005LMBK", "54105BKLM", "54105CCRD", "54115CCBL", "54115BKW", "14115NVCL", "14105PRAQ", "54110CCOR", "54110BLGR", "14105NVHP", "14100BKPK", "14100PRCC", "54100ORG", "54100BURG", "54100GROR"),
			'golf' => array("53530CCNV", "53530WNVR", "53546NVW", "53530BKW", "53529BBK", "53546CCBL", "53529WGBL", "53529CCBL", "53530BRN"),
			'puntillas' => array("49041BLK", "49041LTBR", "22752BKWP", "22752NVLB", "22752TPCL", "22752GYMN"),
			'alpargatas' => array("34096NVY", "34096BLK", "34103BLK", "34103NVY", "34268BBK", "34268NAT", "33756NTMT"),
			'sport' => array("12004NVPK", "11906CCPR", "12004CCAQ", "11906BKHP", "12448BKMT", "12004BBK", "22724BKW", "12055NVPK", "12055GYNP", "11906LGGR", "22724BKMT", "51361NVY", "51508GYOR", "22740NVAQ", "51521BKW", "81878LBKMT", "12055CCAQ", "12131CCPK", "12732BBLP", "12058BKW", "12437CCPK", "81878LAQMT", "12406BKW", "12131BKW", "81799LNVHP", "81799LNPMT", "50140TPBK", "51521CCRD", "81855NGYMT", "22724AQMT", "81905LBKHP", "12437BLHP", "12182NVY", "50140CCBK", "81499LPRNP", "22722CCCL", "22713HPBK", "51508NVLM", "51529CCBK", "12184BBK", "81844LGYAQ", "22740GYNP", "12004BMLT", "95519LBKBL", "51529NVOR", "12182BKW", "11870NVTQ", "12058GYTQ", "81072LPRNP", "12700NVMT", "81905LTQHP", "12184CCPK", "11965CCAQ", "22713BKAQ", "81650LGYMT", "996258LBKPK", "12404NVHP", "81075LBKMT", "51530CCBK", "81908LBBLP", "11965BLK", "81069LGYHP", "51530NVLM", "81650LBKMT", "12433NVAQ", "12730GRY", "10504NPKMT", "97300LNVOR", "12448HPMT", "12452BKPR", "12448AQMT", "12745PRMT", "12453TQMT", "996258LPRMT", "51521NVY", "121PRPK", "81861LNPMT", "95532LGYOR", "14202NVMT", "81852NTQMT", "11863NVY", "81853NPKAQ", "81852LMLT", "11863GRY", "12406NVBL", "51531BKGY", "22740BKCC", "80344LBKMT", "12734MULT", "81852LTQMT", "81909LBBLP", "95527LCCYL", "51531LGBK", "52107BMLT", "12447HPMT", "81702LBKNP", "95390LBNVL", "12447BKMT", "12732PRPK", "12058LGCL", "95513LBKLM", "22722PRPK", "11967BBK", "12433BKW", "95170NBBLM", "12753BKW", "12404GYMT", "95170NCCRD", "81074LBLPK", "81905LHPCR", "80344LPPAQ", "22724HPMT", "81074LCCNV", "95095NRYYL", "998091NRYYL", "11965NVLB", "121BKPR", "81555LBLPK", "51560TPBR", "12753GYCL", "95092NBLYL", "12735GYCL", "81908LNVHP", "95242LNVYL", "81852NMLT", "81075LPRMT", "12437HPMT", "22722NPYL", "12433PKPR", "51530GYBL", "80344LBLAQ", "12406GYHP", "81555LNPCL", "12184LBNV", "81908LNPTQ", "80594NLPMT", "81857LBKPK", "22722NVGR", "51531NVOR", "14202WMLT", "12447AQMT", "12133BKMT", "81068NGYMT", "52107BKW", "81650LNVMT", "51531BLLM", "95533LNVOR", "97301LRYYL", "12741BLK", "12754BKMT", "51509BKW", "51509CCOR", "80594NBLPK", "52107CCOR", "81498LCCNP", "12182LTBL", "95390LBKYO", "52107BLLM", "12454BKLB", "81072LTQOR", "51561BROR", "81851NMLT", "14200BKW", "95092NNVRD", "95533LCCRD", "996257NNPOR", "95092NCCOR", "84201LPRMT", "81498NCCNP", "51423NTGY", "81075LBLMT", "80035LCCMT", "81068NNPPK", "81701LBLHP", "81857LPRNP", "81853LPKAQ", "12740GYCL", "95532LNVLM", "12032NVY", "85609LMLT", "52108BKBL", "95096NBKLM", "81217LHPK", "81842LNPMT", "996259LPKPR", "81499LCCNP", "97303LBKMT", "51560GYBK", "95316LCCOR", "81217LBKMT", "97301LLMBK", "80033LGYPK", "90520LSLBK", "81498LCCMT", "51483CCLM", "81908LCCPK", "141BKHP", "11863BLK", "52108CCBK", "12182PNK", "90461LCCBK", "84201LBLMT", "81853NSMLT", "998091NCCOR", "95316LRYOR", "96350LBLU", "97300LCCBK", "998091NBKRD", "85609LBKWP", "52180BKW", "51138TPE", "51423BKGY", "12433AQUA", "85610LDMLT", "12032PNK", "12032PERI", "81853LSMLT", "95482LNVOR", "51483NVRD", "97300LBKRD", "51561CCLM", "12032CHAR", "12113NVHP", "90520LRYYL", "141NVAQ", "12732HPMT", "22602BKW", "97301LBKW", "95519LCCOR", "81498LBKNP", "85608LPRBL", "95096NNVRD", "51423NVGY", "90461NCCBK", "52108NVGY", "176CCL", "84201LHPMT", "12753NVPK", "12404TURQ", "12740BKLB", "95140LCCOR", "97370LCCOR", "95525LBLLM", "95488LBLK", "81702NNPPR", "81861LNVHP", "12750BKMT", "12404BBK", "12735SLT", "14346NVTL", "51138CCBK", "95314LCCBK", "142BURG", "90293LRDBK", "81217LBLU", "12113BLHP", "95525LBKCC", "95170LCCRD", "10504NBLMT", "51138BKGY", "998090LBKRD", "95527LBKBL", "95170LBKSL", "80593LNPMT", "52102BKW", "52102CCLM", "52108BKW", "52107NVOR", "81245LBKHP", "81245LGYMT", "95488LCCBK", "90550NBBLM", "51551CCBL", "80591NMLT", "141GYMN", "81068NBKHP", "80591LMLT", "52300BURG", "52310BKW", "81494NPKLV", "90550NBCOR", "11870PRCL", "95341NBKYB", "996258LBLMT", "81498NCCMT", "998090LBLLM", "95482LCCLB", "95314LRYBK", "52300BKW", "95170LBBLM", "52310NVGY", "12113AQLM", "90491LBSRY", "123GYLV", "123TPCL", "12452GYMN", "12454HPGR", "14230BKHP", "123BKLV", "14200BLPK", "14230CCPR", "996257NBLPR", "52107LGLM", "998090LNVBL", "12454TQHP", "52310OLBR", "12759PKMT", "176NVY", "54230BKW", "52300NVNT", "84201LGYMT", "52310NTBL", "145NVY", "81854NSPPR", "10901LBKMT", "125NVPK", "143BKGD", "80032LBPPR", "95527LNVRD", "81493LBLAQ", "95955LRYBK", "84200LBLMT", "51480NVYL", "84200LHPMT", "84200LPRMT", "95513LCCRD", "95956LBKYL", "12449HPMT", "81498NSMLT", "93890LBLK", "93890LBRN"),
			'burst' => array("12732BBLP", "12437CCPK", "81905LBKHP", "12437BLHP", "81905LTQHP", "81908LBBLP", "12433NVAQ", "12730GRY", "12745PRMT", "12734MULT", "81909LBBLP", "52107BMLT", "12732PRPK", "12433BKW", "81905LHPCR", "12735GYCL", "81908LNVHP", "12437HPMT", "12433PKPR", "81908LNPTQ", "52107BKW", "12741BLK", "52107CCOR", "52107BLLM", "12740GYCL", "52108BKBL", "97303LBKMT", "81908LCCPK", "52108CCBK", "12433AQUA", "12732HPMT", "64732BRN", "52108NVGY", "12740BKLB", "12735SLT", "52102BKW", "52102CCLM", "52108BKW", "52107NVOR", "52107LGLM"),
			'retro' => array("121PRPK", "121BKPR", "84201LPRMT", "141BKHP", "84201LBLMT", "141NVAQ", "176CCL", "84201LHPMT", "142BURG", "141GYMN", "52300BURG", "52310BKW", "52300BKW", "52310NVGY", "123GYLV", "123TPCL", "123BKLV", "52310OLBR", "176NVY", "52300NVNT", "84201LGYMT", "52310NTBL", "145NVY", "125NVPK", "143BKGD"),
			'skech_air' => array("80035LCCMT", "80033LGYPK", "90520LSLBK", "12113NVHP", "90520LRYYL", "95140LCCOR", "12113BLHP", "12113AQLM", "14230BKHP", "14230CCPR", "54230BKW", "80032LBPPR", "51480NVYL"),
			'starwars' => array("97022LSLBL", "97020LBKRD", "97006LBKRD", "97043LBKRD", "97038NBKMT", "97006LWBK", "97022NSLBL", "97014NBLU", "97002LBKW", "97054LBKRD", "97057LWBK", "97003LBKMT", "97000NBKRD", "97000NWBRY", "97000LBKRD", "97020LRDBK"),
			'go_flex' => array("14010BKHP", "14010NVCL", "14010BKLM", "14011GYCL", "14011GYHP", "14011BKGY", "14011CCTQ", "14010HPK"),
			'go_fit' => array("14086GYHP", "14086PUR", "14088BKW", "14091BKMT", "14087BKW", "14091BLMT")
		);
		$cont = $total = 0;
		if ($tags)
		{
			foreach ($tags as $tag => $productosList)
			{
				$options = array(
					'conditions' => array(
						'Producto.grupo LIKE' => '%'.$tag.'%',
						'Producto.coleccion_id' => 8
					),
					'fields' => array(
						'Producto.id',
						'Producto.codigo_completo',
						'Producto.coleccion_id',
						'Producto.activo',
						'Producto.grupo'
					)
				);
				$productos = $this->Andain->Producto->find('all', $options);
				$save = array();
				foreach ($productos as $index => $producto)
				{
					$productos[$index]['Producto']['grupo'] = array();
					$grupos = explode(']', $producto['Producto']['grupo']);
					foreach ($grupos as $grupo)
					{
						$grupo = trim(str_replace(array('[',']'),'', $grupo));
						$largo = strlen($grupo);
						if ($largo < 1 || $largo > 30)
							continue;
						if ($limpiar && in_array($grupo, $limpiar))
							continue;
						if ($grupo == $tag)
							continue;
						//if (strpos($grupo, $tag))
						//	continue;
						//if (strpos($grupo, str_replace('_', '', $tag)))
						//	continue;
						$productos[$index]['Producto']['grupo'][] = $grupo;
					}
					//if (in_array($producto['Producto']['codigo_completo'], $productosList))// agregar tag a listado de nuevos
					//{
					//	$productos[$index]['Producto']['grupo'][] = $tag;
					//	$cont++;
					//}
					if ($productos[$index]['Producto']['grupo'])
					{
						$productos[$index]['Producto']['grupo'] = '['.implode($productos[$index]['Producto']['grupo'], '][').']';
					}
					else
					{
						$productos[$index]['Producto']['grupo'] = '';
					}
					$save[] = array(
						'id' => $productos[$index]['Producto']['id'],
						'grupo' => $productos[$index]['Producto']['grupo']
					);
				}
				pr('===== ' . $tag . ' =====');
				if ($save)
				{
					if ($this->Andain->Producto->saveAll($save))
					{
						pr(' productos removidos del tag: ' .count($save));
					}
				}
				$save = array();
				$options = array(
					'conditions' => array(
						'Producto.codigo_completo' => $productosList,
					),
					'fields' => array(
						'Producto.id',
						'Producto.codigo_completo',
						'Producto.coleccion_id',
						'Producto.activo',
						'Producto.grupo'
					)
				);
				$productos = $this->Andain->Producto->find('all', $options);
				if ($productos)
				{
					foreach ($productos as $index => $producto)
					{
						$productos[$index]['Producto']['grupo'] = array();
						$grupos = explode(']', $producto['Producto']['grupo']);
						foreach ($grupos as $grupo)
						{
							$grupo = trim(str_replace(array('[',']'),'', $grupo));
							$largo = strlen($grupo);
							if ($largo < 1 || $largo > 30)
								continue;
							if ($limpiar && in_array($grupo, $limpiar))
								continue;
							if ($grupo == $tag)
								continue;
							//if (strpos($grupo, $tag))
							//	continue;
							//if (strpos($grupo, str_replace('_', '', $tag)))
							//	continue;
							$productos[$index]['Producto']['grupo'][] = $grupo;
						}
						if (in_array($producto['Producto']['codigo_completo'], $productosList))// agregar tag a listado de nuevos
						{
							$productos[$index]['Producto']['grupo'][] = $tag;
							$cont++;
						}
						if ($productos[$index]['Producto']['grupo'])
						{
							$productos[$index]['Producto']['grupo'] = '['.implode($productos[$index]['Producto']['grupo'], '][').']';
						}
						else
						{
							$productos[$index]['Producto']['grupo'] = '';
						}
						$save[] = array(
							'id' => $productos[$index]['Producto']['id'],
							'grupo' => $productos[$index]['Producto']['grupo']
						);
					}
				}
				if ($save)
				{
					if ($this->Andain->Producto->saveAll($save))
					{
						pr(' productos agregados al tag: ' .count($save));
					}
				}
			}
		}
		prx('fin');
	}
	
	function transacciones($tipo = 0)
	{
		$baseDir = 'C:\\Users\\ANDAIN - EDUX\\Desktop\\';
		if ($tipo == 0)
		{
			//cuentas que han realizado al menos una transacción en este año calendario
			$list = $this->Andain->Producto->Compra->find('list', array(
				'conditions' => array(
					'Compra.estado' => 1
				),
				'fields' => array(
					'Compra.usuario_id',
					'Compra.usuario_id'
				)
			));
			$list = array_merge($list, array(
				1, 5, 7, 8, 9, 14, 16
			));
			$options = array(
				'conditions' => array(
					'NOT' => array(
						array('Usuario.id' => $list)
					)
				),
				'fields' => array(
					'Usuario.id',
					'Usuario.nombre',
					'Usuario.apellido_paterno',
					'Usuario.apellido_materno',
					'Usuario.sexo_id',
					'Usuario.rut',
					'Usuario.fecha_nacimiento',
					'Usuario.email',
					'Usuario.created',
				),
			);
			if (! $usuarios = $this->Andain->Producto->Compra->Usuario->find('all', $options))
			{
				die('sin registros...');
			}
			$fileName = $baseDir."usuarios_sin_transacciones.xls";
			$fp = fopen($fileName,"w");
			fwrite($fp,"<table><tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>RUT</th><th>Email</th><th>Fecha Nacimiento</th><th>Fecha Registro</th><th>Cantidad de compras</th></tr>");
			foreach ($usuarios as $usuario)
			{
				$apellido = $usuario['Usuario']['apellido_paterno'];
				if ($usuario['Usuario']['apellido_materno'])
				{
					$apellido.=' '.$usuario['Usuario']['apellido_materno'];
				}
				$fNacimiento = date('d-m-Y', strtotime($usuario['Usuario']['fecha_nacimiento']));
				$fRegistro = date('d-m-Y H:i', strtotime($usuario['Usuario']['created']));
				$txt = "<tr>";
				$txt.="<td>{$usuario['Usuario']['id']}</td><td>{$usuario['Usuario']['nombre']}</td><td>{$apellido}</td><td>{$usuario['Usuario']['rut']}</td><td>{$usuario['Usuario']['email']}</td><td>{$fNacimiento}</td><td>{$fRegistro}</td><td>0</td>";
				$txt.="</tr>";
				fwrite($fp,utf8_decode($txt));
			}
			fwrite($fp,"</table>");
			fclose($fp);
			die('archivo creado en el escritorio "'.basename($fileName).'"');
		}
		elseif ($tipo == 1)
		{
			//cuentas que han realizado al menos una transacción en este año calendario
			$options = array(
				'conditions' => array(
					'Transaccion.estado' => 1,
					'Transaccion.created >=' => "2016-01-01 00:00:00"
				),
				'fields' => array(
					'Usuario.id',
					'Usuario.nombre',
					'Usuario.apellido_paterno',
					'Usuario.apellido_materno',
					'Usuario.sexo_id',
					'Usuario.rut',
					'Usuario.fecha_nacimiento',
					'Usuario.email',
					'Usuario.created',
					'COUNT(Transaccion.id) as compras_count'
				),
				'joins' => array(
					array(
						'table' => 'sitio_compras',
						'alias' => 'Transaccion',
						'type' => 'INNER',
						'conditions' => array(
							'Transaccion.usuario_id = Usuario.id'
						)
					),
				),
				'group' => array(
					'Usuario.id'
				),
				//'limit' => 10
			);
			if (! $usuarios = $this->Andain->Producto->Compra->Usuario->find('all', $options))
			{
				die('sin registros...');
			}
			$fileName = $baseDir."usuarios_con_transacciones.xls";
		}
		elseif ($tipo == 2)
		{
			//y los que realizaron transacciones en años anteriores
			$options = array(
				'conditions' => array(
					'Transaccion.estado' => 1,
					'Transaccion.created <' => "2016-01-01 00:00:00"
				),
				'fields' => array(
					'Usuario.id',
					'Usuario.nombre',
					'Usuario.apellido_paterno',
					'Usuario.apellido_materno',
					'Usuario.sexo_id',
					'Usuario.rut',
					'Usuario.fecha_nacimiento',
					'Usuario.email',
					'Usuario.created',
					'COUNT(Transaccion.id) as compras_count'
				),
				'joins' => array(
					array(
						'table' => 'sitio_compras',
						'alias' => 'Transaccion',
						'type' => 'INNER',
						'conditions' => array(
							'Transaccion.usuario_id = Usuario.id'
						)
					),
				),
				'group' => array(
					'Usuario.id'
				),
			);
			if (! $usuarios = $this->Andain->Producto->Compra->Usuario->find('all', $options))
			{
				die('sin registros...');
			}
			$fileName = $baseDir."usuarios_con_transacciones_anteriores.xls";
		}
		else
		{
			die('ERROR');
		}

		$fp = fopen($fileName,"w");
		fwrite($fp,"<table><tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>RUT</th><th>Email</th><th>Fecha Nacimiento</th><th>Fecha Registro</th><th>Cantidad de compras</th></tr>");
		foreach ($usuarios as $usuario)
		{
			$apellido = $usuario['Usuario']['apellido_paterno'];
			if ($usuario['Usuario']['apellido_materno'])
			{
				$apellido.=' '.$usuario['Usuario']['apellido_materno'];
			}
			$fNacimiento = date('d-m-Y', strtotime($usuario['Usuario']['fecha_nacimiento']));
			$fRegistro = date('d-m-Y H:i', strtotime($usuario['Usuario']['created']));
			$txt = "<tr>";
			$txt.="<td>{$usuario['Usuario']['id']}</td><td>{$usuario['Usuario']['nombre']}</td><td>{$apellido}</td><td>{$usuario['Usuario']['rut']}</td><td>{$usuario['Usuario']['email']}</td><td>{$fNacimiento}</td><td>{$fRegistro}</td><td>{$usuario[0]['compras_count']}</td>";
			$txt.="</tr>";
			fwrite($fp,utf8_decode($txt));
		}
		fwrite($fp,"</table>");
		fclose($fp);
		die('archivo creado en el escritorio "'.basename($fileName).'"');
	}
	
	function detalle_compras($tipo = 0)
	{
		Configure::write('debug',1);
		set_time_limit(0);

		//cuentas que han realizado al menos una transacción en este año calendario
		$options = array(
			'conditions' => array(
				'Compra.estado' => 1,
				'Compra.created >=' => "2016-01-01 00:00:00"
			),
			'fields' => array(
				'Compra.id',
				'Compra.boleta',
				'Compra.picking_number',
				'Compra.cod_despacho',
				'Compra.despacho_id',
				'Compra.usuario_id',
				'Compra.subtotal',
				'Compra.iva',
				'Compra.neto',
				'Compra.descuento',
				'Compra.total',
				'Compra.valor_despacho',
				'Compra.despachado',
				'Compra.enviado',
				'Compra.fecha_enviado',
				'Compra.created',
				'Pago.id',
				'Pago.compra_id',
				'Pago.monto',
				'Pago.numeroTarjeta',
				'Pago.codAutorizacion',
				'Pago.cuotas',
				'Pago.tipoPago',
				'Pago.codigo',
			),
			'contain' => array(
				'Usuario' => array(
					'fields' => array(
						'Usuario.id',
						'Usuario.nombre',
						'Usuario.apellido_paterno',
						'Usuario.apellido_materno',
						'Usuario.sexo_id',
						'Usuario.rut',
						'Usuario.email',
					)
				),
				'Despacho' => array(
					'fields' => array(
						'Despacho.id',
						'Despacho.direccion_id',
					),
					'Direccion' => array(
						'fields' => array(
							'Direccion.id',
							'Direccion.calle',
							'Direccion.numero',
							'Direccion.depto',
							'Direccion.comuna_id'
						),
						'Comuna' => array(
							'fields' => array(
								'Comuna.id',
								'Comuna.nombre',
								'Comuna.region_id'
							),
							'Region.nombre'
						)
					)
				),
				'Producto' => array(
					'fields' => array(
						'Producto.id',
						'Producto.nombre',
						'Producto.codigo_completo',
					),
				)
			),
			'joins' => array(
				array(
					'table' => 'sitio_pagos',
					'alias' => 'Pago',
					'type' => 'LEFT',
					'conditions' => array(
						'Pago.compra_id = Compra.id'
					)
				)
			),
			'limit' => 10
		);
		if (! $compras = $this->Andain->Producto->Compra->find('all', $options))
		{
			die('sin registros...');
		}
		$baseDir = 'C:\\Users\\ANDAIN - EDUX\\Desktop\\';
		$fileName = $baseDir."compras.xls";
		$header = '<table>'.
			'<tr>'.
				'<td colspan="13">COMPRA</td>'.
				'<td colspan="3">CLIENTE</td>'.
				'<td colspan="6">PAGO</td>'.
				'<td colspan="3">DESPACHO</td>'.
				'<td colspan="60">PRODUCTOS</td>'.
			'</tr>'.
			'<tr>'.
				'<td>ID</td>'.
				'<td>FECHA</td>'.
				'<td>BOLETA</td>'.
				'<td>PICKING</td>'.
				'<td>COD. DESPACHO</td>'.
				'<td>SUBTOTAL</td>'.
				'<td>IVA</td>'.
				'<td>NETO</td>'.
				'<td>DESCUENTO</td>'.
				'<td>TOTAL</td>'.
				'<td>DESPACHADO</td>'.
				'<td>ENVIADO</td>'.
				'<td>FECHA ENVIO</td>'.
				'<td>NOMBRE</td>'.
				'<td>RUT</td>'.
				'<td>EMAIL</td>'.
				'<td>MONTO</td>'.
				'<td>N TARJETA</td>'.
				'<td>COD. AUTORIZACION</td>'.
				'<td>CUOTAS</td>'.
				'<td>TIPO PAGO</td>'.
				'<td>ID TRANSACCION</td>'.
				'<td>DIRECCION</td>'.
				'<td>COMUNA</td>'.
				'<td>REGION</td>';
		for ($x = 1; $x <= 20; $x++)
		{
			$header.="<td>PRODUCTO #{$x}</td><td>CODIGO #{$x}</td><td>CATEGORIA #{$x}</td><td>TALLA #{$x}</td><td>VALOR #{$x}</td>";
		}
		$header.="</tr>";
		$fp = fopen($fileName,"w");
		fwrite($fp,$header);
		foreach ($compras as $compra)
		{
			$item = "<tr>";
			$item.="<td>{$compra['Compra']['id']}</td>";
			$item.="<td>".date('d-m-Y H:i', strtotime($compra['Compra']['created']))."</td>";
			$item.="<td>{$compra['Compra']['boleta']}</td>";
			$item.="<td>{$compra['Compra']['picking_number']}</td>";
			$item.="<td>{$compra['Compra']['cod_despacho']}</td>";
			$item.="<td>{$compra['Compra']['subtotal']}</td>";
			$item.="<td>{$compra['Compra']['iva']}</td>";
			$item.="<td>{$compra['Compra']['neto']}</td>";
			$item.="<td>{$compra['Compra']['descuento']}</td>";
			$item.="<td>{$compra['Compra']['total']}</td>";
			$item.="<td>".( $compra['Compra']['despachado'] ? 'si':'no')."</td>";
			$item.="<td>".( $compra['Compra']['enviado'] ? 'si':'no')."</td>";
			$item.="<td>".date('d-m-Y H:i', strtotime($compra['Compra']['fecha_enviado']))."</td>";
			$item.="<td>".$compra['Usuario']['nombre']." ".$compra['Usuario']['apellido_paterno']." ".$compra['Usuario']['apellido_materno']."</td>";
			$item.="<td>{$compra['Usuario']['rut']}</td>";
			$item.="<td>{$compra['Usuario']['email']}</td>";
			$item.="<td>{$compra['Pago']['monto']}</td>";
			$item.="<td>{$compra['Pago']['numeroTarjeta']}</td>";
			$item.="<td>{$compra['Pago']['codAutorizacion']}</td>";
			$item.="<td>{$compra['Pago']['cuotas']}</td>";
			$item.="<td>{$compra['Pago']['tipoPago']}</td>";
			$item.="<td>{$compra['Pago']['codigo']}</td>";
			$item.="<td>{$compra['Despacho']['Direccion']['calle']} {$compra['Despacho']['Direccion']['numero']} {$compra['Despacho']['Direccion']['depto']}</td>";
			$item.="<td>{$compra['Despacho']['Direccion']['Comuna']['nombre']}</td>";
			$item.="<td>{$compra['Despacho']['Direccion']['Comuna']['Region']['nombre']}</td>";
			foreach ($compra['Producto'] as $producto)
			{
				$item.="<td>{$producto['nombre']}</td>";
				$item.="<td>{$producto['codigo_completo']}</td>";
				$item.="<td>{$producto['ProductosCompra']['categoria']}</td>";
				$item.="<td>{$producto['ProductosCompra']['talla']}</td>";
				$item.="<td>{$producto['ProductosCompra']['valor']}</td>";
			}
			$item.="</tr>";
			fwrite($fp,utf8_decode($item));
		}
		fwrite($fp,"</table>");
		fclose($fp);
		die('archivo creado en el escritorio "'.basename($fileName).'"');
	}
}
?>