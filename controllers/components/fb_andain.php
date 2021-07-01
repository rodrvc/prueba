<?php
class FbAndainComponent extends Object
{
	var $components	= array('Connect', 'Session');

	/**
	 * Inicializa las opciones bases del componente
	 */
	function initialize(&$controller)
	{
		// CONTROLADOR DE DONDE ES LLAMADO
		$this->Controller	=& $controller;

		// INICIALIZA EL OBJECTO FACEBOOK (ACCESO API)
		$this->Fb			= new FB();
	}


	/***
	 * Llamadas directas a la SDK Facebook
	 * 
	 * @param	string		$method			Metodo llamado
	 * @return	array		$arguments		Argumentos pasados por el usuario
	 */
	function __call($method, $arguments)
	{
		return call_user_func_array(array($this->Fb, $method), $arguments);
	}


	/***
	 * Envia una consulta FQL a la API
	 * 
	 * @param	string		$query			Consulta FQL
	 * @return	array						Respuesta FB
	 */
	function fql($query = null)
	{
		if ( ! $query )
			return false;

		return $this->Fb->api(array('method' => 'fql.query', 'query' => $query));
	}


	/***
	 * Envia consultas FQL multiples a la API
	 * 
	 * @param	array		$query			Consultas FQL
	 * @return	array						Respuesta FB
	 */
	function multifql($query = array())
	{
		if ( empty($query) )
			return false;

		return $this->Fb->api(array('method' => 'fql.multiquery', 'queries' => $query));
	}


	/***
	 * Verifica que el usuario logeado tenga aceptados los permisos de la aplicacion
	 *
	 * @param	string		$permisos		Lista de permisos que debe tener aceptados el usuario, separados por comas
	 * @return	boolean						True si tiene todos los permisos, false si no acepto alguno de los permisos
	 */
	function verificarPermisos($permisos = null)
	{
		// COMPROBAMOS DATOS MINIMOS
		if ( ! $permisos )
			return false;

		// COMPROBAMOS QUE PERMISOS HA ACEPTADO EL USUARIO
		$aceptados	= $this->api('/me?fields=permissions');

		// RECORREMOS Y COMPROBAMOS SI EL USUARIO TIENE LOS PERMISOS ACEPTADOS
		foreach ( explode(',', $permisos) as $permiso )
			if ( ! isset($aceptados['permissions']['data'][0][$permiso]) )
				return false;

		return true;
	}


	/***
	 * Entrega la lista de amigos
	 *
	 * @param	string		$adicional		Parametros adicionales (Limite, paginacion, etc.)
	 * @return	array						Arreglo con la lista de amigos solicitada
	 */
	function listaAmigos($adicional = null)
	{
		// DEVOLVEMOS LA LISTA DE AMIGOS
		if ( $adicional )
			return $this->api("/me/friends?{$adicional}");
		else
			return $this->api('/me/friends');
	}


	/***
	 * Entrega la URL de OAuth Dialog (Aceptar permisos de usuario)
	 *
	 * @param	string		$canvas			Nombre de la aplicación en FB (Facebook Integration - Canvas Page)
	 * @param	string		$permisos		(opcional) Lista de permisos separados por comas
	 * @return	void						Redireccion a la URL donde se pedira aceptar permisos
	 */
	function redireccionAuth($canvas = null, $permisos = null)
	{
		// COMPROBAMOS DATOS MINIMOS
		if ( ! $canvas )
			return false;

		// URL PARA AUTENTICAR AL USUARIO CON LA APP
		$authBase	= 'https://www.facebook.com/dialog/oauth/';
		$authParams	= array('client_id'		=> Configure::read('Facebook.appId'),
							'redirect_uri'	=> "http://apps.facebook.com/{$canvas}/");

		// INCLUIMOS LOS PERMISOS DE SER NECESARIO
		if ( $permisos )
			$authParams['scope']	= $permisos;

		// FORMATEAMOS LA URL FINAL
		$authURL	= $authBase . '?' . http_build_query($authParams, '', '&');

		// ENVIAMOS LA REDIRECCION DIRECTO AL NAVEGADOR
		die("<script>top.location.href = '{$authURL}'</script>");
	}


	/***
	 * Verifica si un usuario debe o no ser enviado a la pagina de permisos,
	 * basado en estado de sesion, permisos aceptados, pagina de origen, etc.
	 *
	 * @param	string		$canvas			Nombre de la aplicación en FB (Facebook Integration - Canvas Page)
	 * @param	string		$permisos		Lista de permisos que el usuario debe aceptar, separados por comas
	 * @param	mixed		$error			(opcional) Pagina donde sera redireccionado el usuario en caso de no aceptar permisos
	 * @return	void						Envia al usuario a la pagina de permisos
	 */
	function verificarLogeo($canvas = null, $permisos = null, $error = null)
	{
		// COMPROBAMOS DATOS MINIMOS
		if ( ! $canvas )
			return false;

		// SI EL USUARIO NO ESTA AUTENTICADO, LO ENVIAMOS A ACEPTAR PERMISOS
		if ( ! $this->Fb->getSession() )
			$this->redireccionAuth($canvas, $permisos);

		// VERIFICAMOS SI EL USUARIO TIENE TODOS LOS PERMISOS ACEPTADOS, SI CORRESPONDE
		if ( $permisos && ! $this->verificarPermisos($permisos) )
			$this->redireccionAuth($canvas, $permisos);

		/*
		// VERIFICAMOS QUE EL USUARIO ESTE ENTRANDO AL SITIO A TRAVES DE LA APP FACEBOOK (signed_request)
		if ( isset($_REQUEST['signed_request']) )
			$this->Session->write('Facebook.Signed', true);

		if ( ! $this->Session->read('Facebook.Signed') )
			$this->redireccionAuth($canvas, $permisos);
		*/

		// USUARIO NO ACEPTA LOS PERMISOS
		if ( isset($_GET['error']) && $_GET['error'] == 'access_denied' )
				$this->redireccionAuth($canvas, $permisos);
	}


	/***
	 * Verifica si el usuario tiene los permisos aceptados
	 * 
	 * @param	array		$fanpages		Lista de fanpages
	 * @return	array						Lista de los fanpages de los que no es fan
	 */
	function verificarFanpages($fanpages = array())
	{
		// COMPROBAMOS DATOS MINIMOS
		if ( empty($fanpages) )
			return false;

		// CONSULTA LOS LIKES REQUERIDOS
		$likes	= $this->fql('SELECT page_id FROM page_fan WHERE uid = me()	AND page_id IN (' . implode(',', $fanpages) . ')');
		$likes	= Set::classicExtract($likes, '{n}.page_id');

		// DEVOLVEMOS LOS FANPAGES DE LOS CUAL NO ES FAN
		return Set::diff($fanpages, $likes);
	}


	/***
	 * Obtiene la informacion sobre fanpages y el estado de fan del usuario
	 * 
	 * @param	string		$fanpages		Lista de fanpages
	 * @return	array						Informacion del fanpage + estado de fan
	 */
	function infoFanpagesFan($fanpages = array())
	{
		// OBTIENE LA INFORMACION DE LOS FANPAGES
		$faninfo	= $this->multifql(array('SELECT page_id, name, page_url FROM page WHERE page_id IN (' . implode(',', $fanpages) . ')',
											'SELECT page_id FROM page_fan WHERE uid = me() AND page_id IN (' . implode(',', $fanpages) . ')'));
		$fanpages	= Set::classicExtract($faninfo[0]['fql_result_set'], '{n}.{.*}');

		// FALLBACK TIMEOUTS
		if ( ( ! isset($faninfo[0]['fql_result_set']) || ! is_array($faninfo[0]['fql_result_set']) || empty($faninfo[0]['fql_result_set']) ) &&
			 ( ! isset($faninfo[1]['fql_result_set']) || ! is_array($faninfo[1]['fql_result_set']) || empty($faninfo[1]['fql_result_set']) ) )
			return array();

		// ESTADO DE LIKES DE USUARIO
		$fanstatus	= array();
		foreach ( $faninfo[1]['fql_result_set'] as $fan )
			$fanstatus[$fan['page_id']]	= true;

		// INFORMACION COMPLETA DE FANPAGES
		foreach ( $fanpages as $x => $fanpage )
			$fanpages[$x]['fan']	= isset($fanstatus[$fanpage['page_id']]);

		return $fanpages;
	}
	
	/***
	 * Comprueba y/o crear un Album de Fotos
	 * permisos:	user_photos
	 *
	 * @param	string		$nombre			Nombre del album
	 * @param	string		$descripcion	Descripcion del album
	 * @return	mixed						False si fallo la creacion o el array con los datos del album
	 */
	function crearAlbum($nombre = null, $descripcion = null)
	{
		// VERIFICACION DE SESION
		if ( ! $this->Connect->user() )
			return false;
		$user_id = $this->Connect->user('id');

		// COMPROBAMOS NOMBRE Y DESCRIPCION DEL ALBUM
		if ( ! $nombre || ! $descripcion )
			return false;

		// PRIMERO, CONSULTAMOS SI EXISTE EL ALBUM
		$existe		= $this->fql("SELECT aid, name, object_id FROM album WHERE owner = '{$user_id}' AND name = '{$nombre}' LIMIT 1");
		$album		= array();
		
		// SI YA EXISTE EL ALBUM, RESCATAMOS EL ID
		if ( isset($existe[0]['object_id']) )
			$album		= $existe[0];
		
		// SI NO EXISTE, LO CREAMOS
		else
		{
			$crear		= $this->api('/me/albums', 'POST', array('name'=> $nombre, 'message' => $descripcion));

			// SI EXISTE UN ERROR AL CREAR EL ALBUM, DEVOLVEMOS FALSE
			if ( ! isset($crear['id']) )
				return false;

			// GUARDAMOS EL ID DEL ALBUM
			$existe		= $this->fql("SELECT aid, name, object_id FROM album WHERE owner = '{$user_id}' AND object_id = '{$crear['id']}'");
			$album		= $existe[0];
		}
		return $album;
	}
	
	
	/***
	 * Sube una foto a un album de FB
	 * permisos:	user_photos
	 *
	 * @param	string		$album_id		ID del album Facebook
	 * @param	string		$foto			Ruta de la foto a subir
	 * @param	string		$caption		Caption de la foto que se subira
	 * @return	mixed						ID de la foto, False si existen errores al subir
	 */
	function subirFoto($album_id = null, $foto = null, $caption = null)
	{
		// VERIFICACION DE SESION
		if ( ! $this->Connect->user() )
			return false;
		
		// COMPROBAMOS DATOS MINIMOS
		if ( ! $album_id || ! $foto || ! $caption )
			return false;
		
		// INTENTAMOS SUBIR LA FOTO
		$foto		= $this->api("/{$album_id}/photos", 'POST', array('image' => "@{$foto}", 'message' => $caption));
	
		// COMPROBAMOS SI SE SUBIO LA FOTO
		if ( isset($foto['id']) )
			return $foto['id'];
		else
			return false;
	}
}
?>