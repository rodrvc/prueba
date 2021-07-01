<?php
//obtiene el nombre del archivo a descargar pasado por 'url'
$file = $archivo;
//seencuentra en el directorio 'export/' en el servidor
$url = $ruta;
 
header ("Content-Disposition: attachment; filename=".$file.";" );
header('content-type application/pdf');
readfile($url);
exit; 
?>