<?
$listViews = array('catalogo','tallas','color');
$new_menu = array(
-3 => array(
		'activo'		=> true,
		'titulo'		=> '<i class="fa fa-question-circle fa-2" aria-hidden="true"></i>&nbsp;Preguntas Frecuentes',
		'url'			=> $this->Html->url(array('controller' => 'pages', 'action' => 'display','faq')),
		'class'			=> 'hidden-xs hidden-sm hidden-md hidden-lg nuevo_item  yamm-fw',
	),
	-2 => array(
		'activo'		=> true,
		'url'			=> 'https://s3.amazonaws.com/andain-sckechers/politicas_skechers.pdf',
		'titulo'		=> '<i class="fa fa-file-text fa-6" aria-hidden="true"></i>&nbspPolíticas de despacho ',
		'class'			=> 'hidden-xs hidden-sm hidden-md hidden-lg nuevo_item yamm-fw',
	),
	-1 => array(
		'activo'		=> true,
		'url'			=> $this->Html->url(array('controller' => 'compras', 'action' => 'estado_despacho')),
		'titulo'		=> '<i class="fa fa-truck" aria-hidden="true"></i>&nbsp;Estado de despacho',
		'class'			=> 'hidden-xs hidden-sm hidden-md hidden-lg nuevo_item yamm-fw',
	),
	0 => array(
		'activo'		=> true,
		'url'			=> $this->Html->url(array('controller' => 'compras', 'action' => 'detalle_boleta')),
		'titulo'		=> '<i class="fa fa-ticket" aria-hidden="true"></i>Mi boleta',
		'class'			=> 'hidden-xs hidden-sm hidden-md hidden-lg nuevo_item yamm-fw',
	),
	1 => array(
		'activo'		=> true,
		'titulo'		=> 'WOMEN',
		'subtitulo'		=> 'MUJERES',
		'class'			=> 'dropdown yamm-fw',
		'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', 'mujer')),
		'tallas'		=> true,
		'colores'		=> true,
		'styles'		=> true
	),
	2 => array(
		'activo'		=> true,
		'titulo'		=> 'MEN',
		'subtitulo'		=> 'HOMBRES',
		'class'			=> 'dropdown yamm-fw',
		'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', 'hombre')),
		'tallas'		=> true,
		'colores'		=> true,
		'styles'		=> true
	),
	3 => array(
		'activo'		=> true,
		'titulo'		=> 'BOYS',
		'subtitulo'		=> 'NIÑOS',
		'class'			=> 'dropdown yamm-fw',
		'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', 'nino')),
		'tallas'		=> true,
		'colores'		=> true,
		'styles'		=> true
	),
	4 => array(
		'activo'		=> true,
		'titulo'		=> 'GIRLS',
		'subtitulo'		=> 'NIÑAS',
		'class'			=> 'dropdown yamm-fw',
		'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo',  'nina')),
		'tallas'		=> true,
		'colores'		=> true,
		'styles'		=> true
	),
	5 => array(
		'activo'		=> false,
		'titulo'		=> 'PERFORMANCE',
		'subtitulo'		=> 'PERFORMANCE',
		'class'			=> '',
		'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo','performance')),
		'tallas'		=> false,
		'colores'		=> false
	),
	// 6 => array(
	// 	'activo'		=> true,
	// 	'titulo'		=> 'ESCOLARES',
	// 	'subtitulo'		=> 'ESCOLARES',
	// 	'class'			=> 'escolar',
	// 	'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'escolar')),
	// 	'tallas'		=> false,
	// 	'colores'		=> false
	// ),
	// 7 => array(
	// 	'activo'		=> true,
	// 	'titulo'		=> 'OUTLET',
	// 	'subtitulo'		=> 'OUTLET',
	// 	'class'			=> '',
	// 	'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'outlet')),
	// 	'tallas'		=> false,
	// 	'colores'		=> false,
	// 	'genero'		=> true
	// ),
	8 => array(
		'activo'		=> true,
		'titulo'		=> 'ROPA',
		'subtitulo'		=> 'ROPA',
		'class'			=> '',
		'url'			=> $this->Html->url(array('controller' => 'productos', 'action' => 'ropa')),
		'tallas'		=> false,
		'colores'		=> false
	),
	9 => array(
		'activo'		=> true,
		'titulo'		=> 'TIENDAS',
		'subtitulo'		=> 'TIENDAS',
		'class'			=> '',
		'url'			=> $this->Html->url(array('controller' => 'tiendas', 'action' => 'index')),
		'tallas'		=> false,
		'colores'		=> false
	),
);

foreach ($new_menu as $x => $tab_menu)
{
	if (! $tab_menu['activo'])
		continue;
	//CURRENT
	if ($this->params['controller'] == 'productos' && in_array($this->params['action'],$listViews) && isset($this->params['pass'][0]) && isset($menu_categorias[$x]) && $this->params['pass'][0] == $menu_categorias[$x])
	{
		if ($new_menu[$x]['class'])
			$new_menu[$x]['class'].=' ';
		$new_menu[$x]['class'].='active';
	}
	elseif ($this->params['controller'] == 'productos' && $this->params['action'] == 'view')
	{
		if (isset($producto['Producto']['outlet']) && $producto['Producto']['outlet'] == 1)
		{
			if ($x == 7)
			{
				if ($new_menu[$x]['class'])
					$new_menu[$x]['class'].=' ';
				$new_menu[$x]['class'].='active';
			}
		}
		elseif (isset($producto['Producto']['categoria_id']) && $producto['Producto']['categoria_id'] == $x)
		{
			if ($new_menu[$x]['class'])
				$new_menu[$x]['class'].=' ';
			$new_menu[$x]['class'].='active';
		}
	}

	//STYLES
	if (isset($tab_menu['styles']) && $tab_menu['styles'])
	{
		$new_menu[$x]['styles'] = array();
		if (isset($menu_estilos[$x]) && $menu_estilos[$x])
		{
			foreach ($menu_estilos[$x] as $style)
			{
				$new_menu[$x]['styles'][] = array(
					'name' => $style['Estilo']['nombre'],
					'link' => $this->Html->url(
						array(
							'controller' => 'productos',
							'action' => 'catalogo',
							$menu_categorias[$x],
							'?' => array('style' => $style['Estilo']['alias'])
						)
					)
				);
			}
		}
	}

	if (isset($tab_menu['tallas']) && $tab_menu['tallas'])
	{
		if (isset($menus_tallas[$x]) && $menus_tallas[$x])
		{
			$new_menu[$x]['tallas'] = array();
			foreach ($menus_tallas[$x] as $menu_talla)
			{
				$_talla = array(
					'name' => $menu_talla['Menu']['talla'],
					'link' => '#'
				);
				if (isset($menu_talla['Menu']['estado']) && $menu_talla['Menu']['estado'])
				{
					$_talla['link'] = $this->Html->url(
						array(
							'controller'=>'productos',
							'action'=>'catalogo',
							$menu_categorias[$x],
							'?' => array(
								'talla' => $menu_talla['Menu']['talla']
							)
						)
					);
				}
				$new_menu[$x]['tallas'][$menu_talla['Menu']['talla']] = $_talla;
			}
		}
		else
		{
			$new_menu[$x]['tallas'] = false;
		}
	}

	if (isset($tab_menu['colores']) && $tab_menu['colores'])
	{
		if (isset($menu_colores[$x]) && $menu_colores[$x])
		{
			$new_menu[$x]['colores'] = array();
			foreach ($menu_colores[$x] as $menu_color)
			{
				$_color = array(
					'name' => $menu_color['Primario']['nombre'],
					'imagen' => $this->Shapeups->imagen('Primario/'.$menu_color['Primario']['id'].'/cubo_'.$menu_color['Primario']['imagen']),
					'link' => $this->Html->url(array('controller'=>'productos','action'=>'catalogo',$menu_categorias[$x],'?' => array('color' => $menu_color['Primario']['slug'])))
				);
				$new_menu[$x]['colores'][$menu_color['Primario']['id']] = $_color;
			}
		}
		else
		{
			$new_menu[$x]['colores'] = false;
		}
	}
}

if ($this->params['controller'] == 'productos' && $this->params['action'] == 'performance')
{
	if ($new_menu[5]['class'])
		$new_menu[5]['class'].=' ';
	$new_menu[5]['class'].='active';
}
if ($this->params['controller'] == 'productos' && $this->params['action'] == 'escolar')
{
	if ($new_menu[6]['class'])
		$new_menu[6]['class'].=' ';
	$new_menu[6]['class'].='active';
}
if ($this->params['controller'] == 'productos' && $this->params['action'] == 'outlet')
{
	if ($new_menu[7]['class'])
		$new_menu[7]['class'].=' ';
	$new_menu[7]['class'].='active';
}
if ($this->params['controller'] == 'tiendas' && $this->params['action'] == 'index')
{
	if ($new_menu[9]['class'])
		$new_menu[9]['class'].=' ';
	$new_menu[9]['class'].='active';
}
$linea = '';
?>
<style type="text/css" media="all">
#navHeader .navbar-skechers .btn-tallas {
	color: #fff;
}
#navHeader .navbar-skechers .btn-tallas.disabled {
	opacity: 0.3;
}
/*=== ESCOLAR === */
#navHeader > ul.navbar-skechers > li.escolar.active > a, #navHeader > ul.navbar-skechers > li.escolar > a:hover {
	border-bottom: #FFDF00 5px solid;;
}
<? if (count($new_menu) >= 8) : ?>
/*=== AJUSTE ===*/
#navHeader > ul.navbar-skechers > li > a {
	font-size: 14px;
	letter-spacing: -1px;
}
<? endif; ?>
</style>
<nav id="navHeader" class="navbar navbar-default yamm hidden-xs hidden-sm" role="navigation">
	<ul class="nav navbar-nav navbar-skechers">
		<? foreach ($new_menu as $tab_menu) : ?>
			<? if ($tab_menu['activo']) : ?>
				<? if ($tab_menu['tallas'] || $tab_menu['colores']) : ?>
				<li class="<?= $tab_menu['class']; ?>">
					<?= $linea; ?>
					<a data-toggle="dropdown" class="dropdown-toggle" href="#" onclick="document.location.href = '<?= $tab_menu['url']; ?>'"><?= $tab_menu['titulo']; ?></a>
					<ul class="dropdown-menu dropdown-menutop">
						<li class="grid-demo">
							<div class="contenido-menutop">
								<? if (isset($tab_menu['styles']) && $tab_menu['styles']) : ?>
									<div class="col-md-4">
										<h5 style="font-weight: bold;">BUSCAR POR ESTILO:</h5>
										<? if (count($tab_menu['styles']) >= 8) : ?>
										<div class="row">
											<div class="col-md-6">
												<div class="row">
													<? $cont = 0; ?>
													<? foreach ($tab_menu['styles'] as $style) : ?>
														<? if (++$cont <= (count($tab_menu['styles']) / 2)) : ?>
														<div class="col-xs-12">
															<a href="<?= $style['link']; ?>" class="list-color"><?= $style['name']; ?></a>
														</div>
														<? endif; ?>
													<? endforeach; ?>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<? $cont = 0; ?>
													<? foreach ($tab_menu['styles'] as $style) : ?>
														<? if (++$cont > (count($tab_menu['styles']) / 2)) : ?>
														<div class="col-xs-12">
															<a href="<?= $style['link']; ?>" class="list-color"><?= $style['name']; ?></a>
														</div>
														<? endif; ?>
													<? endforeach; ?>
												</div>
											</div>
										</div>
										<? else : ?>
										<div class="row">
											<? foreach ($tab_menu['styles'] as $style) : ?>
												<div class="col-xs-12">
													<a href="<?= $style['link']; ?>" class="list-color"><?= $style['name']; ?></a>
												</div>
											<? endforeach; ?>
										</div>
										<? endif; ?>

									</div>
								<? endif; ?>
								<div class="col-md-4">
									<h5 style="font-weight: bold;">BUSCAR POR TALLA:</h5>
									<div class="row">
										<? if (isset($tab_menu['tallas']) && $tab_menu['tallas'] && is_array($tab_menu['tallas'])) : ?>
											<? foreach ($tab_menu['tallas'] as $_talla) : ?>
												<div class="col-md-3 col-xs-4">
													<a href="<?= $_talla['link']; ?>" class="btn btn-tallas btn-block text-center<?= ($_talla['link']=='#')? ' disabled':''; ?>"><?= $_talla['name']; ?></a>
												</div>
											<? endforeach; ?>
										<? endif; ?>
									</div>
								</div>
								<div class="col-md-4">
									<h5 style="font-weight: bold;">BUSCAR POR COLOR:</h5>
									<div class="row">
										<? if (isset($tab_menu['colores']) && $tab_menu['colores'] && is_array($tab_menu['colores'])) : ?>
											<? foreach ($tab_menu['colores'] as $_color) : ?>
												<div class="col-md-6 col-xs-6">
													<a href="<?= $_color['link']; ?>" class="list-color">
														<span class="icon-color" style="background-image:url(<?= $_color['imagen']; ?>);background-position:-1px -1px;width:13px;height:13px;"></span>
														<?= $_color['name']; ?>
													</a>
												</div>
											<? endforeach; ?>
										<? endif; ?>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</li>
				<? else : ?>
				<li class="<?= $tab_menu['class']; ?>">
					<?= $linea; ?>
					<a href="<?= $tab_menu['url']; ?>"><?= $tab_menu['titulo']; ?></a>
				</li>
				<? endif; ?>
				<? if($tab_menu['titulo'] == 'GIRLS'): ?>
					<li class="cybermonday2016">
						<a href="#">
							<img src="<?= $this->Html->url('https://s3.amazonaws.com/andain-sckechers/img/cybermonday2016/boton-azul.png'); ?>" alt="">
						</a>
					</li>
				<? endif; ?>
			<? endif; ?>
		<? endforeach; ?>
	</ul>
	<?= $this->Form->create('Producto',
							array('action' => 'buscar',
								  'autocomplete' => 'off',
								  'class' => 'navbar-form navbar-right nomargin nopadding form-buscar',
								  'role' => 'form',
								  'inputDefaults' =>
								  array('class' => false,
										'div' => false,
										'label' => false
										)
								  )
							); ?>
		<div class="input-group relative" style="width: 200px;">
			<?= $this->Form->input('buscar',
								   array('type' => 'text',
										 'class' => 'form-control input-buscar',
										 'rel' => 'buscar-input',
										 'placeholder' => 'Ej: Memory foam'
										 )
								   ); ?>
			<span class="input-group-btn">
				<button class="btn btn-primary btn-buscar" type="button" onclick="$(this).parents('form').submit();">GO</button>
			</span>
			<!-- AUTOCOMPLETE -->
			<ul class="autocomplete list-unstyled" style="display:none;" rel="autocomplete"></ul>
			<!-- /////////// -->
		</div>
	<?= $this->Form->end(); ?>
</nav>

<nav class="navbar navbar-default hidden-lg hidden-md" role="navigation">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="#">Menu</a>
	</div>
	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<? foreach ($new_menu as $tab_menu) : ?>
					<? if ($tab_menu['activo']) : ?>
						<? if ($tab_menu['tallas'] || $tab_menu['colores']) : ?>
							<li>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $tab_menu['titulo']; ?> <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<div class="row nomargin submenu-mobil">
											<a href="<?= $tab_menu['url']; ?>" class="btn btn-primary btn-block" style="font-weight: bold;">VER TODO <?= $tab_menu['titulo']; ?></a>
										</div>
									</li>
									<? if (isset($tab_menu['styles']) && $tab_menu['styles']) : ?>
										<li>
											<div class="row nomargin submenu-mobil">
												<h3 class="text-center" style="font-weight: bold;">BUSCAR POR ESTILO:</h3>
												<? foreach ($tab_menu['styles'] as $style) : ?>
													<div class="col-xs-6">
														<a href="<?= $style['link']; ?>" style="color: #fff;"><?= $style['name']; ?></a>
													</div>
												<? endforeach; ?>
											</div>
										</li>
									<? endif; ?>
									<li>
										<div class="row nomargin submenu-mobil">
											<h3 class="text-center" style="font-weight: bold;">BUSCAR POR TALLA:</h3>
											<? if (isset($tab_menu['tallas']) && $tab_menu['tallas'] && is_array($tab_menu['tallas'])) : ?>
												<? foreach ($tab_menu['tallas'] as $_talla) : ?>
													<div class="col-md-3 col-xs-4">
														<a href="<?= $_talla['link']; ?>" class="btn btn-tallas btn-block text-center"><?= $_talla['name']; ?></a>
													</div>
												<? endforeach; ?>
											<? endif; ?>
										</div>
									</li>
									<li>
										<div class="row nomargin submenu-mobil">
											<h3 class="text-center" style="font-weight: bold;">BUSCAR POR COLOR:</h3>
											<? if (isset($tab_menu['colores']) && $tab_menu['colores'] && is_array($tab_menu['colores'])) : ?>
												<? foreach ($tab_menu['colores'] as $_color) : ?>
													<div class="col-md-6 col-xs-6">
														<a href="<?= $_color['link']; ?>" class="list-color">
															<span class="icon-color" style="background-image:url(<?= $_color['imagen']; ?>);background-position:-1px -1px;width:13px;height:13px;"></span>
															<?= $_color['name']; ?>
														</a>
													</div>
												<? endforeach; ?>
											<? endif; ?>
										</div>
									</li>
								</ul>
							</li>
						<? elseif (isset($tab_menu['genero']) && $tab_menu['genero']) : ?>
							<li>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $tab_menu['titulo']; ?> <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<div class="row nomargin submenu-mobil">
											<a href="<?= $tab_menu['url']; ?>" class="btn btn-primary btn-block" style="font-weight: bold;">VER TODO <?= $tab_menu['titulo']; ?></a>
										</div>
									</li>
									<?
									$generos = array(
										'mujer' => 'Women',
										'hombre' => 'Men',
										'nino' => 'Boys',
										'nina' => 'Girls',
									);
									?>
									<li>
										<div class="row nomargin submenu-mobil">
											<h3 class="text-center" style="font-weight: bold;">BUSCAR POR GENERO:</h3>
											<? foreach ($generos as $_genderLink => $_genderName) : ?>
												<div class="col-xs-12">
													<a href="<?= $tab_menu['url'].'?categoria='.$_genderLink; ?>" style="color: #fff;"><?= $_genderName; ?></a>
												</div>
											<? endforeach; ?>
										</div>
									</li>
								</ul>
							</li>
						<? else : ?>
							<li><a href="<?= $tab_menu['url']; ?>"><?= $tab_menu['titulo']; ?></a></li>
						<? endif; ?>
					<? endif; ?>
				<? endforeach; ?>
			</li>
		</ul>
	</div><!-- /.navbar-collapse -->
</nav>
