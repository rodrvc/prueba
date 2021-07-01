<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active">
		<?= $this->Html->link('Mis Datos',
								  array('controller' => 'usuarios', 'action' => 'perfil_datos'),
								  array('class' => 'mi' . ($this->params['controller'] == 'usuarios' && $this->action == 'perfil_datos' ? ' current' : ''))); ?>
	</li>
	<li role="presentation">
		<?= $this->Html->link('Mis Direcciones',
								  array('controller' => 'direcciones', 'action' => 'mis_direcciones'),
								  array('class' => 'mi dire' . ($this->params['controller'] == 'direcciones' && $this->action == ('mis_direcciones' || 'editar') ? ' current' : ''))); ?>
	</li>
	<li role="presentation">
		<?= $this->Html->link('Mi Contraseña',
								  array('controller' => 'usuarios', 'action' => 'cambioclave'),
								  array('class' => 'mi pass' . ($this->params['controller'] == 'usuarios' && $this->action == 'cambioclave' ? ' current' : ''))); ?>
	</li>
	<li role="presentation">
		<?= $this->Html->link('Mi Historial de compra',
								  array('controller' => 'usuarios', 'action' => 'historial'),
								  array('class' => 'mi historial' . ($this->params['controller'] == 'usuarios' && $this->action == 'historial' ? ' current' : ''))); ?>
	</li>
</ul>