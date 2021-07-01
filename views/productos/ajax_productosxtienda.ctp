<?
foreach ( $resultados as $resultado ) :
echo '
<div class="con-tienda">
	<span>&nbsp;</span>
	<h3>' . $resultado['Tienda']['nombre'] . '</h3>
	<a href="#" title="mapa" class="btn-mapa mapa-' . $resultado['Tienda']['id'] . '"
			data-tienda="' . $resultado['Tienda']['nombre'] . '"
			data-direccion="' . $resultado['Tienda']['direccion'] . '"
			data-latitud="' . $resultado['Tienda']['latitud'] . '"
			data-longitud="' . $resultado['Tienda']['longitud'] . '"
			data-telefono="' . $resultado['Tienda']['telefono'] . '">Mapa<span class="flechita">&nbsp;</span></a>
</div>';
endforeach;
?>


