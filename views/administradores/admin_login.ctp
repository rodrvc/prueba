<? if ( $this->Session->check('Message.auth') ) : ?>
<div class="alerta" style="padding: 5px;">
	<?= $this->Session->flash('auth'); ?></div>
<? endif; ?>

<?= $this->Form->create('Administrador'); ?>
	<label>
		<span style="width: 60px; float: left; margin-top: 7px; margin-left: 70px">Usuario:</span>
		<?= $this->Form->input('usuario', array('label' => false, 'div' => false, 'class' => 'clase-input', 'style' => 'width: 150px;')); ?>
	</label>
	<br />
	<br />
	<label>
		<span style="width: 60px; float:left; margin-top: 7px; margin-left: 70px">Clave:</span>
		<?= $this->Form->input('clave', array('label' => false, 'type' => 'password', 'div' => false, 'class' => 'clase-input', 'style' => 'width: 150px;')); ?>
	</label>

	<div class="botones">
		<a href="#" class="submit"><span class="entrar">Entrar</span></a>
	</div>
<?= $this->Form->end(); ?>
