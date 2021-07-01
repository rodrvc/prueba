<div class="container">
	<h1 class="titulo-categoria">Registrate o ingresa</h1>
	<div class="panel panel-skechers">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-info panel-skechers2">
						<div class="panel-heading">
							<h3 class="panel-title">Usuario existente</h3>
						</div>
						<div class="panel-body">
							<?= $this->Form->create('Usuario', array('action' => 'login', 'class' => 'form-horizontal')); ?>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Usuario</label>
									<div class="col-sm-10">
										<?= $this->Form->input('email', array('type' => 'text',
																			  'div' => false,
																			  'label' => false,
																			  'class' => 'form-control',
																			  'placeholder' => 'Email')); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Clave:</label>
									<div class="col-sm-10">
										<?= $this->Form->input('clave', array('type' => 'password',
																			  'div' => false,
																			  'label' => false,
																			  'class' => 'form-control',
																			  'placeholder' => 'Tu clave')); ?>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<button type="submit" class="btn btn-primary">Ingresa</button>
									</div>
								</div>
								<a href="#" class="pull-right" rel="recuperarClave"><i class="fa fa-angle-double-right"></i>recuperar contraseña</a>
							<?= $this->Form->end(); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="panel panel-info panel-skechers2">
						<div class="panel-heading">
							<h3 class="panel-title">Usuario nuevo</h3>
						</div>
						<div class="panel-body">
							<?= $this->Form->create('Usuario', array('class' => 'form-horizontal')); ?>
							<div class="form-group">
									<label class="col-sm-5 control-label">Nombres:</label>
									<div class="col-sm-6">
										<?= $this->Form->input('nombre', array('class' => 'form-control', 'placeholder' => 'Tus nombres', 'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label">Apellido paterno:</label>
									<div class="col-sm-6">
										<?= $this->Form->input('apellido_paterno', array('class' => 'form-control', 'placeholder' => 'Tu apellido paterno', 'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label">Apellido materno:</label>
									<div class="col-sm-6">
										<?= $this->Form->input('apellido_materno', array('class' => 'form-control', 'placeholder' => 'Tu apellido materno', 'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label">Género:</label>
									<div class="col-sm-6">
										<?= $this->Form->input('sexo_id', array('class' => 'form-control', 'empty' => 'Seleccione género', 'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label">Rut</label>
									<div class="col-sm-6">
										<?= $this->Form->input('rut', array('class' => 'form-control', 'placeholder' => 'Tu Rut', 'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label">E-mail</label>
									<div class="col-sm-6">
										<?= $this->Form->input('email', array('class' => 'form-control', 'placeholder' => 'Tu email', 'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label">Confirmar E-mail</label>
									<div class="col-sm-6">
										<?= $this->Form->input('email2', array('class' => 'form-control', 'placeholder' => 'Tu email', 'label' => false)); ?>
									</div>
								</div>
							
								<div class="form-group">
									<label class="col-sm-5 control-label">Contraseña:</label>
									<div class="col-sm-6">
										<?= $this->Form->input('clave', array('class' => 'form-control', 'placeholder' => 'Tu clave', 'label' => false, 'type' => 'password')); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label">Confirmar contraseña:</label>
									<div class="col-sm-6">
										<?= $this->Form->input('repetir_clave', array('class' => 'form-control', 'placeholder' => 'Repite tu clave', 'label' => false, 'type' => 'password')); ?>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-5 col-sm-6">
										<button type="submit" class="btn btn-primary" id="registrate">Registrate</button>
									</div>
								</div>
							<?= $this->Form->end(); ?>
						</div>
					</div>
				</div>
			
			</div>
		</div>
	</div>
</div>
<script>


$(document).ready(function()
{
	    var Fn = {
        // Valida el rut con su cadena completa "XXXXXXXX-X"
        validaRut : function (rutCompleto) {
            rutCompleto = rutCompleto.replace("‐","-");
            if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test( rutCompleto ))
                return false;
            var tmp 	= rutCompleto.split('-');
            var digv	= tmp[1];
            var rut 	= tmp[0];
            if ( digv == 'K' ) digv = 'k' ;

            return (Fn.dv(rut) == digv );
        },
        dv : function(T){
            var M=0,S=1;
            for(;T;T=Math.floor(T/10))
                S=(S+T%10*(9-M++%6))%11;
            return S?S-1:'k';
        }
    }


console.log('holaaaa');
	$('#registrate').click(function()
	{
		var run = $("#UsuarioRut").val();
		if (run != ''){
            if (Fn.validaRut( run)){
                return true;
            } else {
				alert('Rut No valido');
				return false;
	        }
        }else{
        	alert('Debe ingresar su rut');
        	return false;

        }
	});
});
$('#UsuarioAddForm .form-group .input.error').each(function(index,elemento) {
	// error
	var texto = $(elemento).find('.error-message').text();
	$(elemento).find('.error-message').addClass('hidden');

	// destacar input + tooltip
	$(elemento).parents('.form-group').addClass('has-error has-feedback').find('.form-control').attr({
		'data-toggle':'tooltip',
		'data-placement' : 'top',
		'title' : texto
	});
	if ($(elemento).parents('.form-group').find('.form-control[name="data[Usuario][repetir_clave]"]').length) {
		console.log(elemento);
		$(elemento).parents('form').find('input[name="data[Usuario][clave]"]').parents('.form-group').addClass('has-error has-feedback').find('.form-control').attr({
			'data-toggle':'tooltip',
			'data-placement' : 'top',
			'title' : texto
		});
	}

	// icono
	$(elemento).append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
});

$('#UsuarioLoginForm .form-group .form-control.form-error').each(function(index,elemento) {
	// error
	var texto = $(elemento).parents('.form-group').find('.error-message').text();
	$(elemento).parents('.form-group').find('.error-message').addClass('hidden');

	// destacar input + tooltip + icono
	// $(elemento).attr({
	// 	'data-toggle':'tooltip',
	// 	'data-placement' : 'top',
	// 	'title' : texto
	// }).parents('.form-group').addClass('has-error has-feedback').append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
});

$('[data-toggle="tooltip"]').tooltip({
	// container: 'body',
	template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow" style="border-top-color: #94241B;"></div><div class="tooltip-inner" style="background-color: #94241B;"></div></div>'
});
</script>