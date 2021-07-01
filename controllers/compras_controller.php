<?php
class ComprasController extends AppController
{
    var $name = 'Compras';
    var $directiorio_boletas = 'D:/xampp/htdocs/skechers/archivos/boletas2';

    function beforeRender()
    {
        parent::beforeRender();
        //LAYOUT EXCEL
        if ( $this->params['action'] == 'admin_estado_devoluciones' ||$this->params['action'] == 'admin_listado_picks' || $this->params['action'] == 'admin_imprimir_despacho' || $this->params['action'] == 'admin_excel' || $this->params['action'] == 'admin_excel2' ||  $this->params['action'] == 'admin_excel3' || $this->params['action'] == 'admin_exportar_compras_seleccionadas' || $this->params['action'] ==  'admin_reporte_excel' )
            $this->layout = 'ajax';
    }
    private function validar_api($headers)
    {
        if(!isset($headers) || !isset($headers['Authorization']))
            return false;
        if($headers['Authorization'] =='9a498bb659f5329c0358')
            return true;
        else
            return false;
    }

    function order_list()
    {
        $headers = getallheaders();
        if(!$this->validar_api($headers))
            die(json_encode(array('result' =>'error -> Api Key')));
        $respuesta = json_decode(file_get_contents('php://input'), true);
        if(!(isset($respuesta['userid']) || isset($respuesta['email']) ))
        {
            die(json_encode(array('result' =>'error -> Missing Email/UserId Error')));

        }else{
            if(!isset($respuesta['userid']))
                $respuesta['userid'] = null;
             if(!isset($respuesta['email']))
                $respuesta['email'] = null;

            $compras = $this->Compra->Usuario->find('first', array(
                                                        'fields' => array(
                                                            'Usuario.id'
                                                  //           'Productoimagen'
                                                            ),
                                                        'conditions' => array(
                                                                'or' => array(
                                                                        'id' => $respuesta['userid'], 
                                                                        'email' => $respuesta['email']
                                                                    )
                                                            ),
                                                        'contain' => array(
                                                            'Compra' => array(
                                                                'fields' => array(
                                                                    'Compra.created',
                                                                    'Compra.estado',
                                                                    'Compra.despachado',
                                                                    'Compra.total',
                                                                    'Compra.id'
                                                                    ),
                                                                'limit' => 20,
                                                                'order' => array('Compra.id' => 'desc'),
                                                                'conditions' => array('estado >' => 0),
                                                                'Producto' => array(
                                                                    'fields' =>array(
                                                                        'Producto.foto',
                                                                        'Producto.id')
                                                                    )
                                                                )

                                                            )
                                                        )
                                                    );
            if(!$compras)
                die(json_encode(array('result' =>array())));

            $venta =array();
            $url='https://d1d6stsa4vf3p.cloudfront.net/img/Producto/';
            foreach ($compras['Compra'] as $compra) 
            {
                $estado = null;
                if($compra['estado'] > 2)
                    $estado = 'RTNC';
                elseif($compra['despachado'])
                    $estado = 'SHIPPED';
                else if($compra['estado'] ==1)
                    $estado = 'NEW';
                $estados = array($estado);
                $imagenes = array();

                foreach ($compra['Producto'] as $producto)
                {
                    $imagenes[]=$url.$producto['id'].'/full_'.$producto['foto'];
                }
                $venta[]= array('timestamp' => strtotime($compra['created']),
                                    'statuses' => $estados,
                                    'total'     => $compra['total'],
                                    'orderid'   => $compra['id'],
                                    'ordertype' => 'WEB',
                                    'points'    =>0,
                                    'images' => $imagenes);
            }
            $respuesta = array('result' => array('webOrders' => $venta));
            Configure::write('debug',0);
            die(json_encode($respuesta));
            //die(json_encode();
        }
    }
   function order_detail($orderId)
   {
        Configure::write('debug',2);
        if (!function_exists('getallheaders')) 
        {
           function getallheaders() {
           $headers = [];
           foreach ($_SERVER as $name => $value) {
           if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
                return $headers;
            }
        }
        $headers = getallheaders();
        $respuesta = json_decode(file_get_contents('php://input'), true);
        if(!(isset($respuesta['userid']) || isset($respuesta['email']) ))
        {
            die(json_encode(array('result' =>'error -> Missing Email/UserId Error')));
        }else{
            if(!isset($respuesta['userid']))
                $respuesta['userid'] = null;
             if(!isset($respuesta['email']))
                $respuesta['email'] = null;
       
        }
        if(!$this->validar_api($headers))
            die(json_encode(array('result' =>'error -> Api Key')));
        if($respuesta['userid'] == null)
        {
        $compra = $this->Compra->find('first', array(
                                                'conditions' => array(
                                                    'Compra.id' => $orderId
                                                
                                                ),
                                                'contain' => array('Usuario',
                                                                    'Despacho' => 'Direccion',
                                                                    'Pago',
                                                                    'Producto' => array('Color','ProductosCompra'
                                                                    )
                                                                )
                                            )
                                    );
        }else{
            $compra = $this->Compra->find('first', array(
                                                'conditions' => array(
                                                    'Compra.id' => $orderId,
                                                    'Compra.usuario_id' => $respuesta['userid']                                                
                                                ),
                                                'contain' => array('Usuario',
                                                                    'Despacho' => 'Direccion',
                                                                    'Pago',
                                                                    'Producto' => array('Color','ProductosCompra'
                                                                    )
                                                                )
                                            )
                                    );
        }
        if(!$compra)
        {
            die(json_encode(array('result' =>'error -> Order Not Found')));
       }else{
            if($respuesta['email'] != null && $compra['Usuario']['email'] != $respuesta['email'])
            {
                die(json_encode(array('result' =>'error -> Email Not Found')));
            }

            $estado = null;
            if($compra['Compra']['estado'] > 2)
                $estado = 'RTNC';
            elseif($compra['Compra']['despachado'])
                $estado = 'SHIPPED';
            else if($compra['Compra']['estado'] ==1)
                $estado = 'NEW';
            $retornable = false;
            $dias_caducidad = 120; // 120 dias
            $caducidad = strtotime(date('Y-m-d H:i:s')) - (60 * 60 * 24 * $dias_caducidad);
            if ( $caducidad > 0)
                $retornable = true;
            $zapato = true;
            $productos = array();
            $fecha_despacho = strtotime($compra['Compra']['created']);
            foreach ($compra['Producto'] as $producto) 
            {
                $division ='';
                if(isset($producto['division']))
                    $division = $producto['division'];

                $producto = array(

                            'skutype' => null, //duda
                            'productname' => $producto['nombre'],
                            'shipdate' =>  $fecha_despacho,
                            'shipstorenum' => 611,
                            'itemtaxes' => array( 'tax' => 19, 'taxtype' => 'IVA'),
                            'isCustomerReturnable' => $retornable,
                            'isCustomerCancellable' => null,
                            'isShoes' => $zapato,
                            'id' =>  $compra['Compra']['id'],
                            'skunumber' => $producto['ProductosCompra']['sku'],
                            'skuvalue' => $producto['ProductosCompra']['talla'],
                            'unitprice' => $producto['ProductosCompra']['valor'],
                            'regularunitprice' => $producto['ProductosCompra']['valor'],
                            'quantity' => 1,
                            'stylecode' => $producto['codigo'],
                            'colorcode' => $producto['Color']['codigo'],
                            'color' => $producto['Color']['codigo'],
                            'trackingnumber' => $compra['Compra']['cod_despacho'],
                            'trackinglink' => null,
                            'orderstatus' => $estado,
                            'itemdiscountamount' => 0,
                            'itemtotalprice' => $producto['ProductosCompra']['valor'],
                            'itemshippingcost' => 0,
                            'categorydiscountamount' => null,
                            'refundamount' => null,
                            'divisioncode' => $division
                            );
                $productos[]=$producto;
            }
       
            $respuesta = array( 'orderid' => $compra['Compra']['id'],
                                'locale' => 'es_CL',
                                'parentid' => null,
                                'storenum' => 611,
                                'customerid' => -3,
                                'externaluserid' => $compra['Usuario']['id'],
                                'emailaddress' => $compra['Usuario']['email'],
                                'billing_fname' => $compra['Usuario']['nombre'],
                                'billing_lname' => $compra['Usuario']['apellido_paterno'],
                                'billing_address1' => $compra['Despacho']['Direccion']['calle'].' '.$compra['Despacho']['Direccion']['numero'],
                                'billing_address2' => $compra['Despacho']['Direccion']['depto'],
                                'billing_city' => '',
                                'billing_zip' => null,
                                'billing_state' => '',
                                'billing_country' => 'CL',
                                'billing_phone' =>$compra['Despacho']['Direccion']['celular'],
                                'shipping_fname' => $compra['Usuario']['nombre'],
                                'shipping_lname' => $compra['Usuario']['apellido_paterno'],
                                'shipping_address1' => $compra['Despacho']['Direccion']['calle'].' '.$compra['Despacho']['Direccion']['numero'],
                                'shipping_address2' => $compra['Despacho']['Direccion']['depto'],
                                'shipping_city' => '',
                                'shipping_zip' => null,
                                'shipping_state' => '',
                                'shipping_country' => 'CL',
                                'shipping_phone' => $compra['Despacho']['Direccion']['celular'],
                                'shippingmethod' => 'Standard',
                                'shippingdescription' => '"Standard Shipping (3-7 days)',
                                'shippingcost' => 0,
                                'tax' => $compra['Compra']['iva'],
                                'taxrate' => 19,
                                'currencycode' => 'CL',
                                'ordertimestamp' => strtotime($compra['Compra']['created']),
                                'subtotal' =>  $compra['Compra']['subtotal'],
                                'total' =>  $compra['Compra']['total'],
                                'hasShippedItems' =>  $compra['Compra']['despachado'],
                                'hasInvoice' => $compra['Compra']['boleta'],
                                'isFreeOrder' => false,
                                'inReturnGracePeriod' =>$retornable, 
                                'numOfInstallments' => $compra['Pago'][0]['cuotas'],
                                'vatid' => null,
                                'orderitemwrites' => $productos,
                                'paymentwrites' => array(
                                    'paymentid' =>  $compra['Pago'][0]['id'],
                                    'accountnumber' =>  $compra['Pago'][0]['numeroTarjeta'],
                                    'paymenttype' => $compra['Pago'][0]['estado'],
                                    'ccexpmonth' => null,
                                    'ccexpyear' => null,
                                    'cctype' => null,
                                    'activeok' => null,
                                    'totalrefundamount' => null,
                                    'totalchargedamount' => $compra['Compra']['total'])
                            );
          $respuesta = array('result' => $respuesta);
          Configure::write('debug',0);
          die(json_encode($respuesta));
        }
    }
       

    function test()
    {
        prx($this->Chilexpress->actualizarOficinas());
    }
    private  function anular_compra($id)
    {
        $pago = $this->Compra->Pago->find('first', array('conditions' =>array( 'compra_id' => $id, 'anulado' => 0)));
        if($pago)
        {
            //pr($this->certificate['commerce_code'] );
            $datos_tbk = $this->tbkParams;
            App::import('Vendor', 'autoload', array('file' => 'libwebpay'.DS.'webpay.php'));
            $configuration = new Configuration();
            $configuration->setEnvironment($this->certificate['environment']);
            $configuration->setCommerceCode($this->certificate['commerce_code']);
            $configuration->setPrivateKey($this->certificate['private_key']);
            $configuration->setPublicCert($this->certificate['public_cert']);
            $configuration->setWebpayCert($this->certificate['webpay_cert']);
            $sessionId = uniqid();
            $webpay = new Webpay($configuration);
          //  
            $monto = (int)$pago['Pago']['monto'] ;
            $merchant = (float)$this->certificate['commerce_code'];
            $cod_autorizacion = (int) $pago['Pago']['codAutorizacion'];
            $compra_id = (int) $pago['Pago']['compra_id'];
            $result = $webpay->getNullifyTransaction()->nullify($cod_autorizacion,$monto,$compra_id,$monto,$merchant );
            //pr($result);
   

            //prx(compact('monto','merchant','compra_id','cod_autorizacion'));
            //$result = $webpay->getNullifyTransaction()->nullify(1213,32990,12,32990,597020000541 );
           // var_dump($result['error']);
            if(isset($result->authorizationCode))
            {
                $guardar =array('id' => $pago['Pago']['id'],
                                'anulacion_codigo' => $result->authorizationCode,
                                'anulacion_token'  => $result->token,
                                'anulacion_fecha'  => $result->authorizationDate,
                                'anulacion_monto'  => $result->nullifiedAmount);
                $this->Compra->Pago->save($guardar);
                return array('procesada' => true);
            }else{
                return array('procesada' => false, 'error' => $result['detail']);
            }
        }else{
            return array('procesada' => false, 'error' => 'No existe Orden');

        }
    }
    function boletas_pdf($ruta_inicial =null,$ruta_final = null)
    {
        die('aca');

        if($ruta_inicial)
            $dir = $ruta_inicial;
        else
            $dir = "D:/xampp/htdocs/skechers/archivos/boletas";
        if($ruta_final)
            $this->set_ruta_boletas($ruta_final);

        error_reporting(8);
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
            $compra = $this->Compra->find('first', array('conditions' => array('id' => $orden)));
            if($compra)
            {
                    if($compra['Compra']['boleta_pdf'] =='')
                    {
                        $this->correo_boleta($orden,'sebastian@sancirilo.cl',$dir.DS.$pdf);
                        $boleta_nombre = substr(md5(time().$orden), 5,20).'.pdf';
                        if(copy($dir.DS.$pdf, $directiorio_boletas.DS.$boleta_nombre))
                        {
                            $guardar = array('id' => $compra['Compra']['id'],
                                             'boleta_pdf' => $boleta_nombre);
                            $this->Compra->save($guardar);
                        }
                    }
            }else
                $compra = $this->Compra->find('first', array('conditions' => array('mv_orden' => $orden)));
                if($compra)
                {
                        if($compra['Compra']['boleta_pdf'] =='')
                        {
                            $this->correo_boleta($orden,'sebastian@sancirilo.cl',$dir.DS.$pdf);
                            $boleta_nombre = substr(md5(time().$orden), 5,20).'.pdf';
                            if(copy($dir.DS.$pdf, $directiorio_boletas.DS.$boleta_nombre))
                            {
                                $guardar = array('id' => $compra['Compra']['id'],
                                                 'boleta_pdf' => $boleta_nombre);
                                $this->Compra->save($guardar);
                            }
                        }
                }else{
                }
            }
      
        }
       


    function descargar_boleta($compra_id)
    {
        if(! $this->Auth->user() )
        {
            $this->redirect(array('controller' => 'usuarios', 'action' => 'perfil_datos'));
        }
        $usuario_id = ($this->Auth->user('id'));


        $compra = $this->Compra->find('first', array('conditions' => array('id' => $compra_id)));
        if($compra['Compra']['usuario_id']!= $usuario_id)
        {
            $this->redirect(array('controller' => 'usuarios', 'action' => 'perfil_datos'));
        }
        $archivo= $compra['Compra']['id'].'.pdf';
        $ruta =$this->directiorio_boletas.DS.$compra['Compra']['boleta_pdf'];
        $this->set(compact('ruta','archivo'));
        //	if(file_exists($this->directiorio_boletas.DS.$compra['Compra']['boleta_pdf']))
    }
    function admin_reporte()
    {

    }
    function admin_reporte_excel()
    {
        if (isset($this->data['Compra']['consulta_fecha1']) && $this->data['Compra']['consulta_fecha1'])
            $fecha1 = $this->data['Compra']['consulta_fecha1'];
        else
            $fecha1 = date('Y-m-d');

        if (isset($this->data['Compra']['consulta_fecha2']) && $this->data['Compra']['consulta_fecha2'])
            $fecha2 = $this->data['Compra']['consulta_fecha2'];
        else
            $fecha2 = date('Y-m-d');
        $imprimir = array();

        $fields = array(
            'Compra.id',
            'Compra.numId',
            'Compra.created',
            'Compra.boleta',
            'Compra.numId',
            'Compra.ip',
            'Compra.boleta_pdf',
            'Compra.cod_despacho',
            'Compra.picking_number',


        );

        $options = array(
            'conditions' => array(
                'Compra.created >=' => $fecha1.' 00:00:00',
                'Compra.created <=' => $fecha2.' 23:59:59',
                'Compra.estado' => 1

            ),
            'fields' => $fields,
            'recursive' => -1,
            'order' => array(
                'Compra.id' => 'ASC',
            ),
        );
        $ventas = $this->Compra->find('all',$options);
        //prx($ventas);
        $this->set(compact('ventas', 'imprimir'));
    }
    function admin_descargar_boleta($compra_id)
    {
        $compra = $this->Compra->find('first', array('conditions' => array('id' => $compra_id)));
        $archivo= $compra['Compra']['id'].'.pdf';
        $ruta =$this->directiorio_boletas.DS.$compra['Compra']['boleta_pdf'];
        $this->set(compact('ruta','archivo'));
        //	if(file_exists($this->directiorio_boletas.DS.$compra['Compra']['boleta_pdf']))
    }
    function correccion()
    {
        $dir = "E:/test";
        $pdfs = array();
        $ffs = scandir($dir);
        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);
        unset($ffs[array_search('new', $ffs, true)]);

        if (count($ffs) < 1)
            return;
        foreach($ffs as $ff)
        {
            $pdfs[]=$ff;
        }
        $i =1;
        foreach ($pdfs as $pdf)
        {
            $i++;
            $nombre = '';

            $nombre = 'Revision '.substr($pdf,9);
            copy($dir.DS.$pdf, $dir.DS.'new/'.$nombre);
            //if($i==5)
            //	prx($dir.DS.'new/'.$nombre);

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
    function admin_sinpicking()
    {
        $limit = 30;
        if ($this->Session->check('ComprasPorPagina'))
            $limit = $this->Session->read('ComprasPorPagina');
        // COMPRAS POR DESPACHAR
        $tres_dias = $this->Carro->dias_habiles(3);
        $cinco_dias = $this->Carro->dias_habiles(5);
        $this->paginate = array(
            'conditions' => array(
                'Compra.estado' => 1,
                'Compra.picking_number' => null,
                'Compra.local' => 0,
                'Compra.created <' => $tres_dias


            ),
            'contain' => array(
                //'Despacho' => array('Direccion'),
                'Usuario',
                //'Pago'
            ),
            'limit' => $limit,
            'order' => array('Compra.created' => 'DESC')
        );
        // CONDICIONADORES PARA MOSTRAR ESTADO DE DESPACHO SEGUN FECHA DE CREACION DE LA COMPRA
        //$compras =  $this->paginate();
        //prx($compras);
        $compras = $this->paginate();
        //prx($compras);
        $this->set('compras',$compras);
        $this->set(compact('tres_dias', 'cinco_dias'));
        $this->render('admin_pagadas');

    }


    function generar_reportes()
    {
        $hoy = strtotime(date('d-m-Y H:i:s'));
        //$hoy = strtotime('2015-01-21 00:00:00');
        $ayer = strtotime(date('d-m-Y',$hoy)) - (60*60*24*1);
        $reporte = array(
            'total' => 0,
            'exito' => 0,
            'fracaso' => 0,
            'monto_total' => 0,
            'total_productos' => 0,
            'productos' => array()
        );
        $options = array(
            'conditions' => array(
                'Compra.created >=' => date('Y-m-d',$ayer).' 00:00:00',
                'Compra.created <=' => date('Y-m-d',$ayer).' 23:59:59',
                'Compra.estado'		=> 1
            ),
            'fields' => array(
                'Compra.id',
                'Compra.total',
                'Compra.estado',
                'Compra.despacho_id'
            ),
            'contain' => array(
                'Producto' => array(
                    'fields' => array(
                        'Producto.id',
                        'Producto.nombre',
                        'Producto.precio',
                        'Producto.codigo',
                        'Producto.codigo_completo',
                        'Producto.division',
                        'Producto.sku'
                    ),
                    'Color',
                    'Categoria',
                ),
                'ProductosCompra'=> array(
                    'fields' => array(
                        'ProductosCompra.id',
                        'ProductosCompra.talla',
                        'Producto.valor'
                    )
                ),
            )
        );
        $compras = $this->Compra->find('all',$options);
        $datos_compras = $datos_despacho = array();
        if($compras)
        {
            foreach ($compras as $compra)
            {
                $despacho = $this->Compra->Despacho->findById($compra['Compra']['despacho_id']);
                $this->Compra->Despacho->Direccion->recursive = 1;
                $direccion = $this->Compra->Despacho->Direccion->findById($despacho['Despacho']['direccion_id']);
                foreach ($compra['Producto'] as $producto)
                {
                    $data = array(
                        'estilo' 		=> $producto['codigo'],
                        'precio_venta'	=> $producto['ProductosCompra']['valor'],
                        'talla'			=> $producto['ProductosCompra']['talla'],
                        'cantidad'		=> 1,
                        'color'			=> $producto['Color']['codigo'],
                        'fecha'			=> date('m/d/Y',$ayer),
                        'fecha_canc'	=> '',
                        'precio_retail'	=> $producto['ProductosCompra']['valor'],
                        'PO'			=> $compra['Compra']['id'],
                        'ACC'			=> '10998',
                        'TERM_CODE'		=> '81',
                        'Shipper_code'	=> 'SR',
                        'Sales_Rep1'	=> '100',
                        'Sales_Rep2'	=> '',
                        'precio_promo'	=> '',
                        'event_code'	=> '',
                        'tienda'		=> '00017'	,
                        'dept_code'		=> '',
                        'DIV_CODE'		=> $producto['division'],
                        'PROMO_CODE'	=>'',
                        'Special_Inst'	=>'',
                        'SKK'			=> $producto['sku'],
                    );
                    array_push($datos_compras,$data);

                    $direccion_texto = $direccion['Direccion']['calle'].' '.$direccion['Direccion']['numero'];

                    if(isset($direccion['Direccion']['depto']) && trim($direccion['Direccion']['depto']) !='')
                        $direccion_texto .= ' DP '.$direccion['Direccion']['depto'];

                    $codigo_comuna ='ST';

                    if(isset($direccion['Comuna']['codigo']) && trim($direccion['Comuna']['codigo']) !='')
                        $codigo_comuna = 	$direccion['Comuna']['codigo'];

                    $data = array(
                        'PO'			=> $compra['Compra']['id'],
                        'direccion'		=> $direccion_texto,
                        'pais'			=> 'CHL',
                        'comuna'		=> $direccion['Comuna']['nombre'],
                        'nombre'		=> $direccion['Usuario']['nombre'].' '.$direccion['Usuario']['apellido_paterno'].' '.$direccion['Usuario']['apellido_materno'],
                        'comuna2'		=> $codigo_comuna,
                        'city'			=> $direccion['Region']['codigo']
                    );
                    array_push($datos_despachos,$data);
                }

            }

            $nombre = 'archivos/compras_'.date('m-d-Y',$ayer).'.csv';
            $fp = fopen($nombre, 'w+');
            $cabecera ='PO#	|ACC#|	STORE#|	Promo_Code|	TERM_CODE|	Shipper_Code|	Sales_Rep1|	UPLOAD_DATE|	Special_Inst|	SKX_UPC#|	DIV_CODE|	STYLE#|	COLOR_CODE| PRICE WHOLESALE|	SIZE|	QTY|	START_DATE|	CANCEL_DATE|	Sales_Rep2|	Cust_UPC/SKU|	class_code|	stock_code|	Event_code|	promoprice|	retail price|	dept code| 	prepack code|
			';
            fwrite($fp,$cabecera);
            foreach($datos_compras as $dato_compra)
            {
                $linea = $dato_compra['PO'].'|'.$dato_compra['ACC'].'|'.$dato_compra['tienda'].'|'.$dato_compra['PROMO_CODE'].'|'.$dato_compra['TERM_CODE'].'|'.$dato_compra['Shipper_code'].'|'.$dato_compra['Sales_Rep1'].'|'.$dato_compra['fecha'].'|'.$dato_compra['Special_Inst'].'|'.$dato_compra['SKK'].'|'.$dato_compra['DIV_CODE'].'|'.$dato_compra['estilo'].'|'.$dato_compra['color'].'|'.$dato_compra['precio_venta'].'|'.$dato_compra['talla'].'|'.$dato_compra['cantidad'].'|'.$dato_compra['fecha'].'|'.$dato_compra['fecha_canc'].'|'.$dato_compra['Sales_Rep2'].';;;;'.$dato_compra['event_code'].'|'.$dato_compra['precio_promo'].'|'.$dato_compra['precio_retail'].'|'.$dato_compra['dept_code'].'||';
                fwrite($fp,$linea);
            }
            fclose($fp);
            $nombre = 'archivos/direcciones_'.date('m-d-Y',$ayer).'.csv';
            $fp = fopen($nombre, 'w+');
            $cabecera ='PO	|NOMBRE CLIENTE|	ADDRESS1|	ADDRESS2|	ADDRESS3|	STATE| CITY	|	COUNTRY|
			';
            fwrite($fp,$cabecera);
            foreach($datos_despachos as $datos_despacho)
            {
                $linea = $datos_despacho['PO'].'|'.trim(utf8_decode((($datos_despacho['nombre'])))).'|'.utf8_decode($datos_despacho['direccion']).'|'.utf8_decode($datos_despacho['nombre']).'|'.utf8_decode($datos_despacho['comuna']).'|'.$datos_despacho['comuna2'].'|'.$datos_despacho['city'].'|'.$datos_despacho['pais'].'
				';
                fwrite($fp,$linea);
            }
            fclose($fp);
        }
    }

    function generar_reporte($venta_id)
    {
        $ayer = strtotime(date('d-m-Y', strtotime(date('d-m-Y H:i:s')))) - (60*60*24*1);

        $productos = array();
        $options = array(
            'conditions' => array(
                'Compra.id ' => $venta_id
            ),
            'fields' => array(
                'Compra.id',
                'Compra.subtotal',
                'Compra.descuento',
                'Compra.total',
                'Compra.estado',
                'Compra.despacho_id',
                'Despacho.id',
                'Direccion.id',
                'Direccion.calle',
                'Direccion.numero',
                'Direccion.depto',
                'Direccion.telefono',
                'Direccion.celular',
                'Direccion.otras_indicaciones',
                'Comuna.id',
                'Comuna.nombre',
                'Comuna.codigo',
                'Region.id',
                'Region.nombre',
                'Region.codigo',
                'UsuarioDespacho.id',
                'UsuarioDespacho.nombre',
                'UsuarioDespacho.apellido_paterno',
                'UsuarioDespacho.apellido_materno',
                'UsuarioDespacho.email',
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_despachos',
                    'alias' => 'Despacho',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Despacho.id = Compra.despacho_id'
                    )
                ),
                array(
                    'table' => 'sitio_direcciones',
                    'alias' => 'Direccion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Direccion.id = Despacho.direccion_id'
                    )
                ),
                array(
                    'table' => 'sitio_comunas',
                    'alias' => 'Comuna',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Comuna.id = Direccion.comuna_id'
                    )
                ),
                array(
                    'table' => 'sitio_regiones',
                    'alias' => 'Region',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Region.id = Comuna.region_id'
                    )
                ),
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'UsuarioDespacho',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'UsuarioDespacho.id = Direccion.usuario_id'
                    )
                ),
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
            return false;

        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $compra['Compra']['id']
            ),
            'fields' => array(
                'ProductosCompra.id',
                'ProductosCompra.descuento_id',
                'ProductosCompra.talla',
                'ProductosCompra.valor',
                'ProductosCompra.sku',
                'Producto.id',
                'Producto.nombre',
                'Producto.precio',
                'Producto.codigo',
                'Producto.codigo_completo',
                'Producto.division',
                'Producto.dosporuno',
                'Color.id',
                'Color.nombre',
                'Color.codigo',
                'Categoria.id',
                'Categoria.nombre',
                'Descuento.id',
                'Descuento.nombre',
                'Descuento.tipo',
                'Descuento.descuento',
            ),
            'joins' => array(
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                ),
                array(
                    'table' => 'sitio_descuentos',
                    'alias' => 'Descuento',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Descuento.id = ProductosCompra.descuento_id'
                    )
                ),
                array(
                    'table' => 'sitio_colores',
                    'alias' => 'Color',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Color.id = Producto.color_id'
                    )
                ),
                array(
                    'table' => 'sitio_categorias',
                    'alias' => 'Categoria',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Categoria.id = Producto.categoria_id'
                    )
                ),
            )
        );
        $productos = $this->Compra->ProductosCompra->find('all',$options);

        $datos_compras = $datos_despachos = array();
        if($compra && $productos)
        {
            /* DOS POR UNO */
            if ($compra['Compra']['descuento'])
            {
                $listado_2x1 = array();
                foreach ( $productos as $producto )
                {
                    if ($producto['Descuento']['id'])
                        continue;
                    if (! $producto['Producto']['dosporuno'])
                        continue;
                    $listado_2x1[ $producto['ProductosCompra']['id'] ] = $producto['ProductosCompra']['valor'];
                }
                $cont = count($listado_2x1);
                if ( $cont && asort($listado_2x1) )
                {
                    $limit = (int)($cont / 2);
                    if ( ($cont % 2) )
                        $limit++;
                    $descuento = $x = 0;
                    foreach ( $listado_2x1 as $productosCompraId => $precio )
                    {
                        if ($x >= $limit)
                            break;
                        ++$x;
                        if ( $descuento < $compra['Compra']['descuento'] )
                        {
                            $descuento+=$precio;
                            foreach ($productos as $index => $producto)
                            {
                                if ( $producto['ProductosCompra']['id'] != $productosCompraId )
                                    continue;
                                $productos[$index]['ProductosCompra']['valor'] = 0;
                                break;
                            }
                        }
                    }
                }
            }
            /* FIN DOS POR UNO */

            foreach ($productos as $producto)
            {
                $precio_venta = $producto['ProductosCompra']['valor'];
                if ($producto['Descuento']['id'])
                {
                    $descontar = 0;
                    if ($producto['Descuento']['tipo'] == 'POR')
                    {
                        if ($producto['Descuento']['descuento'])
                        {
                            $descontar = ($producto['ProductosCompra']['valor'] * $producto['Descuento']['descuento']) / 100;
                            if ( ($descontar % 10) > 0 )// redondea descuento
                                $descontar = (((int)($descontar/10))*10)+10;
                            else
                                $descontar = ((int)($descontar/10))*10;
                        }
                    }
                    elseif ($producto['Descuento']['descuento'])
                    {
                        $descontar = $producto['Descuento']['descuento'];
                    }
                    $precio_venta = $precio_venta-$descontar;
                    if ($precio_venta <= 0)
                        $precio_venta = 0;
                }

                $data = array(
                    'estilo' 		=> $producto['Producto']['codigo'],
                    'precio_venta'	=> $precio_venta,
                    'talla'			=> $producto['ProductosCompra']['talla'],
                    'cantidad'		=> 1,
                    'color'			=> $producto['Color']['codigo'],
                    'fecha'			=> date('m/d/Y',$ayer),
                    'fecha_canc'	=>'',
                    'precio_retail'	=> $producto['ProductosCompra']['valor'],
                    'PO'			=> $compra['Compra']['id'],
                    'ACC'			=> '10998',
                    'TERM_CODE'		=> '81',
                    'Shipper_code'	=> 'SR',
                    'Sales_Rep1'	=> '100',
                    'Sales_Rep2'	=> '',
                    'precio_promo'	=> '',
                    'event_code'	=> '',
                    'tienda'		=> '00017',
                    'dept_code'		=> '',
                    'DIV_CODE'		=> $producto['Producto']['division'],
                    'PROMO_CODE'	=>'',
                    'Special_Inst'	=>'',
                    'SKK'			=> $producto['ProductosCompra']['sku'],
                );
                array_push($datos_compras,$data);

                $direccion_texto = '';
                if (isset($compra['Direccion']['calle']) && $compra['Direccion']['calle'])
                {
                    $direccion_texto = $compra['Direccion']['calle'].' '.$compra['Direccion']['numero'];
                    if(isset($direccion['Direccion']['depto']) && trim($direccion['Direccion']['depto']) !='')
                        $direccion_texto .= ' DP '.$direccion['Direccion']['depto'];
                }

                $codigo_comuna ='ST';
                if(isset($compra['Comuna']['codigo']) && trim($compra['Comuna']['codigo']) !='')
                    $codigo_comuna = $compra['Comuna']['codigo'];

                $data = array(
                    'PO'			=> $compra['Compra']['id'],
                    'direccion'		=> str_replace('_',' ',Inflector::slug($direccion_texto)),
                    'pais'			=> 'CHL',
                    'comuna'		=> str_replace('_',' ',Inflector::slug($compra['Comuna']['nombre'])),
                    'nombre'		=> str_replace('_',' ',Inflector::slug($compra['UsuarioDespacho']['nombre'].' '.$compra['UsuarioDespacho']['apellido_paterno'].' '.$compra['UsuarioDespacho']['apellido_materno'])),
                    'comuna2'		=> $codigo_comuna,
                    'email'			=> $compra['UsuarioDespacho']['email'],
                    'city'			=> $compra['Region']['codigo']
                );
                array_push($datos_despachos,$data);
            }

            if (false)
                $basedir = DS.'home'.DS.'skechile'.DS.'public_html'.DS.'webroot'.DS.'archivos'.DS.'PROD'.DS;
            else
                $basedir = DS.'home'.DS.'skechile'.DS.'public_html'.DS.'desarrollo'.DS.'webroot'.DS.'archivos'.DS.'DEV'.DS;

            if (! is_dir($basedir))
                @mkdir($basedir, 0755, true);

            //      GUARDAMOS EL ARCHIVO DE LOS PRODUCTOS
            $nombre = '10998_'.$venta_id.'.csv';
            $fp = fopen($basedir.$nombre, 'w+');
            $contador =1;
            $linea = '';
            foreach($datos_compras as $dato_compra)
            {
                $linea = $dato_compra['PO'].'|'.$dato_compra['ACC'].'|'.$dato_compra['tienda'].'|'.$dato_compra['PROMO_CODE'].'|'.$dato_compra['TERM_CODE'].'|'.$dato_compra['Shipper_code'].'|'.$dato_compra['Sales_Rep1'].'|'.$dato_compra['fecha'].'|'.$dato_compra['Special_Inst'].'|'.$dato_compra['SKK'].'|'.$dato_compra['DIV_CODE'].'|'.$dato_compra['estilo'].'|'.$dato_compra['color'].'|'.$dato_compra['precio_venta'].'|'.$dato_compra['talla'].'|'.$dato_compra['cantidad'].'|'.$dato_compra['fecha'].'||||||||'.$dato_compra['precio_retail'].'|||';
                if(count($datos_compras) > $contador++)
                {
                    $linea.='
';
                }
                fwrite($fp,$linea);
            }
            fclose($fp);

            //      GUARDAMOS EL ARCHIVO DE LOS ADDRESS
            $nombre = '10998_'.$venta_id.'_address.csv';
            $fp = fopen($basedir.$nombre, 'w+');
            $cabecera ='PO	|NOMBRE CLIENTE|	ADDRESS1|	ADDRESS2|	ADDRESS3|	STATE| CITY	|	COUNTRY|
			';
            $linea ='';
            foreach($datos_despachos as $datos_despacho)
            {
                $linea = $datos_despacho['PO'].'|'.trim(utf8_decode((($datos_despacho['nombre'])))).'|'.utf8_decode($datos_despacho['direccion']).'|'.utf8_decode($datos_despacho['email']).'| |'.utf8_decode($datos_despacho['comuna']).'|'.utf8_decode($datos_despacho['comuna2']).'| |'.utf8_decode($datos_despacho['pais']).'';
            }
            fwrite($fp,$linea);
            fclose($fp);
            return true;
        }
        return false;
    }
    function imprimir_devolucion($id)
    {
        $this->layout ='pdf';
        $conditions = $options = array(
            'conditions' => array(
                'ProductosCompra.id' => $id
            ),
            'fields' => array(
                'ProductosCompra.id',
                'ProductosCompra.descuento_id',
                'ProductosCompra.talla',
                'ProductosCompra.valor',
                'ProductosCompra.devolucion_dinero',
                'ProductosCompra.estado',
                'ProductosCompra.devolucion',
                'Producto.id',
                'Producto.nombre',
                'Producto.foto',
                'Producto.codigo',
                'Color.nombre',
                'Color.codigo',
                'Devolucion.id',
                'Devolucion.compra_id',
                'Devolucion.productos_compra_id',
                'Devolucion.tipo',
                'Devolucion.estado',
                'Devolucion.codigo',
                'Devolucion.fecha',
                'Devolucion.hora',
                'Devolucion.tipo_nombre',
                'Devolucion.razon',
                'Devolucion.producto',
                'Devolucion.talla',
                'Devolucion.created',
                'Compra.id',
                'Compra.boleta',
                'Compra.id',
                'Direccion.calle',
                'Direccion.numero',
                'Direccion.depto',
                'Direccion.telefono',
                'Direccion.celular',
                //'Comuna.Nombre'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                ),
                array(
                    'table' => 'sitio_compras',
                    'alias' => 'Compra',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Compra.id = ProductosCompra.compra_id'
                    )
                ),
                array(
                    'table' => 'sitio_colores',
                    'alias' => 'Color',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Color.id = Producto.color_id'
                    )
                ),
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devolucion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Devolucion.productos_compra_id = ProductosCompra.id',
                        'Devolucion.productos_compra_id' => $id
                    )
                ),
                array(
                    'table' => 'sitio_despachos',
                    'alias' => 'Despacho',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Compra.despacho_id = Despacho.id'

                    )
                ),
                array(
                    'table' => 'sitio_direcciones',
                    'alias' => 'Direccion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Direccion.id = Despacho.direccion_id'
                    )
                ),

            )
        );
        $respuesta = $this->Compra->ProductosCompra->find('first', $options);
        if($respuesta['Devolucion']['producto'])
            $respuesta['Devolucion']['tipo_nombre'] .= ' <b>Codigo : '.$respuesta['Devolucion']['producto'].'</b>';
        if($respuesta['Devolucion']['talla'])
            $respuesta['Devolucion']['tipo_nombre'] .= ' <b>Talla : '.$respuesta['Devolucion']['talla'].'</b>';
        $this->set(compact('respuesta'));
    }

    function devolucion($id = null)
    {
        if($this->data)
        {
            //prx($this->data);
            $estado = 0;
            if($this->data['Compra']['tipo'] ==1)
            {
                $tipo_nombre='Devolución del producto';

            }
            else if($this->data['Compra']['tipo'] ==2)
                $tipo_nombre='Cambio de producto';
            else if($this->data['Compra']['tipo'] ==3)
                $tipo_nombre='Cambio por talla';
            else if($this->data['Compra']['tipo'] ==4)
            {
                $tipo_nombre='Garantia';

            }
            $talla =NULL;
            $producto = NULL;
            if($this->data['Compra']['talla'] && $this->data['Compra']['talla'] != '' )
                $talla = $this->data['Compra']['talla'];
            if($this->data['Compra']['talla2'] && $this->data['Compra']['talla2'] != '')
                $talla = $this->data['Compra']['talla2'];
            if(isset($this->data['Compra']['codigo_producto']) && $this->data['Compra']['codigo_producto'] != '')
                $producto = $this->data['Compra']['codigo_producto'];
            else
            {
                $producto = $this->Compra->Producto->find('first', array('conditions' => array('id' => $this->data['Compra']['producto'])));
                $producto = $producto['Producto']['codigo_completo'];

            }
            $num = $this->Compra->Devolucion->find('count',array('conditions' => array('compra_id' => $this->data['Compra']['compra'])));
            if ($num <= 10){
                $codigo = $this->data['Compra']['compra'].'-0'.$num;
            }else{
                $codigo = $this->data['Compra']['compra'].'-'.$num;
            }
            $usuario_id = $this->Auth->user('id');
            $options = array(
                'conditions' => array(
                    'compra_id' => $this->data['Compra']['compra'],
                    //'Compra.estado' => array(1,3),
                    'usuario_id' => $usuario_id,
                ),
                'fields' => array(
                    'tipoPago'
                ),
            );
            $pago = $this->Compra->Pago->find('first',$options);
            $tipoPago = $pago['Pago']['tipoPago'];

            if($tipoPago == 'VD'){

                $guardar = array('productos_compra_id' => $this->data['Compra']['id'],
                    'compra_id' => $this->data['Compra']['compra'],
                    'estado' => $estado,
                    'tipo' => $this->data['Compra']['tipo'],
                    'talla' => $talla,
                    'codigo' => $codigo,
                    'producto' => $producto,
                    'fecha' => date('Y-m-d'),
                    'hora' => date('H:i'),
                    'razon' => $this->data['Compra']['razon'],
                    'tipo_nombre' => $tipo_nombre,
                    'nombre_titular' => $this->data['Compra']['nombre_titular'],
                    'apellido_titular' => $this->data['Compra']['apellido_titular'],
                    'run_titular' => $this->data['Compra']['run_titular'],
                    'tcuenta_titular' => $this->data['Compra']['tcuenta_titular'],
                    'ncuenta_titular' => $this->data['Compra']['ncuenta_titular'],
                    'banco_titular' => $this->data['Compra']['banco_titular']

                );
            }else{
                $guardar = array('productos_compra_id' => $this->data['Compra']['id'],
                    'compra_id' => $this->data['Compra']['compra'],
                    'estado' => $estado,
                    'tipo' => $this->data['Compra']['tipo'],
                    'talla' => $talla,
                    'codigo' => $codigo,
                    'producto' => $producto,
                    'fecha' => date('Y-m-d'),
                    'hora' => date('H:i'),
                    'razon' => $this->data['Compra']['razon'],
                    'tipo_nombre' => $tipo_nombre

                );
            }

            $this->Compra->Devolucion->save($guardar);
            $guardar = array(	'id' => $this->data['Compra']['id'],
                'devolucion' => 1

            );
            $this->Email->smtpOptions =array(
                'port' => 	'587',
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
            $compra_id = $this->data['Compra']['compra'];
            $this->set(compact('compra_id','tipo_nombre'));

            $this->Email->to = 'sebastian@sancirilo.cl';
            //$this->Email->to = 'ehenriquez@andain.cl';
            //$this->Email->bcc	= $copias;
            $this->Email->subject = '[Skechers - Tienda en linea] Devolucion #' .  $compra_id;
            $this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
            $this->Email->replyTo = $this->email_skechers[2];
            $this->Email->sendAs = 'html';
            $this->Email->template	= 'devolucion';
            $this->Email->delivery = 'smtp';
            if ($this->Email->send())
            {
            }
            $this->Compra->ProductosCompra->save($guardar);
            $guardar = array('id' => $this->data['Compra']['compra'],
                'devolucion' => 1,
                'estado'	=> 12
            );
            $this->Compra->save($guardar);
            $this->redirect(array('controller' => 'compras', 'action' => 'imprimir_devolucion',$this->data['Compra']['id']));

        }

        if (! $id)
            $this->redirect(array('controller' => 'productos', 'action' => 'inicio'));

        if(! $this->Auth->user() )
            $this->redirect(array('controller' => 'productos', 'action' => 'inicio'));
        $usuario_id = $this->Auth->user('id');
        $options = array(
            'conditions' => array(
                'Compra.id' => $id,
                //'Compra.estado' => array(1,3),
                'Compra.usuario_id' => $usuario_id,
            ),
            'fields' => array(
                'Compra.id',
                'Compra.subtotal',
                'Compra.iva',
                'Compra.neto',
                'Compra.total',
                'Compra.valor_despacho',
                'Compra.descuento',
                'Compra.modified'
            ),
        );
        $compra = $this->Compra->find('first',$options);
        if (! $compra = $this->Compra->find('first',$options))
        {
            $this->Session->setFlash(__('Registro invalido.', true));
            $this->redirect(array('controller' => 'productos', 'action' => 'inicio'));
        }
        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $compra['Compra']['id']
            ),
            'fields' => array(
                'ProductosCompra.id',
                'ProductosCompra.descuento_id',
                'ProductosCompra.talla',
                'ProductosCompra.valor',
                'ProductosCompra.devolucion_dinero',
                'ProductosCompra.estado',
                'ProductosCompra.devolucion',
                'Producto.id',
                'Producto.nombre',
                'Producto.foto',
                'Producto.codigo_completo',
                'Color.nombre',
                'Color.codigo',
                'Devolucion.id',
                'Devolucion.compra_id',
                'Devolucion.productos_compra_id',
                'Devolucion.tipo',
                'Devolucion.estado',
                'Devolucion.codigo',
                'Devolucion.fecha',
                'Devolucion.hora',
                'Devolucion.razon',
                'Devolucion.observaciones',
                'Devolucion.created'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                ),
                array(
                    'table' => 'sitio_colores',
                    'alias' => 'Color',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Color.id = Producto.color_id'
                    )
                ),
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devolucion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Devolucion.productos_compra_id = ProductosCompra.id',
                        'Devolucion.compra_id' => $compra['Compra']['id']
                    )
                ),
            )
        );
        $productos = $this->Compra->ProductosCompra->find('all',$options);
        $caducidad30 = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) - (60 * 60 * 24 * 30));
        $caducidad90 = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) - (60 * 60 * 24 * 90));
        $tipos_cambios = array();
        if($caducidad30 < $compra['Compra']['modified'])
        {
            $tipos_devolucion[1] = 'Devolución del producto';
            //$tipos_devolucion[3] = 'Cambio por talla'; //cambio por tall
        }
        if($caducidad90 < $compra['Compra']['modified'])
        {
            $tipos_devolucion[4] = 'Garantia';
        }
        $usuario_id = $this->Auth->user('id');
        $options = array(
            'conditions' => array(
                'compra_id' => $id,
                //'Compra.estado' => array(1,3),
                'usuario_id' => $usuario_id,
            ),
            'fields' => array(
                'tipoPago'
            ),
        );
        $pago = $this->Compra->Pago->find('first',$options);
        $tipoPago = $pago['Pago']['tipoPago'];


        $this->set(compact('compra', 'productos','tipos_devolucion','tipoPago'));
    }

    function admin_anular($id = null)
    {
        if ( ! $id )
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'index'));
        }
        if ( $this->Compra->save(array('Compra' => array('id' => $id, 'estado' => 2))) )
        {
            // guardar log
            $this->guardar_log($this->Auth->user('id'), 'compras', 'admin_anular', 'anular compra '.$id, $_SERVER['REMOTE_ADDR']);
            $this->Session->setFlash(__('Registro anulado', true));
            $this->redirect(array('action' => 'anulado'));
        }
        $this->Session->setFlash(__('El registro no pudo ser anulado. Por favor intenta nuevamente', true));
        $this->redirect(array('action' => 'listar'));
    }
    function admin_webpay_anular($id = null)
    {
      
    }
     function admin_procesar_anular($id = null)
    {
        if($this->data['Compra']['clave'] !='1478963')
        {
            $this->Session->setFlash(__('Clave de validacion incorrecta', true));
            $this->redirect(array('action' => 'webpay_anular'));
        }
        $ordenes = explode(' ',$this->data['Compra']['orden']);
        $resultados = array('ok' =>0, 'error' => 0, 'total' => 0);
        $errores = $procesadas = array();
        foreach ($ordenes as $orden)
        {
            $resultado  = $this->anular_compra($orden);
            //prx($resultado);
            if($resultado['procesada'])
            {
                $resultados['ok']++;
                $procesadas[]=$orden;
                $resultados['total']++;
            }else{
                $resultados['error']++;
                $errores[$orden] = $resultado['error'];
                $resultados['total']++;
            }
           
        }

        $this->set(compact('resultados','errores'));

      
    }

    function admin_index()
    {
        $limit = 20;
        if ($this->Session->check('ComprasPorPagina'))
            $limit = $this->Session->read('ComprasPorPagina');
        // COMPRAS POR DESPACHAR
        $this->paginate = array(
            'conditions' => array(
                'Compra.estado' => 1,
                'Compra.local' => 0,
                'OR' => array(
                    array('Compra.despachado' => NULL),
                    array('Compra.despachado <>' => 1)
                )
            ),
            'contain' => array(
                //'Despacho' => array('Direccion'),
                'Usuario',
                //'Pago'
            ),
            'limit' => $limit,
            'order' => array('Compra.created' => 'ASC')
        );
        // CONDICIONADORES PARA MOSTRAR ESTADO DE DESPACHO SEGUN FECHA DE CREACION DE LA COMPRA
        $tres_dias = $this->Carro->dias_habiles(3);
        $cinco_dias = $this->Carro->dias_habiles(5);
        //$compras =  $this->paginate();
        //prx($compras);
        $this->set('compras', $this->paginate());
        $this->set(compact('tres_dias', 'cinco_dias'));
        $this->render('admin_pagadas');
    }
    function admin_multivende()
    {
        $limit = 20;
        if ($this->Session->check('ComprasPorPagina'))
            $limit = $this->Session->read('ComprasPorPagina');
        // COMPRAS POR DESPACHAR
        $this->paginate = array(
            'conditions' => array(
                'Compra.estado' => 1,
                'Compra.local' => 1,
                'OR' => array(
                    array('Compra.despachado' => NULL),
                    array('Compra.despachado <>' => 1)

                )
            ),

            'limit' => $limit,
            'order' => array('Compra.created' => 'ASC')
        );
        // CONDICIONADORES PARA MOSTRAR ESTADO DE DESPACHO SEGUN FECHA DE CREACION DE LA COMPRA
        $tres_dias = $this->Carro->dias_habiles(3);
        $cinco_dias = $this->Carro->dias_habiles(5);
        $this->set('compras', $this->paginate());
        $this->set(compact('tres_dias', 'cinco_dias'));
    }

    function admin_pagadas()
    {
        // COMPRAS PAGADAS
        $limit = 20;
        if ($this->Session->check('ComprasPorPagina'))
            $limit = $this->Session->read('ComprasPorPagina');
        $this->paginate = array(
            'conditions' => array(
                'Compra.estado' => 1,
                'Compra.local' => 0
            ),
            'fields' => array(
                'Compra.id',
                'Compra.picking_number',
                'Compra.usuario_id',
                'Compra.total',
                'Compra.despachado',
                'Compra.enviado',
                'Compra.boleta_pdf',
                'Compra.created'
            ),
            'contain' => array(
                'Usuario' => array(
                    'fields' => array(
                        'Usuario.id',
                        'Usuario.nombre',
                        'Usuario.apellido_paterno'
                    )
                ),
            ),
            'limit' => $limit,
            'order' => array('Compra.created' => 'DESC')
        );
        // CONDICIONADORES PARA MOSTRAR ESTADO DE DESPACHO SEGUN FECHA DE CREACION DE LA COMPRA
        $tres_dias = $this->Carro->dias_habiles(3);
        $cinco_dias = $this->Carro->dias_habiles(5);
        $this->set('compras', $this->paginate());
        $this->set(compact('tres_dias', 'cinco_dias'));
    }

    function admin_no_pagado()
    {
        $this->paginate = array(
            'conditions' => array(
                'Compra.estado' => 0,
                'Compra.local' => 0
            ),
            'contain' => array(
                'Despacho' => array('Direccion'),
                'Usuario'
            ),
            'order' => array('Compra.created' => 'DESC')
        );

        $this->set('compras', $this->paginate());
        $this->render('admin_listar');
    }

    function admin_devoluciones()
    {
        /*$options = array(
            'conditions' => array(
                'Devolucion.estado' => 0,
                'Pagos.marca' => 'Webpay'
            ),

            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_pagos',
                    'alias' => 'Pagos',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Pagos.compra_id = Devolucion.compra_id',
                    )
                ),
            ),
            'order' => array('Devolucion.created' => 'DESC'),
        );

        $this->paginate = $devoluciones = $this->Compra->Devolucion->find('all',$options);
        $this->set('devoluciones', $devoluciones);*/
        $limit = 20;
        $options = array(
            'conditions' => array(
                'Devolucion.estado' => 0,
                'Pagos.marca' => 'Webpay'
            ),
            'fields' => array(
                'Compra.id',
                'Compra.estado',
                'Compra.created',
                'Compra.total',
                'Compra.boleta_pdf',
                'Compra.usuario_id',
                'Devolucion.estado',
                'Devolucion.codigo',
                'Devolucion.producto',
                'Devolucion.tipo_nombre',
                'Devolucion.created',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno',

            ),
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    ),
                ),
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devolucion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Devolucion.compra_id = Compra.id'
                    ),
                ),
                array(
                    'table' => 'sitio_pagos',
                    'alias' => 'Pagos',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Pagos.compra_id = Devolucion.compra_id',
                    )
                ),
            ),
            'order' => array(
                'Compra.id' => 'DESC'
            ),
            'limit' => $limit
        );


        $this->paginate = $options;
        // var_dump($this->paginate());
        $this->set('compras', $this->paginate());


    }
    function admin_devoluciones_mp()
    {
        /*$options = array(
            'conditions' => array(
                'Devolucion.estado' => 0,
                'Pagos.marca' => 'mercadopago'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_pagos',
                    'alias' => 'Pagos',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Pagos.compra_id = Devolucion.compra_id',
                    )
                ),
            ),
            'order' => array('Devolucion.created' => 'DESC'),
        );

        $this->paginate = $devoluciones = $this->Compra->Devolucion->find('all',$options);

        $this->set('devoluciones', $devoluciones);*/
        $limit = 20;
        $options = array(
            'conditions' => array(
                'Devolucion.estado' => 0,
                'Pagos.marca' => 'mercadopago'
            ),
            'fields' => array(
                'Compra.id',
                'Compra.estado',
                'Compra.created',
                'Compra.total',
                'Compra.boleta_pdf',
                'Compra.usuario_id',
                'Devolucion.estado',
                'Devolucion.codigo',
                'Devolucion.producto',
                'Devolucion.tipo_nombre',
                'Devolucion.created',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno',

            ),
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    ),
                ),
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devolucion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Devolucion.compra_id = Compra.id'
                    ),
                ),
                array(
                    'table' => 'sitio_pagos',
                    'alias' => 'Pagos',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Pagos.compra_id = Devolucion.compra_id',
                    )
                ),
            ),
            'order' => array(
                'Compra.id' => 'DESC'
            ),
            'limit' => $limit
        );


        $this->paginate = $options;
        // var_dump($this->paginate());
        $this->set('compras', $this->paginate());
    }

    function admin_devuelto()
    {
        $this->paginate = array(
            'conditions' => array('Compra.devolucion' => 1),
            'contain' => array(
                'Despacho' => array('Direccion'),
                'Usuario',
                'Devolucion' => array(
                    'conditions' => array(
                        'Devolucion.estado' => 0
                    )
                )
            ),
            'order' => array('Compra.created' => 'DESC')
        );
        $compras = $this->paginate();
        $this->set('compras', $this->paginate());
    }
    function admin_devuelto2()
    {
        $this->paginate = array(
            'conditions' => array(
                'Compra.devolucion' => 1,
                'Compra.estado' => 3
            ),
            'contain' => array(
                'Despacho' => array('Direccion'),
                'Usuario',
                'Devolucion' => array(
                    'conditions' => array(
                        'Devolucion.estado' => 1
                    )
                )
            ),
            'order' => array('Compra.created' => 'DESC')
        );
        $this->set('compras', $this->paginate());
    }
    //Rechazado
    function admin_devuelto4()
    {
        $options = array(
            'conditions' => array(
                'Devolucion.estado ' => 2,

            ),
            'recursive' => -1,
            'order' => array('Devolucion.modified' => 'DESC'),
        );
        $devoluciones = $this->Compra->Devolucion->find('all',$options);

        $this->set(compact('devoluciones', $this->paginate()));
    }


    function admin_devuelto3()
    {
        /*$this->paginate = array(
			'conditions' => array(	'Compra.devolucion' => 1,
									//'Compra.estado !=' => 3
                                    'Compra.estado' => 4
                ),
			'contain' => array(
				'Despacho' => array('Direccion'),
				'Usuario',
				'Devolucion' => array(
				    'conditions' => array(
				        'Devolucion.estado' => 1
                    )
                )
			),
			'order' => array('Compra.created' => 'DESC')
		);

		$this->set('compras', $this->paginate()); */

        $options = array(
            'conditions' => array(
                'Devolucion.estado !=' => 0,

            ),
            'recursive' => -1,
            'order' => array('Devolucion.modified' => 'DESC'),
        );
        $devoluciones = $this->Compra->Devolucion->find('all',$options);

        $this->set(compact('devoluciones', $this->paginate()));
    }
    function admin_ver_cerrado($codigo = null){
        if (! $codigo)
        {
            $this->Session->setFlash(__('Registro invalido.', true));
            $this->redirect(array('action' => 'index'));
        }

        $options = array('conditions' => array('Devolucion.codigo' => $codigo),);
        $devolucion = $this->Compra->Devolucion->find('first',$options);

        $options = array('conditions' => array('id' => $devolucion['Devolucion']['compra_id']));
        $compra = $this->Compra->find('first',$options);

        $options = array('conditions' => array('id' => $compra['Compra']['usuario_id']));
        $usuario = $this->Compra->Usuario->find('first', $options);

        $options = array('conditions' => array('compra_id' => $compra['Compra']['id']));
        $pago = $this->Compra->Pago->find('first', $options);
        //prx($pago);

        $options = array('conditions' => array('usuario_id' => $usuario['Usuario']['id']));
        $direccion =  $this->Compra->Despacho->Direccion->find('first', $options);

        $options = array(
            'conditions' => array(
                //'compra_id' => $compra['Compra']['id'],
                'id' => $devolucion['Devolucion']['productos_compra_id']
            )
        );
        $producto = $this->Compra->ProductosCompra->find('first', $options);

        $options = array(
            'conditions' => array(
                //'compra_id' => $compra['Compra']['id'],
                'id' => $producto['ProductosCompra']['producto_id']
            )
        );
        $producto_datos = $this->Compra->Producto->find('first', $options);


        //var_dump($producto);
        //die();

        $this->set(compact('devolucion','compra','usuario','direccion','producto','producto_datos','pago'));
    }

    function admin_anulado()
    {
        $this->paginate = array(
            'conditions' => array('Compra.estado' => 2),
            'contain' => array(
                'Despacho' => array('Direccion'),
                'Usuario'
            ),
            'order' => array('Compra.created' => 'DESC')
        );
        $this->set('compras', $this->paginate());
        $this->render('admin_listar');
    }
    function admin_anulado_stock()
    {
        $this->paginate = array(
            'conditions' => array('Compra.estado' => 11),
            'contain' => array(
                'Despacho' => array('Direccion'),
                'Usuario'
            ),
            'order' => array('Compra.created' => 'DESC')
        );
        $this->set('compras', $this->paginate());
        $this->render('admin_listar');
    }
    function admin_devolucion_activa()
    {
        $this->paginate = array(
            'conditions' => array('Compra.estado' => 12),
            'contain' => array(
                'Despacho' => array('Direccion'),
                'Usuario'
            ),
            'order' => array('Compra.created' => 'DESC')
        );
        $this->set('compras', $this->paginate());
        $this->render('admin_listar');
    }

    function admin_pendiente()
    {
        $this->paginate = array(
            'conditions' => array('Compra.estado' => 5),
            'contain' => array(
                'Despacho' => array('Direccion'),
                'Usuario'
            ),
            'order' => array('Compra.created' => 'DESC')
        );
        $this->set('compras', $this->paginate());
        $this->render('admin_listar');
    }

    function admin_listar()
    {
        $options = array(
            'contain' => array(
                'Despacho' => array('Direccion'),
                'Usuario'
            ),
            'order' => array('Compra.created' => 'DESC')
        );
        if ($this->Auth->user('perfil') == 0)
            $options['conditions'] = array('Compra.estado' => array(0,1));
        elseif ($this->Auth->user('perfil') == 1)
            $options['conditions'] = array('Compra.estado' => array(1,3,4,5));
        $this->paginate = $options;
        $this->set('compras', $this->paginate());
    }

    function admin_buscar()
    {
        if (! empty($this->data))
        {
            $url = array(
                'controller' => 'compras',
                'action' => 'buscar',
                'field' => '',
                'seacrh' => '',
                'date' => ''
            );
            if (isset($this->data['Buscar']['field']) && isset($this->data['Buscar']['search']))
            {
                $url = array_merge($url, array(
                    'field' => $this->data['Buscar']['field'],
                    'search' => $this->data['Buscar']['search']
                ));
            }
            if (isset($this->data['Buscar']['date']))
            {
                $url = array_merge($url, array(
                    'date' => $this->data['Buscar']['date'],
                ));
            }
            $this->redirect($url);
        }
        set_time_limit(0);
        $limit = 20;
        if ($this->Session->check('ComprasPorPagina'))
            $limit = $this->Session->read('ComprasPorPagina');

        $options = array(
            'conditions' => array(),
            'fields' => array(
                'Compra.id',
                'Compra.estado',
                'Compra.created',
                'Compra.total',
                'Compra.boleta_pdf',
                'Compra.usuario_id',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno'
            ),
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    ),
                ),
            ),
            'order' => array(
                'Compra.id' => 'DESC'
            ),
            'limit' => $limit
        );
        if ( isset($this->params['named']['search']) && isset($this->params['named']['field']) )
        {
            if (in_array($this->params['named']['field'], array('Usuario.apellido_paterno', 'Usuario.email')))
            {
                $options['conditions'] = array_merge($options['conditions'], array(
                    $this->params['named']['field'].' LIKE' => '%'.$this->params['named']['search'].'%'
                ));
            }
            else
            {
                $options['conditions'] = array_merge($options['conditions'], array(
                    $this->params['named']['field'] => $this->params['named']['search']
                ));
            }
            $this->data['Buscar']['search'] = $this->params['named']['search'];
            $this->data['Buscar']['field'] = $this->params['named']['field'];
        }
        if ( isset($this->params['named']['date']) )
        {
            if ($dateIni = strtotime($this->params['named']['date'].'-01'))
            {
                $this->data['Buscar']['date'] = $this->params['named']['date'];
                $dateEnd = strtotime(date('Y-m', $dateIni + (60*60*24*31)).'-01');
                $options['conditions'] = array_merge($options['conditions'], array(
                    'Compra.created >=' => date('Y-m-d', $dateIni).' 00:00:00',
                    'Compra.created <' => date('Y-m-d', $dateEnd).' 00:00:00',
                ));
            }
        }

        $this->paginate = $options;
        $this->set('compras', $this->paginate());
    }
    function admin_buscar_devoluciones($busqueda = null)
    {
    
        set_time_limit(0);
        $limit = 20;
 

        $options = array(
            'conditions' => array(
                'Compra.devolucion' =>1,
                //'Devolucion.estado >=' =>1,
                'or' => array(
                    'Compra.id' => $this->data['Buscar']['search'],
                    'Devolucion.id' => $this->data['Buscar']['search']
                    )
            ),
            'fields' => array(
                'Compra.id',
                'Compra.estado',
                'Compra.created',
                'Compra.total',
                'Compra.boleta_pdf',
                'Compra.usuario_id',
                'Devolucion.estado',
                'Devolucion.codigo',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno'
            ),
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    ),
                ),
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devolucion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Devolucion.compra_id = Compra.id'
                    ),
                ),
            ),
            'order' => array(
                'Compra.id' => 'DESC'
            ),
            'limit' => $limit
        );
       // prx($options);
        if ( isset($this->params['named']['search']) && isset($this->params['named']['field']) )
        {
            if (in_array($this->params['named']['field'], array('Usuario.apellido_paterno', 'Usuario.email')))
            {
                $options['conditions'] = array_merge($options['conditions'], array(
                    $this->params['named']['field'].' LIKE' => '%'.$this->params['named']['search'].'%'
                ));
            }
            else
            {
                $options['conditions'] = array_merge($options['conditions'], array(
                    $this->params['named']['field'] => $this->params['named']['search']
                ));
            }
            $this->data['Buscar']['search'] = $this->params['named']['search'];
            $this->data['Buscar']['field'] = $this->params['named']['field'];
        }
        if ( isset($this->params['named']['date']) )
        {
            if ($dateIni = strtotime($this->params['named']['date'].'-01'))
            {
                $this->data['Buscar']['date'] = $this->params['named']['date'];
                $dateEnd = strtotime(date('Y-m', $dateIni + (60*60*24*31)).'-01');
                $options['conditions'] = array_merge($options['conditions'], array(
                    'Compra.created >=' => date('Y-m-d', $dateIni).' 00:00:00',
                    'Compra.created <' => date('Y-m-d', $dateEnd).' 00:00:00',
                ));
            }
        }

        $this->paginate = $options;
        $this->set('compras', $this->paginate());
    }

    function admin_view($id = null)
    {
        if ( ! $id )
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'index'));
        }

        $options = array(
            'conditions' => array(
                'Compra.id' => $id
            ),
            'contain' => array(
                'Despacho' => array(
                    'Direccion' => array(
                        'Comuna',
                        'Region'
                    )
                ),
                'Usuario' => array(
                    'Sexo'
                ),
                'Pago',
                'Comentario',
                'Boleta'
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
            $this->redirect(array('action' => 'index'));

        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $compra['Compra']['id']
            ),
            'fields' => array(
                'ProductosCompra.id',
                'ProductosCompra.producto_id',
                'ProductosCompra.compra_id',
                'ProductosCompra.descuento_id',
                'ProductosCompra.categoria',
                'ProductosCompra.talla',
                'ProductosCompra.valor',
                'ProductosCompra.devolucion_dinero',
                'ProductosCompra.estado',
                'Producto.id',
                'Producto.nombre',
                'Producto.codigo',
                'Producto.codigo_completo',
                'Producto.color_id',
                'Producto.foto',
                'Producto.precio',
                'Producto.oferta',
                'Producto.precio_oferta',
                'Color.id',
                'Color.nombre',
                'Color.codigo',
                'Descuento.id',
                'Descuento.nombre',
                'Descuento.tipo',
                'Descuento.descuento',
                'Descuento.web_tienda',
                'Descuento.codigo'
            ),
            'joins' => array(
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id',
                    )
                ),
                array(
                    'table' => 'sitio_colores',
                    'alias' => 'Color',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Color.id = Producto.color_id',
                    )
                ),
                array(
                    'table' => 'sitio_descuentos',
                    'alias' => 'Descuento',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Descuento.id = ProductosCompra.descuento_id',
                    )
                )
            ),
            'order' => array(
                'ProductosCompra.producto_id' => 'ASC'
            )
        );
        $compra['Compra']['descuento'] = 0;
        if ($productos = $this->Compra->ProductosCompra->find('all',$options))
        {
            foreach ($productos as $producto)
            {
                if ($producto['ProductosCompra']['descuento_id'] && $producto['Descuento']['id'])
                {
                    $precio = $producto['ProductosCompra']['valor'];
                    $descuento = 0;
                    if ($producto['Descuento']['tipo']=='POR')
                    {
                        $descuento = ($precio*$producto['Descuento']['descuento'])/100;
                        $descuento = $this->redondear_valor($descuento);
                    }
                    elseif ($producto['Descuento']['tipo']=='DIN')
                    {
                        $descuento = $producto['Descuento']['descuento'];
                    }
                    $compra['Compra']['descuento']+=$descuento;
                }
            }
        }

        $this->set(compact('compra', 'productos'));
    }
    function admin_viewmv($id = null)
    {
        if ( ! $id )
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'index'));
        }

        $options = array(
            'conditions' => array(
                'Compra.id' => $id
            ),
            'contain' => array(

                'Usuario' => array(
                    'Sexo'
                )
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
            $this->redirect(array('action' => 'index'));
        //prx($compra);

        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $compra['Compra']['id']
            ),
            'fields' => array(
                'ProductosCompra.id',
                'ProductosCompra.producto_id',
                'ProductosCompra.compra_id',
                'ProductosCompra.descuento_id',
                'ProductosCompra.categoria',
                'ProductosCompra.talla',
                'ProductosCompra.valor',
                'ProductosCompra.devolucion_dinero',
                'ProductosCompra.estado',
                'Producto.id',
                'Producto.nombre',
                'Producto.codigo',
                'Producto.codigo_completo',
                'Producto.color_id',
                'Producto.foto',
                'Producto.precio',
                'Producto.oferta',
                'Producto.precio_oferta',
                'Color.id',
                'Color.nombre',
                'Color.codigo',

            ),
            'joins' => array(
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id',
                    )
                ),
                array(
                    'table' => 'sitio_colores',
                    'alias' => 'Color',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Color.id = Producto.color_id',
                    )
                )
            ),
            'order' => array(
                'ProductosCompra.producto_id' => 'ASC'
            )
        );
        $compra['Compra']['descuento'] = 0;
        if ($productos = $this->Compra->ProductosCompra->find('all',$options))
        {
            foreach ($productos as $producto)
            {
                if ($producto['ProductosCompra']['descuento_id'] && $producto['Descuento']['id'])
                {
                    $precio = $producto['ProductosCompra']['valor'];
                    $descuento = 0;
                    if ($producto['Descuento']['tipo']=='POR')
                    {
                        $descuento = ($precio*$producto['Descuento']['descuento'])/100;
                        $descuento = $this->redondear_valor($descuento);
                    }
                    elseif ($producto['Descuento']['tipo']=='DIN')
                    {
                        $descuento = $producto['Descuento']['descuento'];
                    }
                    $compra['Compra']['descuento']+=$descuento;
                }
            }
        }

        $this->set(compact('compra', 'productos'));
    }


    function admin_cambio_direccion($id = null)
    {
        if (! empty($this->data))
        {
            if (isset($this->data['Compra']['id']) && $this->data['Compra']['id'])
            {
                $id = $this->data['Compra']['id'];
                $options = array(
                    'conditions' => array(
                        'Compra.id' => $id,
                    ),
                    'fields' => array(
                        'Compra.id',
                        'Compra.usuario_id',
                        'Compra.despacho_id'
                    ),
                    'recursive' => -1
                );
                if ($compra = $this->Compra->find('first',$options))
                {
                    if (isset($this->data['Despacho']['direccion_id']) && $this->data['Despacho']['direccion_id'])
                    {
                        $save = array(
                            'id' => $compra['Compra']['despacho_id'],
                            'direccion_id' => $this->data['Despacho']['direccion_id']
                        );
                        if ($this->Compra->Despacho->save($save))
                        {
                            $this->Session->setFlash(__('La direccion se a cambiado exitosamente', true));
                            $this->redirect(array('action' => 'cambio_direccion',$id));
                        }
                    }
                    elseif (isset($this->data['Direccion']) && $this->data['Direccion'])
                    {
                        $save = $this->data['Direccion'];
                        $save['usuario_id'] = $compra['Compra']['usuario_id'];
                        $this->Compra->Despacho->Direccion->create();
                        if ($this->Compra->Despacho->Direccion->save($save))
                        {
                            $save = array(
                                'id' => $compra['Compra']['despacho_id'],
                                'direccion_id' => $this->Compra->Despacho->Direccion->id
                            );
                            if ($this->Compra->Despacho->save($save))
                            {
                                $this->Session->setFlash(__('La direccion se a cambiado exitosamente', true));
                                $this->redirect(array('action' => 'cambio_direccion',$id));
                            }
                        }
                    }
                    $this->Session->setFlash(__('No fue posible guardar los cambios', true));
                }
            }
        }
        if ( ! $id )
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'index'));
        }
        $options = array(
            'conditions' => array(
                'Compra.id' => $id,
            ),
            'fields' => array(
                'Compra.id',
                'Compra.rural',
                'Compra.direccion_rural',
                'Compra.local',
                'Compra.usuario_id',
                'Compra.subtotal',
                'Compra.iva',
                'Compra.neto',
                'Compra.descuento',
                'Compra.total',
                'Compra.valor_despacho',
                'Compra.estado',
                'Direccion.id',
                'Direccion.calle',
                'Direccion.numero',
                'Direccion.depto',
                'Direccion.otras_indicaciones',
                'Direccion.comuna_id',
                'Direccion.region_id',
                'Direccion.codigo_postal',
                'Direccion.telefono',
                'Direccion.celular',
                'Direccion.nombre',
                'Comuna.nombre',
                'Region.nombre',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno',
                'Usuario.rut',
                'Usuario.email'
            ),
            'joins' => array(
                array(
                    'table' => 'sitio_despachos',
                    'alias' => 'Despacho',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Despacho.id = Compra.despacho_id'
                    )
                ),
                array(
                    'table' => 'sitio_direcciones',
                    'alias' => 'Direccion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Direccion.id = Despacho.direccion_id'
                    )
                ),
                array(
                    'table' => 'sitio_comunas',
                    'alias' => 'Comuna',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Comuna.id = Direccion.comuna_id'
                    )
                ),
                array(
                    'table' => 'sitio_regiones',
                    'alias' => 'Region',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Region.id = Comuna.region_id'
                    )
                ),
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                ),
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'index'));
        }
        $options = array(
            'conditions' => array(
                'Direccion.usuario_id' => $compra['Usuario']['id']
            ),
            'fields' => array(
                'Direccion.id',
                'Direccion.nombre',
                'Direccion.calle',
                'Direccion.numero',
                'Direccion.depto',
                'Direccion.otras_indicaciones',
                'Direccion.comuna_id',
                'Direccion.region_id',
                'Direccion.codigo_postal',
                'Direccion.telefono',
                'Direccion.celular'
            ),
            'contain' => array(
                'Comuna.nombre'
            )
        );
        $direcciones = $this->Compra->Despacho->Direccion->find('all',$options);
        $options = array(
            'fields' => array(
                'Comuna.id',
                'Comuna.nombre'
            ),
            'order' => array(
                'Comuna.nombre' => 'ASC'
            )
        );
        $comunas = $this->Compra->Despacho->Direccion->Comuna->find('list',$options);
        $options = array(
            'fields' => array(
                'Region.id',
                'Region.nombre'
            ),
            'order' => array(
                'Region.nombre' => 'ASC'
            )
        );
        $regiones = $this->Compra->Despacho->Direccion->Region->find('list',$options);
        $this->set(compact('compra','direcciones','regiones','comunas'));
    }

    private function redondear_valor($valor = 0)
    {
        if (is_numeric($valor) && $valor)
        {
            if ($valor % 10 > 0)
            {
                $valor = (int)($valor/10);
                $valor = ($valor*10)+10;
            }
            else
            {
                $valor = (int)($valor/10);
                $valor = $valor*10;
            }
            return $valor;
        }
        else
            return 0;
    }

    function admin_add() { }

    function admin_email_anulado($data){
        $compra_id = $data['Compra']['id'];
        $options = array(
            'conditions' => array(
                'Compra.id' => $compra_id
            ),
            'fields' => array(
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno',
                'Usuario.email',
                'Usuario.rut'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                )
            )
        );
        $compra = $this->Compra->find('first',$options);

        $this->set(compact('data','compra'));

        $this->Email->smtpOptions =array(
            'port' => '2525',
            'timeout' => '30',
            'auth' => true,
            'host' => 'smtp.mailtrap.io',
            'username' => 'f50be2e01f78cd',
            'password' => 'dbc99f0c5b8768'
        );
        /*$this->Email->smtpOptions =array(
			'port' => '587',
					'timeout' => '30',
					'auth' => true,
					'host' => 'mail.smtp2go.com',
								'username' => 'noresponder@skechers-chile.cl',
					'password' => 'eXV6M2k1cWp4Yzcw'
			);*/

        $personal = '8ff88ec03f-49a47a@inbox.mailtrap.io';
        // DATOS DESTINATARIO (CLIENTE)
        /*$copias = array(
            'ecom@skechers.com'
        );*/

        $template = 'anulacion_stock';
        //$this->Email->to = $compra['Usuario']['email'];
        $this->Email->to = $personal;
        //$this->Email->bcc	= $copias;
        $this->Email->subject = '[Skechers - Tienda en linea] Cambio de estado compra #' . $compra_id;
        $this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
        //$this->Email->replyTo = $this->email_skechers[2];
        $this->Email->sendAs = 'html';
        $this->Email->layout = 'devoluciones';
        $this->Email->template	= $template;
        $this->Email->delivery = 'smtp';
        if ($this->Email->send())
        {
            //$this->Compra->save(array('id' => $compra_id, 'mail_confirmacion' => 1));
            return true;
        }

        return false;
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
            $this->data['Compra']['fecha_enviado'] = null;
            $this->data['Compra']['despachado'] = null;
            $this->data['Compra']['enviado'] = null;
            $anulado_stock = $this->data['Compra']['estado'];

            if($anulado_stock == 11){
                // $email = $this->admin_email_anulado($this->data);
            }

            if ( $this->Compra->save($this->data) )
            {
                // guardar log
                $this->guardar_log($this->Auth->user('id'), 'compras', 'admin_edit', 'editar compra '.$id, $_SERVER['REMOTE_ADDR']);
                $this->Session->setFlash(__('Registro guardado correctamente', true));
                $this->redirect(array('action' => 'listar'));
            }
            else
            {
                $this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
            }
        }

        $options = array(
            'conditions' => array(
                'Compra.id' => $id
            ),
            'fields' => array(
                'Compra.id',
                'Compra.despacho_id',
                'Compra.usuario_id',
                'Compra.subtotal',
                'Compra.iva',
                'Compra.neto',
                'Compra.descuento',
                'Compra.total',
                'Compra.valor_despacho',
                'Compra.estado',
                'Compra.boleta',
                'Compra.numId',
                'Compra.cod_despacho',
                'Direccion.id',
                'Direccion.calle',
                'Direccion.numero',
                'Direccion.depto',
                'Direccion.telefono',
                'Direccion.celular',
                'Comuna.nombre',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno',
                'Usuario.email',
                'Usuario.rut'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_despachos',
                    'alias' => 'Despacho',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Despacho.id = Compra.despacho_id'
                    )
                ),
                array(
                    'table' => 'sitio_direcciones',
                    'alias' => 'Direccion',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Direccion.id = Despacho.direccion_id'
                    )
                ),
                array(
                    'table' => 'sitio_comunas',
                    'alias' => 'Comuna',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Comuna.id = Direccion.comuna_id'
                    )
                ),
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                )
            )
        );

        if (! $compra = $this->Compra->find('first',$options))
        {
            $this->Session->setFlash(__('Registro invalido.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->data['Compra'] = $compra['Compra'];

        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $compra['Compra']['id']
            ),
            'fields' => array(
                'ProductosCompra.id',
                'ProductosCompra.descuento_id',
                'ProductosCompra.talla',
                'ProductosCompra.valor',
                'ProductosCompra.devolucion_dinero',
                'ProductosCompra.estado',
                'ProductosCompra.devolucion',
                'Producto.id',
                'Producto.nombre',
                'Producto.foto',
                'Producto.codigo_completo',
                'Color.nombre',
                'Color.codigo',
                'Devolucion.id',
                'Devolucion.compra_id',
                'Devolucion.productos_compra_id',
                'Devolucion.tipo',
                'Devolucion.estado',
                'Devolucion.codigo',
                'Devolucion.fecha',
                'Devolucion.hora',
                'Devolucion.razon',
                'Devolucion.observaciones',
                'Devolucion.created',
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                ),
                array(
                    'table' => 'sitio_colores',
                    'alias' => 'Color',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Color.id = Producto.color_id'
                    )
                ),
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devolucion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Devolucion.productos_compra_id = ProductosCompra.id',
                        'Devolucion.compra_id' => $compra['Compra']['id']
                    )
                ),
            )
        );
        $productos = $this->Compra->ProductosCompra->find('all',$options);
        $token = $this->generarToken($compra['Compra']['id']);

        $this->set(compact('compra', 'productos','token'));
    }

    function admin_delete($id = null)
    {
        if ( ! $id )
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'index'));
        }
        if ( $this->Compra->delete($id) )
        {
            // guardar log
            $this->guardar_log($this->Auth->user('id'), 'compras', 'admin_delete', 'eliminar compra '.$id, $_SERVER['REMOTE_ADDR']);
            $this->Session->setFlash(__('Registro eliminado', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
        $this->redirect(array('action' => 'index'));
    }

    function admin_si($id = null)
    {
        if ( ! $id )
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'index'));
        }

        if ( ! empty($this->data) )
        {
            $b_old = array();
            if (isset($this->data['Compra']['n_boleta']) && $this->data['Compra']['n_boleta'])
            {
                $b_old['numero'] = $this->data['Compra']['boleta'];
                $b_old['compra_id'] = $id;

                $this->data['Compra']['boleta'] = $this->data['Compra']['n_boleta'];
                unset($this->data['Compra']['n_boleta']);
            }
            if (isset($this->data['Compra']['n_cod_despacho']) && $this->data['Compra']['n_cod_despacho'])
            {
                $b_old['cod_despacho'] = $this->data['Compra']['cod_despacho'];
                $b_old['compra_id'] = $id;

                $this->data['Compra']['cod_despacho'] = $this->data['Compra']['n_cod_despacho'];
                unset($this->data['Compra']['n_cod_despacho']);
            }
            if ($b_old)
            {
                $this->Compra->Boleta->create();
                $this->Compra->Boleta->save($b_old);
            }
            $fechita = (date('Y-m-d H:m:s'));
            $this->data['Compra']['fecha_enviado'] = $fechita;
            $this->data['Compra']['despachado'] = 1;
            if ( $this->Compra->save($this->data['Compra']) )
            {
                // guardar log
                $this->guardar_log($this->Auth->user('id'), 'compras', 'admin_si', 'despachar compra '.$id, $_SERVER['REMOTE_ADDR']);

                $options = array(
                    'conditions' => array(
                        'Compra.id' => $id
                    ),
                    'contain' => array(
                        'Despacho' => array(
                            'Direccion' => array(
                                'Comuna',
                                'Region'
                            )
                        ),
                        'Usuario' => array('Sexo'),
                        'Pago'
                    )
                );
                $compra = $this->Compra->find('first',$options);

                $enviarMail = true;
                if ($this->data['Compra']['local']==1) // cuando la compra es con despacho local no envia mail de despacho
                    $enviarMail = false;

                if ($enviarMail)
                {
                    $this->set(compact('compra'));
                    //EMAIL

                    $this->Email->smtpOptions =array(
                        'port' => '587',
                        'timeout' => '30',
                        'auth' => true,
                        'host' => 'mail.smtp2go.com',
                        'username' => 'noresponder@skechers-chile.cl',
                        'password' => 'eXV6M2k1cWp4Yzcw'
                    );

                    $copias = array(
                        //'ventas@skechers-chile.cl',
                        //'pyanez@skechers.com',
                        //'store383@skechers.com',
                        //'cherrera@skechers.cl',
                        //'ehenriquez@andain.cl',
                        'solanger@skechers.com',
                        'rsilva@skechers.com',
                        //'sdelvillar@andain.cl'
                    );
                    // DATOS DESTINATARIO (CLIENTE)
                    $this->Email->to = $compra['Usuario']['email'];
                    $this->Email->bcc	= $copias;
                    $this->Email->subject = '[Skechers - Tienda en linea] Despacho Enviado #' . $id;
                    $this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
                    $this->Email->replyTo = $this->email_skechers[2];
                    $this->Email->sendAs = 'html';
                    $this->Email->template	= 'despacho';
                    $this->Email->delivery = 'smtp';
                    if ($this->Email->send())
                    {
                        $this->Compra->save(array('id' => $id, 'mail_confirmacion' => 1));
                    }else{
                        die('error');
                    }
                    // FIN EMAIL
                }
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
            }
        }
        else
        {
            $this->data = $this->Compra->read(null, $id);
        }

        $options = array(
            'conditions' => array(
                'Compra.id' => $id
            ),
            'contain' => array(
                'Despacho' => array(
                    'Direccion' => array(
                        'Comuna',
                        'Region'
                    )
                ),
                'Usuario' => array('Sexo'),
                'Pago'
            )
        );
        $compra = $this->Compra->find('first',$options);
        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $compra['Compra']['id'],
                'ProductosCompra.estado' => array(0,1)
            ),
            //'fields' => array('ProductosCompra.producto_id'),
            'order' => array('ProductosCompra.producto_id' => 'ASC')
        );
        $list_productos = $this->Compra->ProductosCompra->find('all',$options);
        $prod_ant = $talla_ant = $index_ant = 0;
        $cont = 1;

        $this->loadModel('Descuento');
        $compra['Descuento'] = 0;
        $desc_prod = $descuento = array();
        foreach ( $list_productos as $index => $producto )
        {
            if ( $producto['ProductosCompra']['producto_id'] == $prod_ant && $producto['ProductosCompra']['talla'] == $talla_ant )
            {
                $cont++;
                $productos[$index_ant]['cantidad'] = $cont;
                unset($list_productos[$index]);
            }
            else
            {
                $prod_ant = $producto['ProductosCompra']['producto_id'];
                $talla_ant = $producto['ProductosCompra']['talla'];
                $index_ant = $index;
                $cont = 1;
                $options = array(
                    'conditions' => array(
                        'Producto.id' => $producto['ProductosCompra']['producto_id']
                    ),
                    'contain' => array('Color')
                );
                $productos[$index] = $this->Compra->Producto->find('first',$options);
                $productos[$index]['cantidad'] = $cont;
                $productos[$index]['ProductosCompra'] = $producto['ProductosCompra'];
                //$productos[$index]['Producto']['precio'] = $productos[$index]['Producto']['precio_oferta'] = $producto['ProductosCompra']['valor'];
                $productos[$index]['Stock'] = array('talla' => $producto['ProductosCompra']['talla']);
            }
            if ( $producto['ProductosCompra']['descuento_id'] )
            {
                $options = array(
                    'conditions' => array(
                        'Descuento.id' => $producto['ProductosCompra']['descuento_id']
                    )
                );
                $desc_prod = $this->Descuento->find('first',$options);
                $productos[$index]['Descuento'] = $desc_prod['Descuento'];
                if ( $desc_prod )
                {
                    if ( $desc_prod['Descuento']['tipo'] == 'POR')
                    {
                        if ( $productos[$index]['Producto']['oferta'] == 1 )
                            $compra['Descuento'] = $compra['Descuento'] + (( $productos[$index]['Producto']['precio_oferta'] * $desc_prod['Descuento']['descuento'] ) / 100 );
                        else
                            $compra['Descuento'] = $compra['Descuento'] + (( $productos[$index]['Producto']['precio'] * $desc_prod['Descuento']['descuento'] ) / 100 );
                        if ( ($compra['Descuento'] % 10) > 0 )
                            $compra['Descuento'] = (((int)($compra['Descuento']/10))*10)+10;
                        else
                            $compra['Descuento'] = ((int)($compra['Descuento']/10))*10;
                    }
                    elseif ( $desc_prod['Descuento']['tipo'] == 'DIN')
                    {
                        $compra['Descuento'] = $compra['Descuento'] + $desc_prod['Descuento']['descuento'];
                    }
                    $descuento = $desc_prod;
                }
            }
        }
        if ( $descuento )
        {
            if ( $descuento['Descuento']['tipo'] == 'POR')
                $descuento['descripcion'] = $descuento['Descuento']['descuento'] . '% en un producto';
            elseif ( $descuento['Descuento']['tipo'] == 'DIN')
                $descuento['descripcion'] = $descuento['Descuento']['descuento'];
        }
        $options = array(
            'conditions' => array(
                'Comentario.compra_id' => $id
            ),
            'fields' => array(
                'Comentario.id',
                'Comentario.nombre',
                'Comentario.comentario',
                'Comentario.compra_id',
                'Comentario.created'
            ),
            'order' => array('Comentario.id' => 'DESC')
        );
        $comentarios = $this->Compra->Comentario->find('all',$options);
        $this->loadModel('Trabajador');
        $options = array(
            'fields' => array(
                'Trabajador.nombre', 'Trabajador.nombre'
            ),
            'order' => array(
                'Trabajador.nombre' => 'ASC'
            )
        );
        $trabajadores = $this->Trabajador->find('list',$options);
        $this->set(compact('compra', 'productos', 'descuento', 'comentarios', 'trabajadores'));
    }

    function admin_comentario()
    {
        if (! empty($this->data))
        {
            if (! $this->data['Compra']['id'])
            {
                $this->Session->setFlash(__('Registro inválido', true));
                $this->redirect($this->referer());
            }
            if (! $this->data['Comentario'][0]['nombre'])
            {
                $this->Session->setFlash(__('Debe ingresar su nombre', true));
                $this->redirect($this->referer());
            }
            if (! $this->data['Comentario'][0]['comentario'])
            {
                $this->Session->setFlash(__('Debe ingresar un comentario', true));
                $this->redirect($this->referer());
            }
            $new_comentario = array(
                'Comentario' => array(
                    'compra_id' => $this->data['Compra']['id'],
                    'nombre' => $this->data['Comentario'][0]['nombre'],
                    'comentario' => $this->data['Comentario'][0]['comentario']
                )
            );
            $this->Compra->Comentario->create();
            if ($this->Compra->Comentario->save($new_comentario))
            {
                $this->Session->setFlash(__('Su comentario ha sido ingresado exitosamente.', true));
                $this->redirect($this->referer());
            }
            else
            {
                $this->Session->setFlash(__('Su comentario no pudo ser ingresado.', true));
                $this->redirect($this->referer());
            }
        }
        else
        {
            $this->redirect($this->referer());
        }
    }

    function admin_cambiar($id = null)
    {
        if ( ! $id )
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'devuelto'));
        }
        $options = array(
            'conditions' => array(
                'Compra.id' => $id
            ),
            'contain' => array(
                'Despacho' => array(
                    'Direccion' => array(
                        'Comuna',
                        'Region'
                    )
                ),
                'Usuario' => array('Sexo'),
                'Pago'
            )
        );
        $compra = $this->Compra->find('first',$options);
        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $compra['Compra']['id']
            ),
            //'fields' => array('ProductosCompra.producto_id'),
            'order' => array('ProductosCompra.producto_id' => 'ASC')
        );
        $productos = $this->Compra->ProductosCompra->find('all',$options);
        $this->loadModel('Descuento');
        $total_descuento = 0;
        $lista_tallas = array();
        foreach ( $productos as $index => $producto )
        {
            $options = array(
                'conditions' => array(
                    'Producto.id' => $producto['ProductosCompra']['producto_id']
                ),
                'contain' => array('Color')
            );
            $datos_producto = $this->Compra->Producto->find('first',$options);
            $productos[$index] = array(
                'Producto' => $datos_producto['Producto'],
                'Color'		=> $datos_producto['Color'],
                //'Stock' => $datos_producto['Stock'],
                'ProductosCompra' => $producto['ProductosCompra']
            );
            unset($productos[$index]['Producto']['Color']);
            if ( $producto['ProductosCompra']['descuento_id'] && $producto['ProductosCompra']['estado'] == 0 || $producto['ProductosCompra']['estado'] == 1 )
            {
                $options = array(
                    'conditions' => array(
                        'Descuento.id' => $producto['ProductosCompra']['descuento_id']
                    )
                );
                $descuento = $this->Descuento->find('first',$options);
                $productos[$index]['Descuento'] = $descuento['Descuento'];
                if ($descuento['Descuento']['tipo'] == 'POR')
                {
                    if ( $productos[$index]['Producto']['oferta'] == 0 )
                        $total_descuento = $total_descuento + (( $productos[$index]['Producto']['precio'] * $descuento['Descuento']['descuento'] ) / 100 );
                    elseif ( $productos[$index]['Producto']['oferta'] == 1 )
                        $total_descuento = $total_descuento + (( $productos[$index]['Producto']['precio_oferta'] * $descuento['Descuento']['descuento'] ) / 100 );
                }
                elseif ( $descuento['Descuento']['tipo'] == 'DIN' )
                    $total_descuento = $total_descuento + $descuento['Descuento']['descuento'];
            }
        }
        if ( empty($this->data) )
        {
            $this->data['Compra']['id'] = $compra['Compra']['id'];
        }
        else
        {
            if ( isset($this->data['Compra']['estado']) && $this->data['Compra']['estado'] )
            {
                $actualiza_compra = array(
                    'Compra' => array(
                        'id' => $id,
                        'estado' => $this->data['Compra']['estado']
                    )
                );
                if ( $this->Compra->save($actualiza_compra) )
                {
                    // guardar log
                    $this->guardar_log($this->Auth->user('id'), 'compras', 'admin_cambiar', 'cambiar compra '.$id, $_SERVER['REMOTE_ADDR']);
                    $this->Session->setFlash(__('Registro guardado', true));
                    $this->redirect(array('action' => 'devuelto'));
                }
                else
                {
                    $this->Session->setFlash(__('No fue posible guardar los cambios, porfavor intente nuevamente', true));
                    $this->redirect(array('action' => 'cambiar', $id));
                }
            }
        }
        $this->set(compact('compra', 'productos', 'total_descuento', 'lista_productos'));
    }

    function ajax_cantidad_compras_por_pagina()
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        if (! isset($this->params['form']['cantidad']))
            die(false);
        if (! $this->params['form']['cantidad'])
            die(false);
        if (! is_numeric($this->params['form']['cantidad']))
            die(false);
        $this->Session->write('ComprasPorPagina',$this->params['form']['cantidad']);
        die('OK');
    }

    function ajax_lista_productos()	// REVISAR
    {
        if ( isset($this->params['form']['tipo']) && $this->params['form']['tipo'] )
        {
            $options = array(
                'conditions' => array(
                    'Categoria.publico' => 1
                ),
                'fields' => array(
                    'Categoria.id',
                    'Categoria.id'
                )
            );
            $categorias_publicas = $this->Compra->Producto->Categoria->find('list',$options);

            $options = array(
                'conditions' => array(
                    'Producto.categoria_id' => $categorias_publicas
                ),
                'fields' => array(
                    'Producto.id',
                    'Producto.codigo',
                    'Producto.color_id',
                    'Producto.oferta',
                    'Producto.precio',
                    'Producto.precio_oferta'
                ),
                'contain' => array(
                    'Color' => array(
                        'fields' => array(
                            'Color.id',
                            'Color.codigo'
                        ),
                    ),
                    'Talla' => array(
                        'conditions' => array(
                            'Talla.cantidad >' => 3
                        ),
                        'fields' => array(
                            'Talla.id',
                            'Talla.talla',
                            'Talla.cantidad'
                        ),
                        'limit' => 2,
                        'order' => array('Talla.talla' => 'ASC')
                    ),
                    //'Stock' => array('fields' => array('Stock.id', 'Stock.talla', 'Stock.cantidad'))
                ),
                'order' => array('Producto.codigo' => 'ASC')
            );

            if ($this->params['form']['tipo'] != 2)
            {
                $options['conditions']['OR'] = array(
                    'Producto.precio <=' => $this->params['form']['valor'],
                    'Producto.precio_oferta <=' => $this->params['form']['valor']
                );
            }

            $productos = $this->Compra->Producto->find('all',$options);
            $lista_productos = array();
            foreach ($productos as $index => $producto)
            {
                if ($producto['Talla'])
                {
                    if (! $lista_productos)
                    {
                        $lista_productos = array(0 => array('Producto' => array('codigo' => '- Seleccione Producto')));
                    }

                    if ( $this->params['form']['tipo'] == 2 )
                    {
                        if ( $producto['Producto']['oferta'] )
                            $lista_productos[$producto['Producto']['id']] = array('Producto' => array('codigo' => $producto['Producto']['codigo'] . $producto['Color']['codigo'] . ' - $ ' . number_format($producto['Producto']['precio_oferta'], 0, ",", ".")));
                        else
                            $lista_productos[$producto['Producto']['id']] = array('Producto' => array('codigo' => $producto['Producto']['codigo'] . $producto['Color']['codigo'] . ' - $ ' . number_format($producto['Producto']['precio'], 0, ",", ".")));
                    }
                    else
                    {
                        if ( $producto['Producto']['oferta'] )
                        {
                            if ( $producto['Producto']['precio_oferta'] <= $this->params['form']['valor'] )
                            {
                                $lista_productos[$producto['Producto']['id']] = array('Producto' => array('codigo' => $producto['Producto']['codigo'] . $producto['Color']['codigo'] . ' - $ ' . number_format($producto['Producto']['precio_oferta'], 0, ",", ".")));
                            }
                        }
                        else
                        {
                            if ( $producto['Producto']['precio'] <= $this->params['form']['valor'] )
                            {
                                $lista_productos[$producto['Producto']['id']] = array('Producto' => array('codigo' => $producto['Producto']['codigo'] . $producto['Color']['codigo'] . ' - $ ' . number_format($producto['Producto']['precio'], 0, ",", ".")));
                            }
                        }
                    }
                }
            }

            if ($lista_productos)
            {
                $productos = $lista_productos;
            }
            else
            {
                $productos = array(
                    0 => array(
                        'Producto' => array(
                            'codigo' => 'No se encontraron resultados'
                        )
                    )
                );
            }
        }
        else
        {
            $productos = array(
                0 => array(
                    'Producto' => array(
                        'id' => 0,
                        'codigo' => 'No se encontraron resultados'
                    )
                )
            );
        }
        $this->set(compact('productos'));
    }

    function ajax_datos_producto()
    {
        if (! $this->Session->check('Auth.Administrador'))
            return false;
        if (! $this->params['form']['id'])
            return false;
        $options = array(
            'conditions' => array(
                'Categoria.publico' => 1
            ),
            'fields' => array(
                'Categoria.id',
                'Categoria.id'
            )
        );
        $categorias = $this->Compra->Producto->Categoria->find('list', $options);
        if (! $categorias)
            die(false);

        $options = array(
            'conditions' => array(
                'Producto.id' => $this->params['form']['id'],
                'Producto.categoria_id' => $categorias
            ),
            'fields' => array(
                'Producto.id',
                'Producto.nombre',
                'Producto.color_id',
                'Producto.foto',
                'Producto.codigo',
                'Producto.precio',
                'Producto.oferta',
                'Producto.precio_oferta'
            ),
            'contain' => array(
                'Color' => array(
                    'fields' => array(
                        'Color.id',
                        'Color.nombre',
                        'Color.codigo'
                    )
                ),
                'Talla' => array(
                    'fields' => array(
                        'Talla.id',
                        'Talla.producto_id',
                        'Talla.talla'
                    )
                )
            )
        );
        $producto = $this->Compra->Producto->find('first', $options);
        if (! $producto)
            die(false);

        $respuesta = array(
            'id' => $producto['Producto']['id'],
            'nombre' => $producto['Producto']['nombre'],
            'foto' => $producto['Producto']['foto']['mini'],
            'color' => $producto['Color']['nombre'],
            'codigo' => $producto['Producto']['codigo'].$producto['Color']['codigo'],
            'precio' => $producto['Producto']['precio'],
            'tallas' => ''
        );
        if ($producto['Producto']['oferta'])
            $respuesta['precio'] = $producto['Producto']['precio_oferta'];

        if ($producto['Talla'])
        {
            foreach ($producto['Talla'] as $talla)
            {
                $respuesta['tallas'][$talla['talla']] = $talla['talla'];
            }
            $respuesta['tallas'] = implode(', ',$respuesta['tallas']);
        }
        die(json_encode($respuesta));
    }

    function ajax_cambiar ()
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        if (! $this->params['form'])
            die('ERROR_NO_DATA_1');

        if (! $this->params['form']['anterior'] || ! $this->params['form']['nuevo'] || ! $this->params['form']['talla'])
            die('ERROR_NO_DATA_2');

        $options = array(
            'conditions' => array(
                'ProductosCompra.id' => $this->params['form']['anterior']
            )
        );
        if (! $producto_anterior = $this->Compra->ProductosCompra->find('first',$options))
            die('ERROR_PRODUCT1');

        $options = array(
            'conditions' => array(
                'Producto.id' => $this->params['form']['nuevo']
            ),
            'contain' => array('Categoria')
        );
        if (! $producto = $this->Compra->Producto->find('first',$options))
            die('ERROR_PRODUCT2');
        $options = array(
            'conditions' => array(
                'Compra.id' => $producto_anterior['ProductosCompra']['compra_id']
            )
        );
        if (! $compra = $this->Compra->find('first', $options))
            die('ERROR_SALE');

        // valor del producto nuevo
        if ( isset($producto['Producto']['oferta']) && $producto['Producto']['oferta'] == 1 )
            $precio = $producto['Producto']['precio_oferta'];
        else
            $precio = $producto['Producto']['precio'];

        // si existe descuento
        $descontar = 0;
        if ( isset($producto_anterior['ProductosCompra']['descuento_id']) && $producto_anterior['ProductosCompra']['descuento_id'] )
        {
            $this->loadModel('Descuento');
            $options = array(
                'conditions' => array(
                    'Descuento.id' => $producto_anterior['ProductosCompra']['descuento_id']
                )
            );
            if ($descuento = $this->Descuento->find('first',$options))
            {
                if ( isset($descuento['Descuento']['tipo']) && $descuento['Descuento']['tipo'] == 'DIN' )
                    $descontar = $descuento['Descuento']['descuento'];
                elseif ( isset($descuento['Descuento']['tipo']) && $descuento['Descuento']['tipo'] == 'POR' )
                    $descontar = (( $precio * $descuento['Descuento']['descuento'] ) / 100 );
            }
        }

        // verifica que el precio no sea menor a 0
        if ( $precio <= 0 )
            $precio = 0;

        // datos producto nuevo
        $producto_nuevo = array(
            'ProductosCompra' => array(
                'producto_id' => $this->params['form']['nuevo'],
                'compra_id' => $compra['Compra']['id'],
                'descuento_id' => $producto_anterior['ProductosCompra']['descuento_id'],
                'talla' => $this->params['form']['talla'],
                'valor' => $precio - $descontar,
                'categoria' => $producto['Categoria']['nombre'],
                'estado' => 1
            )
        );

        // actualiza estado de productos
        $old_product = array(
            'ProductosCompra' => array(
                'id' => $producto_anterior['ProductosCompra']['id'],
                'estado' => 2
            )
        );
        if (isset($this->params['form']['motivo']) && $this->params['form']['motivo'])
            $old_product['ProductosCompra']['razon'] = $this->params['form']['motivo'];

        if (! $this->Compra->ProductosCompra->save($old_product) )
            die('ERROR_SAVE1');

        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $compra['Compra']['id'],
                'ProductosCompra.estado' => array(0,1)
            )
        );
        $valores = $this->Compra->ProductosCompra->find('all',$options);

        // actualizar compra
        $subtotal = 0;
        foreach ( $valores as $valor )
            if ( isset($valor['ProductosCompra']['valor']) && $valor['ProductosCompra']['valor'] >= 1 )
                $subtotal = $subtotal + $valor['ProductosCompra']['valor'];

        $subtotal = ((int)(( $subtotal + $precio ) / 10 )) * 10;
        $neto = ((int)(( $subtotal / 1.19 ) / 10 )) * 10;
        $iva = $subtotal - $neto;
        $total = $subtotal - $descontar;

        $update_sale = array(
            'Compra' => array(
                'id' => $compra['Compra']['id'],
                'subtotal' => $subtotal,
                'iva' => $iva,
                'neto' => $neto,
                'total' => $total
            )
        );
        if (! $this->Compra->save($update_sale) )
            die('ERROR_SAVE2');

        // ingresar producto nuevo
        $this->Compra->ProductosCompra->create();
        if (! $this->Compra->ProductosCompra->save($producto_nuevo) )
            die('ERROR_SAVE3');

        die('READY');
    }

    function ajax_generar_descuento_compra()
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        $respuesta = array(
            'estado' 	=> 'ERROR',
            'texto'		=> '',
            'descuento'	=> '',
            'codigo'		=> '',
            'compra'		=> ''
        );
        if (isset($this->params['form']['compra']) && isset($this->params['form']['valor_cupon']))
        {
            if ($this->params['form']['compra'] && $this->params['form']['valor_cupon'])
            {
                $compra = $this->params['form']['compra'];
                $cod = "";
                for ($i=0; $i<4; $i++)
                    if ( rand(1,6) % 2 == 1 )
                        $cod .= chr(rand(65,90));
                    else
                        $cod .= chr(rand(48,57));
                $codigo = 'DEV-SAL'.$compra.$cod;
                $descuento = 0;
                // generar descuento
                $options = array(
                    'conditions' => array(
                        'Categoria.publico' => 1
                    ),
                    'fields' => array(
                        'Categoria.id',
                        'Categoria.id'
                    )
                );
                $lista_categorias = $this->Compra->Producto->Categoria->find('list',$options);
                $categorias = array();
                foreach ($lista_categorias as $categoria)
                {
                    $categorias[] = array('categoria_id'=> $categoria);
                }
                // 60 dias
                $caducidad = date('Y-m-d', strtotime(date('Y-m-d')) + (60 * 60 * 24 * 60));
                $new_descuento = array(
                    'Descuento' => array(
                        'nombre' => 'SALDO DEVOLUCION #'.$this->params['form']['compra'],
                        'cantidad' => 1,
                        'fecha_caducidad' => $caducidad,
                        'codigo' => $codigo,
                        'contador' => 0,
                        'tipo' => 'DIN',
                        'descuento' => $this->params['form']['valor_cupon']
                    ),
                    'Categoria'	=> $categorias
                );
                // generar descuento
                $this->Compra->Producto->Categoria->Descuento->create();
                if ( $this->Compra->Producto->Categoria->Descuento->saveAll($new_descuento) )
                {
                    $respuesta = array(
                        'estado' => 'OK',
                        'texto' => '',
                        'descuento' => $this->Compra->Producto->Categoria->Descuento->id,
                        'codigo' => $codigo,
                        'compra' => $compra
                    );
                }
                else
                {
                    $respuesta = array(
                        'estado' => 'ERROR',
                        'texto' => 'No fue posible generar el descuento. Intentelo nuevamente.',
                        'descuento' => '',
                        'codigo' => '',
                        'compra' => ''
                    );
                }
            }
            else
            {
                $respuesta = array(
                    'estado' => 'ERROR',
                    'texto' => 'Datos invalidos. Lo sentimos, no se puede realizar esta operacion.',
                    'descuento' => '',
                    'codigo' => '',
                    'compra' => ''
                );
            }
        }
        else
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'texto' => 'Datos invalidos. Lo sentimos, no se puede realizar esta operacion.',
                'descuento' => '',
                'codigo' => '',
                'compra' => ''
            );
        }
        $this->set(compact('respuesta'));
    }

    function admin_autodescuento($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'listar'));
        }
        $respuesta = array();
        $options = array(
            'conditions' => array(
                'Compra.id' => $id,
                'Compra.estado' => array(3,4)
            ),
            'fields' => array(
                'Compra.id',
                'Compra.usuario_id',
                'Compra.total'
            ),
            'contain' => array(
                'Usuario' => array(
                    'fields' => array(
                        'Usuario.id',
                        'Usuario.sexo_id',
                        'Usuario.nombre',
                        'Usuario.email'
                    )
                )
            )
        );
        if ($compra = $this->Compra->find('first',$options))
        {
            // GENERAR CODIGO DE DESCUENTO
            $cod = "";
            for ($i=0; $i<4; $i++)
                if ( rand(1,6) % 2 == 1 )
                    $cod .= chr(rand(65,90));
                else
                    $cod .= chr(rand(48,57));
            $codigo = 'DEV-COMP'.$compra['Compra']['id'].$cod;
            // generar descuento
            $options = array(
                'conditions' => array(
                    'Categoria.publico' => 1
                ),
                'fields' => array(
                    'Categoria.id',
                    'Categoria.id'
                )
            );
            $lista_categorias = $this->Compra->Producto->Categoria->find('list',$options);
            $categorias = array();
            foreach ($lista_categorias as $categoria)
            {
                $categorias[] = array('categoria_id'=> $categoria);
            }
            // 60 dias
            $caducidad = date('Y-m-d', strtotime(date('Y-m-d')) + (60 * 60 * 24 * 60));
            $new_descuento = array(
                'Descuento' => array(
                    'nombre' => 'COMPRA DEVOLUCION #'.$compra['Compra']['id'],
                    'cantidad' => 1,
                    'fecha_caducidad' => $caducidad,
                    'codigo' => $codigo,
                    'contador' => 0,
                    'tipo' => 'DIN',
                    'descuento' => $compra['Compra']['total']
                ),
                'Categoria'	=> $categorias
            );
            // generar descuento
            $this->Compra->Producto->Categoria->Descuento->create();
            if ( $this->Compra->Producto->Categoria->Descuento->saveAll($new_descuento) )
            {
                $respuesta = array(
                    'anulacion'	=> 'no pudo ser anulada',
                    'envio_mail'	=> 'no se pudo enviar',
                    'descuento'	=> $this->Compra->Producto->Categoria->Descuento->id,
                    'codigo'		=> $codigo,
                    'compra'		=> $compra['Compra']['id'],
                    'total'		=> $compra['Compra']['total']
                );
                // actualizar estado de compra a anulado
                $update_compra = array(
                    'Compra' => array(
                        'id' => $compra['Compra']['id'],
                        'estado' => 2
                    )
                );
                if ( $this->Compra->save($update_compra) )
                {
                    // guardar log
                    $this->guardar_log($this->Auth->user('id'), 'compras', 'admin_autodescuento', 'anular compra '.$id.' y generar descuento', $_SERVER['REMOTE_ADDR']);
                    $respuesta['anulacion'] = 'fue anulada exitosamente';
                }

                $encabezado = 'Estimado '.$compra['Usuario']['nombre'];
                if (isset($compra['Usuario']['sexo_id']) && $compra['Usuario']['sexo_id'] == 2)
                {
                    $encabezado = 'Estimada '.$compra['Usuario']['nombre'];
                }
                $mensaje =	'<p style="color:#7d5179;; float:left; width:520px; margin:0 0 5px;">'.
                    '<b style="font-weight:bold;">'.$encabezado.'</b>'.
                    '</p>'.
                    '<p style="color:#666; float:left; width:520px; margin:0 0 5px;">'.
                    '¡Se ha generado un descuento para ti por $'.$compra['Compra']['total'].'!'.
                    '</p>'.
                    '<p style="color:#666; float:left; width:520px; margin:0 0 5px;">'.
                    'A traves de nuestro sitio <a href="http://store.skechers-chile.cl">store.skechers-chile.cl</a> podras utilizar tu descuento al comprar alguno de nuestros productos, ingresando el siguiente codigo:'.
                    '</p>'.
                    '<p style="color:#666; float:left; width:520px; margin:0 0 5px;">'.
                    '<b>'.$codigo.'</b>'.
                    '</p>'.
                    '<p style="color:#666; float:left; width:520px; margin:0 0 5px;">'.
                    'Este descuento es valido hasta el '.date('d-m-Y', strtotime($caducidad)).
                    '</p>'.
                    '<p style="color:#666; float:left; width:520px; margin:0 0 5px;">'.
                    ''.
                    '</p>'.
                    '<p style="color:#666; float:left; width:520px; margin:0 0 5px;">'.
                    ''.
                    '</p>';

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
                // DATOS DESTINATARIO (CLIENTE)
                $this->Email->to = $compra['Usuario']['email'];
                $this->Email->bcc	= array('ventas@skechers-chile.cl', 'pyanez@skechers.com', 'store383@skechers.com', 'cherrera@skechers.cl', 'ehenriquez@andain.cl', 'sdelvillar@andain.cl','solanger@skechers.com');
                $this->Email->subject = '[Skechers - Tienda en linea] '.$new_descuento['Descuento']['nombre'];
                $this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
                $this->Email->replyTo = $this->email_skechers[2];
                $this->Email->sendAs = 'html';
                $this->Email->template	= 'mensaje';
                $this->Email->delivery = 'smtp';
                if ( $this->Email->send() )
                {
                    $respuesta['envio_mail'] = 'fue enviado exitosamente';
                }
            }
            else
            {
                $this->Session->setFlash(__('No fue posible generar el descuento', true));
            }
        }
        else
        {
            $this->Session->setFlash(__('Registro invalido', true));
            $this->redirect(array('action' => 'listar'));
        }
        $this->set(compact('respuesta'));
    }

    function ajax_enviar_descuento_compra()
    {
        if (! isset($this->params['form']['descuento']))
            die('Datos invalidos, no fue posible enviar el correo.');
        if (! $this->params['form']['descuento'])
            die('Datos invalidos, no fue posible enviar el correo.');
        if (! isset($this->params['form']['compra']))
            die('Datos invalidos, no fue posible enviar el correo.');
        if (! $this->params['form']['compra'])
            die('Datos invalidos, no fue posible enviar el correo.');

        $options = array(
            'conditions' => array(
                'Descuento.id' => $this->params['form']['descuento']
            ),
            'fields' => array(
                'Descuento.id',
                'Descuento.nombre',
                'Descuento.fecha_caducidad',
                'Descuento.codigo',
                'Descuento.descuento'
            ),
            'recursive' => -1
        );
        if (!$descuento = $this->Compra->Producto->Categoria->Descuento->find('first',$options))
            die('Datos invalidos, no fue posible enviar el correo.');

        $options = array(
            'conditions' => array(
                'Compra.id' => $this->params['form']['compra']
            ),
            'fields' => array(
                'Compra.id',
                'Compra.usuario_id'
            ),
            'contain' => array(
                'Usuario' => array(
                    'fields' => array(
                        'Usuario.id',
                        'Usuario.sexo_id',
                        'Usuario.nombre',
                        'Usuario.email'
                    )
                )
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
            die('Datos invalidos, no fue posible enviar el correo.');

        $mensaje =	'<p style="color:#7d5179;; float:left; width:520px; margin:0 0 5px;"><b style="font-weight:bold;">';


        if (isset($compra['Usuario']['sexo_id']) && $compra['Usuario']['sexo_id'] == 2)
            $mensaje.='Estimada ';
        else
            $mensaje.='Estimado ';
        $mensaje.=$compra['Usuario']['nombre'].
            '</b>
		</p>
		<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
			¡Se ha generado un descuento para ti por $'.$descuento['Descuento']['descuento'].'!
		</p>
		<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
			A traves de nuestro sitio
			<a href="http://store.skechers-chile.cl">store.skechers-chile.cl</a>
			podras utilizar tu descuento al comprar alguno de nuestros productos, ingresando el siguiente codigo:
		</p>
		<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
			<b>'.$descuento['Descuento']['codigo'].'</b>
		</p>
		<p style="color:#666; float:left; width:520px; margin:0 0 5px;">
			Este descuento es valido hasta el '.date('d-m-Y', strtotime($descuento['Descuento']['fecha_caducidad'])).
            '</p>
		<p style="color:#666; float:left; width:520px; margin:0 0 5px;"></p>
		<p style="color:#666; float:left; width:520px; margin:0 0 5px;"></p>';

        $this->set(compact('mensaje'));
        $copias = array(
            'ventas@skechers-chile.cl',
            'pyanez@skechers.com',
            'store383@skechers.com',
            'cherrera@skechers.cl',
            'ehenriquez@andain.cl',
            'sdelvillar@andain.cl',
            'solanger@skechers.com'
        );
        //EMAIL
        $this->Email->smtpOptions = array(
            'port' => '25',
            'timeout' => '30',
            'auth' => true,
            'host' => 'skechers-chile.cl',
            'username' => 'noreply@skechers-chile.cl',
            'password' => 'andainandain'
        );
        // DATOS DESTINATARIO (CLIENTE)
        $this->Email->to = $compra['Usuario']['email'];
        $this->Email->bcc	= $copias;
        $this->Email->subject = '[Skechers - Tienda en linea] '.$descuento['Descuento']['nombre'];
        $this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
        $this->Email->replyTo = $this->email_skechers[2];
        $this->Email->sendAs = 'html';
        $this->Email->template	= 'mensaje';
        $this->Email->delivery = 'smtp';
        if ( $this->Email->send() )
            $respuesta = 'OK';
        else
            $respuesta = 'Se produjo un error al enviar el descuento.';
    }

    function admin_reenviar($id = null)
    {
        if ( ! $id )
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'devuelto'));
        }
        $options = array(
            'conditions' => array(
                'Compra.id' => $id
            ),
            'contain' => array(
                'Despacho' => array(
                    'Direccion' => array(
                        'Comuna',
                        'Region'
                    )
                ),
                'Usuario' => array('Sexo'),
                'Pago'
            )
        );
        $compra = $this->Compra->find('first',$options);
        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $compra['Compra']['id']
            ),
            'order' => array(
                'ProductosCompra.producto_id' => 'ASC'
            )
        );
        $productos = $this->Compra->ProductosCompra->find('all',$options);
        $this->loadModel('Descuento');
        $total_descuento = 0;
        $lista_tallas = array();
        foreach ( $productos as $index => $producto )
        {
            $options = array(
                'conditions' => array(
                    'Producto.id' => $producto['ProductosCompra']['producto_id']
                ),
                'contain' => array('Color')
            );
            $datos_producto = $this->Compra->Producto->find('first',$options);
            $productos[$index] = array(
                'Producto' => $datos_producto['Producto'],
                'Color'		=> $datos_producto['Color'],
                'Stock' => array('talla' => $producto['ProductosCompra']['talla']),
                'ProductosCompra' => $producto['ProductosCompra']
            );
            unset($productos[$index]['Producto']['Color']);
            if ( $producto['ProductosCompra']['descuento_id'] && $producto['ProductosCompra']['estado'] == 0 || $producto['ProductosCompra']['estado'] == 1 )
            {
                $descuento = $this->Descuento->find('first', array('conditions' => array('Descuento.id' => $producto['ProductosCompra']['descuento_id'])));
                $productos[$index]['Descuento'] = $descuento['Descuento'];
                if ($descuento['Descuento']['tipo'] == 'POR')
                {
                    if ( $productos[$index]['Producto']['oferta'] == 0 )
                        $total_descuento = $total_descuento + (( $productos[$index]['Producto']['precio'] * $descuento['Descuento']['descuento'] ) / 100 );
                    elseif ( $productos['Producto']['oferta'] == 1 )
                        $total_descuento = $total_descuento + (( $productos[$index]['Producto']['precio_oferta'] * $descuento['Descuento']['descuento'] ) / 100 );
                }
                elseif ( $descuento['Descuento']['tipo'] == 'DIN' )
                {
                    $total_descuento = $total_descuento + $descuento['Descuento']['descuento'];
                }
            }
            $productos[$index]['Producto']['precio_oferta'] = $productos[$index]['Producto']['precio'] = $productos[$index]['ProductosCompra']['valor'];
        }

        if ( empty($this->data) )
        {
            $this->data['Compra']['id'] = $compra['Compra']['id'];
        }
        else
        {
            if ( $this->Compra->save($this->data) )
            {
                // guardar log
                $this->guardar_log($this->Auth->user('id'), 'compras', 'admin_reenviar', 'reenviar compra '.$id, $_SERVER['REMOTE_ADDR']);
                $this->Session->setFlash(__('Registro guardado correctamente', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
            }
        }
        $this->set(compact('compra', 'productos', 'total_descuento', 'lista_productos'));
    }

    function admin_confirmar($id = null)
    {
        if ( ! $id )
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'devuelto'));
        }
        if ($id != $compra['Compra']['id'] = $this->Session->read('CambiarProducto.CompraOriginal'))
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'devuelto'));
        }
        $options = array(
            'conditions' => array(
                'Compra.id' => $compra['Compra']['id']
            ),
            'contain' => array(
                'Despacho' => array(
                    'Direccion' => array(
                        'Comuna',
                        'Region'
                    )
                ),
                'Usuario' => array('Sexo'),
                'Pago'
            )
        );
        $compra = $this->Compra->find('first', $options);
        if ( $compra['Compra']['estado'] == 0 )
            $compra['Compra']['estado_nombre'] = 'No pagado';
        elseif ( $compra['Compra']['estado'] == 1 )
            $compra['Compra']['estado_nombre']= 'Pagado';
        elseif ( $compra['Compra']['estado'] == 2 )
            $compra['Compra']['estado_nombre']= 'Anulado';
        elseif ( $compra['Compra']['estado'] == 3 )
            $compra['Compra']['estado_nombre']= 'En Devolucion';
        elseif ( $compra['Compra']['estado'] == 4 )
            $compra['Compra']['estado_nombre']= 'Devuelto';

        $productos = $this->Session->read('CambiarProducto.Original');
        if (! empty($this->data) )
        {
            foreach ($this->data['Producto'] as $index => $producto)
            {
                $valor_producto = 0;
                $options = array(
                    'conditions' => array(
                        'Producto.id' => $producto['ProductosCompra']['producto_id']
                    )
                );
                $producto_aux = $this->Compra->Producto->find('first',$options);
                if ( isset($producto_aux['Producto']['oferta']) && $producto_aux['Producto']['oferta'] == 1 )
                    $valor_producto = $producto_aux['Producto']['precio_oferta'];
                else
                    $valor_producto = $producto_aux['Producto']['precio'];
                $save = array(
                    'ProductosCompra' => array(
                        'id' => $producto['ProductosCompra']['id'],
                        'producto_id' => $producto['ProductosCompra']['producto_id'],
                        'talla' => $producto['ProductosCompra']['talla'],
                        'valor' => $valor_producto
                    )
                );
                $this->Compra->ProductosCompra->save($save);
            }
            if ( $this->Compra->save(array('Compra' => $this->data['Compra'])) )
            {
                $this->Session->delete('CambiarProducto');
                $this->Session->setFlash(__('Registro guardado correctamente', true));
                $this->redirect(array('action' => 'devuelto'));
            }
        }
        else
        {
            $this->data['Compra']['id'] = $id;
            $this->data['Compra']['estado'] = $this->Session->read('CambiarProducto.CompraNueva.estado');

            if ( $this->data['Compra']['estado'] == 0 )
                $this->data['Compra']['estado_nombre'] = 'No pagado';
            elseif ( $this->data['Compra']['estado'] == 1 )
                $this->data['Compra']['estado_nombre']= 'Pagado';
            elseif ( $this->data['Compra']['estado'] == 2 )
                $this->data['Compra']['estado_nombre']= 'Anulado';
            elseif ( $this->data['Compra']['estado'] == 3 )
                $this->data['Compra']['estado_nombre']= 'En Devolucion';
            elseif ( $this->data['Compra']['estado'] == 4 )
                $this->data['Compra']['estado_nombre']= 'Devuelto';

            $this->data['Producto'] = $this->Session->read('CambiarProducto.Nuevo');
            $subtotal = 0;
            $total = 0;
            $iva = 0;
            $valor_despacho = 0;
            $descuento1 = 0;
            $descuento2 = 0;
            foreach ( $productos as $index => $producto )
            {
                $desc = 0;
                if ( $this->data['Producto'][$index]['Producto']['oferta'] == 0 )
                    $total = $total + $this->data['Producto'][$index]['Producto']['precio'];
                elseif ( $this->data['Producto'][$index]['Producto']['oferta'] == 1 )
                    $total = $total + $this->data['Producto'][$index]['Producto']['precio_oferta'];
                $productos[$index]['ProductosCompra'] = $producto['ProductosCompra'] = $producto['ProductosCompra']['ProductosCompra'];
                if ( $producto['ProductosCompra']['descuento_id'] )
                {
                    if ($producto['Descuento']['tipo'] == 'POR')
                    {
                        if ( $producto['Producto']['oferta'] == 0 )
                        {
                            $descuento1 = $descuento1 + (( $producto['Producto']['precio'] * $producto['Descuento']['descuento'] ) / 100 );
                            $desc = (( $this->data['Producto'][$index]['Producto']['precio'] * $producto['Descuento']['descuento'] ) / 100 );
                            $this->data['Producto'][$index]['Descuento']['descuento'] = $desc;
                            $descuento2 = $descuento2 + $desc;
                        }
                        elseif ( $producto['Producto']['oferta'] == 1 )
                        {
                            $descuento1 = $descuento1 + (( $producto['Producto']['precio_oferta'] * $producto['Descuento']['descuento'] ) / 100 );
                            $desc = (( $this->data['Producto'][$index]['Producto']['precio_oferta'] * $producto['Descuento']['descuento'] ) / 100 );
                            $this->data['Producto'][$index]['Descuento']['descuento'] = $desc;
                            $descuento2 = $descuento2 + $desc;
                        }
                    }
                    elseif ( $producto['Descuento']['tipo'] == 'DIN' )
                    {
                        $descuento1 = $descuento1 + $producto['Descuento']['descuento'];
                        $desc = $producto['Descuento']['descuento'];
                        $this->data['Producto'][$index]['Descuento']['descuento'] = $desc;
                        $descuento2 = $descuento2 + $desc;
                    }

                    if ( ($descuento2 % 10) > 0 )
                        $descuento2 = (((int)($descuento2/10))*10)+10;
                    else
                        $descuento2 = ((int)($descuento2/10))*10;
                }
                $this->data['Producto'][$index]['ProductosCompra']['id'] = $producto['ProductosCompra']['id'];
                $this->data['Producto'][$index]['ProductosCompra']['producto_id'] = $this->data['Producto'][$index]['Stock']['producto_id'];
                $this->data['Producto'][$index]['ProductosCompra']['talla'] = $this->data['Producto'][$index]['Stock']['talla'];
            }
            $total = ((int)($total/10))*10;
            $neto = $total / 1.19;
            $neto = ((int)($neto/10))*10;
            $iva = $total - $neto;
            $subtotal = $total;
            $total = $total - $descuento2;
            $this->data['Compra']['subtotal'] = $subtotal;
            $this->data['Compra']['iva'] = $iva;
            $this->data['Compra']['valor_despacho'] = $valor_despacho;
            $this->data['Compra']['descuento'] = $descuento2;
            $this->data['Compra']['neto'] = $neto;
            $this->data['Compra']['total'] = $total;
            $compra['Compra']['descuento'] = $descuento1;
        }
        $this->set(compact('compra', 'productos'));
    }

    function admin_enviado($id = null)
    {
        if ( ! $id )
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->data['Compra']['id'] = $id;
        $this->data['Compra']['enviado'] = 1;
        if ( $this->Compra->save($this->data) )
        {
            // guardar log
            $this->guardar_log($this->Auth->user('id'), 'compras', 'admin_enviado', 'enviar compra '.$id, $_SERVER['REMOTE_ADDR']);
            //$this->Session->setFlash(__('Enviado', true));
            $this->redirect(array('action' => 'si', $id));
        }
        $this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
        $this->redirect(array('action' => 'index'));
    }

    function admin_imprimir_despacho($id = null)
    {
        if ( ! $id )
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'index'));
        }
        $options = array(
            'conditions' => array(
                'Compra.id' => $id
            ),
            'contain' => array(
                'Despacho' => array(
                    'Direccion' => array(
                        'Comuna',
                        'Region'
                    )
                ),
                'Usuario' => array('Sexo'),
                'Pago'
            )
        );
        $compra = $this->Compra->find('first',$options);
        $this->set(compact('compra'));
    }

    function admin_generarexcel() { }
    function admin_generarexcel2() { }
    function admin_generarexcel3() { }
    function admin_generarexcel4() { }

    function admin_picks() { }
    function admin_excel_devoluciones() { }



    function admin_excel()
    {

        if ( in_array($this->data['Compra']['estado'], array(0,1,2)) )
        {
            $estado = $this->data['Compra']['estado'];
            if ($estado == 1)
                $estado = array(1,5);
        }
        else
        {
            $this->Session->setFlash(__('Debe seleccionar el estado de pago. Por favor intentelo nuevamente', true));
            $this->redirect(array('action' => 'generarexcel'));
        }

        if (isset($this->data['Compra']['consulta_fecha1']) && $this->data['Compra']['consulta_fecha1'])
            $fecha1 = $this->data['Compra']['consulta_fecha1'];
        else
            $fecha1 = date('Y-m-d');

        if (isset($this->data['Compra']['consulta_fecha2']) && $this->data['Compra']['consulta_fecha2'])
            $fecha2 = $this->data['Compra']['consulta_fecha2'];
        else
            $fecha2 = date('Y-m-d');
        $imprimir = array();

        $fields = array(	'Compra.id',
            'Compra.estado',
            'Compra.picking_number',
            'Compra.numId',
            'Compra.despachado',
            'Compra.boleta',
            'Compra.fecha_enviado',
            'Compra.cod_despacho',
            'Compra.rural',
            'Compra.direccion_rural',
            'Compra.subtotal',
            'Compra.neto',
            'Compra.iva',
            'Compra.total',
            'Compra.enviado',
            'Compra.ip',
            'Compra.created',
            'Descuento.nombre',
            'Descuento.codigo',
            'Descuento.tipo');
        if($this->data['Compra']['productos'])
        {
            $fields = array_merge($fields, array(	'ProductosCompra.id',
                    'ProductosCompra.compra_id',
                    'ProductosCompra.producto_id',
                    'ProductosCompra.descuento_id',
                    'ProductosCompra.talla',
                    'ProductosCompra.valor',
                    'ProductosCompra.categoria',
                    'Producto.id',
                    'Producto.nombre',
                    'Producto.codigo',
                    'Producto.outlet',
                    'Producto.precio',
                    'Producto.precio_oferta',
                    'Producto.codigo_completo',
                    'Producto.division',
                    'Producto.showroom',
                    'Color.codigo',
                    'Categoria.nombre',
                    'Descuento.descuento',
                    'Categoria.publico'
                )

            );
            $imprimir['productos'] = 'productos';
        }
        if($this->data['Compra']['forma_pago'])
        {
            $fields = array_merge($fields, array(	'Pago.fecha',
                    'Pago.numeroTarjeta',
                    'Pago.codigo',
                    'Pago.codAutorizacion',
                    'Pago.tipoPago'
                )

            );
            $imprimir['pago'] = 'pago';
        }
        if($this->data['Compra']['direccion'])
        {
            $fields = array_merge($fields, array(	'Direccion.calle',
                    'Direccion.numero',
                    'Direccion.depto',
                    'Direccion.telefono',
                    'Direccion.celular',
                    'Comuna.nombre',
                    'Region.nombre'

                )

            );
            $imprimir['direccion'] = 'direccion';
        }
        if($this->data['Compra']['comprador'])
        {
            $fields = array_merge($fields, array(	'Usuario.nombre',
                    'Usuario.apellido_paterno',
                    'Usuario.apellido_materno',
                    'Usuario.sexo_id',
                    'Usuario.estadocivil_id',
                    'Usuario.rut',
                    'Usuario.email',
                    'Usuario.telefono',
                    'Usuario.fecha_nacimiento',
                )

            );
            $imprimir['usuario'] = 'usuario';
        }

        $options = array(
            'conditions' => array(
                'Compra.estado' => $estado,
                'Compra.created >=' => $fecha1.' 00:00:00',
                'Compra.created <=' => $fecha2.' 23:59:59'
            ),
            'fields' => $fields,
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_compras',
                    'alias' => 'Compra',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Compra.id = ProductosCompra.compra_id'
                    )
                ),
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                ),
                array(
                    'table' => 'sitio_colores',
                    'alias' => 'Color',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Color.id = Producto.color_id'
                    )
                ),
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                ),
                array(
                    'table' => 'sitio_descuentos',
                    'alias' => 'Descuento',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Descuento.id = ProductosCompra.descuento_id'
                    )
                ),
                array(
                    'table' => 'sitio_pagos',
                    'alias' => 'Pago',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Pago.compra_id = Compra.id'
                    )
                ),
                array(
                    'table' => 'sitio_despachos',
                    'alias' => 'Despacho',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Despacho.id = Compra.despacho_id'
                    )
                ),
                array(
                    'table' => 'sitio_direcciones',
                    'alias' => 'Direccion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Direccion.id = Despacho.direccion_id'
                    )
                ),
                array(
                    'table' => 'sitio_comunas',
                    'alias' => 'Comuna',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Comuna.id = Direccion.comuna_id'
                    )
                ),
                array(
                    'table' => 'sitio_regiones',
                    'alias' => 'Region',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Region.id = Comuna.region_id'
                    )
                ),
                array(
                    'table' => 'sitio_categorias',
                    'alias' => 'Categoria',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Categoria.id = Producto.categoria_id'
                    )
                ),
            ),
            'group' => array('ProductosCompra.id'),
            'order' => array(
                'ProductosCompra.compra_id' => 'ASC',
                'ProductosCompra.id' => 'ASC'
            ),
        );
        $ventas = $this->Compra->ProductosCompra->find('all',$options);
        $this->set(compact('ventas', 'imprimir'));
    }

    function admin_excel2()
    {

        set_time_limit(0);

        if ( in_array($this->data['Compra']['estado'], array(0,1,2)) )
        {
            $estado = $this->data['Compra']['estado'];
            if ($estado == 1)
                $estado = array(1,5);
        }
        else
        {
            $this->Session->setFlash(__('Debe seleccionar el estado de pago. Por favor intentelo nuevamente', true));
            $this->redirect(array('action' => 'generarexcel'));
        }

        if (isset($this->data['Compra']['consulta_fecha1']) && $this->data['Compra']['consulta_fecha1'])
            $fecha1 = $this->data['Compra']['consulta_fecha1'];
        else
            $fecha1 = date('Y-m-d');

        if (isset($this->data['Compra']['consulta_fecha2']) && $this->data['Compra']['consulta_fecha2'])
            $fecha2 = $this->data['Compra']['consulta_fecha2'];
        else
            $fecha2 = date('Y-m-d');
        $imprimir = array();

        $fields = array(	'Compra.id',
            'Compra.numId',
            'Compra.total',
            'Compra.created',
            'Compra.local',
            'Compra.ip',
            'Producto.codigo_completo',
            'Producto.precio',
            'ProductosCompra.valor',
            'ProductosCompra.descuento_id',
            'ProductosCompra.talla',
            'Descuento.descuento',
            'Descuento.tipo',
            'Categoria.nombre'

        );

        $options = array(
            'conditions' => array(
                'Compra.estado' => $estado,
                'Compra.created >=' => $fecha1.' 00:00:00',
                'Compra.created <=' => $fecha2.' 23:59:59',

            ),
            'fields' => $fields,
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_compras',
                    'alias' => 'Compra',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Compra.id = ProductosCompra.compra_id'
                    )
                ),
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                ),


                array(
                    'table' => 'sitio_descuentos',
                    'alias' => 'Descuento',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Descuento.id = ProductosCompra.descuento_id'
                    )

                ),

                array(
                    'table' => 'sitio_categorias',
                    'alias' => 'Categoria',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Categoria.id = Producto.categoria_id'
                    )

                ),

            ),
            'group' => array('ProductosCompra.id'),
            'order' => array(
                'ProductosCompra.compra_id' => 'ASC',
                'ProductosCompra.id' => 'ASC'
            ),
        );
        $ventas = $this->Compra->ProductosCompra->find('all',$options);
        //prx($ventas);
        $this->set(compact('ventas', 'imprimir'));
    }
    function admin_listado_picks(){
        set_time_limit(0);
        if($this->data){
            if (isset($this->data['Compra']['consulta_fecha1']) && $this->data['Compra']['consulta_fecha1'])
                $fecha1 = $this->data['Compra']['consulta_fecha1'];
            else
                $fecha1 = date('Y-m-d');

            if (isset($this->data['Compra']['consulta_fecha2']) && $this->data['Compra']['consulta_fecha2'])
                $fecha2 = $this->data['Compra']['consulta_fecha2'];
            else
                $fecha2 = date('Y-m-d');

            $imprimir = array();
            $tipo =$this->data['Compra']['consulta_tipo'];

            switch ($tipo){
                case 1:
                    if($compras = $this->consultar_picks($fecha1,$fecha2,1)){
                        //var_dump($compras);
                        //$archivo = $this->debito($compras);
                        $this->set(compact('compras','imprimir','tipo'));
                    }
                    break;
                case 2:
                    if($compras = $this->consultar_picks($fecha1,$fecha2,2)){
                       // var_dump($compras);
                        //$archivo = $this->credito($compras,2);
                        $this->set(compact('compras','imprimir','tipo'));
                    }
                    break;
                case 3:
                    if($compras = $this->consultar_picks($fecha1,$fecha2,3)){
                       // var_dump($compras);
                       // $archivo = $this->credito($compras,3);
                        $this->set(compact('compras','imprimir','tipo'));
                    }
                    break;
            }

        }
    }
    private function debito($datos){

        //$basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'debito'.DS;
        $basedir = 'C:/xampp/htdocs/skechers/finanzas/debito/'.DS;

        if (! is_dir($basedir))
            @mkdir($basedir, 0755, true);

        $fecha = date('Y-m-d');
        //      GUARDAMOS EL ARCHIVO DE LOS PRODUCTOS
        $fecha = str_replace('-','',$fecha);
        $nombre = $fecha.'_debito.csv';//$fecha.'debito.csv';
        $fp = fopen($basedir.$nombre, 'w+');
        $contador =0;

        foreach($datos as $q)
        {
            if ($contador == 0){
                $linea = 'nombre cliente;run cliente;banco;N cta;Monto a devolver;N compra;N RA;N Boleta'."\n";
                $linea.= $q['Devoluciones']['nombre_titular'] . ';' . $q['Devoluciones']['run_titular'] . ';' . $q['Devoluciones']['banco_titular'] . ';' . $q['Devoluciones']['ncuenta_titular'] . ';' . $q['ProductosCompra']['valor'] . ';' . $q['Compra']['id'] . ';' . $q['Devoluciones']['codigo'] . ';' . $q['Compra']['boleta'] . ';' . "\n";
            }else {
                $linea = $q['Devoluciones']['nombre_titular'] . ';' . $q['Devoluciones']['run_titular'] . ';' . $q['Devoluciones']['banco_titular'] . ';' . $q['Devoluciones']['ncuenta_titular'] . ';' . $q['ProductosCompra']['valor'] . ';' . $q['Compra']['id'] . ';' . $q['Devoluciones']['codigo'] . ';' . $q['Compra']['boleta'] . ';' . "\n";
            }
            if(count($q) > $contador++) {
                $linea.='';
            }
            fwrite($fp,$linea);
        }
        fclose($fp);

        //$email = $this->enviar_correo($basedir,$nombre);
        //return $email;
    }
    private function credito($datos,$tipo)
    {
        if($tipo == 2){
            $tipo = '_credito.csv';
        }else{
            $tipo = '_credito_mp.csv';
        }
        //$basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'debito'.DS;
        $basedir = 'C:/xampp/htdocs/skechers/finanzas/debito/' . DS;

        if (!is_dir($basedir))
            @mkdir($basedir, 0755, true);

        $fecha = date('Y-m-d');
        //      GUARDAMOS EL ARCHIVO DE LOS PRODUCTOS
        $fecha = str_replace('-', '', $fecha);
        $nombre = $fecha . $tipo;//$fecha.'debito.csv';
        $fp = fopen($basedir . $nombre, 'w+');
        $contador = 0;

        foreach ($datos as $q) {
            if ($contador == 0) {
                $linea = 'Monto a devolver;N compra;N RA;N Boleta' . "\n";
                $linea .= $q['ProductosCompra']['valor'] . ';' . $q['Compra']['id'] . ';' . $q['Devoluciones']['codigo'] . ';' . $q['Compra']['boleta'] . ';' . "\n";
            } else {
                $linea = $q['ProductosCompra']['valor'] . ';' . $q['Compra']['id'] . ';' . $q['Devoluciones']['codigo'] . ';' . $q['Compra']['boleta'] . ';' . "\n";
            }
            if (count($q) > $contador++) {
                $linea .= '';
            }
            fwrite($fp, $linea);
        }
        fclose($fp);

        //$email = $this->enviar_correo($basedir, $nombre);
        //return $email;
    }
    function consultar_picks($fecha1,$fecha2,$tipo){

        if($tipo == 1) {
            $pago = 'VD';
            $conditions = array(
                //'Compra.estado' => 1,
                'Devoluciones.fecha_picks >=' => $fecha1,
                'Devoluciones.fecha_picks <=' => $fecha2,
                'Pago.tipoPago' => $pago,
            );
        }
        if($tipo == 2) {
            $pago = 'VC';
            $conditions = array(
                //'Compra.estado' => 1,
                'Devoluciones.fecha_picks >=' => $fecha1,
                'Devoluciones.fecha_picks <=' => $fecha2,
                'Pago.tipoPago' => $pago,
            );
        }
        if($tipo == 3) {
            $pago = 'VC';
            $conditions = array(
                //'Compra.estado' => 1,
                'Devoluciones.fecha_picks >=' => $fecha1,
                'Devoluciones.fecha_picks <=' => $fecha2,
                'Pago.tipoPago' => $pago,
                'Pago.marca' => 'mercadopago',
            );
        }

        $options = array(
            'conditions' => $conditions,
            'fields' => array(
                'Compra.id',
                'Compra.boleta',
                'Devoluciones.nombre_titular',
                'Devoluciones.run_titular',
                'Devoluciones.banco_titular',
                'Devoluciones.ncuenta_titular',
                'Devoluciones.codigo',
                'ProductosCompra.valor',
            ),
            'joins' => array(
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devoluciones',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Devoluciones.compra_id = Compra.id'
                    )
                ),
                array(
                    'table' => 'sitio_pagos',
                    'alias' => 'Pago',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Pago.compra_id = Compra.id'
                    )
                ),
                array(
                    'table' => 'sitio_productos_compras',
                    'alias' => 'ProductosCompra',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'ProductosCompra.id = Devoluciones.productos_compra_id'
                    )
                ),

            ),
        );
        if (!$compras = $this->Compra->find('all', $options)) {
            return '- SIN COMPRAS';
        }else{
            return $compras;
        }
    }
    function admin_estado_devoluciones(){
        if($this->data){
            if (isset($this->data['Compra']['consulta_fecha1']) && $this->data['Compra']['consulta_fecha1'])
                $fecha1 = $this->data['Compra']['consulta_fecha1'];
            else
                $fecha1 = date('Y-m-d');

            if (isset($this->data['Compra']['consulta_fecha2']) && $this->data['Compra']['consulta_fecha2'])
                $fecha2 = $this->data['Compra']['consulta_fecha2'];
            else
                $fecha2 = date('Y-m-d');

            $options = array(
                'conditions' => array(
                    'Compra.devolucion' => 1,
                    'Devoluciones.fecha_picks >=' => $fecha1,
                    'Devoluciones.fecha_picks <=' => $fecha2,
                    //'Pago.tipoPago' => $pago,
                    //'Pago.marca' => 'mercadopago',
                ),
                'fields' => array(
                    'Compra.id',
                    'Compra.boleta',
                    //'Compra.productos_compra_id',
                    'Devoluciones.nombre_titular',
                    'Devoluciones.run_titular',
                    'Devoluciones.productos_compra_id',
                    //'Devoluciones.tipo_nombre',
                    'Devoluciones.estado',
                    'Devoluciones.codigo',
                    'Devoluciones.producto',
                    'Devoluciones.fecha_picks',
                    //'Devoluciones.banco_titular',
                    //'Devoluciones.ncuenta_titular',
                    //'Devoluciones.codigo',
                    'ProductosCompra.valor',
                ),
                'joins' => array(
                    array(
                        'table' => 'sitio_devoluciones',
                        'alias' => 'Devoluciones',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'Devoluciones.compra_id = Compra.id'
                        )
                    ),
                    array(
                        'table' => 'sitio_pagos',
                        'alias' => 'Pago',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'Pago.compra_id = Compra.id'
                        )
                    ),
                    array(
                        'table' => 'sitio_productos_compras',
                        'alias' => 'ProductosCompra',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'ProductosCompra.id = Devoluciones.productos_compra_id'
                        )
                    ),

                ),
            );
            $imprimir = array();
            if ($compras = $this->Compra->find('all', $options)) {

                $this->set(compact('compras','imprimir'));
            }
        }
    }
    private function devoluciones($datos){

        //$basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'debito'.DS;
        $basedir = 'C:/xampp/htdocs/skechers/finanzas/devoluciones/'.DS;
        var_dump('aqui');
        if (! is_dir($basedir))
            @mkdir($basedir, 0755, true);

        $fecha = date('Y-m-d');
        //      GUARDAMOS EL ARCHIVO DE LOS PRODUCTOS
        $fecha = str_replace('-','',$fecha);
        $nombre = $fecha.'_devoluciones.csv';//$fecha.'debito.csv';
        $fp = fopen($basedir.$nombre, 'w+');
        $contador =0;

        foreach($datos as $q)
        {
            if ($contador == 0){
                $linea = 'compra;boleta;nombre;run;estado;codigo;producto;fecha picks;valor;'."\n";
                $linea.= $q['Compra']['id'] . ';' . $q['Compra']['boleta'] . ';' . $q['Devoluciones']['nombre_titular'] . ';' . $q['Devoluciones']['run_titular'] . ';' . $q['Devoluciones']['estado'] . ';' . $q['Devoluciones']['codigo'] . ';' . $q['Devoluciones']['producto'] . ';' . $q['Devoluciones']['fecha_picks'] . ';' . $q['ProductosCompra']['valor'] . ';'. "\n";
            }else {
                $linea.= $q['Compra']['id'] . ';' . $q['Compra']['boleta'] . ';' . $q['Devoluciones']['nombre_titular'] . ';' . $q['Devoluciones']['run_titular'] . ';' . $q['Devoluciones']['estado'] . ';' . $q['Devoluciones']['codigo'] . ';' . $q['Devoluciones']['producto'] . ';' . $q['Devoluciones']['fecha_picks'] . ';' . $q['ProductosCompra']['valor'] . ';'. "\n";
            }
            if(count($q) > $contador++) {
                $linea.='';
            }
            fwrite($fp,$linea);
        }
        fclose($fp);

        //$email = $this->enviar_correo($basedir,$nombre);
        //return $email;
    }
    function admin_excel3()
    {

        set_time_limit(0);

        if (isset($this->data['Compra']['consulta_fecha1']) && $this->data['Compra']['consulta_fecha1'])
            $fecha1 = $this->data['Compra']['consulta_fecha1'];
        else
            $fecha1 = date('Y-m-d');

        if (isset($this->data['Compra']['consulta_fecha2']) && $this->data['Compra']['consulta_fecha2'])
            $fecha2 = $this->data['Compra']['consulta_fecha2'];
        else
            $fecha2 = date('Y-m-d');
        $imprimir = array();

        $fields = array(	'Compra.id',
            'Compra.numId',
            'Compra.total',
            'Compra.created',
            'Compra.local',
            'Compra.ip',
            'Producto.codigo_completo',
            'ProductosCompra.valor',
            'ProductosCompra.descuento_id',
            'Descuento.descuento',
            'Descuento.tipo',
            'Categoria.nombre',
            'Compra.mv_orden',
            'Compra.mail_compra',
            'Compra.verificado'

        );

        $options = array(
            'conditions' => array(
                'Compra.estado' => 1,
                'Compra.local ' => 1,
                'Compra.created >=' => $fecha1.' 00:00:00',
                'Compra.created <=' => $fecha2.' 23:59:59',

            ),
            'fields' => $fields,
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_compras',
                    'alias' => 'Compra',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Compra.id = ProductosCompra.compra_id'
                    )
                ),
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                ),


                array(
                    'table' => 'sitio_descuentos',
                    'alias' => 'Descuento',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Descuento.id = ProductosCompra.descuento_id'
                    )

                ),

                array(
                    'table' => 'sitio_categorias',
                    'alias' => 'Categoria',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Categoria.id = Producto.categoria_id'
                    )

                ),

            ),
            'group' => array('ProductosCompra.id'),
            'order' => array(
                'ProductosCompra.compra_id' => 'ASC',
                'ProductosCompra.id' => 'ASC'
            ),
        );
        $ventas = $this->Compra->ProductosCompra->find('all',$options);
        //prx($ventas);
        $this->set(compact('ventas', 'imprimir'));
    }
    function admin_excel4()
    {

        set_time_limit(0);

        if (isset($this->data['Compra']['consulta_fecha1']) && $this->data['Compra']['consulta_fecha1'])
            $fecha1 = $this->data['Compra']['consulta_fecha1'];
        else
            $fecha1 = date('Y-m-d');

        if (isset($this->data['Compra']['consulta_fecha2']) && $this->data['Compra']['consulta_fecha2'])
            $fecha2 = $this->data['Compra']['consulta_fecha2'];
        else
            $fecha2 = date('Y-m-d');
        $imprimir = array();

        $fields = array(    
            'Compra.id',
            //'Compra.total',
            'Compra.created',
            'Compra.local',
            'Compra.ip',
            'Comuna.nombre',
            'Region.nombre',
            'Usuario.sexo_id',
            'Producto.codigo_completo',
            'Producto.codigo',
            'Color.codigo',
            'Producto.nombre',
            'Producto.division',
            'Producto.showroom',
            'ProductosCompra.talla',
            'ProductosCompra.descuento_id',
            'Descuento.tipo',
            'Descuento.descuento',
            'Producto.precio',
            'Producto.precio_oferta',
            'ProductosCompra.valor',
            'ProductosCompra.categoria',
            'Descuento.nombre',


            
        );


 

           

        $options = array(
            'conditions' => array(
                'Compra.estado' => 1,
                'Compra.created >=' => $fecha1.' 00:00:00',
                'Compra.created <=' => $fecha2.' 23:59:59',

            ),
            'fields' => $fields,
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_compras',
                    'alias' => 'Compra',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Compra.id = ProductosCompra.compra_id'
                    )
                ),
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                ),
                array(
                    'table' => 'sitio_colores',
                    'alias' => 'Color',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Color.id = Producto.color_id'
                    )
                ),
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                ),
                array(
                    'table' => 'sitio_descuentos',
                    'alias' => 'Descuento',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Descuento.id = ProductosCompra.descuento_id'
                    )
                ),
                array(
                    'table' => 'sitio_despachos',
                    'alias' => 'Despacho',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Despacho.id = Compra.despacho_id'
                    )
                ),
                array(
                    'table' => 'sitio_direcciones',
                    'alias' => 'Direccion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Direccion.id = Despacho.direccion_id'
                    )
                ),
                array(
                    'table' => 'sitio_comunas',
                    'alias' => 'Comuna',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Comuna.id = Direccion.comuna_id'
                    )
                ),
                array(
                    'table' => 'sitio_regiones',
                    'alias' => 'Region',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Region.id = Comuna.region_id'
                    )
                )
            ),
            'group' => array('ProductosCompra.id'),
            'order' => array(
                'Compra.id' => 'ASC',
                'ProductosCompra.id' => 'ASC'
            ),
        );
        $ventas = $this->Compra->ProductosCompra->find('all',$options);
       // prx($ventas);
        $this->set(compact('ventas', 'imprimir'));
    }



    function admin_exportar_compras_seleccionadas()
    {
        set_time_limit(0);
        Configure::write('debug',1);
        $lista_compras = array();
        if (isset($this->data['Compra']) && $this->data['Compra'])
        {
            foreach ($this->data['Compra'] as $compra_id => $valor)
            {
                if ($valor['compra_id'] == 1)
                    $lista_compras[$compra_id] = $compra_id;
            }
        }

        $options = array(
            'conditions' => array(
                'Compra.id' => $lista_compras
            ),
            'fields' => array(
                'Compra.id',
                'Compra.boleta',
                'Compra.fecha_enviado',
                'Compra.cod_despacho',
                'Compra.rural',
                'Compra.direccion_rural',
                'Compra.subtotal',
                'Compra.neto',
                'Compra.iva',
                'Compra.total',
                'Compra.enviado',
                'Compra.usuario_id',
                'Compra.despacho_id',
            ),
            'contain' => array(
                'Usuario' => array(
                    'fields' => array(
                        'Usuario.id',
                        'Usuario.nombre',
                        'Usuario.apellido_paterno',
                        'Usuario.email',
                        'Usuario.telefono',
                    )
                ),
                'Despacho' => array(
                    'fields' => array(
                        'Despacho.id',
                        'Despacho.direccion_id'
                    ),
                    'Direccion' => array(
                        'fields' => array(
                            'Direccion.id',
                            'Direccion.calle',
                            'Direccion.numero',
                            'Direccion.depto',
                            'Direccion.comuna_id',
                            'Direccion.region_id',
                            'Direccion.telefono',
                            'Direccion.celular',
                            'Direccion.otras_indicaciones'
                        ),
                        'Comuna' => array(
                            'fields' => array(
                                'Comuna.id',
                                'Comuna.nombre'
                            )
                        ),
                        'Region' => array(
                            'fields' => array(
                                'Region.id',
                                'Region.nombre'
                            )
                        )
                    )
                ),
                'Producto' => array(
                    'fields' => array(
                        'Producto.id',
                        'Producto.color_id',
                        'Producto.codigo',
                        'Producto.codigo_completo',
                        'Producto.precio',
                        'Producto.precio_oferta',
                        'Producto.oferta'
                    ),
                    'Color'	=> array(
                        'fields' => array(
                            'Color.id',
                            'Color.codigo'
                        )
                    ),
                )
            )
        );
        $ventas = $this->Compra->find('all',$options);
        // prx($ventas);
        $this->set(compact('ventas'));
    }

    function ajax_picking_save()
    {
        if (! $this->Session->check('Auth.Administrador'))
            die('Sesion invalida');

        if (! isset($this->data['Compra']['id']))
            die('Registro invalido');
        elseif (! $this->data['Compra']['id'])
            die('Registro invalido');

        if (! isset($this->data['Compra']['picking_number']))
            die('Registro invalido');
        elseif (! $this->data['Compra']['picking_number'])
            die('Registro invalido');

        if (! is_numeric($this->data['Compra']['id']) || ! $this->data['Compra']['picking_number'])
            die('Registro invalido');

        $options = array(
            'conditions' => array(
                'Compra.id' => $this->data['Compra']['id']
            ),
            'fields' => array(
                'Compra.id',
                'Compra.picking_number'
            ),
            'recursive' => -1
        );

        if (! $compra = $this->Compra->find('first',$options))
            die('Registro invalido');

        if ($compra['Compra']['picking_number'])
            die('La compra no puede ser modificada porque ya se le asigno un picking number');

        $save = array(
            'id' => $this->data['Compra']['id'],
            'picking_number' => $this->data['Compra']['picking_number']
        );
        if ($this->Compra->save($save))
            die('OK');
        else
            die('No fue posible guardar el registro. Por favor intentalo nuevamente.');
    }

    function admin_cargas()
    {
        if (! empty($this->data))
        {
            if ($this->data['Compra']['tipo']==1)
                $reporte = $this->despacho_picking__($this->data['Compra']['archivo']['tmp_name']);
            elseif ($this->data['Compra']['tipo']==2)
                $reporte = $this->despacho_boleta__($this->data['Compra']['archivo']['tmp_name']);
            elseif ($this->data['Compra']['tipo']==3)
                $reporte = $this->despacho_despacho__($this->data['Compra']['archivo']['tmp_name']);
            elseif ($this->data['Compra']['tipo']==4)
                $reporte = $this->despacho_numId__($this->data['Compra']['archivo']['tmp_name']);
            elseif ($this->data['Compra']['tipo']==5)
                $reporte = $this->despacho_despacho__($this->data['Compra']['archivo']['tmp_name'], 'CORREOS_CL');
            elseif ($this->data['Compra']['tipo']==6)
                $reporte = $this->despacho_despacho__($this->data['Compra']['archivo']['tmp_name'], 'TANGO');
            elseif ($this->data['Compra']['tipo']==7)
                $reporte = $this->despacho_despacho__($this->data['Compra']['archivo']['tmp_name'], 'CHILEXPRESS', true);
            elseif ($this->data['Compra']['tipo']==8)
                $reporte = $this->despacho_despacho__($this->data['Compra']['archivo']['tmp_name'], 'TANGO', true);
            elseif ($this->data['Compra']['tipo']==9)
                $reporte = $this->despacho_retiro__($this->data['Compra']['archivo']['tmp_name']);
            $this->set(compact('reporte'));
        }
    }

    function despacho_boleta__($archivo = null)
    {
        //Configure::write('debug',1);
        $guardar = true;
        if (! $archivo)
            return false;
        if (! file_exists($archivo))
            return false;
        $inicio = date('d-m-Y H:i:s');
        //$mapeo = array('DOCTYPE','DOCID','Folio','TrackID','TrackIDTstamp','DocDate','DueDate','LocnCode','CUSTID','CustAdrsID','CustAddress','CustState','CustCity','CUSTNAME','CUSTRUT','GIROEMP','RZNSOCIAL','SALESPERSONID','SPRS_NAME','RlseType','TransfType','TruckID','CarrierID','DriverID','PYMTRMID','NroLinDr','SHIPMTHD','PayForms','ExemptAmount','NetAmount','SubTotal','TotalAmount','TaxID','TaxAmount','TaxPercent','STATUS','Distribution_Status','PDF','XML','XMLAcuseRecibo','XMLConfirmationService','XMLConfirmation','PdfCreateStatus','TimeStamp','Createdby','Modifiedby','str_User1','str_User2','str_User3','str_User4','str_User5','str_User6','int_User7','int_User8','int_User9','flo_User10','flo_User11','flo_User12','dat_User13','dat_User14','dat_User15','USER1','USER2','USER3','USER4','USER5','USER6','USER7','USER8');
        //Folio = boleta, str_User2 = compraID, TotalAmount = total
        $mapeo = array('str_User2','Folio');



        $separador=';';
        $stats = array(
            'lineas' => 0,
            'actualizados' => 0,
            'log_actualizados' => '',
            'parametro_invalido' => 0,
            'log_parametro_invalido' => '',
            'compra_invalida' => 0,
            'log_compra_invalida' => '',
            'omitidos' => 0,
            'log_omitidos' => '',
            'errores' => 0,
            'log_errores' => '',

        );

        if (($handle = fopen($archivo, 'r') ) !== FALSE)
        {
            while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
            {
                $stats['lineas']++;

                if ($stats['lineas']<=1)
                    continue;

                $registro = array();

                foreach ( $datos as $index => $valor )
                {
                    if ( ! isset($mapeo[$index]) )
                        continue;

                    if (trim($valor) == '---------- END OF REPORT ---------')// finaliza lectura
                        break 2;

                    $registro[$mapeo[$index]]	= trim($valor);
                }
                //Folio = boleta, str_User2 = compraID, TotalAmount = total
                //$registro['str_User2'] = $registro['str_User5'];
                //prx($registro);
                if (! isset($registro['Folio']))
                {
                    $stats['parametro_invalido']++;
                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! $registro['Folio'])
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! isset($registro['str_User2']))
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! $registro['str_User2'])
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                $id =  ereg_replace("[^0-9]", "", $registro['str_User2']);

                $options = array(
                    'conditions' => array(
                        'Compra.id' => $id,
                        'Compra.estado' => 1
                    ),
                    'fields' => array(
                        'Compra.id',
                        'Compra.picking_number',
                        'Compra.cod_despacho',
                        'Compra.boleta'
                    )
                );
                if (! $compra = $this->Compra->find('first',$options))
                {
                    $stats['compra_invalida']++;

                    if ($stats['log_compra_invalida'])
                        $stats['log_compra_invalida'].=', ';

                    $stats['log_compra_invalida'].=$registro['str_User2'];
                    continue;
                }

                if ($compra['Compra']['boleta'] == $registro['Folio'])
                {
                    $stats['omitidos']++;

                    if ($stats['log_omitidos'])
                        $stats['log_omitidos'].=', ';

                    $stats['log_omitidos'].=$compra['Compra']['id'];
                    continue;
                }

                $save = array(
                    'id' => $compra['Compra']['id'],
                    'boleta' => trim($registro['Folio'])
                    //'numId' => trim($registro['str_User1'])

                );
                if ($guardar)
                {
                    if ($this->Compra->save($save))
                    {
                        $stats['actualizados']++;

                        if ($stats['log_actualizados'])
                            $stats['log_actualizados'].=', ';

                        $stats['log_actualizados'].=$compra['Compra']['id'];
                        if ($compra['Compra']['boleta'])
                        {
                            $save = array(
                                'compra_id' => $compra['Compra']['id'],
                                'numero' => $compra['Compra']['boleta']
                            );
                            $this->Compra->Boleta->create();
                            $this->Compra->Boleta->save($save);
                        }
                    }
                    else
                    {
                        $stats['errores']++;

                        if ($stats['log_errores'])
                            $stats['log_errores'].=', ';

                        $stats['log_errores'].=$compra['Compra']['id'];
                    }
                }
                else
                {
                    $stats['actualizados']++;

                    if ($stats['log_actualizados'])
                        $stats['log_actualizados'].=', ';

                    $stats['log_actualizados'].=$compra['Compra']['id'];
                }
            }
            if ($guardar)
            {
                $basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'archivos'.DS.'BOLETAS'.DS;
                //$basedir = 'C:\xampp\public_html\andain\skechers\catalogo\sitio\webroot\archivos'.DS;
                if (! is_dir($basedir))
                {
                    @mkdir($basedir, 0777, true);
                }
                $name = $this->Auth->user('id').'-BOLETAS-'.(Inflector::slug(date('Y-m-d H:i:s')));
                copy($archivo,$basedir.$name.'.csv');

                $filename_log = $basedir.$name.".txt";
                $fp = fopen($filename_log,"w");
                fwrite($fp,'===== INICIO: '.$inicio.' ====='.PHP_EOL);
                fwrite($fp,'administrador: '.$this->Auth->user('nombre').' ['.$this->Auth->user('id').']'.PHP_EOL);
                foreach ($stats as $stat => $valor)
                {
                    $texto = $stat.': '.$valor;
                    fwrite($fp,'* '.$texto.PHP_EOL);
                }
                fwrite($fp,'===== FIN: '.date('d-m-Y H:i:s').' ====='.PHP_EOL);
                fclose($fp);
            }
        }
        return($stats);
    }

    private function despacho_picking__($archivo = null)
    {
        //Configure::write('debug',2);
        $guardar = true;
        if (! $archivo)
            return false;

        if (! file_exists($archivo))
            return false;

        $inicio = date('d-m-Y H:i:s');
        //$mapeo = array('suboffice','country','div','customer','custmore_name','store','city','p_o','order_no','register','line_seq','style','style_desc','color','qty','price','coord_code','priority','hdr_start_dt','hdr_cancel_dt','prior_dt','start_dt','cancel');
        $mapeo = array('p_o','invoice');


        $separador=';';
        $stats = array(
            'lineas' => 0,
            'actualizados' => 0,
            'log_actualizados' => '',
            'parametro_invalido' => 0,
            'log_parametro_invalido' => '',
            'compra_invalida' => 0,
            'log_compra_invalida' => '',
            'omitidos' => 0,
            'log_omitidos' => '',
            'errores' => 0,
            'log_errores' => '',

        );

        if (($handle = fopen($archivo, 'r') ) !== FALSE)
        {
            while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
            {
                $stats['lineas']++;

                if ($stats['lineas']<=1)
                    continue;

                $registro = array();
                foreach ( $datos as $index => $valor )
                {
                    if ( ! isset($mapeo[$index]) )
                        continue;

                    if (trim($valor) == '---------- END OF REPORT ---------')// finaliza lectura
                        break 2;

                    $registro[$mapeo[$index]]	= trim($valor);
                }
                //continue;
                if (! isset($registro['p_o']))
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! $registro['p_o'])
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! isset($registro['invoice']))
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! $registro['invoice'])
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                $id =  ereg_replace("[^0-9]", "", $registro['p_o']);

                $options = array(
                    'conditions' => array(
                        'Compra.id' => $id,
                        'Compra.estado' => 1
                    ),
                    'fields' => array(
                        'Compra.id',
                        'Compra.picking_number',
                        'Compra.cod_despacho',
                        'Compra.boleta'
                    )
                );
                if (! $compra = $this->Compra->find('first',$options))
                {
                    $stats['compra_invalida']++;

                    if ($stats['log_compra_invalida'])
                        $stats['log_compra_invalida'].=', ';

                    $stats['log_compra_invalida'].=$registro['p_o'];
                    continue;
                }

                $save = array(
                    'id' => $compra['Compra']['id'],
                    'picking_number' => $registro['invoice']
                );
                if ($guardar)
                {
                    if ($this->Compra->save($save))
                    {
                        $stats['actualizados']++;

                        if ($stats['log_actualizados'])
                            $stats['log_actualizados'].=', ';

                        $stats['log_actualizados'].=$compra['Compra']['id'];
                    }
                    else
                    {
                        $stats['errores']++;

                        if ($stats['log_errores'])
                            $stats['log_errores'].=', ';

                        $stats['log_errores'].=$compra['Compra']['id'];
                    }
                }
                else
                {
                    $stats['actualizados']++;

                    if ($stats['log_actualizados'])
                        $stats['log_actualizados'].=', ';

                    $stats['log_actualizados'].=$compra['Compra']['id'];
                }
            }
            if ($guardar)
            {
                $basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'archivos'.DS.'PICKING'.DS;
                //$basedir = 'C:\xampp\public_html\andain\skechers\catalogo\sitio\webroot\archivos'.DS;
                if (! is_dir($basedir))
                {
                    @mkdir($basedir, 0777, true);
                }
                $name = $this->Auth->user('id').'-PICKING-'.(Inflector::slug(date('Y-m-d H:i:s')));
                copy($archivo,$basedir.$name.'.csv');

                $filename_log = $basedir.$name.".txt";
                $fp = fopen($filename_log,"w");
                fwrite($fp,'===== INICIO: '.$inicio.' ====='.PHP_EOL);
                fwrite($fp,'administrador: '.$this->Auth->user('nombre').' ['.$this->Auth->user('id').']'.PHP_EOL);
                foreach ($stats as $stat => $valor)
                {
                    $texto = $stat.': '.$valor;
                    fwrite($fp,'* '.$texto.PHP_EOL);
                }
                fwrite($fp,'===== FIN: '.date('d-m-Y H:i:s').' ====='.PHP_EOL);
                fclose($fp);
            }
        }
        return($stats);
    }

    private function despacho_despacho__($archivo = null, $tipo = 'CHILEXPRESS', $reprocesar = false)
    {
        //Configure::write('debug',1);
        $guardar = true;
        if (! $archivo)
            return false;

        if (! file_exists($archivo))
            return false;
        $separador=';';
        $empresa_despacho=2;
        $inicio = date('d-m-Y H:i:s');
        $start = 1;
        $mapeo = array(
            'nro_referencia',
            'nro_ot'	// codigo de despacho

        );
        if ($tipo == 'CORREOS_CL')
        {
            $empresa_despacho=3;
            $mapeo = array(
                'nro_referencia',		// id de compra
                'nro_ot',				// codigo de despacho
                'fecha',
                'nombre_remitente',
                'direccion_remitente',
                'codigo_postal_remitente',
                'comuna_remitente',
                'nombre_destinatario',
                'direccion_destinatario',
                'codigo_postal_destinatario',
                'comuna_destinatario',
                'telefono_destinatario',
                'nombre_del_receptor',
                'rut_del_receptor',
                'fecha_grabacion',
                'fecha_salida',
                'fecha_llegada',
                'fecha_entrega',
                'fecha_objetivo',
                'servicio',
                'incidencia',
                'imagen',
                'bultos',
                'tipo_portes',
                'peso',
                'volumen',
                'localizador'
            );
        }
        if ($tipo == 'TANGO')
        {
            $empresa_despacho=1;
            $start = 1;
            $separador =';';
            $mapeo = array(
                'nro_referencia', // id de compra y codigo de despacho
                'nro_ot'
            );
        }


        $stats = array(
            'lineas' => 0,
            'actualizados' => 0,
            'log_actualizados' => '',
            'parametro_invalido' => 0,
            'log_parametro_invalido' => '',
            'compra_invalida' => 0,
            'log_compra_invalida' => '',
            'omitidos' => 0,
            'log_omitidos' => '',
            'errores' => 0,
            'log_errores' => '',

        );

        if (($handle = fopen($archivo, 'r') ) !== FALSE)
        {
            while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
            {
                $stats['lineas']++;

                if ($stats['lineas']<=$start)
                    continue;

                $registro = array();
                foreach ( $datos as $index => $valor )
                {
                    if ( ! isset($mapeo[$index]) )
                        continue;

                    $registro[$mapeo[$index]]	= trim($valor);
                }
                if ($tipo == 'TANGO')
                {
                    $registro['nro_ot'] = $registro['nro_referencia'];
                }
                //prx($registro);

                if (! isset($registro['nro_referencia']))
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! $registro['nro_referencia'])
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! isset($registro['nro_ot']))
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! $registro['nro_ot'])
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }

                $id =  ereg_replace("[^0-9]", "", $registro['nro_referencia']);
                $registro['nro_ot'] = ereg_replace("[^0-9]", "",  $registro['nro_ot']);


                $options = array(
                    'conditions' => array(
                        'Compra.id' => $id,
                        'Compra.estado' => 1
                    ),
                    'fields' => array(
                        'Compra.id',
                        'Compra.picking_number',
                        'Compra.cod_despacho',
                        'Compra.boleta'
                    )
                );
                if (! $compra = $this->Compra->find('first',$options))
                {
                    $stats['compra_invalida']++;

                    if ($stats['log_compra_invalida'])
                        $stats['log_compra_invalida'].=', ';

                    $stats['log_compra_invalida'].=$registro['nro_referencia'];

                    continue;
                }
                /*if (! $compra['Compra']['picking_number'])
				{
					$stats['omitidos']++;

					if ($stats['log_omitidos'])
						$stats['log_omitidos'].=', ';

					$stats['log_omitidos'].=$compra['Compra']['id'];
					continue;
				}*/

                if (!$reprocesar && $compra['Compra']['cod_despacho'] == $registro['nro_ot'])
                {
                    $stats['omitidos']++;

                    if ($stats['log_omitidos'])
                        $stats['log_omitidos'].=', ';

                    $stats['log_omitidos'].=$compra['Compra']['id'];
                    continue;
                }

                $save = array(
                    'id' => $compra['Compra']['id'],
                    'cod_despacho' => trim($registro['nro_ot']),
                    'fecha_enviado' => date('Y-m-d H:m:s'),
                    'despachado' => 1,
                    'empresa_despacho_id'=>$empresa_despacho
                );

                if ($guardar)
                {

                    if ($this->Compra->save($save))
                    {
                        $stats['actualizados']++;

                        if ($stats['log_actualizados'])
                            $stats['log_actualizados'].=', ';

                        $stats['log_actualizados'].=$compra['Compra']['id'];

                        if ($compra['Compra']['cod_despacho'])
                        {
                            $save = array(
                                'compra_id' => $compra['Compra']['id'],
                                'cod_despacho' => $compra['Compra']['cod_despacho']
                            );

                            $this->Compra->Boleta->create();
                            $this->Compra->Boleta->save($save);
                        }
                        $x = $this->enviarEmailAutomatico__($compra['Compra']['id']);
                    }
                    else
                    {
                        $stats['errores']++;

                        if ($stats['log_errores'])
                            $stats['log_errores'].=', ';

                        $stats['log_errores'].=$compra['Compra']['id'];
                    }
                }
                else
                {
                    $stats['actualizados']++;

                    if ($stats['log_actualizados'])
                        $stats['log_actualizados'].=', ';

                    $stats['log_actualizados'].=$compra['Compra']['id'];
                }
            }

            if ($guardar)
            {
                $basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'archivos'.DS.'DESPACHO'.DS;
                //$basedir = 'C:\xampp\public_html\andain\skechers\catalogo\sitio\webroot\archivos'.DS;
                //$basedir = 'C:\xampp\htdocs\skechers\archivos'.DS;
                if (! is_dir($basedir))
                {
                    @mkdir($basedir, 0777, true);
                }
                $name = $this->Auth->user('id').'-COD_DESPACHO-'.(Inflector::slug(date('Y-m-d H:i:s'))).'.csv';
                copy($archivo,$basedir.$name.'.csv');

                $filename_log = $basedir.$name.".txt";
                $fp = fopen($filename_log,"w");
                fwrite($fp,'===== INICIO: '.$inicio.' ====='.PHP_EOL);
                fwrite($fp,'administrador: '.$this->Auth->user('nombre').' ['.$this->Auth->user('id').']'.PHP_EOL);
                foreach ($stats as $stat => $valor)
                {
                    $texto = $stat.': '.$valor;
                    fwrite($fp,'* '.$texto.PHP_EOL);
                }
                fwrite($fp,'===== FIN: '.date('d-m-Y H:i:s').' ====='.PHP_EOL);
                fclose($fp);
            }
        }
        return($stats);
    }

      private function despacho_retiro__($archivo = null)
    {
        //Configure::write('debug',1);
        $guardar = true;
        if (! $archivo)
            return false;

        if (! file_exists($archivo))
            return false;
        $separador=';';
        $empresa_despacho=3;
        $inicio = date('d-m-Y H:i:s');
        $start = 0;
        $mapeo = array(
            'nro_referencia'
           // codigo de despacho

        );
         $stats = array(
            'lineas' => 0,
            'actualizados' => 0,
            'log_actualizados' => '',
            'parametro_invalido' => 0,
            'log_parametro_invalido' => '',
            'compra_invalida' => 0,
            'log_compra_invalida' => '',
            'omitidos' => 0,
            'log_omitidos' => '',
            'errores' => 0,
            'log_errores' => '',

        );

        if (($handle = fopen($archivo, 'r') ) !== FALSE)
        {
            while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
            {
                $stats['lineas']++;

                if ($stats['lineas']<=$start)
                    continue;
                pr($datos); 
                $registro = array();
                foreach ( $datos as $index => $valor )
                {
                    if ( ! isset($mapeo[$index]) )
                        continue;
                 pr($mapeo[$index]);
                 var_dump( trim($valor) );

                    $registro[$mapeo[$index]]   = trim($valor);
                }
                pr($registro);

                if (! isset($registro['nro_referencia']))
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! $registro['nro_referencia'])
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                $id =  ereg_replace("[^0-9]", "", $registro['nro_referencia']);


                $options = array(
                    'conditions' => array(
                        'Compra.id' => $id,
                        'Compra.estado' => 1
                    ),
                    'fields' => array(
                        'Compra.id',
                        'Compra.picking_number',
                        'Compra.cod_despacho',
                        'Compra.boleta_pdf',
                        'Compra.boleta',
                        'Compra.created',
                        'Usuario.nombre',
                        'Usuario.email',
                        'Despacho.id',
                        'Despacho.entrega',
                        'Despacho.rut',
                        'Direccion.id',
                        'Direccion.calle',
                        'Direccion.numero',
                        'Direccion.depto',
                        'Direccion.telefono',
                        'Direccion.celular',
                        'Direccion.otras_indicaciones',
                        'Direccion.retiro_id',
                        'Retiro.tipo_id',
                        'Retiro.codigo',
                        'Retiro.nombre',
                        'Retiro.calle',
                        'Retiro.horario'
                    ),
                    'joins' => array(
                        array(
                            'table' => 'sitio_despachos',
                            'alias' => 'Despacho',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Despacho.id = Compra.despacho_id'
                            )
                        ),
                         array(
                            'table' => 'sitio_usuarios',
                            'alias' => 'Usuario',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Usuario.id = Compra.usuario_id'
                            )
                        ),
                        array(
                            'table' => 'sitio_direcciones',
                            'alias' => 'Direccion',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Direccion.id = Despacho.direccion_id'
                            )
                        ),
                        array(
                            'table' => 'sitio_comunas',
                            'alias' => 'Comuna',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Comuna.id = Direccion.comuna_id'
                            )
                        ),
                        array(
                            'table' => 'sitio_regiones',
                            'alias' => 'Region',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Region.id = Comuna.region_id'
                            )
                        ),
                        array(
                            'table' => 'sitio_retiros',
                            'alias' => 'Retiro',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Direccion.retiro_id = Retiro.id',
                                'Retiro.tipo_id = 3'
                            )
                        )
                  )
                );
                if (! $compra = $this->Compra->find('first',$options))
                {

                    $stats['compra_invalida']++;

                    if ($stats['log_compra_invalida'])
                        $stats['log_compra_invalida'].=', ';

                    $stats['log_compra_invalida'].=$registro['nro_referencia'];

                    continue;
                }
                if (!$reprocesar && $compra['Compra']['cod_despacho'] == $registro['nro_referencia'])
                {
                    $stats['omitidos']++;

                    if ($stats['log_omitidos'])
                        $stats['log_omitidos'].=', ';

                    $stats['log_omitidos'].=$compra['Compra']['id'];
                    continue;
                }

                $save = array(
                    'id' => $compra['Compra']['id'],
                    'cod_despacho' => trim($registro['nro_referencia']),
                    'fecha_enviado' => date('Y-m-d H:m:s'),
                    'despachado' => 1
                );

                if ($guardar)
                {
                    if ($this->Compra->save($save))
                    {
                        $stats['actualizados']++;

                        if ($stats['log_actualizados'])
                            $stats['log_actualizados'].=', ';

                        $stats['log_actualizados'].=$compra['Compra']['id'];

                        if ($compra['Compra']['cod_despacho'])
                        {
                            $save = array(
                                'compra_id' => $compra['Compra']['id'],
                                'cod_despacho' => $compra['Compra']['cod_despacho']
                            );

                            $this->Compra->Boleta->create();
                            $this->Compra->Boleta->save($save);
                        }
                        $x = $this->enviarEmailRetiro($compra);
                    }
                    else
                    {
                        $stats['errores']++;

                        if ($stats['log_errores'])
                            $stats['log_errores'].=', ';

                        $stats['log_errores'].=$compra['Compra']['id'];
                    }
                }
                else
                {
                    $stats['actualizados']++;

                    if ($stats['log_actualizados'])
                        $stats['log_actualizados'].=', ';

                    $stats['log_actualizados'].=$compra['Compra']['id'];
                }
            }

            if ($guardar)
            {
                $basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'archivos'.DS.'DESPACHO'.DS;
                //$basedir = 'C:\xampp\public_html\andain\skechers\catalogo\sitio\webroot\archivos'.DS;
                //$basedir = 'C:\xampp\htdocs\skechers\archivos'.DS;
                if (! is_dir($basedir))
                {
                    @mkdir($basedir, 0777, true);
                }
                $name = $this->Auth->user('id').'-COD_DESPACHO-'.(Inflector::slug(date('Y-m-d H:i:s'))).'.csv';
                copy($archivo,$basedir.$name.'.csv');

                $filename_log = $basedir.$name.".txt";
                $fp = fopen($filename_log,"w");
                fwrite($fp,'===== INICIO: '.$inicio.' ====='.PHP_EOL);
                fwrite($fp,'administrador: '.$this->Auth->user('nombre').' ['.$this->Auth->user('id').']'.PHP_EOL);
                foreach ($stats as $stat => $valor)
                {
                    $texto = $stat.': '.$valor;
                    fwrite($fp,'* '.$texto.PHP_EOL);
                }
                fwrite($fp,'===== FIN: '.date('d-m-Y H:i:s').' ====='.PHP_EOL);
                fclose($fp);
            }
        }
        return($stats);
    }


    function despacho_numId__($archivo = null)
    {
        Configure::write('debug',2);
        $guardar = true;
        if (! $archivo)
            return false;

        if (! file_exists($archivo))
            return false;

        $inicio = date('d-m-Y H:i:s');
        $mapeo = array('p_o','invoice');

        $separador=';';
        $stats = array(
            'lineas' => 0,
            'actualizados' => 0,
            'log_actualizados' => '',
            'parametro_invalido' => 0,
            'log_parametro_invalido' => '',
            'compra_invalida' => 0,
            'log_compra_invalida' => '',
            'omitidos' => 0,
            'log_omitidos' => '',
            'errores' => 0,
            'log_errores' => '',

        );
        if (($handle = fopen($archivo, 'r') ) !== FALSE)
        {
            while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
            {
                $stats['lineas']++;

                if ($stats['lineas']<=1)
                    continue;

                $registro = array();
                foreach ( $datos as $index => $valor )
                {
                    if ( ! isset($mapeo[$index]) )
                        continue;

                    if (trim($valor) == '---------- END OF REPORT ---------')// finaliza lectura
                        break 2;

                    $registro[$mapeo[$index]]	= trim($valor);
                }
                if (! isset($registro['p_o']))
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! $registro['p_o'])
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! isset($registro['invoice']))
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                if (! $registro['invoice'])
                {
                    $stats['parametro_invalido']++;

                    if ($stats['log_parametro_invalido'])
                        $stats['log_parametro_invalido'].=', ';

                    $stats['log_parametro_invalido'].=$stats['lineas'];
                    continue;
                }
                $id =  ereg_replace("[^0-9]", "", $registro['p_o']);

                $options = array(
                    'conditions' => array(
                        'Compra.id' => $id,
                        'Compra.estado' => 1
                    ),
                    'fields' => array(
                        'Compra.id',
                        'Compra.picking_number',
                        'Compra.cod_despacho',
                        'Compra.boleta'
                    )
                );
                if (! $compra = $this->Compra->find('first',$options))
                {
                    $stats['compra_invalida']++;

                    if ($stats['log_compra_invalida'])
                        $stats['log_compra_invalida'].=', ';

                    $stats['log_compra_invalida'].=$registro['p_o'];
                    continue;
                }

                $save = array(
                    'id' => $compra['Compra']['id'],
                    'numId' => $registro['invoice']
                );
                if ($guardar)
                {
                    if ($this->Compra->save($save))
                    {
                        $stats['actualizados']++;

                        if ($stats['log_actualizados'])
                            $stats['log_actualizados'].=', ';

                        $stats['log_actualizados'].=$compra['Compra']['id'];
                    }
                    else
                    {
                        $stats['errores']++;

                        if ($stats['log_errores'])
                            $stats['log_errores'].=', ';

                        $stats['log_errores'].=$compra['Compra']['id'];
                    }
                }
                else
                {
                    $stats['actualizados']++;

                    if ($stats['log_actualizados'])
                        $stats['log_actualizados'].=', ';

                    $stats['log_actualizados'].=$compra['Compra']['id'];
                }
            }
            if ($guardar)
            {
                $basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'archivos'.DS.'NUM_ID'.DS;
                //$basedir = 'C:\xampp\public_html\andain\skechers\catalogo\sitio\webroot\archivos'.DS;
                if (! is_dir($basedir))
                {
                    @mkdir($basedir, 0777, true);
                }
                $name = $this->Auth->user('id').'-NUM_ID-'.(Inflector::slug(date('Y-m-d H:i:s')));
                copy($archivo,$basedir.$name.'.csv');

                $filename_log = $basedir.$name.".txt";
                $fp = fopen($filename_log,"w");
                fwrite($fp,'===== INICIO: '.$inicio.' ====='.PHP_EOL);
                fwrite($fp,'administrador: '.$this->Auth->user('nombre').' ['.$this->Auth->user('id').']'.PHP_EOL);
                foreach ($stats as $stat => $valor)
                {
                    $texto = $stat.': '.$valor;
                    fwrite($fp,'* '.$texto.PHP_EOL);
                }
                fwrite($fp,'===== FIN: '.date('d-m-Y H:i:s').' ====='.PHP_EOL);
                fclose($fp);
            }
        }
        return($stats);
    }

    private function enviarEmailAutomatico__($compra_id = null)
    {

        if (! $compra_id)
            return false;

        $options = array(
            'conditions' => array(
                'Compra.id' => $compra_id,
                'Compra.estado' => 1,
                'Compra.despachado' => 1
            ),
            'fields' => array(
                'Compra.id',
                'Compra.boleta',
                'Compra.picking_number',
                'Compra.cod_despacho',
                'Compra.local',
                'Compra.rural',
                'Compra.direccion_rural',
                'Compra.despacho_id',
                'Compra.usuario_id',
                'Compra.subtotal',
                'Compra.iva',
                'Compra.neto',
                'Compra.descuento',
                'Compra.total',
                'Compra.valor_despacho',
                'Compra.pago_id',
                'Compra.estado',
                'Compra.despachado',
                'Compra.fecha_enviado',
                'Compra.mail_confirmacion',
                'Compra.empresa_despacho_id',
                'Compra.boleta_pdf',
            ),
            'contain' => array(
                'Despacho' => array(
                    'fields' => array(
                        'Despacho.id',
                        'Despacho.direccion_id',
                    ),
                    'Direccion' => array(
                        'fields' => array(
                            'Direccion.id',
                            'Direccion.calle',
                            'Direccion.numero',
                            'Direccion.depto',
                            'Direccion.comuna_id',
                            'Direccion.region_id',
                        ),
                        'Comuna.nombre',
                        'Region.nombre'
                    )
                ),
                'Usuario' => array(
                    'fields' => array(
                        'Usuario.id',
                        'Usuario.nombre',
                        'Usuario.apellido_paterno',
                        'Usuario.apellido_materno',
                        'Usuario.email',
                        'Usuario.sexo_id'
                    ),
                    'Sexo.nombre'
                ),
                'Pago' => array(
                    'fields' => array(
                        'Pago.id',
                        'Pago.compra_id',
                        'Pago.numeroOrden',
                        'Pago.monto',
                        'Pago.numeroTarjeta',
                        'Pago.fecha',
                        'Pago.hora',
                        'Pago.estado',
                        'Pago.mac',
                        'Pago.codAutorizacion',
                        'Pago.expiracion',
                        'Pago.fechaContable',
                        'Pago.tipoPago',
                        'Pago.codigo'
                    )
                )
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
            return false;


        if (! $compra['Compra']['picking_number'])
            return false;

        if (! $compra['Compra']['boleta'])
        {
            //return false;
        }
        if (!$compra['Compra']['cod_despacho'])
            return false;

        if ($compra['Compra']['local']==1)
            return false;

        if ($compra['Compra']['mail_confirmacion'])
            return false;


        $this->set(compact('compra'));

        //EMAIL
        $this->Email->smtpOptions =array(


            'transport' => 'Smtp',
            //'from' => array('noreply@skechers-chile.cl' => 'Testing'),
            'port' => '465',
            'timeout' => '30',
            'auth' => true,
            'host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
            'username' => 'AKIAZK3GISZV5N5QNTQB',
            'password' => 'BP66zMPbbcyLkCTQq4DuptpKUmF0j/7dcIIFyCJXAbe/'
        );



        $archivo= $compra['Compra']['boleta_pdf'];
        $basedir = DS.'var'.DS.'www'.DS.'html'.DS.'webroot'.DS.'boletas_skechers'.DS;
        //$basedir = 'C:/xampp/htdocs/skechers/webroot/boletas_skechers/boleta_pdf'.DS;

        $archivo= $compra['Compra']['boleta_pdf'];
        //$basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'boletas_skechers'.DS;
        //$basedir = 'C:/xampp/htdocs/skechers/webroot/boletas_skechers/boleta_pdf'.DS;

        //mailtraps

        /*$this->Email->smtpOptions =array(

            'port' => '2525',
            'timeout' => '30',
            'auth' => true,
            'host' => 'smtp.mailtrap.io',
            'username' => 'f50be2e01f78cd',
            'password' => 'dbc99f0c5b8768'
        );*/

        //$personal = '8ff88ec03f-49a47a@inbox.mailtrap.io';
        // DATOS DESTINATARIO (CLIENTE)
        $copias = array(
            //'sebastian@sancirilo.cl',
            'ecom@skechers.com'
        );

        $template = 'despacho';
        $this->Email->attachments =array($basedir.$archivo);
        $this->Email->to = $compra['Usuario']['email'];
        //$this->Email->to = $personal;
        $this->Email->bcc	= $copias;
        $this->Email->subject = '[Skechers - Tienda en linea] Despacho Enviado #' . $compra_id;
        $this->Email->from = 'Skechers <noreply@skechers-chile.cl>';
        //$this->Email->replyTo = $this->email_skechers[2];
        $this->Email->sendAs = 'html';
        $this->Email->layout = 'ventas';
        $this->Email->template	= $template;//$despacho;
        $this->Email->delivery = 'smtp';
        if ($this->Email->send())
        {
            $this->Compra->save(array('id' => $compra_id, 'mail_confirmacion' => 1));
            return true;
        }

        return false;
    }
    private function enviarEmailRetiro($compra = null)
    {
        if (! $compra)
            return false;
    


        $this->set(compact('compra'));

        //EMAIL
        $this->Email->smtpOptions =array(


            'transport' => 'Smtp',
            //'from' => array('noreply@skechers-chile.cl' => 'Testing'),
            'port' => '465',
            'timeout' => '30',
            'auth' => true,
            'host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
            'username' => 'AKIAZK3GISZV5N5QNTQB',
            'password' => 'BP66zMPbbcyLkCTQq4DuptpKUmF0j/7dcIIFyCJXAbe/'
        );



        $archivo= $compra['Compra']['boleta_pdf'];
        $basedir = DS.'var'.DS.'www'.DS.'html'.DS.'webroot'.DS.'boletas_skechers'.DS;
        //$basedir = 'C:/xampp/htdocs/skechers/webroot/boletas_skechers/boleta_pdf'.DS;

        $archivo= $compra['Compra']['boleta_pdf'];
        //$basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'boletas_skechers'.DS;
        //$basedir = 'C:/xampp/htdocs/skechers/webroot/boletas_skechers/boleta_pdf'.DS;

        //mailtraps

        /*$this->Email->smtpOptions =array(

            'port' => '2525',
            'timeout' => '30',
            'auth' => true,
            'host' => 'smtp.mailtrap.io',
            'username' => 'f50be2e01f78cd',
            'password' => 'dbc99f0c5b8768'
        );*/

        //$personal = '8ff88ec03f-49a47a@inbox.mailtrap.io';
        // DATOS DESTINATARIO (CLIENTE)
        $email_store = "store".$compra['Retiro']['codigo']."@skechers.com";
        $copias = array(
                //'sebastian@sancirilo.cl',
                'ecom@skechers.com',
                $email_store 
        );

        $template = 'retiro';
        $this->Email->attachments =array($basedir.$archivo);
        $this->Email->to = $compra['Usuario']['email'];
        //$this->Email->to = $personal;
        $this->Email->bcc   = $copias;
        $this->Email->subject = '[Skechers - Tienda en linea] Despacho listo para retiro #' . $compra['Compra']['id'];
        $this->Email->from = 'Skechers <noreply@skechers-chile.cl>';
        //$this->Email->replyTo = $this->email_skechers[2];
        $this->Email->sendAs = 'html';
        $this->Email->layout = 'ventas';
        $this->Email->template  = $template;//$despacho;
        $this->Email->delivery = 'smtp';
        if ($this->Email->send())
        {
            $this->Compra->save(array('id' => $compra['Compra']['id'], 'mail_confirmacion' => 1));
            return true;
        }

        return false;
    }
    function ajax_devolucion_producto()
    {
        // verifica sesion
        if (! $this->Session->check('Auth.Administrador'))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Lo sentimos, su sesión ha expirado.'
            );
            die(json_encode($respuesta));
        }

        if (empty($this->data))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Parametros invalidos'
            );
            die(json_encode($respuesta));
        }

        if (! isset($this->data['Compra']['id']))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Parametros invalidos'
            );
            die(json_encode($respuesta));
        }
        elseif (! $this->data['Compra']['id'])
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Parametros invalidos'
            );
            die(json_encode($respuesta));
        }

        if (! isset($this->data['Compra']['productos_compra_id']))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Parametros invalidos'
            );
            die(json_encode($respuesta));
        }
        elseif (! $this->data['Compra']['productos_compra_id'])
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Parametros invalidos'
            );
            die(json_encode($respuesta));
        }

        if (! isset($this->data['Compra']['producto_id']))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Parametros invalidos'
            );
            die(json_encode($respuesta));
        }
        elseif (! $this->data['Compra']['producto_id'])
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Parametros invalidos'
            );
            die(json_encode($respuesta));
        }

        if (! isset($this->data['Compra']['tipo']))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Parametros invalidos'
            );
            die(json_encode($respuesta));
        }
        elseif (! $this->data['Compra']['tipo'])
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Parametros invalidos'
            );
            die(json_encode($respuesta));
        }

        if (! isset($this->data['Compra']['razon']))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Parametros invalidos'
            );
            die(json_encode($respuesta));
        }
        elseif (! $this->data['Compra']['razon'])
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Parametros invalidos'
            );
            die(json_encode($respuesta));
        }

        // compra
        $options = array(
            'conditions' => array(
                'Compra.id' => $this->data['Compra']['id'],
                'Compra.estado' => array(1,3)
            ),
            'fields' => array(
                'Compra.id',
                'ProductosCompra.id',
                'ProductosCompra.producto_id',
                'ProductosCompra.talla',
                'ProductosCompra.valor',
                'ProductosCompra.categoria',
                'Usuario.nombre',
                'Usuario.apellido_paterno',
                'Usuario.email'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_productos_compras',
                    'alias' => 'ProductosCompra',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ProductosCompra.compra_id = Compra.id',
                        'ProductosCompra.id' => $this->data['Compra']['productos_compra_id'],
                        'ProductosCompra.producto_id' => $this->data['Compra']['producto_id'],
                        'ProductosCompra.estado' => array(0,1),
                    )
                ),
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                ),
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Registro invalido.'
            );
            die(json_encode($respuesta));
        }

        // generar registro devolicion
        $devolucion = array(
            'productos_compra_id' => $compra['ProductosCompra']['id'],
            'compra_id' => $compra['Compra']['id'],
            'tipo' => $this->data['Compra']['tipo'],
            'razon' => $this->data['Compra']['razon']
        );

        $this->Compra->Devolucion->create();
        if (! $this->Compra->Devolucion->save($devolucion))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'No se pudo generar la solicitud de devolución. Por favor intentelo nuevamente.'
            );
            die(json_encode($respuesta));
        }

        //PRODUCTO DEVUELTO
        $producto_devuelto = array(
            'id' => $compra['ProductosCompra']['id'],
            'devolucion' => 1,
        );
        if (! $this->Compra->ProductosCompra->save($producto_devuelto))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Se produjo un problema al intentar devolver el producto. Por favor intentelo nuevamente.'
            );
            die(json_encode($respuesta));
        }

        $update = array(
            'id' => $compra['Compra']['id'],
            'estado' => 3
        );
        if ( $this->Compra->save($update) )
        {
            //$para = $compra['Usuario']['email'];
            //// ASUNTO
            //$asunto = '[Skechers - Solicitud de Cambio] Cambio N°'.$compra['Compra']['id'];
            //// MENSAJE
            //$mensaje = 	'Estimado (a) '.$compra['Usuario']['nombre'].' '.$compra['Usuario']['apellido_paterno'].'<br />'.
            //			'Hemos recibido su Solicitud de Cambio N°'.$compra['Compra']['id'].'<br />'.
            //			'El o los producto será retirado por la Empresa de Transporte dentro de las próximas 48 horas, '.
            //			'al momento del retiro el producto deberá estar envuelto y rotulado con lo siguiente:<hr />'.
            //			'SKECHERS CHILE<br />'.
            //			'Av. Andres Bello 2447 local 4181- Providencia<br />'.
            //			'MALL COSTANERA CENTER<hr />'.
            //			'Una vez recibido y analizado el producto será liberado el Cupón que podrá utilizar para el cambio, dicho descuento es personal e intransferible.<br />'.
            //			'<b>Skechers Chile</b>';
            ////$copia = array('ventas@skechers-chile.cl', 'pyanez@skechers.com', 'store383@skechers.com', 'cherrera@skechers.cl');
            //$copia = array('ehenriquez@andain.cl', 'sdelvillar@andain.cl');
            //if ($this->enviar_email_mensaje($para, $mensaje, $asunto, $copia))
            //{
            $respuesta = array(
                'estado' => 'OK',
                'mensaje' => 'La solicitud de cambio ha sido enviada exitosamente.'
            );
            die(json_encode($respuesta));
            //}
            //else
            //{
            //	$respuesta = array(
            //		'estado' => 'OK',
            //		'mensaje' => 'La solicitud ha sido enviada correctamente, pero no se pudo enviar una copia a tu correo. (COD:'.$compra['Compra']['id'].')'
            //	);
            //	die(json_encode($respuesta));
            //}
        }
        else
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Lo sentimos, no fue posible cambiar el estado de la compra. (COD:'.$compra['Compra']['id'].')'
            );
            die(json_encode($respuesta));
        }
    }

    function admin_devolucion($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Registro invalido.', true));
            $this->redirect(array('action' => 'index'));
        }

        $options = array(
            'conditions' => array(
                'Compra.id' => $id
            ),
            'fields' => array(
                'Compra.id',
                'Compra.despacho_id',
                'Compra.usuario_id',
                'Compra.subtotal',
                'Compra.iva',
                'Compra.neto',
                'Compra.descuento',
                'Compra.total',
                'Compra.valor_despacho',
                'Compra.estado',
                'Compra.boleta',
                'Compra.numId',
                'Compra.cod_despacho',
                'Pago.marca',
                'Pago.estado',
                'Direccion.id',
                'Direccion.calle',
                'Direccion.numero',
                'Direccion.depto',
                'Direccion.telefono',
                'Direccion.celular',
                'Comuna.nombre',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno',
                'Usuario.email',
                'Usuario.rut'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_despachos',
                    'alias' => 'Despacho',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Despacho.id = Compra.despacho_id'
                    )
                ),
                array(
                    'table' => 'sitio_pagos',
                    'alias' => 'Pago',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Compra.id = Pago.compra_id'
                    )
                ),
                array(
                    'table' => 'sitio_direcciones',
                    'alias' => 'Direccion',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Direccion.id = Despacho.direccion_id'
                    )
                ),
                array(
                    'table' => 'sitio_comunas',
                    'alias' => 'Comuna',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Comuna.id = Direccion.comuna_id'
                    )
                ),
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                )
            )
        );

        if (! $compra = $this->Compra->find('first',$options))
        {
            $this->Session->setFlash(__('Registro invalido.', true));
            $this->redirect(array('action' => 'index'));
        }
      //  prx($compra);

        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $compra['Compra']['id']
            ),
            'fields' => array(
                'ProductosCompra.id',
                'ProductosCompra.descuento_id',
                'ProductosCompra.talla',
                'ProductosCompra.valor',
                'ProductosCompra.devolucion_dinero',
                'ProductosCompra.estado',
                'ProductosCompra.devolucion',
                'Producto.id',
                'Producto.nombre',
                'Producto.foto',
                'Producto.codigo_completo',
                'Descuento.id',
                'Descuento.tipo',
                'Descuento.descuento',
                'Color.nombre',
                'Color.codigo',
                'Devolucion.id',
                'Devolucion.compra_id',
                'Devolucion.productos_compra_id',
                'Devolucion.tipo',
                'Devolucion.estado',
                'Devolucion.producto',
                'Devolucion.fecha',
                'Devolucion.hora',
                'Devolucion.talla',
                'Devolucion.codigo',
                'Devolucion.razon',
                'Devolucion.observaciones',
                'Devolucion.created',
                'Devolucion.nombre_titular',
                'Devolucion.apellido_titular',
                'Devolucion.run_titular',
                'Devolucion.tcuenta_titular',
                'Devolucion.ncuenta_titular',
                'Devolucion.banco_titular',
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                ),
                array(
                    'table' => 'sitio_colores',
                    'alias' => 'Color',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Color.id = Producto.color_id'
                    )
                ),
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devolucion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Devolucion.productos_compra_id = ProductosCompra.id',
                        'Devolucion.compra_id' => $compra['Compra']['id']
                    )
                ),
                array(
                    'table' => 'sitio_descuentos',
                    'alias' => 'Descuento',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Descuento.id = ProductosCompra.descuento_id',
                        'Devolucion.compra_id' => $compra['Compra']['id']
                    )
                ),
            )
        );
        $productos = $this->Compra->ProductosCompra->find('all',$options);
        //prx($productos);

        $options = array(
            'conditions' => array(
                'compra_id' => $compra['Compra']['id']
                //'Compra.estado' => array(1,3),
                //'usuario_id' => $usuario_id,
            ),
            'fields' => array(
                'tipoPago'
            ),
        );
        $pago = $this->Compra->Pago->find('first',$options);
        $tipoPago = $pago['Pago']['tipoPago'];

        $this->set(compact('compra', 'productos','tipoPago'));
    }

    function admin_procesar_devolucion()
    {
        if (empty($this->data))
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'devuelto'));
        }

        if (! isset($this->data['Devolucion']['id']))
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'devuelto'));
        }
        elseif (! $this->data['Devolucion']['id'])
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'devuelto'));
        }

        if (! isset($this->data['Devolucion']['compra_id']))
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'devuelto'));
        }
        elseif (! $this->data['Devolucion']['compra_id'])
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'devuelto'));
        }

        if (! isset($this->data['Devolucion']['productos_compra_id']))
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'devuelto'));
        }
        elseif (! $this->data['Devolucion']['productos_compra_id'])
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'devuelto'));
        }

        $options = array(
            'conditions' => array(
                'Devolucion.id' => $this->data['Devolucion']['id'],
                'Devolucion.compra_id' => $this->data['Devolucion']['compra_id'],
                'Devolucion.productos_compra_id' => $this->data['Devolucion']['productos_compra_id']
            )
        );
        if (! $devolucion = $this->Compra->Devolucion->find('first',$options))
        {
            $this->Session->setFlash(__('Registro inválido', true));
            $this->redirect(array('action' => 'devuelto'));
        }

        if (isset($this->data['Devolucion']['fecha']) && $this->data['Devolucion']['fecha'])
        {
            if ($fecha = strtotime(implode('-',$this->data['Devolucion']['fecha'])))
                $this->data['Devolucion']['fecha'] = date('Y-m-d',$fecha);
            else
                unset($this->data['Devolucion']['fecha']);
        }

        if (isset($this->data['Devolucion']['hora']) && $this->data['Devolucion']['hora'])
        {
            if ($hora = (string)($this->data['Devolucion']['hora']['hour']) && $minuto = (string)($this->data['Devolucion']['hora']['min'])) {
                $this->data['Devolucion']['hora'] = $hora . ':' . $minuto;
            }
            else
                unset($this->data['Devolucion']['hora']);
        }
       // $fecha = strtotime(implode('-',$this->data['Devolucion']['fecha']));
        $this->data['Devolucion']['fecha_picks'] = date('Y-m-d');
        if ($this->Compra->Devolucion->save($this->data['Devolucion']))
        {
            if ($this->data['Devolucion']['estado'] == 1)
            {
                //$email = $this->enviar_email_devolucion($this->data);
                $this->Session->setFlash(__('Registro guardado correctamente.', true));
            }
            elseif ($this->data['Devolucion']['estado'] == 2)
            {
                //$email = $this->enviar_email_devolucion($this->data);
                $this->Session->setFlash(__('Registro guardado correctamente.', true));
            }
            else
            {
                $this->Session->setFlash(__('Registro guardado correctamente.', true));
            }
            if($this->data['Devolucion']['estado'] == 1){
                $email = $this->enviar_email_devolucion($this->data);
            }
            $options = array(
                'conditions' => array(
                    'compra_id' => $this->data['Devolucion']['compra_id'],
                    'estado ' => 0
                )
            );
            $cantidad_devoluciones = $this->Compra->Devolucion->find('count',$options);
            if (isset($cantidad_devoluciones) && $cantidad_devoluciones != 0){
                $compra = $this->Compra->save(array('id' => $this->data['Devolucion']['compra_id'], 'estado' => 12));
            }else{
                $compra = $this->Compra->save(array('id' => $this->data['Devolucion']['compra_id'], 'estado' => 4));
            }
        }
        else
        {
            $this->Session->setFlash(__('No fue posible guardar el registro. Por favor intentelo nuevamente.', true));
        }
        $this->redirect(array('action' => 'devolucion',$this->data['Devolucion']['compra_id']));
    }
    function enviar_email_devolucion($data){
        //var_dump('aqui');
        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $data['Devolucion']['compra_id']
            ),
            'fields' => array(
                'ProductosCompra.id',
                'ProductosCompra.descuento_id',
                'ProductosCompra.talla',
                'ProductosCompra.valor',
                'ProductosCompra.devolucion_dinero',
                'ProductosCompra.estado',
                'ProductosCompra.devolucion',
                'Producto.id',
                'Producto.nombre',
                'Producto.foto',
                'Producto.codigo_completo',
                'Descuento.id',
                'Descuento.tipo',
                'Descuento.descuento',
                'Color.nombre',
                'Color.codigo',
                'Devolucion.id',
                'Devolucion.compra_id',
                'Devolucion.productos_compra_id',
                'Devolucion.tipo',
                'Devolucion.estado',
                'Devolucion.producto',
                'Devolucion.fecha',
                'Devolucion.hora',
                'Devolucion.talla',
                'Devolucion.codigo',
                'Devolucion.razon',
                'Devolucion.observaciones',
                'Devolucion.created',
                'Devolucion.nombre_titular',
                'Devolucion.apellido_titular',
                'Devolucion.run_titular',
                'Devolucion.tcuenta_titular',
                'Devolucion.ncuenta_titular'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                ),
                array(
                    'table' => 'sitio_colores',
                    'alias' => 'Color',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Color.id = Producto.color_id'
                    )
                ),
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devolucion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Devolucion.productos_compra_id = ProductosCompra.id',
                        'Devolucion.compra_id' => $data['Devolucion']['compra_id']
                    )
                ),
                array(
                    'table' => 'sitio_descuentos',
                    'alias' => 'Descuento',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Descuento.id = ProductosCompra.descuento_id',
                        'Devolucion.compra_id' => $data['Devolucion']['compra_id']
                    )
                ),
            )
        );
        $productos = $this->Compra->ProductosCompra->find('all',$options);

        $compra_id = $data['Devolucion']['compra_id'];;
        $options = array(
            'conditions' => array(
                'Compra.id' => $compra_id
            ),
            'fields' => array(
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno',
                'Usuario.email',
                'Usuario.rut'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                )
            )
        );
        $compra = $this->Compra->find('first',$options);

        $this->set(compact('data','productos','compra'));

        /*$this->Email->smtpOptions =array(
            'port' => '2525',
            'timeout' => '30',
            'auth' => true,
            'host' => 'smtp.mailtrap.io',
            'username' => 'f50be2e01f78cd',
            'password' => 'dbc99f0c5b8768'
        );*/
        $this->Email->smtpOptions =array(
            'port' => '587',
            'timeout' => '30',
            'auth' => true,
            'host' => 'mail.smtp2go.com',
            'username' => 'noresponder@skechers-chile.cl',
            'password' => 'eXV6M2k1cWp4Yzcw'
        );

        $personal = '8ff88ec03f-49a47a@inbox.mailtrap.io';
        // DATOS DESTINATARIO (CLIENTE)
        $copias = array(
            'ecom@skechers.com'
        );

        $template = 'devolucion_estado';
        $this->Email->to = $compra['Usuario']['email'];
        //$this->Email->to = $personal;
        $this->Email->bcc	= $copias;
        $this->Email->subject = '[Skechers - Tienda en linea] Solicitud de devolución #' . $data['Devolucion']['codigo'];
        $this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
        $this->Email->replyTo = $this->email_skechers[2];
        $this->Email->sendAs = 'html';
        $this->Email->layout = 'devoluciones';
        $this->Email->template	= $template;
        $this->Email->delivery = 'smtp';
        if ($this->Email->send())
        {
            //$this->Compra->save(array('id' => $compra_id, 'mail_confirmacion' => 1));
            return true;
        }

        return true;
    }

    function ajax_notificacion_devolucion()
    {
        if (! $this->Session->check('Auth.Administrador'))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Sesión invalida'
            );
            die(json_encode($respuesta));
        }

        if (! isset($this->params['form']['id']))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Registro invalido'
            );
            die(json_encode($respuesta));
        }
        elseif (! $this->params['form']['id'])
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Registro invalido'
            );
            die(json_encode($respuesta));
        }

        $options = array(
            'conditions' => array(
                'Devolucion.id' => $this->params['form']['id'],
                'Devolucion.estado' => array(1,2)
            ),
            'fields' => array(
                'Devolucion.id',
                'Devolucion.tipo',
                'Devolucion.estado',
                'Devolucion.codigo',
                'Devolucion.fecha',
                'Devolucion.hora',
                'Devolucion.observaciones',
                'Compra.id',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno',
                'Usuario.email',
                'Producto.id',
                'Producto.nombre',
                'Producto.foto',
                'Producto.codigo_completo',
                'ProductosCompra.id',
                'ProductosCompra.talla',
                'ProductosCompra.valor',
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_compras',
                    'alias' => 'Compra',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Compra.id = Devolucion.compra_id'
                    )
                ),
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                ),
                array(
                    'table' => 'sitio_productos_compras',
                    'alias' => 'ProductosCompra',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ProductosCompra.id = Devolucion.productos_compra_id'
                    )
                ),
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                ),
            )
        );
        if (! $devolucion = $this->Compra->Devolucion->find('first',$options))
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'Registro invalido'
            );
            die(json_encode($respuesta));
        }
        //1 => 'Devolución del producto',
        //2 => 'Cambio de producto',
        //3 => 'Cambio por talla',
        $texto = '';
        if ($devolucion['Devolucion']['estado'] == 2)
        {
            $texto = '<b>Estimad@ '.$devolucion['Usuario']['nombre'].' '.$devolucion['Usuario']['apellido_paterno'].'</b><hr>';
            $texto.='La devolución no ha sido aceptada.';
            $texto.='Compra: '.$devolucion['Compra']['id'].'<br>';
            $texto.='Producto: '.$devolucion['Producto']['nombre'].' ('.$devolucion['Producto']['codigo_completo'].')<br>';
            $texto.='Talla: '.$devolucion['ProductosCompra']['talla'].'<br>';
            $texto.='Valor: '.$devolucion['ProductosCompra']['valor'].'<br>';
            $texto.='Podras ver esta información mas detallada en tu historial de compra.';
        }
        else
        {
            if ($devolucion['Devolucion']['tipo'] == 1)
            {
                $texto = '<b>Estimad@ '.$devolucion['Usuario']['nombre'].' '.$devolucion['Usuario']['apellido_paterno'].'</b><hr>';
                $texto.='La devolucion ha sido ingresada con exito.<br>';
                $texto.='Compra: '.$devolucion['Compra']['id'].'<br>';
                $texto.='Producto: '.$devolucion['Producto']['nombre'].' ('.$devolucion['Producto']['codigo_completo'].')<br>';
                $texto.='Talla: '.$devolucion['ProductosCompra']['talla'].'<br>';
                $texto.='Valor: '.$devolucion['ProductosCompra']['valor'].'<br>';
                $texto.='Podras ver esta información mas detallada en tu historial de compra.';
            }
            elseif (in_array($devolucion['Devolucion']['tipo'],array(2,3)))
            {
                $cod = "";
                for ($i=0; $i<4; $i++)
                {
                    if ( rand(1,6) % 2 == 1 )
                        $cod .= chr(rand(65,90));
                    else
                        $cod .= chr(rand(48,57));
                }
                $codigo = 'CMB-'.$devolucion['Compra']['id'].'X'.$devolucion['ProductosCompra']['id'].'-'.$cod;
                // generar descuento
                $options = array(
                    'conditions' => array(
                        'Categoria.publico' => 1
                    ),
                    'fields' => array(
                        'Categoria.id', 'Categoria.id'
                    )
                );
                $lista_categorias = $this->Compra->Producto->Categoria->find('list',$options);
                $categorias = array();
                foreach ($lista_categorias as $categoria)
                {
                    $categorias[] = array('categoria_id'=> $categoria);
                }
                // 60 dias
                $caducidad = date('Y-m-d', strtotime(date('Y-m-d')) + (60 * 60 * 24 * 15));
                $new_descuento = array(
                    'Descuento' => array(
                        'nombre' 			=> 'DEVOLUCION #'.$devolucion['Compra']['id'],
                        'cantidad' 			=> 1,
                        'fecha_caducidad' 	=> $caducidad,
                        'codigo' 			=> $codigo,
                        'contador' 			=> 0,
                        'tipo' 				=> 'DIN',
                        'descuento' 		=> $devolucion['ProductosCompra']['valor']
                    ),
                    'Categoria'	=> $categorias
                );
                // generar descuento
                $this->Compra->Producto->Categoria->Descuento->create();
                if ( $this->Compra->Producto->Categoria->Descuento->saveAll($new_descuento) )
                {
                    $texto = '<b>Estimad@ '.$devolucion['Usuario']['nombre'].' '.$devolucion['Usuario']['apellido_paterno'].'</b><hr>';
                    $texto.='La devolucion ha sido ingresada con exito.<br>';
                    $texto.='Codigo de descuento: '.$codigo.'<br>';
                    $texto.='Compra: '.$devolucion['Compra']['id'].'<br>';
                    $texto.='Producto: '.$devolucion['Producto']['nombre'].' ('.$devolucion['Producto']['codigo_completo'].')<br>';
                    $texto.='Talla: '.$devolucion['ProductosCompra']['talla'].'<br>';
                    $texto.='Valor: '.$devolucion['ProductosCompra']['valor'].'<br>';
                    $texto.='Podras ver esta información mas detallada en tu historial de compra.';
                }
                else
                {
                    $respuesta = array(
                        'estado' => 'ERROR',
                        'mensaje' => 'Se produjo un problema al generar el codigo de descuento. Intentelo nuevamente.'
                    );
                    die(json_encode($respuesta));
                }
            }
            else
            {
                $respuesta = array(
                    'estado' => 'ERROR',
                    'mensaje' => 'Registro invalido'
                );
                die(json_encode($respuesta));
            }
        }

        $mensaje = $texto;
        $this->set(compact('mensaje'));
        $this->Email->smtpOptions = array(
            'port' => '25',
            'timeout' => '30',
            'auth' => true,
            'host' => 'skechers-chile.cl',
            'username' => 'noreply@skechers-chile.cl',
            'password' => 'andainandain'
        );
        $this->Email->to = 'ehenriquez@andain.cl';
        $this->Email->subject = '[Skechers - Tienda en linea] Devolucion #'.$devolucion['Devolucion']['id'];
        $this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
        $this->Email->replyTo = $this->email_skechers[2];
        $this->Email->sendAs = 'html';
        $this->Email->template	= 'mensaje';
        $this->Email->delivery = 'smtp';

        if ($this->Email->send())
        {
            $respuesta = array(
                'estado' => 'OK',
                'mensaje' => 'Notificacion enviada exitosamente'
            );
            die(json_encode($respuesta));
        }
        else
        {
            $respuesta = array(
                'estado' => 'ERROR',
                'mensaje' => 'No fue posible enviar la notificacion.'
            );
            die(json_encode($respuesta));
        }
    }

    function ajax_previsualizar_email($id = null)
    {
        if (! $this->Session->check('Auth.Administrador'))
            die('Sesión invalida.');
        if (empty($this->data))
            die('Registro invalido');

        if (! isset($this->data['Devolucion']['compra_id']))
            die('Registro invalido');
        elseif (! $this->data['Devolucion']['compra_id'])
            die('Registro invalido');

        if (! isset($this->data['Compra']['estado']))
            die('Estado invalido');
        elseif (! $this->data['Compra']['estado'])
            die('Estado invalido');
        elseif (! in_array($this->data['Compra']['estado'],array(6,7)))
            die('Estado invalido');

        $fecha = $this->data['Devolucion']['fecha']['day'].'-'.$this->data['Devolucion']['fecha']['month'].'-'.$this->data['Devolucion']['fecha']['year'];
        $hora = $this->data['Devolucion']['hora_desde']['hour'].':'.$this->data['Devolucion']['hora_desde']['min'].' a '.$this->data['Devolucion']['hora_hasta']['hour'].':'.$this->data['Devolucion']['hora_hasta']['min'];

        $email = $this->generarEmailDevolucionEnProceso($fecha,$hora,$this->data['Compra']['estado'],false,true);
        die($email);
    }

    function retiro_pdf()
    {
        $this->layout = 'pdf';
        if (! isset($this->params['url']['a']))
            die(false);
        if (! isset($this->params['url']['b']))
            die(false);
        if (! isset($this->params['url']['c']))
            die(false);
        if (! $this->params['url']['a'])
            die(false);
        if (! $this->params['url']['b'])
            die(false);
        if (! $this->params['url']['c'])
            die(false);
        $textos = array();
        /** estados:
         * 6 devolucion
         * 7 cambio
         */
        if (isset($this->params['url']['estado']) && in_array($this->params['url']['estado'],array(6,7)))
        {
            if ($this->params['url']['estado'] == 6)
            {
                $textos = array(
                    'titulo' => 'DEVOLUCION DE PRODUCTO VENTA ONLINE',
                    'info' => 'Si necesitas anular la Compra. Debes imprimir y pegar este Formulario a la caja y esperar que Chilexpress lo retire desde la misma dirección en donde fue entregado.'
                );
            }
            else
            {
                $textos = array(
                    'titulo' => 'CAMBIO PRODUCTO VENTA ONLINE',
                    'info' => 'Si necesitas cambiar el producto que compraste por una nueva talla o modelo, sólo debes imprimir y pegar este Formulario a la caja y esperar que Chilexpress lo retire desde la misma dirección en donde fue entregado.'
                );
            }
        }

        if (! $compra = $this->datosToken($this->params['url']))
            die(false);
        $this->layout = 'pdf';
        $this->set(compact('compra', 'textos'));
        // OPCIONES GENERALES DEL PDF
        $this->compartidoPDF();
        $this->Tcpdf->SetTitle('Presupuesto');
        $css	= $this->render('pdf_css');
        //// ESCRIBE EL HTML AL DOCUMENTO PDF
        $html	= explode('<<<Tcpdf->AddPage()>>>', $this->render());
        //paginas del documento
        foreach ( $html as $contenido )
        {
            $this->Tcpdf->AddPage();
            $this->Tcpdf->writeHTML($css . $contenido, true, false, true, false);
        }
        $this->Tcpdf->Output('devolucion_pdf.pdf', 'D');
    }

    private function generarToken($compra_id = null)
    {
        if (! $compra_id)
            return false;

        if (! $compra = $this->Compra->findById($compra_id))
            return false;

        $token['a'] = md5('Andain'.$compra_id);
        $token['b'] = substr($token['a'],5,4).$compra_id.substr($token['a'],10,4);
        $token['a'] = md5('Andain'.$compra['Compra']['usuario_id']);
        $token['c'] = substr($token['a'],7,3).$compra['Compra']['usuario_id'].substr($token['a'],13,3);
        $token['a'] = md5('Andain'.$compra_id.$compra['Compra']['usuario_id']);
        return $token;
    }

    private function datosToken($token = array())
    {
        if (! $token)
            return false;
        if (! is_array($token))
            return false;

        if (! isset($token['b']))
            return false;
        elseif (! $token['b'])
            return false;
        elseif (! $compra_id = substr($token['b'],4,-4))
            return false;

        if (! isset($token['c']))
            return false;
        elseif (! $token['c'])
            return false;
        elseif (! $usuario_id = substr($token['c'],3,-3))
            return false;

        if (! isset($token['a']))
            return false;
        elseif (! $token['a'])
            return false;
        elseif ($token['a'] != md5('Andain'.$compra_id.$usuario_id))
            return false;

        $options = array(
            'conditions' => array(
                'Compra.id' => $compra_id,
                'Compra.usuario_id' => $usuario_id
            ),
            'fields' => array(
                'Compra.id',
                'Compra.estado',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno',
                'Usuario.apellido_materno',
                'Devolucion.id',
                'Devolucion.codigo',
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                ),
            )
        );
        if ($this->Session->check('Auth.Administrador'))
        {
            array_push($options['joins'],array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devolucion',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Devolucion.compra_id = Compra.id'
                    )
                )
            );
        }
        else
        {
            if (! isset($token['ra']))
                return false;
            if (! $token['ra'])
                return false;
            array_push($options['joins'],array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devolucion',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Devolucion.compra_id = Compra.id',
                        'Devolucion.codigo' => $token['ra']
                    )
                )
            );
        }
        if (! $compra = $this->Compra->find('first',$options))
            return false;
        if ($this->Session->check('Auth.Administrador'))
        {
            $compra['Devolucion']['codigo'] = $token['ra'];
        }
        return $compra;
    }

    private function compartidoPDF()
    {
        // INICIALIZAMOS TCPDF
        App::import('Vendor', 'xtcpdf');

        // CREA EL NUEVO DOCUMENTO PDF
        $this->Tcpdf = new XTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);

        // SETEA LA INFORMACION SOBRE EL DOCUMENTO
        $this->Tcpdf->SetCreator('Skechers Chile');
        $this->Tcpdf->SetAuthor('Skechers Chile');

        // DESHABILITA HEADER Y FOOTER
        $this->Tcpdf->setPrintHeader(false);
        $this->Tcpdf->setPrintFooter(true);

        // CORTES DE PAGINA AUTOMATICOS
        $this->Tcpdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // FUENTE MONOSPACE
        $this->Tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // SETEA LOS MARGENES DE LA PAGINA
        $this->Tcpdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
        $this->Tcpdf->SetFooterMargin(0);

        // RATIO DE ESCALADO DE IMAGENES
        $this->Tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // CALIDAD DE IMAGENES
        $this->Tcpdf->setJPEGQuality(100);
    }

    function admin_devolucion_proceso()
    {
        if (empty($this->data))
            $this->redirect(array('action' => 'index'));
        if (! isset($this->data['Devolucion']['compra_id']))
            $this->redirect(array('action' => 'index'));
        if (! $this->data['Devolucion']['compra_id'])
            $this->redirect(array('action' => 'index'));
        if (! isset($this->data['Compra']['estado']))
            die('Estado invalido');
        elseif (! $this->data['Compra']['estado'])
            die('Estado invalido');
        elseif (! in_array($this->data['Compra']['estado'],array(6,7)))
            die('Estado invalido');
        $options = array(
            'conditions' => array(
                'Compra.id' => $this->data['Devolucion']['compra_id']
            ),
            'fields' => array(
                'Compra.id',
                'Usuario.email'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                )
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
            $this->redirect(array('action' => 'index'));
        $save = array(
            'Compra' => array(
                'id' => $compra['Compra']['id'],
                'estado' => $this->data['Compra']['estado']
            ),
            'Devolucion' => array(
                0 => array(
                    'tipo' => 1,
                    'estado' => 0,
                    'codigo' => $this->data['Devolucion']['codigo'],
                    'fecha' => $this->data['Devolucion']['fecha']['year'].'-'.$this->data['Devolucion']['fecha']['month'].'-'.$this->data['Devolucion']['fecha']['day'],
                    'hora' => $this->data['Devolucion']['hora_desde']['hour'].':'.$this->data['Devolucion']['hora_desde']['min'].' a '.$this->data['Devolucion']['hora_hasta']['hour'].':'.$this->data['Devolucion']['hora_hasta']['min']
                )
            )
        );
        if ($this->Compra->saveAll($save))
        {
            $token = $this->generarToken($compra['Compra']['id']);
            $adjunto = 'http://desarrollo.skechers-chile.cl/compras/retiro_pdf?a='.$token['a'].'&b='.$token['b'].'&c='.$token['c'].'&ra='.$this->data['Devolucion']['codigo'].'&estado='.$this->data['Compra']['estado'];
            $fecha =$this->data['Devolucion']['fecha']['day'].'-'.$this->data['Devolucion']['fecha']['month'].'-'.$this->data['Devolucion']['fecha']['year'];
            $hora = $this->data['Devolucion']['hora_desde']['hour'].':'.$this->data['Devolucion']['hora_desde']['min'].' a '.$this->data['Devolucion']['hora_hasta']['hour'].':'.$this->data['Devolucion']['hora_hasta']['min'];
            $mensaje = $this->generarEmailDevolucionEnProceso($fecha,$hora,$this->data['Compra']['estado'],$adjunto);
            $this->set(compact('mensaje'));
            $this->Email->smtpOptions = array(
                'port' => '25',
                'timeout' => '30',
                'auth' => true,
                'host' => 'skechers-chile.cl',
                'username' => 'noreply@skechers-chile.cl',
                'password' => 'andainandain'
            );
            $this->Email->to = $compra['Usuario']['email'];
            $this->Email->bcc = array('solanger@skechers.com');
            $this->Email->subject = '[Skechers - Tienda en linea] Devolucion #'.$compra['Compra']['id'];
            $this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
            $this->Email->replyTo = $this->email_skechers[2];
            $this->Email->sendAs = 'html';
            $this->Email->template	= 'mensaje';
            $this->Email->delivery = 'smtp';

            if ($this->Email->send())
                $this->Session->setFlash(__('Registro guardado exitosamente. La notificación ha sido enviada al cliente.', true));
            else
                $this->Session->setFlash(__('Registro guardado exitosamente. la nofiticación NO se ha podido enviar al cliente.', true));
            $this->redirect(array('action' => 'edit',$compra['Compra']['id']));
        }
        else
        {
            $this->Session->setFlash(__('Lo sentimos, no fue posible guardar el registro.', true));
            $this->redirect(array('action' => 'edit',$compra['Compra']['id']));
        }
    }

    private function generarEmailDevolucionEnProceso($fecha = null, $hora = null, $estado = null, $adjunto = false, $previsualizacion = false)
    {
        if (! $fecha)
            return false;
        if (! $hora)
            return false;
        if (! $estado)
            return false;
        if (! in_array($estado,array(6,7)))
            return false;
        $email='';
        if ($estado == 6)
        {
            $email = '
			<table width="575" border="0" cellspacing="0" style="background-color:#FFF; padding: 20px;">';
            if ($previsualizacion)
                $email.='<tr><td><img alt="" src="http://www.skechers.cl/img/mailing/mailing_catss_despacho.png" width="100%" /></td></tr>';
            $email.='
				<tr>
					<td style="font-family: caption,Geneva,sans-serif;">
						Estimado Cliente,
						<br><br>
						Para realizar la devolución Chilexpress retirara el producto en la misma dirección en que fue entregado. ';
            if ($adjunto)
                $email.='<a href="'.$adjunto.'" target="_blank">Debe imprimir el formulario y pegarlo a la caja</a>';
            else
                $email.='Debe imprimir el formulario y pegarlo a la caja';
            $email.='. Chilexpress sólo retirará el producto si esta rotulado. 
						<br><br>
						El producto deberá encontrarse en perfectas condiciones, debiendo restituirse con todos sus embalajes, etiquetas, certificados de garantía, manuales de uso, cajas, elementos de protección y demás documentos o accesorios incluidos en el producto (que también deberán encontrarse en perfecto estado).
						<br><br>
						El retiro de Chilexpress ha sido programado para <b>el día '.$fecha.',  de '.$hora.' hrs.<br>
						IMPORTANTE: Al momento de entregar el producto a Chilexpress exija el comprobante correspondiente al retiro</b>.';
            if ($adjunto)
                $email.='<br><br>Adjunto: <a href="'.$adjunto.'" target="_blank">Documento devolución</a>';
            $email.='<br><br>
						Adjunto
						<br><br>
						Saluda atentamente,
						<br><br>
						<b style="color: #333;">
							Servicio al Cliente
							<br>
							Skechers Chile
						</b>
						<br>
						<img alt="" src="http://www.skechers.cl/img/mailing/servicio_cliente.png">
					</td>
				</tr>';
            $email.='</table>';
        }
        elseif ($estado == 7)
        {
            $email = '
			<table width="575" border="0" cellspacing="0" style="background-color:#FFF; padding: 20px;">';
            if ($previsualizacion)
                $email.='<tr><td><img alt="" src="http://www.skechers.cl/img/mailing/mailing_catss_despacho.png" width="100%" /></td></tr>';
            $email.='
				<tr>
					<td style="font-family: caption,Geneva,sans-serif;">
						Estimado Cliente,
						<br><br>
						Para realizar el cambio Chilexpress retirara el producto en la misma dirección en que fue entregado. ';
            if ($adjunto)
                $email.='<a href="'.$adjunto.'" target="_blank">Debe imprimir el formulario y pegarlo a la caja</a>';
            else
                $email.='Debe imprimir el formulario y pegarlo a la caja. Chilexpress sólo retirará el producto si esta rotulado';
            $email.='. Chilexpress sólo retirará el producto si esta rotulado.<br><br>
						El producto deberá encontrarse en perfectas condiciones, debiendo restituirse con todos sus embalajes, etiquetas, certificados de garantía, manuales de uso, cajas, elementos de protección y demás documentos o accesorios incluidos en el producto (que también deberán encontrarse en perfecto estado).
						<br><br>
						El retiro de Chilexpress ha sido programado para el <b>día '.$fecha.',  de '.$hora.' hrs</b>. 
						<b>IMPORTANTE: Al momento de entregar el producto a Chilexpress exija el comprobante correspondiente al retiro</b>.';
            if ($adjunto)
                $email.='<br><br>Adjunto: <a href="'.$adjunto.'" target="_blank">Documento devolución</a>';
            $email.='<br><br>
						Saluda atentamente,
						<br><br>
						<b style="color: #333;">
							Servicio al Cliente
							<br>
							Skechers Chile
						</b>
						<br>
						<img alt="" src="http://www.skechers.cl/img/mailing/servicio_cliente.png">
					</td>
				</tr>';
            if ($adjunto)
                $email.='<tr><td style="color:#000;">Adjunto: '.$adjunto.'</td></tr>';
            $email.='</table>';
        }
        return $email;
    }

    function ajax_devolucion_info($id = null)
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        if (! $id)
            die(false);
        $options = array(
            'conditions' => array(
                'Compra.id' => $id
            ),
            'fields' => array(
                'Compra.id',
                'Devolucion.id',
                'Devolucion.codigo',
                'Devolucion.fecha',
                'Devolucion.hora'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devolucion',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Devolucion.compra_id = Compra.id'
                    )
                )
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
            die(false);
        $respuesta = array(
            'compra_id' => $compra['Compra']['id'],
            'codigo' => $compra['Devolucion']['codigo'],
            'fecha' => date('d-m-Y',strtotime($compra['Devolucion']['fecha'])),
            'hora' => $compra['Devolucion']['hora'],
        );
        die(json_encode($respuesta));
    }

    function ajax_previsualizar_email_devolucion($tipo = null)
    {
        if (! $this->Session->check('Auth.Administrador'))
            die('Sesión invalida.');
        if (! $tipo)
            die('Registro invalido');

        $email = $this->generarEmailDevolucion($tipo,true);

        die($email);
    }

    private function generarEmailDevolucion($tipo = null,$previsualizar = false)
    {
        if (! $tipo)
            return false;
        if ($tipo == 'debito')
        {
            $email = '
			<table width="575" border="0" cellspacing="0" style="background-color:#FFF; padding: 20px;">';
            if ($previsualizar)
                $email.='<tr><td><img alt="" src="http://www.skechers.cl/img/mailing/mailing_catss_despacho.png" width="100%" /></td></tr>';
            $email.='
				<tr>
					<td style="font-family: caption,Geneva,sans-serif;text-align: left;">
						Estimado Cliente,
						<br><br>
						Su Solicitud de Devolución ha sido Aprobada, de acuerdo al medio de pago original. Favor enviar los siguientes datos para generar la reversa:
						<br><br>
						<b>Nombre Completo:</b><br>
						<b>Rut:</b><br>
						<b>Banco:</b><br>
						<b>N°de Cuenta:</b><br>
						<b>Tipo de Cuenta:</b><br>
						<b>Mail de confirmación:</b><br>
						<br><br>
						Saluda atentamente,
						<br><br>
						<b style="color: #333;">
							Servicio al Cliente
							<br>
							Skechers Chile
						</b>
						<br>
						<img alt="" src="http://www.skechers.cl/img/mailing/servicio_cliente.png">
					</td>
				</tr>
			</table>';
        }
        elseif ($tipo == 'credito')
        {
            $email = '
			<table width="575" border="0" cellspacing="0" style="background-color:#FFF; padding: 20px;">';
            if ($previsualizar)
                $email.='<tr><td><img alt="" src="http://www.skechers.cl/img/mailing/mailing_catss_despacho.png" width="100%" /></td></tr>';
            $email.='
				<tr>
					<td style="font-family: caption,Geneva,sans-serif;text-align: left;">
						Estimado Cliente,
						<br><br>
						Su Solicitud de Devolución ha sido Aprobada, dentro de las próximas 72 horas hábiles se realizará la reversa en su Tarjeta de Crédito.
						<br><br>
						Saluda atentamente,
						<br><br>
						<b style="color: #333;">
							Servicio al Cliente
							<br>
							Skechers Chile
						</b>
						<br>
						<img alt="" src="http://www.skechers.cl/img/mailing/servicio_cliente.png">
					</td>
				</tr>
			</table>';
        }
        return $email;
    }
    function detalle_boleta()
    {
        if ($this->data['Compras']["id"]){

            $dato = $this->data['Compras']["id"];

            $compra = $this->Compra->find('first', array(
                'fields' =>
                    array(
                        'id',
                        'created',
                        'boleta_pdf',
                        'boleta'
                    ),
                'conditions' =>
                    array(
                        'OR' => array(
                            'id'	=>  $dato,
                            'mv_orden'	=>  $dato
                        ),
                        'estado' => 1
                    ),
                'order' =>
                    array(
                        'id' => 'desc'
                    )
            ));
            if ($compra){
                if ($compra['Compra']['boleta_pdf']){


                    $boletas = $compra['Compra']['boleta_pdf'];
                    $url = '../boletas_skechers/';
                    $path = $url.$boletas;

                    $this->set(compact('path'));
                }
                else{
                    $error = 'La orden ingresada, no tiene una boleta asociada';
                    $this->set(compact('error',''));
                }

            }else{
                $error = 'La orden ingresada, no tiene una boleta asociada.';
                $this->set(compact('error',''));
            }

        }

    }
    function estado_despacho($token =  null)
    {
        //Configure::write('debug',2);
        if (!empty($this->data))
        {
            $correo = $this->data['Compras']['correo'];
            $usuario = $this->Compra->Usuario->find('first', array('fields' =>array('id','nombre', 'apellido_paterno'),
                'conditions' => array('email' => $correo)));
            if($usuario)
            {
                $compra = $this->Compra->find('first', array('fields' => array(
                    'id',
                    'created',
                    'cod_despacho',
                    'empresa_despacho_id'),
                    'conditions' => array(

                        'usuario_id' => $usuario['Usuario']['id'],
                        'id'		=> $this->data['Compras']["id"],


                        'estado' => 1),
                    'order' => array('id' => 'desc')));

                if($compra)
                {
                    if($compra['Compra']['cod_despacho'] !='')
                    {
                        if ($compra['Compra']['empresa_despacho_id'] == 2) {

                            $busqueda = array("reference" => $compra['Compra']['id'],
                                "transportOrderNumber" => $compra['Compra']['cod_despacho'],
                                "rut" => 76047265,
                                "showTrackingEvents" => 1);

                            $array_json = json_encode($busqueda);

                            $date = date_create($compra['Compra']['created']);
                            $busqueda['created'] = date_format($date, "d-m-Y");

                            //componente que llama a chileexpress
                            $datos = $this->Chilexpress->despacho($array_json);

                            if (!$datos === false) {

                                $despacho = $datos['despacho'];
                                $recepcion = $datos['recepcion'];
                                $trackeo = $datos['trackeo'];

                                $this->set(compact('despacho', 'recepcion', 'trackeo', 'busqueda'));
                            } else {
                                $error = 'datos no corresponden';
                                $this->set(compact('error'));
                            }
                        }elseif ($compra['Compra']['empresa_despacho_id'] == 1){
                            $id_compra = $this->data['Compras']["id"];

                            $this->set(compact('id_compra'));
                        }else{
                            $error = 'Tu compra aun no ha sido despachada';
                            $this->set(compact('error'));
                        }

                    }else{
                        $error = 'Tu compra aun no ha sido despachada';
                        $this->set(compact('error'));
                    }
                }else{
                    $error = 'Email y/o Numero de orden no corresponden';
                    $this->set(compact('error'));
                }

            }else{
                $error = 'Email y/o Numero de orden no corresponden';
                $this->set(compact('error'));
            }
        }else{


            if($this->params['pass'])
            {
                $empresa = 2;

                $token = $this->params['pass'];

                $q = $this->Chilexpress->obtenerToken($token[0]);
                if($q) {
                    list($id_compra, $correo) = split('/', $q);

                    $empresa = $this->Compra->find('first', array('fields' =>array('empresa_despacho_id'),
                        'conditions' => array('cod_despacho' => $id_compra)));
                }else{


                    $error = 'Aun No esta disponible la informacion de su despacho';
                    $this->set(compact('error'));
                }

                if ($empresa){

                    switch ($empresa['Compra']['empresa_despacho_id']) {
                        case 1:
                            $this->set(compact('id_compra'));
                            break;
                        case 2:
                            $datos = $this->Chilexpress->detalle($token[0]);
                            if($datos){
                                $despacho = $datos['despacho'];
                                $recepcion = $datos['recepcion'];
                                $trackeo = $datos['trackeo'];
                                $busqueda = $datos['busqueda'];

                                $this->set(compact('despacho', 'recepcion', 'trackeo', 'busqueda'));
                            }else{
                                $error = 'El Link no es correcto o presenta un error';
                                $this->set(compact('error'));
                            }

                            break;
                        case 3:
                            $error = 'Aun No esta disponible la informacion de su despacho';
                            $this->set(compact('error'));
                            break;
                    }

                }else{
                    $error = 'Aun No esta disponible la informacion de su despacho';
                    $this->set(compact('error'));
                }

            }

        }

    }


    function ajax_finalizar_devolucion()
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        if (! $this->params['form']['id'])
            die(false);
        if (! $this->params['form']['estado'])
            die(false);
        if (! in_array($this->params['form']['estado'],array('ACEPTAR','CANCELAR')))
            die(false);
        $options = array(
            'conditions' => array(
                'Compra.id' => $this->params['form']['id'],
                'Compra.estado' => 6
            ),
            'fields' => array(
                'Compra.id',
                'Usuario.id',
                'Usuario.email'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                ),
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devolucion',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Devolucion.compra_id = Compra.id'
                    )
                ),
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
        {
            $respuesta = array(
                'estado' => 'Registro invalido.'
            );
            die(json_encode($respuesta));
        }
        if ($this->params['form']['estado'] == 'CANCELAR')
        {
            // devolver estado PAGADO a la compra
            $update = array(
                'id' => $compra['Compra']['id'],
                'estado' => 1
            );
            if ($this->Compra->save($update))
            {
                // desvincular devoluciones
                $query = 'UPDATE sitio_devoluciones SET compra_id = -'.$compra['Compra']['id'].' WHERE compra_id = '.$compra['Compra']['id'].';';
                $this->Compra->Devolucion->query($query);
                die(json_encode(array('estado' => 'OK')));
            }
            else
            {
                die(json_encode(array('estado' => 'No fue posible guardar el registro.')));
            }
        }
        if ($this->params['form']['estado'] == 'ACEPTAR')
        {
            // devolver estado ANULADO a la compra
            $update = array(
                'id' => $compra['Compra']['id'],
                'estado' => 2
            );
            if ($this->Compra->save($update))
            {
                // enviar email
                $mensaje = $this->generarEmailDevolucion($this->params['form']['tipo']);
                $this->set(compact('mensaje'));
                $this->Email->smtpOptions = array(
                    'port' => '25',
                    'timeout' => '30',
                    'auth' => true,
                    'host' => 'skechers-chile.cl',
                    'username' => 'noreply@skechers-chile.cl',
                    'password' => 'andainandain'
                );
                $this->Email->to = $compra['Usuario']['email'];
                $this->Email->bcc = array('solanger@skechers.com');
                $this->Email->subject = '[Skechers - Tienda en linea] Devolucion #'.$compra['Compra']['id'];
                $this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
                $this->Email->replyTo = $this->email_skechers[2];
                $this->Email->sendAs = 'html';
                $this->Email->template	= 'mensaje';
                $this->Email->delivery = 'smtp';

                if ($this->Email->send())
                    die(json_encode(array('estado' => 'OK')));
                else
                    die(json_encode(array('estado' => 'La compra ha sido anulada, pero no ha sido posible enviar el correo de notificación al cliente ('.$compra['Usuario']['email'].').')));
            }
            else
            {
                die(json_encode(array('estado' => 'No fue posible guardar el registro.')));
            }
        }
        die(json_encode(array('estado' => 'Error.')));
    }

    function ajax_cambio_sin_diferencia_devueltos($id = null)
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        if (! $id)
            die(false);
        $options = array(
            'conditions' => array(
                'Compra.id' => $id,
                'Compra.estado' => 7
            ),
            'fields' => array(
                'Compra.id',
            ),
            'recursive' => -1,
        );
        if (! $compra = $this->Compra->find('first',$options))
            die(false);

        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $id
            ),
            'fields' => array(
                'Producto.id',
                'Producto.nombre',
                'Producto.codigo_completo',
                'Producto.foto',
                'ProductosCompra.id',
                'ProductosCompra.talla',
                'ProductosCompra.valor'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                )
            ),
            'group' => array('ProductosCompra.id')
        );
        if (! $list = $this->Compra->ProductosCompra->find('all',$options))
            die(false);

        $productos = array();
        foreach ($list as $producto)
        {
            $productos[$producto['ProductosCompra']['id']] = $producto['Producto']['codigo_completo'].' (talla:'.$producto['ProductosCompra']['talla'].')';
        }
        $this->set(compact('productos'));
    }

    function ajax_cambio_sin_diferencia_seleccionado($id = null)
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        if (! $id)
            die(false);
        $options = array(
            'conditions' => array(
                'ProductosCompra.id' => $id
            ),
            'fields' => array(
                //'Producto.id',
                //'Producto.nombre',
                //'Producto.codigo_completo',
                //'Producto.foto',
                'ProductosCompra.id',
                //'ProductosCompra.talla',
                'ProductosCompra.valor'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                )
            )
        );
        if (! $producto = $this->Compra->ProductosCompra->find('first',$options))
            die(false);
        $options = array(
            'conditions' => array(
                'Producto.oferta' => 0,
                'Producto.precio' => $producto['ProductosCompra']['valor'],
                'Producto.activo' => 1,
                'NOT' => array(
                    array('Producto.foto' => null),
                    array('Producto.foto' => '')
                )
            ),
            'fields' => array(
                'Producto.id'
            ),
            'joins' => array(
                array(
                    'table' => 'sitio_categorias',
                    'alias' => 'Categoria',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Categoria.id = Producto.categoria_id',
                        'Categoria.publico' => 1
                    )
                ),
                array(
                    'table' => 'sitio_stocks',
                    'alias' => 'Stock',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Stock.producto_id = Producto.id',
                    )
                )
            )
        );
        $list = $this->Compra->Producto->find('list',$options);
        $options = array(
            'conditions' => array(
                'Producto.oferta' => 1,
                'Producto.precio_oferta' => $producto['ProductosCompra']['valor'],
                'Producto.activo' => 1,
                'NOT' => array(
                    array('Producto.foto' => null),
                    array('Producto.foto' => '')
                )
            ),
            'fields' => array(
                'Producto.id'
            ),
            'joins' => array(
                array(
                    'table' => 'sitio_categorias',
                    'alias' => 'Categoria',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Categoria.id = Producto.categoria_id',
                        'Categoria.publico' => 1
                    )
                ),
                array(
                    'table' => 'sitio_stocks',
                    'alias' => 'Stock',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Stock.producto_id = Producto.id',
                    )
                )
            )
        );
        $list = array_merge($list,$this->Compra->Producto->find('list',$options));
        $options = array(
            'conditions' => array(
                'Producto.id' => $list
            ),
            'fields' => array(
                'Producto.id',
                'Producto.codigo_completo'
            )
        );
        $productos = $this->Compra->Producto->find('list',$options);
        die(json_encode($productos));
    }
    function ajax_cambio_talla($id = null)
    {
        //Configure::write('debug',2);
        $options = array(
            'conditions' => array(
                'Stock.producto_id' => $id
            ),
            'fields' => array(
                'Stock.id',
                'Stock.talla'
            ),
            'order' => array(
                'Stock.talla' => 'ASC'
            )
        );
        $stocks = $this->Compra->Producto->Stock->find('list',$options);
        foreach ($stocks as $key => $stock)
        {
            $stocks[$key] = $this->Carro->talla($stock);
        }
        die(json_encode($stocks));
    }
    function ajax_cambio_producto($id = null)
    {
        $options = array(
            'conditions' => array(
                'Producto.activo' => 1
            ),
            'fields' => array(
                'Producto.id',
                'Producto.codigo_completo'
            )
        );
        die(json_encode($this->Compra->Producto->find('list',$options)));
    }

    function ajax_cambio_sin_diferencia_talla($id = null)
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        if (! $id)
            die(false);
        $options = array(
            'conditions' => array(
                'Producto.id' => $id,
                'Producto.activo' => 1,
                'NOT' => array(
                    array('Producto.foto' => null),
                    array('Producto.foto' => '')
                )
            ),
            'fields' => array(
                'Producto.id'
            ),
            'joins' => array(
                array(
                    'table' => 'sitio_categorias',
                    'alias' => 'Categoria',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Categoria.id = Producto.categoria_id',
                        'Categoria.publico' => 1
                    )
                ),
                array(
                    'table' => 'sitio_stocks',
                    'alias' => 'Stock',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Stock.producto_id = Producto.id',
                    )
                )
            )
        );
        if (! $producto = $this->Compra->Producto->find('first',$options))
            die(false);
        $options = array(
            'conditions' => array(
                'Stock.producto_id' => $id
            ),
            'fields' => array(
                'Stock.id',
                'Stock.talla'
            ),
            'order' => array(
                'Stock.talla' => 'ASC'
            )
        );
        die(json_encode($this->Compra->Producto->Stock->find('list',$options)));
    }

    function ajax_cambio_sin_diferencia_proceso()
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        if (empty($this->data))
            die(false);
        if (! $this->data['ProductosCompra']['id'])
            die(false);
        if (! $this->data['Producto']['cambio'])
            die(false);
        if (! $this->data['Producto']['talla'])
            die(false);

        $options = array(
            'conditions' => array(
                'ProductosCompra.id' => $this->data['ProductosCompra']['id']
            ),
            'fields' => array(
                'ProductosCompra.id',
                'ProductosCompra.compra_id',
                'ProductosCompra.valor'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.id = ProductosCompra.producto_id'
                    )
                )
            )
        );
        if (! $productoOld = $this->Compra->ProductosCompra->find('first',$options))
            die(false);
        $options = array(
            'conditions' => array(
                'Producto.id' => $this->data['Producto']['cambio'],
                'Stock.id' => $this->data['Producto']['talla']
            ),
            'fields' => array(
                'Producto.id',
                'Producto.precio',
                'Producto.oferta',
                'Producto.precio_oferta',
                'Stock.id',
                'Stock.talla',
                'Categoria.nombre'
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
        if (! $productoNew = $this->Compra->Producto->find('first',$options))
            die(false);

        $precio = $productoNew['Producto']['precio'];
        if ($productoNew['Producto']['oferta'] && $productoNew['Producto']['precio'] > $productoNew['Producto']['precio_oferta'])
            $precio = $productoNew['Producto']['precio_oferta'];
        if ($precio != $productoOld['ProductosCompra']['valor'])
            die(false);

        $options = array(
            'conditions' => array(
                'Devolucion.compra_id' => $productoOld['ProductosCompra']['compra_id']
            ),
        );
        if (! $devolucion = $this->Compra->Devolucion->find('first',$options))
            die(false);

        // GENERAR ORDENES DE COMPRA Y ARCHIVOS USA !!!!

        $save = array(
            'id' => $devolucion['Devolucion']['id'],
            'productos_compra_id' => $productoOld['ProductosCompra']['id']
        );
        if (! $this->Compra->Devolucion->save($save))
        {
            $respuesta = array('estado' => 'No ha sido posible realizar el cambio. Por favor intentelo nuevamente.');
            die(json_encode($respuesta));
        }
        $save = array(
            'id' => $productoOld['ProductosCompra']['compra_id'],
            'estado' => 8
        );
        if ($this->Compra->save($save))
        {
            $new_producto = array(
                'producto_id' => $productoNew['Producto']['id'],
                'compra_id' => $productoOld['ProductosCompra']['compra_id'],
                'categoria' => $productoNew['Categoria']['nombre'],
                'talla' => $productoNew['Stock']['talla'],
                'valor' => $precio,
                'estado' => 1,
                'parent' => $productoOld['ProductosCompra']['id']
            );
            $this->Compra->ProductosCompra->create();
            $this->Compra->ProductosCompra->save($new_producto);
            $respuesta = array('estado' => 'OK');
            die(json_encode($respuesta));
        }
        $respuesta = array('estado' => 'Se ha generado un problema al intentar realizar el cambio. Por favor intentelo nuevamente.');
        die(json_encode($respuesta));
    }

    function ajax_cambio_sin_diferencia_previsualizar()
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        if (empty($this->data))
            die(false);

        $options = array(
            'conditions' => array(
                'Compra.id' => $this->data['Compra']['id'],
                'Compra.estado' => 8
            ),
            'fields' => array(
                'Compra.id',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno',
                'Direccion.id',
                'Direccion.calle',
                'Direccion.numero',
                'Direccion.depto',
                'Comuna.nombre',
                'Region.nombre'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                ),
                array(
                    'table' => 'sitio_despachos',
                    'alias' => 'Despacho',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Despacho.id = Compra.despacho_id'
                    )
                ),
                array(
                    'table' => 'sitio_direcciones',
                    'alias' => 'Direccion',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Direccion.id = Despacho.direccion_id'
                    )
                ),
                array(
                    'table' => 'sitio_comunas',
                    'alias' => 'Comuna',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Comuna.id = Direccion.comuna_id'
                    )
                ),
                array(
                    'table' => 'sitio_regiones',
                    'alias' => 'Region',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Region.id = Comuna.region_id'
                    )
                ),
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
            die(false);
        $email = '
		<form id="CompraAdminDevueltoForm" accept-charset="utf-8" method="post">
			<input id="CompraId" type="hidden" name="data[Compra][id]" value="'.$this->data['Compra']['id'].'">
			<input id="CompraCodDespacho" type="hidden" name="data[Compra][cod_despacho]" value="'.$this->data['Compra']['cod_despacho'].'">
			<input id="CompraBoleta" type="hidden" name="data[Compra][boleta]" value="'.$this->data['Compra']['boleta'].'">
		</form>
		<table width="575" border="0" cellspacing="0" style="background-color:#FFF; padding: 20px;">
			<tr><td><img alt="" src="http://www.skechers.cl/img/mailing/mailing_catss_despacho.png" width="100%" /></td></tr>
			<tr>
				<td style="font-family: caption,Geneva,sans-serif;text-align: left;">
					<b>Estimado(a) '.$compra['Usuario']['nombre'].'</b>,
					<br><br>
					Le informamos que su Cambio ha sido Aprobado. El despacho se realizara a <b>'.$compra['Direccion']['calle'].' #'.$compra['Direccion']['numero'].(($compra['Direccion']['depto'])?' depto.'.$compra['Direccion']['depto']:'').
            ', '.$compra['Comuna']['nombre'].' - '.$compra['Region']['nombre'].
            '</b> el/los productos por usted adquiridos. 
					<br><br>
					<b style="color:#8000ff;">En caso que quisiera hacer seguimiento de su Cambio, puede ingresar al siguiente sitio Web:</b>
					<br>
					<a href="http://www.chilexpress.cl" target="_blank">www.chilexpress.cl</a>
					<br><br>
					<b style="color:#8000ff;">Ingresando el código de seguimiento que estará disponible dentro de 24 horas:</b>
					<br>'.
            $this->data['Compra']['cod_despacho'].
            '<br><br>
					Saluda atentamente,
					<br><br>
					<b style="color: #333;">
						Servicio al Cliente
						<br>
						Skechers Chile
					</b>
					<br>
					<img alt="" src="http://www.skechers.cl/img/mailing/servicio_cliente.png">
				</td>
			</tr>
		</table>';
        die($email);
    }

    function ajax_cambio_sin_diferencia_aprobado()
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        if (empty($this->data))
            die(false);

        $options = array(
            'conditions' => array(
                'Compra.id' => $this->data['Compra']['id'],
                'Compra.estado' => 8
            ),
            'fields' => array(
                'Compra.id',
                'Compra.despacho_id',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.apellido_paterno',
                'Usuario.email',
                'Direccion.id',
                'Direccion.calle',
                'Direccion.numero',
                'Direccion.depto',
                'Comuna.nombre',
                'Region.nombre'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                ),
                array(
                    'table' => 'sitio_despachos',
                    'alias' => 'Despacho',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Despacho.id = Compra.despacho_id'
                    )
                ),
                array(
                    'table' => 'sitio_direcciones',
                    'alias' => 'Direccion',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Direccion.id = Despacho.direccion_id'
                    )
                ),
                array(
                    'table' => 'sitio_comunas',
                    'alias' => 'Comuna',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Comuna.id = Direccion.comuna_id'
                    )
                ),
                array(
                    'table' => 'sitio_regiones',
                    'alias' => 'Region',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Region.id = Comuna.region_id'
                    )
                ),
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
            die(false);
        $options = array(
            'conditions' => array(
                'ProductosCompra.compra_id' => $compra['Compra']['id'],
                'ProductosCompra.estado' => 1,
            ),
            'fields' => array(
                'ProductosCompra.id',
                'ProductosCompra.producto_id',
                'ProductosCompra.compra_id',
                'ProductosCompra.categoria',
                'ProductosCompra.talla',
                'ProductosCompra.valor',
                //'ProductosCompra.parent'
            ),
            'recursive' => -1
        );
        if (! $productoCambio = $this->Compra->ProductosCompra->find('first',$options))
            die(json_encode(array('estado' => 'no se encontro el producto devuelto.')));

        $update = array(
            'id' => $compra['Compra']['id'],
            'estado' => 9
        );
        if (! $this->Compra->save($update))
            die(json_encode(array('estado' => 'Se produjo un problema al intentar actualizar los datos de la compra.')));

        $new_compra = array(
            'Compra' => array(
                'boleta' => $compra['Compra']['boleta'],
                'cod_despacho' => $compra['Compra']['cod_despacho'],
                'despacho_id' => $compra['Compra']['despacho_id'],
                'usuario_id' => $compra['Usuario']['id'],
                'subtotal' => $productoCambio['ProductosCompra']['valor'],
                'total' => $productoCambio['ProductosCompra']['valor'],
                'estado' => 1
            ),
        );
        $this->Compra->create();
        $new_id = 0;
        if ($this->Compra->save($new_compra))
        {
            $new_id = $this->Compra->id;
            $update = array(
                'id' => $productoCambio['ProductosCompra']['id'],
                'compra_id' => $new_id,
                'estado' => 0,
                'devolucion_dinero' => 0,
                'devolucion' => 0
            );
            $this->Compra->ProductosCompra->save($update);
        }

        $mensaje = '
		<table width="575" border="0" cellspacing="0" style="background-color:#FFF; padding: 20px;">
			<tr>
				<td style="font-family: caption,Geneva,sans-serif;text-align: left;">
					<b>Estimado(a) '.$compra['Usuario']['nombre'].'</b>,
					<br><br>
					Le informamos que su Cambio ha sido Aprobado. El despacho se realizara a <b>'.$compra['Direccion']['calle'].' #'.$compra['Direccion']['numero'].(($compra['Direccion']['depto'])?' depto.'.$compra['Direccion']['depto']:'').
            ', '.$compra['Comuna']['nombre'].' - '.$compra['Region']['nombre'].
            '</b> el/los productos por usted adquiridos. 
					<br><br>
					<b style="color:#8000ff;">En caso que quisiera hacer seguimiento de su Cambio, puede ingresar al siguiente sitio Web:</b>
					<br>
					<a href="http://www.chilexpress.cl" target="_blank">www.chilexpress.cl</a>
					<br><br>
					<b style="color:#8000ff;">Ingresando el código de seguimiento que estará disponible dentro de 24 horas:</b>
					<br>'.
            $this->data['Compra']['cod_despacho'].
            '<br><br>
					Saluda atentamente,
					<br><br>
					<b style="color: #333;">
						Servicio al Cliente
						<br>
						Skechers Chile
					</b>
					<br>
					<img alt="" src="http://www.skechers.cl/img/mailing/servicio_cliente.png">
				</td>
			</tr>
		</table>';
        $this->set(compact('mensaje'));
        $this->Email->smtpOptions = array(
            'port' => '25',
            'timeout' => '30',
            'auth' => true,
            'host' => 'skechers-chile.cl',
            'username' => 'noreply@skechers-chile.cl',
            'password' => 'andainandain'
        );
        $this->Email->to = $compra['Usuario']['email'];
        $this->Email->bcc = array('solanger@skechers.com');
        $this->Email->subject = '[Skechers - Tienda en linea] CAMBIO #'.$new_id;
        $this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
        $this->Email->replyTo = $this->email_skechers[2];
        $this->Email->sendAs = 'html';
        $this->Email->template	= 'mensaje';
        $this->Email->delivery = 'smtp';

        if ($this->Email->send())
            die(json_encode(array('estado' => 'OK')));
        else
            die(json_encode(array('estado' => 'La compra ha sido realizada, pero no ha sido posible enviar el correo de notificación al cliente ('.$compra['Usuario']['email'].').')));
    }

    function ajax_cambio_con_diferencia_previsualizar()
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        if (! isset($this->params['form']['id']))
            die(false);
        if (! $this->params['form']['id'])
            die(false);
        $options = array(
            'conditions' => array(
                'Compra.id' => $this->params['form']['id'],
                'Compra.estado' => 7
            ),
            'fields' => array(
                'Compra.id',
                'Compra.total',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.email'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                ),
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
            die(false);
        $codigo = 'CMB-'.$this->params['form']['id'].'-';
        for ($i=0; $i<4; $i++)
        {
            if ( rand(1,6) % 2 == 1 )
                $codigo .= chr(rand(65,90));
            else
                $codigo .= chr(rand(48,57));
        }
        $mensaje = '
		<form id="CompraAdminDevueltoForm" accept-charset="utf-8" method="post">
			<input id="CompraId" type="hidden" name="data[Compra][id]" value="'.$this->params['form']['id'].'">
			<input id="CompraCodigo" type="hidden" name="data[Compra][codigo]" value="'.$codigo.'">
		</form>
		<table width="575" border="0" cellspacing="0" style="background-color:#FFF; padding: 20px;">
			<tr><td><img alt="" src="http://www.skechers.cl/img/mailing/mailing_catss_despacho.png" width="100%" /></td></tr>
			<tr>
				<td style="font-family: caption,Geneva,sans-serif;text-align: left;">
					<b>Estimado(a) '.$compra['Usuario']['nombre'].'</b>,
					<br><br>
					Le informamos que su Cambio ha sido Aprobado.
					Enviamos código de Cambio '.$codigo.' para que elija su nuevo producto. 
					Una vez generada la nueva orden despacharemos el producto en los próximos días.
					<br><br>
					Saluda atentamente,
					<br><br>
					<b style="color: #333;">
						Servicio al Cliente
						<br>
						Skechers Chile
					</b>
					<br>
					<img alt="" src="http://www.skechers.cl/img/mailing/servicio_cliente.png">
				</td>
			</tr>
		</table>';
        die($mensaje);
    }

    function ajax_cambio_con_diferencia_aprobado()
    {
        if (! $this->Session->check('Auth.Administrador'))
            die(false);
        if (empty($this->data))
            die(false);

        $options = array(
            'conditions' => array(
                'Compra.id' => $this->data['Compra']['id'],
                'Compra.estado' => 7
            ),
            'fields' => array(
                'Compra.id',
                'Compra.total',
                'Usuario.id',
                'Usuario.nombre',
                'Usuario.email'
            ),
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'sitio_usuarios',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Usuario.id = Compra.usuario_id'
                    )
                ),
            )
        );
        if (! $compra = $this->Compra->find('first',$options))
            die(false);

        $caducidad = (strtotime(date('Y-m-d')))+(60*60*24*30); // 30 dias

        $newDescuento = array(
            'Descuento' => array(
                'nombre' => 'CAMBIO #'.$compra['Compra']['id'],
                'cantidad' => 1,
                'fecha_caducidad' => date('Y-m-d',$caducidad),
                'codigo' => $this->data['Compra']['codigo'],
                'contador' => 0,
                'tipo' => 'DIN',
                'descuento' => $compra['Compra']['total'],
                'web_tienda' => 0,
                'maximo' => 1
            ),
            'Categoria' => array()
        );

        $options = array(
            'conditions' => array(
                'Categoria.publico' => 1
            ),
            'fields' => array(
                'Categoria.id',
                'Categoria.id'
            )
        );
        if ($list = $this->Compra->Producto->Categoria->find('list',$options))
        {
            foreach ($list as $categoriaId)
            {
                array_push($newDescuento['Categoria'],array('categoria_id'=> $categoriaId));
            }
        }

        $update = array(
            'id' => $compra['Compra']['id'],
            'estado' => 10
        );
        if (! $this->Compra->save($update))
            die(json_encode(array('estado' => 'No ha sido posible cambiar el estado de la compra.')));

        $this->loadModel('Descuento');
        $this->Descuento->create();
        if (! $this->Descuento->saveAll($newDescuento))
            die(json_encode(array('estado' => 'No ha sido posible generar el descuento.')));

        $mensaje = '
		<table width="575" border="0" cellspacing="0" style="background-color:#FFF; padding: 20px;">
			<tr>
				<td style="font-family: caption,Geneva,sans-serif;text-align: left;">
					<b>Estimado(a) '.$compra['Usuario']['nombre'].'</b>,
					<br><br>
					Le informamos que su Cambio ha sido Aprobado.
					Enviamos código de Cambio '.$this->data['Compra']['codigo'].' para que elija su nuevo producto. 
					Una vez generada la nueva orden despacharemos el producto en los próximos días.
					<br><br>
					Saluda atentamente,
					<br><br>
					<b style="color: #333;">
						Servicio al Cliente
						<br>
						Skechers Chile
					</b>
					<br>
					<img alt="" src="http://www.skechers.cl/img/mailing/servicio_cliente.png">
				</td>
			</tr>
		</table>';
        $this->set(compact('mensaje'));
        $this->Email->smtpOptions = array(
            'port' => '25',
            'timeout' => '30',
            'auth' => true,
            'host' => 'skechers-chile.cl',
            'username' => 'noreply@skechers-chile.cl',
            'password' => 'andainandain'
        );
        $this->Email->to = $compra['Usuario']['email'];
        $this->Email->bcc = array('solanger@skechers.com');
        $this->Email->subject = '[Skechers - Tienda en linea] CAMBIO #'.$compra['Usuario']['id'];
        $this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
        $this->Email->replyTo = $this->email_skechers[2];
        $this->Email->sendAs = 'html';
        $this->Email->template	= 'mensaje';
        $this->Email->delivery = 'smtp';

        if ($this->Email->send())
            die(json_encode(array('estado' => 'OK')));
        else
            die(json_encode(array('estado' => 'La compra ha sido actualizada exitosamente, pero no ha sido posible enviar el correo de notificación al cliente con el codigo de descuento ('.$compra['Usuario']['email'].').')));
    }

    function admin_reenviar_despachos()
    {
        Configure::write('debug',1);
        // $corregir = array(
        // 	'36905' => '696290986436',
        // 	'36907' => '696290986403',
        // );
        // $cont = 0;
        // foreach ($corregir as $compra_id => $cod_despacho)
        // {
        // 	$options = array(
        // 		'conditions' => array(
        // 			'Compra.id' => $compra_id,
        // 			'Compra.estado' => 1,
        // 			'OR' => array(
        // 				array('Compra.cod_despacho' => 0),
        // 				array('Compra.cod_despacho' => null)
        // 			)
        // 		),
        // 		'recursive' => -1
        // 	);
        // 	if ($compra = $this->Compra->find('first',$options))
        // 	{
        // 		$save = array('id' => $compra_id,'cod_despacho' => $cod_despacho);
        // 		if ($this->Compra->save($save))
        // 		{
        // 			$cont++;
        // 			echo $compra_id.' actualizado<br>';
        // 		}
        // 		else
        // 		{
        // 			echo $compra_id.' error<br>';
        // 		}
        // 	}
        // }
        // echo 'Se actualizaron : '.$cont.'<br><br><br>';
        $cont = 0;
        $options = array(
            'conditions' => array(
                'Compra.estado' => 1,
                'Compra.despachado' => 1,
                'Compra.fecha_enviado >=' => '2016-08-07 00:00:00',
                'NOT' => array(
                    array('Compra.local' => 1),
                    array('Compra.mail_confirmacion' => 1),
                    array('Compra.boleta' => null),
                    array('Compra.picking_number' => null),
                    array('Compra.cod_despacho' => null),
                )
            ),
            'fields' => array(
                'Compra.id','Compra.id'
            ),
            'limit' => 10
        );
        if ($compras = $this->Compra->find('list',$options))
        {
            foreach ($compras as $compraId)
            {
                // if ($this->enviarEmailAutomatico__($compraId))
                // {
                echo 'correo enviado '.$compraId.'<br>';
                // 	$cont++;
                // }
                // else
                // {
                // 	echo 'NO enviado '.$compraId.'<br>';
                // }
            }
            die('se enViaron '.$cont);
        }
        else
        {
            die('sin compras pendientes');
        }
    }
    function procesar_picks($shell = null){
        if($shell == 1){
            var_dump('shell');
            $compra = array();
        }

        //$basedir = 'C:/xampp/htdocs/';
        $basedir = DS.'home'.DS.'ubuntu'.DS;
        $archivo = $basedir.'30-09-20_test.csv';

        //var_dump($archivo);

        if(is_readable($archivo)) {
            //var_dump('Archivo encontrado, procesando....');
            $start = 5;
            $separador = ';';
            $mapeo = array(
                'Grade',
                'Receipt', // num_id
                'Customer', // 10988 canal online
                'Ret Num', // num_id
                'Div',
                'Style',
                'Color',
                'Qty',
                'PIX Date'
            );

            $stats = array(
                'lineas' => 0,
                'actualizados' => 0,
                'omitidos' => 0,
                'error' => 0,
                'log_actualizados' => '',
                'log_omitidos' => '',
            );

            $registro = array();
            if (($handle = fopen($archivo, 'r')) !== FALSE) {
                while (($datos = fgetcsv($handle, 0, $separador)) !== FALSE) {
                    $stats['lineas']++;

                    if ($stats['lineas'] <= $start)
                        continue;

                    foreach ($datos as $index => $valor) {
                        if (!isset($mapeo[$index]))
                            continue;

                        $registro[$mapeo[$index]] = utf8_decode(trim($valor));
                    }
                    //var_dump( $registro);
                    if ($registro['Receipt'] == ''){
                        $stats['error']++;
                        continue;
                    }
                    if ($registro['Style'] == '') {
                        $stats['error']++;
                        continue;
                    }
                    if ($registro['Color'] == '') {
                        $stats['error']++;
                        continue;
                    }
                    if($registro['Customer'] == '10998'){

                        $num_id = $registro['Receipt'];
                        $style = $registro['Style'];
                        $color = $registro['Color'];
                        //var_dump( $num_id);

                        //$respuesta = $this->consulta_compra($num_id,$style,$color);
                        if ($respuesta = $this->consulta_compra($num_id,$style,$color,$shell)){
                            //var_dump($respuesta);
                            if ($shell == 1){$compra = array_merge($compra,$respuesta);}
                            $stats['actualizados']++;
                            $stats['log_actualizados'] .= $respuesta[0]['Compra']['id'].';' ;
                        }else{
                            $stats['omitidos']++;
                            continue;
                            //$stats['log_omitidos'] .= $respuesta[0]['Compra']['id'].';' ;
                        }

                    }else{
                        $stats['omitidos']++;
                        continue;
                    }
                }
                //var_dump($stats['actualizados']);
                //var_dump($stats['omitidos']);
            }
            fclose($handle);
            if ($shell == 1){

                $merge = array(
                    'stats' => $stats,
                    'compras'=> $compra
                );

                return $merge;
            }else{

                $email = $this->email_interno($stats['actualizados'],$stats['log_actualizados'],$stats['omitidos'],$archivo);
                var_dump($email);
            }

        }else{
            var_dump('El archivo no existe,');
        }
    }
    function consulta_compra($id_num,$style,$color,$shell){

        $codigo = $style.$color;

        App::import('Model', 'Compra');
        $CompraOBJ = new Compra();
        App::import('Model', 'Devolucion');
        $DevolucionesOBJ = new Devolucion();

        $options = array(
            'conditions' => array(
                //'Compra.estado' => 1,
                'OR' => array(
                    'Compra.numId' => $id_num,
                    'Compra.id' => $id_num,
                ),
                'Compra.devolucion' => 1,
                'Devoluciones.producto' => $codigo,
                'Devoluciones.estado !=' =>1
            ),
            'fields' => array(
                'Compra.id',
                'Compra.usuario_id',
                'Devoluciones.id',
                'Devoluciones.producto',
                'Devoluciones.estado',
                //'ProductosCompra.valor',
            ),
            'joins' => array(
                array(
                    'table' => 'sitio_devoluciones',
                    'alias' => 'Devoluciones',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Devoluciones.compra_id = Compra.id'
                    )
                ),
                array(
                    'table' => 'sitio_pagos',
                    'alias' => 'Pago',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Pago.compra_id = Compra.id'
                    )
                ),
                array(
                    'table' => 'sitio_productos_compras',
                    'alias' => 'ProductosCompra',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'ProductosCompra.id = Devoluciones.productos_compra_id'
                    )
                ),

            ),
        );
        //var_dump($CompraOBJ->find('all', $options));
        if ($compras = $CompraOBJ->find('all', $options)){
            $options = array(
                'conditions' => array(
                    'id' => $compras[0]['Devoluciones']['id']
                )
            );
            if ($datos = $DevolucionesOBJ->find('first',$options)){
                $time = (strtotime(date('Y-m-d H:i:s'))); // hace dos hora atras
                $fecha = date('Y-m-d H:i:s', $time);
                $DevolucionesOBJ->save(array('id' => $datos['Devolucion']['id'], 'estado' => 1,'fecha_picks'=>$fecha));
                $datos['Devolucion']['estado'] = 1;
                if ($shell != 1){
                    //  $email = $this->email_devolucion($datos,$compras);
                }

                return $compras;
            }
        }
        //return $compras;
    }
    function email_devolucion($data,$datos)
    {

        //var_dump($datos[0]['Compra']['id']);
        App::import('Component', 'Email');
        $email =& new EmailComponent(null);
        $email->initialize($this->Controller);


        /*$this->Email->smtpOptions = array(
            'port' => '2525',
            'timeout' => '30',
            'auth' => true,
            'host' => 'smtp.mailtrap.io',
            'username' => 'f50be2e01f78cd',
            'password' => 'dbc99f0c5b8768'
        );*/

        //origianl

        $this->Email->smtpOptions = array(
            /*'port' => '587',
					'timeout' => '30',
					'auth' => true,
					'host' => 'mail.smtp2go.com',
					'username' => 'noresponder@skechers-chile.cl',
					'password' => 'eXV6M2k1cWp4Yzcw'*/
            'transport' => 'Smtp',
            //'from' => array('noreply@skechers-chile.cl' => 'Testing'),
            'port' => '465',
            'timeout' => '30',
            'auth' => true,
            'host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
            'username' => 'AKIAZK3GISZV5N5QNTQB',
            'password' => 'BP66zMPbbcyLkCTQq4DuptpKUmF0j/7dcIIFyCJXAbe/'
        );


        $this->set(compact('data'));

        $compra = $datos[0]['Compra']['id'];
        $personal = '8ff88ec03f-49a47a@inbox.mailtrap.io';
        // DATOS DESTINATARIO (CLIENTE)
        $copias = array(
            'sebastian@sancirilo.cl',
            'jumatamala@gmail.com',
            'jwalton@skechers.com'
            //'ecom@skechers.com'

        );


        $id_user = $datos[0]['Compra']['usuario_id'];
        //var_dump($id_user);
        //var_dump($datos);
        $options = array('conditions' => array('id' => $id_user));
        $usuario = $this->Compra->Usuario->find('first', $options);
        //var_dump($usuario['Usuario']['email']);
        //die();

        $template = 'devolucion_estado';
        $this->Email->to = $usuario['Usuario']['email'];
        //$this->Email->to = $personal;
        $this->Email->bcc	= $copias;
        $this->Email->subject = '[Skechers - Tienda en linea] Solicitud de devolución #' . $compra;
        $this->Email->from = 'noreply@skechers-chile.cl';
        // $this->Email->replyTo = $this->email_skechers[2];
        $this->Email->sendAs = 'html';
        $this->Email->layout = 'devoluciones';
        $this->Email->template = $template;
        $this->Email->delivery = 'smtp';
        if ($this->Email->send()) {
            //$this->Compra->save(array('id' => $compra_id, 'mail_confirmacion' => 1));
            return true;
        }else{
            prx($this->Email);
        }

        return false;
    }
    function email_interno($actualizados,$log_a,$omitidos,$archivo)
    {
        //var_dump('aqui');
        App::import('Component', 'Email');
        $email =& new EmailComponent(null);
        $email->initialize($this->Controller);


        /*$this->Email->smtpOptions = array(
            'port' => '2525',
            'timeout' => '30',
            'auth' => true,
            'host' => 'smtp.mailtrap.io',
            'username' => 'f50be2e01f78cd',
            'password' => 'dbc99f0c5b8768'
        );*/
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

        $this->set(compact('actualizados','log_a','omitidos'));

        //$compra = $datos[0]['Compra']['id'];
        $personal = '8ff88ec03f-49a47a@inbox.mailtrap.io';
        // DATOS DESTINATARIO (CLIENTE)
        $copias = array(
            'sebastian@sancirilo.cl',
            'jumatamala@gmail.com',
            'jwalton@skechers.com'
        );
        //'miguela@skechers.com',
        // 'ecom@skechers.com'
        //var_dump('aqui1');
        $template = 'devolucion_interno';
        $this->Email->to = 'sebastian@sancirilo.cl';
        //$this->Email->to = $personal;
        $this->Email->bcc	= $copias;
        $this->Email->subject = 'Correo con status de picks';
        $this->Email->from = 'noreply@skechers-chile.cl';
        // $this->Email->replyTo = $this->email_skechers[2];
        $this->Email->sendAs = 'html';
        $this->Email->layout = 'devoluciones';
        $this->Email->template = $template;
        $this->Email->delivery = 'smtp';
        if ($this->Email->send()) {
            //$this->Compra->save(array('id' => $compra_id, 'mail_confirmacion' => 1));
            return true;
        }else{
            prx($this->Email);
        }

        return false;
    }

}