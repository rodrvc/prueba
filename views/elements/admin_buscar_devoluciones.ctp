<style>
    .desplegar-tools {
        background-image: url(<?= $this->Html->url('/img/iconos/btn-tools.png'); ?>);
        float: right;
        width: 30px;
        height: 30px;
        margin-bottom: 3px;
        margin-top: 0;
        background-position: left top;
    }
    .desplegar-tools:hover {
        background-position: left -30px;
    }
    .tools-panel {
        float:left;
        width: 640px;
        padding: 10px 19px 10px 19px;
        border: 1px solid #818f9e;
        border-top: hidden;
    <?= ($this->params['controller'] == 'compras' && $this->params['action'] == 'admin_buscar') ? '' : 'display: inline;' ; ?>
        background-color: #ececec;
        margin-bottom: 20px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    .linea-abajo {
        border-bottom: 1px solid #000;
    }
    .elemento-panel {
        float: left;
        width: 100%;
        border-bottom: 1px solid #818f9e;
    }
    .elemento-panel .bloque {
        float: left;
        width: 300px;
        padding: 10px;
    }
    .elemento-panel .bloque.divisor {
        border-left: 1px solid #818f9e;
        width: 299px;
    }
    .elemento-panel .label {
        float: left;
        color: #818f9e;
        padding: 5px 10px;
    }
    .elemento-panel .input {
        float: left;
        padding: 5px 10px;
    }
    .elemento-panel .btn {
        float: right;
        padding: 5px 10px;
        background-color: #067eff;
        color: #FFF;
        text-decoration: none;
        border-radius: 3px;
    }
    .elemento-panel .btn:hover {
        opacity: .7;
    }
    .elemento-panel .mega-bloque {
        float: left;
        width: 100%;
        padding: 10px 0;
    }
    .elemento-panel .mega-bloque .input {
        padding: 5px;
        width: 170px;
        margin-right: 10px;

    }
</style>
<a href="#" class="desplegar-tools"></a>
<div class="tools-panel">
    <div class="elemento-panel">
        <div class="bloque">
            <? if ($this->params['controller'] == 'compras' && in_array($this->params['action'], array('admin_index','admin_pagadas', 'admin_buscar'))) : ?>
                <span class="label">Compras por pagina</span>
                <?
                $valor = 20;
                if ($this->Session->check('ComprasPorPagina'))
                {
                    $valor = $this->Session->read('ComprasPorPagina');
                    if (! $valor || ! is_numeric($valor))
                        $valor = 20;
                }
                $options = array(
                    'label'		=> false,
                    'div' 			=> false,
                    'type'			=> 'select',
                    'class' 		=> 'input',
                    'value' 		=> $valor,
                    'options'		=> array(
                        10 => 10,
                        20 => 20,
                        50 => 50,
                        100 => 100
                    )
                );
                echo $this->Form->input('ComprasPorPagina.cantidad_por_pagina',$options);
                ?>
            <? endif; ?>
        </div>

    </div>
    <div class="elemento-panel">
        <?= $this->Form->create('Compra', array('action' => 'buscar_devoluciones', 'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class' => 'input',
            'autocomplete' => 'off'
        ))); ?>
        <div class="mega-bloque">
            <?= $this->Form->input('Buscar.search',array('placeholder' => 'Buscar devolucion')); ?>
             <a href="#" class="btn" id="buscar" rel="buscarCompra">buscar</a>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>



<script>
    $(document).ready(function() {
                console.log('hola');

         $('a[rel="buscarCompra"]').live('click', function() {
            var formulario = $(this).closest('form');
            formulario.submit();
        
        });
    });
</script>

