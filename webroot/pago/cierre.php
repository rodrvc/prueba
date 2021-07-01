<?
set_time_limit(120); 
   include("mysql.php");
   $db = new MySQL();
   require_once( '../../vendors/webpay/libwebpay/webpay.php' );
   require_once( '../../vendors/certificados/cert-normal.php' );
   if ( !isset($_POST["token_ws"]))
            die('error');
    else
      $token = $_POST["token_ws"];
    $configuration = new Configuration();
    $configuration->setEnvironment($certificate['environment']);
    $configuration->setCommerceCode($certificate['commerce_code']);
    $configuration->setPrivateKey($certificate['private_key']);
    $configuration->setPublicCert($certificate['public_cert']);
    $configuration->setWebpayCert($certificate['webpay_cert']);

/** Creacion Objeto Webpay */
    $webpay = new Webpay($configuration);
    $result = $webpay->getNormalTransaction()->getTransactionResult($token);
    $json_result = str_replace('"','',json_encode($result));

   /**** inicio de pagina de cierre xt_compra.php***/
 
   $trs_respuesta = $result->detailOutput->responseCode ;
   $trs_orden_compra = $trs_id_transaccion =$result->buyOrder;
   $monto = $result->detailOutput->amount;
   $trs_nro_final_tarjeta = $result->cardDetail->cardNumber;
   $trs_tipo_pago = $result->detailOutput->paymentTypeCode;
   $trs_nro_cuotas = $result->detailOutput->sharesNumber;
   $trs_fecha_expiracion =  $result->cardDetail->cardExpirationDate;
   $trs_cod_autorizacion = $result->detailOutput->authorizationCode;
   $trs_fecha_contable = $result->accountingDate;
   $url = $result->urlRedirection;

  if( $trs_respuesta !== 0 )
  {
      if($trs_respuesta > 0)
        $trs_respuesta = -9;
       $sql = "update sitio_compras set estado =".$trs_respuesta.", token = '".$token."' where id = ".$trs_orden_compra;
       $db->ejecutar($sql);
       $sql = 'Update sitio_pagos set codigo = "'.$trs_id_transaccion.'",
                 numeroTarjeta  = "'. $trs_nro_final_tarjeta.'",
                 tipoPago = "'.     $trs_tipo_pago.'",
                 codAutorizacion = "'.  $trs_cod_autorizacion.'",
                 cuotas = "'.    $trs_nro_cuotas.'",
                 expiracion = "'.   $trs_fecha_expiracion.'",
                 fechaContable = "'.    $trs_fecha_contable.'",
                 estado = "'. $trs_tipo_pago.'",
                 respuesta = "'. $trs_respuesta.'",
                 json ="'.$json_result.'"
                 where numeroOrden = '.$trs_orden_compra;
        $db->ejecutar($sql);
        header('Location: http://www.skechers.cl/productos/fallo');
  }else{
       $sql = "update sitio_compras set estado = 1, token = '".$token."' where id = ".$trs_orden_compra;
       $db->ejecutar($sql);
       $sql = 'Update sitio_pagos set codigo = "'.$trs_id_transaccion.'",
                 numeroTarjeta  = "'. $trs_nro_final_tarjeta.'",
                 tipoPago = "'.     $trs_tipo_pago.'",
                 codAutorizacion = "'.  $trs_cod_autorizacion.'",
                 cuotas = "'.    $trs_nro_cuotas.'",
                 expiracion = "'.   $trs_fecha_expiracion.'",
                 fechaContable = "'.    $trs_fecha_contable.'",
                 estado = "'. $trs_tipo_pago.'",
               respuesta = "'. $trs_respuesta.'",
                 json ="'.$json_result.'"
                 where numeroOrden = '.$trs_orden_compra;

        $db->ejecutar($sql);
    
      
    }

    ?>
     <form action="<?php echo $url;?>" id="myForm" name="myForm" method="post">
    
    <input type="hidden" name="token_ws" value="<?php echo ($token); ?>">

</form>
<script type="text/javascript">
    document.getElementById('myForm').submit();
</script>






