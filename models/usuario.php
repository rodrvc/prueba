<?php
class Usuario extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Usuario';
	var $displayField	= 'email';
	var $virtualFields = array('nombreCompleto' => 'CONCAT(Usuario.nombre, " ", Usuario.apellido_paterno)');
	

	// BEHAVIORS
	/*
	var $actsAs			= array(// SLUGS
								'Sluggable'	=> array('label' => 'nombre', 'overwrite' => true, 'length' => 120, 'translation' => 'utf-8'),

								// IMAGE UPLOAD
								'Image'		=> array('fields' =>
													 array('imagen' => array('versions' => array(array('prefix'	=> 'mini',
																									   'width'	=> '200',
																									   'height'	=> '200',
																									   'crop'	=> true
																									   )
																								 ),
																			 'image_types'	=> array('jpg', 'jpeg', 'gif', 'png')
																			 )
														   )
													 )
								);
	*/

	// VALIDACIONES
	var $validate = array(
		'facebook_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'nombre' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				'message'		=> 'Ingrese su nombre.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'apellido_paterno' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				'message'		=> 'Ingrese su apellido.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'region_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				'message'		=> 'Selecciona tu región de residencia',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'comuna_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				'message'		=> 'Selecciona tu comuna de residencia',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'sexo_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Ingrese su apellido materno',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'estadocivil_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'email' => array(
			'email' => array(
				'rule'			=> array('email'),
				'last'			=> true,
				'message'		=> 'Ingrese su correo electrónico.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
			'isUnique' =>array(
				'rule'			=> array('isUnique'),
				'last'			=> true,
				'message'		=> 'Este email ya esta registrado.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),

		'email2' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				'message'		=> 'Repita su Email.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
			'repetir' => array(
				'rule'			=> array('validarEmail'),
				'last'			=> true,
				'message'		=> 'Debe repetir el mismo E-mail.'
			)
		),
		'creditos' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'clave' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				'message'		=> 'Ingrese su contraseña.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			)
		),
		'repetir_clave' => array(
			'notempty' => array(
				'rule'			=> array('notempty'),
				'last'			=> true,
				'message'		=> 'Repita su contraseña.',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
			'repetir' => array(
				'rule'			=> array('validarClave'),
				'last'			=> true,
				'message'		=> 'Debe repetir la misma clave.'
			),
			'between' => array(
				'rule'			=> array('between', 5, 20),
				'last'			=> true,
				'message'		=> 'Configuración clave 5 a 20 caracteres.'
			)
		),
		'fecha_nacimiento' => array(
			'date' => array(
				'rule'			=> array('date'),
				'last'			=> true,
				'message'		=> 'Ingrese su fecha de nacimiento.',
				//'allowEmpty'	=> true,
				'required'		=> false,
				//'on'			=> 'create', // Solo valida en operaciones de 'create' o 'update'
			)
		),
		);
	
	/**
	 * Validador: Comprueba que la clave y su repeticion (datos de login) sean identicas
	 */
	function validarClave($data)
	{
		return (AuthComponent::password($data['repetir_clave']) === $this->data[$this->name]['clave']);
	}
	function validarEmail($data)
	{
		return (($data['email2']) === $this->data[$this->name]['email']);
	}
	


	// ASOCIACIONES
	var $belongsTo = array(
		'Sexo' => array(
			'className'				=> 'Sexo',
			'foreignKey'			=> 'sexo_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Sexo')
		),
		'Estadocivil' => array(
			'className'				=> 'Estadocivil',
			'foreignKey'			=> 'estadocivil_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Estadocivil')
		),
		'Region' => array(
			'className'				=> 'Region',
			'foreignKey'			=> 'region_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Region')
		),
		'Comuna' => array(
			'className'				=> 'Comuna',
			'foreignKey'			=> 'comuna_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Comuna')
		)
	);
	var $hasMany = array(
		'Comentario' => array(
			'className'				=> 'Comentario',
			'foreignKey'			=> 'usuario_id',
			'dependent'				=> false,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'exclusive'				=> '',
			'finderQuery'			=> '',
			'counterQuery'			=> ''
		),
		'Compra' => array(
			'className'				=> 'Compra',
			'foreignKey'			=> 'usuario_id',
			'dependent'				=> false,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'exclusive'				=> '',
			'finderQuery'			=> '',
			'counterQuery'			=> ''
		),
		'Despacho' => array(
			'className'				=> 'Despacho',
			'foreignKey'			=> 'usuario_id',
			'dependent'				=> false,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'exclusive'				=> '',
			'finderQuery'			=> '',
			'counterQuery'			=> ''
		),
		'Direccion' => array(
			'className'				=> 'Direccion',
			'foreignKey'			=> 'usuario_id',
			'dependent'				=> false,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'exclusive'				=> '',
			'finderQuery'			=> '',
			'counterQuery'			=> ''
		),
		'Log' => array(
			'className'				=> 'Log',
			'foreignKey'			=> 'usuario_id',
			'dependent'				=> false,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'exclusive'				=> '',
			'finderQuery'			=> '',
			'counterQuery'			=> ''
		),
	);
	function user($desde){

		$desde = $desde;
		$usuarios = $this->find('all', array(
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
												)
											)
										);

		$regiones = $this->Region->find('list', array('fields' => array('id','nombre')));
		$comunas = $this->Comuna->find('list', array('fields' => array('id','nombre')));

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
						}
					}					
				}

				$comuna = utf8_decode($comunas[$compra['Despacho']['Direccion']['comuna_id']]);
				$region = utf8_decode($regiones[$compra['Despacho']['Direccion']['region_id']]);

			}
			
				$sexo = ($usuario['Usuario']['sexo_id']==2)?'Mujer':'hombre';
				$data[] = array(		  
									 'nombre' 				=> utf8_decode($usuario['Usuario']['nombre']),
									 'apellido_paterno'		=> utf8_decode($usuario['Usuario']['apellido_paterno']),
									 'email'				=> $usuario['Usuario']['email'],
									 'sexo'					=> $sexo,
									 'rut'					=> $usuario['Usuario']['rut'],
									 'fecha_nacimiento'		=> $usuario['Usuario']['fecha_nacimiento'],
									 'categorias'			=> utf8_decode(implode('|',$categorias)),
									 'comuna'				=> $comuna,
									 'region'				=> $region
									 
									 );
		
		}

		return $data;
	}
}
?>