<ul class="menu">
	<?
	// MODULOS
	// Vemos a cual paleta corresponde nuestro submenu y desplegamos la lista de opciones
	if ( isset($vinculos) && ! empty($vinculos) )
	{
		foreach ( $vinculos as $key => $modkeys )
			foreach ( $modkeys as $eachmod => $mods )
				if ( in_array($this->params['controller'], $mods) )
					$activo = $eachmod;

		if ( isset($activo) )
			$modulos = $modkeys[$activo];
	}
	else
	{
		$controllers = App::objects('controller');
		foreach ( $controllers as $controller )
		{
			$human = Inflector::humanize(Inflector::underscore($controller));
			$exclusiones = array('App','Pages');
			if ( ! in_array($controller,$exclusiones) )
				$modulos[$human] = $controller;
		}
	}

    if (isset($modulos)){
        foreach ($modulos as $nombre => $controlador){
            echo "<li>";

            if ($this->params['controller']==$controlador){
                echo $html->link($nombre,'#',array('rel' => $controlador,'class' => 'current'));
            }else{
                echo $html->link($nombre,'#',array('rel' => $controlador,'class' => ''));
            }

            //Obtenemos todas las acciones para admin del controlador
             if ($controlador != 'App') {
                App::import('Controller', $controlador);
                $className = $controlador . 'Controller';
                $actions = get_class_methods($className);
                if (is_array($actions)){
                    foreach($actions as $k => $v) {
                        if ($v{0} == '_') {
                            unset($actions[$k]);
                        }
                        if (substr($v,0,6) != 'admin_'){
                            unset($actions[$k]);
                        }
                        if (array_search($v,array('','admin_add','admin_view','admin_index','admin_delete','admin_edit'))){
                            unset($actions[$k]);
                        }
                    }
                }
                $parentActions = get_class_methods('AppController');
                if (is_array($actions)){
                    $acciones[$controlador] = array_diff($actions, $parentActions);
                }
            }

            //Mostramos los links de cada vista
			if (in_array($authUser['perfil'], array(0,1,2,3,4,5,6)))
			{
				switch ( $authUser['perfil'] )
				{
					// SUPER ADMIN
					case 3 :
						?><?= $this->element('admin_lateral_perfil_3', array('controlador' => $controlador, 'nombre' => $nombre)); ?><?
						break;
					// FULL PRODUCTOS
					case 2 :
						?><?= $this->element('admin_lateral_perfil_2', array('controlador' => $controlador, 'nombre' => $nombre)); ?><?
						break;
					// FULL COMPRAS
					case 1 :
						?><?= $this->element('admin_lateral_perfil_1', array('controlador' => $controlador, 'nombre' => $nombre)); ?><?
						break;
					// VER COMPRAS
					case 0 :
						?><?= $this->element('admin_lateral_perfil_0', array('controlador' => $controlador, 'nombre' => $nombre)); ?><?
						break;
					// TIENDAS ADMIN
					case 4 :
						?><?= $this->element('admin_lateral_perfil_4', array('controlador' => $controlador, 'nombre' => $nombre)); ?><?
						break;
					//TIENDAS
					case 5 :
						?><?= $this->element('admin_lateral_perfil_5', array('controlador' => $controlador, 'nombre' => $nombre)); ?><?
						break;
					case 6 :
						?><?= $this->element('admin_lateral_perfil_6', array('controlador' => $controlador, 'nombre' => $nombre)); ?><?
						break;
				}
			}
        }
    }
?>
</ul>

<script type="text/javascript">
$(document).ready(function()
{
	// ABRE EL MENU ACTIVO
	//$('ul.menu li a[rel="<?= $this->params['controller']; ?>"]').next().show();
});
</script>
