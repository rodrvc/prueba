<div class="col02">
	<h1 class="titulo"><? __('Administracion');?></h1>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th>Proceso</th>
			<th>Descripcion</th>
			<th class="actions"><? __('Acciones');?></th>
		</tr>
		<? if ($procesos) : ?>
			<? foreach ($procesos as $proceso) : ?>
			<? if ($proceso['url']) : ?>
				<tr>
					<td><?= $proceso['proceso']; ?></td>
					<td><?= $proceso['descripcion']; ?></td>
					<td>
						<?
						$onClick='';
						if ($proceso['confirm'])
						{
							$onClick='onclick="';
							$onClick.="return confirm('¿Estas seguro de ejecutar el proceso ".$proceso['proceso']." ?');";
							$onClick.='" ';
						}
						?>
						<a <?= $onClick; ?>href="<?= $this->Html->url($proceso['url']); ?>">
							<img src="<?= ($proceso['icon']) ? $this->Html->url('/img/iconos/'.$proceso['icon']):$this->Html->url('/img/iconos/gear_32.png'); ?>" />
						</a>
					</td>
				</tr>
			<? else : ?>
				<tr>
					<td colspan="3" style="text-align: left; font-weight: bold; text-transform: uppercase; background-color: rgba(0,128,255,0.4);"><?= $proceso['proceso']; ?></td>
				</tr>
			<? endif; ?>
				
			<? endforeach; ?>
		<? endif; ?>
	</table>
</div>