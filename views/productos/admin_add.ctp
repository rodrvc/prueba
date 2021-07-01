<div class="col02">
	<?= $this->Form->create('Producto', array('type' => 'file', 'inputDefaults' => array('class' => 'clase-input', 'div' => false, 'label' => array('class' => 'texto')))); ?>
 	<h1 class="titulo"><? __('Agregar Producto'); ?></h1>
	<ul class="edit">
		<li><?= $this->Form->input('nombre'); ?></li>
		<li><?= $this->Form->input('activo'); ?></li>
		<li><?= $this->Form->input('categoria_id', array('empty' => '- categoria')); ?></li>
		<li><?= $this->Form->input('coleccion_id', array('empty' => '- colección')); ?></li>
		<li><?= $this->Form->input('foto', array('type' => 'file')); ?></li>
		<li><?= $this->Form->input('color_id'); ?></li>
		<li><?= $this->Form->input('codigo'); ?></li>
		<li><?= $this->Form->input('precio'); ?></li>
		<li><?= $this->Form->input('oferta', array('type' => 'checkbox')); ?></li>
		<li><?= $this->Form->input('precio_oferta'); ?></li>
		<li><?= $this->Form->input('excluir_descuento'); ?></li>
		<li><?= $this->Form->input('ficha'); ?></li>
		<li><?= $this->Form->input('descripcion'); ?></li>
		<li><?= $this->Form->input('new'); ?></li>
		<li><?= $this->Form->input('escolar'); ?></li>
		<li><?= $this->Form->input('outlet'); ?></li>
		<li><?= $this->Form->input('tipo'); ?></li>
		<li><?= $this->Form->input('division'); ?></li>
		<li><?= $this->Form->input('showroom'); ?></li>
		<li><?= $this->Form->input('stock_seguridad'); ?></li>
		<li style="display: inline-block">
			<style>
			.listado-grupos {
				float: left;
				width: 355px;
			}
			.listado-grupos input {
				float: left;
				margin-bottom: 5px;
			}
			.listado-grupos .boton-mas-grupo {
				float: right;
				width: 10px;
				text-decoration: none !important;
				border: 1px solid #3b3b3b;
				padding: 0 3px 0 3px;
				text-align: center;
				border-radius: 9px;
				font-size: 10px;
				background-color: #8f8f8f;
				font-family: monospace;
				font-weight: bold;
				color: #fff;
			}
			</style>
			<label class="texto">grupo</label>
			<div class="listado-grupos">
				<?= $this->Form->input('Producto.grupo.0', array('type' => 'text')); ?>
				<a href="#" class="boton-mas-grupo">+</a>
			</div>
			<script>
			$.template('agregarGrupoProd', '<input id="ProductoGrupo${Index}" class="clase-input" type="text" name="data[Producto][grupo][${Index}]">');
			$('.boton-mas-grupo').click(function(e)
			{
				e.preventDefault();
				var total	= $('.listado-grupos input').size();
				if( total < 6 )
				{
					$('.listado-grupos').append($.tmpl('agregarGrupoProd', { Index: total }));
					if( total >= 5 )
					{
						$('.boton-mas-grupo').hide();
					}
				}
			});
			</script>
		</li>
	</ul>
	
	<!-- GALERIA -->
	<h2 class="subtitulo"><? __('Agregar imagen a la galería'); ?></h2>
	<ul id="galeria" class="edit">
		<li>
			<label class="texto" for="Galeria0Imagen">Imagenes Galeria</label>
			<input type="file" id="Galeria0Imagen" class="clase-input" name="data[Galeria][][imagen]" multiple>
		</li>
	</ul>

	<div class="botones">
		<a href="#" class="submit"><span class="guardar">Guardar</span></a>
	<!--	<a href="#" class="btn-mas"><span class="mas">Agregar mas</span></a>-->
	</div>
	<?= $this->Form->end(); ?>
</div>
