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
			temporal = true,
			titulo = '<b>Mensaje de Sistema</b>',
			autoClose;
		// alerta archivo corrupto
		if (mensaje == 'ERROR_ARCHIVO-0001') {
			titulo = '<b>Archivo Corrupto</b>';
			mensaje = '<b style="color: Red;">El archivo fue guardado correctamente, pero presenta conflictos</b>';
			temporal = false;
		}
		cuadroMensaje.dialog(
		{
			title		: titulo,
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
	
		if (temporal) {
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
	
	//$('.lista-tiendas').click(function(evento)
	//{
	//	evento.preventDefault();
	//	var id = $(this).data('id');
	//	$('.lista-stockXtienda').hide();
	//	$('.tienda-' + id).show();
	//});
	
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
	
	//// HABILITA CAMPOS DE EDITION DE ORDEN DE BANNERS
	//$('#BannerAdminIndexForm .habilitar-orden').click(function(evento)
	//{
	//	evento.preventDefault();
	//	$(this).hide();
	//	$('#BannerAdminIndexForm .mostrar-orden').hide();
	//	
	//	$('#BannerAdminIndexForm .cambiar-orden').fadeIn(1000);
	//	$('#BannerAdminIndexForm #guardar-orden.btn-enviar').fadeIn(1000);
	//});
	//
	///**
	// * MUESTRA WARNING DE CAMPOS FALTANTES
	// *
	// */
	//$('#BannerAdminIndexForm #guardar-orden.btn-enviar').live('click', function(evento)
	//{
	//	evento.preventDefault();
	//	var acceso = true;
	//	$('input.clase-input').each(function(index, elemento)
	//	{
	//		if (! $(elemento).val() )
	//		{
	//			$(elemento).next().show();
	//			acceso = false;
	//		}
	//	});
	//	
	//	if ( acceso )
	//	{
	//		$('#BannerAdminIndexForm').submit();
	//	}
	//});
	//
	//// ELIMINA WARNING AL HACER FOCUS SOBRE EL CAMPO
	//$('#BannerAdminIndexForm input').focus(function()
	//{
	//	$(this).next().fadeOut(1000);
	//});
	
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

	$('.descripcion-tool').tooltip();

	$("#DireccionRegionId").change(function() {
		var elemento = $(this),
			formulario = $(this).parents('form'),
			target = formulario.find("#DireccionComunaId");
		target.html("<option>Cargando Comunas...</option>");
		if (elemento.val() != "0") {
			$.ajax({
				type: "GET",
				async: true,
				url: webroot + "direcciones/ajax_comunas/"+$(this).val(),                       
				success: function(data){
					target.html(data);
				}
			});
		} else {
			alert('debe seleccionar una region');
		}
    });
});
//]]>


