<div class="menu-outlet">
	<a href="#" class="categoria drop-down">Ordenar<span class="caret"></span></a>
	<div class="dropdown">
		<ul>
			<? foreach ($ordenar as $x => $orden) : ?>
			<?
				$class = $categoria = '';
				$param = 0;
				if ( $this->params['controller'] == 'productos' && in_array($this->params['action'], array('tallas','color')) )
				{
					if ( isset($this->params['pass'][2]) && $this->params['pass'][2] == $x )
						$class = 'active';
					$categoria = $this->params['pass'][0];
					$param = $this->params['pass'][1];
				}
				elseif (isset($this->data['Producto']['ordenar']) && $this->data['Producto']['ordenar'] == $x)
					$class = 'active';
			?>
			<li class="<?=  $class; ?>">
				<a href="#" class="ordenar-catalogo"
				   data-x="<?= $x; ?>"
				   data-categoria="<?= $categoria; ?>"
				   data-param="<?= $param; ?>">
				   <?= $orden; ?>
				</a>
			</li>
			<? endforeach; ?>
		</ul>
	</div>
	<?= $this->Form->create('Producto'); ?>
	<?= $this->Form->hidden('ordenar'); ?>
	<?= $this->Form->end(); ?>
</div>
