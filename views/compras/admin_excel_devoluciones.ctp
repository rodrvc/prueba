<div class="col02">
    <?= $this->Form->create('Compra', array('action' => 'estado_devoluciones', 'type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
    <h1 class="titulo"><? __('Generar Excel Devoluciones'); ?></h1>
    <ul class="edit">
        <li><span class="texto">Desde: </span><?= $this->Form->input('consulta_fecha1', array('label' => false, 'type' => 'text', 'id' => 'datepicker', 'autocomplete' => "off")); ?></li>
        <li><span class="texto">Hasta: </span><?= $this->Form->input('consulta_fecha2', array('label' => false, 'type' => 'text', 'id' => 'datepicker2', 'autocomplete' => "off")); ?></li>
        <?
        $options = array(
            'type' => 'select',
            'options' => array(
                1 => 'Debito',
                2 => 'Crédito',
                3 => 'Crédito mercado libre'
            ),
            'class'=>'DevolucionEstado clase-input',
            'autocomplete' => 'off',
            'label' => false
        );
        ?>


    </ul>
    <div class="botones">
        <a href="#" class="submit"><span class="previsualizar">Generar Documento</span></a>
    </div>
    <?= $this->Form->end(); ?>
</div>
