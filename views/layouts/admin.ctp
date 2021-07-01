<?= $html->docType('xhtml-trans'); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?= $this->Html->charset(); ?>
		<?= $this->Html->tag('title', "Administración | {$title_for_layout}"); ?>
		<?= $this->Html->meta('icon'); ?>

		<!-- HOJAS DE ESTILO -->
		<?= $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/jquery-ui-1.8.16.custom.css'); ?>
		<?= $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/admin-gestion.css'); ?>
		<?= $this->Html->css('https://s3.amazonaws.com/andain-sckechers/css/admin-andain-m4/jquery-ui.css'); ?>

		<!-- ARCHIVOS JAVASCRIPT -->
		<? if ( $mensaje = $this->Session->flash() ) : ?>
		<?= $this->Html->scriptBlock("var sessionFlash = '{$mensaje}';"); ?>
		<? endif; ?>
		<?= $this->Html->scriptBlock("var webroot = '{$this->webroot}';"); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/www.andain.cl-jquery-1.6.2.min.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/www.andain.cl-jquery.tmpl.min.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/www.andain.cl-jquery-ui.1.9m4.min.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/jquery-ui-1.8.16.custom.min.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/www.andain.cl-corner.js'); ?>
		<?= $this->Html->script('https://s3.amazonaws.com/andain-sckechers/js/www.andain.cl-funciones-admin.js'); ?>
		<?= $scripts_for_layout; ?>
	</head>
	<body>
		<!-- CONTENEDOR GENERAL -->
		<div id="admin">

			<!-- HEADER -->
			<div class="head">
				<div class="logo">
					<?= $this->Html->image('admin/logo-skechers.png'); ?>
				</div>
				<h2 class="usuario">Bienvenido(a):
					<?= $this->Html->link('<b class="icon">' . @$authUser['nombre'] . '&nbsp;</b>',
										  array('controller' => 'administradores', 'action' => 'view', @$authUser['id']),
										  array('escape' => false)); ?>
					<!--
					/ Tareas pendientes: <a href="#"><b class="tarea">02</b></a> /
					/-->
					<?= $this->Html->link('<b class="salir">Salir</b>',
										  array('controller' => 'usuarios', 'action' => 'logout'),
										  array('escape' => false)); ?>

				</h2>
			</div>

			<!-- CONTENIDOS -->
			<div class="top-sombra"></div>
			<? if ($this->params['controller'] == 'productos' && $this->action == 'admin_buscar') : ?>
			<div class="wrapper" style="background-repeat: repeat-y;">
			<? else : ?>
			<div class="wrapper">
			<? endif; ?>

				<!-- MENSAJES DE SESION -->
				<div id="session-flash"></div>

				<!-- TABS DE NAVEGACION -->
				<?
				//ACCESOS PARA PERFILES
				if (in_array($authUser['perfil'], array(0,1,2,3,4,5,6)))
				{
					switch($authUser['perfil'])
					{
						// SUPER ADMIN
						case 3 :
							$vinculos = array('Mantenedores'	=> array('Administradores'	=> 'administradores',
																		 'Colores'			=> 'colores',
																		 'Tecnologías'		=> 'tecnologias',
																		 'Minisitios'		=> 'minisitios',
																		 'Categorias'		=> 'categorias',
																		 'Colecciones'		=> 'colecciones',
																		 'Primario'			=> 'primarios',
																		 'Zonas'			=> 'zonas',
																		 'Banners' 			=> 'banners',
																		 'Comunas' 			=> 'comunas',
																		 'Trabajadores' 	=> 'trabajadores',
																		 'Postulantes' 		=> 'trabaje_postulantes',
																		 'Estilos'			=> 'estilos',
																		 'Email Blast' 		=> 'email_blasts',
																		 'Links' => 'links',
																		 'Feriados' => 'feriados'
																		 ),
											  'Productos'		=> array('Productos'		=> 'productos',
																		 'Stock'			=> 'stocks',
																		 'Bloqueos'		=> 'bloqueos',
																		 'Cargas'			=> 'archivos'),
											  'Clientes'		=> array('Usuarios'			=> 'usuarios',
																		 'Direcciones'		=> 'direcciones',
																		 'Comentarios'		=> 'comentarios'),
											  'Tiendas'			=> array('Tiendas'			=> 'tiendas'),
											  'Descuentos'		=> array('Descuentos'		=> 'descuentos',
											  							'Tickets'		=> 'tickets'),
											  'Compras'			=> array ('Compras'			=> 'compras')
											  );
							//if (in_array($authUser['id'], array(2,3,5)) )
							//{
							//	$vinculos['Btn1'] = array('Btn1'		=> 'btn1',
							//							  'Fl'		=> 'fl'
							//							  );
							//}
							break;
						// FULL PRODUCTOS
						case 2 :
							$vinculos = array('Productos'		=> array('Productos'		=> 'productos',
																		 'Stock'			=> 'stocks',
																		 'Archivos'			=> 'archivos'),
											  'Compras'			=> array ('Compras'			=> 'compras')
											  );
							if (in_array($authUser['id'], array(6,37)) )
							{
								$vinculos['Descuentos'] = array('Descuentos'		=> 'descuentos');
							}
							break;
						// FULL COMPRAS
						case 1 :
							$vinculos = array('Compras'			=> array('Compras'			=> 'compras'),
											  'Descuentos'		=> array('Descuentos'		=> 'descuentos'));
							break;
						// VER COMPRAS
						case 0 :
							$vinculos = array('Compras'			=> array('Compras'			=> 'compras'));
							break;
						// TIENDAS ADMIN
						case 4 :
							$vinculos = array('Compras'			=> array('Compras'			=> 'compras'),
											  'Descuentos'		=> array('Descuentos'		=> 'descuentos'));
							break;
						//TIENDAS
						case 5 :
							$vinculos = array('Descuentos'		=> array('Descuentos'		=> 'descuentos',
											  							'Tickets'		=> 'tickets'));
							break;
						case 6 :
							$vinculos = array('Agencia'		=> array('Banner'		=> 'banners',
																	 'Productos'	=> 'productos',
																	 'Email Blast' 	=> 'email_blasts'));
							break;
					}
				}
				else
				{
					$vinculos = array();
				}

				// EXCLUSIONES DONDE APARECERAN LOS MENUS
				$exclusiones = array ('usuarios' => array('login', 'logout'));

				// Generamos los tab de navegacion y el menu lateral
				if ( ! in_array($this->params['controller'], $exclusiones) )
				{
					if ( ! isset($exclusiones[$this->params['controller']]) )
						$exclusiones[$this->params['controller']] = array();

					if ( ! in_array($this->params['action'], $exclusiones[$this->params['controller']]) )
					{
						if ( isset($vinculos) )
						{
							echo $this->element('admin_lateral', array('vinculos' => array($vinculos)));
							if ( isset($authUser) )
							{
								echo '<ul class="tab">';
								foreach ($vinculos as $modkey => $mod)
								{
									$class = (in_array($this->params['controller'], $mod) ? 'current' : '');
									echo "<li class='{$class}'><span class='left'>&nbsp;</span>";
									echo $this->Html->link($modkey, array('controller' => current($mod), 'action' => 'index'));
									echo '<span class="right">&nbsp;</span></li>';
								}
								echo '</ul>';
							}
						}
						else
							echo $this->element('admin_lateral');
					}
				}
				?>

				<!-- VISTA PRINCIPAL -->
				<?= $content_for_layout; ?>

			</div>

			<!-- PIE DE PAGINA -->
			<div class="footer">
				<?= $this->Html->link($this->Html->image('admin/andain-pie.png'),
									  'http://www.andain.cl/',
									  array('escape' => false, 'target' => '_blank', 'title' => 'Andain | Desarrollo y Diseño Web')); ?>
			</div>
		</div>
	</body>
</html>
