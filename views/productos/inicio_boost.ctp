<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
	<!-- Indicators -->
	<ol class="carousel-indicators">
		<? foreach($banners as $index => $banner): ?>
		<? 
			$class = "";
			if($banner === reset($banners))
				$class = 'active';
		?>
		<li data-target="#carousel-example-generic" data-slide-to="<?= $index; ?>" class="<?= $class; ?>"></li>
		<? endforeach; ?>
	</ol>

	<!-- Wrapper for slides -->
	<div class="carousel-inner" role="listbox">
		<? foreach($banners as $index => $banner): ?>
		<? 
			$class = "";
			if($banner === reset($banners))
				$class = 'active';
		?>
		<div class="item <?= $class; ?>">
			<? if($banner['Banner']['link']): ?>
			<a href="<?= $banner['Banner']['link']; ?>">
				<img src="<?= $this->webroot.'img/'.$banner['Banner']['imagen']['grande']; ?>">
			</a>
			<? else: ?>
			<img src="<?= $this->webroot.'img/'.$banner['Banner']['imagen']['grande']; ?>">
			<? endif; ?>
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
<div class="row" style="margin-top:20px">
	<div class="col-md-8">
		<a href="#">
			<img src="<?= $this->Html->url('/img/caluga-iz.jpg'); ?>" width="100%">
		</a>
	</div>
	<div class="col-md-4">
		<a href="#">
			<img src="<?= $this->Html->url('/img/caluga-de.jpg'); ?>" width="100%">
		</a>
	</div>
</div>
