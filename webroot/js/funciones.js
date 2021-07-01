/*jslint browser: true, cap: false, confusion: true, continue: true, css: true, debug: false, devel: true, eqeq: true, evil: false, forin: false, fragment: true, maxerr: 3, newcap: false, plusplus: true, regexp: true, sloppy: true, sub: false, undef: false, unparam: true, vars: false, white: true */
/*globals $, document, webroot, FB */


//<![CDATA[

//------------------------ JQUERY
function alerta(texto) {
	$('#modalAlerta .alert[rel="texto"]').removeClass('alert-success').addClass('alert-warning').html('<i class="fa fa-warning"></i> '+texto);
	$('#modalAlerta').modal('show');
}

function formatMoneda(numero) {
	var format = '';
	if (numero) {
		numero = numero.toString();
		var limite = numero.length;
		var string = '';
		for (x = 0;x<=limite;x++) {
			var pos = limite-x;
			if (x>1 && ((x-1)%3)==0)
				string+='.';
			string+= numero.substr(pos,1);
		}
		limite = string.length;
		for (x = 0;x<=limite;x++) {
			var pos = limite-x;
			format+= string.substr(pos,1);
		}
	} else {
		format = '0';
	}
	return format;
}

function datosDireccion(id) {
	$('table#tablaDatos td[rel="direccionNombre"]').html('');
	$('table#tablaDatos td[rel="direccionCalle"]').html('');
	$('table#tablaDatos td[rel="direccionNumero"]').html('');
	$('table#tablaDatos td[rel="direccionDepto"]').html('');
	$('table#tablaDatos td[rel="direccionRegion"]').html('');
	$('table#tablaDatos td[rel="direccionComuna"]').html('');
	$('table#tablaDatos td[rel="direccionCodigoPostal"]').html('');
	$('table#tablaDatos td[rel="direccionTelefono"]').html('');
	$('table#tablaDatos td[rel="direccionCelular"]').html('');

	if (id && id >0) {
		$.ajax({
			async	: true,
			dataType: "json",
			type	: 'POST',
			url		: webroot + 'direcciones/ajax_datos',
			data	: { direccion_id : id },
			success	: function(direccion) {
				$('#domicilio2').show();
				$('#nueva').hide();
				if (direccion.Direccion.nombre)
					$('table#tablaDatos td[rel="direccionNombre"]').html(direccion.Direccion.nombre);
				if (direccion.Direccion.calle)
					$('table#tablaDatos td[rel="direccionCalle"]').html(direccion.Direccion.calle);
				if (direccion.Direccion.numero)
					$('table#tablaDatos td[rel="direccionNumero"]').html(direccion.Direccion.numero);
				if (direccion.Direccion.depto)
					$('table#tablaDatos td[rel="direccionDepto"]').html(direccion.Direccion.depto);
				if (direccion.Region.nombre)
					$('table#tablaDatos td[rel="direccionRegion"]').html(direccion.Region.nombre);
				if (direccion.Comuna.nombre)
					$('table#tablaDatos td[rel="direccionComuna"]').html(direccion.Comuna.nombre);
				if (direccion.Direccion.codigo_postal)
					$('table#tablaDatos td[rel="direccionCodigoPostal"]').html(direccion.Direccion.codigo_postal);
				if (direccion.Direccion.telefono)
					$('table#tablaDatos td[rel="direccionTelefono"]').html(direccion.Direccion.telefono);
				if (direccion.Direccion.celular)
					$('table#tablaDatos td[rel="direccionCelular"]').html(direccion.Direccion.celular);
			}
		});
	}else{
		$('#domicilio2').hide();
		$('#nueva').show();
	}
	return false;
}

function datosTienda(id) {
	$('table#tablaDatos td[rel="tiendaDireccion"]').html('');
	$('table#tablaDatos td[rel="tiendaRegion"]').html('');
	$('table#tablaDatos td[rel="tiendaComuna"]').html('');
	$('table#tablaDatos td[rel="tiendaTelefono"]').html('');

console.log('aca');
	if (id) {
		$.ajax({
			async	: true,
			dataType: "json",
			type	: 'POST',
			url		: webroot + 'tiendas/ajax_datos',
			data	: { tienda_id : id },
			success	: function(tienda) {
				console.log(tienda)
				if (tienda.Tienda.direccion)
					$('table#tablaDatos td[rel="tiendaDireccion"]').html(tienda.Tienda.direccion);
				if (tienda.Region.nombre)
					$('table#tablaDatos td[rel="tiendaRegion"]').html(tienda.Region.nombre);
				if (tienda.Comuna.nombre)
					$('table#tablaDatos td[rel="tiendaComuna"]').html(tienda.Comuna.nombre);
				if (tienda.Tienda.telefono)
					$('table#tablaDatos td[rel="tiendaTelefono"]').html(tienda.Tienda.telefono);
				
			}
		});
	}
	return false;
}

$(document).ready(function() {
	$('ul.nav li.dropdown').hover(function() {
		$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
	}, function() {
		$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
	});

	$(document).on('change', '#UsuarioTrabajeForm .familiar', function() {
		var valor = $(this).val();
		if(valor == 'si') {
			$('#UsuarioTrabajeForm .sitengo').removeClass('hidden');
			$('#UsuarioTrabajeForm .sitengo').addClass('show');
		} else {
			$('#UsuarioTrabajeForm .sitengo').removeClass('show');
			$('#UsuarioTrabajeForm .sitengo').addClass('hidden');
		}
	});

	$('#modalAlerta').on('hidden.bs.modal', function (e) {
		if ($('#modalAlerta .alert[rel="texto"]').hasClass('alert-success')) {
			if ($('table#devoluciones').length) {
				location.reload();
			}
		}
	});

	// -------------------- SUBMIT DE FORMULARIOS
	$(document).on("keydown", 'input, select, option, optgroup, button', function(e) {
		if ( e.keyCode === 13 ) {
			$(this).parents('form').submit();
		}
	});

	$(document).on("focus", 'input, select, option, optgroup, button', function() {
		$(this).next('.error-message').fadeOut(300, function() {
			$(this).remove();
		});
	});

	$(document).on("keydown", '.submit', function(e) {
		if ( e.keyCode === 13 ) {
			$(this).parents('form').first().submit();
		}
		return false;
	});

	$(document).on("click", '.submit', function(e) {
		e.preventDefault();
		$(this).parents('form').first().submit();
	});

	$(".logincarro").click(function() {
		$('#UsuarioLoginForm').submit();
	});

	////------------------------ BUSCADOR
	$('#navHeader input[rel="buscar-input"]').focus(function() {
		$('#navHeader #ProductoBuscarForm ul[rel="autocomplete"]').slideDown(500);
	}).blur(function() {
		$('#navHeader #ProductoBuscarForm ul[rel="autocomplete"]').slideUp(500);
	});




	var requestBuscar;
	$('#navHeader input[rel="buscar-input"]').keyup(function(e) {
		if (e.keyCode == 13) {
			return false;
		}
		var busca = $(this).val(),
			target = $('#navHeader #ProductoBuscarForm ul[rel="autocomplete"]');

		if (! target.length)
			return false;

		target.html('');
		if (requestBuscar)
			requestBuscar.abort();

		if (busca.length <= 1)
			return false;

		requestBuscar = $.ajax({
			async	: true,
			dataType: "json",
			type	: 'POST',
			url		: webroot + 'productos/ajax_busqueda',
			data	: { busca : busca },
			success	: function( respuesta ) {
				if (respuesta && $.isArray(respuesta)) {
					target.html('');
					$.each(respuesta,function(index, producto) {
						var elemento = '<li><a href="'+webroot+'detalle/'+producto.Producto.slug+'"><img src="'+producto.Producto.foto+'" alt="" /><h5 class="small">'+producto.Producto.nombre+'<br><small>'+producto.Producto.codigo_completo+'</small></h5></a></li>';
						target.append(elemento);
					});
				} else {
					target.html('<li><div class="col-md-12"><p><small>No hay resultados para esta busqueda</small></p></div></li>');
				}
			},
			error: function() {
				target.html('<li><div class="col-md-12"><p><small>No hay resultados para esta busqueda</small></p></div></li>');
			}
		});
		return false;
	});

	$('#navMobile input[rel="buscar-input2"]').focus(function() {
		$('#navMobile #ProductoBuscarForm ul[rel="autocomplete2"]').slideDown(500);
	}).blur(function() {
	$('#navMobile #ProductoBuscarForm ul[rel="autocomplete2"]').slideUp(500);
	});

	$('#navMobile input[rel="buscar-input2"]').keyup(function(e) {
			console.log('entro');
		if (e.keyCode == 13) {
			return false;
		}
		var busca = $(this).val(),
			target = $('#navMobile #ProductoBuscarForm ul[rel="autocomplete2"]');

		if (! target.length)
			return false;

		target.html('');
		if (requestBuscar)
			requestBuscar.abort();

		if (busca.length <= 1)
			return false;

		requestBuscar = $.ajax({
			async	: true,
			dataType: "json",
			type	: 'POST',
			url		: webroot + 'productos/ajax_busqueda',
			data	: { busca : busca },
			success	: function( respuesta ) {
				if (respuesta && $.isArray(respuesta)) {
					target.html('');
					$.each(respuesta,function(index, producto) {
						var elemento = '<li><a href="'+webroot+'detalle/'+producto.Producto.slug+'"><img src="'+producto.Producto.foto+'" alt="" /><h5 class="small">'+producto.Producto.nombre+'<br><small>'+producto.Producto.codigo_completo+'</small></h5></a></li>';
						console.log(elemento)

						target.append(elemento);
					});
				} else {
					target.html('<li><div class="col-md-12"><p><small>No hay resultados para esta busqueda</small></p></div></li>');
				}
			},
			error: function() {
				target.html('<li><div class="col-md-12"><p><small>No hay resultados para esta busqueda</small></p></div></li>');
			}
		});
		return false;
	});

	$('.ver-detalle').click(function(e) {
		e.preventDefault();
		var boton = $(this),
			id = $(this).data('id');
		
		if ($('.tab-detal' + id).is(':visible')) {
			$('.tab-detal' + id).slideUp('slow', function() {
				boton.find('span').html('Ver detalle');
			});
		} else {
			$('.tabla-detal').slideUp('slow');
			$('.ver-detalle span').html('Ver detalle');
			$('.tab-detal' + id).slideDown('slow', function() {
				boton.find('span').html('Ocultar detalle');
			});
		}
	});

	$(document).on('change',"#DireccionRegionId",function() {
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

	$('#DireccionMisDireccionesForm .boton a.azul.submit').click(function() {
		if ( $('#DireccionRegionId').val() == 0 || $('#DireccionComunaId').val() == 0) {
			alert('Debe seleccionar region y comuna');
			return false;
		}
		return true;
	});

	// ====== PERFIL
	$(document).on('click','a[rel="btnGuardarDireccion"]',function(e) {
		e.preventDefault();
		var boton = $(this),
			formulario = boton.parents('form'),
			continuar = true;
		formulario.find('.requerido').each(function(index,elemento) {
			if (! $(elemento).val()) {
				continuar = false;
				$(elemento).parents('.form-group').addClass('has-error');
			}
		});

		if (! continuar)
			return false;
	
		formulario.submit();
		return false;
	});

	$(document).on('focus','.form-group.has-error .requerido',function() {
		$(this).parents('.form-group.has-error').removeClass('has-error');
	});

	$(document).on('click','a[rel="direccionVer"]',function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		if (! id)
			return false;

		$.ajax({
			async : true,
			dataType: 'json',
			type : 'POST',
			url : webroot+'direcciones/ajax_cargar',
			data : {
				id : id
			},
			success: function(respuesta) {
				// datos modal
				$('#modalDireccion h4[rel="titulo"]').html('<i class="fa fa-eye"></i> Ver dirección');
				$('#modalDireccion a[rel="btnGuardarDireccion"]').addClass('hidden');
				$('#modalDireccion .form-control').attr('disabled',true).val('');
	
				// llenar select de comunas filtradas por la region seleccionada
				$('#modalDireccion #DireccionComunaId').html('');
				$.each(respuesta.Comunas,function(index, comuna) {
					$('#modalDireccion #DireccionComunaId').append('<option value="'+comuna.Comuna.id+'">'+comuna.Comuna.nombre+'</option>');
				});
				// cargar valores
				if (respuesta.Direccion.id)
					$('#modalDireccion #DireccionId').val(respuesta.Direccion.id);
				if (respuesta.Direccion.calle)
					$('#modalDireccion #DireccionCalle').val(respuesta.Direccion.calle);
				if (respuesta.Direccion.numero)
					$('#modalDireccion #DireccionNumero').val(respuesta.Direccion.numero);
				if (respuesta.Direccion.depto)
					$('#modalDireccion #DireccionDepto').val(respuesta.Direccion.depto);
				if (respuesta.Direccion.region_id)
					$('#modalDireccion #DireccionRegionId').val(respuesta.Direccion.region_id);
				if (respuesta.Direccion.comuna_id)
					$('#modalDireccion #DireccionComunaId').val(respuesta.Direccion.comuna_id);
				if (respuesta.Direccion.codigo_postal)
					$('#modalDireccion #DireccionCodigoPostal').val(respuesta.Direccion.codigo_postal);
				if (respuesta.Direccion.telefono)
					$('#modalDireccion #DireccionTelefono').val(respuesta.Direccion.telefono);
				if (respuesta.Direccion.celular)
					$('#modalDireccion #DireccionCelular').val(respuesta.Direccion.celular);
				if (respuesta.Direccion.nombre)
					$('#modalDireccion #DireccionNombre').val(respuesta.Direccion.nombre);
	
				// mostrar modal
				$('#modalDireccion').modal({
					backdrop: 'static',
					show: true
				});
			}
		});
		return false;
	});

	$(document).on('click','a[rel="direccionEditar"]',function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		if (! id)
			return false;

		$.ajax({
			async : true,
			dataType: 'json',
			type : 'POST',
			url : webroot+'direcciones/ajax_cargar',
			data : {
				id : id
			},
			success: function(respuesta) {
				if (! respuesta.Direccion.id)
					return false;

				// datos modal
				$('#modalDireccion h4[rel="titulo"]').html('<i class="fa fa-edit"></i> Editar dirección');
				$('#modalDireccion a[rel="btnGuardarDireccion"]').removeClass('hidden');
				$('#modalDireccion .form-control').attr('disabled',true).val('');

				// llenar select de comunas filtradas por la region seleccionada
				$('#modalDireccion #DireccionComunaId').html('');
				$.each(respuesta.Comunas,function(index, comuna) {
					$('#modalDireccion #DireccionComunaId').append('<option value="'+comuna.Comuna.id+'">'+comuna.Comuna.nombre+'</option>');
				});
				$('#modalDireccion .form-control').removeAttr('disabled');
				// cargar valores
				if (respuesta.Direccion.id)
					$('#modalDireccion #DireccionId').val(respuesta.Direccion.id);
				if (respuesta.Direccion.calle)
					$('#modalDireccion #DireccionCalle').val(respuesta.Direccion.calle);
				if (respuesta.Direccion.numero)
					$('#modalDireccion #DireccionNumero').val(respuesta.Direccion.numero);
				if (respuesta.Direccion.depto)
					$('#modalDireccion #DireccionDepto').val(respuesta.Direccion.depto);
				if (respuesta.Direccion.region_id)
					$('#modalDireccion #DireccionRegionId').val(respuesta.Direccion.region_id);
				if (respuesta.Direccion.comuna_id)
					$('#modalDireccion #DireccionComunaId').val(respuesta.Direccion.comuna_id);
				if (respuesta.Direccion.codigo_postal)
					$('#modalDireccion #DireccionCodigoPostal').val(respuesta.Direccion.codigo_postal);
				if (respuesta.Direccion.telefono)
					$('#modalDireccion #DireccionTelefono').val(respuesta.Direccion.telefono);
				if (respuesta.Direccion.celular)
					$('#modalDireccion #DireccionCelular').val(respuesta.Direccion.celular);
				if (respuesta.Direccion.nombre)
					$('#modalDireccion #DireccionNombre').val(respuesta.Direccion.nombre);

				// mostrar modal
				$('#modalDireccion').modal({
					backdrop: 'static',
					show: true
				});
				return false;
			}
		});
		return false;
	});

	$(document).on('click','a[rel="direccionBorrar"]',function(e) {
		e.preventDefault();
		var boton = $(this),
			target = $(this).parents('li.list-group-item'),
			id = $(this).data('id');
		if (! id)
			return false;
		if (! confirm('¿Esta seguro que desea eliminar esta direccion?'))
			return false;
	
		$.ajax({
			async : true,
			type : 'POST',
			url : webroot+'direcciones/ajax_delete',
			data : {
				id : id
			},
			success: function(respuesta) {
				if (respuesta == 'OK') {
					target.slideUp(300,function() {
						target.remove();
					});
				}
			}
		});
		return false;
	});

	$(document).on('click','a[rel="btnCambiarClave"]',function(e) {
		e.preventDefault();
		var boton = $(this),
			formulario = boton.parents('form'),
			continuar = true;
		formulario.find('.requerido').each(function(index,elemento) {
			if (! $(elemento).val()) {
				continuar = false;
				$(elemento).parents('.form-group').addClass('has-error');
			}
		});

		if (! continuar)
			return false;

		formulario.submit();
		return false;
	});
	// -------------------------- VISTA VIEW -----------------------------
	$('#galeriaProducto a.thumbnail').click(function(e) {
		e.preventDefault();
		var	elemento = $(this),
			imagen = elemento.data('img'),
			zoom = elemento.data('zoom'),
			target = $('#imagenZoom');
		if (! imagen)
			return false;
		$('#galeriaProducto a.thumbnail').removeClass('active');
		elemento.addClass('active');
		target.attr({ 'src':imagen, 'data-zoom-image':zoom }).parent().attr('href',zoom);
		var ez = $('#imagenZoom').data('elevateZoom');
		ez.swaptheimage(imagen, zoom);
		return false;
	});

	$(document).on('click','form#ProductoCarroForm .tallas',function(e) {
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
		$('[rel="mostrarCantidad"]').html(cantidad); // texto cantidad
		$('[rel="selectcantnum"]').html("$ " + formatMoneda(cantidad * precio));// cargar precio a pagar en la vista (formateado)
		return false;
	});

	//PRECIO CANTIDAD
	$("form#ProductoCarroForm #numeros").change(function() {
		var elemento = $(this),
			cantidad = elemento.val(),
			precio = elemento.data('precio'),
			talla  = $('form#ProductoCarroForm .tallas.active').data('talla');

		$('[rel="mostrarCantidad"]').html(cantidad); // texto cantidad
		$('[rel="selectcantnum"]').html("$ " + formatMoneda(cantidad * precio));// cargar precio a pagar en la vista (formateado)
		return false;
	});

	//ENVIAR FORMULARIO CARRO DE COMPRAS
	$('form#ProductoCarroForm a[rel="agregar-al-carro"]').click(function(e) {
		e.preventDefault();
		var	id = $(this).data('id'),
			tipo = $(this).data('tipo'),
			cantidad = $('select[rel="seleccion-cantidad"]:visible').val(),
			imagen = $('#img-carro'),
			target = $('#texto_carro'),
			boton = $(this);
			sku = $('a.tallas.active:visible').data('sku');
			precio =  $('a.tallas.active:visible').data('precio');
			nombre =  $('a.tallas.active:visible').data('nombre');
			color =  $('a.tallas.active:visible').data('color');
			categoria =  $('a.tallas.active:visible').data('categoria');

		talla = 1;
		cantidad = 1;
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
		dataLayer.push({
					 'event': 'addToCart',
					 'ecommerce': {'currencyCode': 'CLP',
					 'add': {'products': [{
					 'name': nombre,
					 'id': sku,
					 'price': precio,
					 'brand': 'Skechers',
					 'category': categoria,
					 'variant': color,
					 'quantity': cantidad}]
					 }}});

		$.ajax({
			async	: true,
			dataType: 'json',
			type		: 'POST',
			url		: webroot+'productos/ajax_agregar_al_carro',
			data		: {
				id : id,
				cantidad : cantidad
			},
			success: function( respuesta ) {
				if ( respuesta.codigo == 'AGREGO_OK' ) {


					target.html('<u><i class="fa fa-shopping-cart amarillo_tb fa-1x mr-2" style="font-size: 15px;"> Tu carro tiene '+respuesta.productos+' productos</i></u>');
					$('form#ProductoCarroForm .tallas').removeClass('active');
					$('#ProductoCarroForm select[rel="seleccion-cantidad"]').html('<option value="0">0</option>');
				

					//efecto agregar al carro
					if (tipo == 'mobile') {
						boton.data('url');
						alert('Producto agregado al carro.');
						if (boton.data('url').length) {
							location.href = boton.data('url');
						}
					} else {
						$('html').animate({ scrollTop: 0 }, "slow", function() {
							imagen.effect("transfer", { to: target }, 1000);
						});
					}
				} else {
					if ( respuesta.codigo == 'AGREGO_ERROR3' )
						alert('No se pudo agregar el producto a su carro de compras, el maximo del carro es de 10 productos');
					else
						alert('No se pudo agregar el producto a su carro de compras, porfavor intentelo nuevamente.');
				}
			}
		});
		return false;
	});

	$('#ProductosCarro a[rel="eliminarProducto"]').click(function(){
		var boton = $(this);
		var sku = boton.data('sku');
		var precio = boton.data('precio');
		var nombre = boton.data('nombre');
		var color = boton.data('color');
		var categoria = boton.data('categoria');

		dataLayer.push({
			'event': 'removeFromCart',
			'ecommerce': {
				'currencyCode': 'CLP',
				'add': {
					'products': [{
						'name': nombre,
						'id': sku,
						'price': precio,
						'brand': 'Skechers',
						'category': categoria,
						'variant': color,
						'quantity': 1
					}]
				}
			}
		});
		return true;
	});

	//FANCYBOX
	if ($(".fancybox").length) {
		$(".fancybox").fancybox({
			openEffect: "none",
			closeEffect: "none"
		});
	}

	// ====== CARRO -> DESPACHO
	if ($('#ProductoConfirmarForm #ProductoDireccionId').length && $('#ProductoConfirmarForm #ProductoDireccionId').val()) {
		datosDireccion($('#ProductoConfirmarForm #ProductoDireccionId').val());
		$('#btnContinuar').removeClass('disabled');
	}

	if ($('#ProductoConfirmarForm #tienda_id').length && $('#ProductoConfirmarForm #tienda_id').val()) {
		datosTienda($('#ProductoConfirmarForm #ProductoDireccionId').val());
		$('#btnContinuar').removeClass('disabled');
	}

	$(document).on('change','#tienda_id',function() {
		var direccionId = $(this).val();
		$('#btnContinuar').addClass('disabled');
		if (! direccionId)
			return false;

		datosTienda(direccionId);
		$('#btnContinuar').removeClass('disabled');
		return false;
	});

		$(document).on('change','select[rel="direccionDespacho"]',function() {
		var direccionId = $(this).val();
		$('#btnContinuar').addClass('disabled');
		if (! direccionId)
			return false;
		datosDireccion(direccionId);
		$('#btnContinuar').removeClass('disabled');
		return false;
	});

	$(document).on('click','#CarroDespacho #btnContinuar',function(e) {
		e.preventDefault();
		if ($(this).hasClass('disabled'))
			return false;
		if (! $('#ProductoConfirmarForm #ProductoDireccionId').val() && ! $('#ProductoConfirmarForm #tienda_id').val())
			return false;
		$('#ProductoConfirmarForm').submit();
		return false;
	});

	//MAPA TIENDAS
	$(document).on('click','a[rel="marcadorMapa"]',function(e) {
		var nombre = $(this).data('nombre'),
			direccion = $(this).data('direccion'),
			latitud = $(this).data('latitud'),
			longitud = $(this).data('longitud'),
			mapa = '<iframe width="500" height="480" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?z=15&t=m&q='+nombre+'&ll='+latitud+','+longitud+'&amp;output=embed"></iframe>';
		$('#modalMaps h4[rel="titulo"]').html(nombre);
		$('#modalMaps div[rel="info"]').html(direccion);
		$('#modalMaps #map-canvas').html(mapa);
	});

	// -------------------- LOGOUT FACEBOOK
	$(document).on('click', "#logout", function() {
		FB.getLoginStatus(function(response) {
			if ( response.status == 'connected' ) {
				FB.logout(function() {
					location.href = webroot + 'usuarios/logout';
				});
			} else {
				location.href = webroot + 'usuarios/logout';
			}
		});
	});

	/*****	REDES SOCIALES	*****/
	$(document).on('click','a[rel="compartirProductoFB"]',function(e) {
		e.preventDefault();
		var	nombre = $(this).data('nombre'),
			imagen = $(this).data('imagen'),
			ruta = $(this).data('url');
		FB.ui({
			method		: 'feed',
			name		: '#SkechersChile',
			link		: ruta,
			picture		: imagen,
			caption		: '',
			description	: nombre
		});
		return false;
	});

	$(document).on('click','a[rel="compartirProductoTW"]',function(e) {
		e.preventDefault();
		var	nombre = $(this).data('nombre'),
			ruta = $(this).data('url');
		window.open('https://twitter.com/share?text=' + encodeURIComponent('#SkechersChile - '+nombre) + '&url='+encodeURIComponent(ruta));
		return false;
	});

	//=== DEVOLUCIONES
	// devoluciones - cambio de producto
	$(document).on('click','table#devoluciones a[rel="btnCambioProducto"]',function(e) {
		e.preventDefault();
		var id = $(this).data('id'),
			compra = $(this).data('compra'),
			producto = $(this).data('producto'),
			nombre = $(this).data('nombre'),
			codigo = $(this).data('codigo'),
			color = $(this).data('color'),
			talla = $(this).data('talla'),
			valor = $(this).data('valor'),
			target = $('#modalCambioProducto');
		if (! id)
			return false;
		if (! compra)
			return false;
		if (! producto)
			return false;
		if (! confirm('Se iniciara el proceso para la devolucion del producto. ¿Desea continuar?'))
			return false;

		target.find('#CompraDevolucionForm')[0].reset();

		//DATOS
		target.find('th[rel="nombre"]').html(nombre);
		target.find('td[rel="codigo"]').html(codigo);
		target.find('td[rel="color"]').html(color);
		target.find('td[rel="talla"]').html(talla);
		target.find('td[rel="valor"]').html('$ '+formatMoneda(valor));
		//FORMULARIO
		target.find('td[rel="tallas"] input#CompraId').val(id);
		target.find('td[rel="tallas"] input#CompraCompra').val(compra);
		target.find('td[rel="tallas"] input#CompraProducto').val(producto);

		// mostrar modal
		target.modal({
			backdrop: 'static',
			show: true
		});
		return false;
	});

	$('#UsuarioLoginForm a[rel="recuperarClave"]').click(function(e) {
		e.preventDefault();
		var url = webroot+'usuarios/recuperar',
			email = $(this).parents('form#UsuarioLoginForm').find('input#UsuarioEmail');
		if (email.length && email.val()) {
			url+='/'+email.val();
		}
		location.href=url;
	});
});
//]]>

