<?php
class Compra extends AppModel
{
	// CONFIGURACION DB
	var $name			= 'Compra';
	var $displayField	= 'neto';

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
		'despacho_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'usuario_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'subtotal' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'iva' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'neto' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
		'pago_id' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'last'			=> true,
				//'message'		=> 'Mensaje de validación personalizado',
				//'allowEmpty'	=> true,
				//'required'		=> false,
				//'on'			=> 'update', // Solo valida en operaciones de 'create' o 'update'
			),
		),
	);

	// ASOCIACIONES
	var $belongsTo = array(
		'Despacho' => array(
			'className'				=> 'Despacho',
			'foreignKey'			=> 'despacho_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Despacho')
		),
		'Usuario' => array(
			'className'				=> 'Usuario',
			'foreignKey'			=> 'usuario_id',
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'counterCache'			=> false,
			'counterScope'			=> array('Asociado.modelo' => 'Usuario')
		),
        'Empresa' => array(
            'className'				=> 'Empresa',
            'foreignKey'			=> 'empresa_despacho_id',
            'conditions'			=> '',
            'fields'				=> '',
            'order'					=> '',
            'counterCache'			=> false,
            'counterScope'			=> array('Asociado.modelo' => 'Empresa')
        ),

	);
	var $hasMany = array(
		'Detalle' => array(
			'className'				=> 'Detalle',
			'foreignKey'			=> 'compra_id',
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
		'Pago' => array(
			'className'				=> 'Pago',
			'foreignKey'			=> 'compra_id',
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
		'Descripcion' => array(
			'className'				=> 'Descripcion',
			'foreignKey'			=> 'compra_id',
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
		'Comentario' => array(
			'className'				=> 'Comentario',
			'foreignKey'			=> 'compra_id',
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
		'Boleta' => array(
			'className'				=> 'Boleta',
			'foreignKey'			=> 'compra_id',
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
		'Devolucion' => array(
			'className'				=> 'Devolucion',
			'foreignKey'			=> 'compra_id',
			'dependent'				=> false,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'exclusive'				=> '',
			'finderQuery'			=> '',
			'counterQuery'			=> ''
		)
	);
	var $hasAndBelongsToMany = array(
		'Producto' => array(
			'className'				=> 'Producto',
			'joinTable'				=> 'productos_compras',
			'foreignKey'			=> 'compra_id',
			'associationForeignKey'	=> 'producto_id',
			'unique'				=> true,
			'conditions'			=> '',
			'fields'				=> '',
			'order'					=> '',
			'limit'					=> '',
			'offset'				=> '',
			'finderQuery'			=> '',
			'deleteQuery'			=> '',
			'insertQuery'			=> ''
		)
	);

	function boletas_pdf($ruta_inicial =null,$ruta_final = null)
	{
		Configure::write('debug',2);
		if($ruta_inicial)
			$dir = $ruta_inicial;
		else
			$dir = "D:/xampp/htdocs/skechers/archivos/boletas";
		if(!$ruta_final)
			$ruta_final = 'D:/xampp/htdocs/skechers/archivos/boletas';
		print_r(compact('dir','ruta_final'));
		$pdfs = array();
		$ffs = scandir($dir);
		unset($ffs[array_search('.', $ffs, true)]);
    	unset($ffs[array_search('..', $ffs, true)]);
    	if (count($ffs) < 1)
			return;
    	foreach($ffs as $ff)
    	{
        	$pdfs[]=$ff;
        }


        foreach ($pdfs as $pdf) 
        {

        	$arr = explode('.',$pdf);
        	$orden = $arr[0];
        	$compra = $this->find('first', array('conditions' => array('id' => $orden)));
        	if($compra)
        	{
        		 	if($compra['Compra']['boleta_pdf'] =='')
        		 	{
        		 		$this->correo_boleta($orden,'sebastian@sancirilo.cl',$dir.DS.$pdf);
        		 		$boleta_nombre = substr(md5(time().$orden), 5,20).'.pdf';
        		 		if(copy($dir.DS.$pdf, $ruta_final.DS.$boleta_nombre))
        		 		{
        		 			$guardar = array('id' => $compra['Compra']['id'],
        		 							 'boleta_pdf' => $boleta_nombre);
        		 			$this->save($guardar);
        		 		}
        		 	}else{

        		 	}
        	}
        	else
        		print_r('no');
        }
        prx($pdfs);

	}
	private function correo_boleta($compra_id,$email,$boleta)
	{
		return true;
		$this->Email->smtpOptions =array(
			'port' => '587',
			'timeout' => '30',
			'auth' => true,
			'host' => 'mail.smtp2go.com',
			'username' => 'noresponder@skechers-chile.cl',
			'password' => 'eXV6M2k1cWp4Yzcw'
			);
		// DATOS DESTINATARIO (CLIENTE)
			$copias = array(
				'solanger@skechers.com',
				'rsilva@skechers.com',
			);
			$compra_id = $this->data['Compra']['id'];
			$this->Email->to = $email;
			//$this->Email->to = 'ehenriquez@andain.cl';
			//$this->Email->bcc	= $copias;
			$this->Email->subject = '[Skechers - Tienda en linea] Boleta Compra' .  $compra_id;
			$this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
			$this->Email->replyTo = $this->email_skechers[2];
			$this->Email->sendAs = 'html';
			$this->Email->template	= 'devolucion';
			$this->Email->delivery = 'smtp';
			$this->Email->attachments = array($boleta);
			if ($this->Email->send())
			{
			}
	}
	public function revisarHydee($file)
	{

		$ordenes_hydee = array(834246,834234,834229,834225,834220,834216,834213,834210,834203,834199,834198,834194,834193,834190,834189,834187,834184);

	$ordenes = $this->find('list',array('conditions' => array(
											'local' => 0,
											'estado >' => 0,
											'id >=' => $ordenes_hydee[count($ordenes_hydee)-1],
											'id <=' => $ordenes_hydee[0]),
											'fields' => array('id','id')));
	pr(count($ordenes));
	foreach($ordenes_hydee as $orden)
	{

		if(isset($ordenes[$orden]))
		{
			unset($ordenes[$orden]);
		}
	}
	prx($ordenes);

	prx(count($ordenes));					
	}
}
?>