<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;" bgcolor="#ffffff">
    <tr>
        <td height="120" align="center" bgcolor="#ffffff" style="text-align: center!important; font-family: Lucida Grande, Lucida Sans Unicode, Lucida Sans, DejaVu Sans, Verdana,' sans-serif';">
            Te informamos que tu pedido ha sido entregado hoy a la compañía de transportes para que realice el reparto en
            <span style="color: #222222;  font-size: 16px; font-weight: bold;  height: 24px; font-family:'Century Gothic','Futura',san-serif;">
                 <? if (isset($compra['Compra']['rural']) && $compra['Compra']['rural'] == 1 ) : ?>
                     <b><?= (isset($compra['Compra']['direccion_rural']) && $compra['Compra']['direccion_rural']) ? utf8_decode($compra['Compra']['direccion_rural']):''; ?></b>
                 <? else : ?>
                     <b>
                        <?
                        if (isset($compra['Despacho']['Direccion']['calle']))
                        {
                            echo utf8_decode($compra['Despacho']['Direccion']['calle']);
                            if (isset($compra['Despacho']['Direccion']['numero']) && $compra['Despacho']['Direccion']['numero'])
                                echo ' #' . $compra['Despacho']['Direccion']['numero'];
                            if (isset($compra['Despacho']['Direccion']['depto']) && $compra['Despacho']['Direccion']['depto'])
                                echo ' ' . $compra['Despacho']['Direccion']['depto'];
                            if (isset($compra['Despacho']['Direccion']['Comuna']['nombre']) && $compra['Despacho']['Direccion']['Comuna']['nombre'])
                                echo ', ' . $compra['Despacho']['Direccion']['Comuna']['nombre'];
                            if (isset($compra['Despacho']['Direccion']['Region']['nombre']) && $compra['Despacho']['Direccion']['Region']['nombre'])
                                echo ' - ' . $compra['Despacho']['Direccion']['Region']['nombre'];
                        }
                        ?>
                    </b>
                 <? endif; ?>.
            </span>
        </td>
    </tr>
    <? if ($compra["Compra"]["empresa_despacho_id"] == 20 ) :?>
        <tr >
            <td height="120" align="center" bgcolor="#ffffff" style="text-align: center!important; font-family: Lucida Grande, Lucida Sans Unicode, Lucida Sans, DejaVu Sans, Verdana,' sans-serif';">
                <br>
            <span style="color: #222222;  font-size: 16px; font-weight: bold;  height: 24px; font-family:'Century Gothic','Futura',san-serif;">
                El reparto se realizará dentro de los próximos 12 días hábiles.
                <br>
                Adjunto encontrarás copia de tu boleta electrónica.
            </span>
            </td>
        </tr>
    <? else :?>
        <tr >

            <td height="120" align="center" bgcolor="#ffffff" style="text-align: center!important; font-family: Lucida Grande, Lucida Sans Unicode, Lucida Sans, DejaVu Sans, Verdana,' sans-serif';">
                <br>
                <span style="color: #666666;  font-size: 16px; font-weight: bold;  height: 24px; font-family:'Century Gothic','Futura',san-serif;">
                Para hacer seguimiento de tu orden, puedes ingresar al siguiente Link:
            <?php
            $url = "https://www.skechers.cl/compras/estado_despacho/".bin2hex($compra["Compra"]["cod_despacho"].'/'.$compra["Usuario"]["email"]);
            ?>
                <a href="<?php echo $url;?>" style="font-weight:bold;">
                https://www.skechers.cl/despachos</a>
            </span>
                <br>
                <span style="color: #222222;  font-size: 16px; font-weight: bold;  height: 24px; font-family:'Century Gothic','Futura',san-serif;">
            Adjunto encontrarás copia de tu boleta electrónica.
            </span>
            </td>
        </tr>
    <? endif; ?>

