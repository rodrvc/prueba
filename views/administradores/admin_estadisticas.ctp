<div class="col02">
	<?= $this->element('admin_buscar_compra'); ?>
	<? if ($authUser['id'] == 5) : ?>
	<div id="infoCompras">
		<h2>Compras realizadas Ecommerce</h2>
		<br>
		<table style="background-color: rgb(48, 163, 213);" width="100%">
			<tr>
				<th style="border: 1px solid rgb(10, 91, 126); font-weight: bold;">total compras</th>
				<th style="border: 1px solid rgb(10, 91, 126); font-weight: bold;">exito</th>
				<th style="border: 1px solid rgb(10, 91, 126); font-weight: bold;">email pendiente</th>
								<th style="border: 1px solid rgb(10, 91, 126); font-weight: bold;">Pares</th>

			</tr>
			<tr>
				<td style="border: 1px solid rgb(10, 91, 126);" rel="total">0</td>
				<td style="border: 1px solid rgb(10, 91, 126);" rel="exito">0</td>
				<td style="border: 1px solid rgb(10, 91, 126);" rel="pendiente">0</td>
				<td style="border: 1px solid rgb(10, 91, 126);" rel="pares">0</td>

			</tr>
		</table>
	</div>
	<br>
	<div id="infoCompras2">
		<h2>Ventas Marketplaces</h2>
		<br>
		<table style="background-color: rgb(48, 163, 213);" width="100%">
			<tr>
				<th colspan="2" width="50%" style="border: 1px solid rgb(10, 91, 126); font-weight: bold;" rel="marketplace_1">Marketaplace1</th>
				<th colspan="2" width="50%" style="border: 1px solid rgb(10, 91, 126); font-weight: bold;" rel="marketplace_2">Marketaplace2</th>
			</tr>
			<tr>
				<td style="border: 1px solid rgb(10, 91, 126);" rel="ventas_1">$0</td>
				<td style="border: 1px solid rgb(10, 91, 126);" rel="cantidad_1">0</td>
				<td style="border: 1px solid rgb(10, 91, 126);" rel="ventas_2">$0</td>
				<td style="border: 1px solid rgb(10, 91, 126);" rel="cantidad_2">0</td>

			</tr>
		</table>
	</div>
	<hr>
	<? endif; ?>
	<div id="cantidadVentas" style="width: 680px; height: 500px;"></div>
	<hr>
	<div id="montoVentas" style="width: 680px; height: 500px;"></div>
	<hr>
	<? if ($authUser['id'] == 5) : ?>
	<div id="logCompras">
		<h2>
			Log de compras del día
			<a href="#" style="float: right;" rel="actualizarLog">
				<img src="<?= $this->Html->url('/img/iconos/reload_16.png'); ?>" />
			</a>
		</h2>
		<br>
		<iframe scrolling="auto" src="http://store.skechers-chile.cl/archivos/CORREOS_COMPRA/log_<?= date('Y-m-d'); ?>.txt" frameborder="0" height="250px" width="680px" style="background-color: rgb(48, 163, 213);"></iframe>
	</div>
	<hr>
	<? endif; ?>
	<div id="rankProductos">
		<h2>10 productos mas vendidos en los ultimos 30 días</h2>
		<br>
		<div style="max-height: 300px; ouverflow-x: hidden; overflow-y: auto;">
			<table width="100%" style="background-color: rgb(48, 163, 213);">
				<? $cont=0; ?>
				<? foreach ($productos as $producto) : ?>
					<tr>
						<td style="padding: 0;">
							<?= ++$cont; ?>
						</td>
						<td style="padding: 0;">
							<img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['mini']); ?>">
						</td>
						<td style="padding: 0;">
							<b><?= $producto['Producto']['nombre']; ?></b><br>
							<?= $producto['Producto']['codigo_completo']; ?>
						</td>
						<td style="padding: 0;">
							<?= $producto[0]['count']; ?>
						</td>
					<tr>
					<tr>
						<td colspan="4" style="padding: 0;">
							<hr>
						</td>
					</tr>
				<? endforeach; ?>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	<? if ($authUser['id'] == 5) : ?>
	var respuesta2
	function actualizarInfo() {
		$.ajax({
			async	: true,
			dataType: "json",
			url		: webroot + 'administradores/ajax_refresh_info',
			success	: function(respuesta) {
				respuesta2=respuesta;
				console.log(respuesta);
				if (respuesta) {
					if (respuesta.total) {
						$('#infoCompras table td[rel="total"]').text(respuesta.total);
					}
					if (respuesta.exito) {
						$('#infoCompras table td[rel="exito"]').text(respuesta.exito);
					}
					if (respuesta.pendiente) {
						$('#infoCompras table td[rel="pendiente"]').text(respuesta.pendiente);
					}
					if (respuesta.pares) {
						$('#infoCompras table td[rel="pares"]').text(respuesta.pares);
					}
					if (respuesta.marketaplaces[0].sitio_compras.ip) {
						$('#infoCompras2 table th[rel="marketplace_1"]').text(respuesta.marketaplaces[0].sitio_compras.ip);
					}
					if (respuesta.marketaplaces[1].sitio_compras.ip) {
						$('#infoCompras2 table th[rel="marketplace_2"]').text(respuesta.marketaplaces[1].sitio_compras.ip);
					}
					if (respuesta.marketaplaces[0][0].cantidad) {
						$('#infoCompras2 table td[rel="cantidad_1"]').text(respuesta.marketaplaces[0][0].cantidad);
					}
					if (respuesta.marketaplaces[0][0].venta) {
						$('#infoCompras2 table td[rel="ventas_1"]').text('$'+respuesta.marketaplaces[0][0].venta);
					}
					if (respuesta.marketaplaces[1][0].cantidad) {
						$('#infoCompras2 table td[rel="cantidad_2"]').text(respuesta.marketaplaces[1][0].cantidad);
					}
					if (respuesta.marketaplaces[1][0].venta) {
						$('#infoCompras2 table td[rel="ventas_2"]').text('$'+respuesta.marketaplaces[1][0].venta);
					}

				}
			}
		});
	}
	$(document).ready(function() {
		actualizarInfo();
		setInterval(actualizarInfo, 300000);

		$('a[rel="actualizarLog"]').click(function(e) {
			e.preventDefault();
			var target = $('#logCompras iframe');
			if (! $(target).length) {
				return false;
			}
			$(target).attr('src',$(target).attr('src'));
		});
	});
	<? endif; ?>
	google.load("visualization", "1", {packages:["corechart"]});
  	google.setOnLoadCallback(cantidadVentas);
  	function cantidadVentas() {
    	var data = google.visualization.arrayToDataTable([
		  	<? 
		  	foreach ($compras as $compra)
		  	{
		  		if (reset($compras) == $compra)
		  		{
		  			echo "['Día', 'Promedio', 'Total', 'Exito']";
		  		}
		  		$data = ",['".date('d-m-Y',strtotime($compra['Compra']['created']))."',".$compra[0]['promedio_ventas'].",".$compra[0]['total'].",".$compra[0]['exito']."]";
		  		echo $data;
		  	}
		  	?>
    	]);

	    var options = {
			title: 'Cantidad de ventas de los últimos 30 días',
			hAxis: {title: 'Día',  titleTextStyle: {color: '#333'}},
			vAxis: {minValue: 0},
			legend: { position: 'top' },
			seriesType: "line",
    		series: {
    			0: {
    				type: "steppedLine", 
    				visibleInLegend: false, 
    				areaOpacity: 0,
    				color: '#FF0000'
    			},
    			1: {
    				color: '#F58E43'
    			},
    			2: {
    				type: 'area',
    				color: '#06DE4D'
    			}
    		}
	    };

	    var chart = new google.visualization.LineChart(document.getElementById('cantidadVentas'));
	    chart.draw(data, options);
  	}

  	google.load("visualization", "1", {packages:["corechart"]});
  	google.setOnLoadCallback(montoVentas);
  	function montoVentas() {
    	var data = google.visualization.arrayToDataTable([
		  	<? 
		  	foreach ($compras as $compra)
		  	{
		  		if (reset($compras) == $compra)
		  		{
		  			echo "['Día', 'Promedio', 'Total']";
		  		}
		  		$data = ",['".date('d-m-Y',strtotime($compra['Compra']['created']))."',".$compra[0]['promedio_monto'].",".$compra[0]['monto']."]";
		  		echo $data;
		  	}
		  	?>
    	]);

	    var options = {
			title: 'Valores ventas de los últimos 30 días',
			hAxis: {title: 'Día',  titleTextStyle: {color: '#333'}},
			vAxis: {minValue: 0},
			legend: { position: 'top' },
			seriesType: "line",
    		series: {
    			0: {
    				type: "steppedLine", 
    				visibleInLegend: false, 
    				areaOpacity: 0,
    				color: '#FF0000'
    			},
    			1: {
    				type: 'area',
    				color: '#41D9EC'
    			}
    		}
	    };

	    var chart = new google.visualization.LineChart(document.getElementById('montoVentas'));
	    chart.draw(data, options);
  	}
</script>