<div class="footer">
	<div class="mapsite">
		<div class="left">
			<div class="acerca-ske">
				<h2>Acerca de Skechers</h2>
				<a href="#" title="">Información corporativa</a>
				<?= $this->Html->link('Tiendas', array('controller' => 'tiendas', 'action' => 'index')); ?>
				<?= $this->Html->link('Blog', 'http://www.skechers-chile.cl/blog/', array('style' => 'display: none;')); ?>
				<span class="bandera-ch">&nbsp;</span><h3>Skechers Worldwide<br />Skechers USA.</h3>
			</div>
			<div class="acerca-ske">
				<h2>Tienda Online</h2>
				<?= $this->Html->link('Mi cuenta', array('controller' => 'usuarios', 'action' => 'perfil_datos')); ?>
				<?= $this->Html->link('Políticas de Despacho', 'http://store.skechers-chile.cl/politicas_store_skechers.pdf', array('target' => '_blank')); ?>
				<?= $this->Html->link('Estado de mi orden', array('controller' => 'usuarios', 'action' => 'historial')); ?>
				<?= $this->Html->link('Inscríbete', array('controller' => 'usuarios', 'action' => 'add')); ?>
			</div>
			<div class="acerca-ske">
				<h2>Contacto</h2>
				<a href="mailto: ventas@skechers.com" title=""><span class="icon-mess">&nbsp;</span>ventas@skechers.com</a>
				<div class="datito"><span class="icon-mess fono">&nbsp;</span><h4>+562 2342 93 00</h4></div>
				<div class="datito"><span class="icon-mess depto">&nbsp;</span> <h4>+562 2342 93 00</h4></div>
			</div>
			<a href="<?= $this->Html->url('http://www.andain.cl'); ?>"class="leyton" target="_blank">
				<span class="logo-leyton">&nbsp;</span>
			</a>
		</div>
		<div class="right">
			<h4>Conoce lo último acerca de SKECHERS<span class="doble-fecla">&nbsp;</span></h4>
			<ul class="sitios-social">
				<li>
					<a href="<?= $this->Html->url('http://www.facebook.com/skechersperformancechile'); ?>" class="icono" target="_blank"><span>Performance Facebook &raquo;</span></a>
				</li>
				<li>
					<a href="<?= $this->Html->url('http://www.facebook.com/skechers.chile'); ?>" class="icono fb" target="_blank"><span>Facebook &raquo;</span></a>
				</li>
<!--				<li>
					<a href="<?= $this->Html->url('http://www.facebook.com/skechersfitness'); ?>" class="icono group" target="_blank"><span>Fitness Group &raquo;</span></a>
				</li>-->
				<li>
					<a href="<?= $this->Html->url('http://www.twitter.com/SKECHERSCHILE'); ?>" class="icono tw" target="_blank"><span>Twitter &raquo;</span></a>
				</li>
				<!--<li>
					<a href="<?= $this->Html->url('http://www.skechers-chile.cl/blog'); ?>" class="icono blog" target="_blank"><span>Blog &raquo;</span></a>
				</li>-->
				<li>
					<a href="<?= $this->Html->url('http://www.vimeo.com/skecherschile'); ?>" class="icono videos" target="_blank"><span>Videos &raquo;</span></a>
				</li>
				<li>
					<a href="<?= $this->Html->url('mailto:rrhh@skechers.cl'); ?>" class="icono rss" target="_blank"><span>Trabajos Skechers &raquo;</span></a>
				</li>
	<!--			<li>
					<a href="<?= $this->Html->url('http://www.facebook.com/skechers.chile'); ?>" class="icono urban" target="_blank"><span>BeTheNextOne &raquo;</span></a>
				</li>-->
			</ul>
		</div>
	</div>
</div>
