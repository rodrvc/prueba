
<?
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=Devoluciones.xls');
header('Content-Transfer-Encoding: binary');
?>
<table>
    <tr>
        <th>Compra</th>
        <th>Boleta</th>
        <th>Nombre</th>
        <th>Run</th>
        <th>Estado</th>
        <th>Codigo</th>
        <th>Producto</th>
        <th>Picks</th>
        <th>Valor</th>
    </tr>
    <? foreach( $compras as $compra ) : ?>

        <tr>
            <td><?= $compra['Compra']['id']; ?></td>
            <td><?= $compra['Compra']['boleta']; ?></td>
            <td><?= $compra['Devoluciones']['nombre_titular']; ?></td>
            <td><?= $compra['Devoluciones']['run_titular']; ?></td>
            <td><?= $compra['Devoluciones']['estado']; ?></td>
            <td><?= $compra['Devoluciones']['codigo']; ?></td>
            <td><?= $compra['Devoluciones']['producto']; ?></td>
            <td><?= $compra['Devoluciones']['fecha_picks']; ?></td>
            <td><?= $compra['ProductosCompra']['valor']; ?></td>

        </tr>
    <? endforeach; ?>
</table>
