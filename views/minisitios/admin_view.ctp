<div class="col02">
	<h1 class="titulo">Previsualización de <? __('minisitio');?></h1>
	<div class="previsualizar">
		<ul>
			<li class="extendido"><span><? __('Nombre'); ?>:</span><p><?= $minisitio['Minisitio']['nombre']; ?>&nbsp;</p></li>
			<li class="extendido">
				<span><? __('Tipo'); ?>:</span>
				<? if ( $minisitio['Minisitio']['nombre'] == 0 ) : ?>
				Pre-Diseñada
				<? else : ?>
				Html
				<? endif; ?>
			</li>
		</ul>
	</div>
	<h2 class="subtitulo">Vista Previa</h2>
	<div class="previsualizar" style="background-color: #13A2CE;">
		<div style="background-color: #FFF; position: relative; left: 50%; border: 1px solid #D6D6D6; border-radius: 5px 5px 5px 5px; float: left; margin: 15px 0pt 15px -285px; width: 570px; max-width: 570px; z-index: 5;">
			<? if ( $minisitio['Minisitio']['tipo'] == 0 ) : ?>
			<h2 style="color: #13A2CE; margin: 5px 5px 5px 15px; text-decoration: none;">
				<?= $minisitio['Minisitio']['nombre']; ?>
			</h2>
			<div style="background-image: url(<?= $this->Html->url('/img/marco-acercade.png'); ?>); float: left; height: 235px; margin: 10px 59px; width: 452px;">
				<?= $this->Html->image($minisitio['Minisitio']['imagen']['sitio'], array('style' => 'margin: 12px 25px 12px 28px;')); ?>
			</div>
			<p style="color: #333333; font-size: 12px; font-weight: bolder; margin: 0 30px; text-decoration: none;">
				<?= nl2br($minisitio['Minisitio']['descripcion']); ?>
			</p>
			<br />
			<? else : ?>
			<h2 style="color: #13A2CE; margin: 5px 5px 5px 15px; text-decoration: none;">
				<?= $minisitio['Minisitio']['nombre']; ?>
			</h2>
			<?= $minisitio['Minisitio']['codigo']; ?>
			<? endif; ?>
		</div>
	</div>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $minisitio['Minisitio']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $minisitio['Minisitio']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $minisitio['Minisitio']['id'])); ?>
	</div>
</div>
