<style>
.col02 > h1.titulo > a.btn-admin {
	float: right;
	font-size: small;
	text-decoration: none;
	border: 1px solid #a7a7a7;
	padding: 3px;
	border-radius: 4px;
}
.col02 > h1.titulo > a.btn-admin:hover {
	/*text-decoration: underline;*/
	background-color: #eee;
}
</style>
<div class="col02">
 	<h1 class="titulo">
		<? __('Imagenes y Galeria'); ?>
		<a href="<?= $this->Html->url(array('controller' => 'administradores', 'action' => 'administracion')); ?>" class="btn-admin">&raquo; administrar sitio</a>
	</h1>
	<?= $this->Form->create('Administrador', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
	<ul class="edit">
		<li>
			<?= $this->Form->input('categoria_id', array('empty' => 'todas')); ?>
		</li>
		<li>
			<?= $this->Form->input('galeria', array('type' => 'select',
													'options' => array('todos' => 'todos',
																	   'incompleto' => 'galeria incompleta',
																	   'completo' => 'galeria completa',
																	   'sobrecargado' => 'galeria sobrecargada'),
													'default' => 'todos')); ?>
		</li>
		<li>
			<?= $this->Form->input('coleccion_id', array('type' => 'select',
														 'options' => $colecciones,
														 'empty' => '- todas')); ?>
		</li>
	</ul>
	<div class="botones">
		<a href="#" class="submit"><span class="buscar">filtrar</span></a>
	</div>
	<?= $this->Form->end(); ?>
	<table cellpadding="0" cellspacing="0" class="tabla">
		<tr>
			<th>ID</th>
			<th>codigo</th>
			<th>principal</th>
			<th>B</th>
			<th>C</th>
			<th>D</th>
			<th>E</th>
			<th>F</th>
			<th></th>
		</tr>
		<? foreach ($productos as $producto) : ?>
			<tr>
				<td><?= $producto['Producto']['id']; ?></td>
				<td><?= $producto['Producto']['codigo_completo']; ?></td>
				<td>
					<? if (isset($producto['Producto']['foto']['mini']) && $producto['Producto']['foto']['mini']) : ?>
					<img src="<?= $this->Shapeups->imagen($producto['Producto']['foto']['mini']); ?>" /></td>
					<? endif; ?>
				<? $cont = 0; ?>
				<? if ($producto['Galeria']) : ?>
					<? foreach ($producto['Galeria'] as $galeria) : ?>
						<td><img src="<?= $this->Shapeups->imagen('Galeria/'.$galeria['id'].'/mini_'.$galeria['imagen']); ?>" /></td>
						<? $cont++; ?>
					<? endforeach; ?>
				<? endif; ?>
				<?
				if ($cont<5)
				{
					for($x=$cont;$x<5;$x++)
					{
						echo '<td><img src="'.$this->Html->url('/img/sin-zapatilla.jpg').'" width="71" height="63" /></td>';
					}
				}
				?>
				<td>
					<a href="<?= $this->Html->url(array('controller' => 'productos', 'action' => 'edit', $producto['Producto']['id'])); ?>" style="margin: 0 7px;" target="_blank">edit</a>
					<!--<a href="#" style="margin: 0 7px;" target="_blank">reload</a>-->
				</td>
			</tr>
		<? endforeach; ?>
	</table>
	<div class="paginador">
		<?= $this->Paginator->numbers(array('separator' => false)); ?>
	</div>
</div>
