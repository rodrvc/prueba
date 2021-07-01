<style>
#modalAlerta .modal-dialog > .modal-content {
	background-color: rgba(33,33,33,0.3);	
}
#modalAlerta .modal-dialog > .modal-content > .modal-body {
	padding: 20px !important;
}
#modalAlerta .modal-dialog > .modal-content > .modal-body > .alert::before {
/*	content: url(<?= $this->Html->url('/img/icon-skechers.png'); ?>);
	left: 23px;
    margin-right: 10px;
    position: absolute;
    top: 18px;*/
}
#modalAlerta .modal-dialog > .modal-content > .modal-body > .close {
	position: absolute;
	right: 0px;
	top: 0px;
}
</style>

<!--MODAL CAMBIO DE TALLA-->
<div class="modal fade" id="modalAlerta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header hidden">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">&nbsp;</h4>
			</div>
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times-circle-o"></i></button>
				<div class="alert alert-info nomargin" rel="texto"></div>
			</div>
			<div class="modal-footer hidden">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
<? if ($alerta = $this->Session->flash()) : ?>
<script>
	<? if ($alerta) : ?>
	var textoAlerta = $('<?= $alerta; ?>').html();
	var targetModal = $('#modalAlerta');
	var targetTxt = targetModal.find('div[rel="texto"]');
	if ($('<?= $alerta; ?>').attr('class') != 'message') {
		var clase = 'alert '+$('<?= $alerta; ?>').attr('class');
		targetTxt.removeAttr('class').addClass(clase);
	}
	targetTxt.html(textoAlerta);
	targetModal.modal('show');
	<? endif; ?>
</script>
<? endif; ?>