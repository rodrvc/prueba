<?php
App::import('Core', array('Router','Controller'));


class PickingShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main() 
	{
		$hoy = date('d-m-Y H:i:s');
		$fecha_hasta = date("Y-m-d", strtotime("$hoy   -5 day"));
		$fecha_desde = date("Y-m-d", strtotime("$hoy   -30 day"));
		App::import('Model','Compra');
		$CompraOBJ = new Compra();
		$compras = $CompraOBJ->find('all', array('conditions'=> array('picking_number' => NULL,
																	  'ip !=' 		=> 'mercadolibre',
																	  'created < ' 	=> $fecha_hasta,
																	  'created > ' 	=> $fecha_desde,
																	  'estado' 		=> 1
																	  ),
												'fields' => array('id', 'created')	,	
												'order' => array('id' => 'asc'),
												'limit' => 1000));
      
	$mensaje ='<table width="400" style="border: 1px solid #0080c0;">
				<tr>
					<th colspan="2" style="color:#000;text-align:left;">Compras sin picking</th>
				</tr>';
			if ($compras)
			{
				foreach ($compras as $compra)
				{
					$mensaje.='
					<tr>
						<td width="15%" style="color:#000;text-align:left;font-size:small;">'.$compra['Compra']['id'].'</td>
						<td width="50%" style="color:#000;text-align:left;font-size:small;"><b>'.$compra['Compra']['created'].'</b></td>
						
					</tr>';
				}
			
				$mensaje.='</table>';
				App::import('Component', 'Email');
				$email =& new EmailComponent(null);
				$email->initialize($this->Controller);
		        $email->smtpOptions = array(
                'port' => '587',
                                        'timeout' => '30',
                                        'auth' => true,
                                        'host' => 'mail.smtp2go.com',
                                        'username' => 'noresponder@skechers-chile.cl',
                                        'password' => 'eXV6M2k1cWp4Yzcw'
                        );
				$this->Controller->set('mensaje', $mensaje);
				// DATOS DESTINATARIO (CLIENTE)

				$email->to = 'xeniac@skechers.com';
				$email->bcc	= array('sebastian@sancirilo.cl','jwalton@skechers.com');

				$email->subject = '[Skechers - Tienda en linea] Notificacion COmpras sin Picking';
				$email->from = 'Skechers <noreply@skechers-chile.cl>';
				$email->replyTo = 'noreply@skechers-chile.cl';
				$email->sendAs = 'html';
				$email->template	= 'mensaje';
				$email->delivery = 'smtp';
				if ($email->send())
				{
					echo 'enviado';
				}else{
					print_r($email);
				}
			}else{
				echo 'sin compras';
			}
		}

	
}
function prx($data)
{
	pr($data); exit;
}