<div class="col02">
	<h1 class="titulo"><? __('Banners');?></h1>
	<style type="text/css" media="all">
	.inicio {
		float: left;
		width: 100%;
	}
	.inicio > .carrusel {
		float:left;
		width:678px;
		height:288px;
	}
	.inicio > .izquierda {
		float: left;
		width: 440px;
		height:305px;
		padding: 10px 5px 0 0;
	}
	.inicio > .izquierda > a {
		position: absolute;
		width:170px;
		text-align: center;
		background-color:#ccc;
		padding:10px;
		border:2px solid #999;
		border-radius: 5px;
		color:#0080c0;
		z-index:999;
		text-decoration: none;
		opacity:.6;
		margin-left:120px;
		margin-top:120px;
	}
	.inicio > .izquierda > a:hover {
		text-decoration: underline;
		opacity:1;
	}
	.inicio > .derecha {
		float: left;
		width: 226px;
		height:305px;
		padding: 10px 0 0 5px;
	}
	.inicio > .derecha > a {
		position: absolute;
		width:170px;
		text-align: center;
		background-color:#ccc;
		padding:10px;
		border:2px solid #999;
		border-radius: 5px;
		color:#0080c0;
		z-index:999;
		text-decoration: none;
		opacity:.6;
		margin-left:15px;
		margin-top:120px;
	}
	.inicio > .derecha > a:hover {
		text-decoration: underline;
		opacity:1;
	}
	.inicio > .carrusel > a {
		position: absolute;
		width:170px;
		text-align: center;
		background-color:#ccc;
		padding:10px;
		border:2px solid #999;
		border-radius: 5px;
		color:#0080c0;
		z-index:999;
		text-decoration: none;
		opacity:.6;
		margin-left:-450px;
		margin-top:120px;
	}
	.inicio > .carrusel > a:hover {
		text-decoration: underline;
		opacity:1;
	}
	</style>
	<div class="inicio">
		<div class="carrusel">
			<a href="<?= $this->Html->url(array('action' => 'carrusel')); ?>" style="">para administrar pinche aquí !</a>
			<?= $this->Html->script('unslider/unslider'); ?>
			<style>
			.banner {
				float:left;
				overflow: auto;
				width: 100%;
			}
			.banner > ul {
				overflow: hidden;
			}
			.banner > ul > li {
				list-style: none;
				float: left;
				width:675px;
				height: 288px;
			}
			.banner > .izquierda {
				position: relative;
				display:inline-block;
				width:32px;
				height:32px;
				background-color: transparent;
				top:-170px;
				background-image: url('<?= $this->Html->url('/js/unslider/arrows-32-32-3.png'); ?>');
				background-position: 0 0;
				opacity: .7;
			}
			.banner > .izquierda:hover {
				opacity: 1;
			}
			.banner > .derecha {
				position: relative;
				display:inline-block;
				width:32px;
				height:32px;
				background-color: transparent;
				top:-170px;
				left: 610px;
				background-image: url('<?= $this->Html->url('/js/unslider/arrows-32-32-3.png'); ?>');
				background-position: -32px 0;
				opacity: .7;
			}
			.banner > .derecha:hover {
				opacity: 1;
			}
			.dots {
				position: relative;
				float: right;
				width:auto;
				margin-top: -20px;
				right: 0;
			}
			.dots > li {
				background-image: url('<?= $this->Html->url('/js/unslider/bullet-16-16-1.png'); ?>');
				/*background-repeat: no-repeat;*/
				background-position: 0 0;
				text-indent: -999em;
				margin: 0px 4px;
				width: 16px;
				display: inline;
				height: 16px;
				opacity: .7;
				cursor: pointer;
				border-radius: 100%;
			}
			.dots > li.active {
				background-position: 0 16px;
			}
			</style>
			<div class="banner">
				<ul>
					<? foreach ( $banners as $banner ) : ?>
					<li>
						<img src="<?= $this->Html->url('/img/'.$banner['Banner']['imagen']['grande']); ?>" width="100%" />
					</li>
					<? endforeach; ?>
				</ul>
				<a href="#" class="izquierda"></a>
				<a href="#" class="derecha"></a>
			</div>
			<script>
			var unslider = $('.banner').unslider({
				speed: 700,               //  The speed to animate each slide (in milliseconds)
				delay: 4000,              //  The delay between slide animations (in milliseconds)
				//complete: function() {},  //  A function that gets called after every slide animation
				keys: true,               //  Enable keyboard (left, right) arrow shortcuts
				dots: false,               //  Display dot navigation
				fluid: true              //  Support responsive design. May break non-responsive designs
			});
			$('.banner > .izquierda').click(function(e) {
				e.preventDefault();
				unslider.data('unslider').prev();
			});
			$('.banner > .derecha').click(function(e) {
				e.preventDefault();
				unslider.data('unslider').next();
			});
			</script>
		</div>
		<div class="izquierda">
			<a href="<?= $this->Html->url(array('action' => 'izquierda')); ?>" style="">para administrar pinche aquí !</a>
			<? if (isset($izquierda) && $izquierda) : ?>
			<img src="<?= $this->Html->url('/img/'.$izquierda['Banner']['imagen']['path']); ?>" width="100%" />
			<? endif; ?>
		</div>
		<div class="derecha">
			<a href="<?= $this->Html->url(array('action' => 'derecha')); ?>" style="">para administrar pinche aquí !</a>
			<? if (isset($derecha) && $derecha) : ?>
			<img src="<?= $this->Html->url('/img/'.$derecha['Banner']['imagen']['path']); ?>" width="100%" height="100%" />
			<? endif; ?>
		</div>
	</div>
</div>