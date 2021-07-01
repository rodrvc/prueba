<?= $this->element('menu'); ?>
<?= $this->element('detalle-buscador'); ?>
<div class="wrapper">		
	<div class="cont-catalogo">
		<div class="seleccion">
			<span class="ordenar"> Ordenar por: </span> 
			<label>
			<select class="listas" onchange="javascript:searchSortUrl(this.options[this.selectedIndex].value);" size="1" name="Ordenar">
				<option selected="" value="1">Relevancia</option>
				<option value="2">Precio de mayor a menor</option>
				<option value="3">Orden Alfabético</option>
				<option value="4">Más visitados</option>
			</select>
			</label>
			<div class="ver-resultado">Viendo <b>0</b> Resultados</div>
		</div>
		<div class="nofound">
			<? if( $mensaje = $this->Session->flash() ) : ?>
			<h4>¡Lo sentimos!
			<br />No encontramos resultados para <?= $mensaje; ?></h4>
			<? else : ?>
			<h4>¡Lo sentimos!
			<br />No encontramos resultados</h4>
			<? endif; ?>
			<p class="colcel"> Consejos para buscar por palabras :</p>
			<p><?= $this->Html->image('ske-icon.png', array('style' => 'margin-right: 10px;')); ?>Escribe el código del calzado que quieres excluyendo las letras que lo acompañan. Por ejemplo, si buscas #13500CRL, escribe 13500.</p>
			<p><?= $this->Html->image('ske-icon.png', array('style' => 'margin-right: 10px;')); ?>Escribe el modelo del calzado que quieres. Por ejemplo, si buscas GOrun, escribe gorun. El buscador no distingue mayúsculas de minúsculas.</p>
			<p class="colcel">¿Aún no puedes encontrar lo que buscas?:</p>
			<p>Escríbenos a <?= $this->Html->link('ventas@skechers.com', 'mailto:ventas@skechers.com'); ?> o buscanos en <?= $this->Html->link('Facebook', 'http://www.facebook.com/skechers.chile', array('title' => 'facebook')); ?> y <?= $this->Html->link('Twitter', 'http://www.twitter.com/SKECHERSCHILE', array('title' => 'Twitter')); ?></p>
			
		</div>
		
	</div>		
</div>