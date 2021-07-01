<style>
    td.actions a {
        margin-right: 7px;
    }
    .col02 {
        background-color: #fff;
    }
    .boton-exportar {
        border-radius: 5px;
        height: 14px;
        padding-top: 5px;
        background-image: url("<?= $this->Html->url('/img/admin/bg-boton.png'); ?>");
        background-position: center center;
        background-repeat: repeat-x;
        display: block;
        float: left;
        font-size: 13px;
        font-weight: normal;
        margin-left: 10px;
        padding: 10px 15px;
        text-decoration: none;
        text-transform: capitalize;
        background-color: #000000;
        color: #FFFFFF;
    }
    .boton-exportar:hover {
        background-color: #222;
        color: #DDD;
    }
    .boton-exportar span {
        background-position: left center;
        background-repeat: no-repeat;
        padding-bottom: 5px;
        padding-left: 30px;
        padding-top: 5px;
        background-image: url("<?= $this->Html->url('/img/iconos/excel_16.png'); ?>");
        color: #fff;
    }
</style>
<div class="col02">
    <?= $this->element('admin_buscar_devoluciones'); ?>
    <h1 class="titulo"><? __('Buscador');?></h1>
    <table cellpadding="0" cellspacing="0" class="tabla">
        <tr>
            <th><?= $this->Paginator->sort('Estado', 'estado'); ?></th>
            <th><?= $this->Paginator->sort('Nº Compras', 'id'); ?></th>
            <th><?= $this->Paginator->sort('Devolucion', 'codigo'); ?></th>
            <th><?= $this->Paginator->sort('Nombre', 'usuario_id'); ?></th>
            <th><?= $this->Paginator->sort('Fecha', 'created'); ?></th>
            <th class="actions" style="width: 170px;"><? __('Acciones');?></th>
        </tr>
        <? foreach ( $compras as $compra ) : ?>
            <tr>

                    <?
                    if ($compra['Devolucion']['estado'] != 0)
                    {
                        $compra['Devolucion']['estado'] = 'Cerrado';
                    }
                    elseif ($compra['Devolucion']['estado'] == 0)
                    {
                        $compra['Devolucion']['estado'] = 'Abierto';
                    }
                    ?>

                <td><?= $compra['Devolucion']['estado']; ?>&nbsp;</td>
                <td><?= $compra['Compra']['id']; ?>&nbsp;</td>
                <td><?= $compra['Devolucion']['codigo']; ?>&nbsp;</td>
                <td><?= $compra['Usuario']['nombre'] . ' ' . $compra['Usuario']['apellido_paterno']; ?></td>
                <td><?= date('d-m-Y', strtotime($compra['Compra']['created'])); ?></td>
                <td class="actions acciones-compras-<?= $compra['Compra']['id']; ?>">
                <?php

                   if ($compra['Devolucion']['estado'] == 'Cerrado')
                    {
                        echo '<a href="'.$this->Html->url(array('action'=>'ver_cerrado',$compra['Devolucion']['codigo'])).'" title="Ver"><img src="'.$this->Html->url('/img/iconos/clipboard_16.png').'" /></a>'; 
                    }
                    elseif ($compra['Devolucion']['estado'] == 'Abierto')
                    {
                        echo '<a href="'.$this->Html->url(array('action'=>'devolucion',$compra['Devolucion']['codigo'])).'" title="Ver"><img src="'.$this->Html->url('/img/iconos/clipboard_16.png').'" /></a>';  
                    }
                    ?>
                 
                    
                </td>
            </tr>
        <? endforeach; ?>
    </table>
    <div class="paginador">
        <?= $this->Paginator->numbers(array('separator' => false)); ?>
    </div>
</div>