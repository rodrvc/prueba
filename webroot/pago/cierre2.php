<?
set_time_limit(120); 
   include("mysql.php");
   $db = new MySQL();
   require_once( '../../vendor/autoload.php');
 
   if ( !isset($_POST["token_ws"]) && isset($_POST['TBK_TOKEN']))
   {
  /* 		$token = $_POST['TBK_TOKEN'];
   		die($token);
   		$result  = Transbank\Webpay\WebpayPlus\Transaction::commit($token);
   		$json_result = str_replace('"','',json_encode($result));
   	

   	 	$sql = "update sitio_compras set estado = -1000, token = '".$_POST['TBK_TOKEN']."' where id = ".$trs_orden_compra;
        $db->ejecutar($sql);*/
        header('Location: http://54.234.213.249/productos/carro');

   }else{
      $token = $_POST["token_ws"];
   }
    $result  = Transbank\Webpay\WebpayPlus\Transaction::commit($token);
    $json_result = str_replace('"','',json_encode($result));

   /**** inicio de pagina de cierre xt_compra.php***/
   $url_exito = 'http://54.234.213.249/productos/felicidades';
 
   $trs_respuesta = $result->responseCode ;
   $estado = $result->status;
   $trs_orden_compra = $trs_id_transaccion = $result->buyOrder;
   $monto = $result->amount;
   $trs_nro_final_tarjeta = $result->cardDetail['card_number'];
   $trs_tipo_pago = $result->paymentTypeCode;
   $trs_nro_cuotas = $result->installmentsNumber;
   $trs_cod_autorizacion = $result->authorizationCode;
   $trs_fecha_contable = $result->accountingDate;
  // print_r(compact('estado','trs_respuesta'));
  // die();

  if( $trs_respuesta === 0 && $estado == 'AUTHORIZED' )
  {

       $sql = "update sitio_compras set estado = 1, token = '".$token."' where id = ".$trs_orden_compra;
       $db->ejecutar($sql);
       $sql = 'Update sitio_pagos set codigo = "'.$trs_id_transaccion.'",
                 numeroTarjeta  = "'. $trs_nro_final_tarjeta.'",
                 tipoPago = "'.     $trs_tipo_pago.'",
                 codAutorizacion = "'.  $trs_cod_autorizacion.'",
                 cuotas = "'.    $trs_nro_cuotas.'",
                 fechaContable = "'.    $trs_fecha_contable.'",
                 estado = "'. $trs_tipo_pago.'",
               respuesta = "'. $trs_respuesta.'",
                 json ="'.$json_result.'"
                 where numeroOrden = '.$trs_orden_compra;

        $db->ejecutar($sql);  

        ?>
           <form action="<?php echo $url_exito;?>" id="myForm" name="myForm" method="post">
    
    <input type="hidden" name="token_ws" value="<?php echo ($token); ?>">

</form>
<script type="text/javascript">
    document.getElementById('myForm').submit();
</script>
<?
die();  
  }else{
    if($trs_respuesta > 0)
        $trs_respuesta = -9;
    $sql = "update sitio_compras set estado =".$trs_respuesta.", token = '".$token."' where id = ".$trs_orden_compra;
    $db->ejecutar($sql);
    $sql = 'Update sitio_pagos set codigo = "'.$trs_id_transaccion.'",
                 numeroTarjeta  = "'. $trs_nro_final_tarjeta.'",
                 tipoPago = "'.     $trs_tipo_pago.'",
                 codAutorizacion = "'.  $trs_cod_autorizacion.'",
                 cuotas = "'.    $trs_nro_cuotas.'",
                 fechaContable = "'.    $trs_fecha_contable.'",
                 estado = "'. $trs_tipo_pago.'",
                 respuesta = "'. $trs_respuesta.'",
                 json ="'.$json_result.'"
                 where numeroOrden = '.$trs_orden_compra;
    $db->ejecutar($sql);
    header('Location: http://54.234.213.249/productos/fallo');

    
      
    }

    ?>
     <form action="<?php echo $url;?>" id="myForm" name="myForm" method="post">
    
    <input type="hidden" name="token_ws" value="<?php echo ($token); ?>">

</form>
<script type="text/javascript">
    document.getElementById('myForm').submit();
</script>






