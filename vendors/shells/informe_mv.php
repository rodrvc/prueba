<?php
App::import('Core', array('Router','Controller'));


class InformeMvShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main() 
	{
		$hoy = date('d-m-Y H:i:s');
		$fecha_hasta = date("Y-m-d 00:59:00", strtotime("$hoy "));
		$fecha_desde = date("Y-m-d 01:00:00", strtotime("$hoy   -1 day"));
		App::import('Model','Compra');
		$CompraOBJ = new Compra();
		$compras = $CompraOBJ->find('all', array('conditions'=> array('ip' 		=> 'mercadolibre',
																	  'created < ' 	=> $fecha_hasta,
																	  'created > ' 	=> $fecha_desde,
																	  'estado' 		=> 1
																	  ),
												'fields' => array('id', 'mv_orden', 'created', 'mv_task','mv_numero1','verificado','mail_compra')	,	
												'order' => array('id' => 'asc')
												));
      
	$mensaje ='<table width="400" style="border: 1px solid #0080c0;">
				<tr>
					<th colspan="4" style="color:#000;text-align:left;">Compras Multivende</th>
				</tr>
				<tr>
						<td width="15%" style="color:#000;text-align:left;font-size:small;">ID</td>
						<td width="30%" style="color:#000;text-align:left;font-size:small;">ID MV</td>
						<td width="25%" style="color:#000;text-align:left;font-size:small;">Cod. Despacho</b></td>
						<td width="25%" style="color:#000;text-align:left;font-size:small;">USA</td>
				</tr>';
			if ($compras)
			{
				foreach ($compras as $compra)
				{
					$mensaje.='
					<tr>
						<td width="15%" style="color:#000;text-align:left;font-size:small;">'.$compra['Compra']['id'].'</td>
						<td width="30%" style="color:#000;text-align:left;font-size:small;">'.$compra['Compra']['mv_orden'].'</td>
						<td width="25%" style="color:#000;text-align:left;font-size:small;"><b>'.$compra['Compra']['verificado'].'</b></td>
						<td width="25%" style="color:#000;text-align:left;font-size:small;"><b>'.$compra['Compra']['mail_compra'].'</b></td>
						
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

				$email->to = 'jwalton@skechers.com';
				$email->bcc	= array('sebastian@sancirilo.cl');

				$email->subject = '[Skechers - Tienda en linea] Compras Multivende';
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