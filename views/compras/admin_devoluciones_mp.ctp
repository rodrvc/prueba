<div class="col02">
    <?= $this->element('admin_buscar_devoluciones'); ?>
    <h1 class="titulo"><? __('Productos en Devolucion Mercado Pago');?></h1>
    <table cellpadding="0" cellspacing="0" class="tabla">
        <!--<tr>
            <th>Dev #</th>
            <th>Compra #</th>
            <th>Codigo devolución</th>
            <th>Tipo</th>
            <th>Producto</th>
            <th>Fecha Dev.</th>
            <th class="actions"><? __('Acciones');?></th>
        </tr> -->
        <tr>

            <th><?= $this->Paginator->sort('Nº Compra', 'id'); ?></th>
            <th><?= $this->Paginator->sort('Codigo devolución', 'codigo'); ?></th>
            <th><?= $this->Paginator->sort('tipo', 'tipo_nombre'); ?></th>
            <th><?= $this->Paginator->sort('codigo', 'codigo'); ?></th>
            <th><?= $this->Paginator->sort('fecha', 'created'); ?></th>
            <th class="actions" style="width: 170px;"><? __('Acciones');?></th>
        </tr>

        <? foreach ( $compras as $devolucion ) : ?>
            <tr>
                <td><?= $devolucion['Compra']['id']; ?>&nbsp;</td>
                <!--<td><?= $devolucion['Usuario']['nombre'].' '.$devolucion['Usuario']['apellido_paterno']; ?>&nbsp;</td> -->
                <td><?= $devolucion['Devolucion']['codigo']; ?>&nbsp;</td>
                <td><?= $devolucion['Devolucion']['tipo_nombre']; ?>&nbsp;</td>
                <td><?= $devolucion['Devolucion']['producto']; ?>&nbsp;</td>
                <!--<td><?= $devolucion['Devolucion']['created']; ?>&nbsp;</td> -->
                <td><?= date('d-m-y', strtotime($devolucion['Devolucion']['created'])); ?>&nbsp;</td>
                <td><?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'devolucion', $devolucion['Devolucion']['codigo']), array('escape' => false)); ?>
                </td>

            </tr>
        <? endforeach; ?>

    </table>
    <div class="paginador">
        <?= $this->Paginator->numbers(array('separator' => false)); ?>
    </div>
</div>