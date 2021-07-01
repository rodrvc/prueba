<?
set_time_limit(120); 
   include("mysql.php");
   $db = new MySQL();
   $sql = "Select c.id, c.total, u.email, u.nombre, u.apellido_paterno  from sitio_compras c, sitio_usuarios u where c.usuario_id=u.id and c.id =".$_GET['orden'];
   $consulta = $db->consulta($sql);
   $respuesta = $db->fetch_array($consulta);
   //print_r($respuesta);
  
    require_once 'vendor/autoload.php';

MercadoPago\SDK::setAccessToken("APP_USR-13101959119558-081421-b5466db0755386f2bbe2fc41b462d93a-626169991");      // On Production
    //MercadoPago\SDK::setAccessToken("TEST-2269204429642405-070621-898a8f47328e447fb7129d2128249f4e-604718846"); // On Sandbox


    $preference = new MercadoPago\Preference();
  # Create an item object
  $item = new MercadoPago\Item();
  $item->title = "Productos";
  $item->quantity = 1;
  $item->currency_id = "CLP";
  $item->unit_price = $respuesta['total'];
  # Create a payer object
  $payer = new MercadoPago\Payer();
  $payer->email = $respuesta['email'];
  $payer->name = $respuesta['nombre'].' '. $respuesta['apellido_paterno'];
  $preference->back_urls = array(
    "success" => "http://www.skechers.cl/productos/cierre_mp",
    "failure" => "http://www.skechers.cl/productos/fallo",
    "pending" => "http://www.skechers.cl/productos/pendiente"
);
  $preference->auto_return = "approved";
  $preference->external_reference = $respuesta["id"];
 // $preference->notification_url = 'https://hookb.in/DrXQOm3oXgudNNEweKPK?source_news=webhooks';
  $preference->notification_url = 'http://54.173.4.142/productos/notificacion_mp?source_news=webhooks';

  $preference->payment_methods = array(
    "excluded_payment_methods" => array(
      array("id" => "khipu")
    ),
        "excluded_payment_types" => array( array( "id"=>"ticket"), array("id"=>"bank_transfer"), array("id"=>"atm")
    ),
  "installments" => 12
);
  # Setting preference properties
  $preference->items = array($item);
  $preference->payer = $payer;

  $preference->save();
  $sql = "update sitio_compras set token ='".$preference->id."' where id = ".$respuesta["id"];
  $db->ejecutar($sql);
  //exit;
  $url = $preference->init_point;

//$url = $preference->sandbox_init_point;
 // echo "PaymentId: " . $preference->id . "\n";


?>
     <form action="<?php echo $url;?>" id="myForm" name="myForm" method="post">
    
</form>
<script type="text/javascript">
    document.getElementById('myForm').submit();
</script>

