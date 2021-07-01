<div class="col02">
	<? if (isset($reporte)) : ?>
	<div class="previsualizar" style="margin-top: 30px;">
		<? if ($reporte && is_array($reporte)) : ?>
		<h2 class="subtitulo">Reporte de Carga</h2>
		<ul>
			<li class="extendido"><b><? __('Archivo'); ?>:</b>
				<?
				if (isset($this->data['Compra']['archivo']['name']) && $this->data['Compra']['archivo']['name'])
					echo $this->data['Compra']['archivo']['name'];
				?>
				&nbsp;
			</li>
			<li><b><? __('Tipo'); ?>:</b></li>
			<li>
				<b>
				<?
				if (isset($this->data['Compra']['tipo']))
				{
					if ($this->data['Compra']['tipo']==1)
						echo 'picking';
					elseif ($this->data['Compra']['tipo']==2)
						echo 'boleta';
					elseif ($this->data['Compra']['tipo']==3)
						echo 'chilex';
					elseif ($this->data['Compra']['tipo']==4)
						echo 'num id';
					elseif ($this->data['Compra']['tipo']==5)
						echo 'correos de chile';
					elseif ($this->data['Compra']['tipo']==6)
						echo 'Tango';
					elseif ($this->data['Compra']['tipo']==7)
						echo 'Reprocsar chilex';
					elseif ($this->data['Compra']['tipo']==8)
						echo 'Reprocsar Tango';
					elseif ($this->data['Compra']['tipo']==9)
						echo 'Click&Collect';
				}
				?>
				</b>
				&nbsp;
			</li>
			<li><b><? __('Lineas leidas'); ?></b></li>
			<li><b><?= $reporte['lineas']; ?></b></li>
			<li><b><? __('Registros actualizados'); ?></b></li>
			<li><b><?= $reporte['actualizados']; ?></b></li>
			<li class="extendido" style="padding-left: 20px;"><?= $reporte['log_actualizados']; ?>&nbsp;</li>
			<li><b><? __('Parametros invalidos'); ?></b></li>
			<li><b><?= $reporte['parametro_invalido']; ?></b></li>
			<li class="extendido" style="padding-left: 20px;">lineas: <?= $reporte['log_parametro_invalido']; ?>&nbsp;</li>
			<li><b><? __('Compras invalidas'); ?></b></li>
			<li><b><?= $reporte['compra_invalida']; ?></b></li>
			<li class="extendido" style="padding-left: 20px;"><?= $reporte['log_compra_invalida']; ?>&nbsp;</li>
			<li><b><? __('Omitidos (sin picking o con valor replicado)'); ?></b></li>
			<li><b><?= $reporte['omitidos']; ?></b></li>
			<li class="extendido" style="padding-left: 20px;"><?= $reporte['log_omitidos']; ?>&nbsp;</li>
			<li><b><? __('Errores'); ?></b></li>
			<li><b><?= $reporte['errores']; ?></b></li>
			<li class="extendido" style="padding-left: 20px;"><?= $reporte['log_errores']; ?>&nbsp;</li>
		</ul>
		<? else : ?>
		<ul>
			<li class="extendido">No fue posible cargar el archivo...</li>
		</ul>
		<? endif; ?>
		<div class="botones">
			<a href="<?= $this->Html->url(array('action' => 'cargas')); ?>"><span class="guardar" style="background-image: url('<?= $this->Html->url('/img/iconos/left_16.png'); ?>')";>Volver</span></a>
		</div>
	</div>
	<? else : ?>
	<?= $this->Form->create('Compra', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Cargas'); ?></h1>
	<ul class="edit">
		<li>
			<?= $this->Form->input('archivo', array('type' => 'file')); ?>
		</li>
		<li>
			<?= $this->Form->input('tipo', array(
				'type' => 'select',
				'options' => array(
					1 => 'PICKING NUMBER',
					2 => 'BOLETA',
					3 => 'CHILEXPRESS',
					5 => 'CORREOS DE CHILE',
					4 => 'N° ID',
					6 => 'TANGO',
					7 => 'REPROCESAR CHILEX',
					8 => 'REPROCESAR TANGO',
					9 => 'Click&Collect',
				)
			)); ?>
		</li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	</div>
	<?= $this->Form->end(); ?>
	<? endif; ?>
</div>
