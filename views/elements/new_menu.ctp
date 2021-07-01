<div class="container-fluid pb-0 mb-0 bg_tb">
<div class="container mb-0 pb-0">
      
      <div class="navbar navbar-default navbar-static-top bg_tb mb-0">
      <div class="container pb-0 mb-0 bg_tb">
        <div class="navbar-header bg_tb">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <p class="mt-3 hidden">Categorías</p>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
            <?php foreach ($menu_categorias as $key => $menu_padre) :?>
            <?php
                   $link = $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', $menu_padre['Categoria']['id'])); 
            ?>
            <li class="dropdown menu-large">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo strtoupper($menu_padre['Categoria']['nombre']);?></a>             
                <ul class="dropdown-menu megamenu row">
                    <li class="col-sm-3">
                        <ul>
                            <?php foreach ($menu_padre['ChildCategoria'] as  $menu) : ?>
                                <li><a href="<?php echo $this->Html->url(array('controller' => 'productos', 'action' => 'catalogo', $menu['slug']));?>"><?php echo $menu['nombre'];?></a></li>
                            <?php endforeach; ?>

                           
                        </ul>
                    </li>
              
                </ul>

            </li>

        <?php endforeach; ?>
                
          <!--*--->
                <li class="border">
                        
                </li>
            <!---*--->

        </ul>
        
        </div>
      </div>
    </div>

</div><!--fin container-->
</div><!--fin container fluid-->