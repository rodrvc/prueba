<?= $this->Html->css('newmenu'); ?>
<style>
	.estilos a {
		color: #FFFFFF;
	}
</style>
<?
$listViews = array('catalogo','tallas','color');
$new_menu = array(1 => 	array('activo'		=> true,
							  'titulo'		=> 'WOMEN',
							  'subtitulo'	=> 'MUJERES',
							  'class'		=> '',
							  'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', $menu_categorias[1])),
							  'tallas'		=> '',
							  'colores'		=> ''
							  ),
				  2 => 	array('activo'		=> true,
							  'titulo'		=> 'MEN',
							  'subtitulo'	=> 'HOMBRES',
							  'class'		=> '',
							  'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', $menu_categorias[2])),
							  'tallas'		=> '',
							  'colores'		=> ''
							  ),
				  3 => 	array('activo'		=> true,
							  'titulo'		=> 'BOYS',
							  'subtitulo'	=> 'NIÑOS',
							  'class'		=> '',
							  'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', $menu_categorias[3])),
							  'tallas'		=> '',
							  'colores'		=> ''
							  ),
				  4 => 	array('activo'		=> true,
							  'titulo'		=> 'GIRLS',
							  'subtitulo'	=> 'NIÑAS',
							  'class'		=> '',
							  'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', $menu_categorias[4])),
							  'tallas'		=> '',
							  'colores'		=> ''
							  ),
				  5 =>	array('activo'		=> true,
							  'titulo'		=> 'PERFORMANCE',
							  'subtitulo'	=> 'PERFORMANCE',
							  'class'		=> '',
							  'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'performance')),
							  'tallas'		=> false,
							  'colores'		=> false
							  ),
				  6 =>	array('activo'		=> true,
							  'titulo'		=> 'ESCOLAR',
							  'subtitulo'	=> 'ESCOLAR',
							  'class'		=> ' escolar',
							  'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'escolar')),
							  'tallas'		=> false,
							  'colores'		=> false
							  ),
				  7 =>	array('activo'		=> false,
							  'titulo'		=> 'OUTLET',
							  'subtitulo'	=> 'OUTLET',
							  'class'		=> ' outlet',
							  'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'outlet')),
							  'tallas'		=> false,
							  'colores'		=> false
							  ),
				  8 =>	array('activo'		=> false,
							  'titulo'		=> 'APPAREL',
							  'subtitulo'	=> 'APPAREL',
							  'class'		=> '',
							  'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'ropa')),
							  'tallas'		=> false,
							  'colores'		=> false
							  ),
				  9 =>	array('activo'		=> true,
							  'titulo'		=> 'TIENDAS',
							  'subtitulo'	=> 'TIENDAS',
							  'class'		=> '',
							  'url'			=> $this->Html->url(array('controller' => 'tiendas', 'action' => 'index')),
							  'tallas'		=> false,
							  'colores'		=> false
							  ),
				  );

for ($x = 1; $x <= 4; $x++)
{
	// CURRENT CLASS
	if ($this->params['controller'] == 'productos')
	{
		if (in_array($this->params['action'],$listViews) && $this->params['pass'][0] == $menu_categorias[$x])
			$new_menu[$x]['class'] = ' current';
		elseif ($this->params['action'] == 'view' && $producto['Producto']['categoria_id'] == $x)
			$new_menu[$x]['class'] = ' current';
	}

	// GENERAR LISTADO DE TALLAS (ACTIVAS E INACTIVAS)
	$new_menu[$x]['tallas']='<ul class="tallas">';
	if (isset($menus_tallas[$x]) && $menus_tallas[$x])
	{
		foreach ($menus_tallas[$x] as $menu_talla)
		{
			$new_menu[$x]['tallas'].='<li><a href="';
			if (isset($menu_talla['Menu']['estado']) && $menu_talla['Menu']['estado'])
				$new_menu[$x]['tallas'].=$this->Html->url(array('controller'=>'productos','action'=>'tallas',$menu_categorias[$x],$menu_talla['Menu']['talla'])).'">';
			else
				$new_menu[$x]['tallas'].='#" class="disable">';
			$new_menu[$x]['tallas'].=$menu_talla['Menu']['talla'].'</a></li>';
		}
	}
	$new_menu[$x]['tallas'].='</ul>';

	// GENERAR LISTADO DE COLORES
	$new_menu[$x]['colores']='<ul class="colores" style="float: left;">';
	if (isset($menu_colores[$x]) && $menu_colores[$x])
	{
		$contador_menu = 0;
		foreach ($menu_colores[$x] as $menu_color)
		{
			if ($contador_menu && !($contador_menu % 5))
				$new_menu[$x]['colores'].='</ul><ul class="colores" style="float: left; margin-left: 15px;">';
			$new_menu[$x]['colores'].=
				'<li>'.
					'<a href="'.$this->Html->url(array('controller'=>'productos','action'=>'color',$menu_categorias[$x],$menu_color['Primario']['slug'])).'">'.
						'<span style="background-image:url('.<?= $this->Shapeups->imagen('Primario/'.$menu_color['Primario']['id'].'/cubo_'.$menu_color['Primario']['imagen']).');background-position:-1px -1px;width:13px;height:13px;"></span>'.
						$menu_color['Primario']['nombre'].
					'</a>'.
				'</li>';
			$contador_menu++;
		}
	}
	$new_menu[$x]['colores'].='</ul>';

	//GENERAR LISTADO DE ESTILOS

}
if ($this->params['controller'] == 'productos' && $this->params['action'] == 'performance')
	$new_menu[5]['class'] = ' current';
if ($this->params['controller'] == 'productos' && $this->params['action'] == 'escolar')
	$new_menu[6]['class'] = ' escolar current';
if ($this->params['controller'] == 'productos' && $this->params['action'] == 'outlet')
	$new_menu[7]['class'] = ' outlet current';
if ($this->params['controller'] == 'tiendas' && $this->params['action'] == 'index')
	$new_menu[8]['class'] = ' current';
$cont = 0;
foreach ($new_menu as $tab_menu)
{
	if ($tab_menu['activo'])
		$cont++;
}
?>
<style>
ul.newmenu > li {
	position: static !important;
}
<? if ($cont > 7) : ?>
.newmenu li a.main {
	font-size:12px !important;
	margin-left:15px !important;
	margin-right:15px !important;
}
<? endif; ?>
</style>
<ul class="newmenu">
	<? foreach ($new_menu as $tab_menu) : ?>
		<? if ($tab_menu['activo']) : ?>
			<li>
				<a href="<?= $tab_menu['url']; ?>" class="main<?= $tab_menu['class']; ?>"><?= $tab_menu['titulo']; ?></a>
				<? if ($tab_menu['tallas'] || $tab_menu['colores']) : ?>
					<div class="dropmenu" style="position:absolute;">
						<!--<div class="caja linea">
							<h5>ESTILOS</h5>
							<ul class="estilos">
								<li><a href="#">Nombre de estilo</a></li>
								<li><a href="#">Nombre de estilo</a></li>
								<li><a href="#">Nombre de estilo</a></li>
								<li><a href="#">Nombre de estilo</a></li>
								<li><a href="#">Nombre de estilo</a></li>
						</div>-->
						<div class="caja linea">
							<h5>TALLAS DE <?= $tab_menu['subtitulo']; ?></h5>
							<ul class="tallas">
								<?= $tab_menu['tallas']; ?>
							</ul>
						</div>
						<div class="caja" style="min-width: 200px; width: auto;">
							<h5>Colores</h5>
							<ul class="colores" style="float: left;">
								<?= $tab_menu['colores']; ?>
							</ul>
						</div>
					</div>
				<? endif; ?>
			</li>
			<li><span class="diagonal"></span></li>
		<? endif; ?>
	<? endforeach; ?>
	<li class="right contenedor-buscador">
		<div class="box">
			<?= $this->Form->create('Producto', array('action' => 'buscar', 'autocomplete' => 'off', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => false))); ?>
				<?= $this->Form->input('buscar', array('type' => 'text', 'escape' => false, 'class' => 'input buscador', 'style' => 'color: rgb(153, 153, 153); width: 200px;')); ?>
				<input type="submit" class="submit" value="Go">
			<?= $this->Form->end(); ?>
			<!--DETALLE BUSQUEDA	-->
			<div class="dropdown search" style="display: none;">
				<ul></ul>
			</div>
		</div>
	</li>
</ul>
<script>
function ajustar_menu()
{
	var ancho = 0,
		limite = $('.newmenu').width()-$('.newmenu li.contenedor-buscador').width();
	$('.newmenu li a.main').each(function(index,elemento) {
		ancho = ancho+$(elemento).parent().width();
	});
	if (limite < ancho) {
		$('.newmenu li a.main').css({
			'font-size':12,
			'margin-left':15,
			'margin-right':15
		});
	}
}

$('ul.newmenu > li > div.dropmenu').hide();
$('ul.newmenu > li > .diagonal:last').hide();
$(document).ready(function()
{
	ajustar_menu();
	$('.newmenu > li').mouseover(function() {
		var dropdown = $(this).find('.dropmenu');
		if (dropdown.html()) {
			dropdown.prev('a.main').addClass('activo');
			var posicion = 	$('ul.newmenu').offset();
			var margin_top = posicion.top+$('ul.newmenu > li:first').height();
			dropdown.css({
				'top':margin_top,
				'left':posicion.left
			});
			dropdown.show();
		}
	}).mouseout(function() {
		var dropdown = $(this).find('.dropmenu');
		if (dropdown.html()) {
			dropdown.prev('a.main').removeClass('activo');
			dropdown.hide();
		}
	});
	$('#ProductoBuscar.buscador').keyup(function() {
		var busca = $('#ProductoBuscar.buscador').val();
		$('.dropdown.search').hide();
		$('.dropdown.search ul').html('');
		if ( busca.length >= 3 ) {
			$.ajax({
				async	: false,
				type	: 'POST',
				url		: webroot + 'productos/ajax_busqueda',
				data	: { busca : busca },
				success	: function( respuesta )  {
					if (respuesta) {
						$('.dropdown.search ul').html(respuesta);
						$('.dropdown.search').show();
					}
				}
			});
		}
	});

	$('.contenedor-buscador').mouseover(function() {
		var dropdown = $(this).find('.dropdown');
		if (dropdown.find('ul').html()) {
			dropdown.show();
		}
	}).mouseout(function() {
		var dropdown = $(this).find('.dropdown');
		if (dropdown.find('ul').html()) {
			dropdown.hide();
		}
	});
});
</script>
