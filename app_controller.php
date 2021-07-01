<?php
class AppController extends Controller
{
	var $helpers	= array('Session', 'Html', 'Form', 'Text', 'Time', 'Number', 'Javascript', 'Shapeups', 'Facebook.Facebook');
	var $components	= array('Session',
							'RequestHandler',
							'Auth' => array('userModel'			=> 'Administrador',
											//'userScope'			=> array('Usuario.activo' => true),
											'fields'			=> array('username'		=> 'usuario',
																		 'password'		=> 'clave'),
											'loginAction' 		=> array('controller'	=> 'administradores',		'action' => 'login',	'admin' => true),
											'logoutRedirect' 	=> array('controller'	=> 'administradores',		'action' => 'login',	'admin' => true),
											'loginRedirect' 	=> array('controller'	=> 'administradores',		'action' => 'login',	'admin' => true),
											'loginError'		=> 'Datos de ingreso erroneos',
											'authError'			=> 'No tienes permisos para esta sección',
											'autoRedirect'		=> true
											),
							'DebugKit.Toolbar'	=> array('panels' => array('history'	=> false,
																		   'request'	=> true,
																		   'log'		=> false
																		   )
														 ),
							'Email',
							'Carro',
							'Facebook.Connect',
							'FbAndain',
							'Multivende',
							'Chilexpress'
							);

	var $stock_seguridad = 6;
	var $certificate = array(

  "environment" => "INTEGRACION",

    /** Llave Privada */
    "private_key" => "-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEA0ClVcH8RC1u+KpCPUnzYSIcmyXI87REsBkQzaA1QJe4w/B7g
6KvKV9DaqfnNhMvd9/ypmGf0RDQPhlBbGlzymKz1xh0lQBD+9MZrg8Ju8/d1k0pI
b1QLQDnhRgR2T14ngXpP4PIQKtq7DsdHBybFU5vvAKVqdHvImZFzqexbZjXWxxhT
+/sGcD4Vs673fc6B+Xj2UrKF7QyV5pMDq0HCCLTMmafWAmNrHyl6imQM+bqC12gn
EEAEkrJiSO6P/21m9iDJs5KQanpJby0aGW8mocYRHDMHZjtTiIP0+JAJgL9KsH+r
Xdk2bT7aere7TzOK/bEwhkYEXnMMt/65vV6AfwIDAQABAoIBAHnIlOn6DTi99eXl
KVSzIb5dA747jZWMxFruL70ifM+UKSh30FGPoBP8ZtGnCiw1ManSMk6uEuSMKMEF
5iboVi4okqnTh2WSC/ec1m4BpPQqxKjlfrdTTjnHIxrZpXYNucMwkeci93569ZFR
2SY/8pZV1mBkZoG7ocLmq+qwE1EaBEL/sXMvuF/h08nJ71I4zcclpB8kN0yFrBCW
7scqOwTLiob2mmU2bFHOyyjTkGOlEsBQxhtVwVEt/0AFH/ucmMTP0vrKOA0HkhxM
oeR4k2z0qwTzZKXuEZtsau8a/9B3S3YcgoSOhRP/VdY1WL5hWDHeK8q1Nfq2eETX
jnQ4zjECgYEA7z2/biWe9nDyYDZM7SfHy1xF5Q3ocmv14NhTbt8iDlz2LsZ2JcPn
EMV++m88F3PYdFUOp4Zuw+eLJSrBqfuPYrTVNH0v/HdTqTS70R2YZCFb9g0ryaHV
TRwYovu/oQMV4LBSzrwdtCrcfUZDtqMYmmZfEkdjCWCEpEi36nlG0JMCgYEA3r49
o+soFIpDqLMei1tF+Ah/rm8oY5f4Wc82kmSgoPFCWnQEIW36i/GRaoQYsBp4loue
vyPuW+BzoZpVcJDuBmHY3UOLKr4ZldOn2KIj6sCQZ1mNKo5WuZ4YFeL5uyp9Hvio
TCPGeXghG0uIk4emSwolJVSbKSRi6SPsiANff+UCgYEAvNMRmlAbLQtsYb+565xw
NvO3PthBVL4dLL/Q6js21/tLWxPNAHWklDosxGCzHxeSCg9wJ40VM4425rjebdld
DF0Jwgnkq/FKmMxESQKA2tbxjDxNCTGv9tJsJ4dnch/LTrIcSYt0LlV9/WpN24LS
0lpmQzkQ07/YMQosDuZ1m/0CgYEAu9oHlEHTmJcO/qypmu/ML6XDQPKARpY5Hkzy
gj4ZdgJianSjsynUfsepUwK663I3twdjR2JfON8vxd+qJPgltf45bknziYWvgDtz
t/Duh6IFZxQQSQ6oN30MZRD6eo4X3dHp5eTaE0Fr8mAefAWQCoMw1q3m+ai1PlhM
uFzX4r0CgYEArx4TAq+Z4crVCdABBzAZ7GvvAXdxvBo0AhD9IddSWVTCza972wta
5J2rrS/ye9Tfu5j2IbTHaLDz14mwMXr1S4L39UX/NifLc93KHie/yjycCuu4uqNo
MtdweTnQt73lN2cnYedRUhw9UTfPzYu7jdXCUAyAD4IEjFQrswk2x04=
-----END RSA PRIVATE KEY-----",

    /** Certificado Publico */
    "public_cert" => "-----BEGIN CERTIFICATE-----
MIIDujCCAqICCQCZ42cY33KRTzANBgkqhkiG9w0BAQsFADCBnjELMAkGA1UEBhMC
Q0wxETAPBgNVBAgMCFNhbnRpYWdvMRIwEAYDVQQKDAlUcmFuc2JhbmsxETAPBgNV
BAcMCFNhbnRpYWdvMRUwEwYDVQQDDAw1OTcwMjAwMDA1NDExFzAVBgNVBAsMDkNh
bmFsZXNSZW1vdG9zMSUwIwYJKoZIhvcNAQkBFhZpbnRlZ3JhZG9yZXNAdmFyaW9z
LmNsMB4XDTE2MDYyMjIxMDkyN1oXDTI0MDYyMDIxMDkyN1owgZ4xCzAJBgNVBAYT
AkNMMREwDwYDVQQIDAhTYW50aWFnbzESMBAGA1UECgwJVHJhbnNiYW5rMREwDwYD
VQQHDAhTYW50aWFnbzEVMBMGA1UEAwwMNTk3MDIwMDAwNTQxMRcwFQYDVQQLDA5D
YW5hbGVzUmVtb3RvczElMCMGCSqGSIb3DQEJARYWaW50ZWdyYWRvcmVzQHZhcmlv
cy5jbDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBANApVXB/EQtbviqQ
j1J82EiHJslyPO0RLAZEM2gNUCXuMPwe4OirylfQ2qn5zYTL3ff8qZhn9EQ0D4ZQ
Wxpc8pis9cYdJUAQ/vTGa4PCbvP3dZNKSG9UC0A54UYEdk9eJ4F6T+DyECrauw7H
RwcmxVOb7wClanR7yJmRc6nsW2Y11scYU/v7BnA+FbOu933Ogfl49lKyhe0MleaT
A6tBwgi0zJmn1gJjax8peopkDPm6gtdoJxBABJKyYkjuj/9tZvYgybOSkGp6SW8t
GhlvJqHGERwzB2Y7U4iD9PiQCYC/SrB/q13ZNm0+2nq3u08ziv2xMIZGBF5zDLf+
ub1egH8CAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAdgNpIS2NZFx5PoYwJZf8faze
NmKQg73seDGuP8d8w/CZf1Py/gsJFNbh4CEySWZRCzlOKxzmtPTmyPdyhObjMA8E
Adps9DtgiN2ITSF1HUFmhMjI5V7U2L9LyEdpUaieYyPBfxiicdWz2YULVuOYDJHR
n05jlj/EjYa5bLKs/yggYiqMkZdIX8NiLL6ZTERIvBa6azDKs6yDsCsnE1M5tzQI
VVEkZtEfil6E1tz8v3yLZapLt+8jmPq1RCSx3Zh4fUkxBTpUW/9SWUNEXbKK7bB3
zfB3kGE55K5nxHKfQlrqdHLcIo+vdShATwYnmhUkGxUnM9qoCDlB8lYu3rFi9w==
-----END CERTIFICATE-----",

/** Certificado Privado */
"webpay_cert" => "-----BEGIN CERTIFICATE-----
MIIDKTCCAhECBFZl7uIwDQYJKoZIhvcNAQEFBQAwWTELMAkGA1UEBhMCQ0wxDjAMBgNVBAgMBUNo
aWxlMREwDwYDVQQHDAhTYW50aWFnbzEMMAoGA1UECgwDa2R1MQwwCgYDVQQLDANrZHUxCzAJBgNV
BAMMAjEwMB4XDTE1MTIwNzIwNDEwNloXDTE4MDkwMjIwNDEwNlowWTELMAkGA1UEBhMCQ0wxDjAM
BgNVBAgMBUNoaWxlMREwDwYDVQQHDAhTYW50aWFnbzEMMAoGA1UECgwDa2R1MQwwCgYDVQQLDANr
ZHUxCzAJBgNVBAMMAjEwMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAizJUWTDC7nfP
3jmZpWXFdG9oKyBrU0Bdl6fKif9a1GrwevThsU5Dq3wiRfYvomStNjFDYFXOs9pRIxqX2AWDybjA
X/+bdDTVbM+xXllA9stJY8s7hxAvwwO7IEuOmYDpmLKP7J+4KkNH7yxsKZyLL9trG3iSjV6Y6SO5
EEhUsdxoJFAow/h7qizJW0kOaWRcljf7kpqJAL3AadIuqV+hlf+Ts/64aMsfSJJA6xdbdp9ddgVF
oqUl1M8vpmd4glxlSrYmEkbYwdI9uF2d6bAeaneBPJFZr6KQqlbbrVyeJZqmMlEPy0qPco1TIxrd
EHlXgIFJLyyMRAyjX9i4l70xjwIDAQABMA0GCSqGSIb3DQEBBQUAA4IBAQBn3tUPS6e2USgMrPKp
sxU4OTfW64+mfD6QrVeBOh81f6aGHa67sMJn8FE/cG6jrUmX/FP1/Cpbpvkm5UUlFKpgaFfHv+Kg
CpEvgcRIv/OeIi6Jbuu3NrPdGPwzYkzlOQnmgio5RGb6GSs+OQ0mUWZ9J1+YtdZc+xTga0x7nsCT
5xNcUXsZKhyjoKhXtxJm3eyB3ysLNyuL/RHy/EyNEWiUhvt1SIePnW+Y4/cjQWYwNqSqMzTSW9TP
2QR2bX/W2H6ktRcLsgBK9mq7lE36p3q6c9DtZJE+xfA4NGCYWM9hd8pbusnoNO7AFxJZOuuvLZI7
JvD7YLhPvCYKry7N6x3l
-----END CERTIFICATE-----",

    /** Codigo Comercio */
    "commerce_code" => "597020000541",


);
	var $email_skechers = array(
		0 => 'noreply@skechers-chile.cl',
		1 => 'andainandain',
		2 => 'ventas@skechers-chile.cl'
	);
	var $datos_email = array(
'host' => 'smtp.sendgrid.net',
'user' => 'noreply@skechers-chile.cl',
'pass' => 'Andain5546');
	//var $tbkParams = array(
	//	'cgi'		=> 'http://store.skechers-chile.cl/cgi-bin/tbk_bp_pago.cgi', 
	//	'exito'		=> 'http://www.skechers.cl/productos/felicidades',
	//	'fracaso'	=> 'http://www.skechers.cl/productos/fallo',
	//	'repo_log' 	=> '/home/skechile/public_html/store/cgi-bin/log/' 
	//);
	var $tbkParams = array(
		'cgi'		=> 'http://webpay.skechers-chile.cl/cgi-bin/tbk_bp_pago.cgi',
		'exito'		=> 'http://54.234.213.249/productos/felicidades',
		'fracaso'	=> 'http://54.234.213.249/productos/fallo',
		'cierre'    =>  'http://54.234.213.249/pago/cierre2.php',
		'repo_log' 	=> '/home/skechile/public_html/webpay/cgi-bin/log/',
		'mercado_pago' => 'TEST-2269204429642405-070621-898a8f47328e447fb7129d2128249f4e-604718846'
	);

	function beforeFacebookSave()
	{
		// CAMPOS A GUARDAR DEL USUARIO
		//$apellido= explode(' ', $this->Connect->user('name'));
		$datos			= array($this->Auth->userModel	=> array('nombre'			=> $this->Connect->user('first_name'),
																 'apellido_paterno'	=> $this->Connect->user('last_name'),
																 'email'			=> $this->Connect->user('email'),
																 'validado'			=> $this->Connect->user('verified'),
																 'creditos'			=> 0
																 )
								);

		$this->Connect->authUser	= Set::merge($this->Connect->authUser, $datos);
		// POST DE BIENVENIDA
		//$this->FbAndain->api('/me/feed', 'POST', array('message' 		=> 'Yo ya estoy participando por un viaje a NY . Ven tú también a participar en Sckechers Be the next one in USA!',
		//											   'picture' 		=> 'http://store.skechers-chile.cl/img/btn1/compartir_fb.jpg',
		//											   'name' 			=> 'Be The Next One',
		//											   'link' 			=> 'http://store.skechers-chile.cl/btn1',
		//											   'caption'		=> 'Be The Next One',
		//											   'description'	=> 'Yo ya estoy participando por un viaje a NY . Ven tú también a participar en Sckechers Be the next one in USA!',
		//											   )
		//					 );
		return true;
	}

	function beforeFilter()
	{
		//9a498bb659f5329c0358
		//prx($this->Auth->password('seba'));
		// PERMISOS FULL A TODAS LAS PAGINAS SIN PREFIJO
		if ( isset($this->Auth) && ! isset($this->params['prefix']) )
		{

			$this->Auth->userModel		= 'Usuario';
			$this->Auth->sessionKey		= 'Auth.Usuario';
			$this->Auth->userScope		= array();
			$this->Auth->fields			= array('username' => 'email', 'password' => 'clave');
			$this->Auth->loginAction 	= array('controller' => 'usuarios', 'action' => 'login');
			$this->Auth->logoutRedirect = array('controller' => 'productos', 'action' => 'inicio');
			// if ( isset($this->data['Usuario']['origen']) && $this->data['Usuario']['origen'] == 'carro' )
			// 	$this->Auth->loginRedirect	= array('controller' => 'productos', 'action' => 'registro');
			// else
			$this->Auth->loginRedirect	= array('controller' => 'productos', 'action' => 'inicio');
			if ($this->Session->check('Login.redirect'))
			{
				$this->Auth->loginRedirect	= $this->Session->read('Login.redirect');
			}

			$this->Auth->allow('*');
		}
		if ($this->Session->check('Login'))
		{
			$loginRedirect = $this->Session->read('Login.redirect');
			if ($this->params['controller'] == $loginRedirect['controller'] && $this->params['action'] == $loginRedirect['action'])
			{
				$this->Session->delete('Login.redirect');
			}
		}
					$this->registroLog();


		// PETICIONES AJAX
		if ( $this->RequestHandler->isAjax() )
		{
			if ( $this->Session->read('Message.flash') )
				$this->Session->delete('Message.flash');

			Configure::write('debug', 0);
			$this->disableCache();
			$this->layout	= 'ajax';
		}
	}
	private function registroLog()
    {
    	if($this->Session){
    		$tipo_usuario = 'admin';
        	$usuario = $this->Session->read('Auth');

	        $ignoreTexts = array('vendors', 'template', 'ajax_dropzone', 'plugins', 'globallogs');

	        if(in_array($this->params['controller'], $ignoreTexts) || in_array($this->params['action'], $ignoreTexts))
				return false;

	        if (isset($usuario['Administrador']['id'])){
	        	$registro_log['GlobalLog']['id_usuario'] = $usuario['Administrador']['id'];
	        	$registro_log['GlobalLog']['controlador'] = $this->params['controller'];
	        	$registro_log['GlobalLog']['accion'] = $this->params['action'];

	        	$params = null;

	        	if(!empty($this->data)){
	        		$data = $this->data;
	        		$params = json_encode($data);
	        	}

	        	$registro_log['GlobalLog']['parametros'] = $params;
	        	$registro_log['GlobalLog']['metodo'] = (!empty($this->data)) ? 'post' : 'get';

	            $this->loadModel('GlobalLog');
	           // prx($this->GlobalLog);
	            $this->GlobalLog->save($registro_log);
	           
	        }
    	}
    }

	function beforeRender()
	{
		// pr($this->params);
		 //prx($this->Auth->password('hola'));
		// LAYOUT ADMIN
		if ( isset($this->params['prefix']) && in_array($this->params['prefix'], Configure::read('Routing.prefixes')) )
		{
			$this->layout = 'admin';
		}

		// LAYOUT ADMIN LOGIN
		if ( isset($this->params['prefix']) && in_array($this->params['prefix'], Configure::read('Routing.prefixes')) && stripos($this->action, 'login') !== false )
			$this->layout = 'admin_login';

		// VARIABLE CON LOS DATOS DEL USUARIO LOGEADO
		if ( isset($this->Auth) && $this->Auth->user() )
		{
			//$this->_refreshAuth();
			$authUser	= $this->Auth->user();
			$this->set('fbUser', $authUser[$this->Auth->userModel]);
			$this->set('authUser', $authUser[$this->Auth->userModel]);
		}
					$this->loadModel("Primario");


		// CARGA MENU DE USUARIOS (no carga para admin)
		if ( $this->Auth->userModel != 'Administrador' )
		{
			//if (! $this->Auth->user())
			//{
			//	//$login = array(
			//	//	'email' => 'ehenriquez@andain.cl',
			//	//	'clave' => '38cbd6a9ff657f4f3cf021a6348436ec9d661cdb'
			//	//);
			//	//$this->Auth->login($login);
			//}

	

			$this->loadModel("Categoria");
			//	prx(compact('menus_tallas','menu_colores','menu_estilos'));
			$options = array(
				'conditions' => array(
					'Categoria.parent_id' => 0,
					'Categoria.publico' => 1),
				'fields' => array(
					'Categoria.id',
					'Categoria.slug',
					'Categoria.nombre'
				),
                'contain' => array(
                    'ChildCategoria' => array('ChildCategoria')
                ),
                'order' => array(
                    'Categoria.nombre' => 'ASC'
                )
			);
			$menu_categorias = $this->Categoria->find('all',$options);
			//prx($menu_categorias);

			
			$this->set(compact('menu_categorias'));


/*
		$this->loadModel("Menu");
			$this->loadModel('Estilo');
			//$menus_tallas = Cache::read('menus_tallas');
			$menu_colores = Cache::read('menu_colores');
			$menu_estilos = Cache::read('menu_estilos');
			$menu_colecciones = Cache::read('menu_colecciones');
			if(  $menu_colores == false || $menu_estilos == false || $menu_colecciones  == false)
			{

				for ( $categoria = 1; $categoria <= 4; $categoria++ )
				{
					$options = array(
						'conditions' => array(
							'Menu.categoria_id' => $categoria,
							'Menu.tipo' => 1
						),
						'fields' => array(
							'Menu.id',
							'Menu.talla',
							'Menu.estado'
						)
					);
					$menus_tallas[$categoria] = $this->Menu->find('all',$options);
					$options = array(
						'conditions' => array(
							'Menu.categoria_id' => $categoria,
							'Menu.tipo' => 2
						),
						'fields' => array(
							'Menu.id', 'Menu.primario_id'
						),
						'contain' => array(
							'Primario' => array(
								'fields' => array(
									'Primario.id',
									'Primario.nombre',
									'Primario.imagen',
									'Primario.slug'
								)
							)
						)
					);
					$menu_colores[$categoria] = $this->Menu->find('all',$options);
					$menu_estilos[$categoria] = $estilos = array();
					$options = array(
						'conditions' => array(
								array('Categoria.id' => $categoria),
							//	array('Categoria.parent_id' => $categoria)
							
						),
						'fields' => array(
							'Estilo.nombre',
							'Estilo.activo',
							'Estilo.alias',
							'Estilo.destacado',
							'Estilo.categoria_id'
						),
						'joins' => array(
							array(
								'table' => 'sitio_categorias',
								'alias' => 'Categoria',
								'type' => 'INNER',
								'conditions' => array(
									'Categoria.id = Estilo.categoria_id'
								)
							)
						),
						'order' => array(
							'Estilo.nombre' => 'ASC'
						)
					);
					if ($estilos = $this->Estilo->find('all',$options))
					{
						foreach ($estilos as $estilo)
						{
							$options = array(
								'conditions' => array(
									'Producto.grupo LIKE' => '%['.$estilo['Estilo']['alias'].']%',
									'Producto.activo' => 1,
									'OR' => array(
										array('Categoria.id' => $categoria),
									//	array('Categoria.parent_id' => $categoria)
									)
								),
								'fields' => array(
									'Producto.id'
								),
								'recursive' => -1,
								'joins' => array(
									array(
										'table' => 'sitio_stocks',
										'alias' => 'Stock',
										'type' => 'INNER',
										'conditions' => array(
											'Stock.producto_id = Producto.id'
										)
									),
									array(
										'table' => 'sitio_categorias',
										'alias' => 'Categoria',
										'type' => 'INNER',
										'conditions' => array(
											'Categoria.id = Producto.categoria_id'
										)
									)
								)
							);
							// verifica si existen productos con este filtro de estilo
							if (! $verificacrEstilo = $this->Primario->Color->Producto->find('first',$options))
							{
								continue;
							}
							if($estilo['Estilo']['destacado'] == 1)
							{
								if($estilo['Estilo']['activo'] == 1)
								{
									array_push($menu_estilos[$categoria], $estilo);
								}
								if(!isset($menu_colecciones[$estilo['Estilo']['alias']]))
								{

									$menu_colecciones[$estilo['Estilo']['alias']] = $estilo;
								}

							}
							else{
								if($estilo['Estilo']['activo'] == 1)
								{
									array_push($menu_estilos[$categoria], $estilo);
								}
								
							}
						}
					}
				}
			  Cache::write('menus_tallas', 	$menus_tallas);
				Cache::write('menu_colores', $menu_colores);
				Cache::write('menu_estilos', $menu_estilos);
				Cache::write('menu_colecciones', $menu_colecciones);

				


		}
		

			$options = array(
				'conditions' => array(
					'Categoria.publico' => 1
				),
				'fields' => array(
					'Categoria.id',
					'Categoria.id'
				)
			);
			$lista_categorias_activas = $this->Primario->Color->Producto->Categoria->find('list',$options);

			$options = array(
				'conditions' => array(
					'Categoria.id' => $lista_categorias_activas
				),
				'fields' => array(
					'Categoria.id',
					'Categoria.slug'
				)
			);
			$menu_categorias = $this->Primario->Color->Producto->Categoria->find('list',$options);
			$menu = array(
				'menu_categorias' => $menu_categorias,
				'menus_tallas'	=> $menus_tallas,
				'menu_colores'	=> $menu_colores,
				'menu_colecciones'	=> $menu_colecciones
			);
			$this->set(compact('menu_categorias', 'menus_tallas', 'menu_colores', 'menu_estilos', 'menu_colecciones'));
			*/
		}
		$stock_seguridad = $this->stock_seguridad;
		$settings = $this->Primario->query('select * from settings');
		$estado_sitio =0;
		if(empty($settings))
			$estado_sitio = 0;
		else
			$estado_sitio = $settings[0]['settings']['valor'];
		//$estado_sitio =0;

		if($estado_sitio ==0){
			require("/var/www/html/cortina.html");
			exit();
		}

		$estado_sitio =2;
		$estado_venta = 1;
		//$serv_produccion = false;
		$dir_produccion = 'http://www.skechers.cl';
		//if ( getcwd() == '/home/skechile/public_html/store/webroot' )
		$serv_produccion = true;

		$this->set(compact('dir_produccion', 'serv_produccion', 'stock_seguridad','estado_sitio','estado_venta'));
	}

	/**
	
	 * Refresca la Session de Auth Controller
	 *
	 * @return	void
	 */
	function _refreshAuth()
	{
		$Administrador	= ClassRegistry::init($this->Auth->userModel);
		$Administrador->recursive	= -1;
		$this->Auth->login($Administrador->findById($this->Auth->user('id')));
	}

	function guardar_log($usuario_id = null, $controlador = null, $accion = null, $detalle = null, $ip = null)
	{
		$new_log = array();
		if ($usuario_id)
			$new_log['usuario_id'] = $usuario_id;
		if ($controlador)
			$new_log['controlador'] = $controlador;
		if ($accion)
			$new_log['accion'] = $accion;
		if ($detalle)
			$new_log['detalle'] = $detalle;
		if ($ip)
			$new_log['ip'] = $ip;
		if ($new_log)
		{
			$this->loadModel('Log');
			if ($this->Log->save($new_log))
				return true;
			else
				return false;
		}
		else
		{
			return false;
		}
	}

	function enviar_email_mensaje($para = null, $mensaje = null, $asunto = null, $copia = null)
	{
		if ($para && $mensaje)
		{
			$this->set(compact('mensaje'));
			//EMAIL
			$this->Email->smtpOptions = array(
			'port' => '25',
			'timeout' => '30',
			'auth' => true,
			'host' => 'skechers-chile.cl',
			'username' => 'noreply@skechers-chile.cl',
			'password' => 'andainandain'
			);
			// DATOS DESTINATARIO (ADMIN)
			$this->Email->to = $para;
			if ($copia)
				$this->Email->bcc	= $copia;
			if ($asunto)
				$this->Email->subject = $asunto;
			else
				$this->Email->subject = '[Skechers] Mensaje';
			$this->Email->from = 'Skechers <ventas@skechers.cl>';
			$this->Email->replyTo = 'ventas@skechers.cl';
			$this->Email->sendAs = 'html';
			$this->Email->template	= 'mensaje';
			$this->Email->delivery = 'smtp';
			if ($this->Email->send())
				return true;
			else
				return false;
		}
		return false;
	}

	//function appError()
	//{
	//	$this->redirect('/');
	//}
}

function prx($data)
{
	pr($data); exit;
}
