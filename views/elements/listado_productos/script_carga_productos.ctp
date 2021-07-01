<script>
$(document).ready(function(){
	var cargar = true;
	var limite = 20;
	// funcionalidad al evento scroll de window
	$(window).scroll(function() {
		// Comprobamos si estamos en la parte inferior de la página.
		if ( $(window).scrollTop() >= ($(document).height() - $(window).height())-$('.footer').height() ) {
			if (cargar) {
				cargar = false;
				$('.skechers .catalogo li.hide').each(function(index,elemento) {
					if (index < limite) {
						$(elemento).removeClass('hide').fadeIn(500,function() {
							$(elemento).find('img').attr('src',$(elemento).find('img').data('img'));
							if (index == (limite-1)) {
								setTimeout(function() {
									cargar = true;
								},700);
							}
						});
					}
				});
			}
		}
	});
});
</script>