 <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;" bgcolor="#ffffff">

        <!-- Hero Image, Flush : BEGIN -->
        <tr>
            <td height="120" align="center" bgcolor="#ffffff" style="text-align: center!important; font-family: Lucida Grande, Lucida Sans Unicode, Lucida Sans, DejaVu Sans, Verdana,' sans-serif';">
                <br>
                <span style="color: #003985;  font-size: 24px; font-weight: bold; text-transform: uppercase;font-family:'Century Gothic','Futura',san-serif;">Hola <?= $compra['Usuario']['nombre'];?>!</span>
                <br>
                <hr style="width: 50px; border: 1px solid #113d86">
                <br>
                <span style="color: #666666;  font-size: 16px; font-weight: 400;  height: 24px; font-family:'Century Gothic','Futura',san-serif;">
                        <?
                        if($data['Devolucion']['estado'] == 1){
                            $estado = 'aprobado';
                        }elseif ($data['Devolucion']['estado'] == 2){
                            $estado = 'rechazado';
                        }else{
                            $estado = 'revisado';
                        }

                        ?>
                       Queremos informarte que la solicitud de devolución de tu pedido <strong>N° <?= $data['Devolucion']['compra_id'] ?></strong> ha sido <strong><?= $estado?></strong>
                       <? if($data['Devolucion']['estado'] == 1):?>
                           y en los próximos 15 días hábiles se reembolsará su dinero según su medio de pago.
                           <br>
                           <span style="color: #666666;  font-size: 16px; font-weight: 400;  height: 24px; font-family:'Century Gothic','Futura',san-serif;">Esperamos verte pronto de vuelta en nuestra tienda y ante cualquier duda,
                           puedes contactarnos al correo:<a href="mailto:ventas@skechers.com">ventas@skechers.com</a>
                            </span>
                       <? endif; ?>
                       </span>
                <br>
                <br>
            </td>

        </tr>
        <!-- Hero Image, Flush : END -->

        <!-- 1 Column Text + Button : BEGIN -->
        <tr>
            <td bgcolor="#ffffff" style="padding: 20px;">
                <table width="85%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td height="90">
                            <hr style="width: 80%; border-bottom: 1px solid #cccccc">
                        </td>
                    </tr>
                </table>


