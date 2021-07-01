<div class="col02">
	<?= $this->Form->create('Banner'); ?>
	<style>
	.btn-add {
		float: right;font-size:12px;text-decoration:none !important;
	}
	.btn-add:hover {
		text-decoration:underline !important;
	}
	</style>
	<h1 class="titulo">
		<? __('Banners');?>
		<a href="<?= $this->Html->url(array('action' => 'add','carrusel')); ?>" class="btn-add">&raquo; agregar banner</a>
	</h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th>Imagen</th>
			<th>Link</th>
			<th>Orden</th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
		<? foreach ( $banners as $banner ) : ?>
		<tr>
			<td><?= $this->Html->image($banner['Banner']['imagen']['mini']); ?>&nbsp;</td>
			<td><?= $this->Html->link($this->Text->truncate($banner['Banner']['link'],20) , array('action' => 'view', $banner['Banner']['id']), array('title' => $banner['Banner']['link'], 'style' => 'text-decoration: none')); ?>&nbsp;</td>
			<td>
				<div class="cambiar-orden" style="display: none;">
					<? if ( isset($banner['Banner']['orden']) && $banner['Banner']['orden'] ) : ?>
						<?= $this->Form->input('Banner.' . $banner['Banner']['id'] . '.orden', array('class' => 'clase-input', 'label' => false, 'div' => false, 'style' => 'width: 50px;', 'value' => $banner['Banner']['orden'])); ?>
					<? else : ?>
						<?= $this->Form->input('Banner.' . $banner['Banner']['id'] . '.orden', array('class' => 'clase-input', 'label' => false, 'div' => false, 'style' => 'width: 50px;')); ?>
					<? endif; ?>
					<?= $this->Html->image('iconos/warning_16.png', array('style' => 'display: none;')); ?>
				</div>
				<div class="mostrar-orden"><?= $banner['Banner']['orden']; ?>&nbsp;</div>
			</td>
			<td class="actions">
				<?= $this->Html->link($this->Html->image('iconos/clipboard_16.png', array('title' => 'Ver')), array('action' => 'view', $banner['Banner']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/pencil_16.png', array('title' => 'Editar')), array('action' => 'edit', $banner['Banner']['id']), array('escape' => false)); ?>
				<?= $this->Html->link($this->Html->image('iconos/delete_16.png', array('title' => 'Eliminar')), array('action' => 'delete', $banner['Banner']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $banner['Banner']['id'])); ?>
			</td>
		</tr>
		<? endforeach; ?>
	</table>
	<div class="botones">
		<a href="#" id="guardar-orden" class="btn-enviar" style="display: none;"><span class="guardar">Guardar</span></a>
		<a href="#" class="habilitar-orden"><span class="reload">Cambiar Orden</span></a>
	</div>
	<?= $this->Form->end(); ?>
</div>
<script>
// HABILITA CAMPOS DE EDITION DE ORDEN DE BANNERS
$('#BannerAdminCarruselForm .habilitar-orden').click(function(evento)
{
	evento.preventDefault();
	$(this).hide();
	$('#BannerAdminCarruselForm .mostrar-orden').hide();
	
	$('#BannerAdminCarruselForm .cambiar-orden').fadeIn(1000);
	$('#BannerAdminCarruselForm #guardar-orden.btn-enviar').fadeIn(1000);
});

/**
 * MUESTRA WARNING DE CAMPOS FALTANTES
 *
 */
$('#BannerAdminCarruselForm #guardar-orden.btn-enviar').live('click', function(evento)
{
	evento.preventDefault();
	var acceso = true;
	$('input.clase-input').each(function(index, elemento)
	{
		if (! $(elemento).val() )
		{
			$(elemento).next().show();
			acceso = false;
		}
	});
	
	if ( acceso )
	{
		$('#BannerAdminCarruselForm').submit();
	}
});

// ELIMINA WARNING AL HACER FOCUS SOBRE EL CAMPO
$('#BannerAdminCarruselForm input').focus(function()
{
	$(this).next().fadeOut(1000);
});
</script>