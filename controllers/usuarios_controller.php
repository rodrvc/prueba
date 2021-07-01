<?php
class UsuariosController extends AppController
{
	var $name = 'Usuarios';
	var $helpers = array('Cache');
//	var $cacheAction = "1 hour";
	function login()
	{
		// redirecciona al usuario cuando se loguea al carro de compras
		if ( isset($this->data) && $this->Session->read('Logueo.estado') )
		{

			$this->redirect(array('controller' => $this->Session->read('Logueo.controller'), 'action' => $this->Session->read('Logueo.action')));
		}
	}
	function ajax_info()
	{
		Configure::write('debug',0);
		$this->layout = 'ajax';
	}
	function prueba()
	{
		die(date('Y-m-d H:i:s'));
		$this->Email->smtpOptions = array(
			'port' => '25',
			'timeout' => '30',
			'auth' => true,
			'host' => $this->datos_email['host'],
			'usern' => $this->datos_email['user'],
			'password' => $this->datos_email['pass']
		);
		// DATOS DESTINATARIO
		$this->Email->to = 'sdelvillar@andain.cl';
		$this->Email->subject = '[Skechers - Tienda en linea] Registro';
		$this->Email->from = 'Skechers <ventas@skechers.com>';
		//$this->Email->replyTo = 'contacto@trillonario.com.do';
		$this->Email->sendAs = 'html';
		$this->Email->template	= 'registro';
		$this->Email->delivery = 'smtp';
		prx($this->Email->send());

	}
	function ajax_usuario()
	{
		Configure::write('debug',0);
		$this->layout = 'ajax';
	}
	function listado($desde = null)
	{
	$this->layout = 'ajax';
	$desde = '2020-09-03 00:00';
	$this->loadModel('Region');
	$this->loadModel('Comuna');
	$regiones = $this->Region->find('list', array('fields' => array('id','nombre')));
	$comunas = $this->Comuna->find('list', array('fields' => array('id','nombre')));
	for($i=1; $i<2 ;$i++)
	{
		$usuarios = $this->Usuario->find('all', array(
												'fields'	=> array(
													'nombre',
													'apellido_paterno',
													'email',
													'sexo_id',
													'fecha_nacimiento',
													'rut'
												), 
												'contain' => array(
													'Compra' => array(
														'Producto' => array(
															'fields' => array(
																'categoria_id'
																)
															),
														 'Despacho' => 'Direccion'
														)
												), 
												'conditions' => array(
													'created >' => $desde
												),
												'limit' => 1000,
												'page'	=> $i
											)
										);
		$fp = fopen('D:\xampp\htdocs\skechers\usuarios.csv', "a");
		$encabezado = array('Name','LastName','email','gender','Gen_Comp','rut','F_Nac','Comuna','Region');
		fputcsv($fp,$encabezado,',');
		
		foreach ($usuarios as $usuario)
		{
			$comuna ='';
			$region='';
			$categorias = array();
			if(!empty($usuario['Compra']))
			{
				foreach ($usuario['Compra'] as $compra) 
				{
					foreach ($compra['Producto'] as $producto) 
					{
						if(!isset($categorias[$producto['categoria_id']]) && $producto['categoria_id'] < 5){
							$categorias[$producto['categoria_id']]= str_replace('Outlet','',$producto['ProductosCompra']['categoria']);
						}else
						{
							//pr('ya existe');
						}
					}					
				}
				$comuna = utf8_decode($comunas[$compra['Despacho']['Direccion']['comuna_id']]);
				$region = utf8_decode($regiones[$compra['Despacho']['Direccion']['region_id']]);

			}
			
				$sexo = ($usuario['Usuario']['sexo_id']==2)?'Mujer':'hombre';
				$save = array('nombre' 				=> utf8_decode($usuario['Usuario']['nombre']),
									  'apellido_paterno'	=> utf8_decode($usuario['Usuario']['apellido_paterno']),
									  'email'				=> $usuario['Usuario']['email'],
									  'sexo'				=> $sexo,
									  'rut'					=> $usuario['Usuario']['rut'],
									  'fecha_nacimiento'	=> $usuario['Usuario']['fecha_nacimiento'],
									  'categorias'			=> utf8_decode(implode('|',$categorias)),
									  'comuna'				=> $comuna,
									  'region'				=> $region
									  );

				fputcsv($fp,$save,',');

				
		}
		fclose($fp);
	}
	
	}

	function cpu()
	{
		Configure::write("debug",0);
		$numeros_cpu = 4;
		$datos;
		$uptime = shell_exec("uptime");
		$uptime = substr($uptime,(strpos($uptime,"average:") + 8));
		$tiempos= explode(",",$uptime);
		$datos["cpu1"] = $tiempos[0]*100/$numeros_cpu;
		$datos["cpu15"] = $tiempos[2]*100/$numeros_cpu;
		$memoria  = shell_exec("free");
		$memoria = nl2br($memoria);
		$arr = explode("<br />",$memoria);
		$arr2 = explode(" ",$arr[1]);
		$arr3 = explode(" ",$arr[2]);
		$arr4 = explode(" ",$arr[3]);
		$datos["memoria_utilizada"] = (int)(((int)$arr2[7] - (int)$arr3[9])/1024);
		$datos["memoria_total"] = (int)($arr2[7]/1024) ;
		$datos["swap_utlizada"] = (int)($arr4[12]/1024) ;
		$datos["swap_total"] = (int)($arr4[16]/1024) ;
		die(json_encode((array)$datos));
	}
	function estadisticas()
	{
		Configure::write("debug",0);
		$datos["Usuarios"]["Registrados"] = 1000;
		$datos["Usuarios"]["Visitas"] = 12000;
		$datos["Ventas"]["Realizadas"] = 10;
		$datos["Ventas"]["No Completadas"] = 20;

		die(json_encode((array)$datos));
	}

	function logout()
	{
		$this->Session->destroy();
		$this->Auth->logout();
		$this->redirect(array('controller' => 'productos', 'action' => 'inicio'));
	}

	function add()
	{
		//$this->Mailchimp();
		if ( ! empty($this->data) )
		{
			$this->Usuario->create();
			if ( $this->Usuario->save($this->data) )
			{
				//EMAIL
				$this->Email->smtpOptions =array(
			'port' => '587',
			'timeout' => '30',
			'auth' => true,
			'host' => 'mail.smtp2go.com',
			'username' => 'noresponder@skechers-chile.cl',
			'password' => 'eXV6M2k1cWp4Yzcw'
			);
				// DATOS DESTINATARIO
				$this->Email->to = $this->data['Usuario']['email'];
				$this->Email->subject = '[Skechers - Tienda en linea] Registro';
				$this->Email->from = 'Skechers <ventas@skechers.com>';
				//$this->Email->replyTo = 'contacto@trillonario.com.do';
				$this->Email->sendAs = 'html';
				$this->Email->template	= 'registro';
				$this->Email->delivery = 'smtp';
				$this->Email->send();
				// FIN EMAIL
				$this->Auth->login($this->data);
				$this->Session->setFlash('Tu usuario ha sido creado exitosamente.', 'default', array('class' => 'alerta-bienvenida'));
				$this->redirect(array('action' => 'bienvenida'));
			}
			else
			{
				unset($this->data['Usuario']['clave']);
				unset($this->data['Usuario']['repetir_clave']);
				$this->Session->setFlash(__('Tu Registro NO ha logrado finalizar.', true));
			}
		}
		$this->Usuario->recursive = 1;

		$sexos = array(1 => 'Mujer', 2 => 'Hombre');
		$this->set(compact('sexos'));
	}

	function bienvenida()
	{
		Configure::write('debug',0);
		$this->layout = 'ajax';
	}

	function perfil_datos()
	{
		// ----- VERIFICA LOGUEO
		if(! $this->Auth->user('') )
		{
			 $loginRedirect['controller'] = 'usuarios';
			 $loginRedirect['action'] = 'perfil_datos';
			$this->Session->write('Login.redirect', $loginRedirect);
			$this->redirect(array('action' => 'login'));
		}

		//MIS DATOS
		if ( ! empty($this->data) )
		{
			if ( $this->Usuario->save($this->data) )
			{
				$this->Session->setFlash('Sus datos se han guardado con éxito', 'default', array('class' => 'alert alert-success','rel' => 'form-datos'));
				$this->redirect(array('action' => 'perfil_datos'));
			}
			else
			{
				$this->Session->setFlash('Sus datos no pudieron ser guardados. Por favor vuelva a intentarlo', 'default', array('class' => 'alert alert-warning','rel' => 'form-datos'));
			}
		}
		if ( empty($this->data) )
		{
			$options = array(
				'conditions' => array(
					'Usuario.id' => $this->Auth->user('id')
				),
				'fields' => array(
					'Usuario.id',
					'Usuario.nombre',
					'Usuario.apellido_paterno',
					'Usuario.rut',
					'Usuario.email',
					'Usuario.fecha_nacimiento',
					'Usuario.sexo_id'
				)
			);
			$this->data = $this->Usuario->find('first',$options);
		}

		//MIS DIRECCIONES
		$options = array(
			'conditions' => array(
				'Direccion.usuario_id' => $this->Auth->user('id')
			),
			'fields' => array(
				'Direccion.id',
				'Direccion.nombre'
			),
			'recursive' => -1
		);
		$direcciones = $this->Usuario->Direccion->find('all',$options);

		//HISTORIAL
		$dias_caducidad = 120; // 120 dias
		$caducidad = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) - (60 * 60 * 24 * $dias_caducidad));
		//compras realizadas hace 120 dias
		$options = array(
			'conditions' => array(
				'Compra.usuario_id' 	=> $this->Auth->user('id'),
				'Compra.estado'		=> array(1,2,3,4,11)
			),
			'fields' => array(
				'Compra.id',
				'Compra.usuario_id',
				'Compra.estado',
				'Compra.boleta',
				'Compra.picking_number',
				'Compra.cod_despacho',
				'Compra.despachado',
				'Compra.enviado',
				'Compra.valor_despacho',
				'Compra.total',
				'Compra.boleta_pdf',
				'Compra.created',
				'Compra.modified'
			),
			'contain' => array(
				'Pago' => array(
					'fields' => array(
						'Pago.id',
						'Pago.compra_id',
						'Pago.numeroTarjeta',
						'Pago.codigo'
					)
				),
				'Producto' => array(
					'conditions' => array(
						'ProductosCompra.estado' => array(0,1)
					),
					'fields' => array(
						'Producto.id',
						'Producto.nombre',
						'Producto.foto',
						'Producto.codigo',
						'Producto.color_id'
					),
					'Color' => array(
						'fields' => array(
							'Color.id',
							'Color.nombre',
							'Color.codigo'
						)
					)
				)
			),
			'order' => array('Compra.id' => 'DESC'),
			//'limit' => 20
		);
		if ($this->Auth->user('id') == 2)
		{
			$caducidad = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) - (60 * 60 * 24 * 120));

			// $options['limit'] = 50;
			$options = array_merge($options, array(
				'conditions' => array(
					// 'Compra.id' => array(50873, 50740, 50737, 50695, 50691, 50689, 50688, 50679, 50667),
					'Compra.estado'		=> array(1,2,3,4,),
					'Compra.created >='	=> $caducidad
				),
				'limit' => 30,
				'order' => 'RAND()'
			));
		}


		/*		1 => 'Devolución del producto',
											//2 => 'Cambio de producto',
											3 => 'Cambio por talla',
											4 => 'Garantia',
										),

										*/
		$cod ='';
		if ($compras = $this->Usuario->Compra->find('all',$options))
		{
			foreach ($compras as $indexCompra => $compra)
			{
				if ($compra['Producto'])
				{
					foreach ($compra['Producto'] as $indexProducto => $producto)
					{
						if ($producto['ProductosCompra']['descuento_id'])
						{
							$options = array(
								'conditions' => array(
									'Descuento.id' => $producto['ProductosCompra']['descuento_id']
								),
								'fields' => array(
									'Descuento.id',
									'Descuento.nombre',
									'Descuento.tipo',
									'Descuento.descuento'
								),
								'recursive' => -1
							);
							if ($descuento = $this->Usuario->Compra->Producto->Categoria->Descuento->find('first',$options))
								$compras[$indexCompra]['Producto'][$indexProducto]['Descuento'] = $descuento['Descuento'];
						}
					}
				}
				/*if($compra['Compra']['cod_despacho']){
					$token = $compra['Compra']['id'].'/'.$this->Auth->user('email');
					$datos = $this->Chilexpress->generarToken($token);
					$data = $this->Chilexpress->detalle($datos);
					$cod = array(
						'detalles'=> $data
					);
				}*/
			}
		}

		$sexos = $this->Usuario->Sexo->find('list');
		$estadociviles = $this->Usuario->Estadocivil->find('list');
		$regiones = $this->Usuario->Direccion->Comuna->Region->find('list',array('fields' => array('Region.id','Region.nombre'),'order' => array('Region.id' => 'ASC')));
		$comunas = $this->Usuario->Direccion->Comuna->find('list',array('fields' => array('Comuna.id','Comuna.nombre'),'order' => array('Comuna.nombre' => 'ASC')));
		$this->set(compact('direcciones','compras','sexos','estadociviles','regiones','comunas','cod'));
	}

	function cambioclave()
	{
		// ----- VERIFICA LOGUEO
		if(! $this->Auth->user() )
			$this->redirect(array('action' => 'login'));
		if (empty($this->data) )
			$this->redirect(array('action' => 'perfil_datos', '?' => array('tab' => 'clave')));
		if (! isset($this->data['Usuario']['clave_actual']))
		{
			$this->Session->setFlash('Debe ingresar la clave actual.', 'default', array('class' => 'alert alert-warning','rel' => 'form-clave'));
			$this->redirect(array('action' => 'perfil_datos', '?' => array('tab' => 'clave')));
		}
		if (! $this->data['Usuario']['clave_actual'])
		{
			$this->Session->setFlash('Debe ingresar la clave actual.', 'default', array('class' => 'alert alert-warning','rel' => 'form-clave'));
			$this->redirect(array('action' => 'perfil_datos', '?' => array('tab' => 'clave')));
		}
		if (! isset($this->data['Usuario']['clave']))
		{
			$this->Session->setFlash('Debe ingresar la nueva clave.', 'default', array('class' => 'alert alert-warning','rel' => 'form-clave'));
			$this->redirect(array('action' => 'perfil_datos', '?' => array('tab' => 'clave')));
		}
		if (! $this->data['Usuario']['clave'])
		{
			$this->Session->setFlash('Debe ingresar la nueva clave.', 'default', array('class' => 'alert alert-warning','rel' => 'form-clave'));
			$this->redirect(array('action' => 'perfil_datos', '?' => array('tab' => 'clave')));
		}
		if (! isset($this->data['Usuario']['clave2']))
		{
			$this->Session->setFlash('Debe ingresar la repeticion de la nueva clave.', 'default', array('class' => 'alert alert-warning','rel' => 'form-clave'));
			$this->redirect(array('action' => 'perfil_datos', '?' => array('tab' => 'clave')));
		}
		if (! $this->data['Usuario']['clave2'])
		{
			$this->Session->setFlash('Debe ingresar la repeticion de la nueva clave.', 'default', array('class' => 'alert alert-warning','rel' => 'form-clave'));
			$this->redirect(array('action' => 'perfil_datos', '?' => array('tab' => 'clave')));
		}
		if ($this->data['Usuario']['clave'] != $this->data['Usuario']['clave2'])
		{
			$this->Session->setFlash('La repeticion de la clave debe ser igual a la clave nueva.', 'default', array('class' => 'alert alert-warning','rel' => 'form-clave'));
			$this->redirect(array('action' => 'perfil_datos', '?' => array('tab' => 'clave')));
		}

		$options = array(
			'conditions' => array(
				'Usuario.id' => $this->Auth->user('id'),
				'Usuario.clave' => $this->Auth->password($this->data['Usuario']['clave_actual'])
			)
		);
		if (! $usuario = $this->Usuario->find('first',$options))
		{
			$this->Session->setFlash('La clave ingresada no es correcta.', 'default', array('class' => 'alert alert-warning','rel' => 'form-clave'));
			$this->redirect(array('action' => 'perfil_datos', '?' => array('tab' => 'clave')));
		}

		$save = array(
			'id' => $this->Auth->user('id'),
			'clave' => $this->Auth->password($this->data['Usuario']['clave'])
		);
		if ($this->Usuario->save($save))
		{
			$this->Session->setFlash('Clave actualizada exitosamente.', 'default', array('class' => 'alert alert-success','rel' => 'form-clave'));
			$this->redirect(array('action' => 'perfil_datos', '?' => array('tab' => 'clave')));
		}
		$this->Session->setFlash('No fue posible actualizar su clave. Por favor intentelo nuevamente.', 'default', array('class' => 'alert alert-warning','rel' => 'form-clave'));
		$this->redirect(array('action' => 'perfil_datos', '?' => array('tab' => 'clave')));
	}

	function recuperar($email = null)
	{
		if (! empty($this->data['Usuario']['email']))
		{
			if (! isset($this->data['Usuario']['email']))
			{
				$this->Session->setFlash(__('Debes ingresar un email.', true));
				$this->redirect(array('action' => 'recuperar'));
			}
			if (! $this->data['Usuario']['email'])
			{
				$this->Session->setFlash(__('Debes ingresar un email.', true));
				$this->redirect(array('action' => 'recuperar'));
			}
			$options = array(
				'conditions' => array(
					'Usuario.email' => $this->data['Usuario']['email']
				),
				'fields' => array(
					'Usuario.id',
					'Usuario.nombre',
					'Usuario.email',
					'Usuario.sexo_id'
				)
			);
			if (! $usuario = $this->Usuario->find('first',$options))
			{
				$this->Session->setFlash(__('El email ingresado no esta registrado.<br>¡Te puedes registrar desde <a href="http://www.skechers.cl/registro">aquí</a>!', true));
				$this->redirect(array('action' => 'add'));
			}

			// nueva clave
			$clave = "";
			for ($i=0;$i<6;$i++)
			{
				(rand(1,30)%2 )? $clave .= chr(rand(65,90)) : $clave .= chr(rand(48,57));
			}
			$save = array(
				'Usuario' => array(
					'id' => $usuario['Usuario']['id'],
					'clave' => $this->Auth->password($clave)
				)
			);
			if ( $this->Usuario->save($save) )
			{
				$this->set(compact('usuario', 'clave'));

				//EMAIL


				$this->Email->smtpOptions = array(
					'transport' => 'Smtp',
			//'from' => array('noreply@skechers-chile.cl' => 'Testing'),
					'port' => '465',
					'timeout' => '30',
					'auth' => true,
					'host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
					'username' => 'AKIAZK3GISZV5N5QNTQB',
					'password' => 'BP66zMPbbcyLkCTQq4DuptpKUmF0j/7dcIIFyCJXAbe/'
			);
				// DATOS DESTINATARIO
				$this->Email->to = $usuario['Usuario']['email'];
				//$this->Email->bcc	= array('ventas@skechers-chile.cl', 'ehenriquez@andain.cl', 'pyanez@skechers.com', 'store383@skechers.com');
				$this->Email->subject = '[Skechers - Tienda en linea] Recuperacion de Contraseña';
				$this->Email->from = 'Skechers <noreply@skechers-chile.cl>';
				$this->Email->replyTo = $this->email_skechers[2];
				$this->Email->sendAs = 'html';
				$this->Email->template	= 'recuperar';
				$this->Email->delivery = 'smtp';

				if ( $this->Email->send() )
				{
					$this->Session->setFlash(__('Se ha enviado un correo con tu nueva contraseña.<br>Revisa tu email y sigue las instrucciones del correo.', true));
					$this->redirect(array('action' => 'login'));
				}
				else
				{
					$this->Session->setFlash(__('Se ha producido un error al intentar enviar el correo.<br>Por favor intentalo nuevamente.', true));
					$this->redirect(array('action' => 'recuperar',$this->data['Usuario']['email']));
				}
			}
			else
			{
				$this->Session->setFlash(__('Se produjo un error al generar tu nueva contraseña.<br>Intentalo nuevamente.', true));
				$this->redirect(array('action' => 'recuperar',$this->data['Usuario']['email']));
			}

		}
		elseif ($email)
			$this->data['Usuario']['email'] = $email;
	}

	function admin_login() { }

	function admin_logout()
	{
		$this->Session->delete("Auth.{$this->Auth->userModel}");
		$this->redirect($this->Auth->logout());
	}

	function admin_index($buscar = null)
	{
		$this->Usuario->recursive = 0;
		if ( isset($this->data['Usuario']['buscar']) && $this->data['Usuario']['buscar'] )
		{
			$this->redirect(array('action' => 'index', $this->data['Usuario']['buscar']));
		}

		if ($buscar)
		{
			$condicion = array('conditions' => array('OR' => array('Usuario.nombreCompleto LIKE' => '%' . $buscar . '%',
																   'Usuario.email LIKE' => '%' . $buscar . '%'
																   //'Usuario.apellido_paterno LIKE' => '%' . $buscar . '%',
																   //'Usuario.apellido_materno LIKE' => '%' . $buscar . '%'
																   )
													 ),
							   'order' => array('Usuario.id' => 'ASC'));
			$this->data['Usuario']['buscar'] = $buscar;
		}
		else
		{
			$condicion = array('order' => array('Usuario.id' => 'ASC'));
		}
		$this->paginate = $condicion;
		$this->set('usuarios', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => $id),
													   //'fields'		=> array(),
													   'contain'	=> array('Direccion' => array('fields' => array('Direccion.id',
																													'Direccion.usuario_id',
																													'Direccion.calle',
																													'Direccion.numero',
																													'Direccion.depto',
																													'Direccion.otras_indicaciones',
																													'Direccion.comuna_id',
																													'Direccion.telefono',
																													'Direccion.celular'),
																								  'Comuna' => array('fields' => array('Comuna.id',
																																	  'Comuna.nombre'))),
																			 'Sexo' => array('fields' => array('Sexo.id',
																											   'Sexo.nombre')),
																			 'Estadocivil' => array('fields' => array('Estadocivil.id',
																													  'Estadocivil.nombre')))
													   ));
		//$this->Usuario->recursive = 0;
		//$this->set('usuario', $this->Usuario->read(null, $id));
		$this->set(compact('usuario'));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Usuario->create();
			if ( $this->Usuario->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$this->Usuario->recursive = 1;
		$sexos = $this->Usuario->Sexo->find('list');
		$estadociviles = $this->Usuario->Estadocivil->find('list');
		$this->set(compact('sexos', 'estadociviles'));
	}

	function admin_edit($id = null)
	{
		if ( ! $id && empty($this->data) )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( ! empty($this->data) )
		{
			if (isset($this->data['Usuario']['clave']) && $this->data['Usuario']['clave'] )
				$this->data['Usuario']['clave'] = $this->Auth->password($this->data['Usuario']['clave']);
			else
				unset($this->data['Usuario']['clave']);

			if ( $this->Usuario->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		if ( empty($this->data) )
		{
			$this->data = $this->Usuario->read(null, $id);
		}
		unset($this->data['Usuario']['clave']);
		$this->Usuario->recursive = 1;
		$sexos = $this->Usuario->Sexo->find('list');
		$estadociviles = $this->Usuario->Estadocivil->find('list');
		$this->set(compact('sexos', 'estadociviles'));
	}

	function admin_delete($id = null)
	{
		die('NO SE PUEDE. SOLICITAR A SEBASTIAN');
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Usuario->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}

	// COMIENZA bethenextone
	function inicio2() {}

	function face()
	{
		if ( ! $this->Connect->user() )
			$this->redirect(array('action' => 'inicio2'));

		if ( ! empty($this->data) )
		{
			$this->data['Usuario']['id'] = $this->Auth->user('id');
			$this->Usuario->save($this->data);
			$this->FbAndain->api('POST',array(
									    'message'		=> 'Participa y gana en BETHENEXTONE ',
									   //'picture'		=> 'http://ssl.paginademo.cl/fb/mayo/nivea/blanqueador/img/img-app.png',
									   'name'			=> 'participa aquí',
									   'link' 			=> 'http://www.skechers-chile.cl/btn1/',//para linkearlo a face book  solo cambiar al fan page y listo
									   'caption'		=> 'BETHENEXTONE'
									   ));
		}
			$this->set(compact('face'));
	}
	//juegos
	function ajax_actualizar()
	{
		$this->checkLogin();
		$datos			= (isset($this->data) ? $this->data : null);
		$data			= $this->Usuario->saveAjax($datos, $this->Auth->user('id'));
		$this->set(compact('data'));
	}

	// COMPRUEBA VIA AJAX CUANTOS CREDITOS TIENE ACTUALMENTE EL USUARIO
	function ajax_creditos()
	{
		Configure::write("debug",1);
		$this->checkLogin();
		$this->Usuario->recursive	= -1;
		$usuario	= $this->Usuario->findById($this->Auth->user('id'));
		$creditos	= $usuario['Usuario']['creditos'];
		$this->set(compact('creditos'));
	}

	function checkLogin()
	{
		if ( ! $this->Auth->user() )
			echo '<script>location.reload();</script>';
	}

	function listar_lineas($id = null)
	{
		$respuesta = array('Mujer' => 0,
						   'Hombre' => 0,
						   'Niño' => 0,
						   'Niña' => 0,
						   'Performance-Mujer' => 0,
						   'Performance-Hombre' => 0
						   );
		if ($id)
		{
			$consulta = 'SELECT
							ProductosCompra.id,
							ProductosCompra.categoria
						FROM
							sitio_productos_compras AS ProductosCompra,
							sitio_compras AS Compra
						WHERE
							Compra.id = ProductosCompra.compra_id
							AND Compra.estado = 1
							AND Compra.usuario_id = '.$id;
			$lineas = $this->Usuario->Compra->query($consulta);
			if ($lineas)
			{
				foreach ($lineas as $linea)
				{
					if (isset($linea['ProductosCompra']['categoria']) && $linea['ProductosCompra']['categoria'] && isset($respuesta[$linea['ProductosCompra']['categoria']]))
					{
						$respuesta[$linea['ProductosCompra']['categoria']] = 1;
					}
				}
			}
		}
		$respuesta = implode(';',$respuesta);
		pr($respuesta);
		return $respuesta;
	}

	function app_inscripcion()
	{
		//http://localhost/leyton/catalogo-skechers/sitio/usuarios/app_inscripcion?apellido=Andain&email=andain2015@andain.cl&nombre=Andain&rut=12345678-5
		/**
		 * CONDICIONES PARA GENERAR DESCUENTO
		 * si usuario tiene rut y email, se edita la cuenta
		 * si rut existe se actualiza usuario (email, nombre etc)
		 * si no existe rut y existe email se actualiza
		 * si no existe rut ni email se crea nuevo usuario

		 * APP:
		 * 0 = valor default
		 * 1 = registrado por app
		 * 2 = registrado en pagina, actualizado con app

		 * listado de errores
			00		OK
			01		no existe campo rut
			02		campo rut vacio
			03		digito verificador invaludo
			04		no existe campo nombre
			05		campo nombre vacio
			06		no existe campo email
			07		campo email vacio
			08		email invalido
			09		usuario ya se habia registrado desde la app
			10		No fue posible guardar el registro
			11		Error en la validacion de datos desde modelo
			12		clave invalida
			13		fecha nacimiento invalida

			[url] => Array
			(
				[url] => usuarios/app_inscripcion
				[apellido] => gfdgdd
				[email] => fdsfsafas@fdasfdsa.com
				[nombre] => fsdg
				[rut] => 12345678
				[sexo_id] => 1 / 2
			)
		 */
		Configure::write('debug',1);
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: text/javascript');

		$this->loadModel('Tienda');

		/** BUSCAR TIENDA
		 * si viene la tienda desde la app la busca en BD
		 * sino, trae la primera tienda que encuentre
		 */
		$options = array(
			'fields' => array('Tienda.id'),
			'recursive' => -1
		);
		if (isset($this->params['url']['equipo']) && $this->params['url']['equipo'])
			$options['conditions'] = array('Tienda.mac' => $this->params['url']['equipo']);
		$tienda = $this->Tienda->find('first',$options);

		if ($respuesta = $this->app_validarCampos($this->params['url']))
			die(json_encode($respuesta));

		/**
		 *	NO PERMITE REGISTRAR EL RUT O EL EMAIL MAS DE 2 VECES
		 *	CUANDO HAN SIDO REGISTRADOS (PREVIAMENTE) DESDE LA APP
		*/
		$options = array(
			'conditions' => array(
				'OR' => array(
					array('Usuario.email' => $this->params['url']['email']),
					array('Usuario.rut' => $this->params['url']['rut']),
				),
				'Usuario.app' => array(1,2)
			),
			'fields' => array(
				'Usuario.id'
			),
			'recursive' => -1
		);
		if ($usuarioRegistrado = $this->Usuario->find('first',$options))
		{
			$respuesta = array(
				'estado'	=> 'ERROR',
				'cod'		=> '09',
				'detalle'	=> 'Usuario registrado'
			);
			die(json_encode($respuesta));
		}

		$usuario['Usuario'] = array(
			'rut' => $this->params['url']['rut'],
			'nombre' => $this->params['url']['nombre'],
			'app' => 1
		);
		if (isset($this->params['url']['fecha_nacimiento']) && $this->params['url']['fecha_nacimiento'] && strtotime($this->params['url']['fecha_nacimiento']))
			$usuario['Usuario']['fecha_nacimiento'] = date('Y-m-d',strtotime($this->params['url']['fecha_nacimiento']));
		if (isset($this->params['url']['sexo_id']) && $this->params['url']['sexo_id'])
			$usuario['Usuario']['sexo_id'] = $this->params['url']['sexo_id'];

		$options = array(
			'conditions' => array(
				'Usuario.email' => $this->params['url']['email']
			),
			'fields' => array(
				'Usuario.id',
				//'Usuario.email'
			),
			'recursive' => -1
		);

		if ($user = $this->Usuario->find('first',$options))
		{
			$usuario['Usuario']['id'] = $user['Usuario']['id'];
			$usuario['Usuario']['app'] = 2;
			if ($this->Usuario->save($usuario))
			{
				$codigo = $this->generar_codigo__($usuario['Usuario']['id'], $tienda['Tienda']['id'], 6);
				$mensaje = $this->generar_mensaje__($usuario['Usuario']['nombre'], $this->params['url']['email'], $codigo, 20);
			}
			else
			{
				$respuesta = array(
					'estado'	=> 'ERROR',
					'cod'		=> '10',
					'detalle'	=> 'No fue posible guardar el registro'
				);
				die(json_encode($respuesta));
			}
		}
		else
		{
			$temporal = date('Y-m-d', strtotime(date('Y-m-d')) + (60 * 60 * 24 * 5));
			$clave = $this->generar_clave__($this->params['url']['rut']);
			$usuario['Usuario']['email'] = $this->params['url']['email'];
			$usuario['Usuario']['clave'] = $this->Auth->password($clave);
			$usuario['Usuario']['temporal'] = $temporal;
			if ( isset($this->params['url']['apellido']) && $this->params['url']['apellido'] )
				$usuario['Usuario']['apellido_paterno'] = $this->params['url']['apellido'];
			$this->Usuario->set($usuario);
			if (! $this->Usuario->validates())
			{
				$respuesta = array(
					'estado' => 'ERROR',
					'cod' => '11',
					'detalle' => ''
				);
				$errores = $this->Usuario->invalidFields();
				if ($errores)
				{
					foreach ($errores as $campo => $mensaje)
					{
						if ($campo == 'email')
						{
							die(json_encode(array(
								'estado'	=> 'ERROR',
								'cod'		=> '08',
								'detalle'	=> 'email invalido'
							)));
						}
						elseif ($campo == 'nombre')
						{
							die(json_encode(array(
								'estado'	=> 'ERROR',
								'cod' 		=> '05',
								'detalle' 	=> 'nombre invalido'
							)));
						}
						elseif ($campo == 'clave')
						{
							die(json_encode(array(
								'estado'	=> 'ERROR',
								'cod' 		=> '12',
								'detalle' 	=> 'clave invalida'
							)));
						}
						elseif ($campo == 'fecha_nacimiento')
						{
							die(json_encode(array(
								'estado'	=> 'ERROR',
								'cod' 		=> '13',
								'detalle' 	=> 'fecha de nacimiento invalida'
							)));
						}
						if ($respuesta['detalle'])
							$respuesta['detalle'] = $respuesta['detalle'].', ';
						$respuesta['detalle'] = $respuesta['detalle'].str_replace('_',' ',$campo);
					}
					$respuesta['detalle'] = 'Campos invalidos: '.$respuesta['detalle'];
				}
				die(json_encode($respuesta));
			}

			$this->Usuario->create();
			if ($this->Usuario->save($usuario))
			{
				$usuario['Usuario']['id'] = $this->Usuario->id;
				$codigo = $this->generar_codigo__($usuario['Usuario']['id'], $tienda['Tienda']['id'], 6);
				$mensaje = $this->generar_mensaje__($usuario['Usuario']['nombre'], $this->params['url']['email'], $codigo, 20, $clave);
			}
			else
			{
				$respuesta = array(
					'estado'	=> 'ERROR',
					'cod'		=> '10',
					'detalle'	=> 'No fue posible guardar el registro'
				);
				die(json_encode($respuesta));
			}
		}

		//GENERAR DESCUENTO
		$descuento = array(
			'Descuento' => array(
				'nombre' => 'Descuento Registro',
				'codigo' => $codigo,
				'cantidad' => 1,
				'fecha_caducidad' => date('Y-m-d',strtotime(date('Y-m-d'))+(60*60*24*30)),
				'contador' => 0,
				'tipo' => 'POR',
				'descuento' => 20,
				'web_tienda' => 2,
				'maximo' => 1,
				'tienda_id' => $tienda['Tienda']['id']
			),
			'Categoria' => array()
		);
		$categorias_descuento = array(1,2,3,4,6,9);
		foreach ($categorias_descuento as $c_id)
			$descuento['Categoria'][] = array('categoria_id' => $c_id);

		//GUARDAR DESCUENTO
		$this->loadModel('Descuento');
		$this->Descuento->create();
		$this->Descuento->saveAll($descuento);

		//ENVIAR EMAIL
		$email = $this->params['url']['email'];
		$cco = array('govalle@andain.cl', 'cristianbm@andain.cl', 'ehenriquez@andain.cl');

		$this->enviar_email_mensaje($this->params['url']['email'], $mensaje, 'Gracias por registrarte en Skechers Chile', $cco);

		$this->guardar_log($usuario['Usuario']['id'], 'usuarios', 'app_inscripcion', 'inscripcion usuario: '.$usuario['Usuario']['id'].' >> equipo: '.$tienda['Tienda']['id'], $_SERVER['REMOTE_ADDR']);
		$respuesta = array(
			'estado' => 'OK',
			'cod' => '00',
			'detalle' => $usuario['Usuario']['id']
		);
		die(json_encode($respuesta));
	}

	private function app_validarCampos($campos = array())
	{
		//RUT
		if (! isset($campos['rut']))
		{
			return array(
				'estado'	=> 'ERROR',
				'cod' 		=> '01',
				'detalle' 	=> 'rut invalido'
			);
		}
		elseif (! $campos['rut'])
		{
			return array(
				'estado'	=> 'ERROR',
				'cod' 		=> '02',
				'detalle' 	=> 'rut invalido'
			);
		}
		elseif (! $rutValido = $this->Carro->verificarRut($campos['rut']))
		{
			return array(
				'estado'	=> 'ERROR',
				'cod' 		=> '03',
				'detalle' 	=> 'digito verificador invalido'
			);
		}
		//NOMBRE
		if(! isset($campos['nombre']))
		{
			return array(
				'estado'	=> 'ERROR',
				'cod' 		=> '04',
				'detalle' 	=> 'nombre invalido'
			);
		}
		elseif (! $campos['nombre'])
		{
			return array(
				'estado'	=> 'ERROR',
				'cod' 		=> '05',
				'detalle' 	=> 'nombre invalido'
			);
		}
		//EMAIL
		if(! isset($campos['email']))
		{
			return array(
				'estado'	=> 'ERROR',
				'cod' 		=> '06',
				'detalle' 	=> 'email invalido'
			);
		}
		elseif (! $campos['email'])
		{
			return array(
				'estado'	=> 'ERROR',
				'cod' 		=> '07',
				'detalle' 	=> 'email invalido'
			);
		}
		elseif (! $validarEmail = filter_var($campos['email'], FILTER_VALIDATE_EMAIL))
		{
			return array(
				'estado'	=> 'ERROR',
				'cod' 		=> '08',
				'detalle' 	=> 'email invalido'
			);
		}
		return false;
	}

	private function generar_clave__($rut = null, $largo = 6)
	{
		$clave = "";
		if ($rut)
		{
			$clave = substr($rut,0,4);
		}
		//for ($i=0; $i<$largo; $i++)
		//{
		//	(rand(1,30)%2 )? $clave .= chr(rand(65,90)) : $clave .= chr(rand(48,57));
		//}
		return $clave;
	}

	private function generar_mensaje__($nombre = null, $email = null, $codigo = null, $porcentaje = 20, $clave = null)
	{
		if (! in_array($porcentaje, array(20,40)))
		{
			return false;
		}
		$mensaje = '<img alt="" src="http://desarrollo.skechers-chile.cl/img/app/'.$porcentaje.'desc.jpg" width="100%">
					<br>
					<h3>'.$nombre.' gracias por inscribirte en Skechers Chile</h3>
					<hr>

					<br>
					<hr>';
		if ($clave)
		{
			$mensaje = $mensaje.'<a href="http://www.skechers.cl">Ingresa aquí</a>
								<br>
								<br>
								usuario <b>'.$email.'</b>
								<br>
								clave <b>'.$clave.'</b>
								<br>
								<br>';
		}
		//if ($porcentaje = 40)
		//{
		//	$mensaje = $mensaje.'Tu descuento ha aumentado a 40% sobre un producto, ingresando tu codigo <b>'.$codigo.'</b>';
		//}
		//else
		//{
			$mensaje = $mensaje.'Aprovecha tu descuento de 20% sobre un producto, ingresando tu codigo:<br><b>'.$codigo.'</b>';
		//}
		return $mensaje;
	}

	private function generar_codigo__($usuario_id = 0, $tienda_id = 0, $largo = 6)
	{
		$codigo = 'APP-'.$usuario_id.'SK'.$tienda_id;
		$codigo = $codigo.strtoupper(substr($this->Auth->password($codigo),10,$largo));
		return $codigo;
	}

	function trabaje()
	{
		$this->layout = 'bootstrap_trabaje';
		if (! empty($this->data))
		{
			$continuar = true;
			// validaciones !!!
			$save = array('TrabajePostulante' =>$this->data['Usuario']);
			$requeridos = array(
				'apellido_paterno',
				'nombre',
				'cargo',
				'jornada',
				'rut',
				'f_nacimiento',
				'email'
			);
			foreach ($requeridos as $requerido)
			{
				if (! isset($save['TrabajePostulante'][$requerido]))
					$continuar = false;
				elseif (! $save['TrabajePostulante'][$requerido])
					$continuar = false;
			}

			if ($validar = ereg_replace("[^0-9]", "", $save['TrabajePostulante']['rut']))
			{
				if ($save['TrabajePostulante']['d_verificador'])
				{
					$validar = number_format($validar, 0, ',', '.');
					$validar.='-'.$save['TrabajePostulante']['d_verificador'];
					unset($save['TrabajePostulante']['d_verificador']);
				}
				else
				{
					$continuar = false;
				}
				$save['TrabajePostulante']['rut'] = $validar;
			}
			else
			{
				$continuar = false;
			}
			if ($validar = $save['TrabajePostulante']['email'])
			{
				if (! filter_var($validar, FILTER_VALIDATE_EMAIL))
					$continuar = false;
			}
			else
			{
				$continuar = false;
			}
			if ($save['TrabajePostulante']['jornada'] == 'Part Time')
			{
				if ($this->data['Jornada'])
				{
					$turnos = array();
					foreach ($this->data['Jornada'] as $dia => $turno)
					{
						if ($turno['activo'] && $turno['turno'])
						{
							array_push($turnos,array($dia,$turno['turno']));
						}
					}
					$save['TrabajePostulante']['turnos'] = json_encode($turnos);
				}
			}
			if ($this->data['Carga'])
			{
				foreach ($this->data['Carga'] as $index => $carga)
				{
					if ($carga['carga'])
						$save['TrabajeCarga'][$index] = $carga;
				}
			}
			if ($experiencia = $this->data['Experiencia'])
			{
				if ($experiencia['nombre'])
					$save['TrabajeExperiencia'][0] = $experiencia;
			}
			if ($parientes = $this->data['Pariente'])
			{
				if ($parientes['nombre'])
					$save['TrabajePariente'][0] = $parientes;
			}
			if ($this->data['Referencia'])
			{
				foreach ($this->data['Referencia'] as $index => $referencia)
				{
					if ($referencia['nombre'])
						$save['TrabajeReferencia'][$index] = $referencia;
				}
			}
			if ($continuar)
			{
				$this->loadModel('TrabajePostulante');
				$this->TrabajePostulante->create();
				if ($this->TrabajePostulante->saveAll($save))
				{
					$this->Session->setFlash(__('Registro guardado correctamente', true));
					$this->redirect(array('action' => 'trabaje'));
				}
				else
				{
					$this->Session->setFlash(__('No fue posible guardar el registro. Por favor revisa tu formulario e intentalo nuevamente.', true));
				}
			}
			else
			{
				$this->Session->setFlash(__('Para continuar debes ingresar los campos requeridos. Por favor revisa tu formulario e intentalo nuevamente.', true));
			}
		}
		$jornadas = array(
			'Part Time' => 'Part Time',
			'Full Time' => 'Full Time'
		);
		$estadosCiviles = array(
			'Casado'=>'Casado',
			'Soltero'=>'Soltero',
			'Divorciado'=>'Divorciado',
			'Viudo'=>'Viudo'
		);
		$this->set(compact('jornadas','estadosCiviles'));
	}

	function Mailchimp($desde = null){
		
		if (!$desde){
			$fecha_actual = date("Y-m-d");
			$desde = date("Y-m-d",strtotime($fecha_actual."- 1 days")); 
		}

		$list_id = '743fcdd808';
		$api_key = 'd9e0dd50b6a03e5feeaa580c68528a83-us10';
		$usuarios = $this->Usuario->user($desde);
		
		$this->loadModel('Mailchimp');
		$postUser = 0;
		$putUser = 0;
		
		foreach ($usuarios as $r) {
	
			if (is_Null($r['rut']))
				$r['rut']="";
			if (is_Null($r['fecha_nacimiento']))
				$r['fecha_nacimiento']="";

			$getUser = $this->getUser($r['email'],$list_id,$api_key);

			$data = new stdClass();
			$data->email_address = $r['email'];
			$data->status = 'subscribed';
			$data->merge_fields = array(		  
									'FNAME' 		=> $r['nombre'],
									'LNAME'			=> $r['apellido_paterno'],
									'GENDER'		=> $r['sexo'],
									'RUN'			=> '16379769-0',//$r['rut'],
									'BIRTHDAY'		=> '05/11',//$r['fecha_nacimiento'],
									'CATEGORIA'		=> '2',//$r['categorias'],
									'COMUNA'		=> '',//$r['comuna'],
									'REGION'		=> 'asd'//$r['region']							
								);

			if ($getUser->status_code != 200){
				$postUser = $this->postUser($data,$list_id,$api_key);
				$putUser = 0;
			}else{
				$putUser =	$this->putUser($data,$list_id,$api_key);
				$postUser = 0;
			}
	
			if(is_object($postUser))
    			$postUser = $postUser->status_code;
    		if(is_object($putUser))
    			$putUser = $putUser->status_code;
    			
		
			$log  =  Array
			        	(
				            'email' 		=> $data->email_address,
				            'metodo_get'	=> $getUser->status_code,
				            'metodo_post' 	=> $postUser,
				            'metodo_put' 	=> $putUser,
				            'json' 			=> json_encode($data)
				            
			        	);
			$this->Mailchimp->saveAll($log);

		}
		die;
	
	}
	function getUser($email,$list_id,$api_key){
		
		$data_center = substr($api_key,strpos($api_key,'-')+1);
		$url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members/'.$email.'';
		 
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "GET");
		$result = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$respuesta = json_decode(curl_exec($ch),true);
		curl_close($ch);

		$respuesta = new stdClass();
		$respuesta->status_code = $status_code;
		$respuesta->datos = $result;

		return $respuesta;
	}
	function postUser($data,$list_id,$api_key){

		$data_center = substr($api_key,strpos($api_key,'-')+1);
		$url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members';

		$json = json_encode($data);
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		$result = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$respuesta = json_decode(curl_exec($ch),true);
		curl_close($ch);

		$respuesta = new stdClass();
		$respuesta->status_code = $status_code;
		$respuesta->datos = $result;

		return $respuesta;

	}
	function putUser($data,$list_id,$api_key){

		$data_center = substr($api_key,strpos($api_key,'-')+1);
		$url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members/'.$data->email_address.'';
		
		foreach ($data->merge_fields as $key => $value) {
			if($value == '' or $value == null)
				unset($data->merge_fields[$key]);
		}

		$json = json_encode($data);
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		$result = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$respuesta = json_decode(curl_exec($ch),true);
		curl_close($ch);
		
		$respuesta = new stdClass();
		$respuesta->status_code = $status_code;
		$respuesta->datos = $result;

		return $respuesta;
	}
	function solicitar_devolucion()
	{
		if(! $this->Auth->user('') )
		{
			$loginRedirect['controller'] = 'usuarios';
			$loginRedirect['action'] = 'perfil_datos';
			$this->Session->write('Login.redirect', $loginRedirect);
			$this->redirect(array('action' => 'login'));
		}

		//$this->set(compact('');
	}
}
?>
