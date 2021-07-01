


  <div class="container">
    <div class="row">
      <div class="col-12 pt-2">
        <ul class="navega nuevo_item mb-2">
          <!--<li><a href="<?=$this->Html->url(array('controller' => 'pages', 'action' => 'display','faq'));?>"><i class="fa fa-question-circle fa-2" aria-hidden="true"></i>&nbsp;Preguntas Frecuentes</a></li>
          <li><a href="#"><i class="fa fa-question-circle fa-2" aria-hidden="true"></i>&nbsp;Preguntas Frecuentes</a></li>-->
          <li><a href="https://tecnobuy.s3.amazonaws.com/politicas.pdf" target="_blank"><i class="fa fa-file-text fa-6" aria-hidden="true"></i>&nbsp;Politicas de despacho</a></li>
          <li><a  href="<?=$this->Html->url(array('controller' => 'compras', 'action' => 'estado_despacho'));?>" "><i class="fa fa-truck" aria-hidden="true"></i>&nbsp;Estado de mi despacho</a></li>
          <li><a href="<?=$this->Html->url(array('controller' => 'compras', 'action' => 'detalle_boleta'));?>"><i class="fa fa-ticket" aria-hidden="true"></i>&nbsp;Mi Boleta</a>&nbsp</li>
          <li><a href="<?=$this->Html->url(array('controller' => 'tiendas', 'action' => 'index'));?>"><i class="fas fa-store"></i>&nbsp;Encuentra tu tienda</a>&nbsp</li>
        </ul>

      </div>
    </div>
  </div>
</div>
<!--fin menú auxiliar-->
<!--header-->
<div class="container-fluid" style="background: black;">
  <div class="container">
    <div class="row">
      <div class="col-md-3 pt-3">
        <a href="<?php echo $this->Html->url(array("controller" => "productos", "action" => "inicio")); ?>"><img src="<?= $this->Shapeups->imagen('logo_tecnnobuy.png'); ?>" width="300" style="margin-top: 11px"></a>
      </div>
      <div class="col-md-offset-2 col-md-4" id="usuario">
        
      </div>
       <div class="col-md-3" id="carro">
        
      </div>
    </div>
  </div>
</div>