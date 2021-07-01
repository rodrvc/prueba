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
                        if($data['Compra']['estado'] == 11){
                            $estado = 'Anulada por falta de stock';
                        }else{
                            $estado = 'Pendiente de revision';
                        }

                        ?>
                       Te informamos que tu compra N°<strong><?= $data['Compra']['id'] ?> </strong>,
                        cambio su estado a <strong><?= $estado?></strong>.

                <p>Te solicitamos contactarnos a la brebevedad para solucionar este inconveniente.</p>
            </span>
            <br>
            <br>
        </td>

    </tr>
    <!-- Hero Image, Flush : END -->

    <!-- 1 Column Text + Button : BEGIN -->





