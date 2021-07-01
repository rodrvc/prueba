<style type="text/css" media="all">
html, body, #map-canvas  {
  margin: 0;
  padding: 0;
  height: 100%;
}
#map-canvas {
  width:500px;
  height:480px;
  margin-left: auto;
  margin-right: auto;
}
</style>
<div class="container">
	<h1 class="titulo-categoria">TIENDAS</h1>
	<div class="panel panel-skechers">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div role="tabpanel">
					  <!-- Nav tabs -->
						<ul class="nav nav-tabs nav-tabs-skechers" role="tablist">
							<? foreach ($zonas as $zona) : ?>
								<li role="presentation" class="<?= ($zona == reset($zonas)) ? 'active':''; ?>">
									<a href="#<?= $zona['Zona']['id']; ?>"  aria-controls="home" role="tab" data-toggle="tab"><?= $zona['Zona']['nombre']; ?></a>
								</li>
							<? endforeach; ?>
						</ul>
						<!-- Tab panes -->
						<div class="tab-content tab-content-skechers">
							<? foreach( $zonas as $zona ) : ?>
								<div role="tabpanel" class="tab-pane <?= ($zona == reset($zonas)) ? 'active':''; ?>" id="<?= $zona['Zona']['id']; ?>">
										<div class="well well-sm">
									<p class="text-center"><b>Debido la contingencia sanitaria el horario de las tiendas puede variar al informado</b></p>
							</div>
									<div class="row">
										<? foreach( $zona['Tienda'] as $tienda ) : ?>
											<div class="col-sm-6 col-md-3">
												<div class="thumbnail thumbnail-tiendas" style="min-height: 455px; height: 500px; overflow: hidden; padding-left: 10px; padding-right: 10px;">

													<? if ( isset($tienda['remodelacion']) && $tienda['remodelacion'] ) : ?>
														<div class="row" style="height: 0; margin-top: -20px; margin-bottom: 20px;">
															<img src="<?= $this->Shapeups->imagen('iconos/remodelacion.png'); ?>" style="position: relative; width: 100%;" />
														</div>
													<? else :?>
														<? if ( isset($tienda['outlet']) && $tienda['outlet'] ) : ?>
														<div class="row" style="height: 0; margin-top: -20px; margin-bottom: 20px;">
															<img src="<?= $this->Html->url('/img/iconos/outlet.png'); ?>" style="position: relative; width: 70%;"/>
														</div>
														<? endif; ?>
													<? endif; ?>

													<img src="<?= $this->Shapeups->imagen('Tienda/'.$tienda['id'].'/mini_'.$tienda['imagen']); ?>" class="img-responsive img-circle" />
													<div class="caption" style="padding-left: 0px; padding-right: 0px;">
														<h3 class="text-info" style="font-size: 20px;"><?= $tienda['nombre']; ?></h3>
														<p style="max-height: 200px; overflow: hidden; font-size: 85%;">
															<?= nl2br($tienda['direccion']); ?><br />
															<?= $tienda['Comuna']['nombre']; ?><br />
															<?= $tienda['Region']['nombre']; ?><br />
															Fono: <?= $tienda['telefono']; ?> <br>
															 <?= $tienda['horario']; ?> 
														</p>
														<?php if(in_array($tienda['codigo'], $retiros) && !(isset($tienda['remodelacion']) && $tienda['remodelacion'])):?>
														<p class="text-danger"><b>Disponible para retiro compra Online</b></p>
													<?php endif; ?>
													</div>
												</div>
												<div class="col-xs-12" style="margin-top: -65px;">
													<a href="#modalMaps" title="ver mapa" data-toggle="modal" data-target="#modalMaps" data-backdrop="static" class="btn btn-primary btn-block" rel="marcadorMapa" data-latitud="<?= $tienda['latitud']; ?>" data-longitud="<?= $tienda['longitud']; ?>" data-nombre="<?= $tienda['nombre']; ?>" data-direccion="<?= $tienda['direccion'].' - '.$tienda['Comuna']['nombre']; ?>">Ver Mapa</a>
												</div>
											</div>
										<? endforeach; ?>
									</div>
								</div>
							<? endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- MODAL -->
<div class="modal fade" id="modalMaps" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel" rel="titulo">Modal title</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-info" rel="info"></div>
				<div id="map-canvas"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>