<div class="col02">
	<h1 class="titulo">Previsualización de <? __('descuento');?></h1>
	<div class="previsualizar">
		<ul>
			<li class="extendido"><span><? __('Nombre'); ?>:</span><p><?= $descuento['Descuento']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Cantidad'); ?>:</span><p><?= $descuento['Descuento']['cantidad']; ?>&nbsp;</p></li>
			<li><span><? __('Fecha Caducidad'); ?>:</span><p><?= date('d-F-Y', strtotime($descuento['Descuento']['fecha_caducidad'])); ?>&nbsp;</p></li>
			<li><span><? __('Codigo'); ?>:</span><p><?= $descuento['Descuento']['codigo']; ?>&nbsp;</p></li>
			<li><span><? __('Contador'); ?>:</span><p><?= $descuento['Descuento']['contador']; ?>&nbsp;</p></li>
			<li><span><? __('Tipo'); ?>:</span><p><?= $descuento['Descuento']['tipo']; ?>&nbsp;</p></li>
			<li><span><? __('Descuento'); ?>:</span><p><?= $descuento['Descuento']['descuento']; ?>&nbsp;</p></li>
			<?
				$web_tienda = 'web';
				if ($descuento['Descuento']['web_tienda'])
				{
					$web_tienda = 'tienda';
					if ($descuento['Descuento']['web_tienda'] == 2)
					{
						$web_tienda = 'ambos';
					}
				}
			?>
			<li><span><? __('Web/Tienda'); ?>:</span><p><?= $web_tienda; ?>&nbsp;</p></li>
			<li><span><? __('Max. X usuario'); ?>:</span><p><?= $descuento['Descuento']['maximo']; ?>&nbsp;</p></li>
			<li class="extendido"><span><? __('Comentario'); ?>:</span><?= $descuento['Descuento']['comentario']; ?>&nbsp;</li>
			<li><span><? __('escolar'); ?>:</span><p><?= ($descuento['Descuento']['escolar']) ? 'si' : 'no'; ?>&nbsp;</p></li>
			<li><span><? __('Correlativo'); ?>:</span><p><?= $descuento['Descuento']['correlativo']; ?>&nbsp;</p></li>
			<li><span><? __('Responsable'); ?>:</span><p><?= $descuento['Descuento']['responsable']; ?>&nbsp;</p></li>

		</ul>
	</div>
	<h2 class="subtitulo">Categorias</h2>
	<div class="previsualizar">
		<ul>
			<li class="extendido">
			<? foreach ( $descuento['Categoria'] as $categoria ) : ?>
				<span style="font-weight: 100; width: 160px;"><?= $categoria['nombre']; ?>&nbsp;</span>
			<? endforeach; ?>
			</li>
		</ul>
	</div>
	<? if ( $authUser['perfil'] == 3 ) : ?>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $descuento['Descuento']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $descuento['Descuento']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $descuento['Descuento']['id'])); ?>
	</div>
	<? endif; ?>
</div>
