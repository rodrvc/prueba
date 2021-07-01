<div class="col02">
	<h1 class="titulo">Previsualización de <? __('banner');?></h1>
	<div class="previsualizar">
		<ul>
			<li class="extendido"><span><? __('Imagen'); ?>:</span><p><?= $this->Html->image($banner['Banner']['imagen']['mini']); ?>&nbsp;</p></li>
			<li class="extendido"><span><? __('Link'); ?>:</span><?= $banner['Banner']['link']; ?>&nbsp;</li>
			<li class="extendido">
				<span><? __('Tipo'); ?>:</span>
				<? if ( $banner['Banner']['tipo'] == 0 ) : ?>
				<p>Core</p>
				<? else : ?>
				<p>Banner</p>
				<? endif; ?>
			</li>
		</ul>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $banner['Banner']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $banner['Banner']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $banner['Banner']['id'])); ?>
	</div>
</div>
