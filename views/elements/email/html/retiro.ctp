<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;" bgcolor="#ffffff">
    <tr>
        <td height="120" align="left"  bgcolor="#ffffff" style="text-align: center!important; font-family: Lucida Grande, Lucida Sans Unicode, Lucida Sans, DejaVu Sans, Verdana,' sans-serif';">
        Hola <?php echo $compra['Usuario']['nombre'];?><br><br>

¡Tu pedido ya está listo para ser retirado!<br><br>
Ya puedes retirar tu compra en  <?php echo $compra['Retiro']['nombre'];?>, sólo debes presentar tu carnet de identidad y mail de confirmación. En caso de que hayas designado un tercero, debe presentar una copia de este correo junto con su carnet de identidad. <br><br>

    
Recuerda que tienes 7 días corridos para retirar tu compra, si queda fuera de plazo tu pedido será anulado y se procederá con el reembolso correspondiente. En este caso tu dinero será devuelto en un plazo máximo de 15 días a través del mismo medio utilizado. <br><br>
Número de orden: <?php echo $compra['Compra']['id'];?><br>
Fecha de pedido: <?php echo $compra['Compra']['created'];?> <br>
Horario de entrega: <?php echo $compra['Retiro']['horario'];?><br>
Dirección de tienda: <?php echo $compra['Direccion']['calle']; ?><br>
Nombre: <?php echo $compra['Despacho']['entrega']; ?><br>
Rut: <?php echo $compra['Despacho']['rut']; ?><br><br>
Recuerda que al momento de ir a la tienda no hay filas especiales para retiro, por lo que te recomendamos ir con tiempo y esperar tu turno.
     
        </td>
    </tr>

     
