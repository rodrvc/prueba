<?
set_time_limit(120); 
   include("mysql.php");
   $db = new MySQL();
   $orden =$_GET["orden"];
   $access_token = 'TEST-2269204429642405-070621-898a8f47328e447fb7129d2128249f4e-604718846';
$url = "https://api.mercadopago.com/v1/payments/".$orden."?access_token=".$access_token;
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_SSL_VERIFYHOST => 0,
     CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
      ),
    ));
    $result  = curl_exec($curl);
    
  if( $result === false)
    {
        echo 'Curl error: ' . curl_error($curl);

    }
    else
    {
      $respuesta = json_decode($result ,true);
      echo '<pre>';
      print_r($respuesta);
      echo '</pre>';

      exit;
      $errores_stock = array();
      foreach ($stocks_procesados as $stock)
      {
        if($stock['success'] != 1){
          $errores_stock[]= array('sku' => $stock['code'],
                       'error' => $stock['error']
                       );
        }
      }
      return $errores_stock;

    }