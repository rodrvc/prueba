/*jslint browser: true, cap: false, confusion: true, continue: true, css: true, debug: false, devel: true, eqeq: true, evil: false, forin: false, fragment: true, maxerr: 3, newcap: false, plusplus: true, regexp: true, sloppy: true, sub: false, undef: false, unparam: true, vars: false, white: true */
/*globals $, document, webroot, FB */

/*!
 * <BE THE NEXT ONE - FAN LOOK SKECHERS>
 *
 * Andain, Desarrollo y Diseño Web
 * http://www.andain.cl/ <contacto@andain.cl>
 */

//<![CDATA[

//------------------------ JQUERY
$(document).ready(function()
{
	// FACEBOOK CONNECT
	$('.loginfb').on('click', function(evento)
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
					//else
					//{
					//	alert('error');
					//}
				}, { scope: 'email,publish_stream,photo_upload,user_photos' });
			}
		});
	});
	
	// -------------------- LOGOUT FACEBOOK
	$('.salir-fl').live('click', function()
	{
		FB.getLoginStatus(function(response)
		{
			if ( response.status == 'connected' )
			{
				FB.logout(function()
				{
					location.href = webroot + 'fl/logout';
				});
			}
			else
			{
				location.href = webroot + 'fl/logout';
			}
		});
	});
	
	if ( $(".look ul").html() ) {
		$(".look ul").carouFredSel({
			prev : ".left",
			next : ".right",
			scroll: 1,
			auto: false,
			circular: true,
			responsive	: true,
			items		: {
			//width		: 50,
			visible		: {
				min			: 2,
				max			: 17
				}
			}
		});
	}

	$('.header .flecha a').toggle(
		function() {
			$(this).addClass('up');
			$('.header .box').addClass('fondo');
			$(".header .box").animate({
				height: "239px"
			  }, 300 );
			$('.header .small').fadeOut('fast');
			$('.header .xl').fadeIn('fast');
		},
		function() {
			$(this).removeClass('up');
			$('.header .box').removeClass('fondo');
			$(".header .box").animate({
				height: "48px"
			  }, 300 );
			$('.header .small').delay(300).fadeIn('fast');
			$('.header .xl').fadeOut('fast');
		}
	);
	
	$('.footer .flecha a').toggle(
		function() {
			$(this).removeClass('up');
			$(this).addClass('down');
			$(".footer .box").animate({
				marginBottom: "0px"
			  }, 300 );
		},
		function() {
			$(this).removeClass('down');
			$(this).addClass('up');
			$(".footer .box").animate({
				marginBottom: "-150px"
			  }, 300 );
		}
	);

	/////////////////// CORE
	if ( $(".core ul").html() ) {
		$(".core ul").carouFredSel({
			circular: true,
			infinite: true,
			//auto:	false,
			auto: {
				fx			: "fade",
				duration	: 1500
			},
			pagination	: ".core-paginador"
		});
	}
		

	/////////////////// NAV GALERIA
	if ( $("ul.nav-galeria").html() ) {
		$("ul.nav-galeria").carouFredSel({
			circular: true,
			infinite: true,
			items:	  14,
			scroll:   1,
			auto : false,
			prev : ".nav-left",
			next : ".nav-right"
		});
	}

	$('ul.nav-galeria li a').click(function () {
		$('ul.nav-galeria li a').removeClass('current');
		$(this).addClass('current');
		return false;
	});

	/////////////////// LOOK
	$('li.imagen a').click(function(){
		var imagen_grande 	= $(this).data('img'),
			id			  	= $(this).data('id'),
			//fl_nombre		= $(this).data('fl_nombre'),
			producto 		= $(this).data('img_producto'),
			nombre 			= $(this).data('nombre_producto'),
			link 			= $(this).data('catalogo'),
			id_tilla		= $(this).data('tilla');
		if(imagen_grande)
		{
			$('.look-xl img').attr('src', webroot +'img/' +imagen_grande);
			$('.look-xl img').css({ height: '100%' });
			$('.look-xl').data('id',id);
			//$('.look-nombre span').html(fl_nombre);
			$('.look-xl').data('tilla',id_tilla);
			$('.seleccion .img img').attr('src',webroot+'img/'+producto);
			$('.seleccion .nombre').html('Qué tal las <br>'+nombre);
			$('.look-xl').data('link',link);
         
		}
    });

	$('.aqui a').click(function() {
		var id			= $('.look-xl').data('id'),
			link		= $('.look-xl').data('link'),
			id_tilla	= $('.look-xl').data('tilla');
			
		if(id != null)
		{
			$.ajax(
			{
				type: "POST",
				url: webroot + 'fl/ajax_perfil/' + id + '/'+ id_tilla,
				success: function(respuesta)
				{
					if(respuesta == 'OK')
					{
						location.href = link;
					}
				}
			});
		}
	});
	
	/*Hover de imagenes de fanlook*/
	$('.imagen a').hover(
		
		function() {
			var hover = $(this).data('hover');
			$(this).parent().css({ backgroundImage: 'url('+ webroot + 'img/' + hover+')' });
		},
		
		function () {
			var normal = $(this).data('normal');
			$(this).parent().css({ backgroundImage: 'url('+ webroot + 'img/' + normal+')' });
			
		}
	);
	
	// COMPARTIR PROYECTO
	$('.redes .compartir-fb').click(function()
	{
		var imagen = $(this).data('imagen');
		FB.ui(
		{
			method		: 'feed',
			name		: '#FanLook Skechers',
			link		: 'http://store.skechers-chile.cl/fanlook',
			picture		: imagen,
			caption		: '',
			description	: 'Ya creé mi #FanLook Skechers y estoy participando por espectaculares premios. Anímate y ven a armar el tuyo en http://store.skechers-chile.cl/fanlook!'
		});
		return false;
	});
	
	// COMPARTIR GENERAL
	$('.redes .compartir-tw').click(function()
	{
		window.open('https://twitter.com/share?text=' + encodeURIComponent('Ya creé mi #FanLook Skechers y estoy participando por espectaculares premios. Anímate y ven a armar el tuyo!') + '&url=http%3A%2F%2Fstore.skechers-chile.cl%2Ffanlook');
		return false;
	});
});

//]]>




