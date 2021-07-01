<?= $this->Html->script('jquery.elevatezoom'); ?>
<style type="text/css" media="all">
.zoomWindow {
	z-index: 999 !important;
}
</style>
<!--NORMAL-->
<div class="col-md-12 hidden-xs hidden-sm">
	<!--IMAGEN PRINCIPAL-->
	<div class="col-md-10 col-md-offset-2">
		<div class="img-lg" data-tilla_id="<?= $producto['Producto']['id']; ?>">
			<a href="<?= $this->Shapeups->imagen($producto['Producto']['foto']['path']); ?>" rel="galeriaZapatilla" class="fancybox galeria">
				<img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['full']); ?>" id="imagenZoom" class="img-responsive" width="100%" data-zoom-image="<?= $this->Shapeups->imagen($producto['Producto']['foto']['path']); ?>">
			</a>
		</div>
	</div>
<script type="application/x-javascript">
$("#imagenZoom").elevateZoom({
	zoomWindowPosition: 2,
	zoomWindowWidth: 445,
	zoomWindowHeight: 450,
	borderSize: 2,
	showLens: true,
	borderColour: "#ccc",
	//zoomWindowOffetx: 0,
	//zoomWindowOffety: <?= ($producto['Producto']['oferta']) ? '-40':'-20'; ?>,
	gallery : "gallery_SK", galleryActiveClass: "active"
});
</script>
	<!-- -- -->
	<!--GALERIA-->
	<div id="galeriaProducto" class="col-md-10 col-md-offset-2">
		<hr class="separador-hr">
		<div class="col-md-2" style="padding: 0 5px;">
			<a title="#" class="thumbnail active" data-img="<?= $this->Shapeups->imagen($producto['Producto']['foto']['full']); ?>" data-zoom="<?= $this->Shapeups->imagen($producto['Producto']['foto']['path']); ?>">
				<img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['mini']); ?>" width="100%" class="test" />
			</a>
		</div>
		<? foreach ($producto['Galeria'] as $key => $galeria) : ?>
			<div class="col-md-2" style="padding: 0 5px;">
				<a title="<?= $producto['Producto']['nombre']; ?>" href="#" data-img="<?= $this->Shapeups->imagen('Galeria/'.$galeria['id'].'/full_'.$galeria['imagen']); ?>" data-zoom="<?= $this->Shapeups->imagen('Galeria/'.$galeria['id'].'/'.$galeria['imagen']); ?>" class="thumbnail">
					<img src="<?= $this->Shapeups->imagen('Galeria/'.$galeria['id'].'/mini_'.$galeria['imagen']); ?>" width="100%" class="test" />
				</a>
			</div>
		<? endforeach; ?>
	</div>
	<!-- -- -->
	<!--REDES SOCIALES-->
	<div class="btn-sociales hidden-xs">
		<a href="#" id="compartir-fb" rel="compartirProductoFB" data-nombre="<?= $producto['Producto']['nombre']; ?>" data-imagen="<?= $compartir['imagen']; ?>" data-url="<?= $compartir['url']; ?>">
			<img src="<?= $this->Html->url('/img/bootstraps/icon-fb.png'); ?>">
		</a>
		<a href="#" id="compartir-tw" rel="compartirProductoTW" data-nombre="<?= $producto['Producto']['nombre']; ?>" data-url="<?= $compartir['url']; ?>">
			<img src="<?= $this->Html->url('/img/bootstraps/icon-tw.png'); ?>">
		</a>
	</div>
	<!-- -- -->
</div>
<!-- MOBILE -->
<div class="col-md-12 hidden-md hidden-lg">
	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
		<!-- Wrapper for slides -->
		<div class="carousel-inner" role="listbox">
			<div class="item active">
				<img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['path']); ?>" width="100%">
			</div>
			<? foreach ($producto['Galeria'] as $key => $galeria) : ?>
			<div class="item">
				<img src="<?= $this->Shapeups->imagen('Galeria/'.$galeria['id'].'/full_'.$galeria['imagen']); ?>" width="100%" />
			</div>
			<? endforeach; ?>
		</div>

		<!-- Controls -->
		<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
</div>
