<? if ( isset($activarPromo) && $activarPromo ) : ?>
	<?
	if ( isset($_promo['header']['imagen']) && $_promo['header']['imagen'] )
		echo '<img src="https://s3.amazonaws.com/andain-sckechers/img/cybermonday2016/header_cyberday.png" rel="promoHeader" width="100%">';
	?>
	<? if ( isset($_promo['lb']['imagen']) && $_promo['lb']['imagen'] ) : ?>
	<style>
	#modalPromo > .modal-dialog { }
	#modalPromo .modal-dialog > .modal-content {
		background-color: rgba(33,33,33,0.3);	
	}
	#modalPromo .modal-dialog > .modal-content > .modal-body {
		padding: 20px !important;
	}
	#modalPromo .modal-dialog > .modal-content > .modal-body > .close {
		position: absolute;
		right: 0px;
		top: 0px;
	}
	</style>
	<div id="modalPromo" class="modal fade">
		<div class="modal-dialog" style="<?= ( isset($_promo['lb']['style']) && $_promo['lb']['style'] ) ? $_promo['lb']['style'] : 'width: 75%;'; ?>">
			<div class="modal-content">
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times-circle-o"></i></button>
					<?
					if ( isset($_promo['lb']['imagen']) && $_promo['lb']['imagen'] )
					{
						if (isset($_promo['lb']['url']) && $_promo['lb']['url'])
							echo '<a href="#"'. ( ( $_promo['lb']['url'] == 'close' ) ? ' rel="closeModal"' : '' ) .'><img src="'.$_promo['lb']['imagen'].'" width="100%" /></a>';
						else
							echo '<img src="'.$_promo['lb']['imagen'].'" width="100%" />';
					}
					?>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<? endif; ?>
	<script type="text/javascript">
	$(document).ready(function() {
		<? if ( isset($_promo['lb']['open']) && $_promo['lb']['open'] ) : ?>
		if ( $('#modalPromo').length ) {
			$('#modalPromo').modal('show');
		}
		<? endif; ?>
		<? if ( isset($_promo['header']['url']) && $_promo['header']['url'] ) : ?>
		$('img[rel="promoHeader"]').css({ cursor: 'pointer' });
		$(document).on('click', 'img[rel="promoHeader"]', function() {
			<? if ( $_promo['header']['url'] == '#modalPromo' ) : ?>
			$('#modalPromo').modal('show');
			<? else : ?>
			location.href = '<?= $_promo["header"]["url"]; ?>';
			<? endif; ?>
		});
		<? endif; ?>
		$(document).on('click','#modalPromo a[rel="closeModal"]',function(e) {
			e.preventDefault();
			$('#modalPromo').modal('hide');
		});
	});
	</script>
<? endif; ?>