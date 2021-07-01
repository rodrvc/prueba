<style>
.categoria.current {
	text-decoration: underline;
}
#mapa.g-map .gm-style .gm-style-iw > div {
	min-height: 80px !important;
}
</style>
<?= $this->Html->script('http://maps.google.com/maps/api/js?sensor=false'); ?>
<?
$tiendas = array();
if ($zonas)
{
	foreach ($zonas as $zona)
	{
		if (isset($zona['Tienda']) && $zona['Tienda'])
		{
			foreach ($zona['Tienda'] as $tienda)
			{
				$tiendas[]['Tienda'] = $tienda;
			}
		}
	}
}
?>
<?= $this->Html->scriptBlock("var tiendas = " . json_encode($tiendas) . ";"); ?>
<script>
var iniciado = false, map;
function iniciarMapa()
{
	// CONTENEDOR DEL MAPA
	var mapDiv		= document.getElementById('mapa');
	// OPCIONES DEL MAPA
	var mapOpciones	= {
		center				: new google.maps.LatLng(-33.408875,-70.567338),
		zoom				: 5,
		mapTypeId			: google.maps.MapTypeId.ROADMAP,
		panControl			: false,
		zoomControl			: true,
		zoomControlOptions	: { style			: google.maps.ZoomControlStyle.LARGE },
		mapTypeControl		: false,
		scaleControl		: false,
		streetViewControl	: false
	};
	// inicializar
	map			= new google.maps.Map(mapDiv, mapOpciones);
	var infowindow = new google.maps.InfoWindow({
		content: ''
	});
	// cargar tiendas en mapa
	google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
		// imprime marcadores
		for ( index in tiendas ) {
			var marker	= new google.maps.Marker({
				map				: map,
				icon			: new google.maps.MarkerImage(webroot + 'img/marcador2.png',
															  new google.maps.Size(60, 60),
															  new google.maps.Point(0, 0),
															  new google.maps.Point(30, 56),
															  new google.maps.Size(60, 56)),
				position		: new google.maps.LatLng(tiendas[index].Tienda.latitud, tiendas[index].Tienda.longitud),
				title			: tiendas[index].Tienda.nombre
			});
			// info al marcador de tienda
			var info =	'<div id="content">'+
							'<div id="siteNotice">'+tiendas[index].Tienda.nombre+'</div>'+
							'<h3 id="firstHeading" class="firstHeading"></h3>'+
							'<div id="bodyContent">'+
								'<p><b>'+tiendas[index].Tienda.direccion+'</b></p>'+
								'<p>'+tiendas[index].Tienda.Comuna.nombre+', '+tiendas[index].Tienda.Region.nombre+'</p>'+
							'</div>'+
						'</div>';
			(function(marker, info){                      
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.setContent(info);
					infowindow.open(map, marker);
				});
			})(marker,info);
		}
		// remueve pie de mapa
		$('#mapa.g-map a[href^="http://www.google"]').remove();
		$('#mapa.g-map a[href^="http://maps.google.com"]').remove();
	});
	iniciado = true;
}

$(document).ready(function()
{
	$('.tiendas .t-bloques .info-s .ver-map').click(function(e) {
		e.preventDefault();
		var	latitud 	= $(this).data('latitud'),
			longitud 	= $(this).data('longitud');
			
		if ( latitud && longitud ) {
			<? if (isset($serv_produccion) && $serv_produccion) : ?>
			_gaq.push(['_trackEvent', 'Ver Mapa Tienda', $(this).data('tienda')]);
			<? endif; ?>
			if ( ! iniciado)
				iniciarMapa();
			$('.opacidad').show();
			var zoom = 16;
			map.panTo(new google.maps.LatLng(latitud, longitud));
			map.setZoom(zoom);
		}
	});
	$('.opacidad .video .cerrar').click(function(e) {
		e.preventDefault();
		$('.opacidad').hide();
	});
	$('.menu-outlet .categoria.zona').click(function(e) {
		e.preventDefault();
		var zona = $(this).data('zona');
		$('.menu-outlet .categoria').removeClass('current');
		$(this).addClass('current');
		$('.cont-catalogo .tiendas div[rel^="zona-"]').hide();
		$('.cont-catalogo .tiendas div[rel="zona-'+zona+'"]').fadeIn(500);
	});
});
</script>