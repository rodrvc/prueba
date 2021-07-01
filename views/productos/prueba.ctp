
<div class="container">
	<div class="panel panel-default">
		<div class="panel-body nopadding-top">
			
			<div class="row">
				<div class="col-md-12">
					<div class="progress barra">

					  <div class="progress-bar progress-bar-primary relative" style="width: 20%">
					    <span class="icon-skechers">
					    	<div class="icono">
					    		<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard.png'); ?>" width="100%">
					    	</div>
							<div class="texto">
								Despacho
							</div>
					    </span>
					  </div>

					  <div class="progress-bar relative progress-bar-primary" style="width: 20%">
					    <span class="icon-skechers">
					    	<div class="icono">
					    		<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard.png'); ?>" width="100%">
					    	</div>
							<div class="texto">
								Confirmar
							</div>
					    </span>
					  </div>

					   <div class="progress-bar relative progress-bar-primary" style="width: 20%">
					    <span class="icon-skechers">
					    	<div class="icono">
					    		<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard.png'); ?>" width="100%">
					    	</div>
							<div class="texto">
								Pago
							</div>
					    </span>
					  </div>

					  <div class="progress-bar relative progress-bar-danger" style="width: 20%">
					    <span class="icon-skechers">
					    	<div class="icono">
					    		<img src="<?= $this->Html->url('/img/bootstraps/icon-skechers-wizard-disabled.png'); ?>" width="100%">
					    	</div>
							<div class="texto text-disabled">
								Recibo
							</div>
					    </span>
					  </div>

					  <div class="progress-bar relative progress-bar-danger" style="width: 20%">
					  </div>

					</div>
				</div>
			</div>

 			<div class="jumbotron jumbotron-skechers">
 				<div class="container">
 					<div class="text-center"><?= $this->Html->image('tbp.png'); ?>></div>
 					<h1 class="text-center"><i class="fa fa-refresh fa-spin"></i></h1>
 					<p class="text-center"> Su información esta siendo procesada...</p>

 				</div>
 			</div>




		<form name="formulario" method="post" id="formulariotbk" action="<?= $response->url; ?>">
				<input name="token_ws" value="<?= $response->token; ?>" type="HIDDEN">
			</form>

			<script>
				$(document).ready(function() {
					setTimeout(function(){ $("#formulariotbk").submit();  }, 2000);
				})
			</script>

<script>
$(document).ready(function(){

		dataLayer.push({

	 'event': 'checkoutOption',
	 'ecommerce': {
		 'checkout_option': {
		 	'actionField': {'step': 4}
		 }
	 }
	});

});
</script>

		</div>
	</div>
</div>
