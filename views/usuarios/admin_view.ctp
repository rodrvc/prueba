<div class="col02">
	<h1 class="titulo">Previsualización de <? __('usuario');?></h1>
	<div class="previsualizar">
		<ul>
			<li><span><? __('Nombre'); ?>:</span><p><?= $usuario['Usuario']['nombre']; ?>&nbsp;</p></li>
			<li><span><? __('Apellido Paterno'); ?>:</span><p><?= $usuario['Usuario']['apellido_paterno']; ?>&nbsp;</p></li>
			<li><span><? __('Apellido Materno'); ?>:</span><p><?= $usuario['Usuario']['apellido_materno']; ?>&nbsp;</p></li>
			<li><span><? __('Sexo'); ?>:</span><p><?= $this->Html->link($usuario['Sexo']['nombre'], array('controller' => 'sexos', 'action' => 'view', $usuario['Sexo']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Rut'); ?>:</span><p><?= $usuario['Usuario']['rut']; ?>&nbsp;</p></li>
			<li><span><? __('Fecha Nacimiento'); ?>:</span><p><?= $usuario['Usuario']['fecha_nacimiento']; ?>&nbsp;</p></li>
			<li><span><? __('Estadocivil'); ?>:</span><p><?= $this->Html->link($usuario['Estadocivil']['nombre'], array('controller' => 'estadociviles', 'action' => 'view', $usuario['Estadocivil']['id'])); ?>&nbsp;</p></li>
			<li><span><? __('Email'); ?>:</span><p><?= $usuario['Usuario']['email']; ?>&nbsp;</p></li>
		</ul>
	</div>
	<? if ( $usuario['Direccion'] ) : ?>
	<h2 class="subtitulo">Direcciones</h2>
		<? foreach ( $usuario['Direccion'] as $direccion ) : ?>
		<div class="previsualizar">
			<ul>
				<?
					$dato = $direccion['calle'];
					if ($direccion['numero'])
					{
						$dato = $dato.' #'.$direccion['numero'];
					}
					if ($direccion['depto'])
					{
						$dato = $dato.', depto '.$direccion['depto'];
					}
				?>
				<li class="extendido"><span><? __('Direccion'); ?>:</span><?= $dato; ?>&nbsp;</li>
				<li class="extendido"><span><? __('Comuna'); ?>:</span><p><?= $direccion['Comuna']['nombre']; ?>&nbsp;</p></li>
				<li class="extendido"><span><? __('Otras Indicaciones'); ?>:</span><?= $direccion['otras_indicaciones']; ?>&nbsp;</li>
				<li><span><? __('Telefono'); ?>:</span><p><?= $direccion['telefono']; ?>&nbsp;</p></li>
				<li><span><? __('Celular'); ?>:</span><p><?= $direccion['celular']; ?>&nbsp;</p></li>
			</ul>
		</div>
		<? endforeach; ?>
	<? endif; ?>
	<div class="botones">
		<?= $this->Html->link('<span class="editar">Editar</span>', array('action' => 'edit', $usuario['Usuario']['id']), array('escape' => false)); ?>
		<?= $this->Html->link('<span class="borrar">Borrar</span>', array('action' => 'delete', $usuario['Usuario']['id']), array('escape' => false), sprintf(__('¿Estas seguro de eliminar el registro # %s?', true), $usuario['Usuario']['id'])); ?>
	</div>
</div>
