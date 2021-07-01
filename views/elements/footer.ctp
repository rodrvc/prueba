<style>
	.nfooter {
		padding-top: 20px;
		margin-top: 20px;
	}
	.nfooter h2, h4 {
		margin-bottom: 20px !important;
	}
	.nfooter .acerca-ske a {
		padding-top: 10px;
	}
	.nfooter, .noimagen {
		background-color: #e7e6e6 !important;
		background-image: none !important;
	}
	.lsocial a {
		margin-right: 10px !important;
		margin-bottom: 20px !important;
	}
</style>
<div class="footer nfooter">
	<div class="mapsite">
		<div class="left">
			<div class="acerca-ske">
				<h2>ACERCA DE SKECHERS</h2>
				<a href="#" title="">Información corporativa</a>
				<a href="<?= $this->Html->url(array('controller'=>'tiendas','action'=>'index')); ?>">Tiendas</a>
				<!--<a href="http://www.skechers-chile.cl/blog/">Blog</a>-->
				<a href="#"><span class="bandera-ch">&nbsp;</span><h3>Skechers Worldwide<br />Skechers USA.</h3></a>
			</div>
			<div class="acerca-ske">
				<h2>TIENDA ONLINE</h2>
				<a href="<?= (isset($authUser))? $this->Html->url(array('controller'=>'usuarios','action'=>'perfil_datos')) : $this->Html->url(array('controller'=>'usuarios','action'=>'login')); ?>">Mi cuenta</a>
				<a href="http://store.skechers-chile.cl/politicas_store_skechers.pdf" target="_blank">Políticas de Despacho</a>
				<a href="<?= (isset($authUser))? $this->Html->url(array('controller'=>'usuarios','action'=>'historial')) : $this->Html->url(array('controller'=>'usuarios','action'=>'login')); ?>">Estado de mi orden</a>
				<a href="<?= $this->Html->url(array('controller'=>'usuarios','action'=>'add')); ?>">Inscríbete</a>
			</div>
			<div class="acerca-ske">
				<h2>CONTACTO</h2>
				<a href="mailto: ventas@skechers.com" title=""><span class="icon-mess">&nbsp;</span>ventas@skechers.com</a>
				<a href="#">
					<span class="icon-mess fono">&nbsp;</span>+562 2342 93 00
				</a>
				<a href="#">
					<span class="icon-mess depto">&nbsp;</span> +562 2342 93 00
				</a>
			</div>
			<a href="<?= $this->Html->url('http://www.andain.cl'); ?>"class="leyton" target="_blank">
				<span class="logo-leyton">&nbsp;</span>
			</a>
		</div>
		<div class="right noimagen">
			<h4>CONOCE  LO ÚLTIMO ACERCA DE SKECHERS</h4>
			<ul class="sitios-social">
				<li class="lsocial">
					<a href="http://www.facebook.com/skechersperformancechile" class="icono" target="_blank"></a>
					<a href="http://www.facebook.com/skechers.chile" class="icono fb" target="_blank"></a>
					<a href="http://www.twitter.com/SKECHERSCHILE" class="icono tw" target="_blank"></a>
					<a href="http://www.vimeo.com/skecherschile" class="icono videos" target="_blank"></a>
					<a href="mailto:rrhh@skechers.cl" class="icono rss" target="_blank"></a>
				</li>
	<!--			<li>
					<a href="<?= $this->Html->url('http://www.facebook.com/skechers.chile'); ?>" class="icono urban" target="_blank"><span>BeTheNextOne &raquo;</span></a>
				</li>-->
			</ul>
		</div>
	</div>
</div>
