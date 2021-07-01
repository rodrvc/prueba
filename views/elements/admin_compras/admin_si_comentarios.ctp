<h1 class="titulo">Comentarios (<?= count($comentarios); ?>)</h1>
<? if (isset($comentarios) && $comentarios) : ?>
<div class="comentarios" style="float: left; height: 290px; overflow-x: auto; width: 685px; margin-bottom: 20px; margin-top: 10px; border: 1px solid #999; padding: 20px 5px;">
	<? foreach ($comentarios as $comentario) : ?>
	<div class="previsualizar" style="width: 650px;">
		<ul style="width: 650px;">
			<li style="width: 325px;"><span><?= __('Nombre'); ?>:</span><p><?= $comentario['Comentario']['nombre']; ?></p></li>
			<li style="width: 325px;">
				<span><?= __('Publicado'); ?>:</span>
				<div style="float: left; width: 120px;">
					<?= date('d-m-y H:i', strtotime($comentario['Comentario']['created'])); ?>
				</div>
				<? if ( isset($authUser['perfil']) && in_array($authUser['perfil'], array(3)) ) : ?>
				<div style="float: right;">
					<?= $this->Html->link($this->Html->image('iconos/pencil_16.png'), array('controller' => 'comentarios', 'action' => 'edit', $comentario['Comentario']['id']), array('escape' => false)); ?>
					<?= $this->Html->link($this->Html->image('iconos/delete_16.png'), '#', array('escape' => false, 'class' => 'eliminar-comentario', 'data-id' => $comentario['Comentario']['id'])); ?>
				</div>
				<? endif; ?>
			</li>
			<li class="extendido" style="width: 650px;"><span><?= __('Comentarios'); ?>:</span><?= $comentario['Comentario']['comentario']; ?></li>
		</ul>
	</div>
	<? endforeach; ?>
</div>
<? endif; ?>
<?= $this->Form->create('Compra', array('action' => 'comentario', 'type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
<div class="previsualizar">
	<ul>
		<?= $this->Form->hidden('id'); ?>
		<li class="extendido">
			<span style="font-weight: 100;">NOMBRE</span>
			<? if (isset($authUser['id']) && in_array($authUser['id'], array(1,2,5,12))) : ?>
			<?= $this->Form->input('Comentario.0.nombre', array('label' => false, 'style' => 'width: 500px;')); ?>
				<?
					// CARGA NOMBRE DEL USUARIO AUTOMATICAMENTE EN EL FORMULARIO
					$ejecutar_script = false;
					if (isset($this->data['Comentario'][0]['nombre']))
					{
						if (! $this->data['Comentario'][0]['nombre'])
						{
							$ejecutar_script = true;
						}
					}
					else
					{
						$ejecutar_script = true;
					}
				?>
				<? if (isset($ejecutar_script) && $ejecutar_script && isset($authUser['nombre']) && $authUser['nombre']) : ?>
					<script>
					// <![CDATA[
					if (! $('#Comentario0Nombre').val()) {
						$('#Comentario0Nombre').val('<?= $authUser['nombre']; ?>');
					}
					// ]]>
					</script>
				<? endif; ?>
			<? else : ?>
			<?= $this->Form->input('Comentario.0.nombre', array('type' => 'select',
																'label' => false,
																'empty' => '- seleccione nombre',
																'options' => $trabajadores)); ?>
			<? endif; ?>
		</li>
		<li class="extendido">
			<span style="font-weight: 100;">COMENTARIO</span>
			<?= $this->Form->input('Comentario.0.comentario', array('label' => false, 'style' => 'width: 500px;')); ?>
		</li>
	</ul>
</div>
<div class="botones">
	<a href="#" class="submit"><span class="guardar">Enviar</span></a>
</div>
<?= $this->Form->end(); ?>