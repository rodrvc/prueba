
<?
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=Ventas.xls');
header('Content-Transfer-Encoding: binary');
?>
<table>
    <?if($tipo ==1):?>
        <tr>
            <th>nombre cliente</th>
            <th>run cliente</th>
            <th>banco</th>
            <th>N cta</th>
            <th>Monto a devolver</th>
            <th>N compra</th>
            <th>N RA</th>
            <th>N Boleta</th>
        </tr>
        <? foreach( $compras as $compra ) : ?>
            <tr>
                <td><?= $compra['Devoluciones']['nombre_titular']; ?></td>
                <td><?= $compra['Devoluciones']['run_titular']; ?></td>
                <td><?= $compra['Devoluciones']['banco_titular']; ?></td>
                <td><?= $compra['Devoluciones']['ncuenta_titular']; ?></td>
                <td><?= $compra['ProductosCompra']['valor']; ?></td>
                <td><?= $compra['Compra']['id']; ?></td>
                <td><?= $compra['Devoluciones']['codigo']; ?></td>
                <td><?= $compra['Compra']['boleta']; ?></td>
            </tr>
        <? endforeach; ?>

    <?else: ?>

        <tr>
            <th>Monto a devolver</th>
            <th>N compra</th>
            <th>N RA <th>
            <th>Boleta</th>
        </tr>
        <? foreach( $compras as $compra ) : ?>
            <tr>
                <td><?= $compra['ProductosCompra']['valor']; ?></td>
                <td><?= $compra['Compra']['id']; ?></td>
                <td><?= $compra['Devoluciones']['codigo']; ?></td>
                <td><?= $compra['Compra']['boleta']; ?></td>
            </tr>
        <? endforeach; ?>

    <?endif;?>

</table>
