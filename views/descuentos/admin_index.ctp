<div class="col02">
	<?= $this->element('admin_descuento_exportar'); ?>
	<?= $this->Form->create('Descuento', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Buscar Descuento'); ?></h1>
	<ul class="edit">
		<li style="border-bottom: none;"><?= $this->Form->input('codigo'); ?></li>
		<li style="font-size: 10px; color: #999; text-align: center;">Debe ingresar el codigo de descuento para tiendas que desea utilizar.</li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="buscar">Buscar</span></a>
	</div>
	<?= $this->Form->end(); ?>
	<? if ( isset($descuento) ) : ?>
		<? if ($descuento) : ?>
			<?
				// VERIFICACION DE DISPONIBILIDAD DEL DESCUENTO
				$disponible = true;
				$estilo = 'background-color: #64ca3e; font-size: 10px; font-weight: bold; color: #fff; text-align: center;';
				if ($descuento['Descuento']['cantidad'] <= $descuento['Descuento']['contador'])
				{
					$disponible = false;
					$estilo = 'background-color: #ff0000; font-size: 10px; font-weight: bold; color: #fff; text-align: center;';
				}
				// VERIFICACION DE TIPO DE DESCUENTO (DINERO O PORCENTAJE)
				$valor_descuento = '';
				if (isset($descuento['Descuento']['tipo']) && in_array($descuento['Descuento']['tipo'], array('POR', 'DIN')))
				{
					if ($descuento['Descuento']['tipo'] == 'DIN')
					{
						$valor_descuento = $this->Shapeups->moneda($descuento['Descuento']['descuento']);
					}
					else
					{
						$valor_descuento = $descuento['Descuento']['descuento'].'%';
					}
				}
				// VERIFICACION SI ES DESCUENTO DE TIENDA O WEB
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
			<div class="previsualizar" style="margin-top: 30px;">
				<ul>
					<li class="extendido"><span><? __('Nombre'); ?>:</span><p><?= $descuento['Descuento']['nombre']; ?>&nbsp;</p></li>
					<li>
						<span><? __('Cantidad'); ?>:</span>
						
						<p style="<?= $estilo; ?>"><?= $descuento['Descuento']['contador'].'/'.$descuento['Descuento']['cantidad']; ?>&nbsp;</p>
					</li>
					<li><span><? __('Fecha Caducidad'); ?>:</span><p><?= date('d-m-Y', strtotime($descuento['Descuento']['fecha_caducidad'])); ?>&nbsp;</p></li>
					<li>
						<span><? __('Descuento'); ?>:</span>
						<p><?= $valor_descuento; ?>&nbsp;</p>
					</li>
					<li>
						<span><? __('Web/Tienda'); ?>:</span>
						<p><?= $web_tienda; ?>&nbsp;</p>
					</li>
					<li><span><? __('Max.usuario'); ?>:</span><p><?= $descuento['Descuento']['maximo']; ?>&nbsp;</p></li>
					<li class="extendido">
						<span><? __('Categorias'); ?>:</span>
						<? $separador = ''; ?>
						<? foreach ( $descuento['Categoria'] as $categoria ) : ?>
							<?= $separador.$categoria['nombre']; ?>
							<? $separador = ', '; ?>
						<? endforeach; ?>
					</li>
					<li>
						<span><? __('Escolar'); ?>:</span>
						<p><?= ($descuento['Descuento']['escolar']) ? 'si' : 'no'; ?>&nbsp;</p>
					</li>
				</ul>
			</div>
			<? if ( $compras || $clientes ) : ?>
				<h2 class="subtitulo">Descuentos Utilizados</h2>
				<? if ($compras) : ?>
					<? foreach ($compras as $compra) : ?>
					<div class="previsualizar">
						<ul>
							<li><span>Tipo</span><p>Web</p></li>
							<li><span>Tienda</span><p>store.skechers-chile.cl</p></li>
							<li class="extendido"><span>Cliente</span><p><?= $compra['Usuario']['nombre'].' '.$compra['Usuario']['apellido_paterno']; ?>&nbsp;</p></li>
							<li class="extendido"><span>Fecha</span><p><?= $compra['Compra']['created']; ?>&nbsp;</p></li>
							
						</ul>
					</div>
					<? endforeach; ?>
				<? endif; ?>
				<? if ($clientes) : ?>
					<? foreach ($clientes as $cliente) : ?>
					<div class="previsualizar">
						<ul>
							<li><span>Tipo</span><p>Tienda&nbsp;</p></li>
							<li><span>Tienda</span><p><?= $cliente['Administrador']['nombre']; ?>&nbsp;</p></li>
							<li class="extendido"><span>Cliente</span><p><?= $cliente['ClientesTienda']['nombre']; ?>&nbsp;</p></li>
							<li class="extendido"><span>Fecha</span><p><?= $cliente['ClientesTienda']['created']; ?>&nbsp;</p></li>
						</ul>
					</div>
					<? endforeach; ?>
				<? endif; ?>
			<? endif; ?>
			<? if ($disponible) : ?>
				<?= $this->Form->create('Descuento', array('action' => 'usar_descuento', 'type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
					<h1 class="titulo"><? __('Datos Cliente'); ?></h1>
					<ul class="edit">
						<?= $this->Form->hidden('Descuento.id', array('value' => $descuento['Descuento']['id']));?>
						<?= $this->Form->hidden('Descuento.codigo', array('value' => $descuento['Descuento']['codigo']));?>
						<li><?= $this->Form->input('Cliente.nombre'); ?></li>
						<li><?= $this->Form->input('Cliente.rut'); ?></li>
						<li><?= $this->Form->input('Cliente.telefono'); ?></li>
					</ul>
					<div class="botones">
						<a href="#" class="submit"><span class="generar">Utilizar</span></a>
					</div>
				<?= $this->Form->end(); ?>
			<? else : ?>
			<script type="text/javascript">
			$(document).ready(function()
			{
				alert('Este código de descuento ya fue utilizado');
			});
			</script>
			<? endif; ?>
		<? else : ?>
			<div class="previsualizar" style="margin-top: 30px;">
				<ul>
					<li class="extendido" style="text-align: center; font-weight: bold;">No se encontro descuento.</li>
				</ul>
			</div>
		<? endif; ?>
	<? endif; ?>
</div>