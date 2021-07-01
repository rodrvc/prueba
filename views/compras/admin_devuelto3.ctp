<div class="col02">
    <?= $this->element('admin_buscar_devoluciones'); ?>
    <h1 class="titulo"><? __('Productos en Cerradas');?></h1>
    <table cellpadding="0" cellspacing="0" class="tabla">

        <tr>
            <th><?= $this->Paginator->sort('dev#'); ?></th>
            <th><?= $this->Paginator->sort('Estado', 'estado'); ?></th>
            <th><?= $this->Paginator->sort('Codigo devolución', 'codigo'); ?></th>
            <th><?= $this->Paginator->sort('Tipo', 'tipo'); ?></th>
            <th><?= $this->Paginator->sort('Producto', 'producto'); ?></th>
            <th><?= $this->Paginator->sort('Fecha Dev.', 'Fecha Dev.'); ?></th>
            <th class="actions"><? __('Acciones');?></th>
        </tr>
        <? foreach ( $devoluciones as $devolucion ) : ?>

            <?
                if($devolucion['Devolucion']['estado'] != 0)
                    $estado = 'Cerrada';
            ?>
            <tr>
                <td><?= $devolucion['Devolucion']['id']; ?>&nbsp;</td>
                <td><?= $estado ?>&nbsp;</td>
                <td><?= $devolucion['Devolucion']['codigo']; ?>&nbsp;</td>
                <td><?= $devolucion['Devolucion']['tipo_nombre']; ?>&nbsp;</td>
                <td><?= $devolucion['Devolucion']['producto']; ?>&nbsp;</td>
                <!--<td><?= $devolucion['Devolucion']['talla']; ?>&nbsp;</td> -->
                <td><?= date('d-m-y', strtotime($devolucion['Devolucion']['created'])); ?>&nbsp;</td>
                <td><?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'ver_cerrado', $devolucion['Devolucion']['codigo']), array('escape' => false)); ?>
                </td>

            </tr>
        <? endforeach; ?>

    </table>
</div>